<?php
// app/Middleware/CsrfMiddleware.php

namespace App\Middleware;

use Core\Session;

class CsrfMiddleware
{
    public function handle(): bool
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['_csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
            
            if (!Session::verifyCsrf($token)) {
                http_response_code(403);
                echo json_encode(['error' => 'Invalid CSRF token']);
                exit;
            }
        }
        return true;
    }
}
