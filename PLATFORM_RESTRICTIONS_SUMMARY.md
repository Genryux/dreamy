# Platform Restrictions

This application features full platform separation between Desktop and Web access, ensuring optimized performance, tailored user experiences, and independent management for each platform.

---

## **Key Features:**

### **1. Platform Detection Middleware**
- **`DetectPlatform.php`** - Detects if request is from Electron desktop app
- **`DesktopOnly.php`** - Blocks web browsers from admin features  
- **`WebOnly.php`** - Blocks desktop app from admission features
- **Custom User Agent** - Electron sends `DreamyDesktopApp/1.0.0 (Electron)`

### **2. Route Restrictions**

#### **Desktop App Only (Administrative Operations)**
```php
Route::middleware(['auth', 'pin.security', 'exclude.applicant', 'detect.platform', 'desktop.only'])
```
**Blocked from Web:**
- `/admin` - Admin Dashboard
- `/enrolled-students` - Student Management
- `/school-fees` - Financial Management
- `/invoice/*` - Invoice System
- `/programs` - Program Management
- `/sections` - Section Management
- `/admin/users` - User Management
- `/admin/settings` - School Settings
- All administrative operations

#### **Web Browser Only (Admission Process)**
```php
Route::middleware(['role:applicant|student', 'auth', 'pin.security', 'detect.platform', 'web.only'])
```
**Blocked from Desktop:**
- `/admission` - Admission Dashboard
- `/admission/application-form` - Application Form
- `/api/application-summary` - Application APIs

### **3. Electron Configuration**
- **Login Page**: Desktop app loads `/portal/login` directly
- **User Agent**: Custom identification for Laravel detection
- **Error Handling**: Proper connection error dialogs

### **4. Visual Indicators**
- **Platform Badge**: Shows "(Desktop)" or "(Web)" in navigation
- **Feature Sections**: Different UI sections for each platform
- **Error Pages**: Custom error pages for blocked access

---

## **How It Works:**

### **Desktop App Flow:**
```
1. User opens Desktop App
2. App loads: http://dreamy.test/portal/login
3. User logs in with admin credentials
4. Laravel detects "DreamyDesktopApp" user agent
5. User gets full administrative access
6. Navigation shows "Desktop Features" section
```

### **Web Browser Flow:**
```
1. User visits website in browser
2. User logs in with applicant/student credentials  
3. Laravel detects regular browser user agent
4. User gets admission-only access
5. Navigation shows "Web Features" section
6. Admin routes return 403 error with helpful message
```

---

## **Access Control Matrix:**

| Feature | Desktop App | Web Browser |
|---------|-------------|-------------|
| **Admin Dashboard** | Allowed | Blocked |
| **Student Management** | Allowed | Blocked |
| **Invoice System** | Allowed | Blocked |
| **School Fees** | Allowed | Blocked |
| **User Management** | Allowed | Blocked |
| **Settings** | Allowed | Blocked |
| **Admission Dashboard** | Blocked | Allowed |
| **Application Form** | Blocked | Allowed |
| **Student Portal** | Allowed | Allowed |

---

## **Test:**

### **Test Desktop App:**
```bash
# In your laravel-electron directory
npm run dev
```
**Expected Results:**
- ✅ Loads login page
- ✅ Shows "(Desktop)" badge
- ✅ Full admin access after login
- ✅ "Desktop Features" section visible

### **Test Web Browser:**
```
Visit: http://dreamy.test/portal/login
```
**Expected Results:**
-  Shows "(Web)" badge  
-  Limited to admission features
-  Admin routes show error page
-  "Web Features" section visible

---

## **Error Messages:**

### **Web User Tries Admin Feature:**
```
"Desktop App Required"
"This feature is only available on the desktop application."
"Please download and install the Dreamy School Management desktop app to access administrative features."
```

### **Desktop User Tries Admission Feature:**
```
"Web Browser Required"  
"This feature is only available on the web version."
"Please open your web browser and visit the website to access this feature."
```

---

## **Visual Features:**

### **Desktop App Navigation:**
- Blue "(Desktop)" badge
- "Desktop Features" section
- Full administrative menu
- "Full administrative access" indicator

### **Web Browser Navigation:**
- Green "(Web)" badge  
- "Web Features" section
- Limited admission menu
- "Limited to admission features" indicator
