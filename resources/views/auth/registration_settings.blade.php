@extends('layout')
 
@section('navbar')
<!-- Override Navbar to be empty/minimal as per design (or just standard) -->
<nav class="bg-[var(--bg-primary)] border-b border-[var(--border-base)] shadow-sm w-full z-50">
    <div class="max-w-7xl mx-auto flex justify-between items-center px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex items-center">
             <a href="{{ route('index') }}" class="flex items-center logo-link">
                <img src="{{ asset('assets/img/logo.png') }}" alt="Minijobz Logo" class="h-8 w-auto logo-img">
            </a>
        </div>
        <div class="flex items-center gap-3">
            <!-- Settings dropdown -->
            <div class="relative">
                <button id="settings-button" class="p-2 rounded-full hover:bg-[var(--bg-hover)] transition text-[var(--text-primary)]" type="button" aria-label="Toggle accessibility settings">
                    <i data-feather="settings"></i>
                </button>
                <div id="settings-menu" class="hidden absolute right-0 mt-2 w-56 bg-[var(--nav-dropdown-bg)] border border-[var(--nav-dropdown-border)] rounded-lg shadow-lg z-[60] opacity-0 translate-y-2 transition-all duration-200 ease-out p-1">
                    <div class="flex flex-col" role="none">
                        <div class="group relative" role="none">
                            <button type="button" class="w-full text-left py-2.5 px-3 text-[var(--text-primary)] font-semibold hover:bg-[var(--nav-dropdown-hover)] rounded-lg flex items-center gap-3 transition-colors">
                                <i data-feather="chevron-left" class="w-4 h-4 text-[var(--nav-muted)] group-hover:-translate-x-0.5 transition-transform"></i>
                                <i data-feather="sun" class="w-4 h-4 text-[var(--nav-muted)]"></i>
                                <span>{{ __('navbar.theme') }}</span>
                            </button>
                            <div class="submenu hidden absolute top-0 right-full w-48 bg-[var(--nav-dropdown-bg)] border border-[var(--nav-dropdown-border)] rounded-lg shadow-lg group-hover:block p-1 z-50" role="menu">
                                <button type="button" class="w-full text-left px-3 py-2 text-sm text-[var(--text-primary)] hover:bg-[var(--nav-dropdown-hover)] rounded-lg transition-colors" data-theme="light" role="menuitem">{{ __('navbar.light') }}</button>
                                <button type="button" class="w-full text-left px-3 py-2 text-sm text-[var(--text-primary)] hover:bg-[var(--nav-dropdown-hover)] rounded-lg transition-colors" data-theme="dark" role="menuitem">{{ __('navbar.dark') }}</button>
                                <button type="button" class="w-full text-left px-3 py-2 text-sm text-[var(--text-primary)] hover:bg-[var(--nav-dropdown-hover)] rounded-lg transition-colors" data-theme="system" role="menuitem">{{ __('navbar.system_default') }}</button>
                            </div>
                        </div>
                        <div class="group relative" role="none">
                            <button type="button" class="w-full text-left py-2.5 px-3 text-[var(--text-primary)] font-semibold hover:bg-[var(--nav-dropdown-hover)] rounded-lg flex items-center gap-3 transition-colors">
                                <i data-feather="chevron-left" class="w-4 h-4 text-[var(--nav-muted)] group-hover:-translate-x-0.5 transition-transform"></i>
                                <i data-feather="globe" class="w-4 h-4 text-[var(--nav-muted)]"></i>
                                <span>{{ __('navbar.language') }}</span>
                            </button>
                            <div class="submenu hidden absolute top-0 right-full w-48 bg-[var(--nav-dropdown-bg)] border border-[var(--nav-dropdown-border)] rounded-lg shadow-lg group-hover:block p-1 z-50" role="menu">
                                <button type="button" class="w-full text-left px-3 py-2 text-sm text-[var(--text-primary)] hover:bg-[var(--nav-dropdown-hover)] rounded-lg transition-colors" data-lang="en" role="menuitem">{{ __('navbar.english') }}</button>
                                <button type="button" class="w-full text-left px-3 py-2 text-sm text-[var(--text-primary)] hover:bg-[var(--nav-dropdown-hover)] rounded-lg transition-colors" data-lang="hu" role="menuitem">{{ __('navbar.hungarian') }}</button>
                            </div>
                        </div>
                        <div class="group relative" role="none">
                            <button type="button" class="w-full text-left py-2.5 px-3 text-[var(--text-primary)] font-semibold hover:bg-[var(--nav-dropdown-hover)] rounded-lg flex items-center gap-3 transition-colors">
                                <i data-feather="chevron-left" class="w-4 h-4 text-[var(--nav-muted)] group-hover:-translate-x-0.5 transition-transform"></i>
                                <i data-feather="eye" class="w-4 h-4 text-[var(--nav-muted)]"></i>
                                <span>{{ __('navbar.accessibility') }}</span>
                            </button>
                            <div class="submenu hidden absolute top-0 right-full w-56 bg-[var(--nav-dropdown-bg)] border border-[var(--nav-dropdown-border)] rounded-lg shadow-lg group-hover:block p-1 z-50" role="menu">
                                <button type="button" data-acc-toggle="reduced-motion" class="w-full text-left px-3 py-2 hover:bg-[var(--nav-dropdown-hover)] rounded flex items-center justify-between text-sm transition-colors text-[var(--text-primary)]" role="menuitem">
                                    <span>{{ __('navbar.reduced_motion') }}</span>
                                    <div id="nav-reduced-motion-indicator" class="acc-slider-track w-8 h-4 bg-[var(--border-base)] rounded-full relative">
                                        <div class="acc-slider-circle absolute rounded-full"></div>
                                    </div>
                                </button>
                                <button type="button" data-acc-toggle="high-contrast" class="w-full text-left px-3 py-2 hover:bg-[var(--nav-dropdown-hover)] rounded flex items-center justify-between text-sm transition-colors text-[var(--text-primary)]" role="menuitem">
                                    <span>{{ __('navbar.high_contrast') }}</span>
                                    <div id="nav-high-contrast-indicator" class="acc-slider-track w-8 h-4 bg-[var(--border-base)] rounded-full relative">
                                        <div class="acc-slider-circle absolute rounded-full"></div>
                                    </div>
                                </button>
                            </div>
                        </div>
                        <div class="group relative" role="none">
                            <button type="button" class="w-full text-left py-2.5 px-3 text-[var(--text-primary)] font-semibold hover:bg-[var(--nav-dropdown-hover)] rounded-lg flex items-center gap-3 transition-colors">
                                <i data-feather="chevron-left" class="w-4 h-4 text-[var(--nav-muted)] group-hover:-translate-x-0.5 transition-transform"></i>
                                <i data-feather="more-horizontal" class="w-4 h-4 text-[var(--nav-muted)]"></i>
                                <span>{{ __('navbar.extras') }}</span>
                            </button>
                            <div class="submenu hidden absolute top-0 right-full w-48 bg-[var(--nav-dropdown-bg)] border border-[var(--nav-dropdown-border)] rounded-lg shadow-lg group-hover:block p-1 z-50" role="menu">
                                <a href="{{ route('help-faq') }}" target="_blank" class="block px-3 py-2 hover:bg-[var(--nav-dropdown-hover)] rounded-lg transition-colors text-[var(--text-primary)] no-underline text-sm" role="menuitem">{{ __('navbar.help_faq') ?? 'Help / FAQ' }}</a>
                                <a href="{{ route('contact-support') }}" target="_blank" class="block px-3 py-2 hover:bg-[var(--nav-dropdown-hover)] rounded-lg transition-colors text-[var(--text-primary)] no-underline text-sm" role="menuitem">{{ __('navbar.contact_support') ?? 'Contact / Support' }}</a>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</nav>
