<?php $pageTitle = 'Create Album'; ?>
<?php require dirname(__DIR__) . '/layouts/header.php'; ?>
<div class="card" style="max-width: 700px; margin: 0 auto;">
    <h2 style="font-size: 24px; font-weight: 700; margin-bottom: 24px;">Create New Album</h2>
    <form method="POST" action="<?= url(\'/gallery/create\') ?>" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px;">Album Title</label>
            <input type="text" name="title" required class="w-full px-4 py-3 border-2 rounded-lg">
        </div>
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px;">Description</label>
            <textarea name="description" rows="4" class="w-full px-4 py-3 border-2 rounded-lg"></textarea>
        </div>
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px;">Category</label>
            <input type="text" name="category" class="w-full px-4 py-3 border-2 rounded-lg" placeholder="e.g., Reunion 2024">
        </div>
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px;">Cover Image</label>
            <input type="file" name="cover_image" accept="image/*" class="w-full px-4 py-3 border-2 rounded-lg">
        </div>
        <button type="submit" class="btn-primary">Create Album</button>
    </form>
</div>
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>
