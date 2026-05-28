<?php
// app/Helpers/helpers.php

use Core\Session;
use Core\Auth;

if (!function_exists('view')) {
    function view(string $viewPath, array $data = []): void
    {
        extract($data);
        $viewFile = dirname(__DIR__) . '/Views/' . str_replace('.', '/', $viewPath) . '.php';
        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            throw new RuntimeException("View not found: {$viewPath}");
        }
    }
}

if (!function_exists('url')) {
    function url(string $path = ''): string
    {
        $base = defined('BASE_PATH') ? BASE_PATH : '';
        $path = '/' . ltrim($path, '/');
        return $base . $path;
    }
}

if (!function_exists('redirect')) {
    function redirect(string $url): void
    {
        if (str_starts_with($url, 'http')) {
            header("Location: {$url}");
            exit;
        }
        $base = defined('BASE_PATH') ? BASE_PATH : '';
        $url = $base . '/' . ltrim($url, '/');
        header("Location: {$url}");
        exit;
    }
}

if (!function_exists('back')) {
    function back(): void
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? '/dashboard';
        redirect($referer);
    }
}

if (!function_exists('json')) {
    function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}

if (!function_exists('old')) {
    function old(string $key, $default = '')
    {
        return Session::getFlash("old_{$key}") ?? $default;
    }
}

if (!function_exists('error')) {
    function error(string $key): ?string
    {
        $errors = Session::getFlash('errors') ?? [];
        return $errors[$key] ?? null;
    }
}

if (!function_exists('hasError')) {
    function hasError(string $key): bool
    {
        return error($key) !== null;
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field(): string
    {
        return '<input type="hidden" name="_csrf_token" value="' . Session::csrfToken() . '">';
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token(): string
    {
        return Session::csrfToken();
    }
}

if (!function_exists('auth')) {
    function auth(): ?array
    {
        return Auth::user();
    }
}

if (!function_exists('asset')) {
    function asset(string $path): string
    {
        return '/assets/' . ltrim($path, '/');
    }
}

if (!function_exists('upload')) {
    function upload(string $path): string
    {
        return '/storage/uploads/' . ltrim($path, '/');
    }
}

if (!function_exists('sanitize')) {
    function sanitize($data)
    {
        if (is_array($data)) {
            return array_map('sanitize', $data);
        }
        return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('slugify')) {
    function slugify(string $text): string
    {
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, '-');
        $text = preg_replace('~-+~', '-', $text);
        return strtolower($text);
    }
}

if (!function_exists('timeAgo')) {
    function timeAgo($datetime): string
    {
        $time = strtotime($datetime);
        $diff = time() - $time;
        
        if ($diff < 60) return 'Just now';
        if ($diff < 3600) return floor($diff / 60) . ' minutes ago';
        if ($diff < 86400) return floor($diff / 3600) . ' hours ago';
        if ($diff < 604800) return floor($diff / 86400) . ' days ago';
        if ($diff < 2592000) return floor($diff / 604800) . ' weeks ago';
        if ($diff < 31536000) return floor($diff / 2592000) . ' months ago';
        return floor($diff / 31536000) . ' years ago';
    }
}

if (!function_exists('formatDate')) {
    function formatDate($datetime, string $format = 'd M Y'): string
    {
        return date($format, strtotime($datetime));
    }
}

if (!function_exists('validateEmail')) {
    function validateEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
}

if (!function_exists('validatePhone')) {
    function validatePhone(string $phone): bool
    {
        return preg_match('/^01[3-9]\d{8}$/', $phone) === 1;
    }
}

if (!function_exists('uploadFile')) {
    function uploadFile(array $file, string $directory, array $allowedTypes = []): ?string
    {
        if ($file['error'] !== UPLOAD_ERR_OK) return null;
        
        if (!empty($allowedTypes) && !in_array($file['type'], $allowedTypes)) {
            return null;
        }

        $config = require dirname(__DIR__, 2) . '/config/app.php';
        if ($file['size'] > $config['max_upload_size']) return null;

        $uploadPath = $config['upload_path'] . $directory;
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '_' . time() . '.' . $ext;
        $destination = $uploadPath . '/' . $filename;

        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return $directory . '/' . $filename;
        }

        return null;
    }
}

if (!function_exists('deleteFile')) {
    function deleteFile(?string $path): bool
    {
        if (!$path) return false;
        $config = require dirname(__DIR__, 2) . '/config/app.php';
        $fullPath = $config['upload_path'] . $path;
        if (file_exists($fullPath)) {
            return unlink($fullPath);
        }
        return false;
    }
}

if (!function_exists('paginate')) {
    function paginate(int $total, int $perPage, int $currentPage = 1): array
    {
        $totalPages = ceil($total / $perPage);
        $currentPage = max(1, min($currentPage, $totalPages));
        $offset = ($currentPage - 1) * $perPage;
        
        return [
            'total'        => $total,
            'per_page'     => $perPage,
            'current_page' => $currentPage,
            'total_pages'  => $totalPages,
            'offset'       => $offset,
            'has_prev'     => $currentPage > 1,
            'has_next'     => $currentPage < $totalPages,
            'prev_page'    => $currentPage > 1 ? $currentPage - 1 : null,
            'next_page'    => $currentPage < $totalPages ? $currentPage + 1 : null,
        ];
    }
}

if (!function_exists('generateConversationId')) {
    function generateConversationId(int $user1, int $user2): string
    {
        $ids = [$user1, $user2];
        sort($ids);
        return 'conv_' . implode('_', $ids);
    }
}

if (!function_exists('isOnline')) {
    function isOnline(int $userId): bool
    {
        $db = \Core\DB::getInstance();
        $user = $db->queryOne("SELECT is_online, last_active_at FROM users WHERE id = ?", [$userId]);
        if (!$user) return false;
        if ($user['is_online'] == 1) {
            $lastActive = strtotime($user['last_active_at']);
            return (time() - $lastActive) < 300; // 5 minutes
        }
        return false;
    }
}

if (!function_exists('sendNotification')) {
    function sendNotification(int $userId, string $type, string $title, string $body, ?string $link = null): void
    {
        $db = \Core\DB::getInstance();
        $db->insert('notifications', [
            'user_id' => $userId,
            'type'    => $type,
            'title'   => $title,
            'body'    => $body,
            'link'    => $link,
            'icon'    => 'bell',
        ]);
    }
}
