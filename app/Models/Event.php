<?php
// app/Models/Event.php

namespace App\Models;

use Core\DB;

class Event
{
    private DB $db;

    public function __construct()
    {
        $this->db = DB::getInstance();
    }

    public function create(array $data): int
    {
        $data['slug'] = slugify($data['title']) . '-' . time();
        return $this->db->insert('events', $data);
    }

    public function findById(int $id): ?array
    {
        $event = $this->db->queryOne(
            "SELECT e.*, 
                    p1.full_name_en as creator_name, p1.avatar as creator_avatar,
                    p2.full_name_en as approver_name,
                    (SELECT COUNT(*) FROM event_rsvp WHERE event_id = e.id AND status = 'going') as going_count,
                    (SELECT COUNT(*) FROM event_rsvp WHERE event_id = e.id AND status = 'maybe') as maybe_count
             FROM events e
             LEFT JOIN users u1 ON u1.id = e.created_by
             LEFT JOIN profiles p1 ON p1.user_id = u1.id
             LEFT JOIN users u2 ON u2.id = e.approved_by
             LEFT JOIN profiles p2 ON p2.user_id = u2.id
             WHERE e.id = ?",
            [$id]
        );

        return $event;
    }

    public function findBySlug(string $slug): ?array
    {
        return $this->db->queryOne("SELECT * FROM events WHERE slug = ?", [$slug]);
    }

    public function update(int $id, array $data): int
    {
        return $this->db->update('events', $data, ['id' => $id]);
    }

    public function delete(int $id): int
    {
        return $this->db->delete('events', ['id' => $id]);
    }

    public function getAll(string $status = 'published', int $limit = 12, int $offset = 0): array
    {
        return $this->db->query(
            "SELECT e.*, p.full_name_en as creator_name,
                    (SELECT COUNT(*) FROM event_rsvp WHERE event_id = e.id AND status = 'going') as going_count
             FROM events e
             LEFT JOIN users u ON u.id = e.created_by
             LEFT JOIN profiles p ON p.user_id = u.id
             WHERE e.status = ?
             ORDER BY e.event_date ASC
             LIMIT ? OFFSET ?",
            [$status, $limit, $offset]
        );
    }

    public function getUpcoming(int $limit = 5): array
    {
        return $this->db->query(
            "SELECT e.*, p.full_name_en as creator_name,
                    (SELECT COUNT(*) FROM event_rsvp WHERE event_id = e.id AND status = 'going') as going_count
             FROM events e
             LEFT JOIN users u ON u.id = e.created_by
             LEFT JOIN profiles p ON p.user_id = u.id
             WHERE e.status = 'published' AND e.event_date > NOW()
             ORDER BY e.event_date ASC
             LIMIT ?",
            [$limit]
        );
    }

    public function count(string $status = 'published'): int
    {
        $result = $this->db->queryOne("SELECT COUNT(*) as cnt FROM events WHERE status = ?", [$status]);
        return (int) $result['cnt'];
    }

    public function rsvp(int $eventId, int $userId, string $status): void
    {
        $existing = $this->db->queryOne(
            "SELECT id FROM event_rsvp WHERE event_id = ? AND user_id = ?",
            [$eventId, $userId]
        );

        if ($existing) {
            $this->db->update('event_rsvp', ['status' => $status], ['event_id' => $eventId, 'user_id' => $userId]);
        } else {
            $this->db->insert('event_rsvp', [
                'event_id' => $eventId,
                'user_id'  => $userId,
                'status'   => $status,
            ]);
        }
    }

    public function getUserRsvp(int $eventId, int $userId): ?string
    {
        $result = $this->db->queryOne(
            "SELECT status FROM event_rsvp WHERE event_id = ? AND user_id = ?",
            [$eventId, $userId]
        );
        return $result['status'] ?? null;
    }

    public function getAttendees(int $eventId, string $status = 'going'): array
    {
        return $this->db->query(
            "SELECT u.id, p.full_name_en, p.full_name_bn, p.avatar, p.profession, er.status
             FROM event_rsvp er
             JOIN users u ON u.id = er.user_id
             LEFT JOIN profiles p ON p.user_id = u.id
             WHERE er.event_id = ? AND er.status = ?
             ORDER BY er.created_at DESC",
            [$eventId, $status]
        );
    }

    public function approve(int $eventId, int $approvedBy): int
    {
        return $this->db->update('events', [
            'status'      => 'published',
            'approved_by' => $approvedBy,
            'approved_at' => date('Y-m-d H:i:s'),
        ], ['id' => $eventId]);
    }
}
