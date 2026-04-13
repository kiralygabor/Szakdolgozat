@extends('layout')
 
@section('navbar')
<!-- Override Navbar to be empty/minimal as per design (or just standard) -->
<nav class="bg-white border-b border-gray-200 shadow-sm w-full z-50">
    <div class="max-w-7xl mx-auto flex justify-between items-center px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex items-center">
             <a href="{{ route('index') }}" class="flex items-center logo-link">
                <img src="{{ asset('assets/img/logo.png') }}" alt="Minijobz Logo" class="h-8 w-auto logo-img">
            </a>
        </div>
        <div class="flex items-center gap-3">
            <!-- Settings dropdown -->
            <div class="relative">
                <button id="settings-button" class="p-2 rounded-full hover:bg-gray-200 transition" type="button">
                    <i data-feather="settings"></i>
                </button>
                <div id="settings-menu" class="hidden absolute right-0 mt-2 w-40 bg-white border border-gray-300 rounded-lg shadow-lg z-[60] opacity-0 translate-y-2 transition-all duration-200 ease-out">
                    <div class="flex flex-col">
                        <div class="group relative">
                            <div class="py-2 px-4 text-gray-700 font-semibold hover:bg-gray-100 cursor-pointer flex items-center gap-2">
                                <i data-feather="chevron-left" class="w-4 h-4"></i>
                                {{ __('navbar.theme') }}
                            </div>
                            <div class="submenu absolute top-0 right-full w-48 bg-white border border-gray-200 rounded-lg shadow-lg opacity-0 scale-95 transform transition-all duration-200 ease-out pointer-events-none group-hover:opacity-100 group-hover:scale-100 group-hover:pointer-events-auto">
                                <div class="px-4 py-2 hover:bg-gray-100 cursor-pointer" data-theme="light">{{ __('navbar.light') }}</div>
                                <div class="px-4 py-2 hover:bg-gray-100 cursor-pointer" data-theme="dark">{{ __('navbar.dark') }}</div>
                                <div class="px-4 py-2 hover:bg-gray-100 cursor-pointer" data-theme="system">{{ __('navbar.system_default') }}</div>
                            </div>
                        </div>
                        <div class="group relative">
                            <div class="py-2 px-4 text-gray-700 font-semibold hover:bg-gray-100 cursor-pointer flex items-center gap-2">
                                <i data-feather="chevron-left" class="w-4 h-4"></i>
                                {{ __('navbar.language') }}
                            </div>
                            <div class="submenu absolute top-0 right-full w-48 bg-white border border-gray-200 rounded-lg shadow-lg opacity-0 scale-95 transform transition-all duration-200 ease-out pointer-events-none group-hover:opacity-100 group-hover:scale-100 group-hover:pointer-events-auto">
                                <div class="px-4 py-2 hover:bg-gray-100 cursor-pointer" data-lang="en">{{ __('navbar.english') }}</div>
                                <div class="px-4 py-2 hover:bg-gray-100 cursor-pointer" data-lang="hu">{{ __('navbar.hungarian') }}</div>
                            </div>
                        </div>
                        <div class="group relative" id="nav-accessibility-section">
                            <div class="py-2 px-4 text-gray-700 font-semibold hover:bg-gray-100 cursor-pointer flex items-center gap-2">
                                <i data-feather="chevron-left" class="w-4 h-4" aria-hidden="true"></i>
                                {{ __('navbar.accessibility') }}
                            </div>
                            <div class="submenu absolute top-0 right-full w-56 bg-white border border-gray-200 rounded-lg shadow-lg opacity-0 scale-95 transform transition-all duration-200 ease-out pointer-events-none group-hover:opacity-100 group-hover:scale-100 group-hover:pointer-events-auto p-1">
                                <button type="button" onclick="toggleAccessibilitySetting('reduced-motion')" class="w-full text-left px-3 py-2 hover:bg-gray-100 rounded flex items-center justify-between text-sm">
                                    <span>{{ __('navbar.reduced_motion') }}</span>
                                    <div id="nav-reduced-motion-indicator" class="w-8 h-4 bg-gray-200 rounded-full relative transition-colors">
                                        <div class="dot absolute top-0.5 left-0.5 w-3 h-3 bg-white rounded-full transition-transform"></div>
                                    </div>
                                </button>
                                <button type="button" onclick="toggleAccessibilitySetting('high-contrast')" class="w-full text-left px-3 py-2 hover:bg-gray-100 rounded flex items-center justify-between text-sm">
                                    <span>{{ __('navbar.high_contrast') }}</span>
                                    <div id="nav-high-contrast-indicator" class="w-8 h-4 bg-gray-200 rounded-full relative transition-colors">
                                        <div class="dot absolute top-0.5 left-0.5 w-3 h-3 bg-white rounded-full transition-transform"></div>
                                    </div>
                                </button>
                            </div>
                        </div>
                        <div class="group relative">
                            <div class="py-2 px-4 text-gray-700 font-semibold hover:bg-gray-100 cursor-pointer flex items-center gap-2">
                                <i data-feather="chevron-left" class="w-4 h-4"></i>
                                {{ __('navbar.extras') }}
                            </div>
                            <div class="submenu absolute top-0 right-full w-48 bg-white border border-gray-200 rounded-lg shadow-lg opacity-0 scale-95 transform transition-all duration-200 ease-out pointer-events-none group-hover:opacity-100 group-hover:scale-100 group-hover:pointer-events-auto">
                                <a href="{{ route('help-faq') }}" target="_blank" class="block px-4 py-2 hover:bg-gray-100 cursor-pointer text-gray-700 no-underline">{{ __('navbar.help_faq') ?? 'Help / FAQ' }}</a>
                                <a href="{{ route('contact-support') }}" target="_blank" class="block px-4 py-2 hover:bg-gray-100 cursor-pointer text-gray-700 no-underline">{{ __('navbar.contact_support') ?? 'Contact / Support' }}</a>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</nav>
