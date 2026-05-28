<?php
// app/Middleware/AdminMiddleware.php

namespace App\Middleware;

use Core\Auth;
use Core\Session;

class AdminMiddleware
{
    public function handle(): bool
    {
        if (!Auth::isAdmin()) {
            Session::flash('error', 'Access denied. Admin privileges required.');
            header('Location: /dashboard');
            exit;
        }
        return true;
    }
}
