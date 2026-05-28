<?php $pageTitle = $memorial['full_name']; ?>
<?php require dirname(__DIR__) . '/layouts/header.php'; ?>
<div class="card" style="max-width: 800px; margin: 0 auto; background: #f8fafc;">
    <div style="text-align: center; padding: 40px 0;">
        <?php if ($memorial['photo']): ?>
            <img src="<?= upload($memorial['photo']) ?>" style="width: 180px; height: 180px; border-radius: 50%; object-fit: cover; margin-bottom: 24px; border: 6px solid #cbd5e1;">
        <?php endif; ?>
        <h1 style="font-size: 32px; font-weight: 800; color: var(--primary); margin-bottom: 8px;"><?= htmlspecialchars($memorial['full_name']) ?></h1>
        <?php if ($memorial['full_name_bn']): ?>
            <p style="font-size: 20px; color: #64748b; margin-bottom: 16px;"><?= htmlspecialchars($memorial['full_name_bn']) ?></p>
        <?php endif; ?>
        <?php if ($memorial['batch_name']): ?>
            <p style="font-size: 16px; color: var(--accent); font-weight: 600; margin-bottom: 8px;"><?= htmlspecialchars($memorial['batch_name']) ?></p>
        <?php endif; ?>
        <?php if ($memorial['date_of_death']): ?>
            <p style="font-size: 14px; color: #64748b;">Passed away on <?= formatDate($memorial['date_of_death'], 'd F Y') ?></p>
        <?php endif; ?>
    </div>
    <?php if ($memorial['description']): ?>
        <div style="padding: 24px; background: white; border-radius: 12px; margin-top: 24px;">
            <p style="line-height: 1.8; color: #374151;"><?= nl2br(htmlspecialchars($memorial['description'])) ?></p>
        </div>
    <?php endif; ?>
    <div style="text-align: center; margin-top: 24px;">
        <form method="POST" action="<?= url('/memorial/<?= $memorial['id'] ?>/tribute') ?>" style="display: inline;">
            <?= csrf_field() ?>
            <button type="submit" class="btn-primary">
                <i class="fas fa-heart"></i> Pay Tribute (<?= $memorial['tributes'] ?>)
            </button>
        </form>
    </div>
</div>
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>
