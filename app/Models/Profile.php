<?php
// app/Models/Profile.php

namespace App\Models;

use Core\DB;

class Profile
{
    private DB $db;

    public function __construct()
    {
        $this->db = DB::getInstance();
    }

    public function create(array $data): int
    {
        return $this->db->insert('profiles', $data);
    }

    public function findByUserId(int $userId): ?array
    {
        return $this->db->queryOne("SELECT * FROM profiles WHERE user_id = ?", [$userId]);
    }

    public function update(int $userId, array $data): int
    {
        $existing = $this->findByUserId($userId);
        if ($existing) {
            return $this->db->update('profiles', $data, ['user_id' => $userId]);
        } else {
            $data['user_id'] = $userId;
            return $this->db->insert('profiles', $data);
        }
    }

    public function updateAvatar(int $userId, string $avatar): int
    {
        return $this->update($userId, ['avatar' => $avatar]);
    }

    public function updateCover(int $userId, string $cover): int
    {
        return $this->update($userId, ['cover_photo' => $cover]);
    }

    public function getFullProfile(int $userId): ?array
    {
        $profile = $this->db->queryOne(
            "SELECT u.*, p.*, r.name as role_name
             FROM users u
             LEFT JOIN profiles p ON p.user_id = u.id
             LEFT JOIN roles r ON r.id = u.role_id
             WHERE u.id = ?",
            [$userId]
        );

        if (!$profile) return null;

        $batches = $this->db->query(
            "SELECT b.*, ub.roll_number, ub.registration_number, ub.gpa
             FROM user_batches ub
             JOIN batches b ON b.id = ub.batch_id
             WHERE ub.user_id = ?",
            [$userId]
        );

        $profile['batches'] = $batches;
        return $profile;
    }
}
