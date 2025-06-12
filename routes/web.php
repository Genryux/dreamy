<?php

use App\Http\Controllers\AcademicTermController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdmissionDashboardController;
use App\Http\Controllers\ApplicationFormController;
use App\Http\Controllers\EnrollmentPeriodController;
use App\Http\Controllers\InterviewController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\SessionController;
use App\Models\Applicant;
use App\Models\EnrollmentPeriod;
use App\Models\Interview;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/portal/login', [SessionController::class, 'create'])
    ->name('login')->middleware('guest');

Route::get('/portal/register', [RegistrationController::class, 'create'])
    ->name('register')->middleware('guest');

// Route::get('/admin', function () {

//     $users = User::all();

//     return view('user-admin.dashboard', ['users' => $users]);
// })->name('admin');


//admin
Route::get('/admin', [AdminDashboardController::class, 'index'])->name('admin');

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



Route::get('/student', function () {
    return view('layouts.student');
})->name('student')->middleware('auth');




//admission

// Route::get('/admission', function () {

//     //$activeEnrollmentPeriod = EnrollmentPeriod::whereIn('status', ['Ongoing','Paused'])->first();

//     return view('user-applicant.dashboard', [
//         'activeEnrollmentPeriod' => $activeEnrollmentPeriod
//     ]);

// })->name('admission.dashboard');

Route::get('/admission', [AdmissionDashboardController::class, 'index'])->name('admission.dashboard');


Route::get('/admission/status', function () {

    $applicant = Applicant::where('user_id', Auth::user()->id)->first();

    $application_status = $applicant->application_status ?? '';

    return view('user-applicant.status', [
        'status' => $application_status
    ]);


})->name('status')->middleware('auth');


//dashboard





Route::post('/session', [SessionController::class, 'store']);

Route::post('/register', [RegistrationController::class, 'store']);



Route::delete('/logout', [SessionController::class, 'destroy']);