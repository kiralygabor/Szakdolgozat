<?php
 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\FacebookController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdvertisementController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ReportController;
 
use App\Http\Controllers\UserReportController;
 
Route::get('/', function () {
    return view('welcome');
});
 
Route::get('index', [PagesController::class, 'index'])->name('index');
Route::get('profile', [PagesController::class, 'profile'])->name('profile')->middleware('auth');
Route::put('profile', [PagesController::class, 'updateProfile'])->name('profile.update')->middleware('auth');
Route::delete('profile', [PagesController::class, 'deleteProfile'])->name('profile.delete')->middleware('auth');
Route::post('profile/send-digest', [PagesController::class, 'sendManualDigest'])->name('profile.send-digest')->middleware('auth');
Route::get('profile/{id}', [PagesController::class, 'publicProfile'])->name('public-profile');
Route::post('profile/{id}/review', [PagesController::class, 'storeReview'])->name('public-profile.review')->middleware('auth');
Route::get('category', [PagesController::class, 'category'])->name('category');
Route::get('tasks', [PagesController::class, 'tasks'])->name('tasks');
Route::get('/howitworks', function () {return view('pages.howitworks');})->name('howitworks');
Route::get('/terms', function () {return view('pages.terms-and-conditions');})->name('terms');
Route::get('/guidelines', function () {return view('pages.community-guidelines');})->name('guidelines');
Route::get('/privacy', function () {return view('pages.privacy-policy');})->name('privacy');
Route::get('/help-faq', function () {return view('pages.help-faq');})->name('help-faq');
Route::get('/contact-support', function () {return view('pages.contact-support');})->name('contact-support');
Route::get('my-tasks', [PagesController::class, 'myTasks'])->name('my-tasks')->middleware('auth');
Route::get('notifications', [PagesController::class, 'notifications'])->name('notifications')->middleware('auth');
Route::post('notifications/mark-read', [PagesController::class, 'markAllRead'])->name('notifications.mark-read')->middleware('auth');
Route::get('messages', [MessageController::class, 'index'])->name('messages')->middleware('auth');
Route::get('conversations/{conversation}', [MessageController::class, 'show'])->name('conversations.show')->middleware('auth');
Route::post('conversations/{conversation}/messages', [MessageController::class, 'store'])->name('conversations.messages.store')->middleware('auth');
Route::delete('conversations/{conversation}/messages/{message}', [MessageController::class, 'destroy'])->name('conversations.messages.destroy')->middleware('auth');
Route::get('conversations/{conversation}/check', [MessageController::class, 'checkNewMessages'])->name('conversations.messages.check')->middleware('auth');
Route::get('post-task', [PagesController::class, 'postTask'])->name('post-task')->middleware('auth');
Route::post('post-task', [PagesController::class, 'storeTask'])->name('post-task.store')->middleware('auth');
Route::get('tasks/{task}', [PagesController::class, 'showTask'])->name('tasks.show');
Route::post('tasks/{task}/offers', [OfferController::class, 'store'])->name('tasks.offers.store')->middleware('auth');
Route::delete('tasks/{task}/offers', [OfferController::class, 'destroy'])->name('tasks.offers.destroy')->middleware('auth');
Route::post('offers/{offer}/accept', [OfferController::class, 'accept'])->name('offers.accept')->middleware('auth');
Route::post('tasks/{task}/accept-direct', [OfferController::class, 'acceptDirect'])->name('tasks.accept-direct')->middleware('auth');
Route::post('reports', [ReportController::class, 'store'])->name('reports.store')->middleware('auth');
Route::post('user-reports', [UserReportController::class, 'store'])->name('user-reports.store')->middleware('auth');
Route::get('api/cities', [PagesController::class, 'searchCities'])->name('api.cities.search');
 
// Language switching
Route::post('language/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'hu'])) {
        session(['locale' => $locale]);
        app()->setLocale($locale);
        
        if (auth()->check()) {
            auth()->user()->update(['locale' => $locale]);
        }
    }
    return back();
})->name('language.switch');
 
// Advertisement REST endpoints (optional API for CRUD)
// Allow visiting /advertisements (or common misspelling) via GET by redirecting to tasks list
Route::get('advertisements', function () {
    return redirect()->route('tasks');
});
Route::get('advertisiments', function () { // legacy/misspelled path
    return redirect()->route('tasks');
});
Route::post('advertisements', [AdvertisementController::class, 'store'])->name('advertisements.store')->middleware('auth');
Route::match(['put', 'patch'], 'advertisements/{advertisement}', [AdvertisementController::class, 'update'])->name('advertisements.update')->middleware('auth');
Route::delete('advertisements/{advertisement}', [AdvertisementController::class, 'destroy'])->name('advertisements.destroy')->middleware('auth');
Route::post('advertisements/{advertisement}/complete', [AdvertisementController::class, 'complete'])->name('advertisements.complete')->middleware('auth');
 
Route::get('login', [AuthController::class, 'index'])->name('login');
Route::post('post-login', [AuthController::class, 'postLogin'])->name('login.post');
Route::get('registration', [AuthController::class, 'registration'])->name('register');
Route::post('post-registration', [AuthController::class, 'postRegistration'])->name('register.post');
 
Route::match(['get', 'post'], 'logout', [AuthController::class, 'logout'])->name('logout');
 
Route::get('login/google', [GoogleController::class, 'redirectToGoogle'])->name('login.google');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);
 
Route::get('login/facebook', [FacebookController::class, 'redirectToFacebook'])->name('login.facebook');
Route::get('auth/facebook/callback', [FacebookController::class, 'handleFacebookCallback']);
 
Route::get('/user/verify/{token}', [AuthController::class, 'verifyUser']);
Route::get('registration_settings', [AuthController::class, 'registration_settings'])->name('registeration_settings');
Route::post('post-registration_settings', [AuthController::class, 'postRegistrationSettings'])->name('registration_settings.post');
 
 
 
// Forgot Password Form
Route::get('forgot-password', [AuthController::class, 'showForgotPasswordForm'])
    ->name('password.request');
 
// Send Reset Link
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