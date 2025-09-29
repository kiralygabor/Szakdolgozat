@extends('layout')

@section('content')
<div class="auth-wrapper">
  <div class="auth-box">
    <h2 class="auth-title">Set up your account</h2>

    <form method="POST" action="{{ route('registration_settings.post') }}">
      @csrf

      <!-- First name -->
      <div class="mb-3">
        <input type="text" class="form-control" id="inputFirstName" name="first_name" placeholder="Vezetéknév" required>
      </div>

      <!-- Last name -->
      <div class="mb-3">
        <input type="text" class="form-control" id="inputLastName" name="last_name" placeholder="Keresztnév" required>
      </div>

      <!-- Birthdate -->
      <div class="mb-3">
        <input type="date" class="form-control" id="birthdate" name="birthdate" required>
      </div>

      <!-- Phone -->
      <div class="mb-3">
        <input type="text" class="form-control" id="inputPhone" name="phone_number" placeholder="+36-00/0000-000" required>
      </div>

      <!-- County -->
      <div class="mb-3">
        <select id="inputCounty" name="county" class="form-control" required>
          <option selected disabled>Megye</option>
          <option value="Budapest">Budapest</option>
          <option value="Pest">Pest</option>
          <option value="Fejér">Fejér</option>
          <!-- Add more counties -->
        </select>
      </div>

      <!-- City -->
      <div class="mb-3">
        <input type="text" class="form-control" id="inputCity" name="city" placeholder="Város (pl. Budapest)">
      </div>

      <!-- Submit -->
      <div class="d-grid">
        <button type="submit" class="btn btn-primary">Complete my account</button>
      </div>
    </form>
  </div>
</div>

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
</style>
@endsection
