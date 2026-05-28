<?php $pageTitle = 'স্মরণিকা'; ?>
<?php require dirname(__DIR__) . '/layouts/header.php'; ?>
<div style="display: flex; justify-content: space-between; margin-bottom: 24px;">
    <h2 style="font-size: 24px; font-weight: 700;">স্মরণিকা - Memories</h2>
    <a href="<?= url('/smoronika/create') ?>" class="btn-accent"><i class="fas fa-pen"></i> Write Article</a>
</div>
<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 20px;">
    <?php foreach ($articles as $article): ?>
        <a href="<?= url('/smoronika/<?= $article['id'] ?>') ?>" class="card">
            <?php if ($article['image']): ?>
                <img src="<?= upload($article['image']) ?>" style="width: 100%; height: 200px; object-fit: cover; border-radius: 8px; margin-bottom: 16px;">
            <?php endif; ?>
            <h3 style="font-weight: 700; color: var(--primary); margin-bottom: 8px;"><?= htmlspecialchars($article['title']) ?></h3>
            <p style="font-size: 14px; color: #64748b;">
                By <?= htmlspecialchars($article['author_name']) ?> • <?= timeAgo($article['created_at']) ?>
            </p>
        </a>
    <?php endforeach; ?>
</div>
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>
