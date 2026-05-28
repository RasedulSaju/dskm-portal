<?php $pageTitle = 'Notices'; ?>
<?php require dirname(__DIR__) . '/layouts/header.php'; ?>
<div style="display: flex; justify-content: space-between; margin-bottom: 24px;">
    <h2 style="font-size: 24px; font-weight: 700;">Announcements</h2>
    <?php if (\Core\Auth::isAdmin()): ?>
        <a href="<?= url('/notices/create') ?>" class="btn-accent"><i class="fas fa-plus"></i> Create Notice</a>
    <?php endif; ?>
</div>
<?php foreach ($notices as $notice): ?>
    <div class="card" style="margin-bottom: 16px;">
        <div style="display: flex; justify-content: between; align-items: start;">
            <div style="flex: 1;">
                <?php if ($notice['is_pinned']): ?><i class="fas fa-thumbtack" style="color: var(--accent);"></i><?php endif; ?>
                <a href="<?= url('/notices/' . $notice['id'] . '') ?>" style="font-size: 20px; font-weight: 700; color: var(--primary);">
                    <?= htmlspecialchars($notice['title']) ?>
                </a>
                <p style="color: #64748b; font-size: 14px; margin-top: 8px;">
                    <span class="badge badge-info"><?= ucfirst($notice['category']) ?></span>
                    <span style="margin-left: 12px;"><i class="fas fa-user"></i> <?= htmlspecialchars($notice['author_name']) ?></span>
                    <span style="margin-left: 12px;"><i class="fas fa-clock"></i> <?= timeAgo($notice['created_at']) ?></span>
                    <span style="margin-left: 12px;"><i class="fas fa-eye"></i> <?= $notice['views'] ?> views</span>
                </p>
            </div>
        </div>
    </div>
<?php endforeach; ?>
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>
