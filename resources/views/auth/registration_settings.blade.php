@extends('layout')

@section('navbar')
<!-- Override Navbar to be empty/minimal as per design (or just standard) -->
<nav class="bg-white border-b border-gray-200 shadow-sm w-full z-50">
    <div class="w-full flex justify-between items-center px-6 py-4">
        <div class="flex items-center">
             <a href="{{ route('index') }}" class="flex items-center">
                <img src="{{ asset('assets/img/logo.png') }}" alt="Minijobz Logo" class="h-8 w-auto">
            </a>
        </div>
        <div>
            <a href="{{ route('logout') }}" class="text-gray-500 hover:text-gray-700 font-medium flex items-center gap-1">
                Cancel <span class="text-xl leading-none">&times;</span>
            </a>
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
            <h1 class="text-3xl font-bold text-blue-900">Set up your account</h1>
        </div>

        <form method="POST" action="{{ route('registration_settings.post') }}" id="setup-form">
            @csrf
            
            <!-- Hidden fields for backend requirements not in design -->
            <input type="hidden" name="birthdate" value="2000-01-01">
            <input type="hidden" name="phone_number" value="+36300000000">

            <!-- First Name -->
            <div class="mb-6">
                <label for="first_name" class="block text-sm font-bold text-blue-900 mb-2">First name *</label>
                <input type="text" id="first_name" name="first_name" 
                       class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors"
                       placeholder="John" required value="{{ old('first_name') }}">
                @error('first_name')
                    <p class="text-sm text-orange-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Last Name -->
            <div class="mb-6">
                <label for="last_name" class="block text-sm font-bold text-blue-900 mb-2">Last name *</label>
                <input type="text" id="last_name" name="last_name" 
                       class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors"
                       placeholder="Doe" required value="{{ old('last_name') }}">
                @error('last_name')
                    <p class="text-sm text-orange-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Home Suburb (City Search) -->
            <div class="mb-8 relative">
                <label for="city_search" class="block text-sm font-bold text-blue-900 mb-2">Enter your home suburb *</label>
                <input type="text" id="city_search" 
                       class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors"
                       placeholder="Enter a suburb" autocomplete="off">
                
                <!-- Hidden inputs for ID submission -->
                <input type="hidden" name="city_id" id="city_id" value="{{ old('city_id') }}">
                <input type="hidden" name="county_id" id="county_id" value="{{ old('county_id') }}">

                <div id="city_dropdown" class="absolute left-0 right-0 mt-1 max-h-60 overflow-y-auto bg-white border border-gray-200 rounded-lg shadow-xl hidden z-20"></div>
                
                @error('city_id')
                    <p class="text-sm text-orange-500 mt-1">Please select a valid suburb from the list.</p>
                @enderror
            </div>


            <!-- Checkboxes -->
            <div class="space-y-4 mb-8">
               
                <label class="flex items-start gap-3 cursor-pointer">
                    <input type="checkbox" name="terms_consent" class="mt-1 w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500" required>
                    <span class="text-sm text-gray-600 leading-snug">
                        I agree to the Minijobz <a href="#" class="text-blue-600 hover:underline">Terms & Conditions</a>, <a href="#" class="text-blue-600 hover:underline">Community Guidelines</a>, and <a href="#" class="text-blue-600 hover:underline">Privacy Policy</a> *
                    </span>
                </label>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full py-4 bg-gray-200 text-gray-500 font-bold rounded-full text-lg hover:bg-gray-300 transition-colors disabled:opacity-50 disabled:cursor-not-allowed" id="submit-btn">
                Complete my account
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
