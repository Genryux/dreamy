# 🚀 Dreamy School Management System - Pre-Deployment Checklist

## ⚠️ **CRITICAL ACTIONS REQUIRED BEFORE DEPLOYMENT**

### 1️⃣ **Environment Configuration (.env)**

#### **Required Environment Variables**

```bash
# Application
APP_NAME="Dreamy School Management"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com  # ⚠️ CHANGE THIS
APP_TIMEZONE=Asia/Manila

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dreamy_school  # ⚠️ CHANGE THIS
DB_USERNAME=your_db_user  # ⚠️ CHANGE THIS
DB_PASSWORD=your_secure_password  # ⚠️ CHANGE THIS

# Broadcasting (Laravel Reverb) - ⚠️ CRITICAL FOR MOBILE APP NOTIFICATIONS
REVERB_APP_ID=your_app_id
REVERB_APP_KEY=your_app_key  # ⚠️ MUST MATCH mobile app config
REVERB_APP_SECRET=your_app_secret
REVERB_HOST=your-domain.com  # ⚠️ CHANGE THIS
REVERB_PORT=8080
REVERB_SCHEME=https  # ⚠️ CHANGE TO 'https' FOR PRODUCTION
REVERB_SERVER_HOST=0.0.0.0
REVERB_SERVER_PORT=8080

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com  # Or your mail server
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com  # ⚠️ CHANGE THIS
MAIL_PASSWORD=your-app-password  # ⚠️ CHANGE THIS
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@your-domain.com  # ⚠️ CHANGE THIS
MAIL_FROM_NAME="${APP_NAME}"

# Queue (Required for notifications)
QUEUE_CONNECTION=database

# Cache
CACHE_STORE=database

# Session
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=true

# Encryption Key (Generate new)
php artisan key:generate
```

---

## 2️⃣ **Mobile App Configuration Updates**

### **File: `dreamy_app/config/api.ts`**

Update the production URL:

```typescript
export const API_CONFIG = {
  PRODUCTION: {
    BASE_URL: 'https://your-domain.com',  // ⚠️ CHANGE THIS
    REVERB_HOST: 'your-domain.com',  // ⚠️ CHANGE THIS
    REVERB_PORT: 8080,
    REVERB_SCHEME: 'wss',  // ⚠️ SECURE WebSocket
    name: 'Production'
  },
  
  // Keep development configs for testing
  HOME: {
    BASE_URL: 'http://192.168.100.10:8888',
    REVERB_HOST: '192.168.100.10',
    REVERB_PORT: 8080,
    REVERB_SCHEME: 'ws',
    name: 'Home WiFi (Development)'
  }
};

// ⚠️ CHANGE THIS TO 'PRODUCTION' FOR DEPLOYMENT
export const CURRENT_ENV: keyof typeof API_CONFIG = 'PRODUCTION';
```

### **File: `dreamy_app/config/notifications.ts`**

Update the Reverb app key to match your Laravel .env:

```typescript
export const NOTIFICATION_CONFIG = {
  REVERB_APP_KEY: 'your-actual-reverb-app-key',  // ⚠️ MUST MATCH .env REVERB_APP_KEY
  // ... rest stays the same
};
```

---

## 3️⃣ **Electron Desktop App Configuration**

### **File: `laravel-electron/main.js`**

Update the production URL:

```javascript
const targetConfig = isDev ? {
  url: 'http://dreamy.test/portal/login',
  name: 'Local Development'
} : {
  url: 'https://your-domain.com/portal/login',  // ⚠️ CHANGE THIS
  name: 'Production'
};
```

---

## 4️⃣ **Database Setup**

### **Run Migrations**

```bash
# Run all migrations
php artisan migrate --force

# Optional: Run seeders if needed
php artisan db:seed --class=DatabaseSeeder
```

### **Database Permissions**

```sql
-- Ensure proper permissions for storage
-- Grant execute permissions on database functions
```

---

## 5️⃣ **Laravel Reverb Setup**

### **Start Reverb Server**

