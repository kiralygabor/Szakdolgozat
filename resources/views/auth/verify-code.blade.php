<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Email Verification</title>
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
  </style>
</head>
<body>

<div class="auth-wrapper">
  
  <!-- Title + Subtitle -->
  <h2 class="auth-title">Help us keep Minijobz secure</h2>
  <p class="auth-subtitle">
    You are signed in as <strong>{{ $user->first_name }} {{ $user->last_name }}</strong>.  
    For added security,<br> you’ll need to verify your identity in a quick steps.
  </p>

  <!-- Verification Box -->
  <div class="auth-box">
    <div class="step-header">Step 1: Verify email address</div>
    <div class="step-body">

      <p>We’ve sent a verification code to 
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
        <div class="mb-3">
          <input type="text" name="code" class="form-control text-center @error('code') is-invalid @enderror"
                 placeholder="Verification code" required>
          @error('code')
            <span class="text-danger small">{{ $message }}</span>
          @enderror
        </div>

        <div class="d-grid mb-3">
          <button type="submit" class="btn btn-primary">Verify email address</button>
        </div>

        <div class="small-text text-center">
          Having trouble? <a href="{{ route('resend.code') }}">Send a new code</a> or contact support.
        </div>
      </form>
    </div>
  </div>
</div>

</body>
</html>
