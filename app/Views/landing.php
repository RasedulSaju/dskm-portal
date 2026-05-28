<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DSKM Batch Portal - Dakhil 2010 & Alim 2012 Alumni</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        :root {
            --primary: #0B1F3A;
            --accent: #D4AF37;
            --bg: #F5F7FB;
        }
        body { background: linear-gradient(135deg, var(--primary) 0%, #1a3a5c 100%); min-height: 100vh; }
        .hero { background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23D4AF37" fill-opacity="0.1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,144C960,149,1056,139,1152,128C1248,117,1344,107,1392,101.3L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom; background-size: cover; }
        .glass { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); border-radius: 16px; box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1); }
        .feature-card { transition: transform 0.3s; }
        .feature-card:hover { transform: translateY(-5px); }
    </style>
</head>
<body>
    <div class="hero" style="min-height: 100vh; padding: 40px 20px;">
        <div style="max-width: 1200px; margin: 0 auto;">
            <!-- Header -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 60px;">
                <div style="display: flex; align-items: center; gap: 16px;">
                    <div style="width: 60px; height: 60px; background: var(--accent); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 24px; color: var(--primary);">DS</div>
                    <div>
                        <h1 style="color: white; font-size: 24px; font-weight: 800;">DSKM Batch Portal</h1>
                        <p style="color: rgba(255,255,255,0.8); font-size: 14px;">Dakhil 2010 & Alim 2012</p>
                    </div>
                </div>
                <div style="display: flex; gap: 12px;">
                    <a href="<?= url('/login') ?>" style="padding: 12px 24px; background: rgba(255,255,255,0.2); color: white; border-radius: 8px; font-weight: 600; backdrop-filter: blur(10px);">Login</a>
                    <a href="<?= url('/register') ?>" style="padding: 12px 24px; background: var(--accent); color: var(--primary); border-radius: 8px; font-weight: 700;">Register Now</a>
                </div>
            </div>

            <!-- Hero Section -->
            <div class="glass" style="padding: 60px 40px; margin-bottom: 40px; text-align: center;">
                <h2 style="font-size: 48px; font-weight: 800; color: var(--primary); margin-bottom: 20px;">
                    এককালের স্মৃতির পথে<br/>
                    <span style="color: var(--accent);">DSKM Batch Portal</span>
                </h2>
                <p style="font-size: 20px; color: #64748b; max-width: 700px; margin: 0 auto 40px;">
                    আমাদের আল্মা ম্যাটারের সাথে সংযুক্ত থাকুন। পুরনো বন্ধুদের খুঁজে পান, ইভেন্টে যোগ দিন, এবং একসাথে নতুন স্মৃতি তৈরি করুন।
                </p>
                <div style="display: flex; gap: 16px; justify-content: center;">
                    <a href="<?= url('/register') ?>" style="padding: 16px 32px; background: var(--primary); color: white; border-radius: 12px; font-weight: 700; font-size: 18px;">
                        <i class="fas fa-user-plus"></i> Join Now
                    </a>
                    <a href="<?= url('/login') ?>" style="padding: 16px 32px; background: white; color: var(--primary); border: 2px solid var(--primary); border-radius: 12px; font-weight: 700; font-size: 18px;">
                        <i class="fas fa-sign-in-alt"></i> Member Login
                    </a>
                </div>
            </div>

            <!-- Features Grid -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px; margin-bottom: 40px;">
                <div class="glass feature-card" style="padding: 30px; text-align: center;">
                    <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                        <i class="fas fa-users" style="color: white; font-size: 24px;"></i>
                    </div>
                    <h3 style="font-size: 20px; font-weight: 700; color: var(--primary); margin-bottom: 12px;">Member Directory</h3>
                    <p style="color: #64748b;">৩২৫+ সদস্য খুঁজে পান এবং সংযুক্ত হন</p>
                </div>

                <div class="glass feature-card" style="padding: 30px; text-align: center;">
                    <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                        <i class="fas fa-calendar-alt" style="color: white; font-size: 24px;"></i>
                    </div>
                    <h3 style="font-size: 20px; font-weight: 700; color: var(--primary); margin-bottom: 12px;">Events & Reunions</h3>
                    <p style="color: #64748b;">আসন্ন ইভেন্ট এবং পুনর্মিলনী সম্পর্কে জানুন</p>
                </div>

                <div class="glass feature-card" style="padding: 30px; text-align: center;">
                    <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                        <i class="fas fa-images" style="color: white; font-size: 24px;"></i>
                    </div>
                    <h3 style="font-size: 20px; font-weight: 700; color: var(--primary); margin-bottom: 12px;">Photo Gallery</h3>
                    <p style="color: #64748b;">স্মৃতির মুহূর্ত শেয়ার করুন এবং দেখুন</p>
                </div>

                <div class="glass feature-card" style="padding: 30px; text-align: center;">
                    <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                        <i class="fas fa-book" style="color: white; font-size: 24px;"></i>
                    </div>
                    <h3 style="font-size: 20px; font-weight: 700; color: var(--primary); margin-bottom: 12px;">স্মরণিকা</h3>
                    <p style="color: #64748b;">আপনার স্মৃতিকথা এবং গল্প লিখুন</p>
                </div>
            </div>

            <!-- Stats -->
            <div class="glass" style="padding: 40px; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 30px; text-align: center;">
                <div>
                    <div style="font-size: 48px; font-weight: 800; color: var(--accent);">325+</div>
                    <div style="color: #64748b; font-weight: 600;">Total Members</div>
                </div>
                <div>
                    <div style="font-size: 48px; font-weight: 800; color: var(--accent);">165</div>
                    <div style="color: #64748b; font-weight: 600;">Dakhil 2010</div>
                </div>
                <div>
                    <div style="font-size: 48px; font-weight: 800; color: var(--accent);">160</div>
                    <div style="color: #64748b; font-weight: 600;">Alim 2012</div>
                </div>
                <div>
                    <div style="font-size: 48px; font-weight: 800; color: var(--accent);">28</div>
                    <div style="color: #64748b; font-weight: 600;">Online Now</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
