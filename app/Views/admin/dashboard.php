<?php $pageTitle = 'Admin Dashboard'; ?>
<?php require dirname(__DIR__) . '/layouts/header.php'; ?>
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 30px;">
    <div class="card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
        <div style="font-size: 14px; opacity: 0.9;">Total Members</div>
        <div style="font-size: 36px; font-weight: 800;"><?= $stats['total_members'] ?></div>
    </div>
    <div class="card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
        <div style="font-size: 14px; opacity: 0.9;">Pending Approvals</div>
        <div style="font-size: 36px; font-weight: 800;"><?= $stats['pending_users'] ?></div>
    </div>
    <div class="card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
        <div style="font-size: 14px; opacity: 0.9;">Total Events</div>
        <div style="font-size: 36px; font-weight: 800;"><?= $stats['total_events'] ?></div>
    </div>
    <div class="card" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); color: white;">
        <div style="font-size: 14px; opacity: 0.9;">Pending Support</div>
        <div style="font-size: 36px; font-weight: 800;"><?= $stats['pending_support'] ?></div>
    </div>
</div>
<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
    <div class="card">
        <h3 style="font-weight: 700; margin-bottom: 16px;">Recent Activity</h3>
        <?php foreach (array_slice($recentActivity, 0, 10) as $activity): ?>
            <div style="padding: 12px; background: #f8fafc; border-radius: 8px; margin-bottom: 8px; display: flex; align-items: center; gap: 12px;">
                <img src="<?= $activity['avatar'] ? upload($activity['avatar']) : 'https://ui-avatars.com/api/?name=' . urlencode($activity['full_name_en']) ?>" style="width: 32px; height: 32px; border-radius: 50%;">
                <div style="flex: 1;">
                    <div style="font-size: 14px; color: var(--primary);"><?= htmlspecialchars($activity['description']) ?></div>
                    <div style="font-size: 12px; color: #64748b;"><?= timeAgo($activity['created_at']) ?></div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="card">
        <h3 style="font-weight: 700; margin-bottom: 16px;">Batch Distribution</h3>
        <?php foreach ($batchDistribution as $batch): ?>
            <div style="margin-bottom: 16px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                    <span style="font-size: 14px;"><?= htmlspecialchars($batch['name']) ?></span>
                    <span style="font-weight: 700;"><?= $batch['count'] ?></span>
                </div>
                <div style="height: 8px; background: #e2e8f0; border-radius: 4px; overflow: hidden;">
                    <div style="height: 100%; background: var(--accent); width: <?= ($batch['count'] / max(array_column($batchDistribution, 'count'))) * 100 ?>%;"></div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>
