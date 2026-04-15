<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ __('auth_pages.register.title') }} - Minijobz</title>
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="{{ asset('css/pages/auth.css') }}">
</head>
<body class="{{ (request()->cookie('theme') === 'dark' ? 'dark' : '') }} {{ (request()->cookie('contrast') === 'high' ? 'high-contrast' : '') }}">
 
<div class="auth-wrapper">
  <div class="auth-box">
    <div style="margin-bottom: 24px;">
      <a href="{{ route('index') }}" class="logo-link">
        <img src="{{ asset('assets/img/logo.png') }}" alt="Minijobz" style="height: 48px; width: auto;" class="logo-img">
      </a>
    </div>
    <h2 class="auth-title">{{ __('auth_pages.register.title') }}</h2>
 
    <!-- Success / Warning / Errors -->
    @if (session('status'))
      <div class="alert alert-success mt-4">{{ session('status') }}</div>
    @endif
    @if (session('warning'))
      <div class="alert alert-warning mt-4">{{ session('warning') }}</div>
    @endif
    @if ($errors->any())
      <div class="alert alert-danger mt-4">{{ $errors->first() }}</div>
    @endif
 
    <form method="POST" action="{{ route('register.post') }}">
      @csrf
 
      <!-- Email -->
      <div class="mb-3">
        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
               placeholder="{{ __('auth_pages.register.email_placeholder') }}"
               value="{{ $prefill['email'] ?? old('email') }}" required>
        @error('email')
          <span class="text-danger small">{{ $message }}</span>
        @enderror
      </div>
 
      <!-- Password -->
      <div class="mb-3">
        <div class="position-relative">
          <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror"
                 placeholder="{{ __('auth_pages.register.password_placeholder') }}" required>
          <i class="fa fa-eye-slash position-absolute top-50 end-0 translate-middle-y me-3 password-toggle" 
             data-target="password"></i>
        </div>
        @error('password')
          <span class="text-danger small">{{ $message }}</span>
        @enderror
      </div>

      <!-- Confirm Password -->
      <div class="mb-4">
        <div class="position-relative">
          <input type="password" name="password_confirmation" id="password_confirmation"
            class="form-control @error('password_confirmation') is-invalid @enderror"
            placeholder="{{ __('auth_pages.register.confirm_password_placeholder') }}" required>
          <i class="fa fa-eye-slash position-absolute top-50 end-0 translate-middle-y me-3 password-toggle"
            data-target="password_confirmation"></i>
        </div>
        @error('password_confirmation')
          <span class="text-danger small">{{ $message }}</span>
        @enderror
      </div>
 
      <!-- Signup Button -->
      <div class="d-grid mb-3">
        <button type="submit" class="btn btn-primary">{{ __('auth_pages.register.signup_btn') }}</button>
      </div>
 
      <div class="auth-links mb-3">
        <p class="mb-1">{{ __('auth_pages.register.have_account') }} <a href="{{ route('login') }}">{{ __('auth_pages.register.login_link') }}</a></p>
      </div>
 
      <div class="divider">{{ __('auth_pages.register.or') }}</div>
 
      <!-- Google Continue -->
      <div class="d-grid mb-2">
        <a href="{{ route('login.google') }}" class="btn btn-outline">
          <img src="https://www.svgrepo.com/show/355037/google.svg" alt="Google" width="20" class="me-2">
          {{ __('auth_pages.register.continue_google') }}
        </a>
      </div>
 
      <!-- Facebook Continue -->
      <div class="d-grid">
        <a href="{{ route('login.facebook') }}" class="btn btn-outline">
          <i class="fa-brands fa-facebook me-2" style="color: #1877F2; font-size: 20px;"></i>
          {{ __('auth_pages.register.continue_facebook') }}
        </a>
      </div>
    </form>
  </div>
</div>
 
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script type="module">
  import { AuthManager } from "{{ asset('js/components/auth-manager.js') }}";
  document.addEventListener('DOMContentLoaded', () => {
      new AuthManager();
      
      const isDark = document.cookie.includes('theme=dark');
      if (isDark) document.documentElement.classList.add('dark');
      const isHC = document.cookie.includes('contrast=high');
      if (isHC) document.documentElement.classList.add('high-contrast');
  });
</script>
</body>
</html>
