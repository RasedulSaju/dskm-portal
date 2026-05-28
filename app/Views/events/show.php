<?php $pageTitle = $event['title']; ?>
<?php require dirname(__DIR__) . '/layouts/header.php'; ?>
<div class="card" style="max-width: 900px; margin: 0 auto;">
    <?php if ($event['banner']): ?>
        <img src="<?= upload($event['banner']) ?>" alt="Event" style="width: 100%; height: 400px; object-fit: cover; border-radius: 12px; margin-bottom: 24px;">
    <?php endif; ?>
    <h1 style="font-size: 32px; font-weight: 800; color: var(--primary); margin-bottom: 16px;"><?= htmlspecialchars($event['title']) ?></h1>
    <div style="display: flex; gap: 24px; margin-bottom: 24px; flex-wrap: wrap;">
        <div><i class="fas fa-calendar"></i> <?= formatDate($event['event_date'], 'd M Y, g:i A') ?></div>
        <?php if ($event['venue']): ?><div><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($event['venue']) ?></div><?php endif; ?>
        <div><i class="fas fa-users"></i> <?= $event['going_count'] ?> going</div>
    </div>
    <div style="margin-bottom: 24px;"><?= nl2br(htmlspecialchars($event['description'])) ?></div>
    <?php if ($userRsvp): ?>
        <p style="color: #22c55e; font-weight: 600;">You're <?= $userRsvp ?>!</p>
    <?php else: ?>
        <form method="POST" action="/events/<?= $event['id'] ?>/rsvp" style="display: flex; gap: 12px;">
            <?= csrf_field() ?>
            <button type="submit" name="status" value="going" class="btn-primary">I'm Going</button>
            <button type="submit" name="status" value="maybe" class="btn-secondary">Maybe</button>
        </form>
    <?php endif; ?>
</div>
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>