@endsection
 
@section('content')
<div class="min-h-screen bg-[var(--bg-primary)] flex flex-col items-center pt-10 pb-20">
    <div class="w-full max-w-xl px-6">
       
        <!-- Header -->
        <div class="flex items-center justify-center relative mb-10">
            <a href="{{ route('register') }}" class="absolute left-0 text-[var(--text-muted)] hover:text-[var(--text-primary)]">
                <i data-feather="chevron-left" class="w-6 h-6"></i>
            </a>
            <h1 class="text-3xl font-bold text-[var(--primary-accent)]">{{ __('profile_page.registration.setup_account') }}</h1>
        </div>
 
        <form method="POST" action="{{ route('registration_settings.post') }}" id="setup-form">
            @csrf
           
            <!-- First Name -->
            <div class="mb-6">
                <label for="first_name" class="block text-sm font-bold text-[var(--primary-accent)] mb-2">{{ __('profile_page.registration.first_name') }}</label>
                <input type="text" id="first_name" name="first_name"
                       class="w-full px-4 py-3 rounded-lg border border-[var(--border-base)] bg-[var(--bg-secondary)] text-[var(--text-primary)] focus:ring-2 focus:ring-[var(--primary-accent)] outline-none transition-colors"
                       placeholder="John" required value="{{ old('first_name') }}">
                @error('first_name')
                    <p class="text-sm text-[var(--details-error)] mt-1">{{ $message }}</p>
                @enderror
            </div>
 
            <!-- Last Name -->
            <div class="mb-6">
                <label for="last_name" class="block text-sm font-bold text-[var(--primary-accent)] mb-2">{{ __('profile_page.registration.last_name') }}</label>
                <input type="text" id="last_name" name="last_name"
                       class="w-full px-4 py-3 rounded-lg border border-[var(--border-base)] bg-[var(--bg-secondary)] text-[var(--text-primary)] focus:ring-2 focus:ring-[var(--primary-accent)] outline-none transition-colors"
                       placeholder="Doe" required value="{{ old('last_name') }}">
                @error('last_name')
                    <p class="text-sm text-[var(--details-error)] mt-1">{{ $message }}</p>
                @enderror
            </div>
 
            <!-- Home Suburb (City Search) -->
            <div class="mb-8 relative">
                <label for="city_search" class="block text-sm font-bold text-[var(--primary-accent)] mb-2">{{ __('profile_page.registration.home_suburb') }}</label>
                <input type="text" id="city_search"
                       class="w-full px-4 py-3 rounded-lg border border-[var(--border-base)] bg-[var(--bg-secondary)] text-[var(--text-primary)] focus:ring-2 focus:ring-[var(--primary-accent)] outline-none transition-colors"
                       placeholder="{{ __('profile_page.registration.suburb_placeholder') }}" autocomplete="off">
               
                <!-- Hidden inputs for ID submission -->
                <input type="hidden" name="city_id" id="city_id" value="{{ old('city_id') }}">
                <input type="hidden" name="county_id" id="county_id" value="{{ old('county_id') }}">
 
                <div id="city_dropdown" class="absolute left-0 right-0 mt-1 max-h-60 overflow-y-auto bg-[var(--bg-primary)] border border-[var(--border-base)] rounded-lg shadow-xl hidden z-20"></div>
               
                @error('city_id')
                    <p class="text-sm text-[var(--details-error)] mt-1">{{ __('profile_page.registration.invalid_suburb') }}</p>
                @enderror
            </div>
 
 
            <!-- Checkboxes -->
            <div class="space-y-4 mb-8">
               
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="terms_consent" class="w-5 h-5 text-[var(--primary-accent)] border-[var(--border-base)] rounded focus:ring-[var(--primary-accent)]" required>
                    <span class="text-sm text-[var(--text-muted)] leading-snug">
                        {!! __('profile_page.registration.agree_terms', ['terms_url' => route('terms'), 'guidelines_url' => route('guidelines'), 'privacy_url' => route('privacy')]) !!}
                    </span>
                </label>
 
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="email_notifications" value="1" class="w-5 h-5 text-[var(--primary-accent)] border-[var(--border-base)] rounded focus:ring-[var(--primary-accent)]" checked>
                    <span class="text-sm text-[var(--text-muted)] leading-snug">
                        {{ __('profile_page.registration.email_updates') }}
                    </span>
                </label>
 
                <!-- category selection (shown only if digest is enabled) -->
                <div id="category_selection" class="hidden pl-8 mt-2">
                    <p class="text-xs font-bold text-[var(--text-primary)] mb-2 uppercase tracking-wide">{{ __('profile_page.registration.select_categories') }}</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-48 overflow-y-auto p-2 border border-[var(--border-base)] rounded-lg bg-[var(--bg-secondary)]">
                        @foreach($categories as $cat)
                        <label class="flex items-center gap-2 cursor-pointer hover:bg-[var(--bg-primary)] p-1 rounded transition-colors">
                            <input type="checkbox" name="tracked_categories[]" value="{{ $cat->id }}" class="w-4 h-4 text-[var(--primary-accent)] border-[var(--border-base)] rounded">
                            <span class="text-sm text-[var(--text-muted)]">{{ __('categories.' . $cat->name) }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>
 
            <!-- Submit Button -->
            <button type="submit" class="w-full py-4 bg-[var(--border-base)] text-[var(--text-muted)] font-bold rounded-full text-lg hover:bg-[var(--bg-hover)] transition-all disabled:opacity-50 disabled:cursor-not-allowed" id="submit-btn">
                {{ __('profile_page.registration.complete_account') }}
            </button>
 
        </form>
    </div>
</div>
 
<script type="module">
    import { Autocomplete } from '{{ asset('js/modules/autocomplete.js') }}';
    import { Config } from '{{ asset('js/modules/config.js') }}';
 
    // 1. Initialize Autocomplete
    const cityIdInput = document.getElementById('city_id');
    const countyIdInput = document.getElementById('county_id');
    const cityInput = document.getElementById('city_search');
    const submitBtn = document.getElementById('submit-btn');
 
    new Autocomplete({
        input: cityInput,
        dropdown: document.getElementById('city_dropdown'),
        endpoint: Config.api.cities,
        onSelect: (city) => {
            cityIdInput.value = city.id;
            countyIdInput.value = city.county_id;
            updateSubmitState();
        },
        onClear: () => {
            cityIdInput.value = '';
            countyIdInput.value = '';
            updateSubmitState();
        }
    });
 
    // 2. Form Validation Logic
    const form = document.getElementById('setup-form');
    const requiredInputs = form.querySelectorAll('input[required]');
 
    function updateSubmitState() {
        const isFormFilled = Array.from(requiredInputs).every(input => {
            return input.type === 'checkbox' ? input.checked : input.value.trim() !== '';
        });
 
        const isLocationSelected = !!cityIdInput.value;
        const isValid = isFormFilled && isLocationSelected;
 
        submitBtn.disabled = !isValid;
       
        // Dynamic styling using theme-aware variables
        if (isValid) {
            submitBtn.classList.remove('bg-[var(--border-base)]', 'text-[var(--text-muted)]');
            submitBtn.classList.add('bg-[var(--primary-accent)]', 'text-white', 'hover:bg-[var(--primary-hover)]');
        } else {
            submitBtn.classList.add('bg-[var(--border-base)]', 'text-[var(--text-muted)]');
            submitBtn.classList.remove('bg-[var(--primary-accent)]', 'text-white', 'hover:bg-[var(--primary-hover)]');
        }
    }
 
    form.addEventListener('input', updateSubmitState);
    form.addEventListener('change', updateSubmitState);
 
    // Bootstrap feathered icons
    if (window.feather) feather.replace();
 
    // Initial check
    updateSubmitState();
</script>
@endsection