<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sign Up - Minijobz</title>
  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
  
  <style>
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
      padding: 20px;
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
      background-image: none; /* Removes Bootstrap's default icon */
      animation: shake 0.4s cubic-bezier(.36,.07,.19,.97) both;
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
      10%, 90% { transform: translate3d(-1px, 0, 0); }
      20%, 80% { transform: translate3d(2px, 0, 0); }
      30%, 50%, 70% { transform: translate3d(-4px, 0, 0); }
      40%, 60% { transform: translate3d(4px, 0, 0); }
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

    .divider::before, .divider::after {
      content: "";
      flex: 1;
      height: 1px;
      background: #eee;
    }

    .divider:not(:empty)::before { margin-right: 1rem; }
    .divider:not(:empty)::after { margin-left: 1rem; }

    .alert {
      border-radius: 12px;
      border: none;
      font-weight: 500;
      font-size: 0.9rem;
    }
  </style>
</head>
<body>

<div class="auth-wrapper">
  <div class="auth-box">
    <div style="margin-bottom: 24px;">
      <a href="{{ url('/') }}">
        <img src="{{ asset('assets/img/logo.png') }}" alt="Minijobz" style="height: 48px; width: auto;">
      </a>
    </div>
    <h2 class="auth-title">Sign up to your account</h2>

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
        <input type="email" name="email" 
               class="form-control @error('email') is-invalid @enderror"
               placeholder="Email" 
               value="{{ $prefill['email'] ?? old('email') }}" required>
        @error('email')
          <span class="invalid-feedback-custom">{{ $message }}</span>
        @enderror
      </div>

      <!-- Password Input -->
      <div class="mb-3">
        <input type="password" name="password" 
               class="form-control @error('password') is-invalid @enderror"
               placeholder="Password" required>
        @error('password')
          <span class="invalid-feedback-custom">{{ $message }}</span>
        @enderror
      </div>

      <!-- Confirm Password Input -->
      <div class="mb-4">
        <input type="password" name="password_confirmation" 
               class="form-control @error('password_confirmation') is-invalid @enderror"
               placeholder="Confirm Password" required>
        @error('password_confirmation')
          <span class="invalid-feedback-custom">{{ $message }}</span>
        @enderror
      </div>

      <!-- Register Button -->
      <div class="d-grid mb-4">
        <button type="submit" class="btn btn-primary">Sign up</button>
      </div>

      <div class="auth-links mb-4">
        <p class="mb-0">Already have an account? <a href="{{ route('login') }}">Login</a></p>
      </div>

      <div class="divider">OR</div>

      <!-- Social Logins -->
      <div class="d-grid gap-2">
        <a href="{{ route('login.google') }}" class="btn btn-outline">
          <img src="https://www.svgrepo.com/show/355037/google.svg" alt="Google">
          Continue with Google
        </a>

        <button type="button" class="btn btn-outline">
          <img src="https://www.svgrepo.com/show/349574/facebook.svg" alt="Facebook">
          Continue with Facebook
        </button>
      </div>
    </form>
  </div>
</div>

</body>
</html>