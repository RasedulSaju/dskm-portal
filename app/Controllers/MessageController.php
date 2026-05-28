<?php
// app/Controllers/MessageController.php

namespace App\Controllers;

use App\Models\Message;
use App\Models\User;
use Core\Auth;
use Core\Session;

class MessageController
{
    private Message $messageModel;
    private User $userModel;

    public function __construct()
    {
        $this->messageModel = new Message();
        $this->userModel = new User();
    }

    public function index(): void
    {
        $conversations = $this->messageModel->getConversations(Auth::id());

        view('messages.index', [
            'conversations' => $conversations,
        ]);
    }

    public function show(array $params): void
    {
        $otherUserId = (int) $params['userId'];
        $otherUser = $this->userModel->findById($otherUserId);

        if (!$otherUser || $otherUser['status'] !== 'active') {
            Session::flash('error', 'User not found.');
            redirect('/messages');
            return;
        }

        $conversationId = generateConversationId(Auth::id(), $otherUserId);
        $messages = $this->messageModel->getMessages($conversationId, Auth::id());
        $this->messageModel->markAsRead($conversationId, Auth::id());

        view('messages.show', [
            'otherUser'      => $otherUser,
            'messages'       => $messages,
            'conversationId' => $conversationId,
        ]);
    }

    public function send(): void
    {
        $receiverId = (int) ($_POST['receiver_id'] ?? 0);
        $message = trim($_POST['message'] ?? '');

        if (!$receiverId || empty($message)) {
            json(['success' => false, 'message' => 'Invalid request'], 400);
            return;
        }

        $receiver = $this->userModel->findById($receiverId);
        if (!$receiver || $receiver['status'] !== 'active') {
            json(['success' => false, 'message' => 'Recipient not found'], 404);
            return;
        }

        $media = null;
        if (isset($_FILES['media']) && $_FILES['media']['error'] === UPLOAD_ERR_OK) {
            $config = require dirname(__DIR__, 2) . '/config/app.php';
            $allowedTypes = array_merge($config['allowed_image_types'], ['application/pdf']);
            $path = uploadFile($_FILES['media'], 'messages', $allowedTypes);
            if ($path) {
                $type = in_array($_FILES['media']['type'], $config['allowed_image_types']) ? 'image' : 'file';
                $media = ['path' => $path, 'type' => $type];
            }
        }

        $messageId = $this->messageModel->send(Auth::id(), $receiverId, $message, $media);

        json([
            'success'    => true,
            'message_id' => $messageId,
            'message'    => 'Message sent successfully',
        ]);
    }

    public function getMessages(array $params): void
    {
        $otherUserId = (int) $params['userId'];
        $conversationId = generateConversationId(Auth::id(), $otherUserId);
        $messages = $this->messageModel->getMessages($conversationId, Auth::id());
        $this->messageModel->markAsRead($conversationId, Auth::id());

        json([
            'success'  => true,
            'messages' => $messages,
        ]);
    }

    public function markRead(array $params): void
    {
        $otherUserId = (int) $params['userId'];
        $conversationId = generateConversationId(Auth::id(), $otherUserId);
        $this->messageModel->markAsRead($conversationId, Auth::id());

        json(['success' => true]);
    }

    public function delete(array $params): void
    {
        $messageId = (int) $params['id'];
        $this->messageModel->deleteMessage($messageId, Auth::id());

        json(['success' => true, 'message' => 'Message deleted']);
    }

    public function unreadCount(): void
    {
        $count = $this->messageModel->getUnreadCount(Auth::id());
        json(['count' => $count]);
    }
}
