<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - Minijobz</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    html.dark body { background: #0f172a; color: #e2e8f0; }
    html.dark .auth-box { color: #e2e8f0; }
    html.dark .auth-title { color: #f1f5f9; }
    html.dark .form-control { background: #1e293b; border-color: #475569; color: #e2e8f0; }
    html.dark .form-control:focus { background: #0f172a; border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.2); }
    html.dark .form-control::placeholder { color: #64748b; }
    html.dark .btn-outline { background: #1e293b; border-color: #334155; color: #e2e8f0; }
    html.dark .btn-outline:hover { background: #334155; color: #f1f5f9; }
    html.dark .divider { color: #64748b; }
    html.dark .divider::before, html.dark .divider::after { background: #334155; }
    html.dark .auth-links p, html.dark .auth-links label { color: #94a3b8; }
    html.dark .remember-forgot label { color: #94a3b8; }
    html.dark .text-secondary { color: #94a3b8 !important; }
    html.dark .alert { border: 1px solid; }
    html.dark .alert-success { background: rgba(20,83,45,0.3); color: #86efac; border-color: #166534; }
    html.dark .alert-warning { background: rgba(120,53,15,0.3); color: #fde68a; border-color: #92400e; }
    html.dark .alert-danger { background: rgba(127,29,29,0.3); color: #fca5a5; border-color: #991b1b; }
    html.dark .modal-content { background: #1e293b; color: #e2e8f0; border-color: #334155; }
    html.dark .modal-header { border-color: #334155; }
    html.dark .btn-close { filter: invert(1); }
    html.dark .password-toggle { color: #94a3b8; }
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
    .btn-outline {
      border-radius: 24px;
      padding: 0.75rem;
      font-weight: bold;
      border: 1px solid #ddd;
      background: #fff;
    }
    .auth-links a {
      color: #6366f1;
      text-decoration: none;
      font-weight: 500;
    }
    .auth-links a:hover {
      color: #4f46e5;
    }
    .divider {
      margin: 1.5rem 0;
      text-transform: uppercase;
      font-size: 0.85rem;
      color: #777;
    }
    .remember-forgot {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1rem;
    }
    .password-toggle {
      cursor: pointer;
      color: #777;
    }
   
    /* Accessibility Modes */
    .reduced-motion *, .reduced-motion *:before, .reduced-motion *:after {
      animation-duration: 0.001ms !important;
      animation-iteration-count: 1 !important;
      transition-duration: 0.001ms !important;
      scroll-behavior: auto !important;
    }
    .high-contrast body, .high-contrast .auth-wrapper {
      background-color: #ffffff !important;
      color: #000000 !important;
    }
    .high-contrast p, .high-contrast span, .high-contrast label, .high-contrast .auth-title, .high-contrast .divider, .high-contrast .password-toggle {
      color: #000000 !important;
      font-weight: 800 !important;
    }
    .high-contrast .auth-links a, .high-contrast a.auth-links {
      color: #000000 !important;
      font-weight: 800 !important;
      text-decoration: underline !important;
      text-decoration-color: #ffffff !important;
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
    .high-contrast a:hover {
      text-decoration: underline !important;
      background-color: #000000 !important;
      color: #ffffff !important;
    }
  </style>
 
  <script>
    (function(){
      var root = document.documentElement;
      var theme = localStorage.getItem('theme');
      if (theme === 'dark' || (theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        root.classList.add('dark');
      }
      if(localStorage.getItem('high-contrast') === 'true') root.classList.add('high-contrast');
      if(localStorage.getItem('reduced-motion') === 'true') root.classList.add('reduced-motion');
    })();
  </script>
</head>
<body>
 
<div class="auth-wrapper">
  <div class="auth-box">
    <div style="margin-bottom: 24px;">
      <a href="{{ url('/') }}">
        <img src="{{ asset('assets/img/logo.png') }}" alt="Minijobz" style="height: 48px; width: auto;">
      </a>
    </div>
    <h2 class="auth-title">Login to your account</h2>
 
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
               placeholder="Email" required>
        @error('email')
          <span class="text-danger small">{{ $message }}</span>
        @enderror
      </div>
 
      <!-- Password -->
      <div class="mb-3">
        <div class="position-relative">
          <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror"
                 placeholder="Password" required>
          <i class="fa fa-eye-slash position-absolute top-50 end-0 translate-middle-y me-3 password-toggle" id="togglePassword"></i>
        </div>
        @error('password')
          <span class="text-danger small">{{ $message }}</span>
        @enderror
      </div>
 
      <!-- Remember Me + Forgot Password -->
      <div class="remember-forgot">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="rememberMe" id="rememberMe">
          <label class="form-check-label text-secondary" for="rememberMe">Remember me</label>
        </div>
        <!-- Trigger Modal -->
        <a href="#" class="auth-links" data-bs-toggle="modal" data-bs-target="#forgotPasswordModal">
          {{ __('Forgot password?') }}
        </a>
      </div>
 
      <!-- Login Button -->
      <div class="d-grid mb-3">
        <button type="submit" class="btn btn-primary">Login</button>
      </div>
 
      <div class="auth-links mb-3">
        <p class="mb-1">Don't have an account? <a href="{{ route('register') }}">Sign up</a></p>
      </div>
 
      <div class="divider">OR</div>
 
      <!-- Google Continue -->
      <div class="d-grid mb-2">
        <a href="{{ route('login.google') }}" class="btn btn-outline">
          <img src="https://www.svgrepo.com/show/355037/google.svg" alt="Google" width="20" class="me-2">
          Login with Google
        </a>
      </div>
 
      <!-- Facebook Continue -->
      <div class="d-grid">
        <button type="button" class="btn btn-outline">
          <img src="https://www.svgrepo.com/show/349574/facebook.svg" alt="Facebook" width="20" class="me-2">
          Login with Facebook
        </button>
      </div>
    </form>
  </div>
</div>
 
<!-- Forgot Password Modal -->
<div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-labelledby="forgotPasswordModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content p-4" style="border-radius: 16px;">
      <div class="modal-header border-0">
        <h5 class="modal-title auth-title" id="forgotPasswordModalLabel">Reset your password</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
 
      <div class="modal-body">
        <form method="POST" action="{{ route('password.email') }}">
          @csrf
          <div class="mb-3">
            <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
          </div>
          <div class="d-grid">
            <button type="submit" class="btn btn-primary">Send Reset Link</button>
          </div>
        </form>
      </div>
 
    </div>
  </div>
</div>
 
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  const togglePassword = document.querySelector('#togglePassword');
  const password = document.querySelector('#password');
 
  togglePassword.addEventListener('click', function (e) {
    // toggle the type attribute
    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
    password.setAttribute('type', type);
    // toggle the eye slash icon
    this.classList.toggle('fa-eye');
    this.classList.toggle('fa-eye-slash');
  });
</script>
</body>
</html>