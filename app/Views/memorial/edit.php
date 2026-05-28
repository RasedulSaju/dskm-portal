<?php $pageTitle = 'Edit Memorial'; ?>
<?php require dirname(__DIR__) . '/layouts/header.php'; ?>
<div class="card" style="max-width: 700px; margin: 0 auto;">
    <h2 style="font-size: 24px; font-weight: 700; margin-bottom: 24px;">Edit Memorial</h2>
    <form method="POST" action="<?= url(\'/memorial/<?= $memorial['id'] ?>/edit\') ?>" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px;">Full Name (English)</label>
            <input type="text" name="full_name" value="<?= htmlspecialchars($memorial['full_name']) ?>" required class="w-full px-4 py-3 border-2 rounded-lg">
        </div>
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px;">Full Name (Bangla)</label>
            <input type="text" name="full_name_bn" value="<?= htmlspecialchars($memorial['full_name_bn'] ?? '') ?>" class="w-full px-4 py-3 border-2 rounded-lg">
        </div>
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px;">Type</label>
            <select name="type" required class="w-full px-4 py-3 border-2 rounded-lg">
                <option value="member" <?= $memorial['type'] === 'member' ? 'selected' : '' ?>>Member</option>
                <option value="teacher" <?= $memorial['type'] === 'teacher' ? 'selected' : '' ?>>Teacher</option>
                <option value="staff" <?= $memorial['type'] === 'staff' ? 'selected' : '' ?>>Staff</option>
            </select>
        </div>
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px;">Date of Death</label>
            <input type="date" name="date_of_death" value="<?= $memorial['date_of_death'] ?? '' ?>" class="w-full px-4 py-3 border-2 rounded-lg">
        </div>
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px;">Description</label>
            <textarea name="description" rows="6" class="w-full px-4 py-3 border-2 rounded-lg"><?= htmlspecialchars($memorial['description'] ?? '') ?></textarea>
        </div>
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px;">Photo</label>
            <input type="file" name="photo" accept="image/*" class="w-full px-4 py-3 border-2 rounded-lg">
        </div>
        <button type="submit" class="btn-primary">Update Memorial</button>
    </form>
</div>
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>
