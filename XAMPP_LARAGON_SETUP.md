# 🖥️ LOCAL SERVER SETUP - XAMPP/LARAGON GUIDE

**Platform Compatibility:** XAMPP, Laragon, Docker, VPS  
**Difficulty Level:** Easy  
**Setup Time:** 15-30 minutes  
**Prerequisites:** None

---

## ✅ SYSTEM REQUIREMENTS

### Minimum Specifications
- **RAM:** 2GB
- **Storage:** 500MB
- **PHP Version:** 8.1+
- **MySQL:** 8.0+
- **OS:** Windows, Mac, Linux

### Recommended Specifications
- **RAM:** 4GB
- **Storage:** 1GB
- **PHP:** 8.2+
- **MySQL:** 8.0.36+
- **OS:** Windows 10/11, macOS 12+, Ubuntu 22.04

---

## 📦 PRE-INSTALLATION CHECKLIST

### What You Need
- [ ] XAMPP or Laragon (download from official site)
- [ ] PHP 8.1+ (included in XAMPP/Laragon)
- [ ] MySQL 8.0+ (included in XAMPP/Laragon)
- [ ] Project files (dskm-portal folder)
- [ ] Text editor (VS Code recommended)
- [ ] Web browser (Chrome, Firefox)

### Download Links
- **XAMPP:** https://www.apachefriends.org/
- **Laragon:** https://laragon.org/
- **VS Code:** https://code.visualstudio.com/

---

## 🚀 STEP-BY-STEP SETUP

---

## **METHOD 1: XAMPP (Windows/Mac/Linux)**

### Step 1: Install XAMPP
1. Download XAMPP (PHP 8.1+ version)
2. Run installer
3. Select components: Apache, MySQL, PHP
4. Choose installation path
5. Complete installation

### Step 2: Extract Project
1. Navigate to XAMPP `htdocs` folder
   - **Windows:** `C:\xampp\htdocs\`
   - **Mac:** `/Applications/XAMPP/htdocs/`
   - **Linux:** `/opt/lampp/htdocs/`

2. Extract dskm-portal folder there

### Step 3: Start Services
1. Open XAMPP Control Panel
2. Start Apache (green checkbox)
3. Start MySQL (green checkbox)
4. Verify: Both should show green

```
Apache ✅ Running (PID: xxxx)
MySQL  ✅ Running (PID: xxxx)
```

### Step 4: Create Database
1. Open phpMyAdmin
   - URL: `http://localhost/phpmyadmin`
   - Username: `root`
   - Password: (leave blank)

2. Click "New"

3. Create database:
   ```
   Database Name: dskm_portal
   Collation: utf8mb4_unicode_ci
   ```

4. Click "Create"

### Step 5: Import Schema
1. Click on `dskm_portal` database
2. Click "Import" tab
3. Choose file: `database/schema.sql`
4. Click "Go"
5. Wait for success message ✅

### Step 6: Configure Environment
1. Navigate to: `C:\xampp\htdocs\dskm-portal\`
2. Copy `.env.example` to `.env`
3. Edit `.env`:

```bash
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost/dskm-portal

DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=dskm_portal
DB_USERNAME=root
DB_PASSWORD=

MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=465
```

### Step 7: Set Permissions
```bash
# Windows (Run as Administrator)
icacls "C:\xampp\htdocs\dskm-portal\storage" /grant Everyone:(F) /T

# Mac/Linux
chmod -R 755 /Applications/XAMPP/htdocs/dskm-portal
chmod -R 775 /Applications/XAMPP/htdocs/dskm-portal/storage
```

### Step 8: Access Application
1. Open browser
2. Navigate to: `http://localhost/dskm-portal/public`
3. You should see landing page ✅

---

## **METHOD 2: LARAGON (Windows)**

