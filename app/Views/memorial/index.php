<?php $pageTitle = 'Memorial'; ?>
<?php require dirname(__DIR__) . '/layouts/header.php'; ?>
<div style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); padding: 60px 40px; border-radius: 16px; text-align: center; color: white; margin-bottom: 40px;">
    <i class="fas fa-dove" style="font-size: 48px; opacity: 0.8; margin-bottom: 16px;"></i>
    <h1 style="font-size: 36px; font-weight: 800; margin-bottom: 16px;">In Loving Memory</h1>
    <p style="opacity: 0.9;">Remembering those who left an indelible mark on our lives</p>
</div>
<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
    <?php foreach ($memorials as $memorial): ?>
        <a href="<?= url('/memorial/' . $memorial['id'] . '') ?>" class="card" style="text-align: center; background: #f8fafc;">
            <?php if ($memorial['photo']): ?>
                <img src="<?= upload($memorial['photo']) ?>" style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; margin: 0 auto 16px; border: 4px solid #cbd5e1;">
            <?php endif; ?>
            <h3 style="font-weight: 700; color: var(--primary); margin-bottom: 4px;"><?= htmlspecialchars($memorial['full_name']) ?></h3>
            <?php if ($memorial['full_name_bn']): ?>
                <p style="color: #64748b; font-size: 14px; margin-bottom: 8px;"><?= htmlspecialchars($memorial['full_name_bn']) ?></p>
            <?php endif; ?>
            <?php if ($memorial['batch_name']): ?>
                <p style="font-size: 13px; color: var(--accent); font-weight: 600;"><?= htmlspecialchars($memorial['batch_name']) ?></p>
            <?php endif; ?>
            <?php if ($memorial['date_of_death']): ?>
                <p style="font-size: 12px; color: #64748b; margin-top: 8px;"><?= formatDate($memorial['date_of_death'], 'd M Y') ?></p>
            <?php endif; ?>
        </a>
    <?php endforeach; ?>
</div>
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>
