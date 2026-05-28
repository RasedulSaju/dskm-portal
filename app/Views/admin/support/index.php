<?php $pageTitle = 'Support Management'; ?>
<?php require dirname(__DIR__,2) . '/layouts/header.php'; ?>
<h2 style="font-size: 24px; font-weight: 700; margin-bottom: 24px;">Support Requests</h2>
<?php foreach ($requests as $request): ?>
    <div class="card" style="margin-bottom: 16px;">
        <div style="display: flex; justify-content: space-between; align-items: start;">
            <div style="flex: 1;">
                <a href="<?= url('/admin/support/' . $request['id'] . '') ?>" style="font-weight: 700; color: var(--primary); font-size: 18px;">
                    <?= htmlspecialchars($request['subject']) ?>
                </a>
                <p style="color: #64748b; font-size: 14px; margin-top: 8px;">
                    <span class="badge badge-info"><?= ucfirst($request['category']) ?></span>
                    <span style="margin-left: 12px;">By <?= htmlspecialchars($request['requester_name']) ?></span>
                    <span style="margin-left: 12px;"><?= timeAgo($request['created_at']) ?></span>
                </p>
            </div>
            <span class="badge badge-<?= $request['status']==='approved'?'success':($request['status']==='rejected'?'error':'warning') ?>"><?= ucfirst($request['status']) ?></span>
        </div>
    </div>
<?php endforeach; ?>
<?php require dirname(__DIR__,2) . '/layouts/footer.php'; ?>
