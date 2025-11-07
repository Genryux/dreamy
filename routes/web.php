<?php

use App\Http\Controllers\AcademicTermController;
use App\Http\Controllers\AdmissionDashboardController;
use App\Http\Controllers\ApplicantsController;
use App\Http\Controllers\ApplicationFormController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\DocumentsController;
use App\Http\Controllers\DocumentsSubmissionController;
use App\Http\Controllers\EnrollmentPeriodController;
use App\Http\Controllers\InterviewController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InvoiceItemController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\EmailVerificationController;
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
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TeacherManagementController;
use App\Http\Controllers\UserInvitationController;
use App\Http\Controllers\TrackController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Models\Applicants;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
| Routes accessible to all users (guests and authenticated)
*/

// Homepage and Public Pages (blocked for desktop app users)
Route::get('/', [WebsiteResourceController::class, 'homepage'])->name('home');
// Route::get('/homepage', [WebsiteResourceController::class, 'index'])->name('homepage');

// Public News Routes
Route::get('/news', [WebsiteResourceController::class, 'news'])->name('public.news.index');
Route::get('/news/{news}', [WebsiteResourceController::class, 'showNews'])->name('public.news.show');

// Authentication Routes (Guest Only)
Route::middleware('guest')->group(function () {
    Route::get('/portal/login', [SessionController::class, 'create'])->name('login');
    Route::get('/portal/register', [RegistrationController::class, 'create'])->name('register');
    Route::post('/session', [SessionController::class, 'store']);
    Route::post('/register', [RegistrationController::class, 'store']);
    
    // Password Reset Routes
    Route::get('/forgot-password', [PasswordResetController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])->name('password.update');
    Route::get('/password-reset-success', [PasswordResetController::class, 'showSuccessPage'])->name('password.reset.success');
});

// Email Verification Routes
Route::middleware('auth')->group(function () {
    Route::get('/email/verify', [EmailVerificationController::class, 'notice'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])->name('verification.verify');
    Route::post('/email/verification-notification', [EmailVerificationController::class, 'resend'])->name('verification.send');
});

// Email Verification Status Pages (Public)
Route::get('/email/verify/success', [EmailVerificationController::class, 'success'])->name('verification.success');
Route::get('/email/verify/failed', [EmailVerificationController::class, 'failed'])->name('verification.failed');
Route::get('/email/verify/already-verified', [EmailVerificationController::class, 'alreadyVerified'])->name('verification.already-verified');

// User Registration from Invitation (Public)
Route::get('/user/register/{token}', [UserInvitationController::class, 'showRegistration'])->name('user.register');
Route::post('/user/register/{token}', [UserInvitationController::class, 'storeRegistration'])->name('user.register.store');
Route::get('/user/invitation/{token}', [UserInvitationController::class, 'status'])->name('user.invitation.status');



// Website Background Upload (Public)
Route::post('/upload-background', [WebsiteResourceController::class, 'UploadMainBg'])->name('upload.store');

// Web Admin Message (for admin users accessing via web browser)
Route::get('/web-admin-message', function () {
    return view('auth.web-admin-message');
})->name('web.admin.message');

/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES
|--------------------------------------------------------------------------
| Routes that require user authentication
*/

Route::middleware(['role:applicant|student', 'auth', 'pin.security'])->group(function () {
    Route::get('/admission/application-form', [ApplicationFormController::class, 'create'])->name('admission.form.get');
    Route::post('/admission/application-form', [ApplicationFormController::class, 'store'])->name('admission.form.post');
    // Applicant Dashboard and Status - WEB ONLY
    Route::get('/admission', [AdmissionDashboardController::class, 'index'])->name('admission.dashboard')->middleware('verified');
    Route::get('/api/application-summary', [ApplicationFormController::class, 'getApplicationSummary'])->name('api.application-summary');
});

// PIN Security Routes (must be outside pin.security middleware to avoid loops)
Route::middleware('auth')->group(function () {
    Route::get('/pin/setup', [SessionController::class, 'showPinSetup'])->name('auth.pin.setup');
    Route::post('/pin/setup', [SessionController::class, 'setupPin'])->name('auth.pin.setup.store');
    Route::get('/pin/verify', [SessionController::class, 'showPinVerification'])->name('auth.pin.verify');
    Route::post('/pin/verify', [SessionController::class, 'verifyPin'])->name('auth.pin.verify.store');
});

