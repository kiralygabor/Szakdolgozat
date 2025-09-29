<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sign up - Airtasker Style</title>
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
  </style>
</head>
<body>

<div class="auth-wrapper">
  <div class="auth-box">
    <h2 class="auth-title">Sign up to your account</h2>

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

    <form method="POST" action="{{ route('register.post') }}">
      @csrf

      <!-- Email -->
      <div class="mb-3">
        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
               placeholder="Email" value="{{ old('email') }}" required>
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

      <!-- Confirm Password -->
      <div class="mb-3">
        <input type="password" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror"
               placeholder="Confirm Password" required>
        @error('password_confirmation')
          <span class="text-danger small">{{ $message }}</span>
        @enderror
      </div>

      <!-- Register Button -->
      <div class="d-grid mb-3">
        <button type="submit" class="btn btn-primary">Sign up</button>
      </div>

      <div class="auth-links mb-3">
        <p class="mb-1">Already have an account? <a href="{{ route('login') }}">Login</a></p>
      </div>

      <div class="divider">OR</div>

      <!-- Google Continue -->
     <div class="d-grid mb-2">
    <a href="{{ route('login.google') }}" class="btn btn-outline">
        <img src="https://www.svgrepo.com/show/355037/google.svg" alt="Google" width="20" class="me-2">
        Continue with Google
    </a>
</div>


      <!-- Facebook Continue -->
      <div class="d-grid">
        <button type="button" class="btn btn-outline">
          <img src="https://www.svgrepo.com/show/349574/facebook.svg" alt="Facebook" width="20" class="me-2">
          Continue with Facebook
        </button>
      </div>
    </form>
  </div>
</div>

</body>
</html>
