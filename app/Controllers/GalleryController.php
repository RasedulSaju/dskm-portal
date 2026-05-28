<?php
// app/Controllers/GalleryController.php

namespace App\Controllers;

use App\Models\Gallery;
use Core\Auth;
use Core\Session;

class GalleryController
{
    private Gallery $galleryModel;

    public function __construct()
    {
        $this->galleryModel = new Gallery();
    }

    public function index(): void
    {
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $category = sanitize($_GET['category'] ?? '');
        $perPage = 12;

        $total = $this->galleryModel->countAlbums($category);
        $pagination = paginate($total, $perPage, $page);
        $albums = $this->galleryModel->getAlbums($category, $perPage, $pagination['offset']);

        view('gallery.index', [
            'albums'     => $albums,
            'pagination' => $pagination,
            'category'   => $category,
        ]);
    }

    public function show(array $params): void
    {
        $id = (int) $params['id'];
        $album = $this->galleryModel->findAlbumById($id);

        if (!$album || $album['status'] !== 'active') {
            Session::flash('error', 'Album not found.');
            redirect('/gallery');
            return;
        }

        $images = $this->galleryModel->getImages($id);

        view('gallery.show', [
            'album'  => $album,
            'images' => $images,
        ]);
    }

    public function create(): void
    {
        if (!Auth::isModerator()) {
            Session::flash('error', 'Access denied.');
            redirect('/gallery');
            return;
        }

        view('gallery.create');
    }

    public function store(): void
    {
        if (!Auth::isModerator()) {
            json(['success' => false, 'message' => 'Access denied'], 403);
            return;
        }

        $errors = $this->validateAlbum($_POST, $_FILES);

        if (!empty($errors)) {
            Session::flash('errors', $errors);
            foreach ($_POST as $key => $value) {
                Session::flash("old_{$key}", sanitize($value));
            }
            back();
            return;
        }

        $cover = null;
        if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
            $config = require dirname(__DIR__, 2) . '/config/app.php';
            $cover = uploadFile($_FILES['cover_image'], 'gallery', $config['allowed_image_types']);
        }

        $albumId = $this->galleryModel->createAlbum([
            'title'       => sanitize($_POST['title']),
            'description' => sanitize($_POST['description']) ?: null,
            'cover_image' => $cover,
            'category'    => sanitize($_POST['category']) ?: null,
            'created_by'  => Auth::id(),
            'status'      => 'active',
        ]);

        Session::flash('success', 'Album created successfully!');
        redirect('/gallery/' . $albumId);
    }

    public function uploadImages(array $params): void
    {
        if (!Auth::isModerator()) {
            json(['success' => false, 'message' => 'Access denied'], 403);
            return;
        }

        $albumId = (int) $params['id'];
        $album = $this->galleryModel->findAlbumById($albumId);

        if (!$album) {
            json(['success' => false, 'message' => 'Album not found'], 404);
            return;
        }

        if (!isset($_FILES['images']) || empty($_FILES['images']['name'][0])) {
            json(['success' => false, 'message' => 'No images provided'], 400);
            return;
        }

        $config = require dirname(__DIR__, 2) . '/config/app.php';
        $uploaded = 0;

        foreach ($_FILES['images']['tmp_name'] as $index => $tmpName) {
            if ($_FILES['images']['error'][$index] !== UPLOAD_ERR_OK) continue;

            $file = [
                'name'     => $_FILES['images']['name'][$index],
                'type'     => $_FILES['images']['type'][$index],
                'tmp_name' => $tmpName,
                'error'    => $_FILES['images']['error'][$index],
                'size'     => $_FILES['images']['size'][$index],
            ];

            $path = uploadFile($file, 'gallery', $config['allowed_image_types']);
            if ($path) {
                $caption = sanitize($_POST['captions'][$index] ?? '');
                $this->galleryModel->addImage($albumId, $path, $caption ?: null, Auth::id());
                $uploaded++;
            }
        }

        json(['success' => true, 'message' => "{$uploaded} images uploaded successfully"]);
    }

    public function deleteImage(array $params): void
    {
        if (!Auth::isModerator()) {
            json(['success' => false, 'message' => 'Access denied'], 403);
            return;
        }

        $imageId = (int) $params['id'];
        $this->galleryModel->deleteImage($imageId);

        json(['success' => true, 'message' => 'Image deleted successfully']);
    }

    public function deleteAlbum(array $params): void
    {
        if (!Auth::isAdmin()) {
            json(['success' => false, 'message' => 'Access denied'], 403);
            return;
        }

        $albumId = (int) $params['id'];
        $album = $this->galleryModel->findAlbumById($albumId);

        if (!$album) {
            json(['success' => false, 'message' => 'Album not found'], 404);
            return;
        }

        $images = $this->galleryModel->getImages($albumId);
        foreach ($images as $image) {
            deleteFile($image['image_path']);
        }

        if ($album['cover_image']) deleteFile($album['cover_image']);
        $this->galleryModel->deleteAlbum($albumId);

        json(['success' => true, 'message' => 'Album deleted successfully']);
    }

    public function likeImage(array $params): void
    {
        $imageId = (int) $params['id'];
        $this->galleryModel->likeImage($imageId);

        json(['success' => true, 'message' => 'Image liked']);
    }

    private function validateAlbum(array $data, array $files): array
    {
        $errors = [];

        if (empty($data['title']) || strlen($data['title']) < 3) {
            $errors['title'] = 'Album title must be at least 3 characters.';
        }

        if (isset($files['cover_image']) && $files['cover_image']['error'] === UPLOAD_ERR_OK) {
            $config = require dirname(__DIR__, 2) . '/config/app.php';
            if (!in_array($files['cover_image']['type'], $config['allowed_image_types'])) {
                $errors['cover_image'] = 'Only JPEG, PNG, WEBP images allowed.';
            }
            if ($files['cover_image']['size'] > $config['max_upload_size']) {
                $errors['cover_image'] = 'Image size must be less than 5MB.';
            }
        }

        return $errors;
    }
}
