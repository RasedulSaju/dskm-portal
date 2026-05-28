<?php
// app/Models/SupportRequest.php

namespace App\Models;

use Core\DB;

class SupportRequest
{
    private DB $db;

    public function __construct()
    {
        $this->db = DB::getInstance();
    }

    public function create(array $data): int
    {
        return $this->db->insert('support_requests', $data);
    }

    public function findById(int $id): ?array
    {
        return $this->db->queryOne(
            "SELECT sr.*, p.full_name_en as requester_name, p.avatar as requester_avatar, p.mobile,
                    pr.full_name_en as reviewer_name
             FROM support_requests sr
             LEFT JOIN users u ON u.id = sr.user_id
             LEFT JOIN profiles p ON p.user_id = u.id
             LEFT JOIN users ur ON ur.id = sr.reviewed_by
             LEFT JOIN profiles pr ON pr.user_id = ur.id
             WHERE sr.id = ?",
            [$id]
        );
    }

    public function getByUser(int $userId, int $limit = 50): array
    {
        return $this->db->query(
            "SELECT * FROM support_requests WHERE user_id = ? ORDER BY created_at DESC LIMIT ?",
            [$userId, $limit]
        );
    }

    public function getAll(string $status = '', string $category = '', int $limit = 50, int $offset = 0): array
    {
        $where = [];
        $params = [];

        if ($status) {
            $where[] = 'sr.status = ?';
            $params[] = $status;
        }

        if ($category) {
            $where[] = 'sr.category = ?';
            $params[] = $category;
        }

        $whereClause = $where ? 'WHERE ' . implode(' AND ', $where) : '';

        $sql = "SELECT sr.*, p.full_name_en as requester_name, p.avatar as requester_avatar
                FROM support_requests sr
                LEFT JOIN users u ON u.id = sr.user_id
                LEFT JOIN profiles p ON p.user_id = u.id
                {$whereClause}
                ORDER BY sr.created_at DESC
                LIMIT ? OFFSET ?";

        $params[] = $limit;
        $params[] = $offset;

        return $this->db->query($sql, $params);
    }

    public function count(string $status = '', string $category = ''): int
    {
        $where = [];
        $params = [];

        if ($status) {
            $where[] = 'status = ?';
            $params[] = $status;
        }

        if ($category) {
            $where[] = 'category = ?';
            $params[] = $category;
        }

        $whereClause = $where ? 'WHERE ' . implode(' AND ', $where) : '';

        $result = $this->db->queryOne("SELECT COUNT(*) as cnt FROM support_requests {$whereClause}", $params);
        return (int) $result['cnt'];
    }

    public function update(int $id, array $data): int
    {
        return $this->db->update('support_requests', $data, ['id' => $id]);
    }

    public function updateStatus(int $id, string $status, int $reviewedBy, ?string $adminNote = null): int
    {
        return $this->db->update('support_requests', [
            'status'      => $status,
            'reviewed_by' => $reviewedBy,
            'reviewed_at' => date('Y-m-d H:i:s'),
            'admin_note'  => $adminNote,
        ], ['id' => $id]);
    }

    public function delete(int $id): int
    {
        return $this->db->delete('support_requests', ['id' => $id]);
    }

    public function getStatistics(): array
    {
        return $this->db->queryOne("
            SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = 'reviewing' THEN 1 ELSE 0 END) as reviewing,
                SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved,
                SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected,
                SUM(CASE WHEN status = 'resolved' THEN 1 ELSE 0 END) as resolved
            FROM support_requests
        ");
    }
}
