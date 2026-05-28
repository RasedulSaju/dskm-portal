<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - DSKM Batch Portal</title>
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
    </style>
</head>
<body>
    <div class="card">
        <div style="text-align: center; margin-bottom: 30px;">
            <i class="fas fa-lock" style="font-size: 48px; color: #D4AF37; margin-bottom: 16px;"></i>
            <h1 style="font-size: 28px; font-weight: 800; color: #0B1F3A; margin-bottom: 8px;">Reset Password</h1>
            <p style="color: #64748b; font-size: 14px;">Enter your new password</p>
        </div>

        <?php if ($error = \Core\Session::getFlash('error')): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="/reset-password">
            <?= csrf_field() ?>
            <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

            <div class="input-group">
                <label for="password">New Password</label>
                <input type="password" id="password" name="password" placeholder="Min. 6 characters" required autofocus>
            </div>

            <div class="input-group">
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm your password" required>
            </div>

            <button type="submit" class="btn-primary">
                <i class="fas fa-check"></i> Reset Password
            </button>
        </form>
    </div>
</body>
</html>
