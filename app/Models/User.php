<?php
// app/Models/User.php

namespace App\Models;

use Core\DB;

class User
{
    private DB $db;

    public function __construct()
    {
        $this->db = DB::getInstance();
    }

    public function create(array $data): int
    {
        $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 12]);
        
        return $this->db->insert('users', [
            'username' => $data['username'],
            'email'    => $data['email'] ?? null,
            'mobile'   => $data['mobile'],
            'password' => $hashedPassword,
            'role_id'  => $data['role_id'] ?? 4,
            'status'   => $data['status'] ?? 'pending',
        ]);
    }

    public function findById(int $id): ?array
    {
        return $this->db->queryOne(
            "SELECT u.*, p.*, r.name as role_name, r.slug as role_slug
             FROM users u
             LEFT JOIN profiles p ON p.user_id = u.id
             LEFT JOIN roles r ON r.id = u.role_id
             WHERE u.id = ?",
            [$id]
        );
    }

    public function findByUsername(string $username): ?array
    {
        return $this->db->queryOne("SELECT * FROM users WHERE username = ?", [$username]);
    }

    public function findByEmail(string $email): ?array
    {
        return $this->db->queryOne("SELECT * FROM users WHERE email = ?", [$email]);
    }

    public function findByMobile(string $mobile): ?array
    {
        return $this->db->queryOne("SELECT * FROM users WHERE mobile = ?", [$mobile]);
    }

    public function update(int $id, array $data): int
    {
        return $this->db->update('users', $data, ['id' => $id]);
    }

    public function updateStatus(int $id, string $status): int
    {
        return $this->db->update('users', ['status' => $status], ['id' => $id]);
    }

    public function delete(int $id): int
    {
        return $this->db->delete('users', ['id' => $id]);
    }

    public function getAll(array $filters = [], int $limit = 12, int $offset = 0): array
    {
        $where = ['u.status = ?'];
        $params = ['active'];

        if (!empty($filters['batch'])) {
            $where[] = 'ub.batch_id = ?';
            $params[] = $filters['batch'];
        }

        if (!empty($filters['blood_group'])) {
            $where[] = 'p.blood_group = ?';
            $params[] = $filters['blood_group'];
        }

        if (!empty($filters['district'])) {
            $where[] = 'p.district = ?';
            $params[] = $filters['district'];
        }

        if (!empty($filters['profession'])) {
            $where[] = 'p.profession LIKE ?';
            $params[] = '%' . $filters['profession'] . '%';
        }

        if (!empty($filters['search'])) {
            $where[] = '(p.full_name_en LIKE ? OR p.full_name_bn LIKE ? OR u.username LIKE ?)';
            $search = '%' . $filters['search'] . '%';
            $params[] = $search;
            $params[] = $search;
            $params[] = $search;
        }

        $whereClause = implode(' AND ', $where);

        $sql = "SELECT u.id, u.username, u.mobile, u.email, u.last_active_at, u.is_online,
                       p.full_name_en, p.full_name_bn, p.avatar, p.profession, 
                       p.workplace, p.blood_group, p.district,
                       GROUP_CONCAT(b.name SEPARATOR ', ') as batches
                FROM users u
                LEFT JOIN profiles p ON p.user_id = u.id
                LEFT JOIN user_batches ub ON ub.user_id = u.id
                LEFT JOIN batches b ON b.id = ub.batch_id
                WHERE {$whereClause}
                GROUP BY u.id
                ORDER BY u.created_at DESC
                LIMIT ? OFFSET ?";

        $params[] = $limit;
        $params[] = $offset;

        return $this->db->query($sql, $params);
    }

    public function count(array $filters = []): int
    {
        $where = ['u.status = ?'];
        $params = ['active'];

        if (!empty($filters['batch'])) {
            $where[] = 'ub.batch_id = ?';
            $params[] = $filters['batch'];
        }

        if (!empty($filters['blood_group'])) {
            $where[] = 'p.blood_group = ?';
            $params[] = $filters['blood_group'];
        }

        if (!empty($filters['district'])) {
            $where[] = 'p.district = ?';
            $params[] = $filters['district'];
        }

        if (!empty($filters['search'])) {
            $where[] = '(p.full_name_en LIKE ? OR p.full_name_bn LIKE ?)';
            $search = '%' . $filters['search'] . '%';
            $params[] = $search;
            $params[] = $search;
        }

        $whereClause = implode(' AND ', $where);

        $result = $this->db->queryOne(
            "SELECT COUNT(DISTINCT u.id) as cnt FROM users u
             LEFT JOIN profiles p ON p.user_id = u.id
             LEFT JOIN user_batches ub ON ub.user_id = u.id
             WHERE {$whereClause}",
            $params
        );

        return (int) $result['cnt'];
    }

    public function getPendingApprovals(int $limit = 50): array
    {
        return $this->db->query(
            "SELECT u.*, p.full_name_en, p.full_name_bn, p.avatar
             FROM users u
             LEFT JOIN profiles p ON p.user_id = u.id
             WHERE u.status = 'pending'
             ORDER BY u.created_at ASC
             LIMIT ?",
            [$limit]
        );
    }

    public function getStatistics(): array
    {
        $stats = $this->db->queryOne("
            SELECT 
                (SELECT COUNT(*) FROM users WHERE status = 'active') as total_members,
                (SELECT COUNT(*) FROM users WHERE status = 'pending') as pending_approvals,
                (SELECT COUNT(*) FROM users WHERE is_online = 1 AND last_active_at > DATE_SUB(NOW(), INTERVAL 5 MINUTE)) as online_now,
                (SELECT COUNT(*) FROM users WHERE DATE(created_at) = CURDATE()) as new_today
        ");

        $batchStats = $this->db->query("
            SELECT b.name, b.year, COUNT(ub.user_id) as member_count
            FROM batches b
            LEFT JOIN user_batches ub ON ub.batch_id = b.id
            LEFT JOIN users u ON u.id = ub.user_id AND u.status = 'active'
            GROUP BY b.id
            ORDER BY b.year DESC
        ");

        return [
            'totals' => $stats,
            'batches' => $batchStats,
        ];
    }

    public function changePassword(int $userId, string $newPassword): int
    {
        $hashed = password_hash($newPassword, PASSWORD_BCRYPT, ['cost' => 12]);
        return $this->db->update('users', ['password' => $hashed], ['id' => $userId]);
    }
}
