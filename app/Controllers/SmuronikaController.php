<?php
// app/Controllers/SmuronikaController.php

namespace App\Controllers;

use App\Models\Smoronika;
use Core\Auth;
use Core\Session;

class SmuronikaController
{
    private Smoronika $smuronikaModel;

    public function __construct()
    {
        $this->smuronikaModel = new Smoronika();
    }

    public function index(): void
    {
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = 12;

        $total = $this->smuronikaModel->count('published');
        $pagination = paginate($total, $perPage, $page);
        $articles = $this->smuronikaModel->getAll('published', $perPage, $pagination['offset']);

        view('smoronika.index', [
            'articles'   => $articles,
            'pagination' => $pagination,
        ]);
    }

    public function show(array $params): void
    {
        $id = (int) $params['id'];
        $article = $this->smuronikaModel->findById($id);

        if (!$article || $article['status'] !== 'published') {
            Session::flash('error', 'Article not found.');
            redirect('/smoronika');
            return;
        }

        $this->smuronikaModel->incrementViews($id);

        view('smoronika.show', ['article' => $article]);
    }

    public function create(): void
    {
        view('smoronika.create');
    }

    public function store(): void
    {
        $errors = $this->validateArticle($_POST, $_FILES);

        if (!empty($errors)) {
            Session::flash('errors', $errors);
            foreach ($_POST as $key => $value) {
                Session::flash("old_{$key}", sanitize($value));
            }
            back();
            return;
        }

        $image = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $config = require dirname(__DIR__, 2) . '/config/app.php';
            $image = uploadFile($_FILES['image'], 'smoronika', $config['allowed_image_types']);
        }

        $status = isset($_POST['save_draft']) ? 'draft' : 'pending';

        $articleId = $this->smuronikaModel->create([
            'title'     => sanitize($_POST['title']),
            'content'   => $_POST['content'],
            'image'     => $image,
            'author_id' => Auth::id(),
            'status'    => $status,
        ]);

        $message = $status === 'draft' ? 'Article saved as draft.' : 'Article submitted for approval!';
        Session::flash('success', $message);
        redirect('/smoronika/' . $articleId);
    }

    public function edit(array $params): void
    {
        $id = (int) $params['id'];
        $article = $this->smuronikaModel->findById($id);

        if (!$article) {
            Session::flash('error', 'Article not found.');
            redirect('/smoronika');
            return;
        }

        if ($article['author_id'] != Auth::id() && !Auth::isAdmin()) {
            Session::flash('error', 'Access denied.');
            redirect('/smoronika');
            return;
        }

        view('smoronika.edit', ['article' => $article]);
    }

    public function update(array $params): void
    {
        $id = (int) $params['id'];
        $article = $this->smuronikaModel->findById($id);

        if (!$article || ($article['author_id'] != Auth::id() && !Auth::isAdmin())) {
            Session::flash('error', 'Access denied.');
            redirect('/smoronika');
            return;
        }

        $data = [
            'title'   => sanitize($_POST['title']),
            'content' => $_POST['content'],
        ];

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $config = require dirname(__DIR__, 2) . '/config/app.php';
            $image = uploadFile($_FILES['image'], 'smoronika', $config['allowed_image_types']);
            if ($image) {
                if ($article['image']) deleteFile($article['image']);
                $data['image'] = $image;
            }
        }

        if (isset($_POST['save_draft'])) {
            $data['status'] = 'draft';
        } elseif ($article['status'] === 'draft' || $article['status'] === 'rejected') {
            $data['status'] = 'pending';
        }

        $this->smuronikaModel->update($id, $data);

        Session::flash('success', 'Article updated successfully!');
        redirect('/smoronika/' . $id);
    }

    public function delete(array $params): void
    {
        $id = (int) $params['id'];
        $article = $this->smuronikaModel->findById($id);

        if (!$article || ($article['author_id'] != Auth::id() && !Auth::isAdmin())) {
            json(['success' => false, 'message' => 'Access denied'], 403);
            return;
        }

        if ($article['image']) deleteFile($article['image']);
        $this->smuronikaModel->delete($id);

        json(['success' => true, 'message' => 'Article deleted successfully']);
    }

    private function validateArticle(array $data, array $files): array
    {
        $errors = [];

        if (empty($data['title']) || strlen($data['title']) < 5) {
            $errors['title'] = 'Title must be at least 5 characters.';
        }

        if (empty($data['content']) || strlen($data['content']) < 50) {
            $errors['content'] = 'Content must be at least 50 characters.';
        }

        if (isset($files['image']) && $files['image']['error'] === UPLOAD_ERR_OK) {
            $config = require dirname(__DIR__, 2) . '/config/app.php';
            if (!in_array($files['image']['type'], $config['allowed_image_types'])) {
                $errors['image'] = 'Only JPEG, PNG, WEBP images allowed.';
            }
            if ($files['image']['size'] > $config['max_upload_size']) {
                $errors['image'] = 'Image size must be less than 5MB.';
            }
        }

        return $errors;
    }
}
