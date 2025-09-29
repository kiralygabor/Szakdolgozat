<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - Airtasker Style</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
  <style>
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
      background: #007AFF;
      border: none;
      font-weight: bold;
    }
    .btn-outline {
      border-radius: 24px;
      padding: 0.75rem;
      font-weight: bold;
      border: 1px solid #ddd;
      background: #fff;
    }
    .auth-links a {
      color: #007AFF;
      text-decoration: none;
      font-weight: 500;
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
  </style>
</head>
<body>

<div class="auth-wrapper">
  <div class="auth-box">
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
        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
               placeholder="Password" required>
        @error('password')
          <span class="text-danger small">{{ $message }}</span>
        @enderror
      </div>

      <!-- Remember Me + Forgot Password -->
      <div class="remember-forgot">a
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
</body>
</html>
