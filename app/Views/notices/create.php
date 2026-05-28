<?php $pageTitle = 'Create Notice'; ?>
<?php require dirname(__DIR__) . '/layouts/header.php'; ?>
<div class="card" style="max-width: 700px; margin: 0 auto;">
    <h2 style="font-size: 24px; font-weight: 700; margin-bottom: 24px;">Create New Notice</h2>
    <form method="POST" action="<?= url(\'/notices/create\') ?>" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px;">Title</label>
            <input type="text" name="title" required class="w-full px-4 py-3 border-2 rounded-lg">
        </div>
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px;">Category</label>
            <select name="category" required class="w-full px-4 py-3 border-2 rounded-lg">
                <option value="general">General</option>
                <option value="academic">Academic</option>
                <option value="event">Event</option>
                <option value="urgent">Urgent</option>
            </select>
        </div>
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px;">Content</label>
            <textarea name="content" rows="10" required class="w-full px-4 py-3 border-2 rounded-lg"></textarea>
        </div>
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px;">Attachment (PDF/Image)</label>
            <input type="file" name="attachment" accept=".pdf,image/*" class="w-full px-4 py-3 border-2 rounded-lg">
        </div>
        <div style="margin-bottom: 20px;">
            <label style="display: flex; align-items: center; gap: 8px;">
                <input type="checkbox" name="is_pinned" value="1">
                <span>Pin this notice</span>
            </label>
        </div>
        <button type="submit" class="btn-primary">Publish Notice</button>
    </form>
</div>
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>
