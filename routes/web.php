<?php
// routes/web.php

use Core\Router;

$router = new Router();

// =====================================================
// PUBLIC ROUTES
// =====================================================

$router->get('/', function() {
    view('landing');
});

// =====================================================
// AUTH ROUTES (Guest Only)
// =====================================================

$router->get('/login', ['AuthController', 'showLogin'], ['GuestMiddleware']);
$router->post('/login', ['AuthController', 'login'], ['GuestMiddleware', 'CsrfMiddleware']);

$router->get('/register', ['AuthController', 'showRegister'], ['GuestMiddleware']);
$router->post('/register', ['AuthController', 'register'], ['GuestMiddleware', 'CsrfMiddleware']);

$router->get('/forgot-password', ['AuthController', 'showForgotPassword'], ['GuestMiddleware']);
$router->post('/forgot-password', ['AuthController', 'forgotPassword'], ['GuestMiddleware', 'CsrfMiddleware']);

$router->get('/reset-password', ['AuthController', 'showResetPassword'], ['GuestMiddleware']);
$router->post('/reset-password', ['AuthController', 'resetPassword'], ['GuestMiddleware', 'CsrfMiddleware']);

$router->post('/logout', ['AuthController', 'logout'], ['AuthMiddleware']);

// =====================================================
// DASHBOARD (Authenticated)
// =====================================================

$router->get('/dashboard', ['DashboardController', 'index'], ['AuthMiddleware']);
$router->get('/dashboard/profile', ['DashboardController', 'profile'], ['AuthMiddleware']);
$router->get('/dashboard/profile/edit', ['DashboardController', 'editProfile'], ['AuthMiddleware']);
$router->post('/dashboard/profile/edit', ['DashboardController', 'updateProfile'], ['AuthMiddleware', 'CsrfMiddleware']);
$router->get('/dashboard/change-password', ['DashboardController', 'changePassword'], ['AuthMiddleware']);
$router->post('/dashboard/change-password', ['DashboardController', 'updatePassword'], ['AuthMiddleware', 'CsrfMiddleware']);

// =====================================================
// MEMBERS
// =====================================================

$router->get('/members', ['MembersController', 'index'], ['AuthMiddleware']);
$router->get('/members/{id}', ['MembersController', 'show'], ['AuthMiddleware']);
$router->get('/api/members/search', ['MembersController', 'search'], ['AuthMiddleware']);

// =====================================================
// EVENTS
// =====================================================

$router->get('/events', ['EventController', 'index'], ['AuthMiddleware']);
$router->get('/events/create', ['EventController', 'create'], ['AuthMiddleware']);
$router->post('/events/create', ['EventController', 'store'], ['AuthMiddleware', 'CsrfMiddleware']);
$router->get('/events/{id}', ['EventController', 'show'], ['AuthMiddleware']);
$router->get('/events/{id}/edit', ['EventController', 'edit'], ['AuthMiddleware']);
$router->post('/events/{id}/edit', ['EventController', 'update'], ['AuthMiddleware', 'CsrfMiddleware']);
$router->post('/events/{id}/delete', ['EventController', 'delete'], ['AuthMiddleware', 'CsrfMiddleware']);
$router->post('/events/{id}/rsvp', ['EventController', 'rsvp'], ['AuthMiddleware', 'CsrfMiddleware']);

// =====================================================
// NOTICES
// =====================================================

$router->get('/notices', ['NoticeController', 'index'], ['AuthMiddleware']);
$router->get('/notices/create', ['NoticeController', 'create'], ['AuthMiddleware', 'AdminMiddleware']);
$router->post('/notices/create', ['NoticeController', 'store'], ['AuthMiddleware', 'AdminMiddleware', 'CsrfMiddleware']);
$router->get('/notices/{id}', ['NoticeController', 'show'], ['AuthMiddleware']);
$router->get('/notices/{id}/edit', ['NoticeController', 'edit'], ['AuthMiddleware', 'AdminMiddleware']);
$router->post('/notices/{id}/edit', ['NoticeController', 'update'], ['AuthMiddleware', 'AdminMiddleware', 'CsrfMiddleware']);
$router->post('/notices/{id}/delete', ['NoticeController', 'delete'], ['AuthMiddleware', 'AdminMiddleware', 'CsrfMiddleware']);
$router->post('/notices/{id}/comment', ['NoticeController', 'comment'], ['AuthMiddleware', 'CsrfMiddleware']);

