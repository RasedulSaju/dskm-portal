<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - DSKM Batch Portal</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        body { background: linear-gradient(135deg, #0B1F3A 0%, #1a3a5c 100%); min-height: 100vh; padding: 40px 20px; }
        .register-card { background: white; border-radius: 16px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); max-width: 700px; margin: 0 auto; }
        .step { display: none; }
        .step.active { display: block; }
        .input-group { margin-bottom: 20px; }
        .input-group label { display: block; font-weight: 600; color: #0B1F3A; margin-bottom: 8px; font-size: 14px; }
        .input-group input, .input-group select, .input-group textarea { width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 15px; }
        .input-group input:focus, .input-group select:focus, .input-group textarea:focus { outline: none; border-color: #D4AF37; }
        .input-group.error input, .input-group.error select { border-color: #ef4444; }
        .error-message { color: #ef4444; font-size: 13px; margin-top: 4px; }
        .btn-primary { background: #0B1F3A; color: white; padding: 12px 24px; border-radius: 8px; font-weight: 700; cursor: pointer; }
        .btn-secondary { background: #64748b; color: white; padding: 12px 24px; border-radius: 8px; font-weight: 700; cursor: pointer; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        @media (max-width: 640px) { .grid-2 { grid-template-columns: 1fr; } }
        .alert { padding: 12px; border-radius: 8px; margin-bottom: 20px; }
        .alert-error { background: #fee2e2; color: #991b1b; }
    </style>
</head>
<body>
    <div class="register-card">
        <div style="padding: 40px;">
            <div style="text-align: center; margin-bottom: 30px;">
                <h1 style="font-size: 28px; font-weight: 800; color: #0B1F3A;">Create Your Account</h1>
                <p style="color: #64748b;">Join the DSKM Alumni Community</p>
            </div>

            <?php if ($errors = \Core\Session::getFlash('errors')): ?>
                <div class="alert alert-error">
                    <strong>Please fix the following errors:</strong>
                    <ul style="margin: 8px 0 0 20px;">
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" action="/register" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <!-- Personal Info -->
                <div class="input-group">
                    <label>Full Name (Bangla) <span style="color: #ef4444;">*</span></label>
                    <input type="text" name="full_name_bn" value="<?= old('full_name_bn') ?>" placeholder="আপনার পূর্ণ নাম" required>
                    <?php if (error('full_name_bn')): ?>
                        <div class="error-message"><?= error('full_name_bn') ?></div>
                    <?php endif; ?>
                </div>

                <div class="input-group">
                    <label>Full Name (English) <span style="color: #ef4444;">*</span></label>
                    <input type="text" name="full_name_en" value="<?= old('full_name_en') ?>" placeholder="Your Full Name" required>
                    <?php if (error('full_name_en')): ?>
                        <div class="error-message"><?= error('full_name_en') ?></div>
                    <?php endif; ?>
                </div>

                <div class="grid-2">
                    <div class="input-group">
                        <label>Username <span style="color: #ef4444;">*</span></label>
                        <input type="text" name="username" value="<?= old('username') ?>" placeholder="Choose a username" required>
                        <?php if (error('username')): ?>
                            <div class="error-message"><?= error('username') ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="input-group">
                        <label>Mobile Number <span style="color: #ef4444;">*</span></label>
                        <input type="text" name="mobile" value="<?= old('mobile') ?>" placeholder="01XXXXXXXXX" required>
                        <?php if (error('mobile')): ?>
                            <div class="error-message"><?= error('mobile') ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="input-group">
                    <label>Email (Optional)</label>
                    <input type="email" name="email" value="<?= old('email') ?>" placeholder="your@email.com">
                </div>

                <!-- Batch Selection -->
                <div class="input-group">
                    <label>Select Batch(es) <span style="color: #ef4444;">*</span></label>
                    <?php foreach ($batches as $batch): ?>
                        <label style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px; cursor: pointer;">
                            <input type="checkbox" name="batches[]" value="<?= $batch['id'] ?>" onchange="toggleBatchFields(<?= $batch['id'] ?>, '<?= $batch['exam_type'] ?>')">
                            <span><?= htmlspecialchars($batch['name']) ?> (<?= $batch['year'] ?>)</span>
                        </label>
                    <?php endforeach; ?>
                </div>

                <!-- Dynamic Batch Fields -->
                <?php foreach ($batches as $batch): ?>
                    <div id="batch_fields_<?= $batch['id'] ?>" style="display: none; padding: 16px; background: #f8fafc; border-radius: 8px; margin-bottom: 16px;">
                        <h4 style="font-weight: 700; color: #0B1F3A; margin-bottom: 12px;"><?= htmlspecialchars($batch['name']) ?> Information</h4>
                        <div class="grid-2">
                            <div class="input-group">
                                <label>Roll Number</label>
                                <input type="text" name="roll_<?= $batch['id'] ?>" placeholder="Roll">
                            </div>
                            <div class="input-group">
                                <label>Registration Number</label>
                                <input type="text" name="registration_<?= $batch['id'] ?>" placeholder="Registration">
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

                <!-- Additional Info -->
                <div class="grid-2">
                    <div class="input-group">
                        <label>Blood Group</label>
                        <select name="blood_group">
                            <option value="">Select</option>
                            <option value="A+">A+</option>
                            <option value="A-">A-</option>
                            <option value="B+">B+</option>
                            <option value="B-">B-</option>
                            <option value="AB+">AB+</option>
                            <option value="AB-">AB-</option>
                            <option value="O+">O+</option>
                            <option value="O-">O-</option>
                        </select>
                    </div>

                    <div class="input-group">
                        <label>WhatsApp Number</label>
                        <input type="text" name="whatsapp" value="<?= old('whatsapp') ?>" placeholder="01XXXXXXXXX">
                    </div>
                </div>

                <div class="grid-2">
                    <div class="input-group">
                        <label>District</label>
                        <input type="text" name="district" value="<?= old('district') ?>" placeholder="Your district">
                    </div>

                    <div class="input-group">
                        <label>Profession</label>
                        <input type="text" name="profession" value="<?= old('profession') ?>" placeholder="Your profession">
                    </div>
                </div>

                <div class="input-group">
                    <label>Current Workplace</label>
                    <input type="text" name="workplace" value="<?= old('workplace') ?>" placeholder="Company/Organization">
                </div>

                <div class="input-group">
                    <label>Address</label>
                    <textarea name="address" rows="2" placeholder="Your address"><?= old('address') ?></textarea>
                </div>

                <!-- Social Links -->
                <div class="grid-2">
                    <div class="input-group">
                        <label>Facebook URL</label>
                        <input type="url" name="facebook_url" value="<?= old('facebook_url') ?>" placeholder="https://facebook.com/yourprofile">
                    </div>

                    <div class="input-group">
                        <label>LinkedIn URL</label>
                        <input type="url" name="linkedin_url" value="<?= old('linkedin_url') ?>" placeholder="https://linkedin.com/in/yourprofile">
                    </div>
                </div>

                <div class="input-group">
                    <label>Short Bio</label>
                    <textarea name="bio" rows="3" placeholder="Tell us about yourself..."><?= old('bio') ?></textarea>
                </div>

                <div class="input-group">
                    <label>Profile Photo</label>
                    <input type="file" name="avatar" accept="image/*">
                </div>

                <!-- Password -->
                <div class="grid-2">
                    <div class="input-group">
                        <label>Password <span style="color: #ef4444;">*</span></label>
                        <input type="password" name="password" placeholder="Min. 6 characters" required>
                        <?php if (error('password')): ?>
                            <div class="error-message"><?= error('password') ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="input-group">
                        <label>Confirm Password <span style="color: #ef4444;">*</span></label>
                        <input type="password" name="password_confirmation" placeholder="Confirm password" required>
                        <?php if (error('password_confirmation')): ?>
                            <div class="error-message"><?= error('password_confirmation') ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Terms -->
                <div class="input-group">
                    <label style="display: flex; align-items: start; gap: 8px; cursor: pointer;">
                        <input type="checkbox" name="terms" value="1" required style="margin-top: 4px;">
                        <span style="font-weight: 400; font-size: 14px;">I agree to the terms and conditions and understand my account will be activated after admin approval</span>
                    </label>
                </div>

                <button type="submit" class="btn-primary" style="width: 100%; font-size: 16px;">
                    <i class="fas fa-user-plus"></i> Register
                </button>
            </form>

            <div style="text-align: center; margin-top: 24px; padding-top: 24px; border-top: 1px solid #e2e8f0;">
                <p style="color: #64748b; font-size: 14px;">
                    Already have an account? 
                    <a href="/login" style="color: #D4AF37; font-weight: 700;">Login</a>
                </p>
            </div>
        </div>
    </div>

    <script>
        function toggleBatchFields(batchId, examType) {
            const fields = document.getElementById(`batch_fields_${batchId}`);
            const checkbox = document.querySelector(`input[value="${batchId}"]`);
            fields.style.display = checkbox.checked ? 'block' : 'none';
        }
    </script>
</body>
</html>
