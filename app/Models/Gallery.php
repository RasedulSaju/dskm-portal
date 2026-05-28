<?php
// app/Models/Gallery.php

namespace App\Models;

use Core\DB;

class Gallery
{
    private DB $db;

    public function __construct()
    {
        $this->db = DB::getInstance();
    }

    public function createAlbum(array $data): int
    {
        $data['slug'] = slugify($data['title']) . '-' . time();
        return $this->db->insert('galleries', $data);
    }

    public function findAlbumById(int $id): ?array
    {
        return $this->db->queryOne(
            "SELECT g.*, p.full_name_en as creator_name, p.avatar as creator_avatar,
                    (SELECT COUNT(*) FROM gallery_images WHERE gallery_id = g.id) as image_count
             FROM galleries g
             LEFT JOIN users u ON u.id = g.created_by
             LEFT JOIN profiles p ON p.user_id = u.id
             WHERE g.id = ?",
            [$id]
        );
    }

    public function getAlbums(string $category = '', int $limit = 12, int $offset = 0): array
    {
        if ($category) {
            return $this->db->query(
                "SELECT g.*, p.full_name_en as creator_name,
                        (SELECT COUNT(*) FROM gallery_images WHERE gallery_id = g.id) as image_count
                 FROM galleries g
                 LEFT JOIN users u ON u.id = g.created_by
                 LEFT JOIN profiles p ON p.user_id = u.id
                 WHERE g.status = 'active' AND g.category = ?
                 ORDER BY g.created_at DESC
                 LIMIT ? OFFSET ?",
                [$category, $limit, $offset]
            );
        }

        return $this->db->query(
            "SELECT g.*, p.full_name_en as creator_name,
                    (SELECT COUNT(*) FROM gallery_images WHERE gallery_id = g.id) as image_count
             FROM galleries g
             LEFT JOIN users u ON u.id = g.created_by
             LEFT JOIN profiles p ON p.user_id = u.id
             WHERE g.status = 'active'
             ORDER BY g.created_at DESC
             LIMIT ? OFFSET ?",
            [$limit, $offset]
        );
    }

    public function countAlbums(string $category = ''): int
    {
        if ($category) {
            $result = $this->db->queryOne("SELECT COUNT(*) as cnt FROM galleries WHERE status = 'active' AND category = ?", [$category]);
        } else {
            $result = $this->db->queryOne("SELECT COUNT(*) as cnt FROM galleries WHERE status = 'active'");
        }
        return (int) $result['cnt'];
    }

    public function updateAlbum(int $id, array $data): int
    {
        return $this->db->update('galleries', $data, ['id' => $id]);
    }

    public function deleteAlbum(int $id): int
    {
        return $this->db->delete('galleries', ['id' => $id]);
    }

    public function addImage(int $galleryId, string $imagePath, ?string $caption, int $uploadedBy): int
    {
        return $this->db->insert('gallery_images', [
            'gallery_id'  => $galleryId,
            'image_path'  => $imagePath,
            'caption'     => $caption,
            'uploaded_by' => $uploadedBy,
        ]);
    }

    public function getImages(int $galleryId): array
    {
        return $this->db->query(
            "SELECT gi.*, p.full_name_en as uploader_name
             FROM gallery_images gi
             LEFT JOIN users u ON u.id = gi.uploaded_by
             LEFT JOIN profiles p ON p.user_id = u.id
             WHERE gi.gallery_id = ?
             ORDER BY gi.created_at DESC",
            [$galleryId]
        );
    }

    public function deleteImage(int $imageId): int
    {
        return $this->db->delete('gallery_images', ['id' => $imageId]);
    }

    public function likeImage(int $imageId): void
    {
        $this->db->execute("UPDATE gallery_images SET likes = likes + 1 WHERE id = ?", [$imageId]);
    }
}
