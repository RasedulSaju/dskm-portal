# 🏛️ DSKM Batch Portal - Alumni Management System

**Professional Alumni Portal for Dakhil 2010 & Alim 2012 Batches**

A complete, production-ready web application built with PHP 8+, MySQL, and modern frontend technologies.

---

## ✨ Features

### 🔐 Authentication & User Management
- Secure login/registration with admin approval
- Multi-step registration form
- Password reset functionality
- Role-based access control (Super Admin, Admin, Moderator, Member)
- Session management with "Remember Me"

### 👥 Member Directory
- Advanced search and filtering
- Profile pages with privacy controls
- Batch information management
- Online status indicators
- Blood group directory

### 📅 Events Management
- Create and manage events
- RSVP system
- Event banners and details
- Attendee tracking
- Admin approval workflow

### 📢 Notice Board
- Categorized announcements
- Pinned notices
- PDF attachments
- Rich text content
- Comment system

### 💬 Real-time Messaging
- Direct messaging between members
- Conversation history
- Unread message indicators
- Media sharing support

### 🖼️ Photo Gallery
- Album management
- Image uploads
- Masonry grid layout
- Lightbox preview

### 📖 স্মরণিকা (Smoronika)
- Member story submissions
- Article management
- Admin approval workflow
- Draft saving

### 🕊️ Memorial Section
- Tribute pages for deceased members/teachers
- Photo galleries
- Prayer section

### 🆘 Support System
- Financial/Medical/Personal support requests
- Admin review workflow
- Request tracking
- Status updates

### 🛡️ Admin Panel
- Comprehensive dashboard
- User approval management
- Content moderation
- Analytics and reports
- System settings

---

## 🚀 Technology Stack

**Backend:**
- PHP 8.1+
- MySQL 8.0+
- MVC Architecture
- PDO for database
- Custom routing system

**Frontend:**
- HTML5
- Tailwind CSS
- Vanilla JavaScript
- Alpine.js ready
- Responsive design

**Security:**
- CSRF protection
- XSS prevention
- SQL injection protection
- Password hashing (bcrypt)
- Session security
- File upload validation

---

## 📋 Requirements

- PHP 8.1 or higher
- MySQL 8.0 or higher
- Apache/Nginx web server
- Composer (optional, for dependencies)
- 50MB+ disk space

---

## ⚡ Installation

### 1. Clone/Download the Project
```bash
git clone https://github.com/RasedulSaju/dskm-portal dskm-portal
cd dskm-portal
```

### 2. Database Setup
```bash
# Create database
mysql -u root -p

CREATE DATABASE dskm_portal CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
exit;

# Import schema
mysql -u root -p dskm_portal < database/schema.sql
```

### 3. Configure Environment
```bash
# Copy environment file
cp .env.example .env

# Edit .env with your settings
nano .env
```

Update these values in `.env`:
- `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
- `APP_URL` (your domain)
- `MAIL_*` (SMTP settings)

### 4. Set Permissions
```bash
chmod -R 755 storage/
chmod -R 755 public/
chown -R www-data:www-data storage/
```

### 5. Apache Configuration
```apache
<VirtualHost *:80>
    ServerName yourdomain.com
    DocumentRoot /path/to/dskm-portal/public
    
    <Directory /path/to/dskm-portal/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

Enable mod_rewrite:
```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

### 6. Access Application
Visit: `http://yourdomain.com`

**Default Admin Credentials:**
- Username: `superadmin`
- Password: `Admin@1234`

**⚠️ IMPORTANT: Change this password immediately after first login!**

---

## 📁 Project Structure

```
dskm-portal/
├── app/
│   ├── Controllers/      # Business logic
│   ├── Models/          # Database models
│   ├── Middleware/      # Auth, CSRF, etc.
│   ├── Views/           # HTML templates
│   └── Helpers/         # Utility functions
├── config/              # Configuration files
├── core/                # Core framework classes
├── database/            # SQL schema
├── public/              # Web root
│   ├── assets/          # CSS, JS, images
│   ├── index.php        # Entry point
│   └── .htaccess        # URL rewriting
├── routes/              # Route definitions
├── storage/             # Uploads, logs
└── .env.example         # Environment template
```

---

## 🎨 Customization

### Change Colors
Edit `config/app.php`:
```php
'primary_color' => '#0B1F3A',  // Dark blue
'accent_color'  => '#D4AF37',  // Gold
```

### Update Logo
Replace in views or upload via settings panel.

### Modify Email Templates
Edit files in `app/Views/emails/` (if added).

---

## 🔒 Security Best Practices

1. **Change default admin password**
2. **Use strong database passwords**
3. **Enable HTTPS** (Let's Encrypt recommended)
4. **Regular backups** of database
5. **Keep PHP updated**
6. **Set proper file permissions**
7. **Enable error logging** (not display) in production

---

## 📊 Database Backup

```bash
# Backup
mysqldump -u root -p dskm_portal > backup_$(date +%Y%m%d).sql

# Restore
mysql -u root -p dskm_portal < backup_20240523.sql
```

---

## 🐛 Troubleshooting

### Database Connection Error
- Check `.env` credentials
- Ensure MySQL is running
- Verify database exists

### 404 Errors
- Check Apache mod_rewrite is enabled
- Verify `.htaccess` exists in `public/`
- Check DocumentRoot points to `public/`

### File Upload Issues
- Check `storage/` permissions (755)
- Verify `upload_max_filesize` in `php.ini`
- Check disk space

### Blank Page
- Enable error display temporarily:
  ```php
  ini_set('display_errors', 1);
  ```
- Check error logs: `/var/log/apache2/error.log`

---

## 📞 Support

For issues or questions:
- Check documentation first
- Review error logs
- Contact system administrator

---

## 📝 License

Proprietary - DSKM Alumni Association

---

## 👥 Credits

Developed for DSKM Dakhil 2010 & Alim 2012 Alumni

**Version:** 1.0.0  
**Last Updated:** May 2026

---

## 🔄 Updates & Maintenance

### Regular Maintenance Tasks:
- Weekly database backups
- Monthly security updates
- Quarterly feature reviews
- Annual data cleanup

### Update Checklist:
1. Backup database
2. Backup files
3. Test in staging
4. Deploy updates
5. Verify functionality

---

**Built with ❤️ for the DSKM Alumni Community**
