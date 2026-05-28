<?php
// app/Models/Smoronika.php

namespace App\Models;

use Core\DB;

class Smoronika
{
    private DB $db;

    public function __construct()
    {
        $this->db = DB::getInstance();
    }

    public function create(array $data): int
    {
        $data['slug'] = slugify($data['title']) . '-' . time();
        return $this->db->insert('smoronika', $data);
    }

    public function findById(int $id): ?array
    {
        return $this->db->queryOne(
            "SELECT s.*, p.full_name_en as author_name, p.avatar as author_avatar,
                    p2.full_name_en as approver_name
             FROM smoronika s
             LEFT JOIN users u ON u.id = s.author_id
             LEFT JOIN profiles p ON p.user_id = u.id
             LEFT JOIN users u2 ON u2.id = s.approved_by
             LEFT JOIN profiles p2 ON p2.user_id = u2.id
             WHERE s.id = ?",
            [$id]
        );
    }

    public function getAll(string $status = 'published', int $limit = 12, int $offset = 0): array
    {
        return $this->db->query(
            "SELECT s.*, p.full_name_en as author_name, p.avatar as author_avatar
             FROM smoronika s
             LEFT JOIN users u ON u.id = s.author_id
             LEFT JOIN profiles p ON p.user_id = u.id
             WHERE s.status = ?
             ORDER BY s.created_at DESC
             LIMIT ? OFFSET ?",
            [$status, $limit, $offset]
        );
    }

    public function count(string $status = 'published'): int
    {
        $result = $this->db->queryOne("SELECT COUNT(*) as cnt FROM smoronika WHERE status = ?", [$status]);
        return (int) $result['cnt'];
    }

    public function update(int $id, array $data): int
    {
        return $this->db->update('smoronika', $data, ['id' => $id]);
    }

    public function delete(int $id): int
    {
        return $this->db->delete('smoronika', ['id' => $id]);
    }

    public function approve(int $id, int $approvedBy): int
    {
        return $this->db->update('smoronika', [
            'status'      => 'published',
            'approved_by' => $approvedBy,
            'approved_at' => date('Y-m-d H:i:s'),
        ], ['id' => $id]);
    }

    public function reject(int $id): int
    {
        return $this->db->update('smoronika', ['status' => 'rejected'], ['id' => $id]);
    }

    public function incrementViews(int $id): void
    {
        $this->db->execute("UPDATE smoronika SET views = views + 1 WHERE id = ?", [$id]);
    }

    public function getPending(int $limit = 50): array
    {
        return $this->db->query(
            "SELECT s.*, p.full_name_en as author_name, p.avatar as author_avatar
             FROM smoronika s
             LEFT JOIN users u ON u.id = s.author_id
             LEFT JOIN profiles p ON p.user_id = u.id
             WHERE s.status = 'pending'
             ORDER BY s.created_at ASC
             LIMIT ?",
            [$limit]
        );
    }
}
