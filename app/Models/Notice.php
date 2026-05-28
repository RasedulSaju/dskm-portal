<?php
// app/Models/Notice.php

namespace App\Models;

use Core\DB;

class Notice
{
    private DB $db;

    public function __construct()
    {
        $this->db = DB::getInstance();
    }

    public function create(array $data): int
    {
        $data['slug'] = slugify($data['title']) . '-' . time();
        return $this->db->insert('notices', $data);
    }

    public function findById(int $id): ?array
    {
        return $this->db->queryOne(
            "SELECT n.*, p.full_name_en as author_name, p.avatar as author_avatar
             FROM notices n
             LEFT JOIN users u ON u.id = n.created_by
             LEFT JOIN profiles p ON p.user_id = u.id
             WHERE n.id = ?",
            [$id]
        );
    }

    public function findBySlug(string $slug): ?array
    {
        return $this->db->queryOne(
            "SELECT n.*, p.full_name_en as author_name, p.avatar as author_avatar
             FROM notices n
             LEFT JOIN users u ON u.id = n.created_by
             LEFT JOIN profiles p ON p.user_id = u.id
             WHERE n.slug = ?",
            [$slug]
        );
    }

    public function update(int $id, array $data): int
    {
        return $this->db->update('notices', $data, ['id' => $id]);
    }

    public function delete(int $id): int
    {
        return $this->db->delete('notices', ['id' => $id]);
    }

    public function getAll(string $category = '', int $limit = 12, int $offset = 0): array
    {
        if ($category) {
            return $this->db->query(
                "SELECT n.*, p.full_name_en as author_name
                 FROM notices n
                 LEFT JOIN users u ON u.id = n.created_by
                 LEFT JOIN profiles p ON p.user_id = u.id
                 WHERE n.status = 'published' AND n.category = ?
                 ORDER BY n.is_pinned DESC, n.created_at DESC
                 LIMIT ? OFFSET ?",
                [$category, $limit, $offset]
            );
        }

        return $this->db->query(
            "SELECT n.*, p.full_name_en as author_name
             FROM notices n
             LEFT JOIN users u ON u.id = n.created_by
             LEFT JOIN profiles p ON p.user_id = u.id
             WHERE n.status = 'published'
             ORDER BY n.is_pinned DESC, n.created_at DESC
             LIMIT ? OFFSET ?",
            [$limit, $offset]
        );
    }

    public function getRecent(int $limit = 5): array
    {
        return $this->db->query(
            "SELECT id, title, created_at, category
             FROM notices
             WHERE status = 'published'
             ORDER BY created_at DESC
             LIMIT ?",
            [$limit]
        );
    }

    public function count(string $category = ''): int
    {
        if ($category) {
            $result = $this->db->queryOne(
                "SELECT COUNT(*) as cnt FROM notices WHERE status = 'published' AND category = ?",
                [$category]
            );
        } else {
            $result = $this->db->queryOne("SELECT COUNT(*) as cnt FROM notices WHERE status = 'published'");
        }
        return (int) $result['cnt'];
    }

    public function incrementViews(int $id): void
    {
        $this->db->execute("UPDATE notices SET views = views + 1 WHERE id = ?", [$id]);
    }

    public function addComment(int $noticeId, int $userId, string $comment): int
    {
        return $this->db->insert('notice_comments', [
            'notice_id' => $noticeId,
            'user_id'   => $userId,
            'comment'   => $comment,
        ]);
    }

    public function getComments(int $noticeId): array
    {
        return $this->db->query(
            "SELECT nc.*, p.full_name_en, p.avatar
             FROM notice_comments nc
             LEFT JOIN users u ON u.id = nc.user_id
             LEFT JOIN profiles p ON p.user_id = u.id
             WHERE nc.notice_id = ?
             ORDER BY nc.created_at DESC",
            [$noticeId]
        );
    }
}
