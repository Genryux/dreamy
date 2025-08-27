<?php

use App\Http\Controllers\AcademicTermController;
use App\Http\Controllers\AdmissionDashboardController;
use App\Http\Controllers\ApplicantsController;
use App\Http\Controllers\ApplicationFormController;
use App\Http\Controllers\DocumentsController;
use App\Http\Controllers\DocumentsSubmissionController;
use App\Http\Controllers\EnrollmentPeriodController;
use App\Http\Controllers\InterviewController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\StudentRecordController;
use App\Http\Controllers\StudentsController;
use App\Http\Controllers\SubjectController;
use App\Models\Applicants;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});


Route::get('/test', function () {
    return view('test');
});

Route::post('/test/{id}', [StudentRecordController::class, 'store']);

Route::get('/portal/login', [SessionController::class, 'create'])
    ->name('login')->middleware('guest');

Route::get('/portal/register', [RegistrationController::class, 'create'])
    ->name('register')->middleware('guest');

// Route::get('/admin', function () {

//     $users = User::all();

//     return view('user-admin.dashboard', ['users' => $users]);
// })->name('admin');


//admin
Route::get('/admin', [ApplicationFormController::class, 'index'])->name('admin');

//pending
Route::get('/pending-applications', [ApplicationFormController::class, 'pending'])->name('pending');
Route::get('/pending-application/form-details/{id}', [ApplicationFormController::class, 'show'])->name('pending.details');

//selected
Route::get('/selected-applications', [ApplicationFormController::class, 'selected'])->name('selected');
Route::get('/selected-application/interview-details/{id}', [InterviewController::class, 'show'])->name('selected.details');

Route::get('/admission/application-form', [ApplicationFormController::class, 'create'])->name('admission.form.get');
Route::post('/admission/application-form', [ApplicationFormController::class, 'store'])->name('admission.form.post');

//academic terms
Route::post('/academic-terms', [AcademicTermController::class, 'store'])->name('academic-terms.post');

//enrollment period
Route::post('/enrollment-period', [EnrollmentPeriodController::class, 'store'])->name('enrollment-period.post');
Route::patch('/enrollment-period/{id}', [EnrollmentPeriodController::class, 'update'])->name('enrollment-period.patch');


//interview
Route::post('/set-interview/{id}', [InterviewController::class, 'store'])->name('interview.post');
Route::patch('/set-interview/{id}', [InterviewController::class, 'update'])->name('interview.patch');

// pending documents
Route::get('/pending-documents', [ApplicationFormController::class, 'pendingDocuments'])->name('documents');
Route::get('/pending-documents/document-details/{applicant}', [DocumentsSubmissionController::class, 'index'])->name('documents');



// required docs
Route::get('/pending-documents/document-list', [DocumentsController::class, 'index'])->name('documents.index');



Route::post('/required-docs', [DocumentsController::class, 'store'])->name('documents.store');

// document submission

Route::post('/submit-document', [DocumentsSubmissionController::class, 'store'])->name('documents.store');
Route::patch('/submit-document/{applicant}', [DocumentsSubmissionController::class, 'update']);

Route::patch('/applicants/{applicants}', [ApplicantsController::class, 'update']);


//enrolled students
Route::get('/enrolled-students', [StudentsController::class, 'index'])->name('students.index');

Route::get('/users', [StudentsController::class, 'getUsers']);

Route::get('/student/{studentRecord}', [StudentRecordController::class, 'show']);

//enrolled students


Route::post('/students/import', [StudentRecordController::class, 'import']);

Route::get('/students/export/excel', [StudentRecordController::class, 'exportExcel'])->name('students.export.excel');


Route::post('/student-record/{id}', [StudentRecordController::class, 'store']);

Route::post('/students/{id}', [StudentRecordController::class, 'store']);


Route::get('/student', function () {
    return view('user-student.student');
})->name('student')->middleware('auth');


Route::get('/programs', [ProgramController::class, 'index']);
Route::get('/program/{program}', [ProgramController::class, 'show'])->name('program.show');

Route::get('/getPrograms', [ProgramController::class, 'getPrograms']);

Route::get('/sections', [SectionController::class, 'index']);
Route::get('/getSections', [SectionController::class, 'getSections']);
Route::get('/getStudents/{section}', [SectionController::class, 'getStudents']);

Route::get('/section/{section}', [SectionController::class, 'show']);

Route::post('/assign-section/{section}', [StudentsController::class, 'assignSection']);

Route::post('/section/{section}', [SectionController::class, 'update']);


Route::get('/subjects', [SubjectController::class, 'index']);

//admission

// Route::get('/admission', function () {

//     //$activeEnrollmentPeriod = EnrollmentPeriod::whereIn('status', ['Ongoing','Paused'])->first();

//     return view('user-applicant.dashboard', [
//         'activeEnrollmentPeriod' => $activeEnrollmentPeriod
//     ]);

// })->name('admission.dashboard');

Route::get('/admission', [AdmissionDashboardController::class, 'index'])->name('admission.dashboard')->middleware('auth');


Route::get('/admission/status', function () {

    $applicant = Applicants::where('user_id', Auth::user()->id)->first();

    $application_status = $applicant->application_status ?? '';

    return view('user-applicant.status', [
        'status' => $application_status
    ]);
})->name('status')->middleware('auth');


//dashboard





Route::post('/session', [SessionController::class, 'store']);

Route::post('/register', [RegistrationController::class, 'store']);



Route::delete('/logout', [SessionController::class, 'destroy']);
