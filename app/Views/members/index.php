<?php $pageTitle = 'Member Directory'; ?>
<?php require dirname(__DIR__) . '/layouts/header.php'; ?>

<div class="card" style="margin-bottom: 24px;">
    <form method="GET" style="display: grid; grid-template-columns: 2fr 1fr 1fr 1fr auto; gap: 12px; align-items: end;">
        <div>
            <label style="display: block; font-weight: 600; margin-bottom: 8px; font-size: 14px;">Search</label>
            <input type="text" name="search" value="<?= htmlspecialchars($filters['search']) ?>" placeholder="Search by name..." class="w-full px-4 py-2 border-2 rounded-lg">
        </div>
        <div>
            <label style="display: block; font-weight: 600; margin-bottom: 8px; font-size: 14px;">Batch</label>
            <select name="batch" class="w-full px-4 py-2 border-2 rounded-lg">
                <option value="">All Batches</option>
                <?php foreach ($batches as $batch): ?>
                    <option value="<?= $batch['id'] ?>" <?= $filters['batch'] == $batch['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($batch['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label style="display: block; font-weight: 600; margin-bottom: 8px; font-size: 14px;">Blood</label>
            <select name="blood_group" class="w-full px-4 py-2 border-2 rounded-lg">
                <option value="">All</option>
                <?php foreach (['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bg): ?>
                    <option value="<?= $bg ?>" <?= $filters['blood_group'] === $bg ? 'selected' : '' ?>><?= $bg ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label style="display: block; font-weight: 600; margin-bottom: 8px; font-size: 14px;">District</label>
            <select name="district" class="w-full px-4 py-2 border-2 rounded-lg">
                <option value="">All</option>
                <?php foreach ($districts as $d): ?>
                    <option value="<?= htmlspecialchars($d['district']) ?>" <?= $filters['district'] === $d['district'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($d['district']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn-primary" style="padding: 10px 20px;">
            <i class="fas fa-search"></i> Search
        </button>
    </form>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; margin-bottom: 30px;">
    <?php foreach ($members as $member): ?>
        <a href="<?= url('/members/' . $member['id'] . '') ?>" class="card" style="transition: transform 0.2s; text-align: center;">
            <img src="<?= $member['avatar'] ? upload($member['avatar']) : 'https://ui-avatars.com/api/?name=' . urlencode($member['full_name_en']) ?>&size=200" 
                 alt="Avatar" 
                 style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; margin: 0 auto 16px; border: 4px solid var(--accent);">
            
            <h3 style="font-weight: 700; color: var(--primary); margin-bottom: 4px;">
                <?= htmlspecialchars($member['full_name_en']) ?>
            </h3>
            <p style="color: #64748b; font-size: 14px; margin-bottom: 8px;">
                <?= htmlspecialchars($member['full_name_bn']) ?>
            </p>
            
            <?php if ($member['profession']): ?>
                <p style="font-size: 13px; color: #64748b; margin-bottom: 8px;">
                    <i class="fas fa-briefcase"></i> <?= htmlspecialchars($member['profession']) ?>
                </p>
            <?php endif; ?>
            
            <?php if ($member['batches']): ?>
                <p style="font-size: 12px; color: var(--accent); font-weight: 600;">
                    <?= htmlspecialchars($member['batches']) ?>
                </p>
            <?php endif; ?>
            
            <?php if ($member['is_online']): ?>
                <div style="margin-top: 12px;">
                    <span style="display: inline-flex; align-items: center; gap: 6px; font-size: 12px; color: #22c55e;">
                        <span style="width: 8px; height: 8px; background: #22c55e; border-radius: 50%;"></span>
                        Online
                    </span>
                </div>
            <?php endif; ?>
        </a>
    <?php endforeach; ?>
</div>

<?php if ($pagination['total_pages'] > 1): ?>
    <div style="display: flex; justify-content: center; gap: 8px;">
        <?php if ($pagination['has_prev']): ?>
            <a href="?page=<?= $pagination['prev_page'] ?>&<?= http_build_query($filters) ?>" class="btn-secondary" style="padding: 8px 16px;">
                <i class="fas fa-chevron-left"></i> Previous
            </a>
        <?php endif; ?>
        
        <span style="padding: 8px 16px; background: #f8fafc; border-radius: 8px;">
            Page <?= $pagination['current_page'] ?> of <?= $pagination['total_pages'] ?>
        </span>
        
        <?php if ($pagination['has_next']): ?>
            <a href="?page=<?= $pagination['next_page'] ?>&<?= http_build_query($filters) ?>" class="btn-secondary" style="padding: 8px 16px;">
                Next <i class="fas fa-chevron-right"></i>
            </a>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>
