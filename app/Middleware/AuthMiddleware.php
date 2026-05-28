<?php
// app/Middleware/AuthMiddleware.php

namespace App\Middleware;

use Core\Auth;
use Core\Session;

class AuthMiddleware
{
    public function handle(): bool
    {
        if (Auth::guest()) {
            Session::flash('error', 'Please login to continue.');
            Session::set('intended_url', $_SERVER['REQUEST_URI'] ?? '/');
            header('Location: /login');
            exit;
        }
        return true;
    }
}
