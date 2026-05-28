<?php
// app/Controllers/MembersController.php

namespace App\Controllers;

use App\Models\User;
use App\Models\Profile;
use Core\DB;

class MembersController
{
    private User $userModel;
    private Profile $profileModel;

    public function __construct()
    {
        $this->userModel = new User();
        $this->profileModel = new Profile();
    }

    public function index(): void
    {
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = 12;

        $filters = [
            'batch'       => $_GET['batch'] ?? '',
            'blood_group' => $_GET['blood_group'] ?? '',
            'district'    => $_GET['district'] ?? '',
            'profession'  => $_GET['profession'] ?? '',
            'search'      => $_GET['search'] ?? '',
        ];

        $total = $this->userModel->count($filters);
        $pagination = paginate($total, $perPage, $page);
        $members = $this->userModel->getAll($filters, $perPage, $pagination['offset']);

        $db = DB::getInstance();
        $batches = $db->query("SELECT * FROM batches WHERE is_active = 1");
        $districts = $db->query("SELECT DISTINCT district FROM profiles WHERE district IS NOT NULL ORDER BY district");

        view('members.index', [
            'members'    => $members,
            'pagination' => $pagination,
            'filters'    => $filters,
            'batches'    => $batches,
            'districts'  => $districts,
        ]);
    }

    public function show(array $params): void
    {
        $userId = (int) $params['id'];
        $profile = $this->profileModel->getFullProfile($userId);

        if (!$profile || $profile['status'] !== 'active') {
            \Core\Session::flash('error', 'Member not found.');
            redirect('/members');
            return;
        }

        view('members.show', ['profile' => $profile]);
    }

    public function search(): void
    {
        $query = sanitize($_GET['q'] ?? '');
        
        if (strlen($query) < 2) {
            json(['results' => []]);
            return;
        }

        $db = DB::getInstance();
        $results = $db->query(
            "SELECT u.id, p.full_name_en, p.full_name_bn, p.avatar, p.profession
             FROM users u
             LEFT JOIN profiles p ON p.user_id = u.id
             WHERE u.status = 'active' 
               AND (p.full_name_en LIKE ? OR p.full_name_bn LIKE ? OR u.username LIKE ?)
             LIMIT 10",
            ["%{$query}%", "%{$query}%", "%{$query}%"]
        );

        json(['results' => $results]);
    }
}
