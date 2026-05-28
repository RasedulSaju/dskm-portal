<?php
// app/Controllers/NoticeController.php

namespace App\Controllers;

use App\Models\Notice;
use Core\Auth;
use Core\Session;

class NoticeController
{
    private Notice $noticeModel;

    public function __construct()
    {
        $this->noticeModel = new Notice();
    }

    public function index(): void
    {
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $category = sanitize($_GET['category'] ?? '');
        $perPage = 12;

        $total = $this->noticeModel->count($category);
        $pagination = paginate($total, $perPage, $page);
        $notices = $this->noticeModel->getAll($category, $perPage, $pagination['offset']);

        view('notices.index', [
            'notices'    => $notices,
            'pagination' => $pagination,
            'category'   => $category,
        ]);
    }

    public function show(array $params): void
    {
        $id = (int) $params['id'];
        $notice = $this->noticeModel->findById($id);

        if (!$notice || $notice['status'] !== 'published') {
            Session::flash('error', 'Notice not found.');
            redirect('/notices');
            return;
        }

        $this->noticeModel->incrementViews($id);
        $comments = $this->noticeModel->getComments($id);

        view('notices.show', [
            'notice'   => $notice,
            'comments' => $comments,
        ]);
    }

    public function create(): void
    {
        if (!Auth::isAdmin()) {
            Session::flash('error', 'Access denied.');
            redirect('/notices');
            return;
        }

        view('notices.create');
    }

    public function store(): void
    {
        if (!Auth::isAdmin()) {
            json(['success' => false, 'message' => 'Access denied'], 403);
            return;
        }

        $errors = $this->validateNotice($_POST, $_FILES);

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
            $attachment = uploadFile($_FILES['attachment'], 'documents', $config['allowed_doc_types']);
        }

        $noticeId = $this->noticeModel->create([
            'title'      => sanitize($_POST['title']),
            'content'    => $_POST['content'],
            'category'   => sanitize($_POST['category']),
            'attachment' => $attachment,
            'is_pinned'  => isset($_POST['is_pinned']) ? 1 : 0,
            'status'     => 'published',
            'created_by' => Auth::id(),
        ]);

        Session::flash('success', 'Notice published successfully!');
        redirect('/notices/' . $noticeId);
    }

    public function edit(array $params): void
    {
        if (!Auth::isAdmin()) {
            Session::flash('error', 'Access denied.');
            redirect('/notices');
            return;
        }

        $id = (int) $params['id'];
        $notice = $this->noticeModel->findById($id);

        if (!$notice) {
            Session::flash('error', 'Notice not found.');
            redirect('/notices');
            return;
        }

        view('notices.edit', ['notice' => $notice]);
    }

    public function update(array $params): void
    {
        if (!Auth::isAdmin()) {
            json(['success' => false, 'message' => 'Access denied'], 403);
            return;
        }

        $id = (int) $params['id'];
        $notice = $this->noticeModel->findById($id);

        if (!$notice) {
            Session::flash('error', 'Notice not found.');
            redirect('/notices');
            return;
        }

        $data = [
            'title'     => sanitize($_POST['title']),
            'content'   => $_POST['content'],
            'category'  => sanitize($_POST['category']),
            'is_pinned' => isset($_POST['is_pinned']) ? 1 : 0,
        ];

        if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
            $config = require dirname(__DIR__, 2) . '/config/app.php';
            $attachment = uploadFile($_FILES['attachment'], 'documents', $config['allowed_doc_types']);
            if ($attachment) {
                if ($notice['attachment']) deleteFile($notice['attachment']);
                $data['attachment'] = $attachment;
            }
        }

        $this->noticeModel->update($id, $data);

        Session::flash('success', 'Notice updated successfully!');
        redirect('/notices/' . $id);
    }

    public function delete(array $params): void
    {
        if (!Auth::isAdmin()) {
            json(['success' => false, 'message' => 'Access denied'], 403);
            return;
        }

        $id = (int) $params['id'];
        $notice = $this->noticeModel->findById($id);

        if (!$notice) {
            json(['success' => false, 'message' => 'Notice not found'], 404);
            return;
        }

        if ($notice['attachment']) deleteFile($notice['attachment']);
        $this->noticeModel->delete($id);

        json(['success' => true, 'message' => 'Notice deleted successfully']);
    }

    public function comment(array $params): void
    {
        $id = (int) $params['id'];
        $comment = sanitize($_POST['comment'] ?? '');

        if (empty($comment)) {
            json(['success' => false, 'message' => 'Comment cannot be empty'], 400);
            return;
        }

        $notice = $this->noticeModel->findById($id);
        if (!$notice) {
            json(['success' => false, 'message' => 'Notice not found'], 404);
            return;
        }

        $this->noticeModel->addComment($id, Auth::id(), $comment);

        json(['success' => true, 'message' => 'Comment added successfully']);
    }

    private function validateNotice(array $data, array $files): array
    {
        $errors = [];

        if (empty($data['title']) || strlen($data['title']) < 5) {
            $errors['title'] = 'Notice title must be at least 5 characters.';
        }

        if (empty($data['content'])) {
            $errors['content'] = 'Notice content is required.';
        }

        if (empty($data['category'])) {
            $errors['category'] = 'Please select a category.';
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
