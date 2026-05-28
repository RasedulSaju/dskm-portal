<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        body { background: linear-gradient(135deg, #0B1F3A 0%, #1a3a5c 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
    </style>
</head>
<body>
    <div style="text-align: center; color: white;">
        <h1 style="font-size: 120px; font-weight: 800; margin-bottom: 20px;">404</h1>
        <h2 style="font-size: 32px; margin-bottom: 16px;">Page Not Found</h2>
        <p style="font-size: 18px; opacity: 0.8; margin-bottom: 32px;">The page you're looking for doesn't exist.</p>
        <a href="<?= url('/dashboard') ?>" style="padding: 14px 32px; background: #D4AF37; color: #0B1F3A; border-radius: 8px; font-weight: 700; text-decoration: none;">
            Go to Dashboard
        </a>
    </div>
</body>
</html>
