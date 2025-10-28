# Dreamy School Management System - Installation Guide

## Introduction

The Dreamy School Management System is a comprehensive web application built with Laravel 11 that provides complete school management functionality including student enrollment, academic management, financial tracking, and administrative tools.

### What This System Does

- **Student Enrollment Management**: Complete application and enrollment process
- **Academic Management**: Academic terms, programs, tracks, sections, and subjects
- **Financial Management**: School fees, payment plans, invoices, and payment tracking
- **Document Management**: Required documents and submission tracking
- **User Management**: Role-based access control for administrators, teachers, and students
- **Real-time Notifications**: Live updates using Laravel Reverb
- **News & Announcements**: Public news management system

### Technology Stack

- **Backend**: Laravel 11 (PHP 8.2+)
- **Frontend**: Blade templates with Tailwind CSS
- **JavaScript**: Vanilla JS with jQuery, Chart.js, DataTables
- **Database**: SQLite (default) or MySQL/PostgreSQL
- **Real-time**: Laravel Reverb (WebSocket)
- **Build Tools**: Vite, NPM
- **Additional**: Laravel Sanctum, Spatie Permissions, DomPDF, Excel export

## System Requirements

### Required Software

- **PHP**: ^8.2 (with extensions: BCMath, Ctype, cURL, DOM, Fileinfo, JSON, Mbstring, OpenSSL, PCRE, PDO, Tokenizer, XML)
- **Composer**: Latest version
- **Node.js**: Latest LTS version (18.x or higher)
- **NPM**: Comes with Node.js
- **Git**: For cloning the repository

### Database Options

- **SQLite**: Default, no additional setup required
- **MySQL**: 5.7+ or 8.0+
- **PostgreSQL**: 10.0+

### Web Server

- **Development**: PHP built-in server (included)
- **Production**: Apache 2.4+ or Nginx 1.18+

## Installation Steps

### Step 1: Clone the Repository

```bash
git clone https://github.com/your-username/dreamy.git
cd dreamy
```

### Step 2: Install PHP Dependencies

```bash
composer install
```

This will install all required PHP packages including:
- Laravel Framework
- Laravel Sanctum (API authentication)
- Spatie Laravel Permission (role management)
- Laravel Reverb (WebSocket server)
- DomPDF (PDF generation)
- Maatwebsite Excel (Excel export/import)

### Step 3: Install Node Dependencies

```bash
npm install
```

This installs frontend dependencies including:
- Tailwind CSS
- Vite (build tool)
- Chart.js (charts)
- DataTables (data tables)
- AOS (animations)
- jQuery

### Step 4: Environment Configuration

#### Create Environment File

```bash
# Copy the example environment file
cp .env.example .env
```

If `.env.example` doesn't exist, create a new `.env` file with the following content:

```env
APP_NAME="Dreamy School Management"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_TIMEZONE=UTC
APP_URL=http://localhost:8000

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file
APP_MAINTENANCE_STORE=database

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=reverb
REVERB_APP_ID=local
REVERB_APP_KEY=local
REVERB_APP_SECRET=local
REVERB_HOST="localhost"
REVERB_PORT=8080
REVERB_SCHEME=http

VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"

CACHE_STORE=database
CACHE_PREFIX=

FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

MAIL_MAILER=log
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="${APP_NAME}"
```

#### Generate Application Key

```bash
php artisan key:generate
```

#### Configure Database

**For SQLite (Default - Recommended for Development):**
```bash
# Create SQLite database file
touch database/database.sqlite
```

**For MySQL:**
Update your `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dreamy_school
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

**For PostgreSQL:**
Update your `.env` file:
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=dreamy_school
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

#### Configure Mail Settings

For email verification and password reset functionality:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="Dreamy School Management"
```

### Step 5: Database Setup

#### Run Migrations

```bash
php artisan migrate
```

This will create all necessary database tables including:
- Users and authentication tables
- Academic terms and enrollment periods
- Student and teacher management
- Curriculum (tracks, programs, sections, subjects)
- Financial management (fees, invoices, payments)
- Document management
- News and announcements
- Activity logging

#### Seed Database (Optional)

```bash
php artisan db:seed
```

This will populate the database with initial data including:
- Default roles and permissions
- Sample academic terms
- Basic school settings

### Step 6: Storage Setup

#### Create Storage Link

```bash
php artisan storage:link
```

This creates a symbolic link from `public/storage` to `storage/app/public` for file uploads.

#### Set Permissions (Linux/Mac)

```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### Step 7: Build Assets

#### For Development

```bash
npm run dev
```

This starts Vite in development mode with hot reloading.

#### For Production

```bash
npm run build
```

This compiles and minifies all assets for production.

### Step 8: Start Development Server

#### Option 1: Using Composer Script (Recommended)

```bash
composer dev
```

This single command starts:
- Laravel development server (port 8000)
- Queue worker for background jobs
- Vite development server for assets
- Laravel Reverb WebSocket server (port 8080)

#### Option 2: Manual Setup

Start each service in separate terminals:

```bash
# Terminal 1: Laravel server
php artisan serve

# Terminal 2: Queue worker
php artisan queue:listen

# Terminal 3: Asset compilation
npm run dev

# Terminal 4: WebSocket server
php artisan reverb:start
```

## Initial Configuration

### Access the Application

1. Open your browser and navigate to `http://localhost:8000`
2. You should see the Dreamy School homepage

### Create Super Admin User

#### Method 1: Using Tinker

```bash
php artisan tinker
```

```php
$user = \App\Models\User::create([
    'first_name' => 'Super',
    'last_name' => 'Admin',
    'email' => 'admin@dreamy.edu',
    'password' => bcrypt('password123'),
    'email_verified_at' => now(),
]);

$user->assignRole('super_admin');
```

