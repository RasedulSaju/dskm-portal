<?php
// app/Controllers/AdminController.php

namespace App\Controllers;

use App\Models\User;
use App\Models\Event;
use App\Models\Notice;
use App\Models\Smoronika;
use App\Models\SupportRequest;
use Core\Auth;
use Core\Session;
use Core\DB;

class AdminController
{
    private User $userModel;
    private Event $eventModel;
    private Notice $noticeModel;
    private Smoronika $smuronikaModel;
    private SupportRequest $supportModel;
    private DB $db;

    public function __construct()
    {
        $this->userModel = new User();
        $this->eventModel = new Event();
        $this->noticeModel = new Notice();
        $this->smuronikaModel = new Smoronika();
        $this->supportModel = new SupportRequest();
        $this->db = DB::getInstance();
    }

    public function dashboard(): void
    {
        $stats = [
            'total_members' => $this->db->queryOne("SELECT COUNT(*) as cnt FROM users WHERE status = 'active'")['cnt'],
            'pending_users' => $this->db->queryOne("SELECT COUNT(*) as cnt FROM users WHERE status = 'pending'")['cnt'],
            'total_events' => $this->db->queryOne("SELECT COUNT(*) as cnt FROM events WHERE status = 'published'")['cnt'],
            'pending_events' => $this->db->queryOne("SELECT COUNT(*) as cnt FROM events WHERE status = 'draft'")['cnt'],
            'total_notices' => $this->db->queryOne("SELECT COUNT(*) as cnt FROM notices WHERE status = 'published'")['cnt'],
            'pending_smoronika' => $this->db->queryOne("SELECT COUNT(*) as cnt FROM smoronika WHERE status = 'pending'")['cnt'],
            'pending_support' => $this->db->queryOne("SELECT COUNT(*) as cnt FROM support_requests WHERE status = 'pending'")['cnt'],
            'total_gallery' => $this->db->queryOne("SELECT COUNT(*) as cnt FROM galleries WHERE status = 'active'")['cnt'],
        ];

        $userGrowth = $this->db->query("
            SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count
            FROM users
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
            GROUP BY month
            ORDER BY month ASC
        ");

        $batchDistribution = $this->db->query("
            SELECT b.name, b.year, COUNT(DISTINCT ub.user_id) as count
            FROM batches b
            LEFT JOIN user_batches ub ON ub.batch_id = b.id
            LEFT JOIN users u ON u.id = ub.user_id AND u.status = 'active'
            GROUP BY b.id
            ORDER BY b.year DESC
        ");

        $recentActivity = $this->db->query("
            SELECT al.*, p.full_name_en, p.avatar
            FROM activity_log al
            LEFT JOIN users u ON u.id = al.user_id
            LEFT JOIN profiles p ON p.user_id = u.id
            ORDER BY al.created_at DESC
            LIMIT 20
        ");

        view('admin.dashboard', [
            'stats' => $stats,
            'userGrowth' => $userGrowth,
            'batchDistribution' => $batchDistribution,
            'recentActivity' => $recentActivity,
        ]);
    }

    // ===== USER MANAGEMENT =====

    public function users(): void
    {
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $status = sanitize($_GET['status'] ?? '');
        $perPage = 50;

        $where = $status ? "WHERE u.status = ?" : "";
        $params = $status ? [$status] : [];

        $total = $this->db->queryOne("SELECT COUNT(*) as cnt FROM users u {$where}", $params)['cnt'];
        $pagination = paginate($total, $perPage, $page);

        $params[] = $perPage;
        $params[] = $pagination['offset'];

        $users = $this->db->query("
            SELECT u.*, p.full_name_en, p.avatar, r.name as role_name,
                   GROUP_CONCAT(b.name SEPARATOR ', ') as batches
            FROM users u
            LEFT JOIN profiles p ON p.user_id = u.id
            LEFT JOIN roles r ON r.id = u.role_id
            LEFT JOIN user_batches ub ON ub.user_id = u.id
            LEFT JOIN batches b ON b.id = ub.batch_id
            {$where}
            GROUP BY u.id
            ORDER BY u.created_at DESC
            LIMIT ? OFFSET ?
        ", $params);

        view('admin.users.index', [
            'users' => $users,
            'pagination' => $pagination,
            'status' => $status,
        ]);
    }

    public function pendingApprovals(): void
    {
        $pendingUsers = $this->userModel->getPendingApprovals();
        view('admin.users.pending', ['users' => $pendingUsers]);
    }

    public function approveUser(array $params): void
    {
        $userId = (int) $params['id'];
        $this->userModel->updateStatus($userId, 'active');

        if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            json(['success' => true, 'message' => 'User approved successfully']);
        } else {
            redirect('/admin/users/pending');
        }
    }

    public function rejectUser(array $params): void
    {
        $userId = (int) $params['id'];
        $this->userModel->updateStatus($userId, 'rejected');

        if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            json(['success' => true, 'message' => 'User rejected']);
        } else {
            redirect('/admin/users/pending');
        }
    }

    public function suspendUser(array $params): void
    {
        $userId = (int) $params['id'];
        $this->userModel->updateStatus($userId, 'suspended');

        if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            json(['success' => true, 'message' => 'User suspended']);
        } else {
            redirect('/admin/users');
        }
    }

    public function deleteUser(array $params): void
    {
        $userId = (int) $params['id'];
        
        if ($userId === Auth::id()) {
            json(['success' => false, 'message' => 'Cannot delete your own account'], 400);
            return;
        }

        $this->userModel->delete($userId);
        json(['success' => true, 'message' => 'User deleted successfully']);
    }

    public function changeUserRole(array $params): void
    {
        $userId = (int) $params['id'];
        $roleId = (int) ($_POST['role_id'] ?? 0);

        if (!in_array($roleId, [1, 2, 3, 4])) {
            json(['success' => false, 'message' => 'Invalid role'], 400);
            return;
        }

        $this->userModel->update($userId, ['role_id' => $roleId]);
        json(['success' => true, 'message' => 'User role updated']);
    }

    // ===== EVENT MANAGEMENT =====

    public function events(): void
    {
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $status = sanitize($_GET['status'] ?? 'published');
        $perPage = 20;

        $total = $this->eventModel->count($status);
        $pagination = paginate($total, $perPage, $page);
        $events = $this->eventModel->getAll($status, $perPage, $pagination['offset']);

        view('admin.events.index', [
            'events' => $events,
            'pagination' => $pagination,
            'status' => $status,
        ]);
    }

    public function approveEvent(array $params): void
    {
        $eventId = (int) $params['id'];
        $this->eventModel->approve($eventId, Auth::id());
        
        $event = $this->eventModel->findById($eventId);
        sendNotification($event['created_by'], 'approval', 'Event Approved', "Your event '{$event['title']}' has been approved!", "/events/{$eventId}");

        json(['success' => true, 'message' => 'Event approved']);
    }

    public function deleteEvent(array $params): void
    {
        $eventId = (int) $params['id'];
        $event = $this->eventModel->findById($eventId);
        
        if ($event && $event['banner']) {
            deleteFile($event['banner']);
        }
        
        $this->eventModel->delete($eventId);
        json(['success' => true, 'message' => 'Event deleted']);
    }

    // ===== NOTICE MANAGEMENT =====

    public function notices(): void
    {
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = 20;

        $total = $this->noticeModel->count();
        $pagination = paginate($total, $perPage, $page);
        $notices = $this->noticeModel->getAll('', $perPage, $pagination['offset']);

        view('admin.notices.index', [
            'notices' => $notices,
            'pagination' => $pagination,
        ]);
    }

    // ===== SMORONIKA MANAGEMENT =====

    public function smoronika(): void
    {
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $status = sanitize($_GET['status'] ?? 'pending');
        $perPage = 20;

        $total = $this->smuronikaModel->count($status);
        $pagination = paginate($total, $perPage, $page);
        $articles = $this->smuronikaModel->getAll($status, $perPage, $pagination['offset']);

        view('admin.smoronika.index', [
            'articles' => $articles,
            'pagination' => $pagination,
            'status' => $status,
        ]);
    }

    public function approveSmoronika(array $params): void
    {
        $id = (int) $params['id'];
        $this->smuronikaModel->approve($id, Auth::id());
        
        $article = $this->smuronikaModel->findById($id);
        sendNotification($article['author_id'], 'approval', 'Article Approved', "Your article '{$article['title']}' has been published!", "/smoronika/{$id}");

        json(['success' => true, 'message' => 'Article approved']);
    }

    public function rejectSmoronika(array $params): void
    {
        $id = (int) $params['id'];
        $this->smuronikaModel->reject($id);
        
        $article = $this->smuronikaModel->findById($id);
        sendNotification($article['author_id'], 'rejection', 'Article Rejected', "Your article '{$article['title']}' was rejected.", null);

        json(['success' => true, 'message' => 'Article rejected']);
    }

    // ===== SUPPORT REQUEST MANAGEMENT =====

    public function supportRequests(): void
    {
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $status = sanitize($_GET['status'] ?? 'pending');
        $category = sanitize($_GET['category'] ?? '');
        $perPage = 20;

        $total = $this->supportModel->count($status, $category);
        $pagination = paginate($total, $perPage, $page);
        $requests = $this->supportModel->getAll($status, $category, $perPage, $pagination['offset']);

        view('admin.support.index', [
            'requests' => $requests,
            'pagination' => $pagination,
            'status' => $status,
            'category' => $category,
        ]);
    }

    public function viewSupportRequest(array $params): void
    {
        $id = (int) $params['id'];
        $request = $this->supportModel->findById($id);

        if (!$request) {
            Session::flash('error', 'Request not found.');
            redirect('/admin/support');
            return;
        }

        view('admin.support.show', ['request' => $request]);
    }

    public function updateSupportStatus(array $params): void
    {
        $id = (int) $params['id'];
        $status = sanitize($_POST['status'] ?? '');
        $adminNote = sanitize($_POST['admin_note'] ?? '') ?: null;

        if (!in_array($status, ['reviewing', 'approved', 'rejected', 'resolved'])) {
            json(['success' => false, 'message' => 'Invalid status'], 400);
            return;
        }

        $this->supportModel->updateStatus($id, $status, Auth::id(), $adminNote);
        
        $request = $this->supportModel->findById($id);
        sendNotification($request['user_id'], 'support_update', 'Support Request Updated', "Your support request status has been updated to: {$status}", "/support/{$id}");

        json(['success' => true, 'message' => 'Status updated successfully']);
    }

    // ===== GALLERY MANAGEMENT =====

    public function gallery(): void
    {
        $galleryModel = new \App\Models\Gallery();
        $albums = $galleryModel->getAlbums('', 50, 0);

        view('admin.gallery.index', ['albums' => $albums]);
    }

    // ===== REPORTS =====

    public function reports(): void
    {
        $memberStats = $this->userModel->getStatistics();
        $supportStats = $this->supportModel->getStatistics();

        $eventStats = $this->db->queryOne("
            SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN status = 'published' THEN 1 ELSE 0 END) as published,
                SUM(CASE WHEN status = 'draft' THEN 1 ELSE 0 END) as draft,
                SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled
            FROM events
        ");

        $contentStats = $this->db->queryOne("
            SELECT 
                (SELECT COUNT(*) FROM notices WHERE status = 'published') as notices,
                (SELECT COUNT(*) FROM smoronika WHERE status = 'published') as smoronika,
                (SELECT COUNT(*) FROM galleries WHERE status = 'active') as galleries,
                (SELECT COUNT(*) FROM memorials) as memorials
        ");

        view('admin.reports', [
            'memberStats' => $memberStats,
            'supportStats' => $supportStats,
            'eventStats' => $eventStats,
            'contentStats' => $contentStats,
        ]);
    }

    // ===== SETTINGS =====

    public function settings(): void
    {
        $settings = $this->db->query("SELECT * FROM site_settings ORDER BY group_name, key_name");
        
        $grouped = [];
        foreach ($settings as $setting) {
            $grouped[$setting['group_name']][] = $setting;
        }

        view('admin.settings', ['settings' => $grouped]);
    }

    public function updateSettings(): void
    {
        foreach ($_POST as $key => $value) {
            if (strpos($key, 'setting_') === 0) {
                $keyName = substr($key, 8);
                $this->db->execute(
                    "UPDATE site_settings SET value = ? WHERE key_name = ?",
                    [sanitize($value), $keyName]
                );
            }
        }

        Session::flash('success', 'Settings updated successfully!');
        redirect('/admin/settings');
    }
}
