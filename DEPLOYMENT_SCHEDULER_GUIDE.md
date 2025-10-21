# Laravel Scheduler Deployment Guide

## Production Setup

### 1. Add Cron Job to Server
Add this single cron entry to your server's crontab:

```bash
* * * * * cd /path/to/your/dreamy/project && php artisan schedule:run >> /dev/null 2>&1
```

### 2. How to Add Cron Job

#### Option A: Using crontab command
```bash
# Edit crontab
crontab -e

# Add this line (replace /path/to/your/dreamy/project with actual path)
* * * * * cd /path/to/your/dreamy/project && php artisan schedule:run >> /dev/null 2>&1
```

#### Option B: Using cPanel (if using shared hosting)
1. Go to cPanel → Cron Jobs
2. Add new cron job:
   - **Minute**: *
   - **Hour**: *
   - **Day**: *
   - **Month**: *
   - **Weekday**: *
   - **Command**: `cd /path/to/your/dreamy/project && php artisan schedule:run >> /dev/null 2>&1`

### 3. Verify Setup

#### Check if cron is working:
```bash
# Check cron logs
tail -f /var/log/cron

# Or check Laravel logs
tail -f /path/to/your/dreamy/project/storage/logs/laravel.log
```

#### Test scheduler manually:
```bash
cd /path/to/your/dreamy/project
php artisan schedule:list
php artisan schedule:run
```

### 4. Scheduled Commands

Your Laravel app will automatically run these commands daily at midnight:

1. **`app:send-monthly-reminder`** - Sends monthly reminders
2. **`app:update-overdue-schedules`** - Updates overdue payment schedules  
3. **`invoices:send-reminders`** - Sends invoice reminders (5-day, due, overdue)

### 5. Troubleshooting

#### If scheduler isn't running:
1. Check if cron service is running: `systemctl status cron`
2. Check cron logs: `tail -f /var/log/cron`
3. Verify file permissions: `chmod +x /path/to/your/dreamy/project/artisan`
4. Test manually: `php artisan schedule:run`

#### If emails aren't sending:
1. Check mail configuration in `.env`
2. Check queue worker: `php artisan queue:work`
3. Check Laravel logs: `tail -f storage/logs/laravel.log`

### 6. Alternative: Queue Worker (Recommended)

For better reliability, also run a queue worker:

```bash
# Run this command and keep it running
php artisan queue:work --daemon
```

Or use a process manager like Supervisor to keep it running automatically.

## Important Notes

- **Only ONE cron entry needed** - Laravel handles all scheduling internally
- **Replace `/path/to/your/dreamy/project`** with your actual project path
- **Test on staging first** before deploying to production
- **Monitor logs** to ensure everything is working
