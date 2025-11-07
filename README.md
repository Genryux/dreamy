# Dreamy School Management System

<div align="center">
  <h3>A Comprehensive School Management Platform</h3>
  <p>Built with Laravel 11 • Electron Desktop App • Mobile API • Real-time Notifications</p>
</div>

---

## 📋 Overview

**Dreamy School Management System** is a full-featured school management platform designed to streamline administrative operations, student enrollment, academic management, and financial tracking. The system supports multiple access platforms with role-based restrictions to ensure optimal security and user experience.

### 🎯 Key Features

- ** Student Enrollment Management** - Complete application and enrollment workflow
- ** Academic Management** - Academic terms, programs, tracks, sections, and subjects
- ** Financial Management** - School fees, payment plans, invoices, and payment tracking
- ** Document Management** - Required documents tracking
- ** User Management** - Role-based access control (Admin, Registrar, Teachers, Students)
- ** Real-time Notifications** - Live notifications using Laravel Reverb WebSocket
- ** News & Announcements** - Public news management system
- ** PIN Security** - Additional security layer with PIN verification
- ** Multi-Platform Support** - Desktop app, Web app, and Mobile API

---

## 🏗️ Architecture

The system is built on a multi-platform architecture with platform-specific access restrictions:

### **Desktop Application (Electron)**
- **Purpose**: Administrative operations
- **Access**: Admin and Registrar roles only
- **Features**: Full administrative dashboard, student management, financial management, user management, settings
- **Blocked Features**: Admission forms, student portal (for applicants/students)

### **Web Application**
- **Purpose**: Student/Applicant access
- **Access**: Applicants and Students
- **Features**: Admission dashboard, application forms, document submission, application status
- **Blocked Features**: All administrative operations

### **Mobile Application (API)**
- **Purpose**: Mobile app access for students
- **Access**: Students via API authentication
- **Features**: Dashboard, academic info, financial info, notifications

---

## 🛠️ Technology Stack

### **Backend**
- **Framework**: Laravel 11 (PHP 8.2+)
- **Database**: SQLite (development) / MySQL (production)
- **Authentication**: Laravel Sanctum (mobile authentication)
- **Authorization**: Spatie Laravel Permission
- **Real-time**: Laravel Reverb (WebSocket)
- **PDF Generation**: DomPDF
- **Excel Export**: Maatwebsite Excel

### **Frontend**
- **Templates**: Blade (Server-side rendering)
- **CSS Framework**: Tailwind CSS
- **JavaScript**: Vanilla JS, jQuery
- **Charts**: Chart.js
- **Tables**: DataTables
- **Build Tool**: Vite

### **Desktop Application**
- **Framework**: Electron.js
- **Build Tool**: Electron Builder
- **Platform**: Windows (Portable Executable)

### **Mobile Application**
- **Framework**: React Native (Expo)
- **API**: RESTful API with Laravel Sanctum

---

## 📦 Installation

### Prerequisites

- **PHP**: ^8.2 (with required extensions)
- **Composer**: Latest version
- **Node.js**: 18.x or higher (LTS)
- **NPM**: Comes with Node.js
- **Git**: For cloning the repository

### Quick Start

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd dreamy
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Set up environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure database** (SQLite by default)
   ```bash
   touch database/database.sqlite
   ```

6. **Run migrations and seeders**
   ```bash
   php artisan migrate --seed
   ```

7. **Build frontend assets**
   ```bash
   npm run build
   ```