@endsection
 
@section('content')
<div class="min-h-screen bg-white flex flex-col items-center pt-10 pb-20">
    <div class="w-full max-w-xl px-6">
       
        <!-- Header -->
        <div class="flex items-center justify-center relative mb-10">
            <a href="{{ route('register') }}" class="absolute left-0 text-gray-400 hover:text-gray-600">
                <i data-feather="chevron-left" class="w-6 h-6"></i>
            </a>
            <h1 class="text-3xl font-bold text-blue-900">{{ __('profile_page.registration.setup_account') }}</h1>
        </div>
 
        <form method="POST" action="{{ route('registration_settings.post') }}" id="setup-form">
            @csrf
           
            <!-- Hidden fields no longer needed (now nullable in DB) -->

 
            <!-- First Name -->
            <div class="mb-6">
                <label for="first_name" class="block text-sm font-bold text-blue-900 mb-2">{{ __('profile_page.registration.first_name') }}</label>
                <input type="text" id="first_name" name="first_name"
                       class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors"
                       placeholder="John" required value="{{ old('first_name') }}">
                @error('first_name')
                    <p class="text-sm text-orange-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
 
            <!-- Last Name -->
            <div class="mb-6">
                <label for="last_name" class="block text-sm font-bold text-blue-900 mb-2">{{ __('profile_page.registration.last_name') }}</label>
                <input type="text" id="last_name" name="last_name"
                       class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors"
                       placeholder="Doe" required value="{{ old('last_name') }}">
                @error('last_name')
                    <p class="text-sm text-orange-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
 
            <!-- Home Suburb (City Search) -->
            <div class="mb-8 relative">
                <label for="city_search" class="block text-sm font-bold text-blue-900 mb-2">{{ __('profile_page.registration.home_suburb') }}</label>
                <input type="text" id="city_search"
                       class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors"
                       placeholder="{{ __('profile_page.registration.suburb_placeholder') }}" autocomplete="off">
               
                <!-- Hidden inputs for ID submission -->
                <input type="hidden" name="city_id" id="city_id" value="{{ old('city_id') }}">
                <input type="hidden" name="county_id" id="county_id" value="{{ old('county_id') }}">
 
                <div id="city_dropdown" class="absolute left-0 right-0 mt-1 max-h-60 overflow-y-auto bg-white border border-gray-200 rounded-lg shadow-xl hidden z-20"></div>
               
                @error('city_id')
                    <p class="text-sm text-orange-500 mt-1">{{ __('profile_page.registration.invalid_suburb') }}</p>
                @enderror
            </div>
 
 
            <!-- Checkboxes -->
            <div class="space-y-4 mb-8">
               
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="terms_consent" class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500" required>
                    <span class="text-sm text-gray-600 leading-snug">
                        {!! __('profile_page.registration.agree_terms', ['terms_url' => route('terms'), 'guidelines_url' => route('guidelines'), 'privacy_url' => route('privacy')]) !!}
                    </span>
                </label>
 
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="email_notifications" value="1" class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500" checked>
                    <span class="text-sm text-gray-600 leading-snug">
                        {{ __('profile_page.registration.email_updates') }}
                    </span>
                </label>
 
                <!-- category selection (shown only if digest is enabled) -->
                <div id="category_selection" class="hidden pl-8 mt-2">
                    <p class="text-xs font-bold text-blue-900 mb-2 uppercase tracking-wide">{{ __('profile_page.registration.select_categories') }}</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-48 overflow-y-auto p-2 border border-blue-50 rounded-lg bg-blue-50/30">
                        @foreach($categories as $cat)
                        <label class="flex items-center gap-2 cursor-pointer hover:bg-white p-1 rounded transition-colors">
                            <input type="checkbox" name="tracked_categories[]" value="{{ $cat->id }}" class="w-4 h-4 text-blue-600 border-gray-300 rounded">
                            <span class="text-sm text-gray-600">{{ __('categories.' . $cat->name) }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>
 
            <!-- Submit Button -->
            <button type="submit" class="w-full py-4 bg-gray-200 text-gray-500 font-bold rounded-full text-lg hover:bg-gray-300 transition-colors disabled:opacity-50 disabled:cursor-not-allowed" id="submit-btn">
                {{ __('profile_page.registration.complete_account') }}
            </button>
 
        </form>
    </div>
</div>
 
<script>
    // Feather Icons
    if (window.feather && typeof window.feather.replace === 'function') {
        window.feather.replace();
    }
 
 
 
    // City Autocomplete Logic
    const cityInput = document.getElementById('city_search');
    const cityDropdown = document.getElementById('city_dropdown');
    const cityIdInput = document.getElementById('city_id');
    const countyIdInput = document.getElementById('county_id');
    const submitBtn = document.getElementById('submit-btn');
    let searchTimeout;
 
    cityInput.addEventListener('input', function(e) {
        clearTimeout(searchTimeout);
        const q = e.target.value.trim();
       
        // Reset IDs if user types
        cityIdInput.value = '';
        countyIdInput.value = '';
        updateSubmitButton();
 
        if (q.length < 2) {
            cityDropdown.classList.add('hidden');
            return;
        }
 
        searchTimeout = setTimeout(async () => {
            try {
                const res = await fetch(`/api/cities?q=${encodeURIComponent(q)}`);
                const cities = await res.json();
               
                cityDropdown.innerHTML = '';
                if (cities.length > 0) {
                    cityDropdown.classList.remove('hidden');
                    cities.forEach(city => {
                        const div = document.createElement('div');
                        div.className = 'px-4 py-3 hover:bg-blue-50 cursor-pointer text-gray-700 text-sm border-b border-gray-100 last:border-0';
                        div.textContent = city.name;
                        div.onclick = () => {
                            cityInput.value = city.name;
                            cityIdInput.value = city.id;
                            countyIdInput.value = city.county_id; // Using the new field
                            cityDropdown.classList.add('hidden');
                            updateSubmitButton();
                        };
                        cityDropdown.appendChild(div);
                    });
                } else {
                    cityDropdown.classList.add('hidden');
                }
            } catch (err) {
                console.error(err);
            }
        }, 300);
    });
 
    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!cityInput.contains(e.target) && !cityDropdown.contains(e.target)) {
            cityDropdown.classList.add('hidden');
        }
    });
 
    // Form Validation for Button State
    const form = document.getElementById('setup-form');
    const inputs = form.querySelectorAll('input[required]');
   
    function updateSubmitButton() {
        let isValid = true;
        inputs.forEach(input => {
            if (input.type === 'checkbox') {
                if (!input.checked) isValid = false;
            } else {
                if (!input.value.trim()) isValid = false;
            }
        });
       
        // Also check hidden city_id
        if (!cityIdInput.value) isValid = false;
 
        if (isValid) {
            submitBtn.classList.remove('bg-gray-200', 'text-gray-500');
            submitBtn.classList.add('bg-blue-600', 'text-white', 'hover:bg-blue-700');
            submitBtn.disabled = false;
        } else {
            submitBtn.classList.add('bg-gray-200', 'text-gray-500');
            submitBtn.classList.remove('bg-blue-600', 'text-white', 'hover:bg-blue-700');
            submitBtn.disabled = true;
        }
    }
 
    inputs.forEach(input => {
        input.addEventListener('input', updateSubmitButton);
        input.addEventListener('change', updateSubmitButton);
    });
 
    // Initial check
    updateSubmitButton();
</script>
@endsection