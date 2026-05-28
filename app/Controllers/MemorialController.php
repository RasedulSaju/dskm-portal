<?php
// app/Controllers/MemorialController.php

namespace App\Controllers;

use App\Models\Memorial;
use Core\Auth;
use Core\Session;
use Core\DB;

class MemorialController
{
    private Memorial $memorialModel;

    public function __construct()
    {
        $this->memorialModel = new Memorial();
    }

    public function index(): void
    {
        $type = sanitize($_GET['type'] ?? '');
        $memorials = $this->memorialModel->getAll($type);

        $stats = [
            'total'    => $this->memorialModel->count(),
            'members'  => $this->memorialModel->count('member'),
            'teachers' => $this->memorialModel->count('teacher'),
            'staff'    => $this->memorialModel->count('staff'),
        ];

        view('memorial.index', [
            'memorials' => $memorials,
            'stats'     => $stats,
            'type'      => $type,
        ]);
    }

    public function show(array $params): void
    {
        $id = (int) $params['id'];
        $memorial = $this->memorialModel->findById($id);

        if (!$memorial) {
            Session::flash('error', 'Memorial not found.');
            redirect('/memorial');
            return;
        }

        view('memorial.show', ['memorial' => $memorial]);
    }

    public function create(): void
    {
        if (!Auth::isAdmin()) {
            Session::flash('error', 'Access denied.');
            redirect('/memorial');
            return;
        }

        $db = DB::getInstance();
        $batches = $db->query("SELECT * FROM batches WHERE is_active = 1");

        view('memorial.create', ['batches' => $batches]);
    }

    public function store(): void
    {
        if (!Auth::isAdmin()) {
            json(['success' => false, 'message' => 'Access denied'], 403);
            return;
        }

        $errors = $this->validateMemorial($_POST, $_FILES);

        if (!empty($errors)) {
            Session::flash('errors', $errors);
            foreach ($_POST as $key => $value) {
                Session::flash("old_{$key}", sanitize($value));
            }
            back();
            return;
        }

        $photo = null;
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $config = require dirname(__DIR__, 2) . '/config/app.php';
            $photo = uploadFile($_FILES['photo'], 'memorial', $config['allowed_image_types']);
        }

        $memorialId = $this->memorialModel->create([
            'full_name'      => sanitize($_POST['full_name']),
            'full_name_bn'   => sanitize($_POST['full_name_bn']) ?: null,
            'photo'          => $photo,
            'type'           => sanitize($_POST['type']),
            'batch_id'       => (int) ($_POST['batch_id'] ?? 0) ?: null,
            'date_of_death'  => sanitize($_POST['date_of_death']) ?: null,
            'description'    => sanitize($_POST['description']) ?: null,
            'added_by'       => Auth::id(),
        ]);

        Session::flash('success', 'Memorial added successfully.');
        redirect('/memorial/' . $memorialId);
    }

    public function edit(array $params): void
    {
        if (!Auth::isAdmin()) {
            Session::flash('error', 'Access denied.');
            redirect('/memorial');
            return;
        }

        $id = (int) $params['id'];
        $memorial = $this->memorialModel->findById($id);

        if (!$memorial) {
            Session::flash('error', 'Memorial not found.');
            redirect('/memorial');
            return;
        }

        $db = DB::getInstance();
        $batches = $db->query("SELECT * FROM batches WHERE is_active = 1");

        view('memorial.edit', ['memorial' => $memorial, 'batches' => $batches]);
    }

    public function update(array $params): void
    {
        if (!Auth::isAdmin()) {
            json(['success' => false, 'message' => 'Access denied'], 403);
            return;
        }

        $id = (int) $params['id'];
        $memorial = $this->memorialModel->findById($id);

        if (!$memorial) {
            Session::flash('error', 'Memorial not found.');
            redirect('/memorial');
            return;
        }

        $data = [
            'full_name'     => sanitize($_POST['full_name']),
            'full_name_bn'  => sanitize($_POST['full_name_bn']) ?: null,
            'type'          => sanitize($_POST['type']),
            'batch_id'      => (int) ($_POST['batch_id'] ?? 0) ?: null,
            'date_of_death' => sanitize($_POST['date_of_death']) ?: null,
            'description'   => sanitize($_POST['description']) ?: null,
        ];

        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $config = require dirname(__DIR__, 2) . '/config/app.php';
            $photo = uploadFile($_FILES['photo'], 'memorial', $config['allowed_image_types']);
            if ($photo) {
                if ($memorial['photo']) deleteFile($memorial['photo']);
                $data['photo'] = $photo;
            }
        }

        $this->memorialModel->update($id, $data);

        Session::flash('success', 'Memorial updated successfully.');
        redirect('/memorial/' . $id);
    }

    public function delete(array $params): void
    {
        if (!Auth::isAdmin()) {
            json(['success' => false, 'message' => 'Access denied'], 403);
            return;
        }

        $id = (int) $params['id'];
        $memorial = $this->memorialModel->findById($id);

        if (!$memorial) {
            json(['success' => false, 'message' => 'Memorial not found'], 404);
            return;
        }

        if ($memorial['photo']) deleteFile($memorial['photo']);
        $this->memorialModel->delete($id);

        json(['success' => true, 'message' => 'Memorial deleted successfully']);
    }

    public function tribute(array $params): void
    {
        $id = (int) $params['id'];
        $memorial = $this->memorialModel->findById($id);

        if (!$memorial) {
            json(['success' => false, 'message' => 'Memorial not found'], 404);
            return;
        }

        $this->memorialModel->incrementTributes($id);

        json(['success' => true, 'message' => 'Tribute added']);
    }

    private function validateMemorial(array $data, array $files): array
    {
        $errors = [];

        if (empty($data['full_name'])) {
            $errors['full_name'] = 'Name is required.';
        }

        if (empty($data['type'])) {
            $errors['type'] = 'Please select type.';
        }

        if (isset($files['photo']) && $files['photo']['error'] === UPLOAD_ERR_OK) {
            $config = require dirname(__DIR__, 2) . '/config/app.php';
            if (!in_array($files['photo']['type'], $config['allowed_image_types'])) {
                $errors['photo'] = 'Only JPEG, PNG, WEBP images allowed.';
            }
            if ($files['photo']['size'] > $config['max_upload_size']) {
                $errors['photo'] = 'Image size must be less than 5MB.';
            }
        }

        return $errors;
    }
}
