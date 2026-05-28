<?php $pageTitle = 'Messages'; ?>
<?php require dirname(__DIR__) . '/layouts/header.php'; ?>
<div class="card" style="height: calc(100vh - 200px); display: flex; flex-direction: column;">
    <div style="padding: 16px; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; gap: 12px;">
        <img src="<?= $otherUser['avatar'] ? upload($otherUser['avatar']) : 'https://ui-avatars.com/api/?name=' . urlencode($otherUser['full_name_en']) ?>" style="width: 48px; height: 48px; border-radius: 50%;">
        <div>
            <div style="font-weight: 700;"><?= htmlspecialchars($otherUser['full_name_en']) ?></div>
            <?php if ($otherUser['is_online']): ?>
                <div style="font-size: 12px; color: #22c55e;"><i class="fas fa-circle"></i> Online</div>
            <?php endif; ?>
        </div>
    </div>
    <div id="messages" style="flex: 1; overflow-y: auto; padding: 20px;">
        <?php foreach ($messages as $msg): ?>
            <div style="display: flex; justify-content: <?= $msg['sender_id'] == auth()['id'] ? 'flex-end' : 'flex-start' ?>; margin-bottom: 16px;">
                <div style="max-width: 70%; padding: 12px 16px; border-radius: 16px; background: <?= $msg['sender_id'] == auth()['id'] ? 'var(--primary)' : '#f1f5f9' ?>; color: <?= $msg['sender_id'] == auth()['id'] ? 'white' : '#0f172a' ?>;">
                    <?= nl2br(htmlspecialchars($msg['message'])) ?>
                    <div style="font-size: 11px; margin-top: 4px; opacity: 0.7;"><?= formatDate($msg['created_at'], 'g:i A') ?></div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div style="padding: 16px; border-top: 1px solid #e2e8f0;">
        <form id="messageForm" method="POST" action="<?= url(\'/messages/send\') ?>" style="display: flex; gap: 12px;">
            <?= csrf_field() ?>
            <input type="hidden" name="receiver_id" value="<?= $otherUser['id'] ?>">
            <input type="text" name="message" placeholder="Type a message..." required style="flex: 1; padding: 12px; border: 2px solid #e2e8f0; border-radius: 24px;">
            <button type="submit" class="btn-primary" style="border-radius: 50%; width: 48px; height: 48px; padding: 0;">
                <i class="fas fa-paper-plane"></i>
            </button>
        </form>
    </div>
</div>
<script>
document.getElementById('messageForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(e.target);
    await ajax('/messages/send', Object.fromEntries(formData));
    e.target.querySelector('[name="message"]').value = '';
    location.reload();
});
</script>
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>
