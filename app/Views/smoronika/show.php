<?php $pageTitle = $article['title']; ?>
<?php require dirname(__DIR__) . '/layouts/header.php'; ?>
<div class="card" style="max-width: 900px; margin: 0 auto;">
    <?php if ($article['image']): ?>
        <img src="<?= upload($article['image']) ?>" style="width: 100%; max-height: 400px; object-fit: cover; border-radius: 12px; margin-bottom: 24px;">
    <?php endif; ?>
    <h1 style="font-size: 32px; font-weight: 800; margin-bottom: 16px;"><?= htmlspecialchars($article['title']) ?></h1>
    <div style="display: flex; gap: 16px; margin-bottom: 24px; padding-bottom: 16px; border-bottom: 1px solid #e2e8f0;">
        <span><i class="fas fa-user"></i> <?= htmlspecialchars($article['author_name']) ?></span>
        <span><i class="fas fa-clock"></i> <?= formatDate($article['created_at']) ?></span>
        <span><i class="fas fa-eye"></i> <?= $article['views'] ?> views</span>
    </div>
    <div style="line-height: 1.8; color: #374151;">
        <?= nl2br(htmlspecialchars($article['content'])) ?>
    </div>
</div>
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>
