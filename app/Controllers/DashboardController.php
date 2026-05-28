<?php
// app/Controllers/DashboardController.php

namespace App\Controllers;

use App\Models\User;
use App\Models\Event;
use App\Models\Notice;
use App\Models\Message;
use Core\Auth;
use Core\DB;

class DashboardController
{
    public function index(): void
    {
        $user = Auth::user();
        $userModel = new User();
        $eventModel = new Event();
        $noticeModel = new Notice();
        $messageModel = new Message();

        $stats = $userModel->getStatistics();
        $upcomingEvents = $eventModel->getUpcoming(3);
        $recentNotices = $noticeModel->getRecent(5);
        $unreadMessages = $messageModel->getUnreadCount($user['id']);

        $db = DB::getInstance();
        $onlineMembers = $db->query(
            "SELECT u.id, p.full_name_en, p.avatar, p.profession
             FROM users u
             LEFT JOIN profiles p ON p.user_id = u.id
             WHERE u.is_online = 1 AND u.last_active_at > DATE_SUB(NOW(), INTERVAL 5 MINUTE) AND u.id != ?
             ORDER BY u.last_active_at DESC
             LIMIT 10",
            [$user['id']]
        );

        $recentActivities = $db->query(
            "SELECT * FROM activity_log WHERE user_id = ? ORDER BY created_at DESC LIMIT 10",
            [$user['id']]
        );

        view('dashboard.index', [
            'stats'            => $stats,
            'upcomingEvents'   => $upcomingEvents,
            'recentNotices'    => $recentNotices,
            'unreadMessages'   => $unreadMessages,
            'onlineMembers'    => $onlineMembers,
            'recentActivities' => $recentActivities,
        ]);
    }

    public function profile(): void
    {
        $user = Auth::user();
        $profileModel = new \App\Models\Profile();
        $profile = $profileModel->getFullProfile($user['id']);

        view('dashboard.profile', ['profile' => $profile]);
    }

    public function editProfile(): void
    {
        $user = Auth::user();
        $profileModel = new \App\Models\Profile();
        $profile = $profileModel->getFullProfile($user['id']);

        $db = DB::getInstance();
        $batches = $db->query("SELECT * FROM batches WHERE is_active = 1");

        view('dashboard.edit-profile', ['profile' => $profile, 'batches' => $batches]);
    }

    public function updateProfile(): void
    {
        $user = Auth::user();
        $profileModel = new \App\Models\Profile();

        $profileData = [
            'full_name_bn'     => sanitize($_POST['full_name_bn'] ?? ''),
            'full_name_en'     => sanitize($_POST['full_name_en'] ?? ''),
            'blood_group'      => sanitize($_POST['blood_group'] ?? '') ?: null,
            'date_of_birth'    => sanitize($_POST['date_of_birth'] ?? '') ?: null,
            'whatsapp'         => sanitize($_POST['whatsapp'] ?? '') ?: null,
            'address_present'  => sanitize($_POST['address_present'] ?? '') ?: null,
            'address_permanent'=> sanitize($_POST['address_permanent'] ?? '') ?: null,
            'district'         => sanitize($_POST['district'] ?? '') ?: null,
            'profession'       => sanitize($_POST['profession'] ?? '') ?: null,
            'workplace'        => sanitize($_POST['workplace'] ?? '') ?: null,
            'bio'              => sanitize($_POST['bio'] ?? '') ?: null,
            'facebook_url'     => sanitize($_POST['facebook_url'] ?? '') ?: null,
            'linkedin_url'     => sanitize($_POST['linkedin_url'] ?? '') ?: null,
            'hide_phone'       => isset($_POST['hide_phone']) ? 1 : 0,
            'hide_email'       => isset($_POST['hide_email']) ? 1 : 0,
        ];

        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $config = require dirname(__DIR__, 2) . '/config/app.php';
            $avatar = uploadFile($_FILES['avatar'], 'avatars', $config['allowed_image_types']);
            if ($avatar) {
                $old = $profileModel->findByUserId($user['id']);
                if ($old && $old['avatar']) {
                    deleteFile($old['avatar']);
                }
                $profileData['avatar'] = $avatar;
            }
        }

        if (isset($_FILES['cover_photo']) && $_FILES['cover_photo']['error'] === UPLOAD_ERR_OK) {
            $config = require dirname(__DIR__, 2) . '/config/app.php';
            $cover = uploadFile($_FILES['cover_photo'], 'covers', $config['allowed_image_types']);
            if ($cover) {
                $old = $profileModel->findByUserId($user['id']);
                if ($old && $old['cover_photo']) {
                    deleteFile($old['cover_photo']);
                }
                $profileData['cover_photo'] = $cover;
            }
        }

        $profileModel->update($user['id'], $profileData);

        \Core\Session::flash('success', 'Profile updated successfully!');
        redirect('/dashboard/profile');
    }

    public function changePassword(): void
    {
        view('dashboard.change-password');
    }

    public function updatePassword(): void
    {
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmation = $_POST['password_confirmation'] ?? '';

        if (empty($currentPassword) || empty($newPassword) || empty($confirmation)) {
            \Core\Session::flash('error', 'All fields are required.');
            back();
            return;
        }

        if (strlen($newPassword) < 6) {
            \Core\Session::flash('error', 'New password must be at least 6 characters.');
            back();
            return;
        }

        if ($newPassword !== $confirmation) {
            \Core\Session::flash('error', 'New passwords do not match.');
            back();
            return;
        }

        $user = Auth::user();
        $db = DB::getInstance();
        $userData = $db->queryOne("SELECT password FROM users WHERE id = ?", [$user['id']]);

        if (!password_verify($currentPassword, $userData['password'])) {
            \Core\Session::flash('error', 'Current password is incorrect.');
            back();
            return;
        }

        $userModel = new User();
        $userModel->changePassword($user['id'], $newPassword);

        \Core\Session::flash('success', 'Password changed successfully!');
        redirect('/dashboard');
    }
}
