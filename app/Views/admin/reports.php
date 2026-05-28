<?php $pageTitle = 'Reports'; ?>
<?php require dirname(__DIR__) . '/layouts/header.php'; ?>
<h2 style="font-size: 24px; font-weight: 700; margin-bottom: 24px;">System Reports</h2>
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
    <div class="card">
        <h3 style="font-weight: 700; margin-bottom: 16px;">Member Statistics</h3>
        <p>Total: <?= $memberStats['totals']['total_members'] ?></p>
        <p>Pending: <?= $memberStats['totals']['pending_approvals'] ?></p>
        <p>Online: <?= $memberStats['totals']['online_now'] ?></p>
    </div>
    <div class="card">
        <h3 style="font-weight: 700; margin-bottom: 16px;">Content Statistics</h3>
        <p>Notices: <?= $contentStats['notices'] ?></p>
        <p>Smoronika: <?= $contentStats['smoronika'] ?></p>
        <p>Galleries: <?= $contentStats['galleries'] ?></p>
        <p>Memorials: <?= $contentStats['memorials'] ?></p>
    </div>
    <div class="card">
        <h3 style="font-weight: 700; margin-bottom: 16px;">Support Statistics</h3>
        <p>Total: <?= $supportStats['total'] ?></p>
        <p>Pending: <?= $supportStats['pending'] ?></p>
        <p>Approved: <?= $supportStats['approved'] ?></p>
        <p>Resolved: <?= $supportStats['resolved'] ?></p>
    </div>
</div>
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>
