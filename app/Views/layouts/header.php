<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'DSKM Batch Portal' ?> - Dakhil 2010 & Alim 2012</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        :root {
            --primary: #0B1F3A;
            --accent: #D4AF37;
            --bg: #F5F7FB;
        }
        body { background: var(--bg); }
        .sidebar { background: var(--primary); width: 260px; position: fixed; top: 0; left: 0; height: 100vh; overflow-y: auto; }
        .sidebar a { color: #cbd5e1; padding: 12px 20px; display: flex; align-items: center; gap: 12px; transition: all 0.2s; }
        .sidebar a:hover, .sidebar a.active { background: rgba(212, 175, 55, 0.1); color: var(--accent); }
        .sidebar a i { width: 20px; }
        .main-content { margin-left: 260px; padding: 24px; min-height: 100vh; }
        .navbar { background: white; padding: 16px 24px; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center; }
        .card { background: white; border-radius: 12px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        .btn-primary { background: var(--primary); color: white; padding: 10px 20px; border-radius: 8px; transition: all 0.2s; }
        .btn-primary:hover { background: #0a1829; }
        .btn-accent { background: var(--accent); color: var(--primary); padding: 10px 20px; border-radius: 8px; font-weight: 600; }
        .alert { padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; }
        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }
        .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
        .badge { padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; }
        .badge-success { background: #d1fae5; color: #065f46; }
        .badge-warning { background: #fed7aa; color: #92400e; }
        .badge-info { background: #dbeafe; color: #1e40af; }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); z-index: 1000; }
            .sidebar.active { transform: translateX(0); }
            .main-content { margin-left: 0; }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div style="padding: 24px 20px; border-bottom: 1px solid rgba(255,255,255,0.1);">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 48px; height: 48px; background: var(--accent); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 20px;">DS</div>
                <div>
                    <div style="color: white; font-weight: 700; font-size: 16px;">DSKM Portal</div>
                    <div style="color: #94a3b8; font-size: 11px;">Dakhil & Alim Alumni</div>
                </div>
            </div>
        </div>

        <nav style="padding: 16px 0;">
            <a href="/dashboard" class="<?= $_SERVER['REQUEST_URI'] === '/dashboard' ? 'active' : '' ?>">
                <i class="fas fa-home"></i> Dashboard
            </a>
            <a href="/members" class="<?= strpos($_SERVER['REQUEST_URI'], '/members') === 0 ? 'active' : '' ?>">
                <i class="fas fa-users"></i> Members
            </a>
            <a href="/events" class="<?= strpos($_SERVER['REQUEST_URI'], '/events') === 0 ? 'active' : '' ?>">
                <i class="fas fa-calendar"></i> Events
            </a>
            <a href="/notices" class="<?= strpos($_SERVER['REQUEST_URI'], '/notices') === 0 ? 'active' : '' ?>">
                <i class="fas fa-bullhorn"></i> Notices
            </a>
            <a href="/messages" class="<?= strpos($_SERVER['REQUEST_URI'], '/messages') === 0 ? 'active' : '' ?>">
                <i class="fas fa-envelope"></i> Messages
                <?php $unreadCount = (new \App\Models\Message())->getUnreadCount(auth()['id']); ?>
                <?php if ($unreadCount > 0): ?>
                    <span class="badge badge-error" style="margin-left: auto;"><?= $unreadCount ?></span>
                <?php endif; ?>
            </a>
            <a href="/gallery" class="<?= strpos($_SERVER['REQUEST_URI'], '/gallery') === 0 ? 'active' : '' ?>">
                <i class="fas fa-images"></i> Gallery
            </a>
            <a href="/smoronika" class="<?= strpos($_SERVER['REQUEST_URI'], '/smoronika') === 0 ? 'active' : '' ?>">
                <i class="fas fa-book"></i> Smoronika
            </a>
            <a href="/memorial" class="<?= strpos($_SERVER['REQUEST_URI'], '/memorial') === 0 ? 'active' : '' ?>">
                <i class="fas fa-dove"></i> Memorial
            </a>
            <a href="/support" class="<?= strpos($_SERVER['REQUEST_URI'], '/support') === 0 ? 'active' : '' ?>">
                <i class="fas fa-hands-helping"></i> Support
            </a>

            <?php if (\Core\Auth::isAdmin()): ?>
                <div style="margin: 16px 20px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 16px;">
                    <div style="color: #64748b; font-size: 11px; text-transform: uppercase; font-weight: 600; margin-bottom: 8px;">Admin</div>
                </div>
                <a href="/admin/dashboard" class="<?= strpos($_SERVER['REQUEST_URI'], '/admin') === 0 ? 'active' : '' ?>">
                    <i class="fas fa-shield-alt"></i> Admin Panel
                </a>
            <?php endif; ?>

            <div style="margin: 16px 20px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 16px;">
                <form action="/logout" method="POST" style="margin: 0;">
                    <?= csrf_field() ?>
                    <button type="submit" style="color: #cbd5e1; padding: 12px 0; width: 100%; text-align: left; display: flex; align-items: center; gap: 12px; background: none; border: none; cursor: pointer;">
                        <i class="fas fa-sign-out-alt" style="width: 20px;"></i> Logout
                    </button>
                </form>
            </div>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        <div class="navbar">
            <div>
                <h1 style="font-size: 24px; font-weight: 700; color: var(--primary);"><?= $pageTitle ?? 'Dashboard' ?></h1>
            </div>
            <div style="display: flex; align-items: center; gap: 16px;">
                <a href="/dashboard/profile" style="display: flex; align-items: center; gap: 12px;">
                    <?php $user = auth(); ?>
                    <div style="text-align: right;">
                        <div style="font-weight: 600; color: var(--primary);"><?= htmlspecialchars($user['full_name_en'] ?? $user['username']) ?></div>
                        <div style="font-size: 12px; color: #64748b;">View Profile</div>
                    </div>
                    <img src="<?= $user['avatar'] ? upload($user['avatar']) : 'https://ui-avatars.com/api/?name=' . urlencode($user['full_name_en'] ?? $user['username']) ?>" 
                         alt="Avatar" 
                         style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid var(--accent);">
                </a>
            </div>
        </div>

        <!-- Flash Messages -->
        <?php if ($success = \Core\Session::getFlash('success')): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <?php if ($error = \Core\Session::getFlash('error')): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <!-- Page Content -->
