<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/tite', function () {
    return response()->json(['message' => 'API routes are working']);
});

// Public auth endpoints
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum', 'student.only'])->group(function () {
	// Authenticated user helpers
	Route::post('/auth/logout', [AuthController::class, 'logout']);
	Route::get('/auth/user', [AuthController::class, 'user']);
	Route::post('/auth/change-password', [AuthController::class, 'changePassword']);
	
	// PIN management
	Route::post('/auth/setup-pin', [AuthController::class, 'setupPin']);
	Route::post('/auth/verify-pin', [AuthController::class, 'verifyPin']);
	Route::post('/auth/change-pin', [AuthController::class, 'changePin']);
	Route::post('/auth/toggle-pin', [AuthController::class, 'togglePin']);
	Route::post('/auth/change-email', [AuthController::class, 'changeEmail']);

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

	// Student Profile - NEW
	Route::put('/profile/personal-info', [StudentProfileController::class, 'updatePersonalInfo']);
});
