<?php
// app/Models/Memorial.php

namespace App\Models;

use Core\DB;

class Memorial
{
    private DB $db;

    public function __construct()
    {
        $this->db = DB::getInstance();
    }

    public function create(array $data): int
    {
        return $this->db->insert('memorials', $data);
    }

    public function findById(int $id): ?array
    {
        return $this->db->queryOne(
            "SELECT m.*, b.name as batch_name, p.full_name_en as added_by_name
             FROM memorials m
             LEFT JOIN batches b ON b.id = m.batch_id
             LEFT JOIN users u ON u.id = m.added_by
             LEFT JOIN profiles p ON p.user_id = u.id
             WHERE m.id = ?",
            [$id]
        );
    }

    public function getAll(string $type = '', int $limit = 50): array
    {
        if ($type) {
            return $this->db->query(
                "SELECT m.*, b.name as batch_name
                 FROM memorials m
                 LEFT JOIN batches b ON b.id = m.batch_id
                 WHERE m.type = ?
                 ORDER BY m.date_of_death DESC
                 LIMIT ?",
                [$type, $limit]
            );
        }

        return $this->db->query(
            "SELECT m.*, b.name as batch_name
             FROM memorials m
             LEFT JOIN batches b ON b.id = m.batch_id
             ORDER BY m.date_of_death DESC
             LIMIT ?",
            [$limit]
        );
    }

    public function update(int $id, array $data): int
    {
        return $this->db->update('memorials', $data, ['id' => $id]);
    }

    public function delete(int $id): int
    {
        return $this->db->delete('memorials', ['id' => $id]);
    }

    public function incrementTributes(int $id): void
    {
        $this->db->execute("UPDATE memorials SET tributes = tributes + 1 WHERE id = ?", [$id]);
    }

    public function count(string $type = ''): int
    {
        if ($type) {
            $result = $this->db->queryOne("SELECT COUNT(*) as cnt FROM memorials WHERE type = ?", [$type]);
        } else {
            $result = $this->db->queryOne("SELECT COUNT(*) as cnt FROM memorials");
        }
        return (int) $result['cnt'];
    }
}
