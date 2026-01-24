<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;

use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\ProgramsController;
use App\Http\Controllers\Api\SectionsController;
use App\Http\Controllers\Api\SubjectsController;
use App\Http\Controllers\Api\StudentDocumentsController;
use App\Http\Controllers\Api\DocumentSubmissionsController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SectionSubjectsController;
use App\Http\Controllers\Api\StudentEnrollmentController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\AcademicController;
use App\Http\Controllers\Api\FinancialController;
use App\Http\Controllers\Api\StudentProfileController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\TeacherAppController;

Route::get('/tite', function () {
    return response()->json(['message' => 'API routes are working']);
});

// Public auth endpoints
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

// Broadcasting authentication for mobile app
Route::post('/broadcasting/auth', function (Request $request) {
    return Broadcast::auth($request);
})->middleware('auth:sanctum');

// ===================================
// SHARED AUTH ROUTES (Students & Teachers)
// ===================================
Route::middleware(['auth:sanctum'])->group(function () {
    // Authenticated user helpers (accessible by both students and teachers)
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/user', [AuthController::class, 'user']);
    Route::post('/auth/change-password', [AuthController::class, 'changePassword']);
    
    // PIN management (accessible by both students and teachers)
    Route::post('/auth/setup-pin', [AuthController::class, 'setupPin']);
    Route::post('/auth/verify-pin', [AuthController::class, 'verifyPin']);
    Route::post('/auth/change-pin', [AuthController::class, 'changePin']);
    Route::post('/auth/toggle-pin', [AuthController::class, 'togglePin']);
    Route::post('/auth/change-email', [AuthController::class, 'changeEmail']);
});

// ===================================
// STUDENT MOBILE APP ROUTES
// ===================================
Route::middleware(['auth:sanctum', 'student.only'])->group(function () {
	// Student self data (mobile app can pass their student id)
	Route::get('/students/{student}', [StudentController::class, 'show']);
	Route::patch('/students/{student}', [StudentController::class, 'update']);

	// Academic info (read-only)
	Route::get('/programs/{program}', [ProgramsController::class, 'show']);
	Route::get('/sections/{section}', [SectionsController::class, 'show']);
	Route::get('/subjects', [SubjectsController::class, 'index']);
	Route::get('/subjects/{subject}', [SubjectsController::class, 'show']);

	// Section Subjects (subject scheduling and teacher assignments)
	Route::get('/section-subjects', [SectionSubjectsController::class, 'index']);
	Route::get('/section-subjects/{sectionSubject}', [SectionSubjectsController::class, 'show']);

	// Documents (read-only lists for mobile consumption)
	Route::get('/student-documents', [StudentDocumentsController::class, 'index']);
	Route::get('/student-documents/{studentDocument}', [StudentDocumentsController::class, 'show']);
	Route::get('/document-submissions', [DocumentSubmissionsController::class, 'index']);
	Route::get('/document-submissions/{documentSubmission}', [DocumentSubmissionsController::class, 'show']);

	// Student Enrollments (per-term enrollment management)
	Route::get('/enrollments/current', [StudentEnrollmentController::class, 'current']);
	Route::post('/enrollments/{enrollment}/confirm', [StudentEnrollmentController::class, 'confirm']);

	// Dashboard (News & Announcements)
	Route::get('/dashboard', [DashboardController::class, 'index']);
	Route::get('/news-announcements', [DashboardController::class, 'newsAndAnnouncements']);
	Route::get('/news/{id}', [DashboardController::class, 'newsDetails']);

	// Financial (Invoices & Payments) - View Only
	Route::get('/invoices', [InvoiceController::class, 'index']);
	Route::get('/invoices/{id}', [InvoiceController::class, 'show']);
	Route::get('/payments', [InvoiceController::class, 'payments']);
	Route::get('/financial-summary', [InvoiceController::class, 'summary']);

	// Academic Information - NEW
	Route::get('/academic/section', [AcademicController::class, 'getCurrentSection']);
	Route::get('/academic/subjects', [AcademicController::class, 'getCurrentSubjects']);
	Route::get('/academic/summary', [AcademicController::class, 'getAcademicSummary']);

	// Financial Information - NEW
	Route::get('/financial/invoices', [FinancialController::class, 'getCurrentInvoices']);
	Route::get('/financial/payments', [FinancialController::class, 'getCurrentPaymentHistory']);
	Route::get('/financial/summary', [FinancialController::class, 'getFinancialSummary']);
	Route::get('/financial/terms', [FinancialController::class, 'getAvailableTerms']);
	
	// Payment Plan Selection - Student-Driven
	Route::post('/financial/payment-plan/calculate', [FinancialController::class, 'calculatePaymentPlan']);
	Route::post('/financial/invoice/{invoiceId}/payment-plan/select', [FinancialController::class, 'selectPaymentPlan']);

	// Student Profile - NEW
	Route::put('/profile/personal-info', [StudentProfileController::class, 'updatePersonalInfo']);

	// Notifications - NEW
	Route::get('/notifications', [NotificationController::class, 'index']);
	Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
	Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead']);
	Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount']);
});

// ===================================
// TEACHER MOBILE APP ROUTES
// ===================================
Route::middleware(['auth:sanctum', 'teacher.only'])->prefix('teacher')->group(function () {
	// Teacher Dashboard - Today's schedule & summary
	Route::get('/dashboard', [TeacherAppController::class, 'dashboard']);
	
	// Teacher Classes - Full schedule
	Route::get('/my-classes', [TeacherAppController::class, 'myClasses']);

	// Teacher Advising Sections
	Route::get('/advising-sections', [TeacherAppController::class, 'advisingSections']);
	Route::get('/advising-sections/{section}/students', [TeacherAppController::class, 'getAdvisingSectionStudents']);
	
	// Section Students - Get students enrolled in a specific class
	Route::get('/classes/{sectionSubjectId}/students', [TeacherAppController::class, 'getSectionStudents']);
	
	// Student Details - Get detailed info about a specific student
	Route::get('/classes/{sectionSubjectId}/students/{studentId}', [TeacherAppController::class, 'getStudentDetails']);
	
	// Student Evaluation - Mark student as passed/failed
	Route::post('/classes/{sectionSubjectId}/students/{studentId}/evaluate', [TeacherAppController::class, 'evaluateStudent']);
	
	// Bulk Evaluation - Evaluate multiple students at once
	Route::post('/classes/{sectionSubjectId}/evaluate-bulk', [TeacherAppController::class, 'bulkEvaluateStudents']);

	// Teaching history + remedial controls
	Route::get('/teaching-history', [TeacherAppController::class, 'teachingHistory']);
	Route::get('/student-subjects/{studentSubjectId}', [TeacherAppController::class, 'getStudentSubjectHistory']);
	Route::post('/student-subjects/{studentSubjectId}/remedial', [TeacherAppController::class, 'updateStudentSubjectRemedial']);
	
	// Teacher Profile
	Route::get('/profile', [TeacherAppController::class, 'profile']);
	Route::put('/profile', [TeacherAppController::class, 'updateProfile']);

	// Notifications (shared functionality)
	Route::get('/notifications', [NotificationController::class, 'index']);
	Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
	Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead']);
	Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount']);
});