### Step 1: Install Laragon
1. Download Laragon (PHP 8.1+ version)
2. Run installer (.exe)
3. Choose installation path
4. Select components: Apache, MySQL, PHP
5. Complete installation

### Step 2: Extract Project
1. In Laragon menu, click "Web Root"
2. Extract dskm-portal folder there
3. Or use Laragon app to create new project:
   - Right-click > New > Create a project
   - Name: dskm-portal
   - Extract files into created folder

### Step 3: Start Services
1. Click "Start All" in Laragon
2. Wait for green checkmarks

```
All services running:
Apache ✅
MySQL  ✅
Redis  ✅
```

### Step 4: Create Database
1. In Laragon, click "Database" menu
2. Click "MySQL Client"
3. Run commands:

```sql
CREATE DATABASE dskm_portal CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE dskm_portal;
SOURCE database/schema.sql;
```

### Step 5: Configure Environment
1. Copy `.env.example` to `.env`
2. Edit `.env`:

```env
APP_ENV=local
APP_DEBUG=true
APP_URL=http://dskm-portal.local

DB_HOST=127.0.0.1
DB_DATABASE=dskm_portal
DB_USERNAME=root
DB_PASSWORD=
```

### Step 6: Set Permissions
```bash
Right-click folder > Properties > Security > Edit
Add "Everyone" with Full Control
```

### Step 7: Access Application
1. In Laragon, right-click project > "Open in Browser"
2. Or navigate to: `http://dskm-portal.local`
3. See landing page ✅

---

## **METHOD 3: DOCKER (Cross-Platform)**

### Step 1: Create docker-compose.yml
```yaml
version: '3.8'

services:
  apache:
    image: php:8.1-apache
    ports:
      - "80:80"
    volumes:
      - ./dskm-portal:/var/www/html
    environment:
      - APACHE_DOCUMENT_ROOT=/var/www/html/public
    depends_on:
      - mysql

  mysql:
    image: mysql:8.0
    environment:
      MYSQL_DATABASE: dskm_portal
      MYSQL_ROOT_PASSWORD: root
    ports:
      - "3306:3306"
    volumes:
      - ./dskm-portal/database:/docker-entrypoint-initdb.d
```

### Step 2: Run Docker
```bash
docker-compose up -d
```

### Step 3: Access Application
```
http://localhost
```

---

## 🔑 DEFAULT CREDENTIALS

### Admin Login
```
Username: superadmin
Password: Admin@1234
```

**⚠️ IMPORTANT:** Change this password immediately after first login!

### Database
```
Host:     localhost
User:     root
Password: (leave blank in XAMPP)
Database: dskm_portal
```

---

## ✅ VERIFICATION CHECKLIST

After installation, verify:

```
✅ Apache running
✅ MySQL running
✅ Database created
✅ .env configured
✅ Can access http://localhost/dskm-portal
✅ Can see landing page
✅ Can login with default credentials
✅ Can upload files
✅ Can create database entries
```

---

## 🧪 QUICK TEST STEPS

### Test 1: Access Homepage
1. Go to: `http://localhost/dskm-portal`
2. Should see: Hero section, features, login button
3. Status: ✅ Pass

### Test 2: User Registration
1. Click "Register"
2. Fill form (multi-step)
3. Submit
4. Status: ✅ Pass if no error

### Test 3: Admin Login
1. Click "Login"
2. Username: `superadmin`
3. Password: `Admin@1234`
4. Should see: Dashboard
5. Status: ✅ Pass

### Test 4: Create Event
1. From dashboard: Click "Events"
2. Click "Create Event"
3. Fill form
4. Upload banner image
5. Submit
6. Status: ✅ Pass if event appears

### Test 5: Upload Files
1. Go to: Profile > Edit Profile
2. Upload avatar image
3. Should appear immediately
4. Status: ✅ Pass

---

## 🔧 COMMON ISSUES & SOLUTIONS