8. **Start development server**
   ```bash
   composer run dev
   ```
   This starts:
   - Laravel development server (http://localhost:8000)
   - Queue worker
   - Vite dev server

For detailed installation instructions, see [INSTALLATION.md](./INSTALLATION.md).

---

## 🔐 Platform Restrictions

The system implements platform-based access restrictions:

### **Desktop App Only** (Administrative Operations)
- Admin Dashboard
- Student Management
- Financial Management (Invoices, School Fees, Payment Plans)
- User Management
- Program, Section, Subject Management
- School Settings
- Document Management
- Enrollment Management

### **Web Browser Only** (Admission Process)
- Admission Dashboard
- Application Form
- Document Submission
- Application Status Tracking

### **Platform Detection**
- Desktop app sends custom User-Agent: `DreamyDesktopApp/1.0.0 (Electron)`
- Web browser uses standard browser User-Agent
- Middleware automatically detects and restricts access

For detailed platform restriction information, see [PLATFORM_RESTRICTIONS_SUMMARY.md](./PLATFORM_RESTRICTIONS_SUMMARY.md).

---

## 👥 User Roles & Permissions

### **Super Admin**
- Full system access
- User management
- School settings
- All administrative features

### **Registrar**
- Student enrollment
- Application management
- Financial management
- Academic management
- Document management

### **Head Teacher**
- View assigned sections
- Grade management
- Student evaluation

### **Teacher**
- View assigned sections
- Student evaluation

### **Student**
- View academic information
- View financial information
- Access via or mobile app

### **Applicant**
- Submit application form
- Upload documents
- Track application status
- Access via web only

---

## 🚀 Development

### **Running the Development Server**

```bash
# Start all development services
composer run dev

# Or individually:
php artisan serve          # Laravel server
php artisan queue:listen   # Queue worker
npm run dev                # Vite dev server
```

### **Running Reverb (WebSocket Server)**

```bash
php artisan reverb:start
```

### **Running Tests**

```bash
php artisan test
```

### **Code Style**

```bash
# Format PHP code
./vendor/bin/pint
```

---

## 📱 Desktop Application Setup

The desktop application is located in the `laravel-electron` directory.

### **Development**

```bash
cd laravel-electron
npm install
npm run dev
```

### **Building for Production**

```bash
npm run build:win
```

The built executable will be in `laravel-electron/dist/`.

### **Configuration**

- Production URL: Configured in `main.js`
- User Agent: Automatically set to `DreamyDesktopApp/1.0.0 (Electron)`
- Login Page: Loads `/portal/login` directly

---

## 🔔 Real-time Notifications

The system uses Laravel Reverb for real-time notifications:

### **Configuration**

1. Set Reverb environment variables in `.env`:
   ```env
   REVERB_APP_ID=your_app_id
   REVERB_APP_KEY=your_app_key
   REVERB_APP_SECRET=your_app_secret
   REVERB_HOST=dreamyschoolph.site
   REVERB_PORT=8080
   REVERB_SCHEME=https
   
   VITE_REVERB_APP_KEY=your_app_key
   VITE_REVERB_HOST=dreamyschoolph.site
   VITE_REVERB_PORT=8080
   VITE_REVERB_SCHEME=https
   ```

2. Start Reverb server:
   ```bash
   php artisan reverb:start
   ```

3. Rebuild frontend assets:
   ```bash
   npm run build
   ```

---

## 🗄️ Database

### **Default Configuration (SQLite)**
- Database file: `database/database.sqlite`
- No additional configuration required

### **MySQL Configuration**
Update `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dreamy
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

---

## 📂 Project Structure

```
dreamy/
├── app/
│   ├── Console/          # Artisan commands
│   ├── Events/           # Event classes
│   ├── Exceptions/       # Exception handlers
│   ├── Http/
│   │   ├── Controllers/  # Application controllers
│   │   └── Middleware/   # Custom middleware
│   ├── Models/           # Eloquent models
│   ├── Services/         # Business logic services
│   └── ...
├── database/
│   ├── migrations/       # Database migrations
│   ├── seeders/          # Database seeders
│   └── database.sqlite   # SQLite database
├── resources/
│   ├── views/            # Blade templates
│   ├── js/               # JavaScript files
│   └── css/              # CSS files
├── routes/
│   ├── web.php           # Web routes
│   └── api.php           # API routes
├── public/               # Public assets
└── ...
```

---

## 🔒 Security Features

- **PIN Security**: Additional PIN verification for sensitive operations
- **Role-Based Access Control**: Spatie Laravel Permission
- **CSRF Protection**: Laravel's built-in CSRF protection
- **Platform Restrictions**: Middleware-based platform detection and restriction
- **Session Management**: Secure session handling
- **Rate Limiting**: PIN verification rate limiting
- **Activity Logging**: Spatie Activity Log for audit trails

---

## 📚 Documentation

- [Installation Guide](./INSTALLATION.md)
- [Platform Restrictions](./PLATFORM_RESTRICTIONS_SUMMARY.md)
- [Payment Plan System](./PAYMENT_PLAN_SYSTEM.md)
- [Academic Term Service](./ACADEMIC_TERM_SERVICE_USAGE.md)

---

## 🌐 Deployment

### **Production URL**
- Web Application: `https://dreamyschoolph.site`
- Reverb Server: `wss://dreamyschoolph.site:8080`

### **Environment Variables**
Ensure all required environment variables are set in production `.env`:
- Database configuration
- Reverb configuration
- Mail configuration
- App URL
- Session configuration

---

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

---

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

## 👨‍💻 Support

For support and inquiries, please contact the development team.

---

## 🎓 Capstone Project

This project is developed as a capstone project for academic purposes.

---

<div align="center">
  <p>Built with ❤️ using Laravel 11</p>
</div>
