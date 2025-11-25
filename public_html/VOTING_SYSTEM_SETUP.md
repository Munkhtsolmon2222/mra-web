# MRA Awards 2025 Voting System - Setup Guide

## Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx) with mod_rewrite enabled
- GD library for image processing

## Installation Steps

### 1. Database Setup

1. Create a MySQL database:
   ```sql
   CREATE DATABASE mra_awards CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

2. Update database credentials in `includes/config.php`:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'mra_awards');
   define('DB_USER', 'your_username');
   define('DB_PASS', 'your_password');
   ```

3. Import the database schema:
   ```bash
   mysql -u your_username -p mra_awards < db/schema.sql
   ```
   Or use phpMyAdmin to import `db/schema.sql`

### 2. File Permissions

Set proper permissions for uploads directory:
```bash
chmod 755 uploads/logos
```

### 3. Default Admin Credentials

**IMPORTANT: Change these immediately after first login!**

- Username: `admin`
- Password: `admin123`

To change the password, you can update it directly in the database:
```sql
UPDATE admin_users 
SET password_hash = '$2y$10$...' 
WHERE username = 'admin';
```

Generate a new hash using PHP:
```php
echo password_hash('your_new_password', PASSWORD_DEFAULT);
```

### 4. Configuration

Review and update settings in `includes/config.php`:
- Database credentials
- Upload directory paths
- File size limits
- Allowed image types

## Usage

### Admin Panel
1. Navigate to `/admin/index.php`
2. Login with admin credentials
3. Add participants with logos
4. Manage participants (edit/delete)
5. View vote counts

### Voting Page
1. Users visit `/voting.php` or click "Санал өгөх" button on page6.html
2. Scroll through categories
3. Click "Санал өгөх" button for their choice
4. One vote per category per user (IP + session tracking)

## Features

- ✅ One vote per category per user
- ✅ Real-time vote counting
- ✅ Logo upload with automatic resizing
- ✅ Admin panel for participant management
- ✅ Cinematic, responsive design
- ✅ Security measures (CSRF, XSS, SQL injection prevention)
- ✅ Rate limiting (10 votes per hour per IP)

## Troubleshooting

### Database Connection Error
- Check database credentials in `includes/config.php`
- Verify database exists and user has proper permissions

### Image Upload Fails
- Check `uploads/logos/` directory permissions (755)
- Verify GD library is installed: `php -m | grep gd`
- Check PHP upload limits in `php.ini`

### Votes Not Recording
- Check database connection
- Verify session is working
- Check error logs in server logs

## Security Notes

- Change default admin password immediately
- Use HTTPS in production
- Regularly backup database
- Monitor vote patterns for suspicious activity
- Keep PHP and MySQL updated

