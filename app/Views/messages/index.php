<?php $pageTitle = 'Messages'; ?>
<?php require dirname(__DIR__) . '/layouts/header.php'; ?>
<div style="display: grid; grid-template-columns: 350px 1fr; gap: 24px; height: calc(100vh - 200px);">
    <div class="card" style="overflow-y: auto;">
        <h3 style="font-weight: 700; margin-bottom: 16px;">Conversations</h3>
        <?php foreach ($conversations as $conv): ?>
            <a href="/messages/<?= $conv['other_user_id'] ?>" style="display: flex; gap: 12px; padding: 12px; background: #f8fafc; border-radius: 8px; margin-bottom: 8px;">
                <img src="<?= $conv['avatar'] ? upload($conv['avatar']) : 'https://ui-avatars.com/api/?name=' . urlencode($conv['full_name_en']) ?>" style="width: 48px; height: 48px; border-radius: 50%;">
                <div style="flex: 1; min-width: 0;">
                    <div style="font-weight: 600; color: var(--primary);"><?= htmlspecialchars($conv['full_name_en']) ?></div>
                    <div style="font-size: 13px; color: #64748b; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                        <?= htmlspecialchars(substr($conv['last_message'] ?? '', 0, 40)) ?>
                    </div>
                </div>
                <?php if ($conv['unread_count'] > 0): ?>
                    <span class="badge badge-error"><?= $conv['unread_count'] ?></span>
                <?php endif; ?>
            </a>
        <?php endforeach; ?>
    </div>
    <div class="card" style="display: flex; align-items: center; justify-content: center; color: #64748b;">
        <div style="text-align: center;">
            <i class="fas fa-comments" style="font-size: 64px; opacity: 0.3; margin-bottom: 16px;"></i>
            <p>Select a conversation to start messaging</p>
        </div>
    </div>
</div>
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>
