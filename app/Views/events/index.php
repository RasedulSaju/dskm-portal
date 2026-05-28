<?php $pageTitle = 'Events'; ?>
<?php require dirname(__DIR__) . '/layouts/header.php'; ?>
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <h2 style="font-size: 24px; font-weight: 700;">Upcoming Events</h2>
    <a href="<?= url('/events/create') ?>" class="btn-accent"><i class="fas fa-plus"></i> Create Event</a>
</div>
<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 20px;">
    <?php foreach ($events as $event): ?>
        <a href="<?= url('/events/' . $event['id'] . '') ?>" class="card">
            <?php if ($event['banner']): ?>
                <img src="<?= upload($event['banner']) ?>" alt="Event" style="width: 100%; height: 200px; object-fit: cover; border-radius: 8px; margin-bottom: 16px;">
            <?php endif; ?>
            <h3 style="font-weight: 700; color: var(--primary); margin-bottom: 8px;"><?= htmlspecialchars($event['title']) ?></h3>
            <p style="font-size: 14px; color: #64748b; margin-bottom: 12px;">
                <i class="fas fa-calendar"></i> <?= formatDate($event['event_date'], 'd M Y, g:i A') ?>
            </p>
            <?php if ($event['venue']): ?>
                <p style="font-size: 14px; color: #64748b;"><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($event['venue']) ?></p>
            <?php endif; ?>
            <div style="margin-top: 12px;">
                <span class="badge badge-success"><?= $event['going_count'] ?> going</span>
            </div>
        </a>
    <?php endforeach; ?>
</div>
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>
