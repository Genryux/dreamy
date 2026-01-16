# DREAMY SCHOOL MANAGEMENT SYSTEM
# TECHNICAL MANUAL

**Version:** 1.0.0  
**Last Updated:** January 2025  
**Document Type:** Technical Manual for Developers, IT Staff, and System Administrators

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [System Overview](#2-system-overview)
3. [Hardware Requirements](#3-hardware-requirements)
4. [Software Requirements](#4-software-requirements)
5. [System Architecture](#5-system-architecture)
6. [Installation Guide](#6-installation-guide)
7. [Configuration Guide](#7-configuration-guide)
8. [Database Schema](#8-database-schema)
9. [API Documentation](#9-api-documentation)
10. [Mobile Application Setup](#10-mobile-application-setup)
11. [Maintenance and Troubleshooting](#11-maintenance-and-troubleshooting)
12. [Security Considerations](#12-security-considerations)
13. [Backup and Recovery Procedures](#13-backup-and-recovery-procedures)

---

## 1. Introduction

### 1.1 System Name
**Dreamy School Management System** - A comprehensive, multi-platform school management solution.

### 1.2 Purpose of the System
The Dreamy School Management System is designed to streamline and automate school administrative operations including:
- Student enrollment and admission management
- Academic program and curriculum management
- Financial management (fees, invoices, payment plans)
- Document tracking and verification
- User and role management
- Real-time notifications
- News and announcement publishing

### 1.3 Intended Technical Users
This manual is intended for:
- **System Administrators**: Responsible for server setup, configuration, and maintenance
- **IT Staff**: Responsible for day-to-day technical operations and user support
- **Developers**: Responsible for customization, extensions, and bug fixes
- **DevOps Engineers**: Responsible for deployment pipelines and infrastructure

### 1.4 Document Scope
This technical manual covers:
- Complete installation procedures for both backend (Laravel) and mobile app (React Native/Expo)
- System configuration and environment setup
- Database structure and relationships
- API endpoints and integration points
- Troubleshooting common issues
- Backup and recovery procedures

---

## 2. System Overview

### 2.1 Description of the System
Dreamy School Management System is a full-featured school management platform built on modern web technologies. It consists of three main components:

1. **Web Application (Backend + Admin Portal)**: Laravel 11-based server application
2. **Web Application (Student Portal)**: Browser-based interface for students/applicants
3. **Mobile Application**: React Native/Expo app for students and teachers

### 2.2 Main Features

#### Core Features
| Feature Category | Description |
|-----------------|-------------|
| **Student Enrollment** | Complete application and enrollment workflow from admission to graduation |
| **Academic Management** | Academic terms, programs, tracks, sections, subjects, and scheduling |
| **Financial Management** | School fees, invoices, payment plans, installments, discounts |
| **Document Management** | Required documents tracking, submission, and verification |
| **User Management** | Role-based access control with granular permissions |
| **Real-time Notifications** | Live notifications via WebSocket (Laravel Reverb) |
| **News & Announcements** | Public news management and announcement system |
| **PIN Security** | Two-factor authentication via 6-digit PIN |
| **Teacher Management** | Teacher profiles, class assignments, student evaluation |
| **Report Generation** | PDF invoices, receipts, certificates of enrollment |

### 2.3 System Architecture

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                           DREAMY SCHOOL MANAGEMENT SYSTEM                    │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  ┌──────────────┐    ┌──────────────┐    ┌──────────────┐                   │
│  │   Desktop    │    │     Web      │    │   Mobile     │                   │
│  │   App        │    │   Browser    │    │   App        │                   │
│  │  (Electron)  │    │   Portal     │    │ (React Native)│                  │
│  └──────┬───────┘    └──────┬───────┘    └──────┬───────┘                   │
│         │                   │                   │                           │
│         └───────────────────┼───────────────────┘                           │
│                             │                                               │
│                      ┌──────▼───────┐                                       │
│                      │   HTTPS/WSS  │                                       │
│                      │   Gateway    │                                       │
│                      └──────┬───────┘                                       │
│                             │                                               │
│  ┌──────────────────────────┼──────────────────────────┐                   │
│  │                BACKEND SERVER (Laravel 11)           │                   │
│  │  ┌────────────────────────────────────────────────┐ │                   │
│  │  │              Application Layer                  │ │                   │
│  │  │  ┌──────────┐  ┌──────────┐  ┌──────────────┐  │ │                   │
│  │  │  │Controllers│  │ Services │  │  Middleware  │  │ │                   │
│  │  │  └──────────┘  └──────────┘  └──────────────┘  │ │                   │
│  │  └────────────────────────────────────────────────┘ │                   │
│  │  ┌────────────────────────────────────────────────┐ │                   │
│  │  │               Core Services                     │ │                   │
│  │  │  ┌────────┐ ┌────────┐ ┌────────┐ ┌──────────┐ │ │                   │
│  │  │  │Sanctum │ │ Reverb │ │DomPDF  │ │  Excel   │ │ │                   │
│  │  │  │  Auth  │ │WebSocket│ │  PDF   │ │Export/Imp│ │ │                   │
│  │  │  └────────┘ └────────┘ └────────┘ └──────────┘ │ │                   │
│  │  └────────────────────────────────────────────────┘ │                   │
│  │  ┌────────────────────────────────────────────────┐ │                   │
│  │  │            Data Layer (Eloquent ORM)            │ │                   │
│  │  │  ┌──────────┐  ┌──────────┐  ┌──────────────┐  │ │                   │
│  │  │  │  Models  │  │  Seeders │  │  Migrations  │  │ │                   │
│  │  │  └──────────┘  └──────────┘  └──────────────┘  │ │                   │
│  │  └────────────────────────────────────────────────┘ │                   │
│  └──────────────────────────┬──────────────────────────┘                   │
│                             │                                               │
│                      ┌──────▼───────┐                                       │
│                      │   Database   │                                       │
│                      │SQLite/MySQL  │                                       │
│                      └──────────────┘                                       │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 2.4 Platform-Based Access Restrictions

| Platform | Primary Users | Allowed Operations |
|----------|--------------|-------------------|
| **Desktop App** | Admin, Registrar | Full administrative operations, student management, financial management |
| **Web Application** | Applicants, Students | Admission forms, document submission, application status, student portal |
| **Mobile App (Student)** | Students | Dashboard, academic info, financial info, notifications, profile |
| **Mobile App (Teacher)** | Teachers | Class schedule, student list, student evaluation |

---

## 3. Hardware Requirements

### 3.1 Development Environment

| Component | Minimum | Recommended |
|-----------|---------|-------------|
| **Processor** | Intel Core i5 / AMD Ryzen 5 | Intel Core i7 / AMD Ryzen 7 |
| **RAM** | 8 GB | 16 GB |
| **Storage** | 50 GB SSD | 100 GB SSD |
| **Display** | 1366 x 768 | 1920 x 1080 |
| **Network** | Stable internet connection | Gigabit Ethernet / Fast WiFi |

### 3.2 Production Server

| Component | Minimum | Recommended |
|-----------|---------|-------------|
| **Processor** | 2 vCPU | 4 vCPU |
| **RAM** | 4 GB | 8 GB |
| **Storage** | 50 GB SSD | 100 GB SSD (with backup storage) |
| **Network** | 100 Mbps | 1 Gbps |
| **SSL Certificate** | Required | Required (Let's Encrypt or Commercial) |

### 3.3 Mobile Development

| Component | Minimum | Recommended |
|-----------|---------|-------------|
| **Android Device/Emulator** | Android 6.0+ (API 23) | Android 10+ (API 29) |
| **iOS Device/Simulator** | iOS 13.4+ | iOS 15+ |
| **Development Machine** | 8 GB RAM | 16 GB RAM (macOS for iOS) |

---

## 4. Software Requirements

### 4.1 Backend (Laravel Application)

| Software | Version | Purpose |
|----------|---------|---------|
| **PHP** | ^8.2 | Server-side scripting |
| **Composer** | Latest | PHP dependency management |
| **Node.js** | 18.x LTS or higher | Asset compilation |
| **NPM** | Comes with Node.js | JavaScript package management |
| **Git** | Latest | Version control |

#### Required PHP Extensions
- BCMath
- Ctype
- cURL
- DOM
- Fileinfo
- JSON
- Mbstring
- OpenSSL
- PCRE
- PDO
- Tokenizer
- XML
- SQLite3 (for development)
- MySQLi (for production)

### 4.2 Database Options

| Database | Version | Recommended For |
|----------|---------|-----------------|
| **SQLite** | 3.x | Development, testing |
| **MySQL** | 5.7+ or 8.0+ | Production |
| **PostgreSQL** | 10.0+ | Production (alternative) |

### 4.3 Web Server

| Server | Version | Environment |
|--------|---------|-------------|
| **PHP Built-in Server** | - | Development only |
| **Laravel Herd** | Latest | Development (Windows/Mac) |
| **Apache** | 2.4+ | Production |
| **Nginx** | 1.18+ | Production (recommended) |

### 4.4 Backend Dependencies (via Composer)

```json
{
    "php": "^8.2",
    "laravel/framework": "^11.31",
    "laravel/reverb": "^1.0",
    "laravel/sanctum": "^4.2",
    "laravel/tinker": "^2.9",
    "barryvdh/laravel-dompdf": "^3.1",
    "maatwebsite/excel": "^3.1",
    "spatie/laravel-activitylog": "^4.10",
    "spatie/laravel-permission": "^6.16"
}
```

### 4.5 Frontend Dependencies (via NPM)

- **Tailwind CSS** - Utility-first CSS framework
- **Vite** - Build tool and dev server
- **Chart.js** - Charts and graphs
- **DataTables** - Interactive tables
- **AOS** - Animation library
- **jQuery** - DOM manipulation

### 4.6 Mobile Application (React Native/Expo)

| Software | Version | Purpose |
|----------|---------|---------|
| **Node.js** | 18.x LTS or higher | Runtime |
| **Expo CLI** | ~53.0.x | Development framework |
| **React Native** | 0.79.x | Mobile UI framework |
| **Expo Go** | Latest | Development testing |
| **Android Studio** | Latest | Android development/emulation |
| **Xcode** | 15+ | iOS development (macOS only) |

---

## 5. System Architecture

### 5.1 Technology Stack

```
FRONTEND                    BACKEND                     MOBILE
─────────────────────────────────────────────────────────────────
│ Blade Templates       │   Laravel 11            │ React Native  │
│ Tailwind CSS          │   PHP 8.2+              │ Expo SDK 53   │
│ Vanilla JS / jQuery   │   Eloquent ORM          │ TypeScript    │
│ Chart.js              │   Laravel Sanctum       │ React Nav 7   │
│ DataTables            │   Laravel Reverb        │ Pusher.js     │
│ Vite                  │   Spatie Permission     │ SecureStore   │
─────────────────────────────────────────────────────────────────
```

### 5.2 Directory Structure (Backend)

```
dreamy/
├── app/
│   ├── Console/           # Artisan commands
│   ├── Events/            # Event classes
│   ├── Exceptions/        # Exception handlers
│   ├── Exports/           # Excel export classes
│   ├── Http/
│   │   ├── Controllers/   # HTTP controllers
│   │   │   ├── Api/       # API controllers (mobile)
│   │   │   └── Auth/      # Authentication controllers
│   │   └── Middleware/    # HTTP middleware
│   ├── Imports/           # Excel import classes
│   ├── Jobs/              # Queue jobs
│   ├── Listeners/         # Event listeners
│   ├── Mail/              # Mailable classes
│   ├── Models/            # Eloquent models (45+)
│   ├── Notifications/     # Notification classes
│   ├── Providers/         # Service providers
│   ├── Rules/             # Validation rules
│   └── Services/          # Business logic services
├── bootstrap/             # Application bootstrap
├── config/                # Configuration files
├── database/
│   ├── factories/         # Model factories
│   ├── migrations/        # Database migrations
│   └── seeders/           # Database seeders
├── public/                # Public web root
├── resources/
│   ├── css/               # CSS stylesheets
│   ├── js/                # JavaScript files
│   └── views/             # Blade templates
│       ├── auth/          # Authentication views
│       ├── user-admin/    # Admin portal views
│       ├── user-applicant/# Applicant views
│       ├── user-teacher/  # Teacher views
│       └── ...
├── routes/
│   ├── api.php            # API routes
│   ├── channels.php       # Broadcast channels
│   ├── console.php        # Console commands
│   └── web.php            # Web routes
├── storage/               # File storage
└── tests/                 # Test suites
```

### 5.3 Directory Structure (Mobile App)

```
dreamy_app/
├── app/
│   ├── (auth)/            # Authentication screens
│   │   ├── _layout.tsx
│   │   └── login.tsx
│   ├── (tabs)/            # Student tab screens
│   │   ├── _layout.tsx
│   │   ├── dashboard.tsx
│   │   ├── index.tsx      # Academic
│   │   ├── financial.tsx
│   │   ├── notifications.tsx
│   │   └── profile.tsx
│   ├── (teacher)/         # Teacher tab screens
│   │   ├── _layout.tsx
│   │   ├── dashboard.tsx
│   │   ├── profile.tsx
│   │   └── class/[id]/    # Dynamic class routes
│   ├── auth/              # PIN screens
│   │   ├── pin-setup.tsx
│   │   └── pin-verification.tsx
│   └── profile/           # Profile sub-screens
│       ├── personal-info.tsx
│       └── account-settings.tsx
├── components/            # Reusable components
├── config/                # App configuration
│   └── api.ts             # API environment config
├── contexts/              # React contexts
├── hooks/                 # Custom React hooks
├── services/              # API service classes
│   ├── api.ts             # Student API service
│   └── teacherApi.ts      # Teacher API service
└── constants/             # App constants
```

### 5.4 User Roles and Permissions

| Role | Description | Key Permissions |
|------|-------------|-----------------|
| **super_admin** | Full system access | All permissions |
| **registrar** | Enrollment management | Applications, students, documents, fees |
| **head_teacher** | Department management | Teacher supervision, curriculum |
| **teacher** | Classroom management | Section views, student evaluation |
| **applicant** | Prospective student | Application form, document submission |
| **student** | Enrolled student | View academic/financial info, mobile access |

---

## 6. Installation Guide

### 6.1 Backend Installation

#### Step 1: Clone the Repository
```bash
git clone <repository-url>
cd dreamy
```

#### Step 2: Install PHP Dependencies
```bash
composer install
```

#### Step 3: Install Node.js Dependencies
```bash
npm install
```

#### Step 4: Environment Configuration
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

#### Step 5: Configure Environment Variables
Edit `.env` file with appropriate values:

```env
APP_NAME="Dreamy School Management"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database Configuration
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

# For MySQL (Production)
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=dreamy_school
# DB_USERNAME=your_username
# DB_PASSWORD=your_password

# WebSocket Configuration
BROADCAST_CONNECTION=reverb
REVERB_APP_ID=local
REVERB_APP_KEY=local
REVERB_APP_SECRET=local
REVERB_HOST="localhost"
REVERB_PORT=8080
REVERB_SCHEME=http

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
```

#### Step 6: Database Setup
```bash
# Create SQLite database (development)
touch database/database.sqlite

# Run migrations
php artisan migrate

# Seed database with initial data
php artisan db:seed

# Create storage link
php artisan storage:link
```

#### Step 7: Build Frontend Assets
```bash
# Development
npm run dev

# Production
npm run build
```

#### Step 8: Start Development Server
```bash
# Using Composer script (recommended - starts all services)
composer dev

# Or manually in separate terminals:
# Terminal 1: Laravel server
php artisan serve

# Terminal 2: Queue worker
php artisan queue:listen

# Terminal 3: Vite dev server
npm run dev

# Terminal 4: WebSocket server
php artisan reverb:start
```

### 6.2 Mobile Application Installation

#### Step 1: Navigate to Mobile App Directory
```bash
cd dreamy_app
```

#### Step 2: Install Dependencies
```bash
npm install
```

#### Step 3: Configure API Endpoint
Edit `config/api.ts`:

```typescript
export const API_CONFIG = {
  HOME: {
    BASE_URL: 'http://YOUR_LOCAL_IP:8000',
    REVERB_HOST: 'YOUR_LOCAL_IP',
    REVERB_PORT: 8080,
    REVERB_SCHEME: 'ws',
    name: 'Development'
  },
  PRODUCTION: {
    BASE_URL: 'https://your-domain.com',
    REVERB_HOST: 'your-domain.com',
    REVERB_PORT: 443,
    REVERB_SCHEME: 'wss',
    name: 'Production'
  }
};

// Set current environment
export const CURRENT_ENV: keyof typeof API_CONFIG = 'HOME';
```

#### Step 4: Start Expo Development Server
```bash
npx expo start
```

#### Step 5: Run on Device/Emulator
- **Android**: Press `a` to open Android emulator
- **iOS**: Press `i` to open iOS simulator (macOS only)
- **Physical Device**: Scan QR code with Expo Go app

---

## 7. Configuration Guide

### 7.1 Default User Accounts (After Seeding)

| Role | Email | Default Password |
|------|-------|-----------------|
| Super Admin | admin@dreamy.edu | password123 |
| Registrar | registrar@dreamy.edu | password123 |

> **Important:** Change default passwords immediately after installation!

### 7.2 School Settings Configuration
After logging in as admin:
1. Navigate to **Admin Portal** → **School Settings**
2. Configure:
   - School name and logo
   - Contact information
   - Down payment percentage
   - Payment plan options

### 7.3 Academic Term Setup
1. Go to **Dashboard** → **Add New Academic Term**
2. Configure:
   - School year (e.g., "2024-2025")
   - Semester (First/Second)
   - Start and end dates
   - Set as active term

### 7.4 Enrollment Period Setup
1. Navigate to enrollment period settings
2. Configure:
   - Period name
   - Application start/end dates
   - Maximum applicants
   - Early discount percentage (optional)

### 7.5 Required Documents Configuration
1. Go to **Documents** section
2. Add required documents:
   - Birth Certificate
   - Report Card
   - Good Moral Character
   - Medical Certificate
   - ID Photos

### 7.6 School Fees Setup
1. Navigate to **School Fees**
2. Configure fees by:
   - Program/strand
   - Grade level
   - Fee type (tuition, miscellaneous, etc.)
   - Amount

---

## 8. Database Schema

### 8.1 Entity Overview

The system contains **45+ database entities** organized into the following groups:

#### User Management
- `users` - Core user accounts
- `personal_access_tokens` - API tokens (Sanctum)
- `sessions` - User sessions
- `password_reset_tokens` - Password recovery

#### Roles & Permissions (Spatie)
- `roles` - User roles
- `permissions` - Granular permissions
- `permission_categories` - Permission groupings
- `model_has_roles` - Role assignments
- `model_has_permissions` - Direct permission assignments
- `role_has_permissions` - Role-permission mappings

#### Academic Structure
- `academic_terms` - School years/semesters
- `enrollment_periods` - Admission periods
- `tracks` - Academic tracks (e.g., Academic, TVL)
- `programs` - Strands/programs
- `sections` - Class sections
- `subjects` - Course subjects
- `section_subjects` - Subject-section assignments

#### User Types
- `applicants` - Prospective students
- `students` - Enrolled students
- `student_records` - Student personal details
- `teachers` - Faculty members

#### Enrollment Management
- `application_forms` - Admission applications
- `interviews` - Interview scheduling
- `student_enrollments` - Per-term enrollments
- `student_subjects` - Subject enrollments

#### Document Management
- `documents` - Document types
- `applicant_documents` - Required documents per applicant
- `student_documents` - Required documents per student
- `document_submissions` - Submitted files

#### Financial System
- `school_fees` - Fee definitions
- `invoices` - Student invoices
- `invoice_items` - Invoice line items
- `invoice_payments` - Payment records
- `payment_plans` - Installment structures
- `payment_schedules` - Payment timelines
- `discounts` - Custom discounts

#### Content Management
- `news` - News articles
- `hero_section` - Homepage hero content
- `about_section` - About page content
- (Various homepage section tables)

### 8.2 Key Relationships

```
User (1) ──── (0..1) Applicant ──── (1) ApplicationForm
  │                    │
  │                    └──── (0..*) ApplicantDocuments
  │
  └──── (0..1) Student ──── (1) StudentRecord
           │
           ├──── (0..*) StudentEnrollment ──── AcademicTerm
           │
           ├──── (0..*) StudentSubject ──── SectionSubject
           │
           └──── (0..*) Invoice ──── InvoiceItem ──── SchoolFee
                          │
                          ├──── InvoicePayment
                          │
                          └──── PaymentPlan ──── PaymentSchedule

Track (1) ──── (0..*) Program (1) ──── (0..*) Section (1) ──── (0..*) SectionSubject
                         │                      │
                         └──── (0..*) Subject   └──── Teacher (advisor)
```

---

## 9. API Documentation

### 9.1 Authentication Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/auth/login` | User login |
| POST | `/api/auth/logout` | User logout |
| GET | `/api/auth/user` | Get authenticated user |
| POST | `/api/auth/setup-pin` | Setup 6-digit PIN |
| POST | `/api/auth/verify-pin` | Verify PIN |
| POST | `/api/auth/change-password` | Change password |
| POST | `/api/auth/change-email` | Change email |

### 9.2 Student API Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/dashboard` | Dashboard data |
| GET | `/api/academic/section` | Current section info |
| GET | `/api/academic/subjects` | Current subjects |
| GET | `/api/academic/summary` | Academic summary |
| GET | `/api/financial/invoices` | Student invoices |
| GET | `/api/financial/payments` | Payment history |
| GET | `/api/financial/summary` | Financial summary |
| POST | `/api/financial/payment-plan/calculate` | Calculate payment plan |
| POST | `/api/financial/invoice/{id}/payment-plan/select` | Select payment plan |
| GET | `/api/notifications` | Get notifications |
| POST | `/api/notifications/{id}/read` | Mark notification as read |
| PUT | `/api/profile/personal-info` | Update personal info |

### 9.3 Teacher API Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/teacher/dashboard` | Teacher dashboard |
| GET | `/api/teacher/my-classes` | All assigned classes |
| GET | `/api/teacher/classes/{id}/students` | Students in class |
| GET | `/api/teacher/classes/{id}/students/{studentId}` | Student details |
| POST | `/api/teacher/classes/{id}/students/{studentId}/evaluate` | Evaluate student |
| POST | `/api/teacher/classes/{id}/evaluate-bulk` | Bulk evaluation |
| GET | `/api/teacher/profile` | Teacher profile |
| PUT | `/api/teacher/profile` | Update profile |

### 9.4 API Authentication
All protected endpoints require Bearer token authentication:

```
Authorization: Bearer <token>
```

---

## 10. Mobile Application Setup

### 10.1 Environment Configuration

The mobile app supports multiple environment configurations in `config/api.ts`:

```typescript
export const API_CONFIG = {
  HOME: {
    BASE_URL: 'http://192.168.x.x:8000',
    REVERB_HOST: '192.168.x.x',
    REVERB_PORT: 8080,
    REVERB_SCHEME: 'ws',
    name: 'Home WiFi'
  },
  HERD: {
    BASE_URL: 'http://dreamy.test',
    REVERB_HOST: 'dreamy.test',
    REVERB_PORT: 8080,
    REVERB_SCHEME: 'ws',
    name: 'Laravel Herd'
  },
  PRODUCTION: {
    BASE_URL: 'https://dreamyschoolph.site',
    REVERB_HOST: 'dreamyschoolph.site',
    REVERB_PORT: 443,
    REVERB_SCHEME: 'wss',
    name: 'Production'
  }
};
```

### 10.2 Building for Production

#### Android APK
```bash
# Build development APK
npx expo run:android

# Build production APK with EAS
npx eas build --platform android --profile production
```

#### iOS Build
```bash
# Build for iOS simulator
npx expo run:ios

# Build for App Store with EAS
npx eas build --platform ios --profile production
```

### 10.3 Real-time Notifications Setup
The mobile app uses Pusher.js to connect to Laravel Reverb:

1. Ensure Reverb server is running: `php artisan reverb:start`
2. Configure correct WebSocket host in `config/api.ts`
3. For production, use WSS (port 443) with valid SSL

---

## 11. Maintenance and Troubleshooting

### 11.1 Common Errors and Solutions

#### Database Connection Issues
**Error:** `SQLSTATE[HY000] [2002] Connection refused`

**Solutions:**
- Verify database credentials in `.env`
- Ensure database server is running
- Check firewall settings
- For SQLite, ensure file exists: `touch database/database.sqlite`

#### Asset Compilation Errors
**Error:** Vite build fails or assets not loading

**Solutions:**
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
**Error:** Background jobs not executing

**Solutions:**
```bash
# Check queue status
php artisan queue:work --verbose

# Restart queue worker
php artisan queue:restart

# Clear failed jobs
php artisan queue:flush
```

#### WebSocket Connection Issues
**Error:** Real-time notifications not working

**Solutions:**
```bash
# Start Reverb with verbose output
php artisan reverb:start --verbose

# Check browser console for WebSocket errors
# Verify REVERB_HOST and REVERB_PORT in .env
```

#### Email Not Sending
**Error:** Verification emails not received

**Solutions:**
- Verify SMTP credentials in `.env`
- Test with log driver: `MAIL_MAILER=log`
- Check `storage/logs/laravel.log` for errors
- Ensure queue worker is running

#### Mobile App API Connection
**Error:** Network request failed

**Solutions:**
- Verify device is on same network as server
- Check IP address in `config/api.ts`
- Ensure server allows external connections
- For Android emulator, use `10.0.2.2` for localhost

### 11.2 Scheduled Tasks

The system uses Laravel Scheduler for automated tasks. Add this cron entry:

```bash
* * * * * cd /path/to/dreamy && php artisan schedule:run >> /dev/null 2>&1
```

**Scheduled Commands:**
- `app:send-monthly-reminder` - Monthly payment reminders
- `app:update-overdue-schedules` - Update overdue payment statuses
- `invoices:send-reminders` - Invoice reminders (5-day, due, overdue)

### 11.3 Log Files

| Log | Location | Purpose |
|-----|----------|---------|
| Laravel Log | `storage/logs/laravel.log` | Application errors |
| Queue Log | `storage/logs/laravel.log` | Queue job failures |
| Nginx Log | `/var/log/nginx/error.log` | Web server errors |

---

## 12. Security Considerations

### 12.1 Authentication Security
- Uses Laravel Sanctum for API token authentication
- 6-digit PIN for additional security layer
- PIN rate limiting (5 attempts, then 1-minute lockout)
- Secure password hashing (bcrypt with 12 rounds)

### 12.2 Production Security Checklist

- [ ] Set `APP_DEBUG=false` in production
- [ ] Use HTTPS with valid SSL certificate
- [ ] Configure secure session settings
- [ ] Use environment variables for sensitive data
- [ ] Enable CSRF protection
- [ ] Configure CORS policies properly
- [ ] Set proper file permissions (775 for storage)
- [ ] Use WSS for WebSocket in production
- [ ] Regular security updates

### 12.3 Role-Based Access Control
- Granular permissions managed via Spatie Laravel Permission
- Role-based middleware on routes
- Permission-based UI visibility
- Activity logging for audit trails

---

## 13. Backup and Recovery Procedures

### 13.1 Database Backup

#### SQLite (Development)
```bash
# Simple file copy
cp database/database.sqlite backups/database_$(date +%Y%m%d).sqlite
```

#### MySQL (Production)
```bash
# Full backup
mysqldump -u username -p dreamy_school > backup_$(date +%Y%m%d).sql

# Compressed backup
mysqldump -u username -p dreamy_school | gzip > backup_$(date +%Y%m%d).sql.gz
```

### 13.2 File Storage Backup
```bash
# Backup uploaded files
tar -czvf storage_backup_$(date +%Y%m%d).tar.gz storage/app/public
```

### 13.3 Full System Backup
```bash
#!/bin/bash
DATE=$(date +%Y%m%d)
BACKUP_DIR="/backups/$DATE"

mkdir -p $BACKUP_DIR

# Database
mysqldump -u username -p dreamy_school > $BACKUP_DIR/database.sql

# Files
tar -czvf $BACKUP_DIR/storage.tar.gz storage/app/public

# Environment (encrypted)
gpg -c .env > $BACKUP_DIR/env.gpg
```

### 13.4 Recovery Procedures

#### Database Recovery
```bash
# SQLite
cp backups/database_YYYYMMDD.sqlite database/database.sqlite

# MySQL
mysql -u username -p dreamy_school < backup.sql
```

#### Full Recovery
1. Clone fresh repository
2. Restore `.env` file
3. Import database backup
4. Restore storage files
5. Run `composer install`
6. Run `npm install && npm run build`
7. Clear caches: `php artisan optimize:clear`

---

## Appendix A: Artisan Commands Reference

| Command | Description |
|---------|-------------|
| `php artisan serve` | Start development server |
| `php artisan migrate` | Run database migrations |
| `php artisan migrate:fresh --seed` | Reset and reseed database |
| `php artisan queue:work` | Start queue worker |
| `php artisan reverb:start` | Start WebSocket server |
| `php artisan cache:clear` | Clear application cache |
| `php artisan config:clear` | Clear config cache |
| `php artisan route:clear` | Clear route cache |
| `php artisan view:clear` | Clear compiled views |
| `php artisan storage:link` | Create storage symlink |
| `php artisan tinker` | Interactive PHP shell |

---

## Appendix B: Support Information

For technical support or questions:

1. Review this technical manual
2. Check Laravel documentation: https://laravel.com/docs
3. Check Expo documentation: https://docs.expo.dev
4. Review application logs
5. Contact development team

---

**Document Version:** 1.0.0  
**Last Updated:** January 2025  
**Maintained By:** Dreamy Development Team
