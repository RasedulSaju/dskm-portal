<?php
// app/Controllers/AuthController.php

namespace App\Controllers;

use App\Models\User;
use App\Models\Profile;
use Core\Auth;
use Core\Session;
use Core\DB;

class AuthController
{
    private User $userModel;
    private Profile $profileModel;

    public function __construct()
    {
        $this->userModel = new User();
        $this->profileModel = new Profile();
    }

    public function showLogin(): void
    {
        view('auth.login');
    }

    public function login(): void
    {
        $identifier = sanitize($_POST['identifier'] ?? '');
        $password = $_POST['password'] ?? '';
        $remember = isset($_POST['remember_me']);

        if (empty($identifier) || empty($password)) {
            Session::flash('error', 'Please provide username/mobile/email and password.');
            back();
            return;
        }

        if (Auth::attempt($identifier, $password, $remember)) {
            $intended = Session::get('intended_url', '/dashboard');
            Session::remove('intended_url');
            Session::flash('success', 'Welcome back!');
            redirect($intended);
        } else {
            Session::flash('error', 'Invalid credentials or account not active.');
            back();
        }
    }

    public function showRegister(): void
    {
        $db = DB::getInstance();
        $batches = $db->query("SELECT * FROM batches WHERE is_active = 1 ORDER BY year ASC");
        view('auth.register', ['batches' => $batches]);
    }

