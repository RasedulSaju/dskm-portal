<?php $pageTitle = 'Pending Approvals'; ?>
<?php require dirname(__DIR__,2) . '/layouts/header.php'; ?>
<h2 style="font-size: 24px; font-weight: 700; margin-bottom: 24px;">Pending User Approvals</h2>
<?php if (empty($users)): ?>
    <div class="card" style="text-align: center; padding: 60px 20px; color: #64748b;">
        <i class="fas fa-check-circle" style="font-size: 64px; opacity: 0.3; margin-bottom: 16px;"></i>
        <p style="font-size: 18px;">No pending approvals</p>
    </div>
<?php else: ?>
    <?php foreach ($users as $user): ?>
        <div class="card" style="margin-bottom: 16px;">
            <div style="display: flex; gap: 24px; align-items: center;">
                <img src="<?= $user['avatar'] ? upload($user['avatar']) : 'https://ui-avatars.com/api/?name=' . urlencode($user['full_name_en'] ?? 'User') . '&size=200' ?>"
                     style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover;">
                <div style="flex: 1;">
                    <h3 style="font-weight: 700; font-size: 18px;"><?= htmlspecialchars($user['full_name_en'] ?? '') ?></h3>
                    <p style="color: #64748b;"><?= htmlspecialchars($user['full_name_bn'] ?? '') ?></p>
                    <p style="font-size: 14px; margin-top: 8px;">
                        <strong>Mobile:</strong> <?= htmlspecialchars($user['mobile'] ?? '') ?><br>
                        <strong>Username:</strong> @<?= htmlspecialchars($user['username'] ?? '') ?><br>
                        <strong>Email:</strong> <?= htmlspecialchars($user['email'] ?? '') ?>
                    </p>
                </div>
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <form method="POST" action="<?= url('/admin/users/' . $user['id'] . '/approve') ?>">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn-primary" style="width: 100%;">
                            <i class="fas fa-check"></i> Approve
                        </button>
                    </form>
                    <form method="POST" action="<?= url('/admin/users/' . $user['id'] . '/reject') ?>">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn-secondary" style="width: 100%;"
                                onclick="return confirm('Reject this user?')">
                            <i class="fas fa-times"></i> Reject
                        </button>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
<?php require dirname(__DIR__,2) . '/layouts/footer.php'; ?>
