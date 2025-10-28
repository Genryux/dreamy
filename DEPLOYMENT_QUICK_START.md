# 🚀 Dreamy Deployment - Quick Start Guide

## ⏰ **Before You Deploy Today - DO THESE FIRST:**

### 🔴 **1. Update Mobile App Configuration (CRITICAL)**

**File:** `dreamy_app/config/api.ts`

Change line 24:
```typescript
// FROM:
export const CURRENT_ENV: keyof typeof API_CONFIG = 'HOME';

// TO (Add production config):
export const CURRENT_ENV: keyof typeof API_CONFIG = 'PRODUCTION';
```

And add production configuration:
```typescript
export const API_CONFIG = {
  PRODUCTION: {
    BASE_URL: 'https://your-actual-domain.com',  // ⚠️ YOUR SERVER
    REVERB_HOST: 'your-actual-domain.com',  // ⚠️ YOUR SERVER
    REVERB_PORT: 8080,
    REVERB_SCHEME: 'wss',  // SECURE WEBSOCKET
    name: 'Production'
  },
  // ... rest of configs
};
```

---

### 🔴 **2. Update Desktop App URL (CRITICAL)**

**File:** `laravel-electron/main.js`

Change lines 9-12:
```javascript
} : {
  url: 'https://your-actual-domain.com/portal/login',  // ⚠️ CHANGE
  name: 'Production'
};
```

---

### 🔴 **3. Configure Backend .env File (CRITICAL)**

**File:** `.env` on your production server

```bash
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-actual-domain.com

# Database - Update with your credentials
DB_DATABASE=your_db_name
DB_USERNAME=your_db_user
DB_PASSWORD=your_secure_password

# Reverb - MUST MATCH mobile app config
REVERB_APP_ID=your_app_id
REVERB_APP_KEY=your_app_key  # ⚠️ SAME AS mobile app
REVERB_APP_SECRET=your_app_secret
REVERB_HOST=your-actual-domain.com
REVERB_PORT=8080
REVERB_SCHEME=https  # ⚠️ HTTPS

# Mail
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_FROM_ADDRESS=noreply@your-domain.com

# Queue
QUEUE_CONNECTION=database
```

---

### ✅ **4. Run These Commands on Production Server**

```bash
# 1. Install dependencies
composer install --no-dev --optimize-autoloader
npm install

# 2. Generate app key (if new deployment)
php artisan key:generate

# 3. Run migrations
php artisan migrate --force

# 4. Clear and cache everything
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

php artisan optimize

# 5. Set permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# 6. Build frontend assets
npm run build
```

---

### ✅ **5. Start Required Services**

```bash
# 1. Start Laravel Reverb (CRITICAL FOR MOBILE NOTIFICATIONS)
php artisan reverb:start --daemon

# 2. Start Queue Worker (CRITICAL FOR EMAILS/NOTIFICATIONS)
php artisan queue:work --daemon

# Or use supervisor (recommended)
```

---

### ✅ **6. Configure Web Server**

**Allow WebSocket connections on port 8080:**

```nginx
# Nginx location block for Reverb
location /app {
    proxy_pass http://127.0.0.1:8080;
    proxy_http_version 1.1;
    proxy_set_header Upgrade $http_upgrade;
    proxy_set_header Connection "upgrade";
    proxy_set_header Host $host;
    proxy_cache_bypass $http_upgrade;
}
```

**Firewall:**
```bash
sudo ufw allow 8080/tcp
```

---

### ✅ **7. Build Mobile App**

```bash
cd dreamy_app

# Build Android APK
npx expo build:android

# Or test locally first
npx expo start
```

---

### ✅ **8. Build Desktop App**

```bash
cd laravel-electron

# Build for Windows
npm run build:win

# Or run locally to test
npm run start
```

---

### ✅ **9. Post-Deployment Verification**

```bash
# 1. Check application loads
curl https://your-domain.com

# 2. Check Reverb is running
curl http://localhost:8080

# 3. Check API endpoints
curl https://your-domain.com/api/tite

# 4. View logs
tail -f storage/logs/laravel.log
```

---

## 🧪 **Testing Checklist**

### **Backend:**
- [ ] Login page loads
- [ ] API responds at `/api/tite`
- [ ] Database connection works
- [ ] Email sending configured

### **Mobile App:**
- [ ] Can connect to API
- [ ] Login works
- [ ] Notifications connect (check Reverb)
- [ ] Invoices display
- [ ] Academic info loads

### **Desktop App:**
- [ ] Can connect to admin panel
- [ ] Platform detection works
- [ ] Admin features accessible
- [ ] Reports generate

---

## 🚨 **Emergency Fixes**

### **If mobile app can't connect:**
1. Check `dreamy_app/config/api.ts` - CURRENT_ENV must be 'PRODUCTION'
2. Check `dreamy_app/config/notifications.ts` - REVERB_APP_KEY matches .env
3. Verify Reverb is running: `php artisan reverb:start`
4. Check firewall allows port 8080

### **If notifications don't work:**
1. Verify Reverb server is running
2. Check REVERB_SCHEME is 'https' in production
3. Check REVERB_HOST matches domain
4. Check WebSocket proxy in Nginx
5. Check mobile app REVERB_SCHEME is 'wss'

### **If desktop app fails:**
1. Update `laravel-electron/main.js` URL
2. Rebuild: `npm run build:win`
3. Check user agent detection in backend

---

## 📱 **Mobile App Configuration Notes**

### **Current Status:**
- ✅ Using hardcoded local IP addresses (192.168.x.x)
- ⚠️ MUST add production environment to `api.ts`
- ⚠️ MUST update `CURRENT_ENV` before building

### **Reverb App Key:**
- Current: 'ak6vcojiwfqssrwgezk4'
- ⚠️ MUST match `REVERB_APP_KEY` in Laravel .env

---

## 🖥️ **Desktop App Configuration Notes**

### **Current Status:**
- ⚠️ Hardcoded to 'http://dreamy.test/portal/login'
- ⚠️ MUST update line 10 in `main.js` to production URL

---

## 🔐 **Security Checklist**

- [ ] APP_DEBUG=false
- [ ] APP_ENV=production
- [ ] HTTPS enabled
- [ ] SSL certificate valid
- [ ] Strong database passwords
- [ ] .env file NOT in git
- [ ] Storage permissions correct
- [ ] Session encryption enabled

---

## 🎯 **Priority Actions for Today's Deployment:**

1. **FIRST:** Update mobile app config with production URL
2. **SECOND:** Update desktop app config with production URL  
3. **THIRD:** Configure production .env file
4. **FOURTH:** Run migration and optimization commands
5. **FIFTH:** Start Reverb and Queue services
6. **SIXTH:** Test mobile app connection
7. **SEVENTH:** Test desktop app connection
8. **EIGHTH:** Verify notifications work
9. **NINTH:** Build and deploy mobile APK
10. **TENTH:** Build and deploy desktop app

---

**Good luck with your deployment! 🚀**


