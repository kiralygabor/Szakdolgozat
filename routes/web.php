<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\FacebookController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PagesController::class, 'index'])->name('home');

// View Routes (Static/Dashboard)
Route::controller(PagesController::class)->group(function () {
    Route::get('index', 'index')->name('index');
    Route::get('category', 'category')->name('category');
    Route::get('my-tasks', 'myTasks')->name('my-tasks')->middleware('auth');
    Route::get('notifications', 'notifications')->name('notifications')->middleware('auth');
    Route::get('profile/{id}', 'publicProfile')->name('public-profile');
    Route::get('api/cities', 'searchCities')->name('api.cities.search');
});

Route::get('tasks', [TaskController::class, 'index'])->name('tasks');
Route::get('tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');

// Information Pages
Route::view('/howitworks', 'pages.howitworks')->name('howitworks');
Route::view('/terms', 'pages.terms-and-conditions')->name('terms');
Route::view('/guidelines', 'pages.community-guidelines')->name('guidelines');
Route::view('/privacy', 'pages.privacy-policy')->name('privacy');
Route::view('/help-faq', 'pages.help-faq')->name('help-faq');
Route::view('/contact-support', 'pages.contact-support')->name('contact-support');

// Profile Management
Route::middleware('auth')->prefix('profile')->controller(ProfileController::class)->group(function () {
    Route::get('/', 'edit')->name('profile');
    Route::put('/', 'updateProfile')->name('profile.update');
    Route::put('/notifications', 'updateNotifications')->name('profile.notifications.update');
    Route::delete('/', 'destroy')->name('profile.delete');
    Route::post('/settings', 'updateSettings')->name('profile.settings.update');
    Route::post('/send-digest', 'sendManualDigest')->name('profile.send-digest');
});
Route::post('profile/{id}/review', [PagesController::class, 'storeReview'])->name('public-profile.review')->middleware('auth');

// Task Lifecycle
Route::middleware('auth')->group(function () {
    Route::middleware(\App\Http\Middleware\EnsureProfileComplete::class)->group(function () {
        Route::post('tasks', [TaskController::class, 'store'])->name('tasks.store')->middleware('throttle:6,1');
        Route::post('tasks/{task}/offers', [OfferController::class, 'store'])->name('tasks.offers.store')->middleware('throttle:10,1');
        Route::post('tasks/{task}/accept-direct', [OfferController::class, 'acceptDirect'])->name('tasks.accept-direct');
    });

    Route::delete('tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
    Route::put('tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::post('tasks/{task}/complete', [TaskController::class, 'complete'])->name('tasks.complete');
    Route::post('tasks/{task}/revert-complete', [TaskController::class, 'revertCompletion'])->name('tasks.revert-complete');
    Route::post('tasks/{task}/review', [TaskController::class, 'reviewEmployer'])->name('tasks.reviewEmployer');

    Route::delete('tasks/{task}/offers', [OfferController::class, 'destroy'])->name('tasks.offers.destroy');
    Route::post('offers/{offer}/accept', [OfferController::class, 'accept'])->name('offers.accept');
});

// Notifications
Route::post('notifications/mark-read', [PagesController::class, 'markAllRead'])->name('notifications.mark-read')->middleware('auth');

// Messaging
Route::middleware('auth')->prefix('messages')->controller(MessageController::class)->group(function () {
    Route::get('/', 'index')->name('messages');
    Route::get('/{conversation}', 'show')->name('conversations.show');
    Route::post('/{conversation}/messages', 'store')->name('conversations.messages.store');
    Route::delete('/{conversation}/messages/{message}', 'destroy')->name('conversations.messages.destroy');
    Route::get('/{conversation}/check', 'checkNewMessages')->name('conversations.messages.check');
});

// Reporting
Route::post('reports', [ReportController::class, 'store'])->name('reports.store')->middleware(['auth', 'throttle:5,1']);
Route::post('user-reports', [UserReportController::class, 'store'])->name('user-reports.store')->middleware(['auth', 'throttle:5,1']);

// Legacy Redirects
Route::get('advertisements', fn() => redirect()->route('tasks'));

Route::get('post-task', [TaskController::class, 'create'])->name('post-task')->middleware('auth');

// Language Switching
Route::post('language/{locale}', [\App\Http\Controllers\LanguageController::class, 'switch'])->name('language.switch');

// Authentication
Route::get('login', [AuthController::class, 'index'])->name('login');
Route::post('post-login', [AuthController::class, 'postLogin'])->name('login.post')->middleware('throttle:5,1');
Route::get('registration', [AuthController::class, 'registration'])->name('register');
Route::post('post-registration', [AuthController::class, 'postRegistration'])->name('register.post');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Social Authentication
Route::get('login/google', [GoogleController::class, 'redirectToGoogle'])->name('login.google');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);
Route::get('login/facebook', [FacebookController::class, 'redirectToFacebook'])->name('login.facebook');
Route::get('auth/facebook/callback', [FacebookController::class, 'handleFacebookCallback']);

// Email Verification
Route::get('/user/verify/{token}', [AuthController::class, 'verifyUser']);
Route::get('registration_settings', [AuthController::class, 'registrationSettings'])->name('registeration_settings');
Route::post('post-registration_settings', [AuthController::class, 'postRegistrationSettings'])->name('registration_settings.post');

Route::get('/verify-code', [AuthController::class, 'showVerifyCodeForm'])->name('verify.code.form');
Route::post('/verify-code', [AuthController::class, 'verifyCode'])->name('verify.code')->middleware('throttle:5,1');
Route::get('/resend-code', [AuthController::class, 'resendCode'])->name('resend.code')->middleware('throttle:3,1');

// Password Reset
Route::get('forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email')->middleware('throttle:3,1');
Route::get('reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('reset-password', [AuthController::class, 'resetPassword'])->name('password.update');