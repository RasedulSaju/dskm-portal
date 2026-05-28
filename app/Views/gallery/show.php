<?php $pageTitle = $album['title']; ?>
<?php require dirname(__DIR__) . '/layouts/header.php'; ?>
<div class="card" style="margin-bottom: 24px;">
    <h2 style="font-size: 24px; font-weight: 700;"><?= htmlspecialchars($album['title']) ?></h2>
    <?php if ($album['description']): ?>
        <p style="color: #64748b; margin-top: 8px;"><?= htmlspecialchars($album['description']) ?></p>
    <?php endif; ?>
</div>
<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 16px;">
    <?php foreach ($images as $image): ?>
        <div class="card" style="padding: 0; overflow: hidden;">
            <img src="<?= upload($image['image_path']) ?>" style="width: 100%; height: 250px; object-fit: cover; cursor: pointer;" onclick="window.open(this.src)">
            <?php if ($image['caption']): ?>
                <div style="padding: 12px;">
                    <p style="font-size: 14px;"><?= htmlspecialchars($image['caption']) ?></p>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>
