<?php $pageTitle = 'Add Memorial'; ?>
<?php require dirname(__DIR__) . '/layouts/header.php'; ?>
<div class="card" style="max-width: 700px; margin: 0 auto;">
    <h2 style="font-size: 24px; font-weight: 700; margin-bottom: 24px;">Add Memorial</h2>
    <form method="POST" action="<?= url('/memorial/create') ?>" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px;">Full Name (English)</label>
            <input type="text" name="full_name" required class="w-full px-4 py-3 border-2 rounded-lg">
        </div>
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px;">Full Name (Bangla)</label>
            <input type="text" name="full_name_bn" class="w-full px-4 py-3 border-2 rounded-lg">
        </div>
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px;">Type</label>
            <select name="type" required class="w-full px-4 py-3 border-2 rounded-lg">
                <option value="member">Member</option>
                <option value="teacher">Teacher</option>
                <option value="staff">Staff</option>
            </select>
        </div>
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px;">Batch (if member)</label>
            <select name="batch_id" class="w-full px-4 py-3 border-2 rounded-lg">
                <option value="">Select Batch</option>
                <?php foreach ($batches as $batch): ?>
                    <option value="<?= $batch['id'] ?>"><?= htmlspecialchars($batch['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px;">Date of Death</label>
            <input type="date" name="date_of_death" class="w-full px-4 py-3 border-2 rounded-lg">
        </div>
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px;">Description</label>
            <textarea name="description" rows="6" class="w-full px-4 py-3 border-2 rounded-lg"></textarea>
        </div>
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px;">Photo</label>
            <input type="file" name="photo" accept="image/*" class="w-full px-4 py-3 border-2 rounded-lg">
        </div>
        <button type="submit" class="btn-primary">Add Memorial</button>
    </form>
</div>
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>
