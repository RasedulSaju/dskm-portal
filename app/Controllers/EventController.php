<?php
// app/Controllers/EventController.php

namespace App\Controllers;

use App\Models\Event;
use Core\Auth;
use Core\Session;

class EventController
{
    private Event $eventModel;

    public function __construct()
    {
        $this->eventModel = new Event();
    }

    public function index(): void
    {
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = 12;

        $total = $this->eventModel->count('published');
        $pagination = paginate($total, $perPage, $page);
        $events = $this->eventModel->getAll('published', $perPage, $pagination['offset']);

        view('events.index', [
            'events'     => $events,
            'pagination' => $pagination,
        ]);
    }

    public function show(array $params): void
    {
        $id = (int) $params['id'];
        $event = $this->eventModel->findById($id);

        if (!$event || $event['status'] !== 'published') {
            Session::flash('error', 'Event not found.');
            redirect('/events');
            return;
        }

        $userId = Auth::id();
        $userRsvp = $userId ? $this->eventModel->getUserRsvp($id, $userId) : null;
        $attendees = $this->eventModel->getAttendees($id, 'going');

        view('events.show', [
            'event'     => $event,
            'userRsvp'  => $userRsvp,
            'attendees' => $attendees,
        ]);
    }

    public function create(): void
    {
        view('events.create');
    }

    public function store(): void
    {
        $errors = $this->validateEvent($_POST, $_FILES);

        if (!empty($errors)) {
            Session::flash('errors', $errors);
            foreach ($_POST as $key => $value) {
                Session::flash("old_{$key}", sanitize($value));
            }
            back();
            return;
        }

        $banner = null;
        if (isset($_FILES['banner']) && $_FILES['banner']['error'] === UPLOAD_ERR_OK) {
            $config = require dirname(__DIR__, 2) . '/config/app.php';
            $banner = uploadFile($_FILES['banner'], 'events', $config['allowed_image_types']);
        }

        $eventId = $this->eventModel->create([
            'title'          => sanitize($_POST['title']),
            'description'    => $_POST['description'],
            'banner'         => $banner,
            'event_date'     => sanitize($_POST['event_date']),
            'event_end_date' => sanitize($_POST['event_end_date']) ?: null,
            'venue'          => sanitize($_POST['venue']) ?: null,
            'venue_map_url'  => sanitize($_POST['venue_map_url']) ?: null,
            'max_attendees'  => (int) ($_POST['max_attendees'] ?? 0) ?: null,
            'status'         => Auth::isAdmin() ? 'published' : 'draft',
            'created_by'     => Auth::id(),
            'approved_by'    => Auth::isAdmin() ? Auth::id() : null,
            'approved_at'    => Auth::isAdmin() ? date('Y-m-d H:i:s') : null,
        ]);

        Session::flash('success', Auth::isAdmin() ? 'Event created successfully!' : 'Event created and pending approval.');
        redirect('/events/' . $eventId);
    }

    public function edit(array $params): void
    {
        $id = (int) $params['id'];
        $event = $this->eventModel->findById($id);

        if (!$event) {
            Session::flash('error', 'Event not found.');
            redirect('/events');
            return;
        }

        if ($event['created_by'] != Auth::id() && !Auth::isAdmin()) {
            Session::flash('error', 'Access denied.');
            redirect('/events');
            return;
        }

        view('events.edit', ['event' => $event]);
    }

    public function update(array $params): void
    {
        $id = (int) $params['id'];
        $event = $this->eventModel->findById($id);

        if (!$event || ($event['created_by'] != Auth::id() && !Auth::isAdmin())) {
            Session::flash('error', 'Access denied.');
            redirect('/events');
            return;
        }

        $data = [
            'title'          => sanitize($_POST['title']),
            'description'    => $_POST['description'],
            'event_date'     => sanitize($_POST['event_date']),
            'event_end_date' => sanitize($_POST['event_end_date']) ?: null,
            'venue'          => sanitize($_POST['venue']) ?: null,
            'venue_map_url'  => sanitize($_POST['venue_map_url']) ?: null,
            'max_attendees'  => (int) ($_POST['max_attendees'] ?? 0) ?: null,
        ];

        if (isset($_FILES['banner']) && $_FILES['banner']['error'] === UPLOAD_ERR_OK) {
            $config = require dirname(__DIR__, 2) . '/config/app.php';
            $banner = uploadFile($_FILES['banner'], 'events', $config['allowed_image_types']);
            if ($banner) {
                if ($event['banner']) deleteFile($event['banner']);
                $data['banner'] = $banner;
            }
        }

        $this->eventModel->update($id, $data);

        Session::flash('success', 'Event updated successfully!');
        redirect('/events/' . $id);
    }

    public function delete(array $params): void
    {
        $id = (int) $params['id'];
        $event = $this->eventModel->findById($id);

        if (!$event || ($event['created_by'] != Auth::id() && !Auth::isAdmin())) {
            json(['success' => false, 'message' => 'Access denied'], 403);
            return;
        }

        if ($event['banner']) deleteFile($event['banner']);
        $this->eventModel->delete($id);

        json(['success' => true, 'message' => 'Event deleted successfully']);
    }

    public function rsvp(array $params): void
    {
        $id = (int) $params['id'];
        $status = sanitize($_POST['status'] ?? 'going');

        if (!in_array($status, ['going', 'maybe', 'not_going'])) {
            json(['success' => false, 'message' => 'Invalid RSVP status'], 400);
            return;
        }

        $event = $this->eventModel->findById($id);
        if (!$event || $event['status'] !== 'published') {
            json(['success' => false, 'message' => 'Event not found'], 404);
            return;
        }

        $this->eventModel->rsvp($id, Auth::id(), $status);

        json(['success' => true, 'message' => 'RSVP updated successfully']);
    }

    private function validateEvent(array $data, array $files): array
    {
        $errors = [];

        if (empty($data['title']) || strlen($data['title']) < 5) {
            $errors['title'] = 'Event title must be at least 5 characters.';
        }

        if (empty($data['description'])) {
            $errors['description'] = 'Event description is required.';
        }

        if (empty($data['event_date'])) {
            $errors['event_date'] = 'Event date is required.';
        } elseif (strtotime($data['event_date']) < time()) {
            $errors['event_date'] = 'Event date must be in the future.';
        }

        if (!empty($data['event_end_date']) && strtotime($data['event_end_date']) < strtotime($data['event_date'])) {
            $errors['event_end_date'] = 'End date must be after start date.';
        }

        if (isset($files['banner']) && $files['banner']['error'] === UPLOAD_ERR_OK) {
            $config = require dirname(__DIR__, 2) . '/config/app.php';
            if (!in_array($files['banner']['type'], $config['allowed_image_types'])) {
                $errors['banner'] = 'Only JPEG, PNG, WEBP images allowed.';
            }
            if ($files['banner']['size'] > $config['max_upload_size']) {
                $errors['banner'] = 'Image size must be less than 5MB.';
            }
        }

        return $errors;
    }
}
