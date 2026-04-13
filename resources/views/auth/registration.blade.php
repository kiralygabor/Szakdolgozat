<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ __('auth_pages.register.title') }} - Minijobz</title>
  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

  <style>
    html.dark body {
      background: #0f172a !important;
      color: #e2e8f0;
    }

    html.dark .auth-box {
      color: #e2e8f0;
    }

    html.dark .auth-title {
      color: #f1f5f9 !important;
    }

    html.dark .form-control {
      background: #1e293b !important;
      border-color: #475569 !important;
      color: #e2e8f0 !important;
    }

    html.dark .form-control:focus {
      background: #0f172a !important;
      border-color: #6366f1 !important;
      box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.2) !important;
    }

    html.dark .form-control::placeholder {
      color: #64748b !important;
    }

    html.dark .btn-outline {
      background: #1e293b !important;
      border-color: #334155 !important;
      color: #e2e8f0 !important;
    }

    html.dark .btn-outline:hover {
      background: #334155 !important;
      color: #f1f5f9 !important;
    }

    html.dark .divider {
      color: #64748b;
    }

    html.dark .divider::before,
    html.dark .divider::after {
      background: #334155;
    }

    html.dark .auth-links p {
      color: #94a3b8;
    }

    html.dark .alert {
      border: 1px solid;
    }

    html.dark .alert-success {
      background: rgba(20, 83, 45, 0.3) !important;
      color: #86efac !important;
      border-color: #166534 !important;
    }

    html.dark .alert-warning {
      background: rgba(120, 53, 15, 0.3) !important;
      color: #fde68a !important;
      border-color: #92400e !important;
    }

    html.dark .password-toggle {
      color: #94a3b8;
    }

    html.dark .invalid-feedback-custom {
      color: #fca5a5;
    }

    :root {
      --primary-blue: #6366f1;
      --error-red: #dc3545;
      --dark-navy: #001844;
      --text-gray: #777;
    }

    body {
      background: #fff;
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif;
      color: var(--dark-navy);
    }

    .auth-wrapper {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      padding: 1.5rem;
    }

    .auth-box {
      width: 100%;
      max-width: 400px;
      text-align: center;
    }

    .auth-title {
      font-weight: 800;
      margin-bottom: 2rem;
      font-size: 1.5rem;
      letter-spacing: -0.5px;
    }

    /* Input Styling */
    .form-control {
      border-radius: 12px;
      padding: 0.8rem 1rem;
      border: 1px solid #e2e8f0;
      font-size: 1rem;
      transition: all 0.2s ease-in-out;
    }

    .form-control:focus {
      box-shadow: 0 0 0 4px rgba(0, 122, 255, 0.1);
      border-color: var(--primary-blue);
    }

    /* Option 3: Error States */
    .form-control.is-invalid {
      border-color: var(--error-red);
      background-image: none;
      /* Removes Bootstrap's default icon */
      animation: shake 0.4s cubic-bezier(.36, .07, .19, .97) both;
    }

    /* Left-aligned error text under inputs */
    .invalid-feedback-custom {
      display: block;
      text-align: left;
      color: var(--error-red);
      font-size: 0.85rem;
      margin-top: 6px;
      margin-left: 4px;
      font-weight: 500;
    }

    /* Shake Animation */
    @keyframes shake {

      10%,
      90% {
        transform: translate3d(-1px, 0, 0);
      }

      20%,
      80% {
        transform: translate3d(2px, 0, 0);
      }

      30%,
      50%,
      70% {
        transform: translate3d(-4px, 0, 0);
      }

      40%,
      60% {
        transform: translate3d(4px, 0, 0);
      }
    }

    /* Buttons */
    .btn-primary {
      border-radius: 50px;
      padding: 0.8rem;
      background: var(--primary-blue);
      border: none;
      font-weight: 700;
      font-size: 1rem;
      transition: opacity 0.2s;
    }

    .btn-primary:hover {
      background: #006ae6;
      opacity: 0.9;
    }

    .btn-outline {
      border-radius: 50px;
      padding: 0.8rem;
      font-weight: 600;
      border: 1px solid #e2e8f0;
      background: #fff;
      color: var(--dark-navy);
      display: flex;
      align-items: center;
      justify-content: center;
      text-decoration: none;
      transition: background 0.2s;
    }

    .btn-outline:hover {
      background: #f8fafc;
      color: var(--dark-navy);
    }

    .btn-outline img {
      width: 20px;
      margin-right: 12px;
    }

    /* Helpers */
    .auth-links {
      font-size: 0.95rem;
    }

    .auth-links a {
      color: var(--primary-blue);
      text-decoration: none;
      font-weight: 600;
    }

    .divider {
      margin: 1.5rem 0;
      display: flex;
      align-items: center;
      text-transform: uppercase;
      font-size: 0.75rem;
      font-weight: 700;
      color: var(--text-gray);
      letter-spacing: 1px;
    }

    .divider::before,
    .divider::after {
      content: "";
      flex: 1;
      height: 1px;
      background: #eee;
    }

    .divider:not(:empty)::before {
      margin-right: 1rem;
    }

    .divider:not(:empty)::after {
      margin-left: 1rem;
    }

    .alert {
      border-radius: 12px;
      border: none;
      font-weight: 500;
      font-size: 0.9rem;
    }

    .password-toggle {
      cursor: pointer;
      color: #777;
    }

    /* Accessibility Modes */
    .reduced-motion *,
    .reduced-motion *:before,
    .reduced-motion *:after {
      animation-duration: 0.001ms !important;
      animation-iteration-count: 1 !important;
      transition-duration: 0.001ms !important;
      scroll-behavior: auto !important;
    }

    .high-contrast body,
    .high-contrast .auth-wrapper {
      background-color: #ffffff !important;
      color: #000000 !important;
    }

    .high-contrast p,
    .high-contrast span,
    .high-contrast label,
    .high-contrast .auth-title,
    .high-contrast .divider,
    .high-contrast .auth-links a,
    .high-contrast .password-toggle {
      color: #000000 !important;
      font-weight: 800 !important;
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

    .high-contrast .btn-outline {
      border: 3px solid #000000 !important;
      color: #000000 !important;
      background-color: #ffffff !important;
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

    .high-contrast img.logo-img {
      filter: brightness(0) !important;
    }
  </style>

  <script>
    (function () {
      var root = document.documentElement;
      // High contrast and dark mode on auth pages are strictly managed via layout for guests
      // so we don't have persistence across different users on same machine.
    })();
  </script>
</head>

<body>

  <div class="auth-wrapper">
    <div class="auth-box">
      <div style="margin-bottom: 24px;">
        <a href="{{ route('index') }}" class="logo-link">
          <img src="{{ asset('assets/img/logo.png') }}" alt="Minijobz" style="height: 48px; width: auto;"
            class="logo-img">
        </a>
      </div>
      <h2 class="auth-title">{{ __('auth_pages.register.title') }}</h2>

      <!-- Generic Status Messages (Success/Warnings) -->
      @if (session('status'))
        <div class="alert alert-success mb-4">{{ session('status') }}</div>
      @endif

      @if (session('warning'))
        <div class="alert alert-warning mb-4">{{ session('warning') }}</div>
      @endif

      <form method="POST" action="{{ route('register.post') }}">
        @csrf

        <!-- Email Input -->
        <div class="mb-3">
          <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
            placeholder="{{ __('auth_pages.register.email_placeholder') }}"
            value="{{ $prefill['email'] ?? old('email') }}" required>
          @error('email')
            <span class="invalid-feedback-custom">{{ $message }}</span>
          @enderror
        </div>

        <!-- Password Input -->
        <div class="mb-3">
          <div class="position-relative">
            <input type="password" name="password" id="password"
              class="form-control @error('password') is-invalid @enderror"
              placeholder="{{ __('auth_pages.register.password_placeholder') }}" required>
            <i class="fa fa-eye-slash position-absolute top-50 end-0 translate-middle-y me-3 password-toggle"
              data-target="password"></i>
          </div>
          @error('password')
            <span class="invalid-feedback-custom">{{ $message }}</span>
          @enderror
        </div>

        <!-- Confirm Password Input -->
        <div class="mb-4">
          <div class="position-relative">
            <input type="password" name="password_confirmation" id="password_confirmation"
              class="form-control @error('password_confirmation') is-invalid @enderror"
              placeholder="{{ __('auth_pages.register.confirm_password_placeholder') }}" required>
            <i class="fa fa-eye-slash position-absolute top-50 end-0 translate-middle-y me-3 password-toggle"
              data-target="password_confirmation"></i>
          </div>
          @error('password_confirmation')
            <span class="invalid-feedback-custom">{{ $message }}</span>
          @enderror
        </div>

        <!-- Register Button -->
        <div class="d-grid mb-4">
          <button type="submit" class="btn btn-primary">{{ __('auth_pages.register.signup_btn') }}</button>
        </div>

        <div class="auth-links mb-4">
          <p class="mb-0">{{ __('auth_pages.register.have_account') }} <a
              href="{{ route('login') }}">{{ __('auth_pages.register.login_link') }}</a></p>
        </div>

        <div class="divider">{{ __('auth_pages.register.or') }}</div>

        <!-- Social Logins -->
        <div class="d-grid gap-2">
          <a href="{{ route('login.google') }}" class="btn btn-outline">
            <img src="https://www.svgrepo.com/show/355037/google.svg" alt="Google" width="20" class="me-2">
            {{ __('auth_pages.register.continue_google') }}
          </a>

          <a href="{{ route('login.facebook') }}" class="btn btn-outline">
            <i class="fa-brands fa-facebook me-2" style="color: #1877F2; font-size: 20px;"></i>
            {{ __('auth_pages.register.continue_facebook') }}
          </a>
        </div>
      </form>
    </div>
  </div>

  <script>
    document.querySelectorAll('.password-toggle').forEach(item => {
      item.addEventListener('click', function () {
        const targetId = this.getAttribute('data-target');
        const input = document.getElementById(targetId);

        const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
        input.setAttribute('type', type);

        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
      });
    });
  </script>
</body>

</html>