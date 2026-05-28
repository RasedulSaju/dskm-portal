<?php $pageTitle = 'Edit Profile'; ?>
<?php require dirname(__DIR__) . '/layouts/header.php'; ?>

<div class="card" style="max-width: 900px; margin: 0 auto;">
    <h2 style="font-size: 24px; font-weight: 700; color: var(--primary); margin-bottom: 24px;">
        <i class="fas fa-edit"></i> Edit Profile
    </h2>

    <form method="POST" action="/dashboard/profile/edit" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div>
                <label style="display: block; font-weight: 600; margin-bottom: 8px;">Full Name (Bangla)</label>
                <input type="text" name="full_name_bn" value="<?= htmlspecialchars($profile['full_name_bn']) ?>" class="w-full px-4 py-3 border-2 rounded-lg">
            </div>
            <div>
                <label style="display: block; font-weight: 600; margin-bottom: 8px;">Full Name (English)</label>
                <input type="text" name="full_name_en" value="<?= htmlspecialchars($profile['full_name_en']) ?>" class="w-full px-4 py-3 border-2 rounded-lg">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div>
                <label style="display: block; font-weight: 600; margin-bottom: 8px;">Blood Group</label>
                <select name="blood_group" class="w-full px-4 py-3 border-2 rounded-lg">
                    <option value="">Select</option>
                    <?php foreach (['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bg): ?>
                        <option value="<?= $bg ?>" <?= $profile['blood_group'] === $bg ? 'selected' : '' ?>><?= $bg ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label style="display: block; font-weight: 600; margin-bottom: 8px;">Date of Birth</label>
                <input type="date" name="date_of_birth" value="<?= $profile['date_of_birth'] ?? '' ?>" class="w-full px-4 py-3 border-2 rounded-lg">
            </div>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px;">Profession</label>
            <input type="text" name="profession" value="<?= htmlspecialchars($profile['profession'] ?? '') ?>" class="w-full px-4 py-3 border-2 rounded-lg">
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px;">Current Workplace</label>
            <input type="text" name="workplace" value="<?= htmlspecialchars($profile['workplace'] ?? '') ?>" class="w-full px-4 py-3 border-2 rounded-lg">
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px;">Bio</label>
            <textarea name="bio" rows="4" class="w-full px-4 py-3 border-2 rounded-lg"><?= htmlspecialchars($profile['bio'] ?? '') ?></textarea>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div>
                <label style="display: block; font-weight: 600; margin-bottom: 8px;">WhatsApp</label>
                <input type="text" name="whatsapp" value="<?= htmlspecialchars($profile['whatsapp'] ?? '') ?>" class="w-full px-4 py-3 border-2 rounded-lg">
            </div>
            <div>
                <label style="display: block; font-weight: 600; margin-bottom: 8px;">District</label>
                <input type="text" name="district" value="<?= htmlspecialchars($profile['district'] ?? '') ?>" class="w-full px-4 py-3 border-2 rounded-lg">
            </div>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px;">Present Address</label>
            <textarea name="address_present" rows="2" class="w-full px-4 py-3 border-2 rounded-lg"><?= htmlspecialchars($profile['address_present'] ?? '') ?></textarea>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div>
                <label style="display: block; font-weight: 600; margin-bottom: 8px;">Facebook URL</label>
                <input type="url" name="facebook_url" value="<?= htmlspecialchars($profile['facebook_url'] ?? '') ?>" class="w-full px-4 py-3 border-2 rounded-lg">
            </div>
            <div>
                <label style="display: block; font-weight: 600; margin-bottom: 8px;">LinkedIn URL</label>
                <input type="url" name="linkedin_url" value="<?= htmlspecialchars($profile['linkedin_url'] ?? '') ?>" class="w-full px-4 py-3 border-2 rounded-lg">
            </div>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px;">Profile Photo</label>
            <input type="file" name="avatar" accept="image/*" class="w-full px-4 py-3 border-2 rounded-lg">
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px;">Cover Photo</label>
            <input type="file" name="cover_photo" accept="image/*" class="w-full px-4 py-3 border-2 rounded-lg">
        </div>

        <div style="margin-bottom: 24px;">
            <label style="display: flex; align-items: center; gap: 8px;">
                <input type="checkbox" name="hide_phone" value="1" <?= $profile['hide_phone'] ? 'checked' : '' ?>>
                <span>Hide phone number from other members</span>
            </label>
            <label style="display: flex; align-items: center; gap: 8px; margin-top: 8px;">
                <input type="checkbox" name="hide_email" value="1" <?= $profile['hide_email'] ? 'checked' : '' ?>>
                <span>Hide email from other members</span>
            </label>
        </div>

        <div style="display: flex; gap: 12px;">
            <button type="submit" class="btn-primary">
                <i class="fas fa-save"></i> Save Changes
            </button>
            <a href="/dashboard/profile" class="btn-secondary">
                <i class="fas fa-times"></i> Cancel
            </a>
        </div>
    </form>
</div>

<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>
