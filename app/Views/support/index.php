<?php $pageTitle = 'Support Requests'; ?>
<?php require dirname(__DIR__) . '/layouts/header.php'; ?>
<div style="display: flex; justify-content: space-between; margin-bottom: 24px;">
    <h2 style="font-size: 24px; font-weight: 700;">My Support Requests</h2>
    <a href="/support/create" class="btn-accent"><i class="fas fa-plus"></i> New Request</a>
</div>
<?php foreach ($requests as $request): ?>
    <div class="card" style="margin-bottom: 16px;">
        <div style="display: flex; justify-content: space-between; align-items: start;">
            <div style="flex: 1;">
                <a href="/support/<?= $request['id'] ?>" style="font-size: 18px; font-weight: 700; color: var(--primary);">
                    <?= htmlspecialchars($request['subject']) ?>
                </a>
                <p style="font-size: 14px; color: #64748b; margin-top: 8px;">
                    <span class="badge badge-info"><?= ucfirst($request['category']) ?></span>
                    <span style="margin-left: 12px;"><?= timeAgo($request['created_at']) ?></span>
                </p>
            </div>
            <span class="badge <?= $request['status'] === 'approved' ? 'badge-success' : ($request['status'] === 'rejected' ? 'badge-error' : 'badge-warning') ?>">
                <?= ucfirst($request['status']) ?>
            </span>
        </div>
    </div>
<?php endforeach; ?>
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>