    public function register(): void
    {
        $errors = $this->validateRegistration($_POST, $_FILES);

        if (!empty($errors)) {
            Session::flash('errors', $errors);
            foreach ($_POST as $key => $value) {
                if (!is_array($value)) {
                    Session::flash("old_{$key}", sanitize($value));
                }
            }
            back();
            return;
        }

        $db = DB::getInstance();
        $db->beginTransaction();

        try {
            $userId = $this->userModel->create([
                'username' => sanitize($_POST['username']),
                'email'    => sanitize($_POST['email']) ?: null,
                'mobile'   => sanitize($_POST['mobile']),
                'password' => $_POST['password'],
                'status'   => 'pending',
            ]);

            $avatar = null;
            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
                $config = require dirname(__DIR__, 2) . '/config/app.php';
                $avatar = uploadFile($_FILES['avatar'], 'avatars', $config['allowed_image_types']);
            }

            $this->profileModel->create([
                'user_id'       => $userId,
                'full_name_bn'  => sanitize($_POST['full_name_bn']),
                'full_name_en'  => sanitize($_POST['full_name_en']),
                'avatar'        => $avatar,
                'blood_group'   => sanitize($_POST['blood_group']) ?: null,
                'whatsapp'      => sanitize($_POST['whatsapp']) ?: null,
                'address_present' => sanitize($_POST['address']) ?: null,
                'district'      => sanitize($_POST['district']) ?: null,
                'profession'    => sanitize($_POST['profession']) ?: null,
                'workplace'     => sanitize($_POST['workplace']) ?: null,
                'bio'           => sanitize($_POST['bio']) ?: null,
                'facebook_url'  => sanitize($_POST['facebook_url']) ?: null,
                'linkedin_url'  => sanitize($_POST['linkedin_url']) ?: null,
            ]);

            $batches = $_POST['batches'] ?? [];
            foreach ($batches as $batchId) {
                $rollField = "roll_{$batchId}";
                $regField = "registration_{$batchId}";
                
                $db->insert('user_batches', [
                    'user_id'             => $userId,
                    'batch_id'            => (int) $batchId,
                    'roll_number'         => sanitize($_POST[$rollField] ?? ''),
                    'registration_number' => sanitize($_POST[$regField] ?? ''),
                ]);
            }

            $db->commit();

            Session::flash('success', 'Registration successful! Your account is pending admin approval.');
            redirect('/login');

        } catch (\Exception $e) {
            $db->rollback();
            error_log('Registration error: ' . $e->getMessage());
            Session::flash('error', 'Registration failed. Please try again.');
            back();
        }
    }

    private function validateRegistration(array $data, array $files): array
    {
        $errors = [];

        if (empty($data['full_name_bn'])) {
            $errors['full_name_bn'] = 'Bangla name is required.';
        }

        if (empty($data['full_name_en'])) {
            $errors['full_name_en'] = 'English name is required.';
        }

        if (empty($data['username']) || strlen($data['username']) < 3) {
            $errors['username'] = 'Username must be at least 3 characters.';
        } elseif ($this->userModel->findByUsername($data['username'])) {
            $errors['username'] = 'Username already taken.';
        }

        if (empty($data['mobile']) || !validatePhone($data['mobile'])) {
            $errors['mobile'] = 'Valid mobile number required (01XXXXXXXXX).';
        } elseif ($this->userModel->findByMobile($data['mobile'])) {
            $errors['mobile'] = 'Mobile number already registered.';
        }

        if (!empty($data['email'])) {
            if (!validateEmail($data['email'])) {
                $errors['email'] = 'Invalid email format.';
            } elseif ($this->userModel->findByEmail($data['email'])) {
                $errors['email'] = 'Email already registered.';
            }
        }

        if (empty($data['password']) || strlen($data['password']) < 6) {
            $errors['password'] = 'Password must be at least 6 characters.';
        }

        if (empty($data['password_confirmation']) || $data['password'] !== $data['password_confirmation']) {
            $errors['password_confirmation'] = 'Passwords do not match.';
        }

        if (empty($data['batches']) || !is_array($data['batches'])) {
            $errors['batches'] = 'Please select at least one batch.';
        }

        if (empty($data['terms'])) {
            $errors['terms'] = 'You must accept the terms and conditions.';
        }

        if (isset($files['avatar']) && $files['avatar']['error'] === UPLOAD_ERR_OK) {
            $config = require dirname(__DIR__, 2) . '/config/app.php';
            if (!in_array($files['avatar']['type'], $config['allowed_image_types'])) {
                $errors['avatar'] = 'Only JPEG, PNG, WEBP images allowed.';
            }
            if ($files['avatar']['size'] > $config['max_upload_size']) {
                $errors['avatar'] = 'Image size must be less than 5MB.';
            }
        }

        return $errors;
    }

    public function logout(): void
    {
        Auth::logout();
        Session::flash('success', 'You have been logged out.');
        redirect('/login');
    }

    public function showForgotPassword(): void
    {
        view('auth.forgot-password');
    }

    public function forgotPassword(): void
    {
        $mobile = sanitize($_POST['mobile'] ?? '');

        if (empty($mobile) || !validatePhone($mobile)) {
            Session::flash('error', 'Please provide a valid mobile number.');
            back();
            return;
        }

        $user = $this->userModel->findByMobile($mobile);
        if (!$user) {
            Session::flash('error', 'No account found with this mobile number.');
            back();
            return;
        }

        $token = bin2hex(random_bytes(32));
        $db = DB::getInstance();
        $db->insert('password_resets', [
            'mobile'     => $mobile,
            'token'      => $token,
            'expires_at' => date('Y-m-d H:i:s', time() + 3600),
        ]);

        Session::flash('success', 'Password reset link sent! (Demo: Use token ' . substr($token, 0, 8) . '...)');
        redirect('/login');
    }

    public function showResetPassword(): void
    {
        $token = $_GET['token'] ?? '';
        if (empty($token)) {
            Session::flash('error', 'Invalid reset token.');
            redirect('/login');
            return;
        }

        $db = DB::getInstance();
        $reset = $db->queryOne(
            "SELECT * FROM password_resets WHERE token = ? AND expires_at > NOW() AND used = 0",
            [$token]
        );

        if (!$reset) {
            Session::flash('error', 'Invalid or expired reset token.');
            redirect('/login');
            return;
        }

        view('auth.reset-password', ['token' => $token]);
    }

    public function resetPassword(): void
    {
        $token = $_POST['token'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirmation = $_POST['password_confirmation'] ?? '';

        if (empty($password) || strlen($password) < 6) {
            Session::flash('error', 'Password must be at least 6 characters.');
            back();
            return;
        }

        if ($password !== $confirmation) {
            Session::flash('error', 'Passwords do not match.');
            back();
            return;
        }

        $db = DB::getInstance();
        $reset = $db->queryOne(
            "SELECT * FROM password_resets WHERE token = ? AND expires_at > NOW() AND used = 0",
            [$token]
        );

        if (!$reset) {
            Session::flash('error', 'Invalid or expired reset token.');
            redirect('/login');
            return;
        }

        $user = $this->userModel->findByMobile($reset['mobile']);
        if (!$user) {
            Session::flash('error', 'User not found.');
            redirect('/login');
            return;
        }

        $this->userModel->changePassword($user['id'], $password);
        $db->update('password_resets', ['used' => 1], ['id' => $reset['id']]);

        Session::flash('success', 'Password reset successful! Please login.');
        redirect('/login');
    }
}
