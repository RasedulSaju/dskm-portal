<?php $pageTitle = 'My Profile'; ?>
<?php require dirname(__DIR__) . '/layouts/header.php'; ?>

<div class="card" style="margin-bottom: 24px; padding: 0; overflow: hidden;">
    <?php if ($profile['cover_photo']): ?>
        <div style="height: 200px; background: url('<?= upload($profile['cover_photo']) ?>') center/cover;"></div>
    <?php else: ?>
        <div style="height: 200px; background: linear-gradient(135deg, #0B1F3A 0%, #1a3a5c 100%);"></div>
    <?php endif; ?>
    
    <div style="padding: 24px; position: relative;">
        <div style="display: flex; align-items: end; gap: 24px; margin-top: -80px;">
            <img src="<?= $profile['avatar'] ? upload($profile['avatar']) : 'https://ui-avatars.com/api/?name=' . urlencode($profile['full_name_en']) ?>&size=200" 
                 alt="Avatar" 
                 style="width: 140px; height: 140px; border-radius: 50%; object-fit: cover; border: 5px solid white; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
            
            <div style="flex: 1; padding-bottom: 12px;">
                <h1 style="font-size: 32px; font-weight: 800; color: var(--primary); margin-bottom: 8px;">
                    <?= htmlspecialchars($profile['full_name_en']) ?>
                </h1>
                <p style="font-size: 18px; color: #64748b; margin-bottom: 12px;">
                    <?= htmlspecialchars($profile['full_name_bn']) ?>
                </p>
                <?php if ($profile['profession']): ?>
                    <p style="font-size: 16px; color: #64748b;">
                        <i class="fas fa-briefcase"></i> <?= htmlspecialchars($profile['profession']) ?>
                        <?php if ($profile['workplace']): ?>
                            at <?= htmlspecialchars($profile['workplace']) ?>
                        <?php endif; ?>
                    </p>
                <?php endif; ?>
            </div>

            <a href="<?= url(\'/dashboard/profile/edit\') ?>" class="btn-accent">
                <i class="fas fa-edit"></i> Edit Profile
            </a>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
    <div>
        <?php if ($profile['bio']): ?>
            <div class="card" style="margin-bottom: 24px;">
                <h2 style="font-size: 18px; font-weight: 700; color: var(--primary); margin-bottom: 12px;">About</h2>
                <p style="color: #64748b; line-height: 1.6;"><?= nl2br(htmlspecialchars($profile['bio'])) ?></p>
            </div>
        <?php endif; ?>

        <div class="card">
            <h2 style="font-size: 18px; font-weight: 700; color: var(--primary); margin-bottom: 16px;">Education</h2>
            <?php if (!empty($profile['batches'])): ?>
                <?php foreach ($profile['batches'] as $batch): ?>
                    <div style="padding: 16px; background: #f8fafc; border-radius: 8px; margin-bottom: 12px;">
                        <h3 style="font-weight: 700; color: var(--primary);"><?= htmlspecialchars($batch['name']) ?></h3>
                        <p style="color: #64748b; font-size: 14px; margin-top: 4px;">
                            <?php if ($batch['roll_number']): ?>
                                Roll: <?= htmlspecialchars($batch['roll_number']) ?> | 
                            <?php endif; ?>
                            <?php if ($batch['registration_number']): ?>
                                Reg: <?= htmlspecialchars($batch['registration_number']) ?>
                            <?php endif; ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="color: #64748b;">No education information</p>
            <?php endif; ?>
        </div>
    </div>

    <div>
        <div class="card" style="margin-bottom: 24px;">
            <h2 style="font-size: 18px; font-weight: 700; color: var(--primary); margin-bottom: 16px;">Contact Info</h2>
            <div style="display: flex; flex-direction: column; gap: 12px;">
                <?php if (!$profile['hide_phone']): ?>
                    <div>
                        <div style="font-size: 12px; color: #64748b; margin-bottom: 4px;">Mobile</div>
                        <div style="font-weight: 600; color: var(--primary);">
                            <i class="fas fa-phone"></i> <?= htmlspecialchars($profile['mobile']) ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (!$profile['hide_email'] && $profile['email']): ?>
                    <div>
                        <div style="font-size: 12px; color: #64748b; margin-bottom: 4px;">Email</div>
                        <div style="font-weight: 600; color: var(--primary);">
                            <i class="fas fa-envelope"></i> <?= htmlspecialchars($profile['email']) ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($profile['whatsapp']): ?>
                    <div>
                        <div style="font-size: 12px; color: #64748b; margin-bottom: 4px;">WhatsApp</div>
                        <div style="font-weight: 600; color: var(--primary);">
                            <i class="fab fa-whatsapp"></i> <?= htmlspecialchars($profile['whatsapp']) ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($profile['blood_group']): ?>
                    <div>
                        <div style="font-size: 12px; color: #64748b; margin-bottom: 4px;">Blood Group</div>
                        <div style="font-weight: 600; color: var(--primary);">
                            <i class="fas fa-tint"></i> <?= htmlspecialchars($profile['blood_group']) ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($profile['address_present']): ?>
                    <div>
                        <div style="font-size: 12px; color: #64748b; margin-bottom: 4px;">Address</div>
                        <div style="font-weight: 600; color: var(--primary);">
                            <i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($profile['address_present']) ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($profile['facebook_url'] || $profile['linkedin_url']): ?>
            <div class="card">
                <h2 style="font-size: 18px; font-weight: 700; color: var(--primary); margin-bottom: 16px;">Social Links</h2>
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <?php if ($profile['facebook_url']): ?>
                        <a href="<?= htmlspecialchars($profile['facebook_url']) ?>" target="_blank" style="padding: 10px; background: #1877f2; color: white; border-radius: 8px; text-align: center; font-weight: 600;">
                            <i class="fab fa-facebook"></i> Facebook
                        </a>
                    <?php endif; ?>

                    <?php if ($profile['linkedin_url']): ?>
                        <a href="<?= htmlspecialchars($profile['linkedin_url']) ?>" target="_blank" style="padding: 10px; background: #0a66c2; color: white; border-radius: 8px; text-align: center; font-weight: 600;">
                            <i class="fab fa-linkedin"></i> LinkedIn
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>
