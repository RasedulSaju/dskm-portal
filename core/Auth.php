<?php
// core/Auth.php

namespace Core;

class Auth
{
    private static ?array $user = null;

    public static function attempt(string $identifier, string $password, bool $remember = false): bool
    {
        $db = DB::getInstance();
        $user = $db->queryOne(
            "SELECT u.*, p.full_name_en, p.full_name_bn, p.avatar, p.profession
             FROM users u
             LEFT JOIN profiles p ON p.user_id = u.id
             WHERE (u.mobile = ? OR u.email = ? OR u.username = ?)
             LIMIT 1",
            [$identifier, $identifier, $identifier]
        );

        if (!$user) return false;

        // Check if locked
        if ($user['locked_until'] && strtotime($user['locked_until']) > time()) {
            Session::flash('error', 'Account is temporarily locked. Try again later.');
            return false;
        }

        if (!password_verify($password, $user['password'])) {
            // Increment login attempts
            $attempts = $user['login_attempts'] + 1;
            $lockedUntil = null;
            if ($attempts >= 5) {
                $lockedUntil = date('Y-m-d H:i:s', time() + 900); // 15 min
                $attempts = 0;
            }
            $db->execute(
                "UPDATE users SET login_attempts = ?, locked_until = ? WHERE id = ?",
                [$attempts, $lockedUntil, $user['id']]
            );
            return false;
        }

        if ($user['status'] !== 'active') {
            $msgs = [
                'pending'   => 'Your account is pending admin approval.',
                'suspended' => 'Your account has been suspended.',
                'rejected'  => 'Your account registration was rejected.',
            ];
            Session::flash('error', $msgs[$user['status']] ?? 'Account inactive.');
            return false;
        }

        // Reset attempts
        $db->execute("UPDATE users SET login_attempts = 0, locked_until = NULL, last_login_at = NOW(), is_online = 1 WHERE id = ?", [$user['id']]);

        // Set session
        Session::regenerate();
        Session::set('user_id', $user['id']);
        Session::set('user_role', $user['role_id']);
        Session::set('user', self::sanitizeUser($user));

        // Remember me
        if ($remember) {
            $token = bin2hex(random_bytes(32));
            $db->execute("UPDATE users SET remember_token = ? WHERE id = ?", [$token, $user['id']]);
            setcookie('remember_token', $token, time() + (86400 * 30), '/', '', false, true);
        }

        // Log activity
        self::logActivity($user['id'], 'login', 'User logged in');

        return true;
    }

    public static function user(): ?array
    {
        if (self::$user !== null) return self::$user;

        if (Session::has('user_id')) {
            $db = DB::getInstance();
            $user = $db->queryOne(
                "SELECT u.*, p.full_name_en, p.full_name_bn, p.avatar, p.profession
                 FROM users u
                 LEFT JOIN profiles p ON p.user_id = u.id
                 WHERE u.id = ? AND u.status = 'active'",
                [Session::get('user_id')]
            );
            if ($user) {
                self::$user = self::sanitizeUser($user);
                // Update last active
                $db->execute("UPDATE users SET last_active_at = NOW(), is_online = 1 WHERE id = ?", [$user['id']]);
                return self::$user;
            }
        }

        // Try remember me cookie
        if (isset($_COOKIE['remember_token'])) {
            $db = DB::getInstance();
            $user = $db->queryOne(
                "SELECT u.*, p.full_name_en, p.full_name_bn, p.avatar, p.profession
                 FROM users u
                 LEFT JOIN profiles p ON p.user_id = u.id
                 WHERE u.remember_token = ? AND u.status = 'active'",
                [$_COOKIE['remember_token']]
            );
            if ($user) {
                Session::regenerate();
                Session::set('user_id', $user['id']);
                Session::set('user_role', $user['role_id']);
                Session::set('user', self::sanitizeUser($user));
                self::$user = self::sanitizeUser($user);
                return self::$user;
            }
        }

        return null;
    }

    public static function check(): bool
    {
        return self::user() !== null;
    }

    public static function id(): ?int
    {
        return self::check() ? (int) self::user()['id'] : null;
    }

    public static function role(): ?int
    {
        return Session::get('user_role');
    }

    public static function isAdmin(): bool
    {
        return in_array(self::role(), [1, 2]); // super_admin, admin
    }

    public static function isSuperAdmin(): bool
    {
        return self::role() === 1;
    }

    public static function isModerator(): bool
    {
        return in_array(self::role(), [1, 2, 3]);
    }

    public static function hasPermission(string $permission): bool
    {
        if (self::isSuperAdmin()) return true;
        $roleId = self::role();
        if (!$roleId) return false;
        $db = DB::getInstance();
        $result = $db->queryOne(
            "SELECT rp.permission_id FROM role_permissions rp
             JOIN permissions p ON p.id = rp.permission_id
             WHERE rp.role_id = ? AND p.slug = ?",
            [$roleId, $permission]
        );
        return $result !== null;
    }

    public static function logout(): void
    {
        if (self::check()) {
            $db = DB::getInstance();
            $db->execute("UPDATE users SET is_online = 0, remember_token = NULL WHERE id = ?", [self::id()]);
            self::logActivity(self::id(), 'logout', 'User logged out');
        }
        setcookie('remember_token', '', time() - 3600, '/');
        Session::destroy();
        self::$user = null;
    }

    public static function guest(): bool
    {
        return !self::check();
    }

    private static function sanitizeUser(array $user): array
    {
        unset($user['password'], $user['remember_token']);
        return $user;
    }

    private static function logActivity(int $userId, string $action, string $desc): void
    {
        try {
            $db = DB::getInstance();
            $db->execute(
                "INSERT INTO activity_log (user_id, action, description, ip_address, user_agent) VALUES (?, ?, ?, ?, ?)",
                [$userId, $action, $desc, $_SERVER['REMOTE_ADDR'] ?? null, $_SERVER['HTTP_USER_AGENT'] ?? null]
            );
        } catch (\Exception $e) {
            // Silent fail for logging
        }
    }
}
