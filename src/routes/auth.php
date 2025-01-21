<?php

use App\Http\Controllers\PasswordResetController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterUserController;


Route::post('/register', [RegisterUserController::class, 'store']);

Route::post('/login', [RegisterUserController::class, 'login'])
    ->name('login');

Route::get('/email/verify/{id}/{hash}', [RegisterUserController::class, 'verify'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::get('/forgot-password', [PasswordResetController::class, 'showForgotPassword'])
    ->middleware('guest')
    ->name('password.request');

Route::post('/forgot-password', [PasswordResetController::class, 'store'])
    ->middleware('guest')
    ->name('password.email');

Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetPassword'])
    ->middleware('guest')
    ->name('password.reset');

Route::post('/reset-password', [PasswordResetController::class, 'storeNewPassword'])
    ->middleware('guest')
    ->name('password.update');