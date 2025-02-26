<?php

use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\SessionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/portal/login', [SessionController::class, 'create'])
    ->name('login');

Route::get('/portal/register', [RegistrationController::class, 'create'])
    ->name('register');


Route::post('/session', [SessionController::class, 'store']);

Route::post('/register', [RegistrationController::class, 'store']);



Route::delete('/logout', [SessionController::class, 'destroy']);