#### Method 2: Using Seeder

Create a seeder file:

```bash
php artisan make:seeder SuperAdminSeeder
```

Add the following to `database/seeders/SuperAdminSeeder.php`:

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::create([
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'email' => 'admin@dreamy.edu',
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
        ]);

        $user->assignRole('super_admin');
    }
}
```

Run the seeder:

```bash
php artisan db:seed --class=SuperAdminSeeder
```

### Configure School Settings

1. Login with your super admin account
2. Navigate to **Admin Portal** → **School Settings**
3. Configure:
   - School name and information
   - Contact details
   - Academic year settings
   - Down payment configuration

### Set Up Academic Terms

1. Go to **Dashboard** → **Add New Academic Term**
2. Create the current academic term (e.g., "2024-2025")
3. Set it as active

### Configure Required Documents

1. Navigate to **Documents** section
2. Add required documents for enrollment:
   - Birth Certificate
   - Report Card
   - Good Moral Character
   - Medical Certificate
   - etc.

### Set Up School Fees

1. Go to **School Fees** section
2. Configure fee structure:
   - Tuition fees by program
   - Miscellaneous fees
   - Payment plan options

## Key Features Setup

### Academic Term Management

- Create academic terms for each school year
- Set enrollment periods
- Manage semester schedules

### Document Requirements

- Define required documents for enrollment
- Set document submission deadlines
- Track document status

### School Fees Configuration

- Set tuition fees by program/track
- Configure miscellaneous fees
- Create payment plan templates

### User Roles and Permissions

The system includes these default roles:
- **super_admin**: Full system access
- **registrar**: Enrollment and student management
- **head_teacher**: Teacher management and curriculum
- **teacher**: Class and student management
- **applicant**: Student application access

### Real-time Notifications Setup

The system uses Laravel Reverb for real-time notifications:
- Admin notifications for enrollment updates
- Teacher notifications for student activities
- Student notifications for application status

## Production Deployment Notes

### Environment Configuration

1. Set `APP_ENV=production`
2. Set `APP_DEBUG=false`
3. Configure production database
4. Set up proper mail configuration
5. Configure file storage (AWS S3 recommended)

### Asset Optimization

```bash
npm run build
```

### Database Optimization

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

### Queue Worker Setup

For production, set up a proper queue worker:

```bash
# Using Supervisor (recommended)
php artisan queue:work --daemon

# Or using systemd service
```

### Reverb Server Configuration

Configure Reverb for production:

```env
REVERB_APP_ID=your-app-id
REVERB_APP_KEY=your-app-key
REVERB_APP_SECRET=your-app-secret
REVERB_HOST=your-domain.com
REVERB_PORT=443
REVERB_SCHEME=https
```

### Security Considerations

1. Use HTTPS in production
2. Set secure session configuration
3. Configure proper file permissions
4. Use environment variables for sensitive data
5. Enable CSRF protection
6. Set up proper CORS policies

## Troubleshooting

### Common Issues and Solutions

#### Permission Errors

**Problem**: "Permission denied" errors on storage or cache directories

**Solution**:
```bash
# Linux/Mac
sudo chown -R $USER:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Windows
# Run terminal as administrator and ensure proper permissions
```

#### Database Connection Issues

**Problem**: "SQLSTATE[HY000] [2002] Connection refused"

**Solutions**:
- Check database credentials in `.env`
- Ensure database server is running
- Verify database exists
- Check firewall settings

#### Asset Compilation Errors

**Problem**: Vite build fails or assets not loading

**Solutions**:
```bash
# Clear node modules and reinstall
rm -rf node_modules package-lock.json
npm install

# Clear Laravel caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

#### Queue Not Processing

**Problem**: Background jobs not executing

**Solutions**:
```bash
# Check queue configuration
php artisan queue:work --verbose

# Restart queue worker
php artisan queue:restart
```

#### Reverb/WebSocket Issues

**Problem**: Real-time notifications not working

**Solutions**:
```bash
# Check Reverb configuration
php artisan reverb:start --verbose

# Verify WebSocket connection
# Check browser console for connection errors
```

#### Email Not Sending

**Problem**: Email verification/password reset emails not sent

**Solutions**:
- Check mail configuration in `.env`
- Verify SMTP credentials
- Check mail logs: `storage/logs/laravel.log`
- Test with mail log driver: `MAIL_MAILER=log`

### Getting Help

If you encounter issues not covered in this guide:

1. Check the Laravel documentation: https://laravel.com/docs
2. Review the application logs: `storage/logs/laravel.log`
3. Check the browser console for JavaScript errors
4. Verify all environment variables are set correctly
5. Ensure all dependencies are installed properly

## Additional Resources

### Laravel Documentation
- [Laravel 11.x Documentation](https://laravel.com/docs/11.x)
- [Laravel Reverb Documentation](https://laravel.com/docs/11.x/reverb)
- [Laravel Sanctum Documentation](https://laravel.com/docs/11.x/sanctum)

### Project-Specific Documentation
- `DEPLOYMENT_CHECKLIST.md` - Production deployment checklist
- `DEPLOYMENT_QUICK_START.md` - Quick deployment guide
- `PAYMENT_PLAN_SYSTEM.md` - Payment plan system documentation
- `PERFORMANCE_OPTIMIZATION_SUMMARY.md` - Performance optimization tips

### Support Information

For technical support or questions about this installation guide, please:
1. Check the troubleshooting section above
2. Review the Laravel documentation
3. Check the project's GitHub issues page
4. Contact the development team

---

**Note**: This installation guide assumes you have basic knowledge of PHP, Laravel, and web development. If you're new to Laravel, we recommend going through the [Laravel Bootcamp](https://bootcamp.laravel.com) first.

**Last Updated**: January 2025
**Version**: 1.0.0
