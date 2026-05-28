# 🚀 DSKM BATCH PORTAL - DEPLOYMENT GUIDE

**Production Deployment Checklist & Server Setup**

---

## 📋 PRE-DEPLOYMENT CHECKLIST

### Required Information
- [ ] Domain name (e.g., alumni.dskm.edu.bd)
- [ ] Server IP address
- [ ] SSH credentials
- [ ] Database credentials
- [ ] SMTP email credentials
- [ ] SSL certificate (Let's Encrypt recommended)

---

## 🖥️ SERVER REQUIREMENTS

### Minimum Specifications
- **OS:** Ubuntu 20.04/22.04 LTS or CentOS 7/8
- **PHP:** 8.1 or higher
- **MySQL:** 8.0 or higher
- **RAM:** 2GB minimum, 4GB recommended
- **Storage:** 20GB minimum, 50GB recommended
- **Bandwidth:** 100GB/month minimum

### Required Software
```bash
# Apache/Nginx
# PHP 8.1+ with extensions:
- php-mysql
- php-mbstring
- php-xml
- php-gd
- php-curl
- php-zip
- php-json

# MySQL 8.0+
# Composer (optional)
# Git (optional)
```

---

## 🛠️ SERVER SETUP (Ubuntu 22.04)

### 1. Update System
```bash
sudo apt update && sudo apt upgrade -y
```

### 2. Install Apache
```bash
sudo apt install apache2 -y
sudo systemctl start apache2
sudo systemctl enable apache2
```

### 3. Install PHP 8.1
```bash
sudo apt install software-properties-common -y
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update

sudo apt install php8.1 php8.1-cli php8.1-common php8.1-mysql \
php8.1-zip php8.1-gd php8.1-mbstring php8.1-curl php8.1-xml -y
```

### 4. Install MySQL
```bash
sudo apt install mysql-server -y
sudo mysql_secure_installation
```

### 5. Configure MySQL
```bash
sudo mysql -u root -p

CREATE DATABASE dskm_portal CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'dskm_user'@'localhost' IDENTIFIED BY 'STRONG_PASSWORD_HERE';
GRANT ALL PRIVILEGES ON dskm_portal.* TO 'dskm_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

---

## 📦 APPLICATION DEPLOYMENT

### 1. Upload Files
```bash
# Option A: Via SCP
scp -r dskm-portal/ user@server:/var/www/

# Option B: Via Git
cd /var/www/
git clone <repository-url> dskm-portal
```

### 2. Set Permissions
```bash
cd /var/www/dskm-portal
sudo chown -R www-data:www-data .
sudo chmod -R 755 .
sudo chmod -R 775 storage/
sudo chmod -R 775 public/uploads/
```

### 3. Import Database
```bash
mysql -u dskm_user -p dskm_portal < database/schema.sql
```

### 4. Configure Environment
```bash
cp .env.example .env
nano .env
```

Update `.env` with production values:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://alumni.dskm.edu.bd

DB_HOST=localhost
DB_DATABASE=dskm_portal
DB_USERNAME=dskm_user
DB_PASSWORD=YOUR_STRONG_PASSWORD

MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=noreply@dskm.edu.bd
MAIL_PASSWORD=YOUR_APP_PASSWORD
```

---

## 🌐 APACHE CONFIGURATION

### 1. Create Virtual Host
```bash
sudo nano /etc/apache2/sites-available/dskm-portal.conf
```

Add configuration:
```apache
<VirtualHost *:80>
    ServerName alumni.dskm.edu.bd
    ServerAdmin admin@dskm.edu.bd
    DocumentRoot /var/www/dskm-portal/public

    <Directory /var/www/dskm-portal/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/dskm_error.log
    CustomLog ${APACHE_LOG_DIR}/dskm_access.log combined
</VirtualHost>
```

### 2. Enable Site
```bash
sudo a2enmod rewrite
sudo a2ensite dskm-portal.conf
sudo systemctl restart apache2
```

---

## 🔒 SSL CERTIFICATE (Let's Encrypt)

### 1. Install Certbot
```bash
sudo apt install certbot python3-certbot-apache -y
```

### 2. Obtain Certificate
```bash
sudo certbot --apache -d alumni.dskm.edu.bd
```

### 3. Auto-Renewal
```bash
sudo systemctl status certbot.timer
```

---

## 🛡️ SECURITY HARDENING

### 1. Configure Firewall
```bash
sudo ufw allow 'Apache Full'
sudo ufw allow OpenSSH
sudo ufw enable
```

### 2. Hide PHP Version
```bash
sudo nano /etc/php/8.1/apache2/php.ini
# Set: expose_php = Off
```

### 3. Disable Directory Listing
Already configured in `.htaccess`:
```apache
Options -Indexes
```

### 4. Set Secure File Permissions
```bash
find /var/www/dskm-portal -type f -exec chmod 644 {} \;
find /var/www/dskm-portal -type d -exec chmod 755 {} \;
chmod -R 775 storage/
```

### 5. Change Default Admin Password
```sql
UPDATE users 
SET password = '$2y$12$NEW_BCRYPT_HASH_HERE' 
WHERE username = 'superadmin';
```

---

## 📊 MONITORING & MAINTENANCE

### 1. Enable Error Logging
```php
// In production, set in .env:
APP_DEBUG=false

// Errors logged to: storage/logs/
```

### 2. Database Backup (Daily)
```bash
#!/bin/bash
# Save as: /root/backup-dskm.sh

DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/backups/dskm"
mkdir -p $BACKUP_DIR

mysqldump -u dskm_user -p'PASSWORD' dskm_portal | gzip > $BACKUP_DIR/dskm_$DATE.sql.gz

# Keep only last 30 days
find $BACKUP_DIR -name "*.sql.gz" -mtime +30 -delete
```

Add to crontab:
```bash
sudo crontab -e
# Add: 0 2 * * * /root/backup-dskm.sh
```

### 3. File Backup
```bash
tar -czf /backups/dskm-files-$(date +%Y%m%d).tar.gz /var/www/dskm-portal/storage/uploads/
```

---

## 🚦 PERFORMANCE OPTIMIZATION

### 1. Enable PHP OPcache
```bash
sudo nano /etc/php/8.1/apache2/php.ini
```

Add:
```ini
opcache.enable=1
opcache.memory_consumption=128
opcache.max_accelerated_files=10000
```

### 2. Enable Apache Compression
```bash
sudo a2enmod deflate
sudo systemctl restart apache2
```

### 3. MySQL Optimization
```bash
sudo nano /etc/mysql/mysql.conf.d/mysqld.cnf
```

Add:
```ini
innodb_buffer_pool_size = 1G
query_cache_size = 64M
max_connections = 200
```

---

## 📧 EMAIL CONFIGURATION

### Gmail SMTP Setup
1. Enable 2-Factor Authentication
2. Generate App Password
3. Update `.env`:
```env
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_FROM=noreply@dskm.edu.bd
```

---

## ✅ POST-DEPLOYMENT VERIFICATION

### 1. Check Website Access
```bash
curl -I https://alumni.dskm.edu.bd
# Should return: HTTP/2 200
```

### 2. Test Database Connection
- Visit: https://alumni.dskm.edu.bd/login
- Try logging in with default credentials

### 3. Test File Uploads
- Upload profile picture
- Check file appears in `storage/uploads/`

### 4. Verify SSL
- Visit: https://www.ssllabs.com/ssltest/
- Check your domain for SSL grade

### 5. Test Email
- Use password reset feature
- Verify email delivery

---

## 🆘 TROUBLESHOOTING

### Issue: 500 Internal Server Error
```bash
# Check Apache error log
sudo tail -f /var/log/apache2/error.log

# Check file permissions
ls -la /var/www/dskm-portal/storage/
```

### Issue: Database Connection Failed
```bash
# Test MySQL connection
mysql -u dskm_user -p dskm_portal

# Check .env credentials
cat /var/www/dskm-portal/.env | grep DB_
```

### Issue: File Upload Not Working
```bash
# Check directory permissions
sudo chmod -R 775 /var/www/dskm-portal/storage/

# Check PHP upload limits
php -i | grep upload_max_filesize
```

---

## 📱 MOBILE APP API (Future)

If developing mobile app:
```bash
# Enable CORS in .htaccess
Header set Access-Control-Allow-Origin "*"
Header set Access-Control-Allow-Methods "GET, POST, PUT, DELETE"
```

---

## 🔄 UPDATE PROCEDURE

### Application Updates
```bash
# 1. Backup current version
cp -r /var/www/dskm-portal /var/www/dskm-portal.backup

# 2. Pull updates
cd /var/www/dskm-portal
git pull origin main

# 3. Run migrations (if any)
mysql -u dskm_user -p dskm_portal < database/migrations/latest.sql

# 4. Clear cache
rm -rf storage/cache/*

# 5. Restart Apache
sudo systemctl restart apache2
```

---

## 📞 SUPPORT CONTACTS

**System Administrator:**
- Email: admin@dskm.edu.bd
- Phone: +880-XXX-XXXXXX

**Technical Support:**
- Emergency: Available 24/7
- Regular: 9 AM - 5 PM (GMT+6)

---

## 📊 MONITORING CHECKLIST

**Daily:**
- [ ] Check error logs
- [ ] Monitor disk space
- [ ] Verify backups completed

**Weekly:**
- [ ] Review performance metrics
- [ ] Check SSL certificate expiry
- [ ] Update pending users

**Monthly:**
- [ ] Apply security patches
- [ ] Review user activity
- [ ] Optimize database
- [ ] Test backup restoration

---

**Deployment Complete! 🎉**

**Next Steps:**
1. Change default admin password
2. Configure email settings
3. Test all features
4. Train administrators
5. Launch to users

---

**Document Version:** 1.0  
**Last Updated:** May 2026  
**Deployed By:** _______________  
**Deployment Date:** _______________