// =====================================================
// MESSAGES
// =====================================================

$router->get('/messages', ['MessageController', 'index'], ['AuthMiddleware']);
$router->get('/messages/{userId}', ['MessageController', 'show'], ['AuthMiddleware']);
$router->post('/messages/send', ['MessageController', 'send'], ['AuthMiddleware', 'CsrfMiddleware']);
$router->get('/api/messages/{userId}', ['MessageController', 'getMessages'], ['AuthMiddleware']);
$router->post('/api/messages/{userId}/read', ['MessageController', 'markRead'], ['AuthMiddleware', 'CsrfMiddleware']);
$router->post('/api/messages/{id}/delete', ['MessageController', 'delete'], ['AuthMiddleware', 'CsrfMiddleware']);
$router->get('/api/messages/unread-count', ['MessageController', 'unreadCount'], ['AuthMiddleware']);

// =====================================================
// GALLERY
// =====================================================

$router->get('/gallery', ['GalleryController', 'index'], ['AuthMiddleware']);
$router->get('/gallery/create', ['GalleryController', 'create'], ['AuthMiddleware']);
$router->post('/gallery/create', ['GalleryController', 'store'], ['AuthMiddleware', 'CsrfMiddleware']);
$router->get('/gallery/{id}', ['GalleryController', 'show'], ['AuthMiddleware']);
$router->post('/gallery/{id}/upload', ['GalleryController', 'uploadImages'], ['AuthMiddleware', 'CsrfMiddleware']);
$router->post('/gallery/{id}/delete', ['GalleryController', 'deleteAlbum'], ['AuthMiddleware', 'AdminMiddleware', 'CsrfMiddleware']);
$router->post('/gallery/image/{id}/delete', ['GalleryController', 'deleteImage'], ['AuthMiddleware', 'CsrfMiddleware']);
$router->post('/gallery/image/{id}/like', ['GalleryController', 'likeImage'], ['AuthMiddleware', 'CsrfMiddleware']);

// =====================================================
// SMORONIKA
// =====================================================

$router->get('/smoronika', ['SmuronikaController', 'index'], ['AuthMiddleware']);
$router->get('/smoronika/create', ['SmuronikaController', 'create'], ['AuthMiddleware']);
$router->post('/smoronika/create', ['SmuronikaController', 'store'], ['AuthMiddleware', 'CsrfMiddleware']);
$router->get('/smoronika/{id}', ['SmuronikaController', 'show'], ['AuthMiddleware']);
$router->get('/smoronika/{id}/edit', ['SmuronikaController', 'edit'], ['AuthMiddleware']);
$router->post('/smoronika/{id}/edit', ['SmuronikaController', 'update'], ['AuthMiddleware', 'CsrfMiddleware']);
$router->post('/smoronika/{id}/delete', ['SmuronikaController', 'delete'], ['AuthMiddleware', 'CsrfMiddleware']);

// =====================================================
// MEMORIAL
// =====================================================

$router->get('/memorial', ['MemorialController', 'index'], ['AuthMiddleware']);
$router->get('/memorial/create', ['MemorialController', 'create'], ['AuthMiddleware', 'AdminMiddleware']);
$router->post('/memorial/create', ['MemorialController', 'store'], ['AuthMiddleware', 'AdminMiddleware', 'CsrfMiddleware']);
$router->get('/memorial/{id}', ['MemorialController', 'show'], ['AuthMiddleware']);
$router->get('/memorial/{id}/edit', ['MemorialController', 'edit'], ['AuthMiddleware', 'AdminMiddleware']);
$router->post('/memorial/{id}/edit', ['MemorialController', 'update'], ['AuthMiddleware', 'AdminMiddleware', 'CsrfMiddleware']);
$router->post('/memorial/{id}/delete', ['MemorialController', 'delete'], ['AuthMiddleware', 'AdminMiddleware', 'CsrfMiddleware']);
$router->post('/memorial/{id}/tribute', ['MemorialController', 'tribute'], ['AuthMiddleware', 'CsrfMiddleware']);

// =====================================================
// SUPPORT
// =====================================================

