@extends('layout')
 
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/profile.css') }}">
@endpush

@section('content')
 
 
<div class="settings-container max-w-7xl mx-auto px-6 py-10">
    <div class="flex flex-col md:flex-row gap-10">
       
        <!-- Sidebar Navigation (aligned with Logo) -->
        <div class="md:w-1/5 mb-8 md:mb-0">
            <!-- Optional: User Brief Info -->
            <div class="text-center mb-4 pb-3 border-bottom d-md-none">
                <h5>{{ __('profile_page.sidebar.settings') }}</h5>
            </div>
 
                <nav class="nav flex-column nav-pills settings-nav" id="settingsTab" aria-orientation="vertical">
                    <a class="nav-link sub-menu-link" href="{{ url('/index') }}" tabindex="0"><i class="fas fa-arrow-left fa-fw me-2"></i> {{ __('profile_page.sidebar.back_home') }}</a>
                    <div class="my-2 border-bottom border-[var(--settings-border)]"></div>
                   
                    @auth
                    <a class="nav-link sub-menu-link" href="{{ route('my-tasks') }}" tabindex="0"><i class="fas fa-columns fa-fw me-2"></i> {{ __('profile_page.sidebar.dashboard') }}</a>
                    <a class="nav-link sub-menu-link" id="notification-tab" data-section="notification" href="#notification" tabindex="0"><i class="fas fa-bell fa-fw me-2"></i> {{ __('profile_page.sidebar.notifications') }}</a>
               
                    <!-- Active Tab styling matches the screenshot logic -->
                    <a class="nav-link sub-menu-link {{ auth()->check() ? 'active' : '' }}" id="profile-tab" data-section="profile" href="#profile" tabindex="0"><i class="fas fa-user fa-fw me-2"></i> {{ __('profile_page.sidebar.profile') }}</a>
                    @endauth
                    <a class="nav-link sub-menu-link {{ !auth()->check() ? 'active' : '' }}" id="account-tab" data-section="account" href="#account" tabindex="0"><i class="fas fa-cog fa-fw me-2"></i> {{ __('profile_page.sidebar.settings') }}</a>
                    @auth
                    <a class="nav-link sub-menu-link" id="security-tab" data-section="security" href="#security" tabindex="0"><i class="fas fa-shield-alt fa-fw me-2"></i> {{ __('profile_page.sidebar.security') }}</a>
                    <a class="nav-link sub-menu-link" id="billing-tab" data-section="billing" href="#billing" tabindex="0"><i class="fas fa-credit-card fa-fw me-2"></i> {{ __('profile_page.sidebar.billing') }}</a>
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
                        <div class="p-4 mb-4 rounded-xl items-center flex gap-2 border-[var(--details-success-border)] bg-[var(--details-success-bg)] text-[var(--details-success)]">
                            <i data-feather="check-circle" class="w-4 h-4"></i>
                            {{ session('success') }}
                        </div>
                    @endif
 
                    <!-- Profile Form -->
                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
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
                                    <label class="btn btn-primary btn-primary-custom px-4 mb-0" for="avatarInput" tabindex="0" role="button">{{ __('profile_page.profile.upload_photo') }}</label>
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
                            <div id="location-suggestions" class="list-group position-absolute w-100 hidden bg-[var(--settings-surface)] border border-[var(--settings-border)] shadow-lg rounded-b-lg" style="z-index: 1050; max-height: 200px; overflow-y: auto;"></div>
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
                            
                            <div class="relative w-full">
                                <div class="phone-selector-container w-full">
                                    <button id="dropdown-phone-button" type="button" class="phone-dropdown-btn">
                                        <div id="selected-flag" class="flex items-center">
                                            <!-- Default: Hungary Flag -->
                                            <svg class="w-4 h-4 me-2 rounded-full" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg"><rect width="16" height="5.33" fill="#CE2939"/><rect y="5.33" width="16" height="5.34" fill="#FFFFFF"/><rect y="10.67" width="16" height="5.33" fill="#477050"/></svg>
                                            <span id="selected-prefix">+36</span>
                                        </div>
                                        <svg class="w-4 h-4 ms-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"/></svg>
                                    </button>
                                    
                                    <input type="tel" id="phone" name="phone_number" class="phone-input-field w-full" 
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
                                    <input type="checkbox" id="accessibility-master-toggle" class="w-5 h-5 text-[var(--settings-accent)] border-[var(--settings-border)] rounded focus:ring-[var(--settings-accent)]">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-semibold var(--settings-text-main)">{{ __('profile_page.account.enable_acc') }}</span>
                                        <span class="text-xs var(--settings-text-muted)">{{ __('profile_page.account.show_acc_desc') }}</span>
                                    </div>
                                </label>
 
                                <div id="access-sub-options" class="hidden pl-8 space-y-4 pt-2 border-l-2 border-[var(--primary-accent-focus)] ml-2">
                                    <label class="flex items-center gap-3 cursor-pointer group">
                                        <input type="checkbox" id="reduced-motion-toggle" class="w-5 h-5 text-[var(--settings-accent)] border-[var(--settings-border)] rounded focus:ring-[var(--settings-accent)]">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-semibold var(--settings-text-main)">{{ __('profile_page.account.reduced_motion') }}</span>
                                            <span class="text-xs var(--settings-text-muted)">{{ __('profile_page.account.reduced_motion_desc') }}</span>
                                        </div>
                                    </label>
                                   
                                    <label class="flex items-center gap-3 cursor-pointer group">
                                        <input type="checkbox" id="high-contrast-toggle" class="w-5 h-5 text-[var(--settings-accent)] border-[var(--settings-border)] rounded focus:ring-[var(--settings-accent)]">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-semibold var(--settings-text-main)">{{ __('profile_page.account.high_contrast') }}</span>
                                            <span class="text-xs var(--settings-text-muted)">{{ __('profile_page.account.high_contrast_desc') }}</span>
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
                        <div class="mt-12 pt-8 border-t border-[var(--settings-border)]">
                            <div class="p-6 rounded-2xl border-[var(--details-error-border)] bg-[var(--details-error-bg)] text-[var(--details-error)]">
                                <h6 class="font-bold mb-2">{{ __('profile_page.account.delete_title') }}</h6>
                                <p class="offset-sm text-sm mb-4">{{ __('profile_page.account.delete_desc') }}</p>
                               
                                <form action="{{ route('profile.delete') }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="w-full sm:w-auto px-8 py-3 bg-[var(--details-error)] text-white font-bold rounded-full hover:opacity-90 transition-all shadow-lg shadow-[var(--details-error)] shadow-opacity-20" type="submit">
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
                    <div class="rounded-xl border-[var(--details-success-border)] bg-[var(--details-success-bg)] p-4 mb-6 flex items-center gap-3">
                        <i data-feather="check-circle" class="w-5 h-5 text-[var(--details-success)]"></i>
                        <span class="text-[var(--details-success)] font-medium text-sm">{{ session('success') }}</span>
                    </div>
                    @endif

                    @if(session('info'))
                    <div class="rounded-xl border-[var(--details-info-border)] bg-[var(--details-info-bg)] p-4 mb-6 flex items-center gap-3">
                        <i data-feather="info" class="w-5 h-5 text-[var(--details-info)]"></i>
                        <span class="text-[var(--details-info)] font-medium text-sm">{{ session('info') }}</span>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="rounded-xl border-[var(--details-error-border)] bg-[var(--details-error-bg)] p-4 mb-6 flex items-center gap-3">
                        <i data-feather="alert-circle" class="w-5 h-5 text-[var(--details-error)]"></i>
                        <span class="text-[var(--details-error)] font-medium text-sm">{{ session('error') }}</span>
                    </div>
                    @endif
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="form_type" value="notifications">

                        <div class="mb-5">
                            <h6 class="section-label">{{ __('profile_page.notifications.email_prefs') }}</h6>
                           
                            <div class="space-y-4">
                                <label class="flex items-start gap-3 cursor-pointer p-4 rounded-xl border border-transparent hover:bg-[var(--settings-surface)] hover:border-[var(--settings-border)] transition-all">
                                    <input type="checkbox" name="email_notifications" value="1" {{ $user->email_notifications ? 'checked' : '' }} class="mt-1 w-5 h-5 text-[var(--settings-accent)] border-[var(--settings-border)] rounded focus:ring-[var(--settings-accent)]">
                                    <div>
                                        <span class="block font-bold var(--settings-text-main)">{{ __('profile_page.notifications.offer_updates') }}</span>
                                        <span class="text-sm var(--settings-text-muted)">{{ __('profile_page.notifications.offer_updates_desc') }}</span>
                                    </div>
                                </label>
 
                                <label class="flex items-start gap-3 cursor-pointer p-4 rounded-xl border border-transparent hover:bg-[var(--settings-surface)] hover:border-[var(--settings-border)] transition-all">
                                    <input type="checkbox" name="email_direct_quotes" value="1" {{ $user->email_direct_quotes ? 'checked' : '' }} class="mt-1 w-5 h-5 text-[var(--settings-accent)] border-[var(--settings-border)] rounded focus:ring-[var(--settings-accent)]">
                                    <div>
                                        <span class="block font-bold var(--settings-text-main)">{{ __('profile_page.notifications.direct_quotes') }}</span>
                                        <span class="text-sm var(--settings-text-muted)">{{ __('profile_page.notifications.direct_quotes_desc') }}</span>
                                    </div>
                                </label>
 
                                <label class="flex items-start gap-3 cursor-pointer p-4 rounded-xl border border-transparent hover:bg-[var(--settings-surface)] hover:border-[var(--settings-border)] transition-all">
                                    <input type="checkbox" name="email_task_digest" value="1" id="digest_toggle_profile" {{ $user->email_task_digest ? 'checked' : '' }} class="mt-1 w-5 h-5 text-[var(--settings-accent)] border-[var(--settings-border)] rounded focus:ring-[var(--settings-accent)]">
                                    <div>
                                        <span class="block font-bold var(--settings-text-main)">{{ __('profile_page.notifications.task_digest') }}</span>
                                        <span class="text-sm var(--settings-text-muted)">{{ __('profile_page.notifications.task_digest_desc') }}</span>
                                    </div>
                                </label>
                            </div>
 
                            <!-- category selection (shown only if digest is enabled) -->
                            <div id="category_selection_profile" class="{{ $user->email_task_digest ? '' : 'hidden' }} pl-11 mt-4">
                                <button type="button" id="toggle_categories_btn" class="flex items-center gap-2 text-xs font-bold var(--settings-text-main) mb-3 uppercase tracking-wide hover:opacity-80 transition-opacity cursor-pointer">
                                    <span>{{ __('profile_page.notifications.tracked_categories') }}</span>
                                    <svg id="toggle_categories_chevron" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="transition: transform 0.2s;"><polyline points="6 9 12 15 18 9"></polyline></svg>
                                </button>
                                <div id="categories_grid" class="hidden grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 p-4 border border-[var(--settings-border)] rounded-xl bg-[var(--settings-surface)] max-h-64 overflow-y-auto custom-scroll">
                                    @foreach($categories as $cat)
                                    <label class="flex items-center gap-2 cursor-pointer hover:bg-[var(--settings-bg)] p-2 rounded-lg transition-colors border border-transparent hover:border-[var(--settings-border)] shadow-sm hover:shadow-md">
                                        <input type="checkbox" name="tracked_categories[]" value="{{ $cat->id }}"
                                            {{ $user->trackedCategories->contains($cat->id) ? 'checked' : '' }}
                                            class="w-4 h-4 text-[var(--settings-accent)] border-[var(--settings-border)] rounded focus:ring-[var(--settings-accent)]">
                                        <span class="text-sm var(--settings-text-main) font-medium">{{ __('categories.' . $cat->name) }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
 
                        <div class="mt-8">
                            <button type="submit" class="btn btn-primary-custom px-10 py-3 font-bold text-white shadow-lg shadow-[var(--primary-accent-focus)]">
                                {{ __('profile_page.notifications.save') }}
                            </button>
                        </div>
                    </form>

                    @if($user->email_task_digest)
                    <div class="mt-8 pt-8 border-t border-[var(--settings-border)]">
                        <h6 class="section-label">{{ __('profile_page.notifications.manual_digest') ?? 'Send Manual Digest' }}</h6>
                        <p class="text-sm var(--settings-text-muted) mb-4">{{ __('profile_page.notifications.manual_digest_desc') ?? 'Send an email with the latest tasks from your tracked categories right now!' }}</p>
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
                    <div class="p-4 var(--settings-surface) text-center rounded-xl var(--settings-text-muted) border border-[var(--settings-border)]">{{ __('profile_page.billing.no_payments') }}</div>
                </div>
                @endauth
 
            </div>
        </div>
    </div>
</div>
 
<script type="module">
    import { ProfileManager } from '{{ asset('js/components/profile-manager.js') }}';

    document.addEventListener('DOMContentLoaded', () => {
        new ProfileManager({
            csrf: '{{ csrf_token() }}',
            currentLocale: '{{ app()->getLocale() }}',
            translations: {
                settingsSaved: '{{ __("profile_page.account.apply_success") ?? "Settings applied successfully!" }}',
                confirmUpdate: '{{ __("profile_page.profile.confirm_update") }}',
                confirmDelete: '{{ __("profile_page.profile.confirm_delete") ?? "Are you sure you want to delete your account? This action cannot be undone." }}'
            }
        });
    });
</script>
</div>
@endsection
