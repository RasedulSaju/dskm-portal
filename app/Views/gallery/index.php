<?php $pageTitle = 'Gallery'; ?>
<?php require dirname(__DIR__) . '/layouts/header.php'; ?>
<div style="display: flex; justify-content: space-between; margin-bottom: 24px;">
    <h2 style="font-size: 24px; font-weight: 700;">Photo Gallery</h2>
    <?php if (\Core\Auth::isModerator()): ?>
        <a href="/gallery/create" class="btn-accent"><i class="fas fa-plus"></i> Create Album</a>
    <?php endif; ?>
</div>
<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
    <?php foreach ($albums as $album): ?>
        <a href="/gallery/<?= $album['id'] ?>" class="card" style="padding: 0; overflow: hidden;">
            <?php if ($album['cover_image']): ?>
                <img src="<?= upload($album['cover_image']) ?>" style="width: 100%; height: 200px; object-fit: cover;">
            <?php else: ?>
                <div style="width: 100%; height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"></div>
            <?php endif; ?>
            <div style="padding: 16px;">
                <h3 style="font-weight: 700; color: var(--primary);"><?= htmlspecialchars($album['title']) ?></h3>
                <p style="font-size: 13px; color: #64748b; margin-top: 4px;">
                    <?= $album['image_count'] ?> photos
                </p>
            </div>
        </a>
    <?php endforeach; ?>
</div>
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>