$router->get('/support', ['SupportController', 'index'], ['AuthMiddleware']);
$router->get('/support/create', ['SupportController', 'create'], ['AuthMiddleware']);
$router->post('/support/create', ['SupportController', 'store'], ['AuthMiddleware', 'CsrfMiddleware']);
$router->get('/support/{id}', ['SupportController', 'show'], ['AuthMiddleware']);
$router->post('/support/{id}/delete', ['SupportController', 'delete'], ['AuthMiddleware', 'CsrfMiddleware']);

// =====================================================
// ADMIN PANEL
// =====================================================

$router->get('/admin', ['AdminController', 'dashboard'], ['AuthMiddleware', 'AdminMiddleware']);
$router->get('/admin/dashboard', ['AdminController', 'dashboard'], ['AuthMiddleware', 'AdminMiddleware']);

// User Management
$router->get('/admin/users', ['AdminController', 'users'], ['AuthMiddleware', 'AdminMiddleware']);
$router->get('/admin/users/pending', ['AdminController', 'pendingApprovals'], ['AuthMiddleware', 'AdminMiddleware']);
$router->post('/admin/users/{id}/approve', ['AdminController', 'approveUser'], ['AuthMiddleware', 'AdminMiddleware', 'CsrfMiddleware']);
$router->post('/admin/users/{id}/reject', ['AdminController', 'rejectUser'], ['AuthMiddleware', 'AdminMiddleware', 'CsrfMiddleware']);
$router->post('/admin/users/{id}/suspend', ['AdminController', 'suspendUser'], ['AuthMiddleware', 'AdminMiddleware', 'CsrfMiddleware']);
$router->post('/admin/users/{id}/delete', ['AdminController', 'deleteUser'], ['AuthMiddleware', 'AdminMiddleware', 'CsrfMiddleware']);
$router->post('/admin/users/{id}/role', ['AdminController', 'changeUserRole'], ['AuthMiddleware', 'AdminMiddleware', 'CsrfMiddleware']);

// Event Management
$router->get('/admin/events', ['AdminController', 'events'], ['AuthMiddleware', 'AdminMiddleware']);
$router->post('/admin/events/{id}/approve', ['AdminController', 'approveEvent'], ['AuthMiddleware', 'AdminMiddleware', 'CsrfMiddleware']);
$router->post('/admin/events/{id}/delete', ['AdminController', 'deleteEvent'], ['AuthMiddleware', 'AdminMiddleware', 'CsrfMiddleware']);

// Notice Management
$router->get('/admin/notices', ['AdminController', 'notices'], ['AuthMiddleware', 'AdminMiddleware']);

// Smoronika Management
$router->get('/admin/smoronika', ['AdminController', 'smoronika'], ['AuthMiddleware', 'AdminMiddleware']);
$router->post('/admin/smoronika/{id}/approve', ['AdminController', 'approveSmoronika'], ['AuthMiddleware', 'AdminMiddleware', 'CsrfMiddleware']);
$router->post('/admin/smoronika/{id}/reject', ['AdminController', 'rejectSmoronika'], ['AuthMiddleware', 'AdminMiddleware', 'CsrfMiddleware']);

// Support Management
$router->get('/admin/support', ['AdminController', 'supportRequests'], ['AuthMiddleware', 'AdminMiddleware']);
$router->get('/admin/support/{id}', ['AdminController', 'viewSupportRequest'], ['AuthMiddleware', 'AdminMiddleware']);
$router->post('/admin/support/{id}/status', ['AdminController', 'updateSupportStatus'], ['AuthMiddleware', 'AdminMiddleware', 'CsrfMiddleware']);

// Gallery Management
$router->get('/admin/gallery', ['AdminController', 'gallery'], ['AuthMiddleware', 'AdminMiddleware']);

// Reports
$router->get('/admin/reports', ['AdminController', 'reports'], ['AuthMiddleware', 'AdminMiddleware']);

// Settings
$router->get('/admin/settings', ['AdminController', 'settings'], ['AuthMiddleware', 'AdminMiddleware']);
$router->post('/admin/settings', ['AdminController', 'updateSettings'], ['AuthMiddleware', 'AdminMiddleware', 'CsrfMiddleware']);

return $router;
