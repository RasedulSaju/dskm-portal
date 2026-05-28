<?php $pageTitle = $notice['title']; ?>
<?php require dirname(__DIR__) . '/layouts/header.php'; ?>
<div class="card" style="max-width: 900px; margin: 0 auto;">
    <h1 style="font-size: 32px; font-weight: 800; margin-bottom: 16px;"><?= htmlspecialchars($notice['title']) ?></h1>
    <div style="display: flex; gap: 16px; margin-bottom: 24px; padding-bottom: 16px; border-bottom: 1px solid #e2e8f0;">
        <span class="badge badge-info"><?= ucfirst($notice['category']) ?></span>
        <span><i class="fas fa-user"></i> <?= htmlspecialchars($notice['author_name']) ?></span>
        <span><i class="fas fa-clock"></i> <?= formatDate($notice['created_at']) ?></span>
        <span><i class="fas fa-eye"></i> <?= $notice['views'] ?> views</span>
    </div>
    <div style="line-height: 1.8; color: #374151; margin-bottom: 24px;">
        <?= nl2br(htmlspecialchars($notice['content'])) ?>
    </div>
    <?php if ($notice['attachment']): ?>
        <a href="<?= upload($notice['attachment']) ?>" target="_blank" class="btn-primary" style="display: inline-block;">
            <i class="fas fa-download"></i> Download Attachment
        </a>
    <?php endif; ?>
</div>
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>
