<?php $pageTitle = 'Change Password'; ?>
<?php require dirname(__DIR__) . '/layouts/header.php'; ?>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <h2 style="font-size: 24px; font-weight: 700; color: var(--primary); margin-bottom: 24px;">
        <i class="fas fa-lock"></i> Change Password
    </h2>

    <form method="POST" action="<?= url(\'/dashboard/change-password\') ?>">
        <?= csrf_field() ?>

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px;">Current Password</label>
            <input type="password" name="current_password" required class="w-full px-4 py-3 border-2 rounded-lg">
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px;">New Password</label>
            <input type="password" name="new_password" required class="w-full px-4 py-3 border-2 rounded-lg">
        </div>

        <div style="margin-bottom: 24px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px;">Confirm New Password</label>
            <input type="password" name="password_confirmation" required class="w-full px-4 py-3 border-2 rounded-lg">
        </div>

        <button type="submit" class="btn-primary">
            <i class="fas fa-key"></i> Update Password
        </button>
    </form>
</div>

<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>
