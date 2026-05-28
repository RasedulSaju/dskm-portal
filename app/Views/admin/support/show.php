<?php $pageTitle = 'Support Request Details'; ?>
<?php require dirname(__DIR__,2) . '/layouts/header.php'; ?>
<div class="card" style="max-width: 900px; margin: 0 auto;">
    <h2 style="font-size: 24px; font-weight: 700; margin-bottom: 16px;"><?= htmlspecialchars($request['subject']) ?></h2>
    <p style="color: #64748b; margin-bottom: 24px;">
        <span class="badge badge-info"><?= ucfirst($request['category']) ?></span>
        <span style="margin-left: 12px;">Submitted by <?= htmlspecialchars($request['requester_name']) ?></span>
        <span style="margin-left: 12px;"><?= formatDate($request['created_at']) ?></span>
    </p>
    <div style="line-height: 1.8; margin-bottom: 24px; padding: 20px; background: #f8fafc; border-radius: 8px;">
        <?= nl2br(htmlspecialchars($request['description'])) ?>
    </div>
    <?php if ($request['attachment']): ?>
        <a href="<?= upload($request['attachment']) ?>" target="_blank" class="btn-secondary" style="margin-bottom: 24px;">
            <i class="fas fa-paperclip"></i> View Attachment
        </a>
    <?php endif; ?>
    <form method="POST" action="<?= url(\'/admin/support/<?= $request['id'] ?>/status\') ?>" style="border-top: 1px solid #e2e8f0; padding-top: 24px;">
        <?= csrf_field() ?>
        <div style="margin-bottom: 16px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px;">Update Status</label>
            <select name="status" required class="w-full px-4 py-3 border-2 rounded-lg">
                <option value="reviewing" <?= $request['status']==='reviewing'?'selected':'' ?>>Reviewing</option>
                <option value="approved" <?= $request['status']==='approved'?'selected':'' ?>>Approved</option>
                <option value="rejected" <?= $request['status']==='rejected'?'selected':'' ?>>Rejected</option>
                <option value="resolved" <?= $request['status']==='resolved'?'selected':'' ?>>Resolved</option>
            </select>
        </div>
        <div style="margin-bottom: 16px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px;">Admin Note</label>
            <textarea name="admin_note" rows="4" class="w-full px-4 py-3 border-2 rounded-lg" placeholder="Add a note for the user..."><?= htmlspecialchars($request['admin_note'] ?? '') ?></textarea>
        </div>
        <button type="submit" class="btn-primary">Update Request</button>
    </form>
</div>
<?php require dirname(__DIR__,2) . '/layouts/footer.php'; ?>
