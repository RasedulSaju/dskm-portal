<?php $pageTitle = 'User Management'; ?>
<?php require dirname(__DIR__,2) . '/layouts/header.php'; ?>
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <h2 style="font-size: 24px; font-weight: 700;">All Members</h2>
    <a href="<?= url('/admin/users/pending') ?>" class="btn-accent">
        <i class="fas fa-user-check"></i> Pending Approvals
    </a>
</div>
<div class="card">
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="border-bottom: 2px solid #e2e8f0; background: #f8fafc;">
                <th style="padding: 12px 16px; text-align: left; font-weight: 600;">User</th>
                <th style="padding: 12px 16px; text-align: left; font-weight: 600;">Contact</th>
                <th style="padding: 12px 16px; text-align: left; font-weight: 600;">Status</th>
                <th style="padding: 12px 16px; text-align: left; font-weight: 600;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr style="border-bottom: 1px solid #f1f5f9;">
                    <td style="padding: 12px 16px;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <img src="<?= $user['avatar'] ? upload($user['avatar']) : 'https://ui-avatars.com/api/?name=' . urlencode($user['full_name_en'] ?? 'User') . '&size=100' ?>"
                                 style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                            <div>
                                <div style="font-weight: 600;"><?= htmlspecialchars($user['full_name_en'] ?? '') ?></div>
                                <div style="font-size: 12px; color: #64748b;">@<?= htmlspecialchars($user['username'] ?? '') ?></div>
                            </div>
                        </div>
                    </td>
                    <td style="padding: 12px 16px; font-size: 14px;"><?= htmlspecialchars($user['mobile'] ?? '') ?></td>
                    <td style="padding: 12px 16px;">
                        <?php
                            $statusColors = [
                                'active'   => 'badge-success',
                                'pending'  => 'badge-warning',
                                'rejected' => 'badge-error',
                                'suspended'=> 'badge-error',
                            ];
                            $badgeClass = $statusColors[$user['status']] ?? 'badge-warning';
                        ?>
                        <span class="badge <?= $badgeClass ?>"><?= ucfirst($user['status']) ?></span>
                    </td>
                    <td style="padding: 12px 16px;">
                        <?php if ($user['status'] === 'pending'): ?>
                            <form method="POST" action="<?= url('/admin/users/' . $user['id'] . '/approve') ?>" style="display: inline;">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn-primary" style="padding: 6px 12px; font-size: 13px;">
                                    Approve
                                </button>
                            </form>
                            <form method="POST" action="<?= url('/admin/users/' . $user['id'] . '/reject') ?>" style="display: inline;">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn-secondary" style="padding: 6px 12px; font-size: 13px;"
                                        onclick="return confirm('Reject this user?')">
                                    Reject
                                </button>
                            </form>
                        <?php elseif ($user['status'] === 'active'): ?>
                            <form method="POST" action="<?= url('/admin/users/' . $user['id'] . '/suspend') ?>" style="display: inline;">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn-secondary" style="padding: 6px 12px; font-size: 13px;"
                                        onclick="return confirm('Suspend this user?')">
                                    Suspend
                                </button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php require dirname(__DIR__,2) . '/layouts/footer.php'; ?>
