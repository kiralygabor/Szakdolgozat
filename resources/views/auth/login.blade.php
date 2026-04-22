<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ __('auth_pages.login.title') }} - Minijobz</title>
  
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
    <h2 class="auth-title">{{ __('auth_pages.login.title') }}</h2>
 
    <!-- Success / Warning / Errors -->
    @if (session('status'))
      <div class="alert alert-success">{{ session('status') }}</div>
    @endif
    @if (session('warning'))
      <div class="alert alert-warning">{{ session('warning') }}</div>
    @endif
    @if ($errors->any())
      <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif
 
    <form method="POST" action="{{ route('login.post') }}">
      @csrf
      @if(request()->has('returnUrl'))
        <input type="hidden" name="returnUrl" value="{{ request('returnUrl') }}">
      @endif
 
      <!-- Email -->
      <div class="mb-3">
        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
               placeholder="{{ __('auth_pages.login.email_placeholder') }}" required value="{{ old('email') }}">
        @error('email')
          <span class="text-danger small">{{ $message }}</span>
        @enderror
      </div>
 
      <!-- Password -->
      <div class="mb-3">
        <div class="position-relative">
          <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror"
                 placeholder="{{ __('auth_pages.login.password_placeholder') }}" required>
          <i class="fa fa-eye-slash position-absolute top-50 end-0 translate-middle-y me-3 password-toggle" id="togglePassword"></i>
        </div>
        @error('password')
          <span class="text-danger small">{{ $message }}</span>
        @enderror
      </div>
 
      <!-- Remember Me + Forgot Password -->
      <div class="remember-forgot">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="rememberMe" id="rememberMe" {{ old('rememberMe') ? 'checked' : '' }}>
          <label class="form-check-label" for="rememberMe">{{ __('auth_pages.login.remember_me') }}</label>
        </div>
        <!-- Trigger Modal -->
        <a href="#" class="auth-links" data-bs-toggle="modal" data-bs-target="#forgotPasswordModal">
          {{ __('auth_pages.login.forgot_password') }}
        </a>
      </div>
 
      <!-- Login Button -->
      <div class="d-grid mb-3">
        <button type="submit" class="btn btn-primary">{{ __('auth_pages.login.login_btn') }}</button>
      </div>
 
      <div class="auth-links mb-3">
        <p class="mb-1">{{ __('auth_pages.login.no_account') }} <a href="{{ route('register') }}">{{ __('auth_pages.login.signup_link') }}</a></p>
      </div>
 
      <div class="divider">{{ __('auth_pages.login.or') }}</div>
 
      <!-- Google Continue -->
      <div class="d-grid mb-2">
        <a href="{{ route('login.google') }}" class="btn btn-outline">
          <img src="https://www.svgrepo.com/show/355037/google.svg" alt="Google" width="20" class="me-2">
          {{ __('auth_pages.login.login_google') }}
        </a>
      </div>
 
      <!-- Facebook Continue -->
      <div class="d-grid">
        <a href="{{ route('login.facebook') }}" class="btn btn-outline">
          <i class="fa-brands fa-facebook me-2" style="color: #1877F2; font-size: 20px;"></i>
          {{ __('auth_pages.login.login_facebook') }}
        </a>
      </div>
    </form>
  </div>
</div>
 
<!-- Forgot Password Modal -->
<div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-labelledby="forgotPasswordModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content p-4">
      <div class="modal-header border-0">
        <h5 class="modal-title auth-title" id="forgotPasswordModalLabel">{{ __('auth_pages.forgot_password.reset_title') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
 
      <div class="modal-body">
        <form method="POST" action="{{ route('password.email') }}">
          @csrf
          <div class="mb-3">
            <input type="email" name="email" class="form-control" placeholder="{{ __('auth_pages.forgot_password.email_placeholder') }}" required>
          </div>
          <div class="d-grid">
            <button type="submit" class="btn btn-primary">{{ __('auth_pages.forgot_password.send_link_btn') }}</button>
          </div>
        </form>
      </div>
 
    </div>
  </div>
</div>
 
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script type="module">
  import { AuthManager } from "{{ asset('js/components/auth-manager.js') }}";
  document.addEventListener('DOMContentLoaded', () => {
      new AuthManager();
      
      // Theme enforcement from cookie logic if necessary
      const isDark = document.cookie.includes('theme=dark');
      if (isDark) document.documentElement.classList.add('dark');
      const isHC = document.cookie.includes('contrast=high');
      if (isHC) document.documentElement.classList.add('high-contrast');
  });
</script>
</body>
</html>
