<?php $pageTitle = 'New Support Request'; ?>
<?php require dirname(__DIR__) . '/layouts/header.php'; ?>
<div class="card" style="max-width: 700px; margin: 0 auto;">
    <h2 style="font-size: 24px; font-weight: 700; margin-bottom: 24px;">Submit Support Request</h2>
    <form method="POST" action="/support/create" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px;">Category</label>
            <select name="category" required class="w-full px-4 py-3 border-2 rounded-lg">
                <option value="">Select Category</option>
                <option value="financial">Financial Support</option>
                <option value="medical">Medical Support</option>
                <option value="personal">Personal Issue</option>
                <option value="other">Other</option>
            </select>
        </div>
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px;">Subject</label>
            <input type="text" name="subject" required class="w-full px-4 py-3 border-2 rounded-lg" placeholder="Brief summary of your request">
        </div>
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px;">Detailed Description</label>
            <textarea name="description" rows="10" required class="w-full px-4 py-3 border-2 rounded-lg" placeholder="Provide detailed information about your situation..."></textarea>
        </div>
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px;">Attachment (Optional)</label>
            <input type="file" name="attachment" accept=".pdf,image/*" class="w-full px-4 py-3 border-2 rounded-lg">
        </div>
        <button type="submit" class="btn-primary">Submit Request</button>
    </form>
</div>
<?php require dirname(__DIR__) . '/layouts/footer.php'; ?>