```bash
# Install Reverb
php artisan reverb:install

# Start the server
php artisan reverb:start

# Or run in background
php artisan reverb:start --daemon
```

### **⚠️ CRITICAL: Firewall Configuration**

```bash
# Allow Reverb port
sudo ufw allow 8080/tcp
```

---

## 6️⃣ **Queue Worker Setup**

### **Start Queue Worker**

```bash
# Development (one-off)
php artisan queue:work

# Production (supervisor/daemon)
php artisan queue:work --daemon

# Or use supervisor (recommended for production)
```

### **Supervisor Configuration**

Create `/etc/supervisor/conf.d/dreamy-worker.conf`:

```ini
[program:dreamy-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/dreamy/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/path/to/dreamy/storage/logs/worker.log
stopwaitsecs=3600
```

---

## 7️⃣ **Application Optimizations**

### **Cache Configuration**

```bash
# Cache routes
php artisan route:cache

# Cache config
php artisan config:cache

# Cache views
php artisan view:cache

# Cache events
php artisan event:cache

# Optimize
php artisan optimize
```

### **Storage Permissions**

```bash
# Set proper permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### **Generate Application Key**

```bash
php artisan key:generate --force
```

---

## 8️⃣ **Security Checklist**

### **✅ Required Security Actions**

```bash
# [ ] APP_DEBUG=false in .env
# [ ] APP_ENV=production in .env
# [ ] Strong encryption key generated
# [ ] HTTPS enabled (SSL certificate installed)
# [ ] Secure passwords for database
# [ ] CORS configured properly
# [ ] Rate limiting enabled
# [ ] API keys secured
# [ ] .env file NOT in version control
# [ ] CSRF protection enabled
```

### **HTTPS Configuration**

1. Install SSL certificate (Let's Encrypt recommended)
2. Force HTTPS redirect in `.env`:
   ```bash
   APP_URL=https://your-domain.com
   ```
3. Update Nginx/Apache to redirect HTTP to HTTPS

### **Rate Limiting**

Already configured in routes, but verify in `app/Http/Kernel.php`:
```php
'throttle:60,1', // 60 requests per minute
```

---

## 9️⃣ **Mobile App Build Configuration**

### **Update Android Configuration**

**File: `dreamy_app/app.json`**

```json
{
  "expo": {
    "name": "Dreamy School App",
    "android": {
      "package": "com.dreamy.school.app",
      "versionCode": 1,
      "permissions": [
        "INTERNET",
        "ACCESS_NETWORK_STATE",
        "ACCESS_WIFI_STATE"
      ]
    }
  }
}
```

### **Build Commands**

```bash
# Navigate to mobile app directory
cd dreamy_app

# Build for Android
npx expo build:android

