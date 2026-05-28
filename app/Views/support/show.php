<?php $pageTitle = $request['subject']; ?>
<?php require dirname(__DIR__) . '/layouts/header.php'; ?>
<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 24px;">
        <h1 style="font-size: 28px; font-weight: 800;"><?= htmlspecialchars($request['subject']) ?></h1>
        <span class="badge <?= $request['status'] === 'approved' ? 'badge-success' : ($request['status'] === 'rejected' ? 'badge-error' : 'badge-warning') ?>">
            <?= ucfirst($request['status']) ?>
        </span>
    </div>
    <div style="margin-bottom: 24px; padding-bottom: 16px; border-bottom: 1px solid #e2e8f0;">
        <span class="badge badge-info"><?= ucfirst($request['category']) ?></span>
        <span style="margin-left: 16px; color: #64748b;"><?= formatDate($request['created_at']) ?></span>
    </div>
    <div style="line-height: 1.8; margin-bottom: 24px;">
        <?= nl2br(htmlspecialchars($request['description'])) ?>
    </div>
    <?php if ($request['attachment']): ?>
        <a href="<?= upload($request['attachment']) ?>" target="_blank" class="btn-secondary" style="display: inline-block; margin-bottom: 24px;">
            <i class="fas fa-paperclip"></i> View Attachment
        </a>
    <?php endif; ?>
    <?php if ($request['admin_note']): ?>
        <div style="padding: 16px; background: #f8fafc; border-left: 4px solid var(--accent); border-radius: 8px;">
            <h3 style="font-weight: 700; margin-bottom: 8px;">Admin Note:</h3>
            <p><?= nl2br(htmlspecialchars($request['admin_note'])) ?></p>
        </div>
    <?php endif; ?>
</div>
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>
