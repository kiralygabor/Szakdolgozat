<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ __('auth_pages.forgot_password.title') }} - Minijobz</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="{{ asset('css/pages/auth.css') }}">

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