# Build for iOS (if needed)
npx expo build:ios
```

---

## 🔟 **Testing Checklist**

### **Before Deployment:**

- [ ] **Backend API:**
  - [ ] Test all routes in `routes/api.php`
  - [ ] Test authentication endpoints
  - [ ] Test notification broadcasting
  - [ ] Test payment plan calculations
  - [ ] Test invoice generation
  - [ ] Verify email notifications work

- [ ] **Mobile App:**
  - [ ] Test login/logout
  - [ ] Test PIN setup and verification
  - [ ] Test notifications (WebSocket)
  - [ ] Test invoice viewing
  - [ ] Test payment viewing
  - [ ] Test academic information display

- [ ] **Desktop App:**
  - [ ] Test admin login
  - [ ] Test platform detection middleware
  - [ ] Test restricted routes
  - [ ] Test invoice management
  - [ ] Test student management

- [ ] **Integration:**
  - [ ] Mobile app ↔ Backend API
  - [ ] Desktop app ↔ Backend API
  - [ ] Realtime notifications work
  - [ ] Email notifications delivered

---

## 1️⃣1️⃣ **Production Server Setup**

### **Server Requirements**

```bash
PHP 8.2+
MySQL/MariaDB 10.3+
Node.js 18+ (for mobile app build)
Composer 2.x
Nginx or Apache
SSL certificate
```

### **Nginx Configuration Example**

```nginx
server {
    listen 80;
    server_name your-domain.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name your-domain.com;
    root /path/to/dreamy/public;

    ssl_certificate /path/to/cert.pem;
    ssl_certificate_key /path/to/key.pem;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### **WebSocket Proxy (Reverb)**

Add to Nginx config:

```nginx
location /app {
    proxy_pass http://127.0.0.1:8080;
    proxy_http_version 1.1;
    proxy_set_header Upgrade $http_upgrade;
    proxy_set_header Connection "upgrade";
    proxy_set_header Host $host;
    proxy_cache_bypass $http_upgrade;
}
```

---

## 1️⃣2️⃣ **Backup Strategy**

### **Before Deployment:**

```bash
# Backup database
mysqldump -u username -p database_name > backup.sql

# Backup .env file
cp .env .env.backup

# Backup storage
tar -czf storage_backup.tar.gz storage/
```

---

## 1️⃣3️⃣ **Monitoring & Logging**

### **Log Files Location**

```bash
storage/logs/laravel.log  # Application logs
storage/logs/worker.log  # Queue worker logs
```

### **Monitor:**

- Application errors
- Queue failures
- Reverb connection issues
- API response times
- Database queries

---

## 1️⃣4️⃣ **Post-Deployment Verification**

### **Immediate Checks:**

- [ ] Application loads without errors
- [ ] SSL certificate active (green padlock)
- [ ] Mobile app can connect to backend
- [ ] Desktop app can connect to backend
- [ ] Notifications working (test broadcast)
- [ ] User login works
- [ ] Email sending works
- [ ] Database connections stable
- [ ] Queue processing jobs running
- [ ] Reverb server running and accessible

### **Functional Tests:**

- [ ] Create a test invoice
- [ ] Generate payment plan
- [ ] Record a test payment
- [ ] Send test notification
- [ ] Verify PDF generation
- [ ] Test email delivery

---

## 🚨 **CRITICAL ISSUES TO FIX BEFORE DEPLOYMENT**

### **1. Mobile App API Configuration**
⚠️ **CURRENTLY:** Using localhost IPs (192.168.x.x)  
⚠️ **MUST CHANGE:** Update to production domain in `dreamy_app/config/api.ts`

### **2. Electron Desktop URL**
⚠️ **CURRENTLY:** Using `http://dreamy.test/portal/login`  
⚠️ **MUST CHANGE:** Update to production URL in `laravel-electron/main.js`

### **3. Reverb Configuration**
⚠️ **CURRENTLY:** Using development settings  
⚠️ **MUST CHANGE:** Update REVERB_HOST and REVERB_SCHEME in .env

### **4. Environment Variables**
⚠️ **CRITICAL:** Ensure all sensitive data is in `.env` and NOT committed to Git

### **5. HTTPS/WSS Configuration**
⚠️ **REQUIRED:** Production MUST use HTTPS and WSS (secure WebSocket)

---

## 📋 **Final Pre-Deployment Steps**

1. ✅ Update all configuration files
2. ✅ Run `composer install --no-dev --optimize-autoloader`
3. ✅ Run `npm run build` for frontend assets
4. ✅ Run `php artisan optimize`
5. ✅ Clear all caches
6. ✅ Run migrations
7. ✅ Test locally with production settings
8. ✅ Backup database
9. ✅ Deploy
10. ✅ Verify all services running
11. ✅ Run post-deployment checks

---

## 🔗 **Quick Reference Commands**

```bash
# Pre-deployment
composer install --no-dev --optimize-autoloader
npm run build
php artisan optimize

# After deployment
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan queue:restart
php artisan reverb:start --daemon

# Monitoring
tail -f storage/logs/laravel.log
php artisan queue:listen
```

---

**Last Updated:** $(date)  
**Version:** Dreamy v1.0.0  
**Status:** Pre-deployment checklist ready


