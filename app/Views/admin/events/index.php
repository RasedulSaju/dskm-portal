<?php $pageTitle = 'Event Management'; ?>
<?php require dirname(__DIR__,2) . '/layouts/header.php'; ?>
<h2 style="font-size: 24px; font-weight: 700; margin-bottom: 24px;">Event Management</h2>
<?php foreach ($events as $event): ?>
    <div class="card" style="margin-bottom: 16px;">
        <div style="display: flex; justify-content: space-between; align-items: start;">
            <div>
                <h3 style="font-weight: 700; font-size: 18px;"><?= htmlspecialchars($event['title']) ?></h3>
                <p style="color: #64748b; font-size: 14px; margin-top: 4px;"><?= formatDate($event['event_date']) ?></p>
            </div>
            <div style="display: flex; gap: 8px; align-items: center;">
                <span class="badge badge-<?= $event['status']==='published'?'success':'warning' ?>"><?= ucfirst($event['status']) ?></span>
                <?php if ($event['status'] === 'draft'): ?>
                    <button onclick="ajax('/admin/events/<?= $event['id'] ?>/approve',{},'POST').then(()=>location.reload())" class="btn-primary" style="padding: 6px 12px; font-size: 13px;">Approve</button>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endforeach; ?>
<?php require dirname(__DIR__,2) . '/layouts/footer.php'; ?>
