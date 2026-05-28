<?php $pageTitle = 'Notice Management'; ?>
<?php require dirname(__DIR__,2) . '/layouts/header.php'; ?>
<div style="display: flex; justify-content: space-between; margin-bottom: 24px;">
    <h2 style="font-size: 24px; font-weight: 700;">Notice Management</h2>
    <a href="<?= url('/notices/create') ?>" class="btn-accent"><i class="fas fa-plus"></i> Create Notice</a>
</div>
<?php foreach ($notices as $notice): ?>
    <div class="card" style="margin-bottom: 16px;">
        <h3 style="font-weight: 700; font-size: 18px;"><?= htmlspecialchars($notice['title']) ?></h3>
        <p style="color: #64748b; font-size: 14px; margin-top: 8px;">
            <span class="badge badge-info"><?= ucfirst($notice['category']) ?></span>
            <span style="margin-left: 12px;"><?= timeAgo($notice['created_at']) ?></span>
            <span style="margin-left: 12px;"><i class="fas fa-eye"></i> <?= $notice['views'] ?> views</span>
        </p>
    </div>
<?php endforeach; ?>
<?php require dirname(__DIR__,2) . '/layouts/footer.php'; ?>
