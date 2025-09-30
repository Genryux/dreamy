<?php

use App\Http\Controllers\AcademicTermController;
use App\Http\Controllers\AdmissionDashboardController;
use App\Http\Controllers\ApplicantsController;
use App\Http\Controllers\ApplicationFormController;
use App\Http\Controllers\DocumentsController;
use App\Http\Controllers\DocumentsSubmissionController;
use App\Http\Controllers\EnrollmentPeriodController;
use App\Http\Controllers\InterviewController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\SchoolFeeController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\StudentRecordController;
use App\Http\Controllers\StudentsController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\SchoolSettingController;
use App\Http\Controllers\WebsiteResourceController;
use App\Http\Controllers\InvoicePaymentController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\TeacherManagementController;
use App\Http\Controllers\UserInvitationController;
use App\Models\Applicants;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
| Routes accessible to all users (guests and authenticated)
*/

// Homepage and Public Pages
Route::get('/', [WebsiteResourceController::class, 'homepage'])->name('home');
Route::get('/homepage', [WebsiteResourceController::class, 'index'])->name('homepage');

// Public News Routes
Route::get('/news', [WebsiteResourceController::class, 'news'])->name('public.news.index');
Route::get('/news/{news}', [WebsiteResourceController::class, 'showNews'])->name('public.news.show');

// Authentication Routes (Guest Only)
Route::middleware('guest')->group(function () {
    Route::get('/portal/login', [SessionController::class, 'create'])->name('login');
    Route::get('/portal/register', [RegistrationController::class, 'create'])->name('register');
    Route::post('/session', [SessionController::class, 'store']);
    Route::post('/register', [RegistrationController::class, 'store']);
});

// User Registration from Invitation (Public)
Route::get('/user/register/{token}', [UserInvitationController::class, 'showRegistration'])->name('user.register');
Route::post('/user/register/{token}', [UserInvitationController::class, 'storeRegistration'])->name('user.register.store');
Route::get('/user/invitation/{token}', [UserInvitationController::class, 'status'])->name('user.invitation.status');

// Application Form (Public)
Route::get('/admission/application-form', [ApplicationFormController::class, 'create'])->name('admission.form.get');
Route::post('/admission/application-form', [ApplicationFormController::class, 'store'])->name('admission.form.post');

// Website Background Upload (Public)
Route::post('/upload-background', [WebsiteResourceController::class, 'UploadMainBg'])->name('upload.store');

/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES
|--------------------------------------------------------------------------
| Routes that require user authentication
*/

