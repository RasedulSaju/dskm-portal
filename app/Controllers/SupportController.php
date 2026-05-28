<?php
// app/Controllers/SupportController.php

namespace App\Controllers;

use App\Models\SupportRequest;
use Core\Auth;
use Core\Session;

class SupportController
{
    private SupportRequest $supportModel;

    public function __construct()
    {
        $this->supportModel = new SupportRequest();
    }

    public function index(): void
    {
        $requests = $this->supportModel->getByUser(Auth::id());
        view('support.index', ['requests' => $requests]);
    }

    public function show(array $params): void
    {
        $id = (int) $params['id'];
        $request = $this->supportModel->findById($id);

        if (!$request || ($request['user_id'] != Auth::id() && !Auth::isAdmin())) {
            Session::flash('error', 'Support request not found.');
            redirect('/support');
            return;
        }

        view('support.show', ['request' => $request]);
    }

    public function create(): void
    {
        view('support.create');
    }

    public function store(): void
    {
        $errors = $this->validateRequest($_POST, $_FILES);

        if (!empty($errors)) {
            Session::flash('errors', $errors);
            foreach ($_POST as $key => $value) {
                Session::flash("old_{$key}", sanitize($value));
            }
            back();
            return;
        }

        $attachment = null;
        if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
            $config = require dirname(__DIR__, 2) . '/config/app.php';
            $attachment = uploadFile($_FILES['attachment'], 'support', $config['allowed_doc_types']);
        }

        $requestId = $this->supportModel->create([
            'user_id'     => Auth::id(),
            'category'    => sanitize($_POST['category']),
            'subject'     => sanitize($_POST['subject']),
            'description' => sanitize($_POST['description']),
            'attachment'  => $attachment,
            'status'      => 'pending',
        ]);

        Session::flash('success', 'Support request submitted successfully!');
        redirect('/support/' . $requestId);
    }

    public function delete(array $params): void
    {
        $id = (int) $params['id'];
        $request = $this->supportModel->findById($id);

        if (!$request || $request['user_id'] != Auth::id()) {
            json(['success' => false, 'message' => 'Access denied'], 403);
            return;
        }

        if ($request['attachment']) deleteFile($request['attachment']);
        $this->supportModel->delete($id);

        json(['success' => true, 'message' => 'Request deleted successfully']);
    }

    private function validateRequest(array $data, array $files): array
    {
        $errors = [];

        if (empty($data['category'])) {
            $errors['category'] = 'Please select a category.';
        }

        if (empty($data['subject']) || strlen($data['subject']) < 5) {
            $errors['subject'] = 'Subject must be at least 5 characters.';
        }

        if (empty($data['description']) || strlen($data['description']) < 20) {
            $errors['description'] = 'Description must be at least 20 characters.';
        }

        if (isset($files['attachment']) && $files['attachment']['error'] === UPLOAD_ERR_OK) {
            $config = require dirname(__DIR__, 2) . '/config/app.php';
            if (!in_array($files['attachment']['type'], $config['allowed_doc_types'])) {
                $errors['attachment'] = 'Only PDF and image files allowed.';
            }
            if ($files['attachment']['size'] > $config['max_upload_size']) {
                $errors['attachment'] = 'File size must be less than 5MB.';
            }
        }

        return $errors;
    }
}
