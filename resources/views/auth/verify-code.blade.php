<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ __('auth_pages.verify_code.title') }}</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #f9f9fb;
      font-family: Arial, sans-serif;
      color: #333;
    }
    .auth-wrapper {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      flex-direction: column;
      text-align: center;
    }
    .auth-title {
      font-weight: bold;
      margin-bottom: 9px;
      font-size: 1.5rem;
      color: #001844;
    }
    .auth-subtitle {
      font-size: 0.95rem;
      color: #555;
      margin-bottom: 15px;
    }
    .auth-box {
      background: #fff;
      border: 1px solid #e5e5e5;
      border-radius: 12px;
      width: 100%;
      max-width: 560px; /* wider so long emails fit in one line */
      box-shadow: 0 2px 8px rgba(0,0,0,0.05);
      text-align: left;
    }
    .step-header {
      background: #f5f6f8;
      border-bottom: 1px solid #e5e5e5;
      padding: 0.9rem 1.2rem;
      font-weight: bold;
      font-size: 1rem;
      border-radius: 12px 12px 0 0;
      color: #001844;
    }
    .step-body {
      padding: 1.5rem;
    }
    .step-body p {
      margin-bottom: 1rem;
      font-size: 0.95rem;
      color: #555;
      white-space: nowrap; /* keep email in one line */
    }
    .form-control {
      border-radius: 10px;
      padding: 0.75rem 1rem;
      font-size: 1rem;
    }
    .btn-primary {
      border-radius: 10px;
      padding: 0.75rem;
      background: #007AFF;
      border: none;
      font-weight: bold;
    }
    .btn-primary:hover {
      background: #0066d6;
    }
    .small-text {
      font-size: 0.85rem;
      color: #777;
    }
    a {
      color: #007AFF;
      text-decoration: none;
    }
    a:hover {
      text-decoration: underline;
    }
    .alert {
      font-size: 0.9rem;
      padding: 0.6rem 0.8rem;
    }
    .otp-inputs {
      gap: 12px;
      margin-top: 10px;
      margin-bottom: 20px;
    }
    .otp-input {
      width: 60px;
      height: 65px;
      font-size: 1.5rem;
      font-weight: bold;
      border-radius: 12px;
    }
    .otp-input:focus {
      border-color: #007AFF;
      box-shadow: 0 0 0 4px rgba(0, 122, 255, 0.15);
    }
    
    /* Accessibility Modes */
    .high-contrast body, .high-contrast .auth-wrapper {
      background-color: #ffffff !important;
      color: #000000 !important;
    }
    .high-contrast p, .high-contrast span, .high-contrast label, .high-contrast .auth-subtitle, .high-contrast .step-header {
      color: #000000 !important;
      font-weight: 800 !important;
    }
    .high-contrast .auth-box {
      border: 3px solid #000000 !important;
    }
    .high-contrast .form-control {
      border: 3px solid #000000 !important;
      background-color: #ffffff !important;
      color: #000000 !important;
    }
    .high-contrast .btn-primary {
      background-color: #000000 !important;
      color: #ffffff !important;
      border: 3px solid #000000 !important;
      font-weight: 900 !important;
    }
    .high-contrast a:hover:not(.logo-link) {
      text-decoration: underline !important;
      background-color: #000000 !important;
      color: #ffffff !important;
    }
    .high-contrast a.logo-link:hover {
      background-color: transparent !important;
    }
  </style>
  
  <script>
    (function(){
      var root = document.documentElement;
      if(localStorage.getItem('high-contrast') === 'true') root.classList.add('high-contrast');
      if(localStorage.getItem('reduced-motion') === 'true') root.classList.add('reduced-motion');
      var theme = localStorage.getItem('theme');
      if (theme === 'dark' || (theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        root.classList.add('dark');
      }
    })();
  </script>
</head>
<body>
 
<div class="auth-wrapper">
 
  <!-- Title + Subtitle -->
  <a href="{{ route('index') }}" class="logo-link">
    <img src="{{ asset('assets/img/logo.png') }}" alt="Minijobz Logo" style="height: 60px; width: auto; margin: 0 auto 15px;">
  </a>
  <p class="auth-subtitle">
    {{ __('auth_pages.verify_code.signed_in_as') }} <strong>{{ $user->first_name }} {{ $user->last_name }}</strong>.  
    {{ __('auth_pages.verify_code.security_intro') }}
  </p>
 
  <!-- Verification Box -->
  <div class="auth-box">
    <div class="step-header">{{ __('auth_pages.verify_code.step1') }}</div>
    <div class="step-body">
 
      <p>{{ __('auth_pages.verify_code.sent_to') }}
        <strong>{{ Str::maskEmail($user->email) }}</strong>
      </p>
 
      @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
      @endif
      @if ($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
      @endif
 
      <form method="POST" action="{{ route('verify.code') }}">
        @csrf
        <div class="mb-4">
          <div class="d-flex justify-content-between otp-inputs">
            <input type="text" maxlength="1" class="form-control text-center otp-input" pattern="[0-9]*" inputmode="numeric">
            <input type="text" maxlength="1" class="form-control text-center otp-input" pattern="[0-9]*" inputmode="numeric">
            <input type="text" maxlength="1" class="form-control text-center otp-input" pattern="[0-9]*" inputmode="numeric">
            <input type="text" maxlength="1" class="form-control text-center otp-input" pattern="[0-9]*" inputmode="numeric">
            <input type="text" maxlength="1" class="form-control text-center otp-input" pattern="[0-9]*" inputmode="numeric">
            <input type="text" maxlength="1" class="form-control text-center otp-input" pattern="[0-9]*" inputmode="numeric">
          </div>
          <input type="hidden" name="code" id="verificationCode" required>
          @error('code')
            <div class="text-danger small mt-1 text-center">{{ $message }}</div>
          @enderror
        </div>
 
        <div class="d-grid mb-3">
          <button type="submit" class="btn btn-primary">{{ __('auth_pages.verify_code.verify_btn') }}</button>
        </div>
 
        <div class="small-text text-center mt-3">
          {{ __('auth_pages.verify_code.trouble') }} <a href="{{ route('resend.code') }}">{{ __('auth_pages.verify_code.resend_link') }}</a> {{ __('navbar.or') ?? 'or' }} <a href="{{ route('contact-support') }}" target="_blank">{{ __('auth_pages.verify_code.contact_support') }}</a>.
        </div>
      </form>
    </div>
  </div>
</div>
 
<script>
  document.addEventListener("DOMContentLoaded", function () {
    const inputs = document.querySelectorAll(".otp-input");
    const hiddenCode = document.getElementById("verificationCode");
   
    inputs.forEach((input, index) => {
      input.addEventListener("input", function (e) {
        this.value = this.value.replace(/[^0-9]/g, ""); // Keep only numbers
        if (this.value.length === 1 && index < inputs.length - 1) {
          inputs[index + 1].focus();
        }
        updateHiddenCode();
      });
 
      input.addEventListener("keydown", function (e) {
        if (e.key === "Backspace" && !this.value && index > 0) {
          inputs[index - 1].focus();
        }
      });
     
      input.addEventListener("paste", function (e) {
        e.preventDefault();
        const pastedData = e.clipboardData.getData("text").replace(/[^0-9]/g, "").slice(0, 6);
        if (pastedData) {
          for (let i = 0; i < pastedData.length; i++) {
            if (i < inputs.length) {
              inputs[i].value = pastedData[i];
            }
          }
          const focusIndex = Math.min(pastedData.length, 5);
          inputs[focusIndex].focus();
          updateHiddenCode();
        }
      });
    });
 
    function updateHiddenCode() {
      hiddenCode.value = Array.from(inputs).map(i => i.value).join("");
    }
  });
</script>
</body>
</html>