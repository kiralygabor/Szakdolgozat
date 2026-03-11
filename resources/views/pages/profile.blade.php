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
</style>
 
<div class="max-w-7xl mx-auto px-6 py-10">
    <div class="flex flex-col md:flex-row gap-10">
       
        <!-- Sidebar Navigation (aligned with Logo) -->
        <div class="md:w-1/5 mb-8 md:mb-0">
            <!-- Optional: User Brief Info -->
            <div class="text-center mb-4 pb-3 border-bottom d-md-none">
                <h5>My Settings</h5>
            </div>
 
            <nav class="nav flex-column nav-pills settings-nav" id="settingsTab" role="tablist" aria-orientation="vertical">
                <a class="nav-link" href="{{ url('/index') }}"><i class="fas fa-arrow-left fa-fw"></i> {{ __('profile_page.sidebar.back_home') }}</a>
                <div class="my-2 border-bottom"></div>
               
                @auth
                <a class="nav-link" href="{{ route('my-tasks') }}"><i class="fas fa-columns fa-fw"></i> {{ __('profile_page.sidebar.dashboard') }}</a>
                <a class="nav-link" id="notification-tab" data-bs-toggle="pill" href="#notification" role="tab">{{ __('profile_page.sidebar.notifications') }}</a>
               
                <!-- Active Tab styling matches the screenshot logic -->
                <a class="nav-link {{ auth()->check() ? 'active' : '' }}" id="profile-tab" data-bs-toggle="pill" href="#profile" role="tab">{{ __('profile_page.sidebar.profile') }}</a>
                @endauth
                <a class="nav-link {{ !auth()->check() ? 'active' : '' }}" id="account-tab" data-bs-toggle="pill" href="#account" role="tab">{{ __('profile_page.sidebar.settings') }}</a>
                @auth
                <a class="nav-link" id="security-tab" data-bs-toggle="pill" href="#security" role="tab">{{ __('profile_page.sidebar.security') }}</a>
                <a class="nav-link" id="billing-tab" data-bs-toggle="pill" href="#billing" role="tab">{{ __('profile_page.sidebar.billing') }}</a>
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
                                value="{{ $user->birthdate ? $user->birthdate->format('Y-m-d') : '' }}">
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
                            <input
                                type="text"
                                class="form-control form-control-custom"
                                id="phone"
                                name="phone_number"
                                value="{{ old('phone_number', $user->phone_number ?? '') }}">
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
                                <option value="en" @selected(app()->getLocale() == 'en')>English</option>
                                <option value="hu" @selected(app()->getLocale() == 'hu')>Hungarian</option>
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
 
                        <div class="mb-5">
                            <button type="button" id="apply-settings-btn" class="btn btn-primary btn-primary-custom px-8">
                                {{ __('Apply') }}
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
                   
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')
                       
                        <!-- Hidden profile fields to avoid overwriting them with empty if not in this tab -->
                        <input type="hidden" name="first_name" value="{{ $user->first_name }}">
                        <input type="hidden" name="last_name" value="{{ $user->last_name }}">
                        <input type="hidden" name="email" value="{{ $user->email }}">
                        <input type="hidden" name="phone_number" value="{{ $user->phone_number }}">
                        <input type="hidden" name="birthdate" value="{{ $user->birthdate ? $user->birthdate->format('Y-m-d') : '' }}">
                        <input type="hidden" name="city_id" value="{{ $user->city_id }}">
 
                        <div class="mb-5">
                            <h6 class="section-label">Email Preferences</h6>
                           
                            <div class="space-y-4">
                                <label class="flex items-start gap-3 cursor-pointer p-3 rounded-lg hover:bg-gray-50 transition-colors">
                                    <input type="checkbox" name="email_notifications" value="1" {{ $user->email_notifications ? 'checked' : '' }} class="mt-1 w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <div>
                                        <span class="block font-bold text-blue-900">Offer Updates</span>
                                        <span class="text-sm text-gray-600">Email me when I receive an offer on my task or when my offer is accepted</span>
                                    </div>
                                </label>
 
                                <label class="flex items-start gap-3 cursor-pointer p-3 rounded-lg hover:bg-gray-50 transition-colors">
                                    <input type="checkbox" name="email_task_digest" value="1" id="digest_toggle_profile" {{ $user->email_task_digest ? 'checked' : '' }} class="mt-1 w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <div>
                                        <span class="block font-bold text-blue-900">Task Digest</span>
                                        <span class="text-sm text-gray-600">Send me a daily summary of new tasks posted in categories I follow</span>
                                    </div>
                                </label>
                            </div>
 
                            <!-- category selection (shown only if digest is enabled) -->
                            <div id="category_selection_profile" class="{{ $user->email_task_digest ? '' : 'hidden' }} pl-11 mt-4">
                                <p class="text-xs font-bold text-blue-900 mb-3 uppercase tracking-wide">Tracked Categories:</p>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 p-4 border border-blue-100 rounded-xl bg-blue-50/30 max-h-64 overflow-y-auto">
                                    @foreach($categories as $cat)
                                    <label class="flex items-center gap-2 cursor-pointer hover:bg-white p-2 rounded-lg transition-colors border border-transparent hover:border-blue-100">
                                        <input type="checkbox" name="tracked_categories[]" value="{{ $cat->id }}"
                                            {{ $user->trackedCategories->contains($cat->id) ? 'checked' : '' }}
                                            class="w-4 h-4 text-blue-600 border-gray-300 rounded">
                                        <span class="text-sm text-gray-700 font-medium">{{ $cat->name }}</span>
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
                    localStorage.setItem('theme', 'system');
                    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                    root.classList.toggle('dark', prefersDark);
                } else if (mode === 'dark') {
                    root.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                } else {
                    root.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
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
 
            // Init select state
            const savedTheme = localStorage.getItem('theme') || 'system';
            if (themeSelect) themeSelect.value = savedTheme;
 
            if (applyBtn) {
                applyBtn.addEventListener('click', function() {
                    console.log('Apply button clicked');
                   
                    // 1. Apply Theme
                    if (themeSelect) {
                        applyTheme(themeSelect.value);
                        console.log('Theme applied:', themeSelect.value);
                    }
 
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
            if (!digestToggle || !categorySelection) return;
 
            digestToggle.addEventListener('change', function() {
                if (this.checked) {
                    categorySelection.classList.remove('hidden');
                } else {
                    categorySelection.classList.add('hidden');
                }
            });
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
    });
</script>
 
@endsection