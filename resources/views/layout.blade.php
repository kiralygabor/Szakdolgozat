<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Minijobz</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- FontAwesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

  <!-- Bootstrap JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Custom CSS -->
  <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

  <!-- Favicon -->
  <link rel="shortcut icon" href="{{ asset('favicon.png') }}" type="image/x-icon">

  <!-- Tailwind (for navbar styles) -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    window.isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
    @auth
      window.userSettings = {
        theme: {!! json_encode(auth()->user()->theme) !!},
        reduced_motion: {{ auth()->user()->reduced_motion ? 'true' : 'false' }},
        high_contrast: {{ auth()->user()->high_contrast ? 'true' : 'false' }}
      };
    @else
      window.userSettings = null;
    @endauth

    tailwind.config = {
      darkMode: 'class',
      theme: {
        extend: {
          colors: {
            primary: { 500: '#6366f1' },
            secondary: { 500: '#6366f1' }
          }
        }
      }
    }
    // Apply theme early to prevent flash - Prioritize cookies for instant persistency on refresh
    const getCookie = (name) => {
      const value = `; ${document.cookie}`;
      const parts = value.split(`; ${name}=`);
      if (parts.length === 2) return parts.pop().split(';').shift();
      return null;
    };

    const cookieTheme = getCookie('theme');
    const savedTheme = cookieTheme || (window.userSettings && window.userSettings.theme) || 'light';
    
    if (savedTheme === 'dark' || (savedTheme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
      document.documentElement.classList.add('dark');
    } else {
      document.documentElement.classList.remove('dark');
    }

    // Apply accessibility settings early
    const cookieRM = getCookie('reduced_motion');
    const cookieHC = getCookie('contrast');

    const savedReducedMotion = cookieRM !== null ? (cookieRM === 'true') : (window.userSettings && window.userSettings.reduced_motion) || false;
    const savedHighContrast = cookieHC !== null ? (cookieHC === 'high') : (window.userSettings && window.userSettings.high_contrast) || false;
    
    if (savedReducedMotion) {
        document.documentElement.classList.add('reduced-motion');
    } else {
        document.documentElement.classList.remove('reduced-motion');
    }

    if (savedHighContrast) {
        document.documentElement.classList.add('high-contrast');
        document.documentElement.classList.remove('dark'); // HC forces light mode
    } else {
        document.documentElement.classList.remove('high-contrast');
    }

  </script>
  <!-- Custom modular CSS -->
  <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
  <link rel="stylesheet" href="{{ asset('css/components.css') }}">
  @stack('styles')
  <!-- Feather icons (used in navbar) -->

  <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
</head>

<body class="bg-[var(--bg-secondary)] flex flex-col min-h-screen">


  @include('partials.navbar')


    <!-- 🔹 MAIN CONTENT -->
    <main id="main-content" tabindex="-1">
      @yield('content')
    </main>

    @include('partials.footer')

    <script type="module" src="{{ asset('js/layout.js') }}"></script>


    @auth
      <form id="universal-logout-form" method="POST" action="{{ route('logout') }}" class="hidden">
        @csrf
      </form>
    @endauth

    @stack('scripts')
</body>

</html>