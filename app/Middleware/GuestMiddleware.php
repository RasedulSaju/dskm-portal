<?php
// app/Middleware/GuestMiddleware.php

namespace App\Middleware;

use Core\Auth;

class GuestMiddleware
{
    public function handle(): bool
    {
        if (Auth::check()) {
            header('Location: /dashboard');
            exit;
        }
        return true;
    }
}
