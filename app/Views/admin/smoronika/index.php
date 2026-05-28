<?php $pageTitle = 'Smoronika Management'; ?>
<?php require dirname(__DIR__,2) . '/layouts/header.php'; ?>
<h2 style="font-size: 24px; font-weight: 700; margin-bottom: 24px;">Smoronika Articles (<?= ucfirst($status) ?>)</h2>
<?php foreach ($articles as $article): ?>
    <div class="card" style="margin-bottom: 16px;">
        <div style="display: flex; justify-content: space-between; align-items: start;">
            <div style="flex: 1;">
                <h3 style="font-weight: 700; font-size: 18px;"><?= htmlspecialchars($article['title']) ?></h3>
                <p style="color: #64748b; font-size: 14px; margin-top: 4px;">By <?= htmlspecialchars($article['author_name']) ?> • <?= timeAgo($article['created_at']) ?></p>
            </div>
            <div style="display: flex; gap: 8px;">
                <?php if ($article['status'] === 'pending'): ?>
                    <button onclick="ajax('/admin/smoronika/<?= $article['id'] ?>/approve',{},'POST').then(()=>location.reload())" class="btn-primary" style="padding: 6px 12px; font-size: 13px;">Approve</button>
                    <button onclick="ajax('/admin/smoronika/<?= $article['id'] ?>/reject',{},'POST').then(()=>location.reload())" class="btn-secondary" style="padding: 6px 12px; font-size: 13px;">Reject</button>
                <?php else: ?>
                    <span class="badge badge-<?= $article['status']==='published'?'success':'warning' ?>"><?= ucfirst($article['status']) ?></span>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endforeach; ?>
<?php require dirname(__DIR__,2) . '/layouts/footer.php'; ?>
