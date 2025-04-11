<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\ApplicationFormController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\SessionController;
use App\Models\Applicant;
use App\Models\ApplicationForm;
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

Route::get('/student', function () {
    return view('layouts.student');
})->name('student')->middleware('auth');

Route::get('/admin', [ApplicationFormController::class, 'index'])->name('admin');


//admission

Route::get('/admission', function () {
    return view('user-applicant.dashboard');
})->name('admission.dashboard');


Route::get('/admission/status', function () {

    $applicant = Applicant::where('user_id', Auth::user()->id)->first();

    $application_status = $applicant->application_status;

    return view('user-applicant.status', [
        'status' => $application_status
    ]);


})->name('status')->middleware('auth');


Route::get('/pending-application/form-details/{id}', [ApplicationFormController::class, 'show'])->name('pending.form-details');

Route::get('/pending-applications', [ApplicationFormController::class, 'pending'])->name('pending');

Route::get('/admission/application-form', [ApplicationFormController::class, 'create'])->name('admission.form.get');

Route::post('/admission/application-form', [ApplicationFormController::class, 'store'])->name('admission.form.post');




Route::post('/session', [SessionController::class, 'store']);

Route::post('/register', [RegistrationController::class, 'store']);



Route::delete('/logout', [SessionController::class, 'destroy']);