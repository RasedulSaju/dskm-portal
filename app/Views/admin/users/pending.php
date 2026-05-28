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
            <div style="display: flex; gap: 24px; align-items: start;">
                <img src="<?= $user['avatar'] ? upload($user['avatar']) : 'https://ui-avatars.com/api/?name=' . urlencode($user['full_name_en']) ?>" style="width: 80px; height: 80px; border-radius: 50%;">
                <div style="flex: 1;">
                    <h3 style="font-weight: 700; font-size: 18px;"><?= htmlspecialchars($user['full_name_en']) ?></h3>
                    <p style="color: #64748b;"><?= htmlspecialchars($user['full_name_bn']) ?></p>
                    <p style="font-size: 14px; margin-top: 8px;">
                        <strong>Mobile:</strong> <?= htmlspecialchars($user['mobile']) ?><br>
                        <strong>Username:</strong> @<?= htmlspecialchars($user['username']) ?>
                    </p>
                </div>
                <div style="display: flex; gap: 8px;">
                    <button onclick="if(confirm('Approve this user?'))ajax('/admin/users/<?= $user['id'] ?>/approve',{},'POST').then(()=>location.reload())" class="btn-primary">Approve</button>
                    <button onclick="if(confirm('Reject this user?'))ajax('/admin/users/<?= $user['id'] ?>/reject',{},'POST').then(()=>location.reload())" class="btn-secondary">Reject</button>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
<?php require dirname(__DIR__,2) . '/layouts/footer.php'; ?>
