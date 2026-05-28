<?php $pageTitle = 'Write Article'; ?>
<?php require dirname(__DIR__) . '/layouts/header.php'; ?>
<div class="card" style="max-width: 800px; margin: 0 auto;">
    <h2 style="font-size: 24px; font-weight: 700; margin-bottom: 24px;">Write Your Story</h2>
    <form method="POST" action="/smoronika/create" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px;">Title</label>
            <input type="text" name="title" required class="w-full px-4 py-3 border-2 rounded-lg">
        </div>
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px;">Content</label>
            <textarea name="content" rows="15" required class="w-full px-4 py-3 border-2 rounded-lg" placeholder="Write your memories, stories, experiences..."></textarea>
        </div>
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px;">Featured Image</label>
            <input type="file" name="image" accept="image/*" class="w-full px-4 py-3 border-2 rounded-lg">
        </div>
        <div style="display: flex; gap: 12px;">
            <button type="submit" class="btn-primary">Submit for Approval</button>
            <button type="submit" name="save_draft" value="1" class="btn-secondary">Save as Draft</button>
        </div>
    </form>
</div>
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>
