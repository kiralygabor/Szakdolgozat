<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ __('auth_pages.verify_code.title') }} - Minijobz</title>
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="{{ asset('css/pages/auth.css') }}">
  
  <script>
    (function(){
      var root = document.documentElement;
      // Force theme enforcement based on cookies before render
      if (document.cookie.includes('theme=dark')) root.classList.add('dark');
      if (document.cookie.includes('contrast=high')) root.classList.add('high-contrast');
    })();
  </script>
</head>
<body class="{{ (request()->cookie('theme') === 'dark' ? 'dark' : '') }} {{ (request()->cookie('contrast') === 'high' ? 'high-contrast' : '') }}">
 
<div class="auth-wrapper">
 
  <!-- Brand Logo -->
  <a href="{{ route('index') }}" class="logo-link">
    <img src="{{ asset('assets/img/logo.png') }}" alt="Minijobz Logo" style="height: 60px; width: auto; margin-bottom: 1.5rem;" class="logo-img">
  </a>

  <!-- Personal Greeting -->
  <p class="auth-subtitle">
    {{ __('auth_pages.verify_code.signed_in_as') }} <strong>{{ $user->first_name }} {{ $user->last_name }}</strong>.<br>
    <span style="display:inline-block; margin-top:5px;">{{ __('auth_pages.verify_code.security_intro') }}</span>
  </p>
 
  <!-- Verification Box -->
  <div class="verification-box">
    <div class="step-header">{{ __('auth_pages.verify_code.step1') }}</div>
    <div class="step-body">
 
      <p>{{ __('auth_pages.verify_code.sent_to') }}
        <strong>{{ Str::maskEmail($user->email) }}</strong>
      </p>
 
      <!-- Alerts -->
      @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
      @endif
      @if ($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
      @endif
 
      <form method="POST" action="{{ route('verify.code') }}">
        @csrf
        
        <!-- OTP Inputs -->
        <div class="mb-4">
          <div class="d-flex justify-content-between otp-inputs">
            <input type="text" maxlength="1" class="form-control otp-input" pattern="[0-9]*" inputmode="numeric">
            <input type="text" maxlength="1" class="form-control otp-input" pattern="[0-9]*" inputmode="numeric">
            <input type="text" maxlength="1" class="form-control otp-input" pattern="[0-9]*" inputmode="numeric">
            <input type="text" maxlength="1" class="form-control otp-input" pattern="[0-9]*" inputmode="numeric">
            <input type="text" maxlength="1" class="form-control otp-input" pattern="[0-9]*" inputmode="numeric">
            <input type="text" maxlength="1" class="form-control otp-input" pattern="[0-9]*" inputmode="numeric">
          </div>
          <input type="hidden" name="code" id="verificationCode" required>
          @error('code')
            <div class="text-danger small mt-1 text-center font-bold">{{ $message }}</div>
          @enderror
        </div>
 
        <!-- Verify Button -->
        <div class="d-grid mb-3">
          <button type="submit" class="btn btn-primary">{{ __('auth_pages.verify_code.verify_btn') }}</button>
        </div>
 
        <!-- Footer Links -->
        <div class="small-text text-center mt-3">
          {{ __('auth_pages.verify_code.trouble') }} 
          <a href="{{ route('resend.code') }}">{{ __('auth_pages.verify_code.resend_link') }}</a> 
          {{ app()->getLocale() === 'hu' ? 'vagy' : 'or' }} 
          <a href="{{ route('contact-support') }}" target="_blank">{{ __('auth_pages.verify_code.contact_support') }}</a>.
        </div>
      </form>
    </div>
  </div>
</div>
 
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script type="module">
  import { AuthManager } from "{{ asset('js/components/auth-manager.js') }}";
  document.addEventListener('DOMContentLoaded', () => {
    new AuthManager();
  });
</script>
</body>
</html>