Route::middleware('auth')->group(function () {

    // Logout
    Route::delete('/logout', [SessionController::class, 'destroy'])->name('logout');

    // Student Dashboard
    Route::get('/student', function () {
        return view('user-student.student');
    })->name('student');

    // Applicant Dashboard and Status
    Route::get('/admission', [AdmissionDashboardController::class, 'index'])->name('admission.dashboard');
    Route::get('/admission/status', function () {
        $applicant = Applicants::where('user_id', Auth::user()->id)->first();
        $application_status = $applicant->application_status ?? '';
        return view('user-applicant.status', ['status' => $application_status]);
    })->name('status');

    // Document Submission
    Route::post('/submit-document', [DocumentsSubmissionController::class, 'store'])->name('documents.store');
    Route::patch('/submit-document/{applicant}', [DocumentsSubmissionController::class, 'update']);

    // Applicant Updates
    Route::patch('/applicants/{applicants}', [ApplicantsController::class, 'update']);
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
| Routes accessible only to super_admin and registrar roles
*/

Route::middleware(['auth', 'role:super_admin|registrar'])->group(function () {

    // Admin Dashboard
    Route::get('/admin', [ApplicationFormController::class, 'index'])->name('admin');
    Route::get('/admin/recent-applications', [ApplicationFormController::class, 'getRecentApplications'])->name('admin.recent-applications');

    // School Settings
    Route::get('/admin/settings/school', [SchoolSettingController::class, 'edit'])->name('admin.settings.school.edit');
    Route::post('/admin/settings/school', [SchoolSettingController::class, 'update'])->name('admin.settings.school.update');

    // Application Management
    Route::get('/applications/pending', [ApplicationFormController::class, 'pending'])->name('applications.pending');
    Route::get('/pending-application/form-details/{id}', [ApplicationFormController::class, 'show'])->name('pending.details');
    Route::get('/selected-applications', [ApplicationFormController::class, 'selected'])->name('applications.approved');
    Route::get('/selected-application/interview-details/{id}', [InterviewController::class, 'show'])->name('selected.details');

    // Document Management
    Route::get('/pending-documents', [ApplicationFormController::class, 'pendingDocuments'])->name('documents');
    Route::get('/pending-documents/document-details/{applicant}', [DocumentsSubmissionController::class, 'index'])->name('documents');
    Route::get('/pending-documents/document-list', [DocumentsController::class, 'index'])->name('documents.index');
    Route::post('/required-docs', [DocumentsController::class, 'store'])->name('documents.store');

    // Academic Terms
    Route::post('/academic-terms', [AcademicTermController::class, 'store'])->name('academic-terms.post');

    // Enrollment Period
    Route::post('/enrollment-period', [EnrollmentPeriodController::class, 'store'])->name('enrollment-period.post');
    Route::patch('/enrollment-period/{id}', [EnrollmentPeriodController::class, 'update'])->name('enrollment-period.patch');

    // Interview Management
    Route::post('/set-interview/{id}', [InterviewController::class, 'store'])->name('interview.post');
    Route::patch('/set-interview/{id}', [InterviewController::class, 'update'])->name('interview.patch');

    // Student Management
    Route::get('/enrolled-students', [StudentsController::class, 'index'])->name('students.index');
    Route::get('/users', [StudentsController::class, 'getUsers']);
    Route::get('/enrollment-stats', [StudentsController::class, 'getEnrollmentStats']);
    Route::post('/getStudent', [StudentsController::class, 'getStudent']);
    Route::post('/students/import', [StudentRecordController::class, 'import']);
    Route::get('/students/export/excel', [StudentRecordController::class, 'exportExcel'])->name('students.export.excel');
    Route::post('/student-record/{id}', [StudentRecordController::class, 'store']);
    Route::post('/students/{id}', [StudentRecordController::class, 'store']);
    Route::post('/assign-section/{section}', [StudentsController::class, 'assignSection']);

    // Student Records
    Route::get('/student/{studentRecord}', [StudentRecordController::class, 'show']);
    Route::get('/student-record/{studentRecord}/coe', [StudentRecordController::class, 'coePreview'])->name('students.coe.preview');
    Route::get('/student-record/{studentRecord}/coe.pdf', [StudentRecordController::class, 'coePdf'])->name('students.coe.pdf');

    // Teacher Management
    Route::get('/admin/teachers', [TeacherManagementController::class, 'index'])->name('admin.teachers.index');
    Route::get('/admin/teachers/create', [TeacherManagementController::class, 'create'])->name('admin.teachers.create');
    Route::post('/admin/teachers', [TeacherManagementController::class, 'store'])->name('admin.teachers.store');
    Route::get('/admin/teachers/{teacher}', [TeacherManagementController::class, 'show'])->name('admin.teachers.show');
    Route::get('/admin/teachers/{teacher}/edit', [TeacherManagementController::class, 'edit'])->name('admin.teachers.edit');
    Route::put('/admin/teachers/{teacher}', [TeacherManagementController::class, 'update'])->name('admin.teachers.update');
    Route::delete('/admin/teachers/{teacher}', [TeacherManagementController::class, 'destroy'])->name('admin.teachers.destroy');
    Route::patch('/admin/teachers/{teacher}/toggle-status', [TeacherManagementController::class, 'toggleStatus'])->name('admin.teachers.toggle-status');
    Route::get('/admin/getTeachers', [TeacherManagementController::class, 'getTeachers'])->name('admin.getTeachers');

    // User Management
    Route::get('/admin/users', [UserInvitationController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/users/invite', [UserInvitationController::class, 'invite'])->name('admin.users.invite');
    Route::post('/admin/users/send-invitation', [UserInvitationController::class, 'sendInvitation'])->name('admin.users.send-invitation');
    Route::get('/admin/users/data', [UserInvitationController::class, 'getAllUsers'])->name('admin.users.data');
    Route::get('/admin/users/analytics', [UserInvitationController::class, 'getAnalytics'])->name('admin.users.analytics');
    Route::post('/admin/users/{user}/resend-invitation', [UserInvitationController::class, 'resendInvitation'])->name('admin.users.resend-invitation');
    Route::delete('/admin/users/{user}/cancel-invitation', [UserInvitationController::class, 'cancelInvitation'])->name('admin.users.cancel-invitation');

    // User Management Tabs - Roles and Permissions
    Route::get('/admin/users/roles', [UserInvitationController::class, 'roles'])->name('admin.users.roles');

    // Role Management Routes
    Route::get('/admin/roles/data', [UserInvitationController::class, 'getRolesData'])->name('admin.roles.data');
    Route::get('/admin/roles', [UserInvitationController::class, 'getAllRoles'])->name('admin.roles.index');
    Route::get('/admin/permissions', [UserInvitationController::class, 'getAllPermissions'])->name('admin.permissions.index');
    Route::get('/admin/roles/{role}', [UserInvitationController::class, 'getRole'])->name('admin.roles.show');
    Route::post('/admin/roles', [UserInvitationController::class, 'createRole'])->name('admin.roles.create');
    Route::put('/admin/roles/{role}', [UserInvitationController::class, 'updateRole'])->name('admin.roles.update');
    Route::post('/admin/roles/{role}/permissions', [UserInvitationController::class, 'updateRolePermissions'])->name('admin.roles.permissions');
    Route::delete('/admin/roles/{role}', [UserInvitationController::class, 'deleteRole'])->name('admin.roles.delete');


    // News Management
    Route::get('/admin/news', [NewsController::class, 'index'])->name('admin.news.index');
    Route::get('/admin/getNews', [NewsController::class, 'getNews']);
    Route::get('/admin/news/{news}', [NewsController::class, 'show']);
    Route::post('/admin/news', [NewsController::class, 'storeOrUpdate']);
    Route::put('/admin/news/{news}', [NewsController::class, 'update']);
    Route::delete('/admin/news/{news}', [NewsController::class, 'destroy']);

    // School Fees and Invoices
    Route::get('/school-fees', [SchoolFeeController::class, 'index'])->name('school-fees.index');
    Route::get('/school-fees/invoices', [SchoolFeeController::class, 'index'])->name('school-fees.invoices');
    Route::get('/school-fees/payments', [SchoolFeeController::class, 'index'])->name('school-fees.payments');
    Route::get('/getSchoolFees', [SchoolFeeController::class, 'getSchoolFees']);
    Route::post('/school-fees', [SchoolFeeController::class, 'store']);
    Route::post('/invoice', [InvoiceController::class, 'store']);
    Route::get('/getInvoices', [InvoiceController::class, 'getInvoices']);
    Route::get('/invoice/{id}', [InvoiceController::class, 'show']);
    Route::get('/getPayments', [InvoicePaymentController::class, 'getPayments']);
    Route::post('/invoice/{invoice}/payments', [InvoicePaymentController::class, 'store'])->name('invoice.payments.store');
});

/*
|--------------------------------------------------------------------------
| HEAD TEACHER ROUTES
|--------------------------------------------------------------------------
| Routes accessible only to head_teacher role
*/

Route::middleware(['auth', 'role:head_teacher'])->group(function () {
    Route::get('/head-teacher/dashboard', function () {
        return view('user-head-teacher.dashboard');
    })->name('head-teacher.dashboard');
});

/*
|--------------------------------------------------------------------------
| TEACHER ROUTES
|--------------------------------------------------------------------------
| Routes accessible only to teacher role
*/

Route::middleware(['auth', 'role:teacher'])->group(function () {
    Route::get('/teacher/dashboard', [TeacherManagementController::class, 'dashboard'])->name('teacher.dashboard');
    Route::get('/teacher/sections', [TeacherManagementController::class, 'getTeacherSections'])->name('teacher.sections');
    Route::get('/teacher/section/{section}', [TeacherManagementController::class, 'showSection'])->name('teacher.section.show');
});

/*
|--------------------------------------------------------------------------
| SECTION MANAGEMENT ROUTES
|--------------------------------------------------------------------------
| Routes for managing sections (accessible to admin and head_teacher)
*/

Route::middleware(['auth', 'role:super_admin|head_teacher'])->group(function () {
    Route::get('/sections', [SectionController::class, 'index'])->name('sections.index');
    Route::post('/section', [SectionController::class, 'store'])->name('sections.store');
    Route::get('/section/{section}', [SectionController::class, 'show'])->name('sections.show');
    Route::post('/section/{section}', [SectionController::class, 'update'])->name('sections.update');

    // Section Subject Management
    Route::post('/assignSubject/{section}', [SectionController::class, 'assignSubject'])->name('sections.assign-subject');
    Route::post('/updateSubject/{section}', [SectionController::class, 'updateSubject'])->name('sections.update-subject');
    Route::post('/checkScheduleConflict/{section}', [SectionController::class, 'checkScheduleConflict'])->name('sections.check-conflict');
});

/*
|--------------------------------------------------------------------------
| PROGRAM MANAGEMENT ROUTES
|--------------------------------------------------------------------------
| Routes for managing programs (accessible to admin)
*/

Route::middleware(['auth', 'role:super_admin|registrar|head_teacher'])->group(function () {
    Route::get('/programs', [ProgramController::class, 'index'])->name('programs.index');
    Route::post('/programs', [ProgramController::class, 'store'])->name('programs.store');
    Route::get('/program/{program}', [ProgramController::class, 'show'])->name('program.show');
    Route::post('/updateProgram/{program}', [ProgramController::class, 'update'])->name('program.update');
    Route::delete('/program/{program}', [ProgramController::class, 'destroy'])->name('program.destroy');
    Route::get('/program/{program}/sections', [ProgramController::class, 'show'])->name('program.sections');
    Route::get('/program/{program}/subjects', [ProgramController::class, 'show'])->name('program.subjects');
    Route::get('/getPrograms', [ProgramController::class, 'getPrograms']);
});

/*
|--------------------------------------------------------------------------
| SUBJECT MANAGEMENT ROUTES
|--------------------------------------------------------------------------
| Routes for managing subjects (accessible to admin and head_teacher)
*/

Route::middleware(['auth', 'role:super_admin|head_teacher'])->group(function () {
    Route::get('/subjects', [SubjectController::class, 'index'])->name('subjects.index');
    Route::get('/getSubjects/{program}', [SubjectController::class, 'getSubjects']);
    Route::get('/subjects/auto-assign', [SubjectController::class, 'getAutoAssignSubjects']);
});

/*
|--------------------------------------------------------------------------
| AJAX/API ROUTES
|--------------------------------------------------------------------------
| Routes for AJAX requests and API endpoints
*/

Route::middleware('auth')->group(function () {
    // Section-related AJAX routes
    Route::get('/getSections/{program}', [SectionController::class, 'getSections']);
    Route::get('/getStudents/{section}', [SectionController::class, 'getStudents']);
    Route::get('/getAvailableStudents/{section}', [SectionController::class, 'getAvailableStudents']);
    Route::get('/getAvailableSubjects/{section}', [SectionController::class, 'getAvailableSubjects']);
    Route::get('/getTeachers', [SectionController::class, 'getTeachers']);
    Route::get('/getSectionSubject/{sectionSubjectId}', [SectionController::class, 'getSectionSubject']);
    
    // Notification routes - optimized for performance
    Route::get('/notifications', function () {
        $notifications = auth()->user()
            ->notifications()
            ->select(['id', 'data', 'read_at', 'created_at'])
            ->latest()
            ->take(20)
            ->get();
        return response()->json(['notifications' => $notifications]);
    });
    
    Route::post('/notifications/{notification}/mark-read', function ($notificationId) {
        $notification = auth()->user()->notifications()->find($notificationId);
        if ($notification) {
            $notification->markAsRead();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 404);
    });
    
    Route::post('/notifications/mark-all-read', function () {
        auth()->user()->unreadNotifications()->update(['read_at' => now()]);
        return response()->json(['success' => true]);
    });
});

/*
|--------------------------------------------------------------------------
| TEST ROUTES
|--------------------------------------------------------------------------
| Routes for testing purposes (should be removed in production)
*/

Route::get('/test', function () {
    return view('test');
});

// Debug notification route
Route::get('/test-notification', function () {
    $admins = \App\Models\User::role(['registrar', 'super_admin'])->get();
    $teachers = \App\Models\User::role(['head_teacher', 'teacher'])->get();
    $students = \App\Models\User::role(['student'])->get();
    
    if ($admins->isEmpty() && $teachers->isEmpty() && $students->isEmpty()) {
        return response()->json(['error' => 'No admin, teacher, or student users found']);
    }
    
    // Send to admin roles
    if (!$admins->isEmpty()) {
        Notification::send($admins, new \App\Notifications\QueuedNotification(
            "Test Admin Notification",
            "This is a test notification for admin roles!",
            url('/admin/users')
        ));
        
        Notification::route('broadcast', 'admins')
            ->notify(new \App\Notifications\ImmediateNotification(
                "Test Admin Notification",
                "This is a test notification for admin roles!",
                url('/admin/users')
            ));
    }
    
    // Send to teacher roles
    if (!$teachers->isEmpty()) {
        Notification::send($teachers, new \App\Notifications\QueuedNotification(
            "Test Teacher Notification",
            "This is a test notification for teacher roles!",
            url('/enrolled-students')
        ));
        
        Notification::route('broadcast', 'teachers')
            ->notify(new \App\Notifications\ImmediateNotification(
                "Test Teacher Notification",
                "This is a test notification for teacher roles!",
                url('/enrolled-students')
            ));
    }
    
    // Send to student roles (for mobile app testing)
    if (!$students->isEmpty()) {
        // Generate a shared ID for both queued and immediate notifications
        $sharedNotificationId = 'test-student-' . time() . '-' . uniqid();
        
        // Database notification (queued)
        Notification::send($students, new \App\Notifications\QueuedNotification(
            "Test Student Notification",
            "This is a test notification for student mobile app!",
            null, // No URL needed for mobile
            $sharedNotificationId // Shared ID for mobile app matching
        ));
        
        // Real-time broadcast (immediate)
        Notification::route('broadcast', 'students')
            ->notify(new \App\Notifications\ImmediateNotification(
                "Test Student Notification",
                "This is a test notification for student mobile app!",
                null, // No URL needed for mobile
                $sharedNotificationId // Same shared ID for matching
            ));
    }
    
    return response()->json([
        'success' => true,
        'message' => 'Test notifications sent to ' . $admins->count() . ' admin(s), ' . $teachers->count() . ' teacher(s), and ' . $students->count() . ' student(s)',
        'admins' => $admins->pluck('email'),
        'teachers' => $teachers->pluck('email'),
        'students' => $students->pluck('email')
    ]);
});

// Debug notification API route
Route::get('/debug-notifications', function () {
    $user = auth()->user();
    if (!$user) {
        return response()->json(['error' => 'Not authenticated']);
    }
    
    $notifications = $user->notifications()->latest()->take(20)->get();
    
    return response()->json([
        'user' => $user->email,
        'user_roles' => $user->roles->pluck('name'),
        'notifications_count' => $notifications->count(),
        'unread_count' => $user->unreadNotifications()->count(),
        'notifications' => $notifications
    ]);
});

// Test notification creation directly
Route::get('/test-notification-direct', function () {
    $user = auth()->user();
    if (!$user) {
        return response()->json(['error' => 'Not authenticated']);
    }
    
    try {
        $user->notify(new \App\Notifications\QueuedNotification(
            "Direct Test Notification",
            "This notification was created directly to test the system.",
            url('/admin/users')
        ));
        
        return response()->json([
            'success' => true,
            'message' => 'Notification sent successfully',
            'user' => $user->email,
            'notifications_count' => $user->notifications()->count()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Failed to create notification',
            'message' => $e->getMessage()
        ]);
    }
});

// Comprehensive notification test
Route::get('/test-notification-comprehensive', function () {
    try {
        // Test 1: Check if admin users exist
        $admins = \App\Models\User::role(['registrar', 'super_admin'])->get();
        
        if ($admins->isEmpty()) {
            return response()->json(['error' => 'No admin users found']);
        }
        
        $results = [];
        
        // Test 2: Try to create notification for each admin
        foreach ($admins as $admin) {
            try {
                $beforeCount = $admin->notifications()->count();
                
                $admin->notify(new \App\Notifications\QueuedNotification(
                    "Comprehensive Test Notification",
                    "This is a comprehensive test of the notification system.",
                    url('/admin/users')
                ));
                
                $afterCount = $admin->notifications()->count();
                
                $results[] = [
                    'admin_email' => $admin->email,
                    'before_count' => $beforeCount,
                    'after_count' => $afterCount,
                    'success' => $afterCount > $beforeCount,
                    'error' => null
                ];
            } catch (\Exception $e) {
                $results[] = [
                    'admin_email' => $admin->email,
                    'success' => false,
                    'error' => $e->getMessage()
                ];
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Comprehensive test completed',
            'admin_count' => $admins->count(),
            'results' => $results
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Comprehensive test failed',
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
});

Route::post('/test/{id}', [StudentRecordController::class, 'store']);
