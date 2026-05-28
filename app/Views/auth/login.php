<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - DSKM Batch Portal</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        body { background: linear-gradient(135deg, #0B1F3A 0%, #1a3a5c 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .login-card { background: white; border-radius: 16px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); max-width: 450px; width: 100%; }
        .input-group { margin-bottom: 20px; }
        .input-group label { display: block; font-weight: 600; color: #0B1F3A; margin-bottom: 8px; }
        .input-group input { width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; }
        .input-group input:focus { outline: none; border-color: #D4AF37; }
        .btn-primary { background: #0B1F3A; color: white; padding: 14px; border-radius: 8px; font-weight: 700; width: 100%; font-size: 16px; cursor: pointer; }
        .btn-primary:hover { background: #0a1829; }
        .alert { padding: 12px; border-radius: 8px; margin-bottom: 20px; }
        .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }
    </style>
</head>
<body>
    <div class="login-card">
        <div style="padding: 40px;">
            <!-- Logo -->
            <div style="text-align: center; margin-bottom: 30px;">
                <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #0B1F3A 0%, #D4AF37 100%); border-radius: 16px; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; box-shadow: 0 10px 30px rgba(11, 31, 58, 0.3);">
                    <span style="color: white; font-size: 32px; font-weight: 800;">DS</span>
                </div>
                <h1 style="font-size: 28px; font-weight: 800; color: #0B1F3A; margin-bottom: 8px;">Welcome Back!</h1>
                <p style="color: #64748b; font-size: 14px;">Sign in to your account</p>
            </div>

            <!-- Flash Messages -->
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

            <!-- Login Form -->
            <form method="POST" action="<?= url('/login') ?>">
                <?= csrf_field() ?>

                <div class="input-group">
                    <label for="identifier">
                        <i class="fas fa-user"></i> Mobile / Email / Username
                    </label>
                    <input type="text" 
                           id="identifier" 
                           name="identifier" 
                           placeholder="Enter your mobile, email or username" 
                           required 
                           autofocus>
                </div>

                <div class="input-group">
                    <label for="password">
                        <i class="fas fa-lock"></i> Password
                    </label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           placeholder="Enter your password" 
                           required>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="checkbox" name="remember_me" value="1">
                        <span style="font-size: 14px; color: #64748b;">Remember me</span>
                    </label>
                    <a href="<?= url('/forgot-password') ?>" style="font-size: 14px; color: #D4AF37; font-weight: 600;">Forgot Password?</a>
                </div>

                <button type="submit" class="btn-primary">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
            </form>

            <div style="text-align: center; margin-top: 24px; padding-top: 24px; border-top: 1px solid #e2e8f0;">
                <p style="color: #64748b; font-size: 14px;">
                    Don't have an account? 
                    <a href="<?= url('/register') ?>" style="color: #D4AF37; font-weight: 700;">Register Now</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
