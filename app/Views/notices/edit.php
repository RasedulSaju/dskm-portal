<?php $pageTitle = 'Edit Notice'; ?>
<?php require dirname(__DIR__) . '/layouts/header.php'; ?>
<div class="card" style="max-width: 700px; margin: 0 auto;">
    <h2 style="font-size: 24px; font-weight: 700; margin-bottom: 24px;">Edit Notice</h2>
    <form method="POST" action="/notices/<?= $notice['id'] ?>/edit" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px;">Title</label>
            <input type="text" name="title" value="<?= htmlspecialchars($notice['title']) ?>" required class="w-full px-4 py-3 border-2 rounded-lg">
        </div>
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px;">Category</label>
            <select name="category" required class="w-full px-4 py-3 border-2 rounded-lg">
                <option value="general" <?= $notice['category'] === 'general' ? 'selected' : '' ?>>General</option>
                <option value="academic" <?= $notice['category'] === 'academic' ? 'selected' : '' ?>>Academic</option>
                <option value="event" <?= $notice['category'] === 'event' ? 'selected' : '' ?>>Event</option>
                <option value="urgent" <?= $notice['category'] === 'urgent' ? 'selected' : '' ?>>Urgent</option>
            </select>
        </div>
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px;">Content</label>
            <textarea name="content" rows="10" required class="w-full px-4 py-3 border-2 rounded-lg"><?= htmlspecialchars($notice['content']) ?></textarea>
        </div>
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px;">Attachment</label>
            <input type="file" name="attachment" accept=".pdf,image/*" class="w-full px-4 py-3 border-2 rounded-lg">
        </div>
        <div style="margin-bottom: 20px;">
            <label style="display: flex; align-items: center; gap: 8px;">
                <input type="checkbox" name="is_pinned" value="1" <?= $notice['is_pinned'] ? 'checked' : '' ?>>
                <span>Pin this notice</span>
            </label>
        </div>
        <button type="submit" class="btn-primary">Update Notice</button>
    </form>
</div>
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>
