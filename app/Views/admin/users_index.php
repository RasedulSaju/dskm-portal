<?php $pageTitle = 'User Management'; ?>
<?php require dirname(__DIR__,2) . '/layouts/header.php'; ?>
<div style="display: flex; justify-content: space-between; margin-bottom: 24px;">
    <h2 style="font-size: 24px; font-weight: 700;">User Management</h2>
    <a href="/admin/users/pending" class="btn-accent">Pending Approvals</a>
</div>
<div class="card">
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="border-bottom: 2px solid #e2e8f0;">
                <th style="padding: 12px; text-align: left;">User</th>
                <th style="padding: 12px; text-align: left;">Contact</th>
                <th style="padding: 12px; text-align: left;">Status</th>
                <th style="padding: 12px; text-align: left;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr style="border-bottom: 1px solid #f1f5f9;">
                    <td style="padding: 12px;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <img src="<?= $user['avatar'] ? upload($user['avatar']) : 'https://ui-avatars.com/api/?name=' . urlencode($user['full_name_en']) ?>" style="width: 40px; height: 40px; border-radius: 50%;">
                            <div>
                                <div style="font-weight: 600;"><?= htmlspecialchars($user['full_name_en']) ?></div>
                                <div style="font-size: 12px; color: #64748b;">@<?= htmlspecialchars($user['username']) ?></div>
                            </div>
                        </div>
                    </td>
                    <td style="padding: 12px; font-size: 14px;"><?= htmlspecialchars($user['mobile']) ?></td>
                    <td style="padding: 12px;">
                        <span class="badge badge-<?= $user['status']==='active'?'success':'warning' ?>"><?= ucfirst($user['status']) ?></span>
                    </td>
                    <td style="padding: 12px;">
                        <?php if ($user['status'] === 'pending'): ?>
                            <button onclick="ajax('/admin/users/<?= $user['id'] ?>/approve',{},'POST').then(()=>location.reload())" class="btn-primary" style="padding: 6px 12px; font-size: 13px;">Approve</button>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php require dirname(__DIR__,2) . '/layouts/footer.php'; ?>
