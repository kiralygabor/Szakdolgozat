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
        margin-right: 20px;
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
                
                <a class="nav-link" href="#">{{ __('profile_page.sidebar.dashboard') }}</a>
                <a class="nav-link" id="notification-tab" data-bs-toggle="pill" href="#notification" role="tab">{{ __('profile_page.sidebar.notifications') }}</a>
                
                <!-- Active Tab styling matches the screenshot logic -->
                <a class="nav-link active" id="profile-tab" data-bs-toggle="pill" href="#profile" role="tab">{{ __('profile_page.sidebar.profile') }}</a>
                <a class="nav-link" id="account-tab" data-bs-toggle="pill" href="#account" role="tab">{{ __('profile_page.sidebar.settings') }}</a>
                <a class="nav-link" id="security-tab" data-bs-toggle="pill" href="#security" role="tab">{{ __('profile_page.sidebar.security') }}</a>
                <a class="nav-link" id="billing-tab" data-bs-toggle="pill" href="#billing" role="tab">{{ __('profile_page.sidebar.billing') }}</a>
            </nav>
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 md:pl-10">
            
            <div class="tab-content" id="settingsTabContent">
                
                <!-- Profile Tab (Matches Screenshot) -->
                <div class="tab-pane fade show active" id="profile" role="tabpanel">
                    
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

                <!-- Account Tab (Settings) -->
                <div class="tab-pane fade" id="account" role="tabpanel">
                    <h1 class="page-title">{{ __('profile_page.account.title') }}</h1>
                    
                    <div class="mb-5 custom-input-group">
                        <h6 class="section-label">{{ __('navbar.language') }}</h6>
                        <select id="lang-select" class="form-control form-control-custom w-full md:w-1/2">
                            <option value="en" @selected(app()->getLocale() == 'en')>English</option>
                            <option value="hu" @selected(app()->getLocale() == 'hu')>Hungarian</option>
                        </select>
                    </div>

                    <div class="mb-5 custom-input-group">
                        <h6 class="section-label">{{ __('navbar.theme') }}</h6>
                        <select id="theme-select" class="form-control form-control-custom w-full md:w-1/2">
                            <option value="light">{{ __('navbar.light') }}</option>
                            <option value="dark">{{ __('navbar.dark') }}</option>
                            <option value="system">{{ __('navbar.system_default') }}</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <button type="button" id="apply-settings-btn" class="btn btn-primary btn-primary-custom px-5">
                            {{ __('Apply') }}
                        </button>
                    </div>

                    <form>
                        <hr class="my-4">
                        <div class="mb-3">
                            <label class="text-danger fw-bold">{{ __('profile_page.account.delete_title') }}</label>
                            <p class="text-muted small">{{ __('profile_page.account.delete_desc') }}</p>
                            <button class="btn btn-danger rounded-pill px-4" type="button">{{ __('profile_page.account.delete_btn') }}</button>
                        </div>
                    </form>
                </div>

                <!-- Security Tab -->
                <div class="tab-pane fade" id="security" role="tabpanel">
                    <h1 class="page-title">{{ __('profile_page.security.title') }}</h1>
                    <form>
                        <div class="mb-4 custom-input-group">
                            <label class="form-label">{{ __('profile_page.security.old_password') }}</label>
                            <input type="password" class="form-control form-control-custom mb-3">
                            
                            <label class="form-label">{{ __('profile_page.security.new_password') }}</label>
                            <input type="password" class="form-control form-control-custom mb-3">
                            
                            <label class="form-label">{{ __('profile_page.security.confirm_password') }}</label>
                            <input type="password" class="form-control form-control-custom">
                        </div>
                        <button class="btn btn-primary btn-primary-custom px-4">{{ __('profile_page.security.update_password') }}</button>
                    </form>
                </div>

                <!-- Notification Tab -->
                <div class="tab-pane fade" id="notification" role="tabpanel">
                    <h1 class="page-title">{{ __('profile_page.notifications.title') }}</h1>
                    <form>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" value="" id="emailAlerts" checked>
                            <label class="form-check-label" for="emailAlerts">
                                {{ __('profile_page.notifications.email_alerts') }}
                            </label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" value="" id="emailSummary" checked>
                            <label class="form-check-label" for="emailSummary">
                                {{ __('profile_page.notifications.email_summary') }}
                            </label>
                        </div>
                        <button class="btn btn-primary btn-primary-custom px-4 mt-2">{{ __('profile_page.notifications.save') }}</button>
                    </form>
                </div>

                <!-- Billing Tab -->
                <div class="tab-pane fade" id="billing" role="tabpanel">
                    <h1 class="page-title">{{ __('profile_page.billing.title') }}</h1>
                    <button class="btn btn-primary btn-primary-custom mb-4" type="button">{{ __('profile_page.billing.add_method') }}</button>
                    <div class="p-4 bg-light text-center rounded text-muted">{{ __('profile_page.billing.no_payments') }}</div>
                </div>

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
    });
</script>

@endsection