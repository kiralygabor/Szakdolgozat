<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('mainpage', [PagesController::class, 'mainpage'])->name('manpage');
Route::get('index', [PagesController::class, 'index'])->name('index');
Route::get('profile', [PagesController::class, 'profile'])->name('profile');



Route::get('login', [AuthController::class, 'index'])->name('login');
Route::post('post-login', [AuthController::class, 'postLogin'])->name('login.post'); 
Route::get('registration', [AuthController::class, 'registration'])->name('register');
Route::post('post-registration', [AuthController::class, 'postRegistration'])->name('register.post'); 
 
Route::get('logout', [AuthController::class, 'logout'])->name('logout');

Route::get('login/google', [GoogleController::class, 'redirectToGoogle'])->name('login.google');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

Route::get('/user/verify/{token}', [AuthController::class, 'verifyUser']);
Route::get('registration_settings', [AuthController::class, 'registration_settings'])->name('registeration_settings');
Route::post('post-registration_settings', [AuthController::class, 'postRegistrationSettings'])->name('registration_settings.post');



// Forgot Password Form
Route::get('forgot-password', [AuthController::class, 'showForgotPasswordForm'])
    ->name('password.request');

// Send Reset Link Email
Route::post('forgot-password', [AuthController::class, 'sendResetLinkEmail'])
    ->name('password.email');

// Reset Password Form (from email link)
Route::get('reset-password/{token}', [AuthController::class, 'showResetForm'])
    ->name('password.reset');

// Handle Reset Password Submission
Route::post('reset-password', [AuthController::class, 'resetPassword'])
    ->name('password.update');


Route::get('/verify-code', [AuthController::class, 'showVerifyCodeForm'])->name('verify.code.form');
Route::post('/verify-code', [AuthController::class, 'verifyCode'])->name('verify.code');
Route::get('/resend-code', [AuthController::class, 'resendCode'])->name('resend.code');