Route::middleware(['auth', 'pin.security'])->group(function () {

    // Logout
    Route::delete('/logout', [SessionController::class, 'destroy'])->name('logout');

    // Test route to verify PIN security is working
    Route::get('/test-pin-security', function () {
        return response()->json([
            'message' => 'PIN security is working!',
            'user' => auth()->user()->email,
            'pin_verified' => session('pin_verified', false),
            'timestamp' => now()
        ]);
    })->name('test.pin.security');

    // Profile Settings
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/pin/setup', [ProfileController::class, 'setupPin'])->name('profile.pin.setup');
    Route::put('/profile/pin', [ProfileController::class, 'updatePin'])->name('profile.pin.update');
    Route::post('/profile/pin/enable', [ProfileController::class, 'enablePin'])->name('profile.pin.enable');
    Route::delete('/profile/pin', [ProfileController::class, 'disablePin'])->name('profile.pin.disable');

    // Student Dashboard
    Route::get('/student', function () {
        return view('user-student.student');
    })->name('student');

    Route::post('/update-status/{applicant}', [InterviewController::class, 'updateStatus'])->name('admission.update.status');

    // Document Submission
    Route::get('/document-restrictions', [DocumentsSubmissionController::class, 'getDocumentRestrictions'])->name('documents.restrictions');
    Route::post('/submit-document', [DocumentsSubmissionController::class, 'store'])
        ->middleware(['permission:submit document', 'verified'])->name('documents.store');
    // Applicant Updates
    Route::patch('/applicants/{applicants}', [ApplicantsController::class, 'update']);

    // Invoice Downloads
    Route::get('/invoice/{invoice}/schedule/{schedule}/download', [InvoiceController::class, 'downloadScheduleInvoice'])->name('invoice.schedule.download');
    Route::get('/invoice/{invoice}/schedule/{schedule}/receipt', [InvoiceController::class, 'downloadScheduleReceipt'])->name('invoice.schedule.receipt');

    // One-time payment invoice and receipt
    Route::get('/invoice/{invoice}/onetime/download', [InvoiceController::class, 'downloadOneTimeInvoice'])->name('invoice.onetime.download');
    Route::get('/invoice/{invoice}/onetime/receipt', [InvoiceController::class, 'downloadOneTimeReceipt'])->name('invoice.onetime.receipt');
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
| Routes accessible only to super_admin and registrar roles
*/

Route::middleware(['auth', 'pin.security', 'exclude.applicant'])->group(function () {

    // Admin Dashboard - DESKTOP ONLY
    Route::get('/admin', [ApplicationFormController::class, 'index'])->middleware(['permission:view enrollment dashboard page'])->name('admin');


    // School Settings
    Route::get('/admin/settings/school', [SchoolSettingController::class, 'edit'])->name('admin.settings.school.edit');
    Route::post('/admin/settings/school', [SchoolSettingController::class, 'update'])->name('admin.settings.school.update');
    Route::match(['PUT', 'POST'], '/admin/settings/school/payments', [SchoolSettingController::class, 'updatePayments'])
        ->middleware(['permission:create school fees', 'throttle:10,1']) // 10 requests per minute
        ->name('admin.settings.school.payments.update');

    // Activity Logs
    Route::get('/admin/activity-logs', [ActivityController::class, 'getActivityLogs'])->name('admin.activity-logs');

    // Application Management
    Route::get('/applications/pending', [ApplicationFormController::class, 'index'])
        ->middleware(['permission:view applications page'])->name('applications.pending');
    Route::get('/applications/accepted', [ApplicationFormController::class, 'index'])
        ->middleware(['permission:view applications page'])->name('applications.accepted');
    Route::get('/applications/pending-documents', [ApplicationFormController::class, 'index'])
        ->middleware(['permission:view applications page'])->name('applications.pending-documents');
    Route::get('/applications/rejected', [ApplicationFormController::class, 'index'])
        ->middleware(['permission:view applications page'])->name('applications.rejected');

    // Individual application pages
    Route::get('/applications/pending/form-details/{applicant}', [ApplicationFormController::class, 'pendingDetails'])
        ->middleware(['permission:view pending form'])->name('pending.details');
    Route::get('/applications/accepted/admission-details/{applicant}', [InterviewController::class, 'showAdmissionDetails'])
        ->middleware(['permission:view accepted form'])->name('selected.details');

    // Route::patch('/applicants/{applicants}', [ApplicantsController::class, 'update']);

    // DataTables routes for applications
    Route::get('/getRecentApplications', [ApplicationFormController::class, 'getRecentApplications'])->name('get.recent-applications');
    Route::get('/getPendingApplications', [ApplicationFormController::class, 'getPendingApplications'])->name('get.pending-applications');
    Route::get('/getAcceptedApplications', [ApplicationFormController::class, 'getAcceptedApplications'])->name('get.accepted-applications');
    Route::get('/getPendingDocumentsApplications', [ApplicationFormController::class, 'getPendingDocumentsApplications'])->name('get.pending-documents-applications');
    Route::get('/getRejectedApplications', [ApplicationFormController::class, 'getRejectedApplications'])->name('get.rejected-applications');

    // Application statistics API
    Route::get('/api/application-statistics', [ApplicationFormController::class, 'getApplicationStatistics'])->name('api.application-statistics');

    // Enrollment summary API
    Route::get('/api/enrollment-summary/{enrollmentPeriod?}', function ($enrollmentPeriodId = null) {
        $dashboardService = app(\App\Services\DashboardDataService::class);
        $summary = $dashboardService->getEnrollmentSummary($enrollmentPeriodId);
        return response()->json($summary);
    })->name('api.enrollment-summary');


    // Document Management
    Route::get('/applications/pending-document/submission-details/{applicant}', [DocumentsSubmissionController::class, 'index'])
        ->middleware(['permission:view pending-document form'])->name('documents');
    Route::get('/documents', [DocumentsController::class, 'index'])->middleware(['permission:view documents'])->name('documents.index');
    Route::get('/getDocuments', [DocumentsController::class, 'getDocuments']);
    Route::post('/required-docs', [DocumentsController::class, 'store'])->middleware(['permission:create documents'])->name('documents.create');
    Route::get('/required-docs/{id}', [DocumentsController::class, 'show']);
    Route::put('/required-docs/{id}', [DocumentsController::class, 'update'])->middleware(['permission:edit documents']);
    Route::delete('/required-docs/{id}', [DocumentsController::class, 'destroy'])->middleware(['permission:delete documents']);
    Route::patch('/submit-document/{applicant}', [DocumentsSubmissionController::class, 'update'])
        ->middleware(['permission:manage submitted documents']);
    // Academic Terms
    Route::post('/academic-terms', [AcademicTermController::class, 'store'])->middleware(['permission:create new term'])->name('academic-terms.post');
    Route::post('/new-term/{id}', [AcademicTermController::class, 'startNewTerm'])->middleware(['permission:create new term'])->name('new-term');
    Route::put('/academic-terms/{id}', [AcademicTermController::class, 'update'])->middleware(['permission:edit term'])->name('academic-terms.update');

    // Enrollment Period
    Route::post('/enrollment-period', [EnrollmentPeriodController::class, 'store'])->middleware(['permission:add enrollment period'])->name('enrollment-period.post');
    Route::patch('/enrollment-period/{id}', [EnrollmentPeriodController::class, 'update'])->middleware(['permission:update enrollment period'])->name('enrollment-period.patch');
    Route::put('/enrollment-period/{id}', [EnrollmentPeriodController::class, 'updateEnrollment'])->middleware(['permission:update enrollment period'])->name('enrollment-period.update');


    // Interview Management
    Route::post('/schedule-admission/{applicant}', [InterviewController::class, 'store'])
        ->middleware(['permission:accept and schedule'])->name('admission.post');
    Route::put('/record-admission-result/{applicant}', [InterviewController::class, 'recordAdmissionResult'])
        ->middleware(['permission:record result'])->name('admission.record');
    // Route::put('/set-interview/{id}', [InterviewController::class, 'update'])->name('interview.patch');
    Route::post('/reject-application/{applicant}', [ApplicationFormController::class, 'reject'])
        ->middleware(['permission:reject application'])->name('application.reject');

    // Student Management
    Route::get('/enrolled-students', [StudentsController::class, 'index'])->middleware(['permission:view enrolled students page'])->name('students.index');
    Route::get('/users', [StudentsController::class, 'getUsers']);
    Route::get('/application-analytics', [StudentsController::class, 'getApplicationAnalytics']);
    Route::get('/enrollment-analytics', [StudentsController::class, 'getEnrollmentAnalytics']);
    Route::get('/enrollment-stats', [StudentsController::class, 'getEnrollmentStats']);
    Route::post('/getStudent', [StudentsController::class, 'getStudent']);
    Route::post('/students/import', [StudentRecordController::class, 'import'])->middleware(['permission:import student']);
    Route::get('/students/export/excel', [StudentRecordController::class, 'exportExcel'])->name('students.export.excel');
    Route::post('/student-record/{id}', [StudentRecordController::class, 'store'])->middleware(['permission:enroll student']);
    Route::post('/students/{id}', [StudentRecordController::class, 'store'])
        ->middleware(['permission:enroll student']);
    Route::post('/assign-section/{section}', [StudentsController::class, 'assignSection'])->middleware(['permission:add student to a section']);
    Route::post('/removeStudentFromSection/{section}', [StudentsController::class, 'removeStudentFromSection'])->middleware(['permission:remove assigned subject to a section', 'throttle:10,1']);
    Route::patch('/evaluate-student/{id}', [StudentsController::class, 'evaluateStudent'])->middleware(['permission:evaluate student']);
    Route::patch('/promote-student/{id}', [StudentsController::class, 'promoteStudent'])->middleware(['permission:promote student']);
    Route::patch('/withdraw-student/{id}', [StudentsController::class, 'withdrawStudent'])->middleware(['permission:withdraw enrollment']);

    // Student Records
    Route::get('/student/{student}', [StudentRecordController::class, 'show'])->middleware(['permission:view student']);
    Route::get('/student-record/{studentRecord}/coe', [StudentRecordController::class, 'coePreview'])->name('students.coe.preview');
    Route::get('/student-record/{studentRecord}/coe.pdf', [StudentRecordController::class, 'coePdf'])->name('students.coe.pdf');
    Route::put('/students/{student}/personal-info', [StudentRecordController::class, 'updatePersonalInfo'])->middleware(['permission:edit student'])->name('students.personal.info');
    Route::put('/students/{student}/academic-info', [StudentRecordController::class, 'updateAcademicInfo'])->middleware(['permission:edit student'])->name('students.academic.info');
    Route::put('/students/{student}/address-info', [StudentRecordController::class, 'updateAddressInfo'])->middleware(['permission:edit student'])->name('students.address.info');
    Route::put('/students/{student}/emergency-info', [StudentRecordController::class, 'updateEmergencyInfo'])->middleware(['permission:edit student'])->name('students.emergency.info');

    // User Management
    Route::get('/admin/users', [UserInvitationController::class, 'index'])->middleware(['permission:view users'])->name('admin.users.index');
    Route::get('/admin/users/data', [UserInvitationController::class, 'getAllUsers'])->name('admin.users.data');
    Route::get('/admin/users/analytics', [UserInvitationController::class, 'getAnalytics'])->name('admin.users.analytics');

    // User Management Tabs - Roles and Permissions (must be before generic user routes)
    Route::get('/admin/users/roles', [UserInvitationController::class, 'roles'])->name('admin.users.roles')->middleware('permission:view roles');

    // User CRUD operations (must be before the specific user routes)
    Route::post('/admin/users', [UserInvitationController::class, 'store'])->name('admin.users.store')->middleware('permission:create users');
    Route::get('/admin/users/{user}', [UserInvitationController::class, 'show'])->name('admin.users.show')->middleware('permission:view users');
    Route::put('/admin/users/{user}', [UserInvitationController::class, 'update'])->name('admin.users.update')->middleware('permission:update users');
    Route::delete('/admin/users/{user}', [UserInvitationController::class, 'destroy'])->name('admin.users.destroy')->middleware('permission:delete users');

    // Get programs for user forms
    Route::get('/admin/programs', [UserInvitationController::class, 'getPrograms'])->name('admin.programs');

    // Role Management Routes
    Route::get('/admin/roles/data', [UserInvitationController::class, 'getRolesData'])->name('admin.roles.data');
    Route::get('/admin/roles', [UserInvitationController::class, 'getAllRoles'])->name('admin.roles.index');
    Route::get('/admin/permissions', [UserInvitationController::class, 'getAllPermissions'])->name('admin.permissions.index');
    Route::get('/admin/roles/{role}', [UserInvitationController::class, 'getRole'])->name('admin.roles.show')->middleware('permission:view roles');
    Route::post('/admin/roles', [UserInvitationController::class, 'createRole'])->name('admin.roles.create')->middleware('permission:create roles');
    Route::put('/admin/roles/{role}', [UserInvitationController::class, 'updateRole'])->name('admin.roles.update')->middleware('permission:edit roles');
    Route::post('/admin/roles/{role}/permissions', [UserInvitationController::class, 'updateRolePermissions'])->name('admin.roles.permissions')->middleware('permission:edit roles');
    Route::delete('/admin/roles/{role}', [UserInvitationController::class, 'deleteRole'])->name('admin.roles.delete')->middleware('permission:delete roles');


    // News Management
    Route::get('/admin/news', [NewsController::class, 'index'])->name('admin.news.index');
    Route::get('/admin/getNews', [NewsController::class, 'getNews']);
    Route::get('/admin/news/{news}', [NewsController::class, 'show']);

    // Discount Management
    Route::get('/getDiscounts', [DiscountController::class, 'getDiscounts'])->name('admin.discounts.get');
    Route::post('/admin/discounts', [DiscountController::class, 'store'])->middleware(['permission:create discount', 'throttle:10,1'])->name('admin.discounts.store');
    Route::put('/admin/discounts/{discount}', [DiscountController::class, 'update'])->middleware(['permission:update discount'])->name('admin.discounts.update');
    Route::delete('/admin/discounts/{discount}', [DiscountController::class, 'destroy'])->middleware(['permission:delete discount'])->name('admin.discounts.destroy');
    Route::patch('/admin/discounts/{discount}/toggle', [DiscountController::class, 'toggle'])->middleware(['permission:update discount', 'throttle:10,1'])->name('admin.discounts.toggle');
    Route::post('/admin/news', [NewsController::class, 'storeOrUpdate']);
    Route::put('/admin/news/{news}', [NewsController::class, 'update']);
    Route::delete('/admin/news/{news}', [NewsController::class, 'destroy']);

    // School Fees and Invoices
    Route::get('/school-fees', [SchoolFeeController::class, 'index'])->name('school-fees.index')->middleware('permission:view school fees page');
    Route::get('/getSchoolFees', [SchoolFeeController::class, 'getSchoolFees']);

    // Invoices (must come before /school-fees/{id} to avoid route conflict)
    Route::get('/school-fees/invoices', [SchoolFeeController::class, 'index'])->name('school-fees.invoices')->middleware('permission:view invoice records');

    Route::get('/school-fees/payments', [SchoolFeeController::class, 'index'])->name('school-fees.payments')->middleware('permission:view payment history');

    Route::get('/school-fees/discounts', [SchoolFeeController::class, 'index'])->name('school-fees.discounts');

    // School fee show route (must come after specific routes)
    Route::get('/school-fees/{id}', [SchoolFeeController::class, 'show'])->middleware('permission:view school fees');

    Route::post('/school-fees', [SchoolFeeController::class, 'store'])->middleware(['permission:create school fees', 'throttle:30,1']); // 30 requests per minute
    Route::put('/school-fees/{id}', [SchoolFeeController::class, 'update'])->middleware(['permission:update school fees', 'throttle:30,1']); // 30 requests per minute
    Route::delete('/school-fees/{id}', [SchoolFeeController::class, 'destroy'])->middleware(['permission:delete school fees', 'throttle:30,1']); // 30 requests per minute
    Route::get('/getInvoices', [InvoiceController::class, 'getInvoices']);
    Route::get('/invoice/{id}', [InvoiceController::class, 'show'])->middleware(['permission:view invoice records']);
    Route::post('/invoice', [InvoiceController::class, 'store'])->middleware(['permission:create invoice', 'throttle:20,1']); // 20 requests per minute
    Route::get('/getInvoiceItems/{invoice}', [InvoiceItemController::class, 'getInvoiceItems']);
    Route::delete('/invoice/{invoice}/item/{item}', [InvoiceController::class, 'removeInvoiceItem'])->name('invoice.item.remove')
        ->middleware(['permission:remove invoice item', 'throttle:10,1']);

    // Invoice History
    Route::get('/getPayments', [InvoicePaymentController::class, 'getPayments']);
    Route::get('/getInvoiceHistory', [InvoiceController::class, 'getInvoiceHistory']);


    Route::post('/invoice/{invoice}/payments', [InvoicePaymentController::class, 'store'])
        ->name('invoice.payments.store')
        ->middleware('permission:record payment');

    // Payment Plans
    Route::post('/invoice/{invoice}/payment-plan', [App\Http\Controllers\PaymentPlanController::class, 'store'])->name('invoice.payment-plan.store');
    Route::get('/invoice/{invoice}/payment-plan', [App\Http\Controllers\PaymentPlanController::class, 'show'])->name('invoice.payment-plan.show');
    Route::post('/payment-plan/calculate', [App\Http\Controllers\PaymentPlanController::class, 'calculate'])->name('payment-plan.calculate');
    Route::put('/payment-plan/{paymentPlan}', [App\Http\Controllers\PaymentPlanController::class, 'update'])->name('payment-plan.update');
    Route::delete('/payment-plan/{paymentPlan}', [App\Http\Controllers\PaymentPlanController::class, 'destroy'])->name('payment-plan.destroy');
});

/*
|--------------------------------------------------------------------------
| HEAD TEACHER ROUTES
|--------------------------------------------------------------------------
| Routes accessible only to head_teacher role
*/

Route::middleware(['auth', 'pin.security', 'exclude.applicant'])->group(function () {
    Route::get('/head-teacher/dashboard', [App\Http\Controllers\HeadTeacherController::class, 'dashboard'])->name('head-teacher.dashboard');
    Route::get('/getTeachers', [App\Http\Controllers\HeadTeacherController::class, 'getTeachers']);
});

/*
|--------------------------------------------------------------------------
| TEACHER ROUTES
|--------------------------------------------------------------------------
| Routes accessible only to teacher role
*/

Route::middleware(['auth', 'pin.security', 'exclude.applicant'])->group(function () {
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

Route::middleware(['auth', 'pin.security', 'exclude.applicant'])->group(function () {

    Route::get('/getAllSections', [SectionController::class, 'getAllSections']);

    Route::get('/sections', [SectionController::class, 'index'])->middleware(['permission:view sections'])->name('sections.index');
    Route::post('/section', [SectionController::class, 'store'])->middleware(['permission:create section'])->name('sections.store');
    Route::get('/section/{section}', [SectionController::class, 'show'])->middleware(['permission:view section'])->name('sections.show');
    Route::post('/section/{section}', [SectionController::class, 'update'])->middleware(['permission:edit section'])->name('sections.update');
    Route::delete('/sections/{section}', [SectionController::class, 'destroy'])->middleware(['permission:delete section'])->name('sections.destroy');

    // Section Subject Management
    Route::post('/assignSubject/{section}', [SectionController::class, 'assignSubject'])->middleware(['permission:assign subject to a section'])->name('sections.assign-subject');
    Route::post('/updateSubject/{section}', [SectionController::class, 'updateSubject'])->middleware(['permission:edit subject assigned to a section'])->name('sections.update-subject');
    Route::post('/removeSubjectFromSection/{section}', [SectionController::class, 'removeSubjectFromSection'])->middleware(['permission:remove assigned subject to a section'])->name('sections.remove-subject');
    Route::post('/checkScheduleConflict/{section}', [SectionController::class, 'checkScheduleConflict'])->name('sections.check-conflict');
});

/*
|--------------------------------------------------------------------------
| PROGRAM MANAGEMENT ROUTES
|--------------------------------------------------------------------------
| Routes for managing programs (accessible to admin)
*/

Route::middleware(['auth', 'pin.security', 'exclude.applicant'])->group(function () {
    Route::get('/programs', [ProgramController::class, 'index'])->name('programs.index');
    Route::post('/programs/{tracks}', [ProgramController::class, 'store'])
        ->middleware(['permission:create strand', 'throttle:30,1']) // 30 requests per minute
        ->name('programs.store');
    Route::get('/program/{program}', [ProgramController::class, 'show'])->name('program.show');
    Route::post('/updateProgram/{program}', [ProgramController::class, 'update'])->middleware(['permission:edit strand'])->name('program.update');
    Route::delete('/program/{program}', [ProgramController::class, 'destroy'])->middleware(['permission:delete strand'])->name('program.destroy');
    Route::get('/program/{program}/sections', [ProgramController::class, 'show'])->middleware(['permission:view sections'])->name('program.sections');
    Route::get('/program/{program}/subjects', [ProgramController::class, 'show'])->middleware(['permission:view subjects'])->name('program.subjects');
    Route::get('/program/{program}/faculty', [ProgramController::class, 'show'])->middleware(['permission:view subjects'])->name('program.faculty');
    Route::get('/getPrograms', [ProgramController::class, 'getPrograms']);
    Route::get('/getTeacchers', [ProgramController::class, 'getTeachers']);
});

/*
|--------------------------------------------------------------------------
| TRACK MANAGEMENT ROUTES
|--------------------------------------------------------------------------
| Routes for managing tracks (accessible to admin)
*/

Route::middleware(['auth', 'pin.security', 'exclude.applicant'])->group(function () {
    Route::get('/tracks', [TrackController::class, 'index'])->name('tracks.index')->middleware(['permission:view track']);
    Route::post('/tracks', [TrackController::class, 'store'])
        ->middleware(['permission:create track', 'throttle:30,1']) // 30 requests per minute
        ->name('tracks.store');
    Route::get('/tracks/{track}', [TrackController::class, 'show'])->name('tracks.show');
    Route::put('/tracks/{track}', [TrackController::class, 'update'])->middleware(['permission:edit track'])->name('tracks.update');
    Route::delete('/tracks/{track}', [TrackController::class, 'destroy'])->middleware(['permission:delete track'])->name('tracks.destroy');
    Route::get('/tracks/{track}/programs', [TrackController::class, 'getPrograms'])->name('tracks.programs');
});

/*
|--------------------------------------------------------------------------
| SUBJECT MANAGEMENT ROUTES
|--------------------------------------------------------------------------
| Routes for managing subjects (accessible to admin and head_teacher)
*/

Route::middleware(['auth', 'pin.security', 'exclude.applicant'])->group(function () {
    Route::get('/subjects', [SubjectController::class, 'index'])->middleware(['permission:view subjects'])->name('subjects.index');
    Route::get('/getAllSubjects', [SubjectController::class, 'getAllSubjects'])->middleware(['permission:view subjects'])->name('subjects.getAllSubjects');
    Route::post('/subjects', [SubjectController::class, 'store'])
        ->middleware(['permission:create subject', 'throttle:30,1']) // 30 requests per minute
        ->name('subjects.store');
    Route::get('/subjects/auto-assign', [SubjectController::class, 'getAutoAssignSubjects'])->name('subjects.auto-assign');
    Route::get('/subjects/{subject}', [SubjectController::class, 'show'])->name('subjects.show');
    Route::put('/subjects/{subject}', [SubjectController::class, 'update'])
        ->middleware(['permission:edit subject', 'throttle:30,1']) // 30 requests per minute
        ->name('subjects.update');
    Route::delete('/subjects/{subject}', [SubjectController::class, 'destroy'])
        ->middleware(['permission:edit subject', 'throttle:30,1']) // 30 requests per minute
        ->name('subjects.destroy');
    Route::get('/getSubjects/{program}', [SubjectController::class, 'getSubjects']);
});

/*
|--------------------------------------------------------------------------
| AJAX/API ROUTES
|--------------------------------------------------------------------------
| Routes for AJAX requests and API endpoints
*/

Route::middleware(['auth', 'pin.security', 'exclude.applicant'])->group(function () {
    // Section-related AJAX routes
    Route::get('/getSections/{program}', [SectionController::class, 'getSections']);
    Route::get('/getStudents/{section}', [SectionController::class, 'getStudents']);
    Route::get('/getAvailableStudents/{section}', [SectionController::class, 'getAvailableStudents']);
    Route::get('/getAvailableSubjects/{section}', [SectionController::class, 'getAvailableSubjects']);
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