### Issue: "404 Page Not Found"
**Cause:** .htaccess not working  
**Solution:**
- Enable mod_rewrite in Apache
- XAMPP: uncomment `LoadModule rewrite_module` in httpd.conf
- Laragon: Usually automatic

### Issue: "Connection Refused - 3306"
**Cause:** MySQL not running  
**Solution:**
- Start MySQL in XAMPP/Laragon
- Check if port 3306 is available
- Verify no other MySQL instance running

### Issue: "Permission Denied - Storage"
**Cause:** Folder permissions  
**Solution:**
- Give write permission to storage folder
- Windows: Right-click > Properties > Security
- Mac/Linux: `chmod -R 775 storage/`

### Issue: "Database Import Failed"
**Cause:** Encoding issues  
**Solution:**
- Use phpMyAdmin import
- Ensure UTF-8 encoding selected
- Check file integrity

### Issue: "Blank Page / White Screen"
**Cause:** PHP error display disabled  
**Solution:**
- Set `APP_DEBUG=true` in .env
- Check error logs in `storage/logs/`
- Enable error display in php.ini

### Issue: "Session Not Working"
**Cause:** Session directory permissions  
**Solution:**
- Set storage/sessions permissions
- Clear browser cookies
- Restart services

---

## 📊 PERFORMANCE OPTIMIZATION

### For XAMPP
1. Increase PHP memory limit in `php.ini`:
   ```
   memory_limit = 256M
   ```

2. Increase upload size:
   ```
   upload_max_filesize = 50M
   post_max_size = 50M
   ```

3. Enable OPcache:
   ```
   opcache.enable = 1
   opcache.memory_consumption = 128
   ```

### For Laragon
1. Usually optimized by default
2. Check: Tools > PHP > Settings
3. Adjust if needed

---

## 🚀 PRODUCTION vs DEVELOPMENT

### Local Development (XAMPP/Laragon)
- ✅ `.env`: APP_DEBUG=true
- ✅ Error display enabled
- ✅ No SSL required
- ✅ Single user testing
- ✅ Fast setup

### Production (Linux Server)
- ✅ `.env`: APP_DEBUG=false
- ✅ Error logging only
- ✅ SSL/HTTPS required
- ✅ Multiple users
- ✅ Security hardened

---

## 📋 FINAL SETUP CHECKLIST

```
SYSTEM SETUP
✅ XAMPP/Laragon installed
✅ PHP 8.1+ verified
✅ MySQL running
✅ Apache running

PROJECT SETUP
✅ Files extracted
✅ Database created
✅ Schema imported
✅ .env configured
✅ Permissions set
✅ Storage folder writable

TESTING
✅ Can access homepage
✅ Can login
✅ Can register
✅ Can create content
✅ File uploads working
✅ Database operations working

READY TO USE
✅ Application fully functional
✅ All features accessible
✅ Admin panel working
✅ Development environment ready
```

---

## 🎯 NEXT STEPS

### After Setup
1. ✅ Login with default admin credentials
2. ✅ Change admin password
3. ✅ Create test users
4. ✅ Explore all modules
5. ✅ Test all features
6. ✅ Customize branding (colors, logo)
7. ✅ Configure email settings

### Before Production
1. ✅ Change all default passwords
2. ✅ Enable HTTPS/SSL
3. ✅ Set APP_DEBUG=false
4. ✅ Configure backup strategy
5. ✅ Set up monitoring
6. ✅ Configure email properly
7. ✅ Test with real data

---

## 📞 SUPPORT

**Local Setup Issues:**
- Check Apache/MySQL status
- Verify file permissions
- Clear browser cache
- Check error logs

**Application Issues:**
- Check `.env` configuration
- Verify database connection
- Review application logs

---

**Setup Guide Status:** ✅ Complete  
**Verified With:** XAMPP 8.2, Laragon 5.0  
**Last Updated:** May 2026

🎉 **Your DSKM Portal is now ready to run locally!**
