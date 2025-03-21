<?php

use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\SessionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/portal/login', [SessionController::class, 'create'])
    ->name('login')->middleware('guest');

Route::get('/portal/register', [RegistrationController::class, 'create'])
    ->name('register')->middleware('guest');

Route::get('/admin', function () {
    return view('layouts.admin');
})->name('admin')->middleware('auth');

Route::get('/student', function () {
    return view('layouts.student');
})->name('student')->middleware('auth');

Route::get('/admission', function () {
    return view('user-applicant.dashboard');
})->name('admission');

Route::get('/admission/status', function () {
    return view('user-applicant.status');
})->name('status')->middleware('auth');


Route::post('/session', [SessionController::class, 'store']);

Route::post('/register', [RegistrationController::class, 'store']);



Route::delete('/logout', [SessionController::class, 'destroy']);