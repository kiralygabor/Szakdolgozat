<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ __('auth_pages.forgot_password.title') }} - Minijobz</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    html.dark body { background: #0f172a; color: #e2e8f0; }
    html.dark .auth-box { color: #e2e8f0; }
    html.dark .auth-title { color: #f1f5f9; }
    html.dark .form-control { background: #1e293b; border-color: #475569; color: #e2e8f0; }
    html.dark .form-control:focus { background: #0f172a; border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.2); }
    html.dark .form-control::placeholder { color: #64748b; }
    html.dark .alert-success { background: rgba(20,83,45,0.3); color: #86efac; border-color: #166534; }
    html.dark .alert-danger { background: rgba(127,29,29,0.3); color: #fca5a5; border-color: #991b1b; }
    html.dark .auth-links p, html.dark .auth-links a { color: #94a3b8; }
    html.dark .auth-links a:hover { color: #f1f5f9; }

    body {
      background: #fff;
      font-family: Arial, sans-serif;
    }
    .auth-wrapper {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      flex-direction: column;
      padding: 1.5rem;
    }
    .auth-box {
      width: 100%;
      max-width: 400px;
      text-align: center;
    }
    .auth-title {
      font-weight: bold;
      margin-bottom: 1.5rem;
      font-size: 1.3rem;
      color: #001844;
    }
    .form-control {
      border-radius: 12px;
      padding: 0.75rem 1rem;
    }
    .btn-primary {
      border-radius: 24px;
      padding: 0.75rem;
      background: #6366f1;
      border: none;
      font-weight: bold;
    }
    .btn-primary:hover {
      background: #4f46e5;
    }
    .auth-links a {
      color: #6366f1;
      text-decoration: none;
      font-weight: 500;
    }
    .auth-links a:hover {
      color: #4f46e5;
    }
    .alert {
      border-radius: 12px;
      font-size: 0.9rem;
    }

    /* Accessibility Modes */
    .high-contrast body, .high-contrast .auth-wrapper {
      background-color: #ffffff !important;
      color: #000000 !important;
    }
    .high-contrast p, .high-contrast span, .high-contrast label, .high-contrast .auth-title {
      color: #000000 !important;
      font-weight: 800 !important;
    }
    .high-contrast .form-control {
      border: 3px solid #000000 !important;
      background-color: #ffffff !important;
      color: #000000 !important;
    }
    .high-contrast a.logo-link:hover {
      background-color: transparent !important;
    }
    .high-contrast img.logo-img {
        filter: brightness(0) !important;
    }
    .high-contrast .btn-primary {
      background-color: #000000 !important;
      color: #ffffff !important;
      border: 3px solid #000000 !important;
      font-weight: 900 !important;
    }
  </style>

  <script>
    (function(){
      var root = document.documentElement;
      // Auth flow pages default to light mode
      root.classList.remove('dark', 'high-contrast', 'reduced-motion');
    })();
  </script>
</head>
<body>

<div class="auth-wrapper">
  <div class="auth-box">
    <div style="margin-bottom: 24px;">
      <a href="{{ route('index') }}" class="logo-link">
        <img src="{{ asset('assets/img/logo.png') }}" alt="Minijobz" style="height: 48px; width: auto;" class="logo-img">
      </a>
    </div>
    <h2 class="auth-title">{{ __('auth_pages.forgot_password.title') }}</h2>

    @if (session('status'))
      <div class="alert alert-success mt-3">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
      <div class="alert alert-danger mt-3">
        <ul class="mb-0 list-unstyled">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="mt-4">
      @csrf
      <div class="mb-4">
        <input type="email" name="email" class="form-control" placeholder="{{ __('auth_pages.forgot_password.email_placeholder') }}" required autofocus>
      </div>
      <div class="d-grid mb-3">
        <button type="submit" class="btn btn-primary">{{ __('auth_pages.forgot_password.send_link_btn') }}</button>
      </div>
      <div class="auth-links mt-3 text-center">
        <a href="{{ route('login') }}"><i class="fa fa-arrow-left me-1"></i> {{ __('auth_pages.forgot_password.back_to_login') }}</a>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

