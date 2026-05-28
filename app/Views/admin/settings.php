<?php $pageTitle = 'Settings'; ?>
<?php require dirname(__DIR__) . '/layouts/header.php'; ?>
<div class="card" style="max-width: 800px; margin: 0 auto;">
    <h2 style="font-size: 24px; font-weight: 700; margin-bottom: 24px;">Site Settings</h2>
    <form method="POST" action="<?= url(\'/admin/settings\') ?>">
        <?= csrf_field() ?>
        <?php foreach ($settings as $group => $items): ?>
            <h3 style="font-weight: 700; margin: 24px 0 16px; text-transform: capitalize;"><?= htmlspecialchars($group) ?></h3>
            <?php foreach ($items as $setting): ?>
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px;"><?= htmlspecialchars(ucwords(str_replace('_', ' ', $setting['key_name']))) ?></label>
                    <input type="text" name="setting_<?= htmlspecialchars($setting['key_name']) ?>" value="<?= htmlspecialchars($setting['value']) ?>" class="w-full px-4 py-3 border-2 rounded-lg">
                </div>
            <?php endforeach; ?>
        <?php endforeach; ?>
        <button type="submit" class="btn-primary">Save Settings</button>
    </form>
</div>
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>
