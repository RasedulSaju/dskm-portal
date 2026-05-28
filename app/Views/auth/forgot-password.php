<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - DSKM Batch Portal</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        body { background: linear-gradient(135deg, #0B1F3A 0%, #1a3a5c 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .card { background: white; border-radius: 16px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); max-width: 450px; width: 100%; padding: 40px; }
        .input-group { margin-bottom: 20px; }
        .input-group label { display: block; font-weight: 600; color: #0B1F3A; margin-bottom: 8px; }
        .input-group input { width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; }
        .input-group input:focus { outline: none; border-color: #D4AF37; }
        .btn-primary { background: #0B1F3A; color: white; padding: 14px; border-radius: 8px; font-weight: 700; width: 100%; cursor: pointer; }
        .alert { padding: 12px; border-radius: 8px; margin-bottom: 20px; }
        .alert-error { background: #fee2e2; color: #991b1b; }
        .alert-success { background: #d1fae5; color: #065f46; }
    </style>
</head>
<body>
    <div class="card">
        <div style="text-align: center; margin-bottom: 30px;">
            <i class="fas fa-key" style="font-size: 48px; color: #D4AF37; margin-bottom: 16px;"></i>
            <h1 style="font-size: 28px; font-weight: 800; color: #0B1F3A; margin-bottom: 8px;">Forgot Password?</h1>
            <p style="color: #64748b; font-size: 14px;">Enter your mobile number to reset your password</p>
        </div>

        <?php if ($error = \Core\Session::getFlash('error')): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <?php if ($success = \Core\Session::getFlash('success')): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= url('/forgot-password') ?>">
            <?= csrf_field() ?>

            <div class="input-group">
                <label for="mobile">
                    <i class="fas fa-mobile-alt"></i> Mobile Number
                </label>
                <input type="text" id="mobile" name="mobile" placeholder="01XXXXXXXXX" required autofocus>
            </div>

            <button type="submit" class="btn-primary">
                <i class="fas fa-paper-plane"></i> Send Reset Link
            </button>
        </form>

        <div style="text-align: center; margin-top: 24px; padding-top: 24px; border-top: 1px solid #e2e8f0;">
            <a href="<?= url('/login') ?>" style="color: #D4AF37; font-weight: 700; font-size: 14px;">
                <i class="fas fa-arrow-left"></i> Back to Login
            </a>
        </div>
    </div>
</body>
</html>
