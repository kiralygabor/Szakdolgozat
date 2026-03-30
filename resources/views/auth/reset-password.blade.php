<!-- resources/views/auth/reset-password.blade.php -->
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('auth_pages.reset_password.page_title') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        .password-toggle {
            cursor: pointer;
            color: #777;
        }
    </style>
</head>
<body class="bg-light">
<div class="container mt-5 px-4">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h3 class="card-title mb-4 text-center">{{ __('auth_pages.reset_password.page_title') }}</h3>
 
                    <!-- Status message -->
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
 
                    <!-- Validation errors -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
 
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">
 
                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __('auth_pages.login.email_placeholder') }}</label>
                            <input type="email"
                                   class="form-control"
                                   id="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   required
                                   autofocus>
                        </div>
 
                        <div class="mb-3">
                            <label for="password" class="form-label">{{ __('auth_pages.reset_password.new_password_label') }}</label>
                            <div class="position-relative">
                                <input type="password"
                                       class="form-control"
                                       id="password"
                                       name="password"
                                       required>
                                <i class="fa fa-eye-slash position-absolute top-50 end-0 translate-middle-y me-3 password-toggle" data-target="password"></i>
                            </div>
                        </div>
 
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">{{ __('auth_pages.reset_password.confirm_password_label') }}</label>
                            <div class="position-relative">
                                <input type="password"
                                       class="form-control"
                                       id="password_confirmation"
                                       name="password_confirmation"
                                       required>
                                <i class="fa fa-eye-slash position-absolute top-50 end-0 translate-middle-y me-3 password-toggle" data-target="password_confirmation"></i>
                            </div>
                        </div>
 
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">{{ __('auth_pages.reset_password.reset_btn') }}</button>
                        </div>
 
                        <div class="mt-3 text-center">
                            <a href="{{ route('login') }}">{{ __('auth_pages.forgot_password.back_to_login') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  document.querySelectorAll('.password-toggle').forEach(item => {
    item.addEventListener('click', function() {
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
 