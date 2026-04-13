@extends('layout')
 
@section('content')
 
<style>
    /* Custom Styling to match the AirTasker Screenshot */
    body {
        background-color: #fff;
        color: #292b32;
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
    }
 
    /* Sidebar Navigation */
    .settings-nav .nav-link {
        color: #545a77;
        font-weight: 500;
        padding: 10px 15px;
        border-radius: 8px;
        transition: all 0.2s;
        text-align: left;
    }
    .settings-nav .nav-link:hover {
        background-color: #f7f9fa;
        color: #0065ff;
    }
    .settings-nav .nav-link.active {
        background-color: #eef7ff;
        color: #0065ff;
        font-weight: 600;
    }
    .settings-nav .fa-fw {
        margin-right: 10px;
        opacity: 0.7;
    }
 
    /* Main Content Headers */
    h1.page-title {
        font-size: 2.5rem;
        font-weight: 800;
        color: #0e164d;
        font-family: sans-serif; /* Use a condensed font if available */
        letter-spacing: -1px;
        margin-bottom: 30px;
    }
 
    h6.section-label {
        font-weight: 700;
        color: #0e164d;
        margin-bottom: 15px;
        font-size: 1.1rem;
    }
 
    /* Custom Input Fields (The gray background look) */
    .custom-input-group label {
        font-weight: 700;
        color: #0e164d;
        margin-bottom: 8px;
        font-size: 0.95rem;
    }
    .form-control-custom {
        background-color: #f1f3f6;
        border: 1px solid transparent;
        border-radius: 6px;
        padding: 12px 15px;
        font-size: 1rem;
        color: #292b32;
        transition: border-color 0.2s;
    }
    .form-control-custom:focus {
        background-color: #fff;
        border-color: #0065ff;
        box-shadow: 0 0 0 3px rgba(0, 101, 255, 0.1);
        outline: none;
    }
 
    /* Buttons */
    .btn-primary-custom {
        background-color: #0065ff;
        border: none;
        border-radius: 50px; /* Pill shape */
        padding: 10px 24px;
        font-weight: 600;
        font-size: 0.95rem;
    }
    .btn-primary-custom:hover {
        background-color: #0052cc;
    }
 
    .btn-light-custom {
        background-color: #f1f8ff;
        color: #0065ff;
        border: none;
        border-radius: 50px;
        padding: 10px 24px;
        font-weight: 600;
        font-size: 0.95rem;
    }
    .btn-light-custom:hover {
        background-color: #e1efff;
        color: #0052cc;
    }
 
    /* Avatar Section */
    .avatar-circle {
        width: 80px;
        height: 80px;
        background-color: #e1efff;
        color: #0065ff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        flex-shrink: 0;
    }
 
    /* Verification Bar */
    .verification-bar {
        font-size: 0.75rem;
        color: #6c757d;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 5px;
    }
    /* Progress Bar */
    .progress-custom {
        height: 6px;
        background-color: #e9ecef;
        border-radius: 3px;
    }
    .password-toggle {
        cursor: pointer;
        color: #777;
        z-index: 10;
    }
 
    /* High Contrast Mode for Profile Page */
    .high-contrast .btn-primary-custom {
        background-color: #000000 !important;
        color: #ffffff !important;
        border: 2px solid #000000 !important;
    }
 
    .high-contrast .btn-primary-custom:hover {
        background-color: #ffffff !important;
        color: #000000 !important;
    }
 
    .high-contrast .settings-nav .nav-link.active {
        background-color: #000000 !important;
        color: #ffffff !important;
        border: 2px solid #000000 !important;
    }
 
    .high-contrast .settings-nav .nav-link.active i,
    .high-contrast .settings-nav .nav-link.active svg {
        color: #ffffff !important;
    }
 
    .high-contrast .settings-nav .nav-link:hover:not(.active) {
        background-color: #000000 !important;
        color: #ffffff !important;
    }
 
    .high-contrast .settings-nav .nav-link:hover:not(.active) i,
    .high-contrast .settings-nav .nav-link:hover:not(.active) svg {
        color: #ffffff !important;
    }
 
    .high-contrast .page-title,
    .high-contrast .section-label,
    .high-contrast .custom-input-group label,
    .high-contrast .verification-bar {
        color: #000000 !important;
    }
 
    .high-contrast .form-control-custom {
        background-color: #ffffff !important;
        border: 2px solid #000000 !important;
        color: #000000 !important;
    }
 
    .high-contrast .bg-red-50 {
        background-color: #ffffff !important;
        border: 4px solid #000000 !important;
        color: #000000 !important;
    }
 
    .high-contrast .bg-red-50 *,
    .high-contrast .text-red-700,
    .high-contrast .text-red-600 {
        color: #000000 !important;
        opacity: 1 !important;
    }
 
    .high-contrast .btn-danger {
        background-color: #000000 !important;
        color: #ffffff !important;
        border: 2px solid #000000 !important;
        opacity: 1 !important;
    }
   
    .high-contrast input[type="checkbox"] {
        border: 3px solid #000000 !important;
        width: 1.5rem !important;
        height: 1.5rem !important;
    }

    /* Account Settings Dark Mode Fixes */
    html.dark body { background-color: #0f172a !important; color: #f1f5f9 !important; }
    html.dark h1.page-title, 
    html.dark h6.section-label,
    html.dark .custom-input-group label { color: #f8fafc !important; }
    
    html.dark .settings-nav .nav-link { color: #94a3b8 !important; }
    html.dark .settings-nav .nav-link:hover { background-color: #1e293b !important; color: #60a5fa !important; }
    html.dark .settings-nav .nav-link.active { background-color: #1e3a8a !important; color: #60a5fa !important; }
    
    html.dark .form-control-custom { background-color: #1e293b !important; border-color: #334155 !important; color: #f8fafc !important; }
    html.dark .form-control-custom:focus { background-color: #0f172a !important; border-color: #2563eb !important; }
    
    html.dark .btn-primary-custom { background-color: #2563eb !important; border: none !important; }
    html.dark .btn-light-custom { background-color: #1e293b !important; color: #60a5fa !important; }
    
    html.dark .border-t, 
    html.dark .border-b, 
    html.dark .border-bottom { border-color: #334155 !important; }
    
    html.dark .bg-red-50 { background-color: rgba(127, 29, 29, 0.2) !important; color: #fecaca !important; border-color: #7f1d1d !important; }
    html.dark .text-red-700, 
    html.dark .text-red-600 { color: #f87171 !important; }
    html.dark .btn-danger { background-color: #dc2626 !important; border-color: #b91c1c !important; color: white !important; }
    
    html.dark .text-muted, 
    html.dark .text-gray-500 { color: #94a3b8 !important; }
    html.dark .bg-blue-50\/30 { background-color: rgba(30, 41, 59, 0.5) !important; border-color: #334155 !important; }
    html.dark .hover\:bg-white:hover { background-color: #1e293b !important; }
    html.dark .border-blue-100 { border-color: #1e3a8a !important; }
    html.dark .text-blue-900 { color: #60a5fa !important; }
    html.dark .bg-gray-50 { background-color: #0f172a !important; }
    html.dark .text-gray-600 { color: #cbd5e1 !important; }
    html.dark .text-gray-800 { color: #f1f5f9 !important; }
    html.dark .avatar-circle { background-color: #1e3a8a !important; color: #60a5fa !important; }
    
    html.dark .list-group-item { background-color: #1e293b !important; color: #f8fafc !important; border-color: #334155 !important; }
    html.dark .list-group-item:hover { background-color: #334155 !important; }

    /* Phone Country Selector Styles */
    .phone-selector-container {
        display: flex;
        align-items: center;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        border-radius: 8px;
    }
    .phone-dropdown-btn {
        display: inline-flex;
        align-items: center;
        background-color: #f1f3f6;
        border: 1px solid #dee2e6;
        border-radius: 8px 0 0 8px;
        padding: 10px 15px;
        font-size: 0.875rem;
        font-weight: 500;
        color: #292b32;
        transition: all 0.2s;
        border-right: none;
    }
    .phone-dropdown-btn:hover {
        background-color: #e9ecef;
    }
    .phone-dropdown-menu {
        position: absolute;
        z-index: 1050;
        display: none;
        min-width: 220px;
        background-color: #fff;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        padding: 8px;
        margin-top: 5px;
    }
    .phone-dropdown-menu.show {
        display: block;
    }
    .phone-dropdown-item {
        display: flex;
        align-items: center;
        width: 100%;
        padding: 8px 12px;
        font-size: 0.875rem;
        color: #292b32;
        border-radius: 6px;
        transition: background 0.2s;
        cursor: pointer;
        border: none;
        background: none;
        text-align: left;
    }
    .phone-dropdown-item:hover {
        background-color: #f3f4f6;
        color: #0065ff;
    }
    .phone-input-field {
        flex: 1;
        background-color: #f1f3f6;
        border: 1px solid #dee2e6;
        border-radius: 0 8px 8px 0;
        padding: 10px 15px;
        font-size: 0.875rem;
        color: #292b32;
        outline: none;
        transition: border-color 0.2s;
    }
    .phone-input-field:focus {
        border-color: #0065ff;
        background-color: #fff;
    }

    html.dark .phone-dropdown-btn,
    html.dark .phone-input-field {
        background-color: #1e293b !important;
        border-color: #334155 !important;
        color: #f8fafc !important;
    }
    html.dark .phone-dropdown-menu {
        background-color: #1e293b !important;
        border-color: #334155 !important;
    }
    html.dark .phone-dropdown-item {
        color: #cbd5e1 !important;
    }
    html.dark .phone-dropdown-item:hover {
        background-color: #334155 !important;
        color: #60a5fa !important;
    }
</style>
 
<div class="max-w-7xl mx-auto px-6 py-10">
    <div class="flex flex-col md:flex-row gap-10">
       
        <!-- Sidebar Navigation (aligned with Logo) -->
        <div class="md:w-1/5 mb-8 md:mb-0">
            <!-- Optional: User Brief Info -->
            <div class="text-center mb-4 pb-3 border-bottom d-md-none">
                <h5>{{ __('profile_page.sidebar.settings') }}</h5>
            </div>
 
            <nav class="nav flex-column nav-pills settings-nav" id="settingsTab" role="tablist" aria-orientation="vertical">
                <a class="nav-link sub-menu-link" href="{{ url('/index') }}"><i class="fas fa-arrow-left fa-fw"></i> {{ __('profile_page.sidebar.back_home') }}</a>
                <div class="my-2 border-bottom"></div>
               
                @auth
                <a class="nav-link sub-menu-link" href="{{ route('my-tasks') }}"><i class="fas fa-columns fa-fw"></i> {{ __('profile_page.sidebar.dashboard') }}</a>
                <a class="nav-link sub-menu-link" id="notification-tab" data-bs-toggle="pill" href="#notification" role="tab">{{ __('profile_page.sidebar.notifications') }}</a>
               
                <!-- Active Tab styling matches the screenshot logic -->
                <a class="nav-link sub-menu-link {{ auth()->check() ? 'active' : '' }}" id="profile-tab" data-bs-toggle="pill" href="#profile" role="tab">{{ __('profile_page.sidebar.profile') }}</a>
                @endauth
                <a class="nav-link sub-menu-link {{ !auth()->check() ? 'active' : '' }}" id="account-tab" data-bs-toggle="pill" href="#account" role="tab">{{ __('profile_page.sidebar.settings') }}</a>
                @auth
                <a class="nav-link sub-menu-link" id="security-tab" data-bs-toggle="pill" href="#security" role="tab">{{ __('profile_page.sidebar.security') }}</a>
                <a class="nav-link sub-menu-link" id="billing-tab" data-bs-toggle="pill" href="#billing" role="tab">{{ __('profile_page.sidebar.billing') }}</a>
                @endauth
            </nav>
        </div>
 
        <!-- Main Content Area -->
        <div class="flex-1 md:pl-10">
           
            <div class="tab-content" id="settingsTabContent">
               
                <!-- Profile Tab (Matches Screenshot) -->
                @auth
                <div class="tab-pane fade {{ auth()->check() ? 'show active' : '' }}" id="profile" role="tabpanel">
                   
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <h1 class="page-title">{{ __('profile_page.profile.title') }}</h1>
                       
                    </div>
 
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
 
                    <!-- Profile Form -->
                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" onsubmit="return confirm('{{ __('profile_page.profile.confirm_update') }}');">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="form_type" value="profile">
                        <!-- Avatar Section -->
                        <div class="mb-5">
                            <h6 class="section-label">{{ __('profile_page.profile.upload_avatar') }}</h6>
                            <div class="d-flex flex-column flex-md-row align-items-center gap-3">
                                <!-- Avatar Placeholder / Current Avatar -->
                                <div class="avatar-circle overflow-hidden">
                                    <img src="{{ $user->avatar_url }}" id="avatarPreview" alt="Avatar" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
 
                                <!-- Action Buttons -->
                                <div class="d-flex flex-column gap-2">
                                    <label class="btn btn-primary btn-primary-custom px-4 mb-0" for="avatarInput">{{ __('profile_page.profile.upload_photo') }}</label>
                                    <small class="text-muted">{{ __('profile_page.profile.upload_help') }}</small>
                                </div>
                            </div>
                            <!-- Hidden file input for avatar upload -->
                            <input type="file" id="avatarInput" name="avatar" accept="image/*" class="d-none">
                        </div>
                        <div class="mb-4 custom-input-group">
                            <label for="firstName" class="form-label">{{ __('profile_page.profile.first_name') }}</label>
                            <input
                                type="text"
                                class="form-control form-control-custom"
                                id="firstName"
                                name="first_name"
                                value="{{ old('first_name', $user->first_name ?? '') }}">
                        </div>
 
                        <div class="mb-4 custom-input-group">
                            <label for="lastName" class="form-label">{{ __('profile_page.profile.last_name') }}</label>
                            <input
                                type="text"
                                class="form-control form-control-custom"
                                id="lastName"
                                name="last_name"
                                value="{{ old('last_name', $user->last_name ?? '') }}">
                        </div>
 
                        <div class="mb-4 custom-input-group">
                            <label for="birthdate" class="form-label">{{ __('profile_page.profile.birthday') }}</label>
                            <input
                                type="date"
                                class="form-control form-control-custom"
                                id="birthdate"
                                name="birthdate"
                                max="{{ date('Y-m-d') }}"
                                value="{{ $user->birthdate ? ($user->birthdate instanceof \Carbon\Carbon ? $user->birthdate->format('Y-m-d') : \Carbon\Carbon::parse($user->birthdate)->format('Y-m-d')) : '' }}">
                        </div>
 
                        <div class="mb-4 custom-input-group position-relative">
                            <label for="location" class="form-label">{{ __('profile_page.profile.location') }}</label>
                            <input
                                type="text"
                                class="form-control form-control-custom"
                                id="location"
                                autocomplete="off"
                                value="{{ old('location', optional(optional($user)->city)->name) }}">
                            <input type="hidden" name="city_id" id="city_id" value="{{ old('city_id', $user->city_id ?? '') }}">
                            <div id="location-suggestions" class="list-group position-absolute w-100" style="z-index: 1050; max-height: 200px; overflow-y: auto; display: none;"></div>
                        </div>
 
                        <div class="mb-4 custom-input-group">
                            <label for="email" class="form-label">{{ __('profile_page.profile.email') }}</label>
                            <input
                                type="email"
                                class="form-control form-control-custom"
                                id="email"
                                name="email"
                                value="{{ old('email', $user->email ?? '') }}">
                        </div>
 
                        <div class="mb-4 custom-input-group">
                            <label for="phone" class="form-label">{{ __('profile_page.profile.phone') }}</label>
                            
                            <div class="relative">
                                <div class="phone-selector-container">
                                    <button id="dropdown-phone-button" type="button" class="phone-dropdown-btn">
                                        <div id="selected-flag" class="flex items-center">
                                            <!-- Default: Hungary Flag -->
                                            <svg class="w-4 h-4 me-2 rounded-full" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg"><rect width="16" height="5.33" fill="#CE2939"/><rect y="5.33" width="16" height="5.34" fill="#FFFFFF"/><rect y="10.67" width="16" height="5.33" fill="#477050"/></svg>
                                            <span id="selected-prefix">+36</span>
                                        </div>
                                        <svg class="w-4 h-4 ms-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"/></svg>
                                    </button>
                                    
                                    <input type="tel" id="phone" name="phone_number" class="phone-input-field" 
                                           value="{{ old('phone_number', $user->phone_number ?? '') }}" 
                                           placeholder="30 123 4567">
                                </div>

                                <div id="dropdown-phone" class="phone-dropdown-menu">
                                    <ul class="list-unstyled mb-0">
                                        <li>
                                            <button type="button" class="phone-dropdown-item country-option" data-prefix="+36" data-flag="hu">
                                                <svg class="w-4 h-4 me-3 rounded-full" viewBox="0 0 16 16"><rect width="16" height="5.33" fill="#CE2939"/><rect y="5.33" width="16" height="5.34" fill="#FFFFFF"/><rect y="10.67" width="16" height="5.33" fill="#477050"/></svg>
                                                {{ __('profile_page.countries.hu') }} (+36)
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="phone-dropdown-item country-option" data-prefix="+43" data-flag="at">
                                                <svg class="w-4 h-4 me-3 rounded-full" viewBox="0 0 16 16"><rect width="16" height="5.33" fill="#ED2939"/><rect y="5.33" width="16" height="5.34" fill="#FFFFFF"/><rect y="10.67" width="16" height="5.33" fill="#ED2939"/></svg>
                                                {{ __('profile_page.countries.at') }} (+43)
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="phone-dropdown-item country-option" data-prefix="+421" data-flag="sk">
                                                <svg class="w-4 h-4 me-3 rounded-full" viewBox="0 0 16 16"><rect width="16" height="5.33" fill="#FFFFFF"/><rect y="5.33" width="16" height="5.34" fill="#0B4592"/><rect y="10.67" width="16" height="5.33" fill="#EE1C25"/></svg>
                                                {{ __('profile_page.countries.sk') }} (+421)
                                            </button>
                                        </li>
 
                                        <li>
                                            <button type="button" class="phone-dropdown-item country-option" data-prefix="+385" data-flag="hr">
                                                <svg class="w-4 h-4 me-3 rounded-full" viewBox="0 0 16 16"><rect width="16" height="5.33" fill="#FF0000"/><rect y="5.33" width="16" height="5.34" fill="#FFFFFF"/><rect y="10.67" width="16" height="5.33" fill="#171796"/></svg>
                                                {{ __('profile_page.countries.hr') }} (+385)
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="phone-dropdown-item country-option" data-prefix="+381" data-flag="rs">
                                                <svg class="w-4 h-4 me-3 rounded-full" viewBox="0 0 16 16"><rect width="16" height="5.33" fill="#C6363C"/><rect y="5.33" width="16" height="5.34" fill="#0C4076"/><rect y="10.67" width="16" height="5.33" fill="#FFFFFF"/></svg>
                                                {{ __('profile_page.countries.rs') }} (+381)
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="phone-dropdown-item country-option" data-prefix="+386" data-flag="si">
                                                <svg class="w-4 h-4 me-3 rounded-full" viewBox="0 0 16 16"><rect width="16" height="5.33" fill="#FFFFFF"/><rect y="5.33" width="16" height="5.34" fill="#0000FF"/><rect y="10.67" width="16" height="5.33" fill="#FF0000"/></svg>
                                                {{ __('profile_page.countries.si') }} (+386)
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="phone-dropdown-item country-option" data-prefix="+380" data-flag="ua">
                                                <svg class="w-4 h-4 me-3 rounded-full" viewBox="0 0 16 16"><rect width="16" height="8" fill="#0057B7"/><rect y="8" width="16" height="8" fill="#FFD700"/></svg>
                                                {{ __('profile_page.countries.ua') }} (+380)
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
 
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary btn-primary-custom px-5">{{ __('profile_page.profile.save') }}</button>
                        </div>
                    </form>
                </div>
                @endauth
 
                <!-- Account Tab (Settings) -->
                <div class="tab-pane fade {{ !auth()->check() ? 'show active' : '' }}" id="account" role="tabpanel">
                    <h1 class="page-title">{{ __('profile_page.account.title') }}</h1>
                   
                    <div class="max-w-2xl">
                        <div class="mb-5 custom-input-group">
                            <h6 class="section-label">{{ __('navbar.language') }}</h6>
                            <select id="lang-select" class="form-control form-control-custom w-full md:w-2/3">
                                <option value="en" @selected(app()->getLocale() == 'en')>{{ __('navbar.english') }}</option>
                                <option value="hu" @selected(app()->getLocale() == 'hu')>{{ __('navbar.hungarian') }}</option>
                            </select>
                        </div>
 
                        <div class="mb-5 custom-input-group">
                            <h6 class="section-label">{{ __('navbar.theme') }}</h6>
                            <select id="theme-select" class="form-control form-control-custom w-full md:w-2/3">
                                <option value="light">{{ __('navbar.light') }}</option>
                                <option value="dark">{{ __('navbar.dark') }}</option>
                                <option value="system">{{ __('navbar.system_default') }}</option>
                            </select>
                        </div>
 
                        <div class="mb-5 custom-input-group">
                            <h6 class="section-label">{{ __('profile_page.account.accessibility') }}</h6>
                            <div class="space-y-4 pt-2">
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input type="checkbox" id="accessibility-master-toggle" class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-semibold text-gray-800">{{ __('profile_page.account.enable_acc') }}</span>
                                        <span class="text-xs text-gray-500">{{ __('profile_page.account.show_acc_desc') }}</span>
                                    </div>
                                </label>
 
                                <div id="access-sub-options" class="hidden pl-8 space-y-4 pt-2 border-l-2 border-blue-50 ml-2">
                                    <label class="flex items-center gap-3 cursor-pointer group">
                                        <input type="checkbox" id="reduced-motion-toggle" class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-semibold text-gray-800">{{ __('profile_page.account.reduced_motion') }}</span>
                                            <span class="text-xs text-gray-500">{{ __('profile_page.account.reduced_motion_desc') }}</span>
                                        </div>
                                    </label>
                                   
                                    <label class="flex items-center gap-3 cursor-pointer group">
                                        <input type="checkbox" id="high-contrast-toggle" class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-semibold text-gray-800">{{ __('profile_page.account.high_contrast') }}</span>
                                            <span class="text-xs text-gray-500">{{ __('profile_page.account.high_contrast_desc') }}</span>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
 
                        <div class="mb-5">
                            <button type="button" id="apply-settings-btn" class="btn btn-primary btn-primary-custom px-8">
                                {{ __('profile_page.account.apply') }}
                            </button>
                        </div>
 
                        @auth
                        <div class="mt-12 pt-8 border-t border-gray-100">
                            <div class="bg-red-50 rounded-2xl p-6 border border-red-100">
                                <h6 class="text-red-700 font-bold mb-2">{{ __('profile_page.account.delete_title') }}</h6>
                                <p class="text-red-600 offset-sm text-sm mb-4">{{ __('profile_page.account.delete_desc') }}</p>
                               
                                <form action="{{ route('profile.delete') }}" method="POST" onsubmit="return confirm('{{ __('profile_page.profile.confirm_delete') ?? 'Are you sure you want to delete your account? This action cannot be undone.' }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger rounded-pill px-6 font-bold" type="submit">
                                        {{ __('profile_page.account.delete_btn') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endauth
                    </div>
                </div>
 
                <!-- Security Tab -->
                @auth
                <div class="tab-pane fade" id="security" role="tabpanel">
                    <h1 class="page-title">{{ __('profile_page.security.title') }}</h1>
                    <form>
                        <div class="mb-4 custom-input-group">
                            <label class="form-label">{{ __('profile_page.security.old_password') }}</label>
                            <div class="position-relative mb-3">
                                <input type="password" id="old_password" class="form-control form-control-custom w-100">
                                <i class="fa fa-eye-slash position-absolute top-50 end-0 translate-middle-y me-3 password-toggle" data-target="old_password"></i>
                            </div>
                           
                            <label class="form-label">{{ __('profile_page.security.new_password') }}</label>
                            <div class="position-relative mb-3">
                                <input type="password" id="new_password" class="form-control form-control-custom w-100">
                                <i class="fa fa-eye-slash position-absolute top-50 end-0 translate-middle-y me-3 password-toggle" data-target="new_password"></i>
                            </div>
                           
                            <label class="form-label">{{ __('profile_page.security.confirm_password') }}</label>
                            <div class="position-relative">
                                <input type="password" id="confirm_password" class="form-control form-control-custom w-100">
                                <i class="fa fa-eye-slash position-absolute top-50 end-0 translate-middle-y me-3 password-toggle" data-target="confirm_password"></i>
                            </div>
                        </div>
                        <button class="btn btn-primary btn-primary-custom px-4">{{ __('profile_page.security.update_password') }}</button>
                    </form>
                </div>
                @endauth
 
                <!-- Notification Tab -->
                @auth
                <div class="tab-pane fade" id="notification" role="tabpanel">
                    <h1 class="page-title">{{ __('profile_page.notifications.title') }}</h1>

                    @if(session('success'))
                    <div class="rounded-xl border border-green-200 bg-green-50 p-4 mb-6 flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        <span class="text-green-800 font-medium text-sm">{{ session('success') }}</span>
                    </div>
                    @endif

                    @if(session('info'))
                    <div class="rounded-xl border border-blue-200 bg-blue-50 p-4 mb-6 flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                        <span class="text-blue-800 font-medium text-sm">{{ session('info') }}</span>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="rounded-xl border border-red-200 bg-red-50 p-4 mb-6 flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                        <span class="text-red-800 font-medium text-sm">{{ session('error') }}</span>
                    </div>
                    @endif
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="form_type" value="notifications">

                        <div class="mb-5">
                            <h6 class="section-label">{{ __('profile_page.notifications.email_prefs') }}</h6>
                           
                            <div class="space-y-4">
                                <label class="flex items-start gap-3 cursor-pointer p-3 rounded-lg hover:bg-gray-50 transition-colors">
                                    <input type="checkbox" name="email_notifications" value="1" {{ $user->email_notifications ? 'checked' : '' }} class="mt-1 w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <div>
                                        <span class="block font-bold text-blue-900">{{ __('profile_page.notifications.offer_updates') }}</span>
                                        <span class="text-sm text-gray-600">{{ __('profile_page.notifications.offer_updates_desc') }}</span>
                                    </div>
                                </label>
 
                                <label class="flex items-start gap-3 cursor-pointer p-3 rounded-lg hover:bg-gray-50 transition-colors">
                                    <input type="checkbox" name="email_direct_quotes" value="1" {{ $user->email_direct_quotes ? 'checked' : '' }} class="mt-1 w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <div>
                                        <span class="block font-bold text-blue-900">{{ __('profile_page.notifications.direct_quotes') }}</span>
                                        <span class="text-sm text-gray-600">{{ __('profile_page.notifications.direct_quotes_desc') }}</span>
                                    </div>
                                </label>
 
                                <label class="flex items-start gap-3 cursor-pointer p-3 rounded-lg hover:bg-gray-50 transition-colors">
                                    <input type="checkbox" name="email_task_digest" value="1" id="digest_toggle_profile" {{ $user->email_task_digest ? 'checked' : '' }} class="mt-1 w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <div>
                                        <span class="block font-bold text-blue-900">{{ __('profile_page.notifications.task_digest') }}</span>
                                        <span class="text-sm text-gray-600">{{ __('profile_page.notifications.task_digest_desc') }}</span>
                                    </div>
                                </label>
                            </div>
 
                            <!-- category selection (shown only if digest is enabled) -->
                            <div id="category_selection_profile" class="{{ $user->email_task_digest ? '' : 'hidden' }} pl-11 mt-4">
                                <button type="button" id="toggle_categories_btn" class="flex items-center gap-2 text-xs font-bold text-blue-900 mb-3 uppercase tracking-wide hover:text-blue-700 transition-colors cursor-pointer">
                                    <span>{{ __('profile_page.notifications.tracked_categories') }}</span>
                                    <svg id="toggle_categories_chevron" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="transition: transform 0.2s;"><polyline points="6 9 12 15 18 9"></polyline></svg>
                                </button>
                                <div id="categories_grid" class="hidden grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 p-4 border border-blue-100 rounded-xl bg-blue-50/30 max-h-64 overflow-y-auto">
                                    @foreach($categories as $cat)
                                    <label class="flex items-center gap-2 cursor-pointer hover:bg-white p-2 rounded-lg transition-colors border border-transparent hover:border-blue-100">
                                        <input type="checkbox" name="tracked_categories[]" value="{{ $cat->id }}"
                                            {{ $user->trackedCategories->contains($cat->id) ? 'checked' : '' }}
                                            class="w-4 h-4 text-blue-600 border-gray-300 rounded">
                                        <span class="text-sm text-gray-700 font-medium">{{ __('categories.' . $cat->name) }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
 
                        <div class="mt-8">
                            <button type="submit" class="btn btn-primary btn-primary-custom px-8 py-2 font-bold text-white shadow-lg shadow-blue-500/20">
                                {{ __('profile_page.notifications.save') }}
                            </button>
                        </div>
                    </form>

                    @if($user->email_task_digest)
                    <div class="mt-8 pt-8 border-t border-gray-100">
                        <h6 class="section-label">{{ __('profile_page.notifications.manual_digest') ?? 'Send Manual Digest' }}</h6>
                        <p class="text-sm text-gray-600 mb-4">{{ __('profile_page.notifications.manual_digest_desc') ?? 'Send an email with the latest tasks from your tracked categories right now!' }}</p>
                        <form action="{{ route('profile.send-digest') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-light-custom">
                                <i class="fas fa-envelope fa-fw"></i> {{ __('profile_page.notifications.manual_digest') ?? 'Send Manual Digest' }}
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
                @endauth
 
                <!-- Billing Tab -->
                @auth
                <div class="tab-pane fade" id="billing" role="tabpanel">
                    <h1 class="page-title">{{ __('profile_page.billing.title') }}</h1>
                    <button class="btn btn-primary btn-primary-custom mb-4" type="button">{{ __('profile_page.billing.add_method') }}</button>
                    <div class="p-4 bg-light text-center rounded text-muted">{{ __('profile_page.billing.no_payments') }}</div>
                </div>
                @endauth
 
            </div>
        </div>
    </div>
</div>
 
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- Location Autocomplete ---
        (function () {
            const input = document.getElementById('location');
            const hiddenCityId = document.getElementById('city_id');
            const suggestions = document.getElementById('location-suggestions');
            if (!input || !suggestions) return;
 
            let timer = null;
 
            function clearSuggestions() {
                suggestions.innerHTML = '';
                suggestions.style.display = 'none';
            }
 
            input.addEventListener('input', function () {
                const query = this.value.trim();
                hiddenCityId.value = '';
 
                if (timer) clearTimeout(timer);
                if (query.length < 2) {
                    clearSuggestions();
                    return;
                }
 
                timer = setTimeout(() => {
                    fetch(`/api/cities?q=${encodeURIComponent(query)}`)
                        .then(res => res.json())
                        .then(cities => {
                            suggestions.innerHTML = '';
                            if (!Array.isArray(cities) || cities.length === 0) {
                                clearSuggestions();
                                return;
                            }
 
                            cities.slice(0, 10).forEach(city => {
                                const item = document.createElement('button');
                                item.type = 'button';
                                item.className = 'list-group-item list-group-item-action';
                                item.textContent = city.name;
                                item.addEventListener('click', function () {
                                    input.value = city.name;
                                    hiddenCityId.value = city.id || '';
                                    clearSuggestions();
                                });
                                suggestions.appendChild(item);
                            });
 
                            suggestions.style.display = 'block';
                        })
                        .catch(() => {
                            clearSuggestions();
                        });
                }, 300);
            });
 
            document.addEventListener('click', function (e) {
                if (!suggestions.contains(e.target) && e.target !== input) {
                    clearSuggestions();
                }
            });
        })();
 
        // --- Tab switching via URL query param ---
        (function handleUrlTabs() {
            const urlParams = new URLSearchParams(window.location.search);
            const tabParam = urlParams.get('tab');
            if (tabParam) {
                const tabEl = document.getElementById(tabParam + '-tab');
                if (tabEl && typeof bootstrap !== 'undefined') {
                    const bstab = new bootstrap.Tab(tabEl);
                    bstab.show();
                }
            }
        })();
 
        // --- Language and Theme switching logic ---
        (function handleSettings() {
            const themeSelect = document.getElementById('theme-select');
            const langSelect = document.getElementById('lang-select');
            const applyBtn = document.getElementById('apply-settings-btn');
            const root = document.documentElement;
 
            function applyTheme(mode) {
                if (mode === 'system') {
                    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                    root.classList.toggle('dark', prefersDark);
                } else if (mode === 'dark') {
                    root.classList.add('dark');
                    root.classList.remove('high-contrast'); // Dark mode and HC are mutually exclusive
                } else {
                    root.classList.remove('dark');
                }
            }

            function applyAccessibility(prefs) {
                if (prefs.reducedMotion) {
                    root.classList.add('reduced-motion');
                } else {
                    root.classList.remove('reduced-motion');
                }

                if (prefs.highContrast) {
                    root.classList.add('high-contrast');
                    root.classList.remove('dark');
                } else {
                    root.classList.remove('high-contrast');
                }
            }

            async function saveSettings(theme, reducedMotion, highContrast) {
                // Persistent storage for authenticated users
                if (window.userSettings) {
                    try {
                        const response = await fetch('{{ route("profile.settings.update") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                theme: theme,
                                reduced_motion: reducedMotion,
                                high_contrast: highContrast
                            })
                        });
                        if (response.ok) {
                            console.log('Settings synced to profile');
                            // Update window.userSettings to reflect new state
                            window.userSettings.theme = theme;
                            window.userSettings.reduced_motion = reducedMotion;
                            window.userSettings.high_contrast = highContrast;
                        }
                    } catch (error) {
                        console.error('Error syncing settings:', error);
                    }
                } else {
                    // Guest fallback (temporary for session, no localStorage persistence)
                    console.log('Guest settings applied locally only');
                }
            }
 
 
        // Avatar Preview Logic
        const avatarInput = document.getElementById('avatarInput');
        const avatarPreview = document.getElementById('avatarPreview');
 
        if (avatarInput && avatarPreview) {
            avatarInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        avatarPreview.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
 
            // Init states - Strictly from profile (window.userSettings)
            const initialTheme = (window.userSettings && window.userSettings.theme) ? window.userSettings.theme : 'light';
            if (themeSelect) themeSelect.value = initialTheme;

            const reducedMotionToggle = document.getElementById('reduced-motion-toggle');
            const highContrastToggle = document.getElementById('high-contrast-toggle');
            const masterAccToggle = document.getElementById('accessibility-master-toggle');
            const subOptions = document.getElementById('access-sub-options');
           
            const initialReducedMotion = (window.userSettings && window.userSettings.reduced_motion) || false;
            const initialHighContrast = (window.userSettings && window.userSettings.high_contrast) || false;

            if (masterAccToggle) {
                masterAccToggle.checked = (initialReducedMotion || initialHighContrast);
                if (masterAccToggle.checked) subOptions?.classList.remove('hidden');
                masterAccToggle.addEventListener('change', function() {
                    subOptions?.classList.toggle('hidden', !this.checked);
                });
            }
            if (reducedMotionToggle) {
                reducedMotionToggle.checked = initialReducedMotion;
            }
            if (highContrastToggle) {
                highContrastToggle.checked = initialHighContrast;
                highContrastToggle.addEventListener('change', function() {
                    if (this.checked && themeSelect) {
                        themeSelect.value = 'light'; // Force light theme UI
                    }
                });
            }

            if (themeSelect) {
                themeSelect.addEventListener('change', function() {
                    if (this.value === 'dark' && highContrastToggle) {
                        highContrastToggle.checked = false; // Force HC off UI
                    }
                });
            }
 
            if (applyBtn) {
                applyBtn.addEventListener('click', function() {
                    console.log('Apply button clicked');
                   
                    // 1. Apply Theme
                    if (themeSelect) {
                        applyTheme(themeSelect.value);
                        console.log('Theme applied:', themeSelect.value);
                    }
 
                    // 2. Apply Accessibility
                    const reducedMotion = reducedMotionToggle ? reducedMotionToggle.checked : false;
                    const highContrast = highContrastToggle ? highContrastToggle.checked : false;
                    const theme = themeSelect ? themeSelect.value : 'light';

                    if (masterAccToggle) {
                        applyAccessibility({
                            reducedMotion: masterAccToggle.checked ? reducedMotion : false,
                            highContrast: masterAccToggle.checked ? highContrast : false
                        });
                    }

                    // 3. Save to profile
                    saveSettings(theme, 
                        masterAccToggle.checked ? reducedMotion : false, 
                        masterAccToggle.checked ? highContrast : false
                    );
 
                    // 2. Apply Language (Redirect if changed)
                    if (langSelect) {
                        const currentLocale = '{{ app()->getLocale() }}';
                        const newLocale = langSelect.value;
                        console.log('Current locale:', currentLocale, 'New locale:', newLocale);
                       
                        if (newLocale !== currentLocale) {
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = '/language/' + newLocale;
                            const csrfInput = document.createElement('input');
                            csrfInput.type = 'hidden';
                            csrfInput.name = '_token';
                            csrfInput.value = '{{ csrf_token() }}';
                            form.appendChild(csrfInput);
                            document.body.appendChild(form);
                            form.submit();
                            return;
                        }
                    }
                   
                    alert('Settings applied successfully!');
                });
            }
        })();
 
        // --- Notification Settings Toggles ---
        (function handleNotificationToggles() {
            const digestToggle = document.getElementById('digest_toggle_profile');
            const categorySelection = document.getElementById('category_selection_profile');
            const toggleBtn = document.getElementById('toggle_categories_btn');
            const categoriesGrid = document.getElementById('categories_grid');
            const chevron = document.getElementById('toggle_categories_chevron');
            if (!digestToggle || !categorySelection) return;
 
            digestToggle.addEventListener('change', function() {
                if (this.checked) {
                    categorySelection.classList.remove('hidden');
                } else {
                    categorySelection.classList.add('hidden');
                    if (categoriesGrid) categoriesGrid.classList.add('hidden');
                    if (chevron) chevron.style.transform = '';
                }
            });

            // Toggle categories grid expand/collapse
            if (toggleBtn && categoriesGrid) {
                toggleBtn.addEventListener('click', function() {
                    const isHidden = categoriesGrid.classList.toggle('hidden');
                    if (chevron) {
                        chevron.style.transform = isHidden ? '' : 'rotate(180deg)';
                    }
                });
            }
        })();
 
        // --- Password Toggle Logic ---
        (function handlePasswordToggle() {
            document.querySelectorAll('.password-toggle').forEach(item => {
                item.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');
                    const input = document.getElementById(targetId);
                    if (!input) return;
                   
                    const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                    input.setAttribute('type', type);
                   
                    this.classList.toggle('fa-eye');
                    this.classList.toggle('fa-eye-slash');
                });
            });
        })();

        // --- Phone Country Selector Logic ---
        (function handlePhoneSelector() {
            const dropdownBtn = document.getElementById('dropdown-phone-button');
            const dropdownMenu = document.getElementById('dropdown-phone');
            const countryOptions = document.querySelectorAll('.country-option');
            const selectedFlag = document.getElementById('selected-flag');
            const selectedPrefix = document.getElementById('selected-prefix');
            const phoneInput = document.getElementById('phone');

            if (!dropdownBtn || !dropdownMenu) return;

            dropdownBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                dropdownMenu.classList.toggle('show');
            });

            document.addEventListener('click', function(e) {
                if (!dropdownBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
                    dropdownMenu.classList.remove('show');
                }
            });

            countryOptions.forEach(option => {
                option.addEventListener('click', function() {
                    const prefix = this.getAttribute('data-prefix');
                    const flagSvg = this.querySelector('svg').cloneNode(true);
                    flagSvg.classList.remove('me-3');
                    flagSvg.classList.add('me-2');

                    selectedFlag.innerHTML = '';
                    selectedFlag.appendChild(flagSvg);
                    selectedFlag.appendChild(document.createTextNode(prefix));
                    
                    dropdownMenu.classList.remove('show');
                    
                    // Focus input after selection
                    phoneInput.focus();
                });
            });

            // Optional: Auto-detect prefix from existing value
            const currentVal = phoneInput.value.trim();
            if (currentVal.startsWith('+')) {
                countryOptions.forEach(option => {
                    const prefix = option.getAttribute('data-prefix');
                    if (currentVal.startsWith(prefix)) {
                        option.click();
                        // Remove prefix from input display if you want separate storage
                        // But usually we keep it or just visual. 
                        // For now let's just match the UI to the data.
                    }
                });
            }
        })();
    });
</script>
 
@endsection
