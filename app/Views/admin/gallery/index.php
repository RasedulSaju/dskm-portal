<?php $pageTitle = 'Gallery Management'; ?>
<?php require dirname(__DIR__,2) . '/layouts/header.php'; ?>
<h2 style="font-size: 24px; font-weight: 700; margin-bottom: 24px;">Gallery Albums</h2>
<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
    <?php foreach ($albums as $album): ?>
        <div class="card">
            <?php if ($album['cover_image']): ?>
                <img src="<?= upload($album['cover_image']) ?>" style="width: 100%; height: 150px; object-fit: cover; border-radius: 8px; margin-bottom: 12px;">
            <?php endif; ?>
            <h3 style="font-weight: 700; font-size: 16px;"><?= htmlspecialchars($album['title']) ?></h3>
            <p style="color: #64748b; font-size: 14px; margin-top: 4px;">
                <i class="fas fa-images"></i> <?= $album['image_count'] ?? 0 ?> photos
            </p>
        </div>
    <?php endforeach; ?>
</div>
<?php require dirname(__DIR__,2) . '/layouts/footer.php'; ?>
