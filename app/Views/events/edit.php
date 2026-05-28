<?php $pageTitle = 'Edit Event'; ?>
<?php require dirname(__DIR__) . '/layouts/header.php'; ?>
<div class="card" style="max-width: 700px; margin: 0 auto;">
    <h2 style="font-size: 24px; font-weight: 700; margin-bottom: 24px;">Edit Event</h2>
    <form method="POST" action="<?= url(\'/events/<?= $event['id'] ?>/edit\') ?>" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px;">Event Title</label>
            <input type="text" name="title" value="<?= htmlspecialchars($event['title']) ?>" required class="w-full px-4 py-3 border-2 rounded-lg">
        </div>
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px;">Description</label>
            <textarea name="description" rows="6" required class="w-full px-4 py-3 border-2 rounded-lg"><?= htmlspecialchars($event['description']) ?></textarea>
        </div>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
            <div>
                <label style="display: block; font-weight: 600; margin-bottom: 8px;">Event Date & Time</label>
                <input type="datetime-local" name="event_date" value="<?= date('Y-m-d\TH:i', strtotime($event['event_date'])) ?>" required class="w-full px-4 py-3 border-2 rounded-lg">
            </div>
            <div>
                <label style="display: block; font-weight: 600; margin-bottom: 8px;">End Date</label>
                <input type="datetime-local" name="event_end_date" value="<?= $event['event_end_date'] ? date('Y-m-d\TH:i', strtotime($event['event_end_date'])) : '' ?>" class="w-full px-4 py-3 border-2 rounded-lg">
            </div>
        </div>
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px;">Venue</label>
            <input type="text" name="venue" value="<?= htmlspecialchars($event['venue'] ?? '') ?>" class="w-full px-4 py-3 border-2 rounded-lg">
        </div>
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px;">Event Banner</label>
            <input type="file" name="banner" accept="image/*" class="w-full px-4 py-3 border-2 rounded-lg">
        </div>
        <button type="submit" class="btn-primary">Update Event</button>
    </form>
</div>
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>
