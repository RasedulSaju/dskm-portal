<?php
// app/Models/Message.php

namespace App\Models;

use Core\DB;

class Message
{
    private DB $db;

    public function __construct()
    {
        $this->db = DB::getInstance();
    }

    public function send(int $senderId, int $receiverId, string $message, ?array $media = null): int
    {
        $conversationId = generateConversationId($senderId, $receiverId);

        $this->createConversationIfNotExists($conversationId, $senderId, $receiverId);

        $data = [
            'conversation_id' => $conversationId,
            'sender_id'       => $senderId,
            'receiver_id'     => $receiverId,
            'message'         => $message,
        ];

        if ($media) {
            $data['media'] = $media['path'];
            $data['media_type'] = $media['type'];
        }

        $messageId = $this->db->insert('messages', $data);

        $this->db->update('conversations', [
            'last_message_id' => $messageId,
            'updated_at'      => date('Y-m-d H:i:s'),
        ], ['id' => $conversationId]);

        sendNotification($receiverId, 'message', 'New Message', substr($message, 0, 50), "/messages/{$senderId}");

        return $messageId;
    }

    private function createConversationIfNotExists(string $conversationId, int $user1, int $user2): void
    {
        $exists = $this->db->queryOne("SELECT id FROM conversations WHERE id = ?", [$conversationId]);
        if (!$exists) {
            $this->db->insert('conversations', [
                'id'       => $conversationId,
                'user1_id' => min($user1, $user2),
                'user2_id' => max($user1, $user2),
            ]);
        }
    }

    public function getConversations(int $userId): array
    {
        return $this->db->query(
            "SELECT c.*, 
                    CASE 
                        WHEN c.user1_id = ? THEN c.user2_id 
                        ELSE c.user1_id 
                    END as other_user_id,
                    p.full_name_en, p.avatar, p.profession,
                    u.is_online, u.last_active_at,
                    m.message as last_message, m.created_at as last_message_time, m.sender_id as last_sender_id,
                    (SELECT COUNT(*) FROM messages WHERE conversation_id = c.id AND receiver_id = ? AND is_read = 0) as unread_count
             FROM conversations c
             LEFT JOIN messages m ON m.id = c.last_message_id
             LEFT JOIN users u ON u.id = (CASE WHEN c.user1_id = ? THEN c.user2_id ELSE c.user1_id END)
             LEFT JOIN profiles p ON p.user_id = u.id
             WHERE c.user1_id = ? OR c.user2_id = ?
             ORDER BY c.updated_at DESC",
            [$userId, $userId, $userId, $userId, $userId]
        );
    }

    public function getMessages(string $conversationId, int $userId, int $limit = 50): array
    {
        $messages = $this->db->query(
            "SELECT m.*, 
                    ps.full_name_en as sender_name, ps.avatar as sender_avatar,
                    pr.full_name_en as receiver_name, pr.avatar as receiver_avatar
             FROM messages m
             LEFT JOIN users us ON us.id = m.sender_id
             LEFT JOIN profiles ps ON ps.user_id = us.id
             LEFT JOIN users ur ON ur.id = m.receiver_id
             LEFT JOIN profiles pr ON pr.user_id = ur.id
             WHERE m.conversation_id = ?
               AND ((m.sender_id = ? AND m.deleted_by_sender = 0) 
                    OR (m.receiver_id = ? AND m.deleted_by_receiver = 0))
             ORDER BY m.created_at DESC
             LIMIT ?",
            [$conversationId, $userId, $userId, $limit]
        );

        return array_reverse($messages);
    }

    public function markAsRead(string $conversationId, int $userId): void
    {
        $this->db->execute(
            "UPDATE messages 
             SET is_read = 1, read_at = NOW()
             WHERE conversation_id = ? AND receiver_id = ? AND is_read = 0",
            [$conversationId, $userId]
        );
    }

    public function getUnreadCount(int $userId): int
    {
        $result = $this->db->queryOne(
            "SELECT COUNT(*) as cnt FROM messages WHERE receiver_id = ? AND is_read = 0 AND deleted_by_receiver = 0",
            [$userId]
        );
        return (int) $result['cnt'];
    }

    public function deleteMessage(int $messageId, int $userId): void
    {
        $message = $this->db->queryOne("SELECT * FROM messages WHERE id = ?", [$messageId]);
        if (!$message) return;

        if ($message['sender_id'] == $userId) {
            $this->db->update('messages', ['deleted_by_sender' => 1], ['id' => $messageId]);
        } elseif ($message['receiver_id'] == $userId) {
            $this->db->update('messages', ['deleted_by_receiver' => 1], ['id' => $messageId]);
        }
    }
}
