# Reverb WebSocket Deployment Fix Guide

## 🔴 Problems Identified

### 1. **Port Mismatch** - Critical
- **Environment**: REVERB_PORT=433
- **Nginx**: proxy_pass http://127.0.0.1:8080
- **Supervisor**: --port=433
- **Reverb Config**: REVERB_SERVER_PORT defaults to 8080

**The Nginx proxy is pointing to port 8080, but Supervisor is starting Reverb on port 433!**

### 2. **Wrong Port Number**
- Using port **433** instead of **443**
- Port 433 is not a standard HTTPS port

### 3. **Missing Environment Variables**
- Need to set REVERB_SERVER_PORT and REVERB_SERVER_HOST

---

## ✅ Solution

### Option A: Use Standard HTTPS Port (Recommended)

#### 1. Update `.env` file on server:
```bash
# Reverb Environment Variables
BROADCAST_CONNECTION=reverb

REVERB_APP_ID=708773
REVERB_APP_KEY=ysobotsfpebn3bo0sqzl
REVERB_APP_SECRET=my-secret-key

# Client-facing configuration (what browsers connect to)
REVERB_HOST=dreamyschoolph.site
REVERB_PORT=443
REVERB_SCHEME=https

# Reverb server configuration (internal server settings)
REVERB_SERVER_HOST=127.0.0.1
REVERB_SERVER_PORT=8080
```

#### 2. Update `supervisor.conf`:
```ini
[program:laravel-reverb]
command=php /var/www/laravel/artisan reverb:start --host=127.0.0.1
autostart=true
autorestart=true
user=www-data
redirect_stderr=true
stdout_logfile=/var/www/laravel/storage/logs/reverb.log
```

**Remove the `--port=433` flag!** Let Reverb use the default from config.

#### 3. Update Nginx configuration:
```nginx
server {
    server_name dreamyschoolph.site www.dreamyschoolph.site;
    root /var/www/laravel/public;
    index index.php index.html;
    
    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    charset utf-8;
    
    # Main application
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    # WebSocket proxy for Reverb
    location /app/ {
        proxy_pass http://127.0.0.1:8080;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "Upgrade";
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        proxy_read_timeout 60s;
        proxy_send_timeout 60s;
        proxy_connect_timeout 60s;
    }
    
    # PHP handler
    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
    
    location ~ /\.ht {
        deny all;
    }
    
    error_log /var/log/nginx/laravel_error.log;
    access_log /var/log/nginx/laravel_access.log;
    
    listen 443 ssl http2; # managed by Certbot
    ssl_certificate /etc/letsencrypt/live/dreamyschoolph.site/fullchain.pem; # managed by Certbot
    ssl_certificate_key /etc/letsencrypt/live/dreamyschoolph.site/privkey.pem; # managed by Certbot
    include /etc/letsencrypt/options-ssl-nginx.conf; # managed by Certbot
    ssl_dhparam /etc/letsencrypt/ssl-dhparams.pem; # managed by Certbot
}

server {
    if ($host = www.dreamyschoolph.site) {
        return 301 https://$host$request_uri;
    } # managed by Certbot
    if ($host = dreamyschoolph.site) {
        return 301 https://$host$request_uri;
    } # managed by Certbot
    listen 80;
    server_name dreamyschoolph.site www.dreamyschoolph.site;
    return 404; # managed by Certbot
}
```

#### 4. Reload configurations:
```bash
# Clear Laravel config cache
sudo php artisan config:clear
sudo php artisan config:cache

# Reload Nginx
sudo nginx -t  # Test configuration first
sudo systemctl reload nginx

# Restart Reverb via Supervisor
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl restart laravel-reverb

# Check Reverb status
sudo supervisorctl status laravel-reverb
tail -f /var/www/laravel/storage/logs/reverb.log
```

### Option B: Use Custom Port 8080 (Alternative)

If you want to keep using 8080:

#### 1. Update `.env`:
```bash
REVERB_PORT=8080
REVERB_SCHEME=https
REVERB_SERVER_PORT=8080
```

#### 2. Update Nginx:
```nginx
location /app/ {
    proxy_pass http://127.0.0.1:8080;
    # ... rest of config
}
```

#### 3. Update Supervisor:
```ini
command=php /var/www/laravel/artisan reverb:start
# No --port flag needed, uses REVERB_SERVER_PORT from .env
```

---

## 🔍 Verification Steps

### 1. Check if Reverb is running:
```bash
# Check Supervisor status
sudo supervisorctl status laravel-reverb

# Check if port 8080 is listening
sudo netstat -tlnp | grep 8080
# or
sudo ss -tlnp | grep 8080

# Check Reverb logs
tail -f /var/www/laravel/storage/logs/reverb.log
```

Expected output:
```
laravel-reverb                    RUNNING   pid 12345, uptime 0:05:00
tcp    0.0.0.0:8080    0.0.0.0:*    LISTEN    12345/php
```

### 2. Test WebSocket connection:
Open browser console and check:
```javascript
// Should show connection established
Echo.connector.pusher.connection.bind('connected', () => {
    console.log('✅ WebSocket connected!');
});

Echo.connector.pusher.connection.bind('error', (error) => {
    console.error('❌ WebSocket error:', error);
});
```

