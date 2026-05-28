<?php $pageTitle = 'Dashboard'; ?>
<?php require dirname(__DIR__) . '/layouts/header.php'; ?>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
    <div class="card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
        <div style="display: flex; justify-content: space-between; align-items: start;">
            <div>
                <div style="font-size: 14px; opacity: 0.9; margin-bottom: 8px;">Total Members</div>
                <div style="font-size: 36px; font-weight: 800;"><?= $stats['totals']['total_members'] ?></div>
            </div>
            <i class="fas fa-users" style="font-size: 40px; opacity: 0.3;"></i>
        </div>
    </div>

    <div class="card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
        <div style="display: flex; justify-content: space-between; align-items: start;">
            <div>
                <div style="font-size: 14px; opacity: 0.9; margin-bottom: 8px;">Online Now</div>
                <div style="font-size: 36px; font-weight: 800;"><?= $stats['totals']['online_now'] ?></div>
            </div>
            <i class="fas fa-circle" style="font-size: 40px; opacity: 0.3;"></i>
        </div>
    </div>

    <div class="card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
        <div style="display: flex; justify-content: space-between; align-items: start;">
            <div>
                <div style="font-size: 14px; opacity: 0.9; margin-bottom: 8px;">Unread Messages</div>
                <div style="font-size: 36px; font-weight: 800;"><?= $unreadMessages ?></div>
            </div>
            <i class="fas fa-envelope" style="font-size: 40px; opacity: 0.3;"></i>
        </div>
    </div>

    <div class="card" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); color: white;">
        <div style="display: flex; justify-content: space-between; align-items: start;">
            <div>
                <div style="font-size: 14px; opacity: 0.9; margin-bottom: 8px;">New Today</div>
                <div style="font-size: 36px; font-weight: 800;"><?= $stats['totals']['new_today'] ?></div>
            </div>
            <i class="fas fa-user-plus" style="font-size: 40px; opacity: 0.3;"></i>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px; margin-bottom: 30px;">
    <!-- Upcoming Events -->
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2 style="font-size: 20px; font-weight: 700; color: var(--primary);">
                <i class="fas fa-calendar"></i> Upcoming Events
            </h2>
            <a href="<?= url(\'/events\') ?>" style="color: var(--accent); font-weight: 600; font-size: 14px;">View All</a>
        </div>

        <?php if (empty($upcomingEvents)): ?>
            <p style="color: #64748b; text-align: center; padding: 40px 0;">No upcoming events</p>
        <?php else: ?>
            <?php foreach ($upcomingEvents as $event): ?>
                <div style="padding: 16px; background: #f8fafc; border-radius: 8px; margin-bottom: 12px;">
                    <div style="display: flex; justify-content: space-between; align-items: start;">
                        <div style="flex: 1;">
                            <h3 style="font-weight: 700; color: var(--primary); margin-bottom: 8px;">
                                <a href="<?= url(\'/events/<?= $event['id'] ?>\') ?>"><?= htmlspecialchars($event['title']) ?></a>
                            </h3>
                            <div style="font-size: 14px; color: #64748b;">
                                <i class="fas fa-calendar-alt"></i> <?= formatDate($event['event_date'], 'd M Y, g:i A') ?>
                            </div>
                            <?php if ($event['venue']): ?>
                                <div style="font-size: 14px; color: #64748b; margin-top: 4px;">
                                    <i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($event['venue']) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <span class="badge badge-success"><?= $event['going_count'] ?> going</span>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Online Members -->
    <div class="card">
        <h2 style="font-size: 20px; font-weight: 700; color: var(--primary); margin-bottom: 20px;">
            <i class="fas fa-users"></i> Online Members
        </h2>

        <?php if (empty($onlineMembers)): ?>
            <p style="color: #64748b; text-align: center; padding: 20px 0;">No members online</p>
        <?php else: ?>
            <?php foreach ($onlineMembers as $member): ?>
                <a href="<?= url(\'/members/<?= $member['id'] ?>\') ?>" style="display: flex; align-items: center; gap: 12px; padding: 12px; background: #f8fafc; border-radius: 8px; margin-bottom: 8px;">
                    <img src="<?= $member['avatar'] ? upload($member['avatar']) : 'https://ui-avatars.com/api/?name=' . urlencode($member['full_name_en']) ?>" 
                         alt="Avatar" 
                         style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                    <div style="flex: 1;">
                        <div style="font-weight: 600; color: var(--primary); font-size: 14px;"><?= htmlspecialchars($member['full_name_en']) ?></div>
                        <?php if ($member['profession']): ?>
                            <div style="font-size: 12px; color: #64748b;"><?= htmlspecialchars($member['profession']) ?></div>
                        <?php endif; ?>
                    </div>
                    <span style="width: 8px; height: 8px; background: #22c55e; border-radius: 50%;"></span>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Recent Notices -->
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="font-size: 20px; font-weight: 700; color: var(--primary);">
            <i class="fas fa-bullhorn"></i> Latest Announcements
        </h2>
        <a href="<?= url(\'/notices\') ?>" style="color: var(--accent); font-weight: 600; font-size: 14px;">View All</a>
    </div>

    <?php if (empty($recentNotices)): ?>
        <p style="color: #64748b; text-align: center; padding: 40px 0;">No notices available</p>
    <?php else: ?>
        <?php foreach ($recentNotices as $notice): ?>
            <div style="padding: 16px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <a href="<?= url(\'/notices/<?= $notice['id'] ?>\') ?>" style="font-weight: 600; color: var(--primary);">
                        <?= htmlspecialchars($notice['title']) ?>
                    </a>
                    <div style="font-size: 13px; color: #64748b; margin-top: 4px;">
                        <span class="badge badge-info"><?= ucfirst($notice['category']) ?></span>
                        <span style="margin-left: 8px;"><?= timeAgo($notice['created_at']) ?></span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>
