<?php

use App\Http\Controllers\ApplicationFormController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\SessionController;
use App\Models\ApplicationForm;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/portal/login', [SessionController::class, 'create'])
    ->name('login')->middleware('guest');

Route::get('/portal/register', [RegistrationController::class, 'create'])
    ->name('register')->middleware('guest');

Route::get('/admin', function () {

    $users = User::all();

    return view('user-admin.dashboard', ['users' => $users]);
})->name('admin');

Route::get('/student', function () {
    return view('layouts.student');
})->name('student')->middleware('auth');


//admission

Route::get('/admission', function () {
    return view('user-applicant.dashboard');
})->name('admission');

Route::get('/admission/application-form', function() {
    return view('user-applicant.application-form');
})->name('admission');

Route::get('/admission/status', function () {
    return view('user-applicant.status');
})->name('status')->middleware('auth');

Route::post('/admission/application-form', [ApplicationFormController::class, 'store'])->name('admission');

Route::post('/session', [SessionController::class, 'store']);

Route::post('/register', [RegistrationController::class, 'store']);



Route::delete('/logout', [SessionController::class, 'destroy']);