### 3. Check Nginx logs:
```bash
tail -f /var/log/nginx/laravel_error.log
tail -f /var/log/nginx/laravel_access.log | grep /app/
```

### 4. Test from command line:
```bash
# Test WebSocket endpoint
curl -i -N \
  -H "Connection: Upgrade" \
  -H "Upgrade: websocket" \
  -H "Sec-WebSocket-Version: 13" \
  -H "Sec-WebSocket-Key: test" \
  https://dreamyschoolph.site/app/
```

---

## 🛠️ Additional Configuration

### Ensure CORS is configured correctly:

**config/cors.php** (Already correct):
```php
return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['*'], // Consider restricting to your domain in production
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];
```

### Verify environment variables are loaded:

Run on server:
```bash
sudo -u www-data php artisan tinker
```

Then in tinker:
```php
config('broadcasting.default')
config('broadcasting.connections.reverb')
config('reverb.servers.reverb')
```

Should show your configured values.

---

## 📋 Quick Reference

### Port Numbers Explained:

| Configuration | Value | Purpose |
|--------------|-------|---------|
| REVERB_PORT | 443 or 8080 | Port clients (browsers) connect to through Nginx |
| REVERB_SERVER_PORT | 8080 | Internal port Reverb server listens on |
| Nginx proxy_pass | 127.0.0.1:8080 | Nginx forwards /app/ to this internal port |

**Flow**: Browser → HTTPS:443 → Nginx → HTTP:8080 → Reverb Server

---

## 🚨 Common Issues & Solutions

### Issue 1: Port Already in Use
```bash
# Find what's using port 8080
sudo lsof -i :8080
sudo netstat -tlnp | grep 8080

# Kill the process if needed
sudo kill -9 <PID>

# Restart Reverb
sudo supervisorctl restart laravel-reverb
```

### Issue 2: Permission Denied
```bash
# Check logs directory permissions
sudo chown -R www-data:www-data /var/www/laravel/storage/logs
sudo chmod -R 775 /var/www/laravel/storage/logs

# Check Supervisor user
sudo nano /etc/supervisor/conf.d/laravel-reverb.conf
# Ensure: user=www-data
```

### Issue 3: Configuration Not Updating
```bash
# Clear all caches
sudo php artisan config:clear
sudo php artisan cache:clear
sudo php artisan view:clear

# Rebuild cache
sudo php artisan config:cache

# Restart services
sudo supervisorctl restart laravel-reverb
sudo systemctl reload nginx
```

### Issue 4: SSL Certificate Issues
```bash
# Verify SSL certificate
sudo certbot certificates

# Renew if needed
sudo certbot renew

# Test SSL configuration
sudo nginx -t
```

### Issue 5: Firewall Blocking Port
```bash
# Check firewall status
sudo ufw status

# Allow ports if needed
sudo ufw allow 443/tcp
sudo ufw allow 8080/tcp

# Reload firewall
sudo ufw reload
```

---

## 📝 Final Checklist

Before testing, ensure:

- [ ] `.env` has correct REVERB_* variables
- [ ] Nginx `location /app/` proxies to `127.0.0.1:8080`
- [ ] Supervisor config doesn't specify conflicting port
- [ ] Reverb is running on port 8080 internally
- [ ] Nginx is reloaded and tested
- [ ] Supervisor has restarted Reverb
- [ ] Config cache is cleared and rebuilt
- [ ] Firewall allows port 8080
- [ ] SSL certificate is valid
- [ ] Logs show no errors

---

## 🧪 Testing

### Test 1: Connection Test
Open browser console on your site:
```javascript
console.log('Echo connection:', window.Echo);
console.log('Connection state:', Echo.connector.pusher.connection.state);
```

### Test 2: Channel Subscription
```javascript
Echo.channel('test-channel')
    .listen('.test-event', (e) => {
        console.log('Test event received:', e);
    })
    .subscribed(() => {
        console.log('✅ Subscribed to test-channel');
    })
    .error((error) => {
        console.error('❌ Subscription error:', error);
    });
```

### Test 3: Send Test Notification
Create a test route:
```php
// routes/web.php
Route::get('/test-broadcast', function () {
    event(new \App\Events\TestBroadcast());
    return 'Broadcast sent!';
});

// app/Events/TestBroadcast.php
<?php
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class TestBroadcast implements ShouldBroadcastNow
{
    public function broadcastOn()
    {
        return new Channel('test-channel');
    }
    
    public function broadcastWith()
    {
        return ['message' => 'Test message'];
    }
}
```

Visit `/test-broadcast` and check console.

---

## 📞 Need Help?

Check these locations for debugging:

1. **Reverb Logs**: `/var/www/laravel/storage/logs/reverb.log`
2. **Laravel Logs**: `/var/www/laravel/storage/logs/laravel.log`
3. **Nginx Error Log**: `/var/log/nginx/laravel_error.log`
4. **Supervisor Status**: `sudo supervisorctl status`
5. **Browser Console**: Network tab → WS connections

---

**Last Updated**: 2025-01-XX  
**Recommendation**: Use **Option A** (standard 443 port setup)

