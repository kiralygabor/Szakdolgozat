<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Osztálynapló</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}" type="image/x-icon">

    <!-- Tailwind (for navbar styles) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        window.isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
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
    </script>
    <style>
      /* Navbar dropdown animation helper */
      a { text-decoration: none; }
      #settings-menu.show { opacity: 1 !important; transform: translateY(0) !important; }

      .user-pic { width: 40px; border-radius: 50%; cursor: pointer; }
      .sub-menu-wrap { position: absolute; top: 60px; right: 0; width: 280px; max-height: 0; overflow: hidden; transition: max-height 0.3s ease; z-index: 50; }
      .sub-menu-wrap.open-menu { max-height: 500px; }
      .sub-menu { background: #fff; border-radius: 12px; padding: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); border: 1px solid #d1d5db; }
      .user-info { margin-bottom: 15px; padding: 10px 12px; border-radius: 8px; transition: background 0.2s ease, color 0.2s ease; cursor: pointer; }
      .user-info:hover { background: #007bff; }
      .user-info:hover h3 a, .user-info:hover p { color: #fff; }
      .user-info h3 a { font-size: 15px; font-weight: 600; color: #1a1a1a; text-decoration: none; }
      .user-info p { font-size: 13px; color: #888; margin-top: 2px; }
      .sub-menu hr { border: 0; height: 1px; background: #eee; margin: 10px 0; }
      .sub-menu-link { display: block; text-decoration: none; color: #333; font-size: 14px; padding: 10px 0; transition: color 0.2s ease; }
      .sub-menu-link:hover { color: #007bff; }

      /* Notification Bell Button */
      .notification-btn {
        width: 40px;
        height: 40px;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: rgb(255, 255, 255);
        border-radius: 50%;
        cursor: pointer;
        transition-duration: .3s;
        border: none;
      }
      .notification-btn:hover { background-color: #f3f4f6; }
      .notification-btn .bell { width: 18px; }
      .notification-btn .bell path { fill: #4b5563; }
      .notification-btn:hover .bell { animation: bellRing 0.9s both; }

      @keyframes bellRing {
        0%, 100% { transform-origin: top; }
        15% { transform: rotateZ(10deg); }
        30% { transform: rotateZ(-10deg); }
        45% { transform: rotateZ(5deg); }
        60% { transform: rotateZ(-5deg); }
        75% { transform: rotateZ(2deg); }
      }
      .notification-btn:active { transform: scale(0.8); }

      /* ===== MOBILE NAVBAR ===== */
      .mobile-navbar {
        display: none;
        background: #fff;
        border-bottom: 1px solid #e5e7eb;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        padding: 10px 16px;
        align-items: center;
        justify-content: space-between;
        position: relative;
        z-index: 100;
      }

      @media (max-width: 767px) {
        .mobile-navbar { display: flex; }
        .desktop-navbar { display: none !important; }
      }

      /* Standard Mode: Using Tailwind defaults to maintain original aesthetics */
      
      /* Navbar Link Contrast */
      .desktop-navbar a.text-gray-500, .desktop-navbar span.text-gray-500, .desktop-navbar a.text-gray-600 {
          color: #6b7280;
      }
      
      /* Footer Contrast Improvements */
      footer .text-gray-500 { color: #6b7280; }
      footer .text-gray-400 { color: #9ca3af; }

      /* Accessibility Overrides (Toggled via settings) */
      .reduced-motion *, .reduced-motion *:before, .reduced-motion *:after {
        animation-duration: 0.001ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.001ms !important;
        scroll-behavior: auto !important;
      }
      /* Enhanced High Contrast Mode (WCAG AAA alignment) */
      .high-contrast {
        background-color: #ffffff !important;
        color: #000000 !important;
      }

      /* === 1. HERO SECTION: Strong overlay + ALL text white === */
      .high-contrast .bg-gray-900 .bg-gradient-to-r {
        background: rgba(0, 0, 0, 0.90) !important;
      }
      .high-contrast .bg-gray-900 img.object-cover {
        opacity: 0.1 !important;
      }
      /* Force ALL hero text to white EXCEPT buttons */
      .high-contrast .bg-gray-900 h1,
      .high-contrast .bg-gray-900 h1 span:not(.btn),
      .high-contrast .bg-gray-900 h1 *:not(.btn):not(a),
      .high-contrast .bg-gray-900 p,
      .high-contrast .bg-gray-900 .text-gray-200,
      .high-contrast .bg-gray-900 .text-indigo-500:not(a),
      .high-contrast .bg-gray-900 .text-indigo-600:not(a),
      .high-contrast .bg-gray-900 .text-blue-600:not(a),
      .high-contrast .bg-gray-900 .text-violet-600:not(a),
      .high-contrast .bg-gray-900 div.relative.z-10 > h1,
      .high-contrast .bg-gray-900 div.relative.z-10 > p {
        color: #ffffff !important;
      }
      /* Hero buttons (Force black text on white background) */
      .high-contrast .bg-gray-900 a.bg-white,
      .high-contrast .bg-gray-900 a.bg-indigo-500 {
        background-color: #ffffff !important;
        color: #000000 !important;
        border: 4px solid #000000 !important;
        font-weight: 900 !important;
      }

      /* === 2. ALL SECONDARY TEXT: Force black on white === */
      .high-contrast .text-gray-400,
      .high-contrast .text-gray-500,
      .high-contrast .text-gray-600,
      .high-contrast .text-slate-400,
      .high-contrast .text-slate-500,
      .high-contrast .text-slate-600,
      .high-contrast .text-gray-300 {
        color: #000000 !important;
      }
      .high-contrast .text-gray-700,
      .high-contrast .text-gray-800,
      .high-contrast .text-gray-900 {
        color: #000000 !important;
      }
      .high-contrast .text-green-600,
      .high-contrast .text-red-600,
      .high-contrast .text-emerald-600 {
        color: #000000 !important;
        font-weight: 700 !important;
      }
      .high-contrast p,
      .high-contrast span,
      .high-contrast li,
      .high-contrast h1,
      .high-contrast h2,
      .high-contrast h3,
      .high-contrast h4,
      .high-contrast blockquote p {
        color: #000000 !important;
      }

      /* === 3. CATEGORY & FEATURE CARDS: High visibility === */
      .high-contrast .bg-white {
        background-color: #ffffff !important;
      }
      .high-contrast .bg-gray-50,
      .high-contrast .bg-slate-50,
      .high-contrast .bg-blue-50,
      .high-contrast .bg-violet-50 {
        background-color: #ffffff !important;
      }
      .high-contrast .bg-blue-50,
      .high-contrast .bg-emerald-50,
      .high-contrast .bg-indigo-50,
      .high-contrast .bg-blue-200 {
        background-color: #000000 !important;
        color: #ffffff !important;
      }
      .high-contrast .bg-green-100, .high-contrast .bg-red-100, .high-contrast .bg-blue-100, .high-contrast .bg-violet-100, .high-contrast .bg-purple-100, .high-contrast .bg-orange-100, .high-contrast .bg-teal-100, .high-contrast .bg-indigo-100 {
        background-color: #000000 !important;
        color: #ffffff !important;
        border: 2px solid #000000 !important;
      }
      /* Ensure text inside colored badges is white */
      .high-contrast .text-blue-700, .high-contrast .text-green-700, .high-contrast .text-purple-700,
      .high-contrast .text-orange-700, .high-contrast .text-red-700, .high-contrast .text-teal-700,
      .high-contrast .text-indigo-700 {
        color: #ffffff !important;
      }
      .high-contrast .text-blue-600, .high-contrast .text-indigo-600, .high-contrast .text-violet-600 {
        color: #000044 !important;
        font-weight: 800 !important;
      }
      .high-contrast #cat-desc {
        color: #ffffff !important;
        padding: 8px 16px !important;
        border-radius: 0.5rem !important;
      }

      /* === 4. PURPLE GRADIENT SECTION (Mobile App CTA) === */
      .high-contrast .bg-gradient-to-br.from-indigo-600 {
        background: #000000 !important;
      }
      .high-contrast .bg-gradient-to-br.from-indigo-600 h2,
      .high-contrast .bg-gradient-to-br.from-indigo-600 p,
      .high-contrast .bg-gradient-to-br.from-indigo-600 span {
        color: #ffffff !important;
      }
      .high-contrast .text-indigo-100,
      .high-contrast .text-indigo-200 {
        color: #ffffff !important;
      }
      .high-contrast .text-indigo-400,
      .high-contrast .text-indigo-600 {
        color: #000044 !important;
        font-weight: 700 !important;
      }

      /* === 5. BUTTONS (Secondary): Strong borders === */
      .high-contrast .border, .high-contrast .border-gray-200, .high-contrast .border-gray-100, .high-contrast .border-slate-200, .high-contrast .border-gray-300 {
        border-color: #000000 !important;
        border-width: 2px !important;
      }
      .high-contrast .bg-blue-600, .high-contrast .bg-indigo-600, .high-contrast .bg-violet-600, .high-contrast .bg-primary-500, .high-contrast .bg-secondary-500, .high-contrast .bg-indigo-500 {
        background-color: #000000 !important;
        color: #ffffff !important;
        border: 2px solid #000000 !important;
      }
      .high-contrast .bg-white.text-gray-900 {
        background-color: #ffffff !important;
        color: #000000 !important;
        border: 3px solid #000000 !important;
        font-weight: 800 !important;
      }

      /* === 6. FOOTER: All text to high contrast === */
      .high-contrast footer {
        background-color: #ffffff !important;
      }
      .high-contrast footer .text-gray-500,
      .high-contrast footer .text-gray-400,
      .high-contrast footer .text-gray-600,
      .high-contrast footer .text-sm {
        color: #000000 !important;
      }
      .high-contrast footer a {
        color: #000000 !important;
        font-weight: 600 !important;
      }

      /* === GENERAL HIGH CONTRAST HELPERS === */
      .high-contrast *, .high-contrast *:before, .high-contrast *:after {
        border-color: #000000 !important;
        --tw-text-opacity: 1 !important;
        --tw-border-opacity: 1 !important;
      }
      .high-contrast .task-card {
        background-color: #ffffff !important;
        border: 3px solid #000000 !important;
        box-shadow: none !important;
      }
      .high-contrast .btn, .high-contrast button, .high-contrast .sub-menu-link {
        font-weight: 700 !important;
      }
      .high-contrast .maplibregl-popup-content .bg-blue-600, 
      .high-contrast .maplibregl-popup-content .bg-violet-600 {
        border: none !important;
      }
      .high-contrast a:hover:not(.flex.items-center), 
      .high-contrast button:hover {
        background-color: #000000 !important;
        color: #ffffff !important;
        text-decoration: underline !important;
      }
      /* Exclude logo from black background on hover */
      .high-contrast a.flex.items-center:hover {
        background-color: transparent !important;
      }
      /* Navbar Settings & Links */
      .high-contrast .desktop-navbar a {
        text-decoration: underline !important;
        font-weight: 700 !important;
      }
      /* Navbar Settings Dropdown & Submenus */
      .high-contrast #settings-menu,
      .high-contrast #settings-menu .submenu {
          background-color: #ffffff !important;
          border: 3px solid #000000 !important;
          color: #000000 !important;
          z-index: 100 !important;
      }
      .high-contrast #settings-menu div:not([id*="-indicator"]):not(.dot),
      .high-contrast #settings-menu button {
          background-color: #ffffff !important;
          color: #000000 !important;
          text-decoration: underline !important;
          text-underline-offset: 4px;
          font-weight: 800 !important;
          border: none !important;
      }
      /* Hover state: Parent item or current button/div turns black */
      .high-contrast #settings-menu div:hover:not([id*="-indicator"]):not(.dot),
      .high-contrast #settings-menu button:hover,
      .high-contrast #settings-menu .group:hover > div:first-child {
          background-color: #000000 !important;
          color: #ffffff !important;
      }
      /* Accessibility Toggles in High Contrast */
      .high-contrast [id*="-indicator"] {
          background-color: #ffffff !important;
          border: 3px solid #000000 !important;
          width: 2.5rem !important; /* Slightly wider */
          height: 1.25rem !important;
          opacity: 1 !important;
      }
      .high-contrast [id*="-indicator"].bg-blue-600 {
          background-color: #000000 !important;
      }
      .high-contrast [id*="-indicator"] .dot {
          background-color: #000000 !important;
          border: 1px solid #ffffff;
          width: 0.75rem !important;
          height: 0.75rem !important;
          top: 1.5px !important;
          left: 1.5px !important;
      }
      .high-contrast [id*="-indicator"].bg-blue-600 .dot {
          background-color: #ffffff !important;
          border: 1px solid #000000;
          transform: translateX(20px) !important;
      }
      /* Submenu items visibility */
      .high-contrast #settings-menu .submenu div,
      .high-contrast #settings-menu .submenu button {
          background-color: #ffffff !important;
          color: #000000 !important;
      }
      .high-contrast #settings-menu .submenu div:hover {
          background-color: #000000 !important;
          color: #ffffff !important;
      }
      /* Maintain hidden state correctly */
      .high-contrast #settings-menu.hidden { display: none !important; }
      .high-contrast #settings-menu:not(.show) { opacity: 0 !important; pointer-events: none !important; }
      .high-contrast #settings-menu.show { opacity: 1 !important; transform: none !important; }
      .high-contrast #settings-menu .group > .submenu {
          opacity: 0 !important;
          pointer-events: none !important;
      }
      .high-contrast #settings-menu .group:hover > .submenu {
          opacity: 1 !important;
          pointer-events: auto !important;
          transform: none !important;
          display: block !important;
      }

      /* Footer Highlighting */
      .high-contrast footer p, 
      .high-contrast footer div.text-sm,
      .high-contrast footer .text-gray-500,
      .high-contrast footer .text-gray-400 {
        color: #000000 !important;
        font-weight: 600 !important;
        opacity: 1 !important;
      }

      /* Global Reduced Motion Hover Fix */
      .reduced-motion *:hover:not(.dot):not(img),
      .reduced-motion .group:hover:not(.dot):not(img),
      .reduced-motion .group:hover *:not(.dot):not(img) {
        transform: none !important;
        -webkit-transform: none !important;
        transition: none !important;
        -webkit-transition: none !important;
        box-shadow: none !important;
      }
      .high-contrast input, .high-contrast select, .high-contrast textarea {
        border: 2px solid #000000 !important;
        background-color: #ffffff !important;
        color: #000000 !important;
        opacity: 1 !important;
      }
      .high-contrast ::placeholder {
        color: #000000 !important;
        opacity: 0.7 !important;
      }
      /* Decorative blobs/dots should be hidden in high contrast */
      .high-contrast [style*="radial-gradient"],
      .high-contrast [class*="blur-"] {
        opacity: 0 !important;
      }
      .high-contrast .sr-only:not(:focus) {
          /* Ensure sr-only stays hidden but accessible */
      }

      /* Hamburger button */
      .mobile-hamburger {
        background: none;
        border: none;
        cursor: pointer;
        padding: 6px;
        display: flex;
        flex-direction: column;
        gap: 5px;
        z-index: 201;
      }
      .mobile-hamburger span {
        display: block;
        width: 22px;
        height: 2.5px;
        background: #1a1a2e;
        border-radius: 2px;
        transition: all 0.3s ease;
      }
      .mobile-hamburger.active span:nth-child(1) {
        transform: translateY(7.5px) rotate(45deg);
      }
      .mobile-hamburger.active span:nth-child(2) {
        opacity: 0;
      }
      .mobile-hamburger.active span:nth-child(3) {
        transform: translateY(-7.5px) rotate(-45deg);
      }

      /* Mobile sidebar overlay */
      .mobile-sidebar-overlay {
        display: none;
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.4);
        z-index: 190;
        opacity: 0;
        transition: opacity 0.3s ease;
      }
      .mobile-sidebar-overlay.active {
        display: block;
        opacity: 1;
      }

      /* Mobile sidebar */
      .mobile-sidebar {
        position: fixed;
        top: 0;
        left: -100%;
        width: 85%;
        max-width: 340px;
        height: 100vh;
        background: #fff;
        z-index: 200;
        transition: left 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        overflow-y: auto;
        box-shadow: 4px 0 25px rgba(0,0,0,0.15);
        padding-bottom: 80px; /* Extra space for system nav bars */
        overscroll-behavior: contain;
      }
      .mobile-sidebar.active {
        left: 0;
      }

      /* Sidebar header */
      .mobile-sidebar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 18px 20px;
        border-bottom: 1px solid #f0f0f0;
      }
      .mobile-sidebar-close {
        background: none;
        border: none;
        font-size: 28px;
        color: #333;
        cursor: pointer;
        padding: 4px 8px;
        line-height: 1;
        transition: color 0.2s;
      }
      .mobile-sidebar-close:hover {
        color: #6366f1;
      }

      /* Sidebar user info */
      .mobile-sidebar-user {
        padding: 16px 20px;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        align-items: center;
        gap: 12px;
      }
      .mobile-sidebar-user img {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #e5e7eb;
      }
      .mobile-sidebar-user-name {
        font-size: 15px;
        font-weight: 600;
        color: #1a1a2e;
      }
      .mobile-sidebar-user-sub {
        font-size: 12px;
        color: #888;
      }

      /* Sidebar navigation links */
      .mobile-sidebar-nav {
        padding: 8px 0;
      }
      .mobile-sidebar-link {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 20px;
        font-size: 15px;
        font-weight: 500;
        color: #333;
        text-decoration: none;
        transition: background 0.2s, color 0.2s;
        border-left: 3px solid transparent;
      }
      .mobile-sidebar-link:hover,
      .mobile-sidebar-link.active {
        background: #f5f3ff;
        color: #6366f1;
        border-left-color: #6366f1;
      }
      .mobile-sidebar-link i,
      .mobile-sidebar-link svg {
        width: 18px;
        height: 18px;
        color: #6b7280;
        flex-shrink: 0;
      }
      .mobile-sidebar-link:hover i,
      .mobile-sidebar-link:hover svg {
        color: #6366f1;
      }
      .mobile-sidebar-divider {
        height: 1px;
        background: #f0f0f0;
        margin: 6px 0;
      }
      .mobile-sidebar-section-label {
        padding: 12px 20px 6px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #9ca3af;
      }

      /* Mobile post-task button in sidebar */
      .mobile-sidebar-cta {
        display: block;
        margin: 12px 20px;
        padding: 12px;
        text-align: center;
        background: #6366f1;
        color: #fff;
        font-weight: 600;
        font-size: 15px;
        border-radius: 10px;
        text-decoration: none;
        transition: background 0.2s, transform 0.15s;
      }
      .mobile-sidebar-cta:hover {
        background: #4f46e5;
        transform: translateY(-1px);
        color: #fff;
      }

      /* Mobile right-side profile dropdown */
      .mobile-profile-btn {
        background: none;
        border: none;
        cursor: pointer;
        padding: 0;
        position: relative;
      }
      .mobile-profile-btn img {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #e5e7eb;
        transition: border-color 0.2s;
      }
      .mobile-profile-btn:hover img {
        border-color: #6366f1;
      }
      .mobile-profile-dropdown {
        position: absolute;
        top: 52px;
        right: 0;
        width: 260px;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.15);
        border: 1px solid #e5e7eb;
        overflow: hidden;
        z-index: 210;
        opacity: 0;
        transform: translateY(-8px) scale(0.95);
        pointer-events: none;
        transition: opacity 0.25s ease, transform 0.25s ease;
      }
      .mobile-profile-dropdown.active {
        opacity: 1;
        transform: translateY(0) scale(1);
        pointer-events: auto;
      }
      .mobile-profile-dropdown-user {
        padding: 14px 16px;
        border-bottom: 1px solid #f0f0f0;
        cursor: pointer;
        transition: background 0.2s;
      }
      .mobile-profile-dropdown-user:hover {
        background: #f5f3ff;
      }
      .mobile-profile-dropdown-user h4 {
        font-size: 14px;
        font-weight: 600;
        color: #1a1a2e;
        margin: 0;
      }
      .mobile-profile-dropdown-user p {
        font-size: 12px;
        color: #888;
        margin: 2px 0 0;
      }
      .mobile-profile-dropdown-links {
        padding: 6px 0;
      }
      .mobile-profile-dropdown-links a {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 16px;
        font-size: 14px;
        color: #333;
        text-decoration: none;
        transition: background 0.2s, color 0.2s;
      }
      .mobile-profile-dropdown-links a:hover {
        background: #f5f3ff;
        color: #6366f1;
      }
      .mobile-profile-dropdown-links a i {
        width: 16px;
        height: 16px;
        color: #6b7280;
      }
      .mobile-profile-dropdown-links a:hover i {
        color: #6366f1;
      }
      .mobile-profile-dropdown-divider {
        height: 1px;
        background: #f0f0f0;
        margin: 0;
      }

      /* Mobile guest buttons */
      .mobile-guest-btns {
        display: flex;
        align-items: center;
        gap: 8px;
      }
      .mobile-guest-btns a {
        padding: 7px 14px;
        font-size: 13px;
        font-weight: 600;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.2s;
      }
      .mobile-login-btn {
        background: #6366f1;
        color: #fff !important;
      }
      .mobile-login-btn:hover {
        background: #4f46e5;
      }
      .mobile-signup-btn {
        border: 1.5px solid #6366f1;
        color: #6366f1 !important;
        background: transparent;
      }
      .mobile-signup-btn:hover {
        background: rgba(99,102,241,0.08);
      }
    </style>
    <!-- Feather icons (used in navbar) -->
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">
    <!-- Skip to Main Content Link -->
    <a href="#main-content" id="skip-link" class="focus:not-sr-only focus:fixed focus:top-4 focus:left-4 focus:z-[100] focus:px-4 focus:py-2 focus:bg-blue-600 focus:text-white focus:rounded-lg sr-only">
        Skip to main content
    </a>


    <!-- 🔹 NAVBAR (overrideable) -->
@hasSection('navbar')
@yield('navbar')
@else

{{-- ===== MOBILE NAVBAR (visible < 768px) ===== --}}
<nav class="mobile-navbar" aria-label="Mobile main navigation">
  {{-- Left: Hamburger --}}
  <button class="mobile-hamburger" id="mobileHamburger" aria-label="Open sidebar menu" aria-controls="mobileSidebar" aria-expanded="false">
    <span></span>
    <span></span>
    <span></span>
  </button>

  {{-- Center: Logo --}}
  <a href="{{ url('/index') }}" class="flex items-center">
    <img src="{{ asset('assets/img/logo.png') }}" alt="Minijobz Logo" style="height: 28px; width: auto; object-fit: contain;">
  </a>

  {{-- Right: Profile picture / Login --}}
  <div style="position: relative;">
    @auth
      @php
        $currentUser = auth()->user();
        $fullName = trim(($currentUser->first_name ?? '') . ' ' . ($currentUser->last_name ?? ''))
            ?: ($currentUser->name ?? $currentUser->email);
        $avatarSrc = $currentUser->avatar_url;
        $unreadCount = $currentUser->unreadNotifications()->count();
        $notifications = $currentUser->notifications()->limit(5)->get();
      @endphp
      <button class="mobile-profile-btn" id="mobileProfileBtn" type="button" aria-label="Open profile menu" aria-controls="mobileProfileDropdown" aria-expanded="false">
        <img src="{{ $avatarSrc }}" alt="" aria-hidden="true" class="w-8 h-8 rounded-full object-cover">
        @if($unreadCount > 0)
          <span class="sr-only">{{ $unreadCount }} unread notifications</span>
          <span style="position:absolute;top:-2px;right:-2px;width:16px;height:16px;background:#ef4444;color:#fff;font-size:9px;font-weight:700;border-radius:50%;display:flex;align-items:center;justify-content:center;border:2px solid #fff;" aria-hidden="true">{{ $unreadCount }}</span>
        @endif
      </button>

      {{-- Mobile profile dropdown --}}
      <div class="mobile-profile-dropdown" id="mobileProfileDropdown">
        <a href="{{ route('public-profile', auth()->id()) }}" class="mobile-profile-dropdown-user block no-underline transition-colors hover:bg-indigo-50">
          <h4 class="text-sm font-bold text-gray-900 group-hover:text-indigo-600 mb-0">{{ $fullName }}</h4>
          <p class="text-xs text-gray-500 mt-1 mb-0">{{ __('navbar.public_profile') }}</p>
        </a>
        <div class="mobile-profile-dropdown-links">
          <a href="{{ route('my-tasks') }}">
            <i data-feather="grid"></i> {{ __('navbar.dashboard') }}
          </a>
          <a href="{{ route('messages') }}">
            <i data-feather="message-square"></i> {{ __('navbar.messages') ?? 'Messages' }}
          </a>
          <a href="{{ route('notifications') }}">
            <i data-feather="bell"></i> {{ __('navbar.notifications') }}
            @if($unreadCount > 0)
              <span style="margin-left:auto;background:#ef4444;color:#fff;font-size:10px;font-weight:700;padding:1px 6px;border-radius:10px;">{{ $unreadCount }}</span>
            @endif
          </a>
          <a href="{{ route('profile') }}">
            <i data-feather="user"></i> {{ __('navbar.profile') }}
          </a>
          <a href="{{ route('profile', ['tab' => 'account']) }}">
            <i data-feather="settings"></i> {{ __('navbar.settings') }}
          </a>
          <a href="{{ route('profile', ['tab' => 'security']) }}">
            <i data-feather="shield"></i> {{ __('navbar.security') }}
          </a>
          <a href="{{ route('profile', ['tab' => 'billing']) }}">
            <i data-feather="credit-card"></i> {{ __('navbar.billing') }}
          </a>
        </div>
        <div class="mobile-profile-dropdown-divider"></div>
        <div class="mobile-profile-dropdown-links">
          <a href="#" onclick="event.preventDefault(); document.getElementById('mobile-logout-form').submit();" style="color:#dc2626;">
            <i data-feather="log-out" style="color:#dc2626;"></i> {{ __('navbar.logout') }}
          </a>
          <form id="mobile-logout-form" method="POST" action="{{ route('logout') }}" style="display:none;">
            @csrf
          </form>
        </div>
      </div>
    @endauth

    @guest
      <div class="mobile-guest-btns">
        <a href="{{ route('login') }}" class="mobile-login-btn">{{ __('navbar.login') }}</a>
        <a href="{{ route('register') }}" class="mobile-signup-btn">{{ __('navbar.sign_up') }}</a>
      </div>
    @endguest
  </div>
</nav>

{{-- Mobile Sidebar Overlay --}}
<div class="mobile-sidebar-overlay" id="mobileSidebarOverlay"></div>

{{-- Mobile Sidebar --}}
<div class="mobile-sidebar" id="mobileSidebar" role="dialog" aria-modal="true" aria-label="Main menu sidebar">
  <div class="mobile-sidebar-header">
    <a href="{{ url('/index') }}">
      <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" style="height: 26px; width: auto;">
    </a>
    <button class="mobile-sidebar-close" id="mobileSidebarClose" aria-label="Close menu">&times;</button>
  </div>

  @auth
    <a href="{{ route('public-profile', auth()->id()) }}" class="mobile-sidebar-user flex items-center gap-3 no-underline transition-colors hover:bg-indigo-50">
      <img src="{{ $avatarSrc }}" alt="Profile" class="w-10 h-10 rounded-full object-cover border border-gray-200">
      <div>
        <div class="mobile-sidebar-user-name text-sm font-bold text-gray-900">{{ $fullName }}</div>
        <div class="mobile-sidebar-user-sub text-xs text-gray-400">{{ __('navbar.public_profile') }}</div>
      </div>
    </a>
  @endauth

  <div class="mobile-sidebar-nav">
    <div class="mobile-sidebar-section-label">{{ __('navbar.categories') }}</div>
    <a href="{{ url('category') }}" class="mobile-sidebar-link">
      <i data-feather="grid"></i> {{ __('navbar.categories') }}
    </a>
    <a href="{{ route('messages') }}" class="mobile-sidebar-link">
      <i data-feather="message-square"></i> {{ __('navbar.messages') ?? 'Messages' }}
    </a>

    <a href="{{ route('post-task') }}" onclick="return checkLogin(event)" class="mobile-sidebar-cta">
      {{ __('navbar.post_task') }}
    </a>

    <div class="mobile-sidebar-divider"></div>
    <div class="mobile-sidebar-section-label">Navigation</div>

    <a href="{{ url('/index') }}" class="mobile-sidebar-link">
      <i data-feather="home"></i> Home
    </a>
    <a href="{{ url('tasks') }}" class="mobile-sidebar-link">
      <i data-feather="search"></i> {{ __('navbar.browse_tasks') }}
    </a>
    <a href="{{ url('howitworks') }}" class="mobile-sidebar-link">
      <i data-feather="help-circle"></i> {{ __('navbar.how_it_works') }}
    </a>

    @auth
      <div class="mobile-sidebar-divider"></div>
      <div class="mobile-sidebar-section-label">{{ __('navbar.dashboard') }}</div>
      <a href="{{ route('my-tasks') }}" class="mobile-sidebar-link">
        <i data-feather="clipboard"></i> {{ __('navbar.dashboard') }}
      </a>
      <a href="{{ route('notifications') }}" class="mobile-sidebar-link">
        <i data-feather="bell"></i> {{ __('navbar.notifications') }}
        @if($unreadCount > 0)
          <span style="margin-left:auto;background:#ef4444;color:#fff;font-size:10px;font-weight:700;padding:1px 6px;border-radius:10px;">{{ $unreadCount }}</span>
        @endif
      </a>
    @endauth

    <div class="mobile-sidebar-divider"></div>
    <div class="mobile-sidebar-section-label">{{ __('navbar.settings') }}</div>
    <a href="{{ route('profile', ['tab' => 'account']) }}" class="mobile-sidebar-link">
      <i data-feather="settings"></i> {{ __('navbar.settings') }}
    </a>

    <div class="mobile-sidebar-divider"></div>
      
  </div>

  @guest
    <div style="padding: 16px 20px; border-top: 1px solid #f0f0f0; margin-top: auto;">
      <a href="{{ route('login') }}" class="mobile-sidebar-cta" style="margin: 0 0 8px;">{{ __('navbar.login') }}</a>
      <a href="{{ route('register') }}" class="mobile-sidebar-cta" style="margin: 0; background: transparent; color: #6366f1; border: 1.5px solid #6366f1;">{{ __('navbar.sign_up') }}</a>
    </div>
  @endguest
</div>

{{-- ===== DESKTOP NAVBAR (visible >= 768px) ===== --}}
<nav class="desktop-navbar bg-white border-b border-gray-200 shadow-sm w-full z-50" aria-label="Desktop main navigation">
  <div class="max-w-7xl mx-auto flex justify-between items-center px-6 py-3">
    <!-- LEFT: Logo (aligned with Sidebar location) -->
    <div class="flex items-center md:w-1/5">
      <a href="{{ url('/index') }}" class="flex items-center">
        <img src="{{ asset('assets/img/logo.png') }}" alt="Minijobz" class="h-8 w-auto mix-blend-multiply">
      </a>
    </div>
 
    <!-- CENTER & RIGHT: Center Links and Auth (aligned with Section location) -->
    <div class="flex-1 flex justify-between items-center md:pl-10">
      <div class="flex items-center space-x-5">
        <a href="{{ route('post-task') }}" onclick="return checkLogin(event)" class="px-4 py-2 rounded-lg bg-secondary-500 text-white hover:bg-secondary-600 font-semibold">
          {{ __('navbar.post_task') }}
        </a>
 
  <!-- Mega Menu -->
  <div id="categories-group" class="relative group">
    <a href="{{ url('category') }}" id="categories-toggle" class="text-gray-600 hover:text-blue-700 font-medium inline-flex items-center px-2 py-2" aria-haspopup="true" aria-expanded="false">
      {{ __('navbar.categories') }}
      <svg class="ml-2 w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
      </svg>
    </a>

  <!-- Mega Dropdown -->
  <div id="categories-menu" class="absolute left-0 mt-3 hidden flex bg-white border border-gray-200 rounded-xl shadow-2xl w-[650px] z-50 overflow-hidden before:absolute before:-top-3 before:left-0 before:w-full before:h-3 before:content-[''] before:block">

    <!-- Left Section -->
    <div class="w-1/3 bg-gray-50 border-r border-gray-200 p-5 flex flex-col justify-start">
      <h3 class="text-gray-800 font-semibold text-lg mb-2">{{ __('navbar.pick_task_type') }}</h3>
      <p class="text-sm text-gray-500 leading-snug">{{ __('navbar.choose_category_desc') }}</p>
    </div>

    <!-- Right Section -->
    <div class="w-2/3 grid grid-cols-2 gap-6 p-6">

      <!-- Home Services -->
      <div>
        <h3 class="font-semibold text-indigo-600 mb-2">Home Services</h3>
        <ul class="space-y-1 text-gray-700 text-sm">
          <li><a href="/categories/handyman" class="hover:text-indigo-600">Handyman</a></li>
          <li><a href="/categories/plumbing" class="hover:text-indigo-600">Plumbing</a></li>
          <li><a href="/categories/electrical" class="hover:text-indigo-600">Electrical Repairs</a></li>
          <li><a href="/categories/carpentry" class="hover:text-indigo-600">Carpentry</a></li>
          <li><a href="/categories/painting" class="hover:text-indigo-600">Painting</a></li>
          <li><a href="/categories/roofing" class="hover:text-indigo-600">Roofing</a></li>
        </ul>
      </div>

      <!-- Cleaning & Maintenance -->
      <div>
        <h3 class="font-semibold text-indigo-600 mb-2">Cleaning & Maintenance</h3>
        <ul class="space-y-1 text-gray-700 text-sm">
          <li><a href="/categories/house-cleaning" class="hover:text-indigo-600">House Cleaning</a></li>
          <li><a href="/categories/carpet-cleaning" class="hover:text-indigo-600">Carpet Cleaning</a></li>
          <li><a href="/categories/window-cleaning" class="hover:text-indigo-600">Window Cleaning</a></li>
          <li><a href="/categories/laundry" class="hover:text-indigo-600">Laundry</a></li>
          <li><a href="/categories/rubbish-removal" class="hover:text-indigo-600">Rubbish Removal</a></li>
          <li><a href="/categories/gardening" class="hover:text-indigo-600">Gardening</a></li>
        </ul>
      </div>

      <!-- Moving & Delivery -->
      <div>
        <h3 class="font-semibold text-indigo-600 mb-2">Moving & Delivery</h3>
        <ul class="space-y-1 text-gray-700 text-sm">
          <li><a href="/categories/removals" class="hover:text-indigo-600">Removals</a></li>
          <li><a href="/categories/courier-services" class="hover:text-indigo-600">Courier Services</a></li>
          <li><a href="/categories/delivery" class="hover:text-indigo-600">Delivery</a></li>
          <li><a href="/categories/food-delivery" class="hover:text-indigo-600">Food Delivery</a></li>
          <li><a href="/categories/grocery-delivery" class="hover:text-indigo-600">Grocery Delivery</a></li>
          <li><a href="/categories/vehicle-transport" class="hover:text-indigo-600">Vehicle Transport</a></li>
        </ul>
      </div>

      <!-- Personal Care & Wellness -->
      <div>
        <h3 class="font-semibold text-indigo-600 mb-2">Personal Care & Wellness</h3>
        <ul class="space-y-1 text-gray-700 text-sm">
          <li><a href="/categories/hairdressers" class="hover:text-indigo-600">Hairdressers</a></li>
          <li><a href="/categories/beauticians" class="hover:text-indigo-600">Beauticians</a></li>
          <li><a href="/categories/makeup-artists" class="hover:text-indigo-600">Makeup Artists</a></li>
          <li><a href="/categories/barbers" class="hover:text-indigo-600">Barbers</a></li>
          <li><a href="/categories/fitness" class="hover:text-indigo-600">Fitness</a></li>
          <li><a href="/categories/health-and-wellness" class="hover:text-indigo-600">Health & Wellness</a></li>
        </ul>
      </div>

      <!-- Business & Tech -->
      <div>
        <h3 class="font-semibold text-indigo-600 mb-2">Business & Tech</h3>
        <ul class="space-y-1 text-gray-700 text-sm">
          <li><a href="/categories/accounting" class="hover:text-indigo-600">Accounting</a></li>
          <li><a href="/categories/admin" class="hover:text-indigo-600">Admin</a></li>
          <li><a href="/categories/marketing" class="hover:text-indigo-600">Marketing</a></li>
          <li><a href="/categories/design" class="hover:text-indigo-600">Design</a></li>
          <li><a href="/categories/web" class="hover:text-indigo-600">Web</a></li>
          <li><a href="/categories/writing" class="hover:text-indigo-600">Writing</a></li>
        </ul>
      </div>

      <!-- Automotive -->
      <div>
        <h3 class="font-semibold text-indigo-600 mb-2">Automotive</h3>
        <ul class="space-y-1 text-gray-700 text-sm">
          <li><a href="/categories/car-wash" class="hover:text-indigo-600">Car Wash</a></li>
          <li><a href="/categories/car-detailing" class="hover:text-indigo-600">Car Detailing</a></li>
          <li><a href="/categories/car-service" class="hover:text-indigo-600">Car Service</a></li>
          <li><a href="/categories/car-repair" class="hover:text-indigo-600">Car Repair</a></li>
          <li><a href="/categories/mechanic" class="hover:text-indigo-600">Mechanic</a></li>
          <li><a href="/categories/motorcycle-mechanic" class="hover:text-indigo-600">Motorcycle Mechanic</a></li>
        </ul>
      </div>

      <!-- View All -->
      <div class="col-span-2 text-center border-t pt-3 mt-2">
        <a href="/category" class="inline-block text-indigo-600 font-medium hover:underline">{{ __('navbar.view_all_categories') }} →</a>
      </div>

    </div>
  </div>
</div>
 
  <a href="{{ url('tasks') }}" class="text-gray-600 hover:text-secondary-500">{{ __('navbar.browse_tasks') }}</a>
  <a href="{{ url('howitworks') }}" class="text-gray-600 hover:text-secondary-500">{{ __('navbar.how_it_works') }}</a>
</div>
 
<!-- RIGHT: Login / Signup / Settings -->
<div class="flex items-center space-x-3 pr-4">
  
  @guest
    <!-- Show Login and Sign Up for guests -->
    <a href="{{ route('login') }}" class="px-4 py-2 rounded-lg bg-primary-500 hover:bg-primary-600 text-white">
      {{ __('navbar.login') }}
    </a>
    <a href="{{ route('register') }}" class="px-4 py-2 rounded-lg bg-primary-500 hover:bg-primary-600 text-white">
      {{ __('navbar.sign_up') }}
    </a>
  @endguest

  @auth
    <!-- Right: avatar dropdown -->
    <div class="relative ml-auto pr-4">
      <button type="button" class="rounded-full overflow-hidden w-9 h-9 ring-1 ring-gray-300 hover:ring-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500" 
              id="user-menu-button" aria-expanded="false" aria-haspopup="true" onclick="toggleMenu()" aria-label="Open user profile menu">
        <img src="{{ $avatarSrc }}" alt="" class="w-full h-full object-cover">
      </button>
      <div class="sub-menu-wrap" id="subMenu" role="menu" aria-labelledby="user-menu-button" aria-orientation="vertical">
        <div class="sub-menu">
          <a href="{{ route('public-profile', Auth::id()) }}" class="user-info group block px-3 py-2 rounded-lg hover:bg-indigo-50 transition-colors no-underline">
            <h3 class="text-base font-bold text-gray-900 group-hover:text-[#6366f1] transition-colors">{{ $fullName }}</h3>
            <p class="text-xs text-gray-500 mb-0">{{ __('navbar.public_profile') }}</p>
          </a>
          <hr>
          <a href="{{ route('my-tasks') }}" class="sub-menu-link flex items-center gap-2">
            <i data-feather="grid" class="w-4 h-4"></i> {{ __('navbar.dashboard') }}
          </a>
          <a href="{{ route('messages') }}" class="sub-menu-link flex items-center gap-2">
            <i data-feather="message-square" class="w-4 h-4"></i> {{ __('navbar.messages') ?? 'Messages' }}
          </a>
          <a href="{{ route('notifications') }}" class="sub-menu-link flex items-center gap-2">
            <i data-feather="bell" class="w-4 h-4"></i> {{ __('navbar.notifications') }}
          </a>
          <a href="{{ route('profile') }}" class="sub-menu-link flex items-center gap-2">
            <i data-feather="user" class="w-4 h-4"></i> {{ __('navbar.profile') }}
          </a>
          <a href="{{ route('profile', ['tab' => 'account']) }}" class="sub-menu-link flex items-center gap-2">
            <i data-feather="settings" class="w-4 h-4"></i> {{ __('navbar.settings') }}
          </a>
          <a href="{{ route('profile', ['tab' => 'security']) }}" class="sub-menu-link flex items-center gap-2">
            <i data-feather="shield" class="w-4 h-4"></i> {{ __('navbar.security') }}
          </a>
          <a href="{{ route('profile', ['tab' => 'billing']) }}" class="sub-menu-link flex items-center gap-2">
            <i data-feather="credit-card" class="w-4 h-4"></i> {{ __('navbar.billing') }}
          </a>
          <hr>
          <a href="#" class="sub-menu-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('navbar.logout') }}</a>
          <form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">
            @csrf
          </form>
        </div>
      </div>
    </div>

    <!-- Notification Bell & Dropdown -->
    <div class="relative">
        <button class="notification-btn focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-full" 
                type="button" onclick="toggleNotifications()" 
                id="notifications-menu-button" aria-expanded="false" aria-haspopup="true" aria-label="Notifications">
          <svg viewBox="0 0 448 512" class="bell" aria-hidden="true">
            <path d="M224 0c-17.7 0-32 14.3-32 32V49.9C119.5 61.4 64 124.2 64 200v33.4c0 45.4-15.5 89.5-43.8 124.9L5.3 377c-5.8 7.2-6.9 17.1-2.9 25.4S14.8 416 24 416H424c9.2 0 17.6-5.3 21.6-13.6s2.9-18.2-2.9-25.4l-14.9-18.6C399.5 322.9 384 278.8 384 233.4V200c0-75.8-55.5-138.6-128-150.1V32c0-17.7-14.3-32-32-32zm0 96h8c57.4 0 104 46.6 104 104v33.4c0 47.9 13.9 94.6 39.7 134.6H72.3C98.1 328 112 281.3 112 233.4V200c0-57.4 46.6-104 104-104h8zm64 352H224 160c0 17 6.7 33.3 18.7 45.3s28.3 18.7 45.3 18.7s33.3-6.7 45.3-18.7s18.7-28.3 18.7-45.3z"></path>
          </svg>
          @if($unreadCount > 0)
            <span class="sr-only">{{ $unreadCount }} unread notifications</span>
            <span class="absolute top-0 right-0 flex items-center justify-center w-4 h-4 text-[9px] font-bold text-white bg-red-500 rounded-full border border-white transform translate-x-1 -translate-y-1" aria-hidden="true">
                {{ $unreadCount }}
            </span>
          @endif
        </button>

        <!-- Dropdown Menu -->
        <div id="notification-dropdown" 
             role="menu" aria-labelledby="notifications-menu-button"
             class="absolute right-0 mt-3 w-80 bg-white rounded-xl shadow-2xl border border-gray-200 overflow-hidden hidden transform origin-top-right transition-all duration-200 z-50">
            <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                <h3 class="font-bold text-gray-800">Notifications</h3>
                @if($unreadCount > 0)
                    <span onclick="markNotificationsRead()" class="text-xs text-blue-600 font-semibold cursor-pointer hover:underline">Mark all read</span>
                @endif
            </div>
            
            <div class="max-h-[400px] overflow-y-auto">
                @forelse($notifications as $notification)
                    <a href="{{ $notification->data['link'] ?? '#' }}" class="block p-4 hover:bg-gray-50 transition border-b border-gray-50 {{ $notification->read_at ? 'opacity-75' : 'bg-blue-50/30' }}">
                        <div class="flex gap-3">
                            <div class="mt-1">
                                <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                                    <i data-feather="bell" style="width:14px; height:14px;"></i>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-900 font-bold mb-0 truncate">
                                    {{ $notification->data['title'] ?? 'Notification' }}
                                </p>
                                <p class="text-xs text-gray-600 mb-0 truncate">
                                    {{ $notification->data['message'] ?? '' }}
                                </p>
                                <p class="text-[10px] text-gray-400 mt-1 uppercase font-semibold">
                                    {{ $notification->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="p-8 text-center text-gray-500">
                        <i data-feather="bell-off" class="mx-auto mb-2 opacity-50"></i>
                        <p class="text-sm">No notifications yet</p>
                    </div>
                @endforelse
            </div>
            
            <div class="p-3 bg-gray-50 text-center border-t border-gray-100">
                <a href="{{ route('notifications') }}" class="text-sm font-bold text-blue-600 hover:text-blue-700">View all notifications</a>
            </div>
        </div>
    </div>
  @endauth
 
  <!-- Settings dropdown -->
  <div class="relative">
    <button id="settings-button" class="p-2 rounded-full hover:bg-gray-200 transition" type="button">
      <i data-feather="settings"></i>
    </button>
    <div id="settings-menu" class="hidden absolute right-0 mt-2 w-40 bg-white border border-gray-300 rounded-lg shadow-lg z-[60] opacity-0 translate-y-2 transition-all duration-200 ease-out">
      <div class="flex flex-col">
        <div class="group relative">
          <div class="py-2 px-4 text-gray-700 font-semibold hover:bg-gray-100 cursor-pointer flex items-center gap-2">
            <i data-feather="chevron-left" class="w-4 h-4"></i>
            {{ __('navbar.theme') }}
          </div>
          <div class="submenu absolute top-0 right-full w-48 bg-white border border-gray-200 rounded-lg shadow-lg opacity-0 scale-95 transform transition-all duration-200 ease-out pointer-events-none group-hover:opacity-100 group-hover:scale-100 group-hover:pointer-events-auto">
            <div class="px-4 py-2 hover:bg-gray-100 cursor-pointer" data-theme="light">{{ __('navbar.light') }}</div>
            <div class="px-4 py-2 hover:bg-gray-100 cursor-pointer" data-theme="dark">{{ __('navbar.dark') }}</div>
            <div class="px-4 py-2 hover:bg-gray-100 cursor-pointer" data-theme="system">{{ __('navbar.system_default') }}</div>
          </div>
        </div>
        <div class="group relative">
          <div class="py-2 px-4 text-gray-700 font-semibold hover:bg-gray-100 cursor-pointer flex items-center gap-2">
            <i data-feather="chevron-left" class="w-4 h-4"></i>
            {{ __('navbar.language') }}
          </div>
          <div class="submenu absolute top-0 right-full w-48 bg-white border border-gray-200 rounded-lg shadow-lg opacity-0 scale-95 transform transition-all duration-200 ease-out pointer-events-none group-hover:opacity-100 group-hover:scale-100 group-hover:pointer-events-auto">
            <div class="px-4 py-2 hover:bg-gray-100 cursor-pointer" data-lang="en">English</div>
            <div class="px-4 py-2 hover:bg-gray-100 cursor-pointer" data-lang="hu">Hungarian</div>
          </div>
        </div>
        <div class="group relative" id="nav-accessibility-section">
          <div class="py-2 px-4 text-gray-700 font-semibold hover:bg-gray-100 cursor-pointer flex items-center gap-2">
            <i data-feather="chevron-left" class="w-4 h-4" aria-hidden="true"></i>
            Accessibility
          </div>
          <div class="submenu absolute top-0 right-full w-56 bg-white border border-gray-200 rounded-lg shadow-lg opacity-0 scale-95 transform transition-all duration-200 ease-out pointer-events-none group-hover:opacity-100 group-hover:scale-100 group-hover:pointer-events-auto p-1">
             <button type="button" onclick="toggleAccessibilitySetting('reduced-motion')" class="w-full text-left px-3 py-2 hover:bg-gray-100 rounded flex items-center justify-between text-sm">
                <span>Reduced Motion</span>
                <div id="nav-reduced-motion-indicator" class="w-8 h-4 bg-gray-200 rounded-full relative transition-colors">
                    <div class="dot absolute top-0.5 left-0.5 w-3 h-3 bg-white rounded-full transition-transform"></div>
                </div>
             </button>
             <button type="button" onclick="toggleAccessibilitySetting('high-contrast')" class="w-full text-left px-3 py-2 hover:bg-gray-100 rounded flex items-center justify-between text-sm">
                <span>High Contrast</span>
                <div id="nav-high-contrast-indicator" class="w-8 h-4 bg-gray-200 rounded-full relative transition-colors">
                    <div class="dot absolute top-0.5 left-0.5 w-3 h-3 bg-white rounded-full transition-transform"></div>
                </div>
             </button>
          </div>
        </div>
        <div class="group relative">
          <div class="py-2 px-4 text-gray-700 font-semibold hover:bg-gray-100 cursor-pointer flex items-center gap-2">
            <i data-feather="chevron-left" class="w-4 h-4"></i>
            {{ __('navbar.extras') }}
          </div>
          <div class="submenu absolute top-0 right-full w-48 bg-white border border-gray-200 rounded-lg shadow-lg opacity-0 scale-95 transform transition-all duration-200 ease-out pointer-events-none group-hover:opacity-100 group-hover:scale-100 group-hover:pointer-events-auto">
            <div class="px-4 py-2 hover:bg-gray-100 cursor-pointer">{{ __('navbar.help_faq') }}</div>
            <div class="px-4 py-2 hover:bg-gray-100 cursor-pointer">{{ __('navbar.contact_support') }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
 
</div>
</nav>
@endif


    <!-- 🔹 MAIN CONTENT -->
    <main id="main-content" tabindex="-1">
        @yield('content')
    </main>

    <!-- 🔹 FOOTER -->
    @hasSection('hideFooter')
    @else
    <footer class="bg-gray-50 border-t border-gray-200 pt-16 pb-8 mt-auto shadow-[0_-1px_2px_rgba(0,0,0,0.03)]">
        <div class="container mx-auto px-6 max-w-7xl">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">
                <!-- Column 1: Brand -->
                <div class="space-y-4">
                    <a href="{{ url('/index') }}" class="flex items-center">
                        <img src="{{ asset('assets/img/logo.png') }}" alt="Minijobz" class="h-8 w-auto mix-blend-multiply">
                    </a>
                    <p class="text-sm text-gray-500 leading-relaxed">
                        {{ __('footer.brand_description') }}
                    </p>
                </div>

                <!-- Column 2: Popular Categories -->
                <div>
                    <h3 class="font-bold text-gray-900 mb-4">{{ __('footer.popular_categories') }}</h3>
                    <ul class="space-y-3 text-sm text-gray-600">
                        <li><a href="{{ url('/categories/handyman') }}" class="hover:text-indigo-600 transition-colors">Handyman</a></li>
                        <li><a href="{{ url('/categories/cleaning') }}" class="hover:text-indigo-600 transition-colors">Cleaning</a></li>
                        <li><a href="{{ url('/categories/delivery') }}" class="hover:text-indigo-600 transition-colors">Delivery</a></li>
                        <li><a href="{{ url('/categories/gardening') }}" class="hover:text-indigo-600 transition-colors">Gardening</a></li>
                        <li><a href="{{ url('/categories/removals') }}" class="hover:text-indigo-600 transition-colors">Removals</a></li>
                    </ul>
                </div>

                <!-- Column 3: Company -->
                <div>
                    <h3 class="font-bold text-gray-900 mb-4">{{ __('footer.company') }}</h3>
                    <ul class="space-y-3 text-sm text-gray-600">
                        <li><a href="#" class="hover:text-indigo-600 transition-colors">{{ __('footer.about_us') }}</a></li>
                        <li><a href="#" class="hover:text-indigo-600 transition-colors">{{ __('footer.community_guidelines') }}</a></li>
                        <li><a href="#" class="hover:text-indigo-600 transition-colors">{{ __('footer.contact_us') }}</a></li>
                        <li><a href="#" class="hover:text-indigo-600 transition-colors">{{ __('footer.privacy_policy') }}</a></li>
                        <li><a href="#" class="hover:text-indigo-600 transition-colors">{{ __('footer.terms_and_conditions') }}</a></li>
                    </ul>
                </div>

                <!-- Column 4: Pages -->
                <div>
                    <h3 class="font-bold text-gray-900 mb-4">{{ __('footer.pages') }}</h3>
                    <ul class="space-y-3 text-sm text-gray-600">
                        <li><a href="{{ route('tasks') }}" class="hover:text-indigo-600 transition-colors">{{ __('navbar.browse_tasks') }}</a></li>
                        <li><a href="{{ route('howitworks') }}" class="hover:text-indigo-600 transition-colors">{{ __('navbar.how_it_works') }}</a></li>
                        @guest
                            <li><a href="{{ route('login') }}" class="hover:text-indigo-600 transition-colors">{{ __('navbar.login') }}</a></li>
                            <li><a href="{{ route('register') }}" class="hover:text-indigo-600 transition-colors">{{ __('navbar.sign_up') }}</a></li>
                        @endguest
                        @auth
                            <li><a href="{{ route('my-tasks') }}" class="hover:text-indigo-600 transition-colors">{{ __('navbar.dashboard') }}</a></li>
                            <li><a href="{{ route('profile') }}" class="hover:text-indigo-600 transition-colors">{{ __('navbar.profile') }}</a></li>
                        @endauth
                    </ul>
                </div>
            </div>

            <div class="pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-sm text-gray-500">
                    &copy; {{ date('Y') }} Minijobz. {{ __('footer.all_rights_reserved') }}
                </p>
                <div class="text-sm text-gray-400">
                    {{ __('footer.copyright_authors') }}
                </div>
            </div>
        </div>
    </footer>
    @endif

    <script>
      // Navbar settings dropdown behavior
      (function(){
        var btn = document.getElementById('settings-button');
        var menu = document.getElementById('settings-menu');
        var subMenu = document.getElementById('subMenu');
        var root = document.documentElement;
        // Theme helpers
        function applyTheme(mode){
          if(mode === 'system'){
            localStorage.setItem('theme','system');
            var prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            root.classList.toggle('dark', prefersDark);
          } else if(mode === 'dark'){
            root.classList.add('dark');
            localStorage.setItem('theme','dark');
          } else {
            root.classList.remove('dark');
            localStorage.setItem('theme','light');
          }
        }
        function applyAcc(type, val) {
            root.classList.toggle(type, val);
            localStorage.setItem(type, val);
            const indicator = document.getElementById('nav-' + type + '-indicator');
            if (indicator) {
                indicator.classList.toggle('bg-blue-600', val);
                indicator.classList.toggle('bg-gray-200', !val);
                const dot = indicator.querySelector('.dot');
                if (dot) dot.style.transform = val ? 'translateX(16px)' : 'translateX(0)';
            }
        }
        window.applyAccMode = function(enabled) {
            localStorage.setItem('accessibility-mode', enabled);
        }
        window.toggleAccessibilitySetting = function(type) {
            const current = localStorage.getItem(type) === 'true';
            applyAcc(type, !current);
        }
        // Init theme and accessibility on load
        try {
          var saved = localStorage.getItem('theme') || 'system';
          applyTheme(saved);
          
          // Always apply specific accessibility settings from localStorage on load
          applyAcc('reduced-motion', localStorage.getItem('reduced-motion') === 'true');
          applyAcc('high-contrast', localStorage.getItem('high-contrast') === 'true');

          const accMode = localStorage.getItem('accessibility-mode') === 'true';
          window.applyAccMode(accMode);
          // react to system changes if system mode selected
          window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(){
            if ((localStorage.getItem('theme') || 'system') === 'system') applyTheme('system');
          });
        } catch(e) {}
        var suppressUntil = 0;
        // Categories mega menu behavior (ensures visibility even if CSS hover fails)
        var catGroup = document.getElementById('categories-group');
        var catMenu = document.getElementById('categories-menu');
        
        // Settings dropdown handler
        if (btn && menu) {
          btn.addEventListener('mousedown', function(e){
            e.stopPropagation();
            var isHidden = menu.classList.contains('hidden');
            if (isHidden) {
              // Open
              menu.classList.remove('hidden','opacity-0','translate-y-2');
              menu.classList.add('show','opacity-100');
              suppressUntil = Date.now() + 150; // ignore immediate outside click
            } else {
              // Close
              menu.classList.remove('show','opacity-100');
              menu.classList.add('opacity-0','translate-y-2');
              setTimeout(function(){ menu.classList.add('hidden'); }, 150);
            }
          });
          // Prevent closing when interacting inside the dropdown
          ['mousedown','click'].forEach(function(ev){
            menu.addEventListener(ev, function(e){ e.stopPropagation(); });
          });
          document.addEventListener('mousedown', function(e){
            if (Date.now() < suppressUntil) return; // ignore the opening mousedown
            if (!btn.contains(e.target) && !menu.contains(e.target)) {
              menu.classList.remove('show','opacity-100');
              menu.classList.add('opacity-0','translate-y-2');
              setTimeout(function(){ menu.classList.add('hidden'); }, 150);
            }
          });
          // Close on Escape
          document.addEventListener('keydown', function(e){
            if (e.key === 'Escape') {
              menu.classList.remove('show','opacity-100');
              menu.classList.add('opacity-0','translate-y-2');
              setTimeout(function(){ menu.classList.add('hidden'); }, 150);
            }
          });
          // Ensure nested submenus open on hover
          var submenuGroups = menu.querySelectorAll('.group.relative');
          submenuGroups.forEach(function(g){
            var trigger = g.querySelector(':scope > div.py-2, :scope > .py-2');
            var submenu = g.querySelector(':scope .submenu');
            if (!submenu) return;
            g.addEventListener('mouseenter', function(){
              submenu.classList.remove('opacity-0','scale-95');
              submenu.style.pointerEvents = 'auto';
            });
            g.addEventListener('mouseleave', function(){
              submenu.classList.add('opacity-0','scale-95');
              submenu.style.pointerEvents = 'none';
            });
            // keyboard focus support
            g.addEventListener('focusin', function(){
              submenu.classList.remove('opacity-0','scale-95');
              submenu.style.pointerEvents = 'auto';
            });
            g.addEventListener('focusout', function(e){
              if (!g.contains(e.relatedTarget)) {
                submenu.classList.add('opacity-0','scale-95');
                submenu.style.pointerEvents = 'none';
              }
            });
            // Theme option clicks (Global)
            document.querySelectorAll('[data-theme]').forEach(function(opt){
              opt.addEventListener('click', function(e){
                e.stopPropagation();
                applyTheme(opt.getAttribute('data-theme'));
              });
            });

            // Language option clicks (Global)
            document.querySelectorAll('[data-lang]').forEach(function(opt){
              opt.addEventListener('click', function(e){
                e.stopPropagation();
                var locale = opt.getAttribute('data-lang');
                var form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ url("/language") }}/' + locale;
                var csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = '{{ csrf_token() }}';
                form.appendChild(csrfInput);
                document.body.appendChild(form);
                form.submit();
              });
            });
          });
        }
        
        // Avatar submenu toggle helper
        window.toggleMenu = function(){
          if (!subMenu) return;
          const isOpen = subMenu.classList.toggle('open-menu');
          const btn = document.getElementById('user-menu-button');
          if (btn) btn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        };
        
        if (catGroup && catMenu) {
          var catTimeout;
          
          var showCat = function(){
            clearTimeout(catTimeout);
            catMenu.classList.remove('hidden');
            const toggle = document.getElementById('categories-toggle');
            if (toggle) toggle.setAttribute('aria-expanded', 'true');
            // small delay to allow display change to register before opacity transition
            requestAnimationFrame(() => {
                catMenu.classList.remove('opacity-0','pointer-events-none','translate-y-2');
                catMenu.classList.add('opacity-100');
            });
          };
          
          var hideCat = function(){
            clearTimeout(catTimeout);
            catTimeout = setTimeout(function(){
                catMenu.classList.remove('opacity-100');
                catMenu.classList.add('opacity-0');
                const toggle = document.getElementById('categories-toggle');
                if (toggle) toggle.setAttribute('aria-expanded', 'false');
                // delay pointer-events toggle to allow transition
                setTimeout(function(){ 
                    if(catMenu.classList.contains('opacity-0')) {
                        catMenu.classList.add('pointer-events-none','translate-y-2');
                        catMenu.classList.add('hidden');
                    }
                }, 150);
            }, 300); // 300ms delay to bridge the gap
          };

          catGroup.addEventListener('mouseenter', showCat);
          catGroup.addEventListener('mouseleave', hideCat);
          
          // Also handle focus for accessibility
          catGroup.addEventListener('focusin', showCat);
          catGroup.addEventListener('focusout', function(e){
            // Delay strict focus check slightly or rely on the same timeout
            if (!catGroup.contains(e.relatedTarget)) hideCat();
          });
        }
        if (window.feather && typeof window.feather.replace === 'function') {
          window.feather.replace();
        }
      })();

      // ===== MOBILE NAVIGATION LOGIC =====
      (function() {
        var hamburger = document.getElementById('mobileHamburger');
        var sidebar = document.getElementById('mobileSidebar');
        var overlay = document.getElementById('mobileSidebarOverlay');
        var closeBtn = document.getElementById('mobileSidebarClose');
        var profileBtn = document.getElementById('mobileProfileBtn');
        var profileDropdown = document.getElementById('mobileProfileDropdown');

        function openSidebar() {
          if (!sidebar || !overlay) return;
          sidebar.classList.add('active');
          overlay.classList.add('active');
          if (hamburger) {
              hamburger.classList.add('active');
              hamburger.setAttribute('aria-expanded', 'true');
          }
          document.body.style.overflow = 'hidden';
        }
        function closeSidebar() {
          if (!sidebar || !overlay) return;
          sidebar.classList.remove('active');
          overlay.classList.remove('active');
          if (hamburger) {
              hamburger.classList.remove('active');
              hamburger.setAttribute('aria-expanded', 'false');
          }
          document.body.style.overflow = '';
        }

        if (hamburger) hamburger.addEventListener('click', function() {
          if (sidebar && sidebar.classList.contains('active')) {
            closeSidebar();
          } else {
            openSidebar();
          }
        });
        if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
        if (overlay) overlay.addEventListener('click', closeSidebar);

        // Profile dropdown toggle
        if (profileBtn && profileDropdown) {
          profileBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            const isActive = profileDropdown.classList.toggle('active');
            profileBtn.setAttribute('aria-expanded', isActive ? 'true' : 'false');
          });
          document.addEventListener('click', function(e) {
            if (profileDropdown && !profileDropdown.contains(e.target) && !profileBtn.contains(e.target)) {
              profileDropdown.classList.remove('active');
              profileBtn.setAttribute('aria-expanded', 'false');
            }
          });
        }


        // Re-apply feather icons for mobile sidebar
        setTimeout(function() {
          if (window.feather && typeof window.feather.replace === 'function') {
            window.feather.replace();
          }
        }, 100);
      })();

      // Notification Dropdown Logic
      function toggleNotifications() {
          const dropdown = document.getElementById('notification-dropdown');
          const btn = document.getElementById('notifications-menu-button');
          if (dropdown.classList.contains('hidden')) {
              dropdown.classList.remove('hidden');
              btn.setAttribute('aria-expanded', 'true');
              setTimeout(() => {
                  dropdown.classList.remove('opacity-0', 'scale-95');
                  dropdown.classList.add('opacity-100', 'scale-100');
              }, 10);
          } else {
              dropdown.classList.remove('opacity-100', 'scale-100');
              dropdown.classList.add('opacity-0', 'scale-95');
              btn.setAttribute('aria-expanded', 'false');
              setTimeout(() => {
                  dropdown.classList.add('hidden');
              }, 200);
          }
      }

      // Close notifications on click outside
      document.addEventListener('click', function(e) {
          const dropdown = document.getElementById('notification-dropdown');
          const btn = document.querySelector('.notification-btn');
          if (dropdown && !dropdown.classList.contains('hidden') && !dropdown.contains(e.target) && !btn.contains(e.target)) {
              toggleNotifications();
          }
      }); 

      function markNotificationsRead() {
          fetch('{{ route("notifications.mark-read") }}', {
              method: 'POST',
              headers: {
                  'X-CSRF-TOKEN': '{{ csrf_token() }}',
                  'Content-Type': 'application/json'
              }
          })
          .then(response => response.json())
          .then(data => {
              if (data.success) {
                  // Remove badge
                  const badge = document.querySelector('.notification-btn span');
                  if (badge) badge.remove();
                  
                  // Hide "Mark all read"
                  const markReadBtn = document.querySelector('#notification-dropdown span[onclick="markNotificationsRead()"]');
                  if (markReadBtn) markReadBtn.remove();

                  // Close the dropdown immediately
                  toggleNotifications();

                  // Clear the list for next open (optional, based on request "dropdown is clear")
                  // If you want to show "No notifications" state:
                  const listContainer = document.querySelector('#notification-dropdown .max-h-\\[400px\\]');
                  if(listContainer) {
                      listContainer.innerHTML = `
                        <div class="p-8 text-center text-gray-500">
                            <i data-feather="bell-off" class="mx-auto mb-2 opacity-50" style="width: 24px; height: 24px;"></i>
                            <p class="text-sm">No new notifications</p>
                        </div>
                      `;
                      if (window.feather) window.feather.replace();
                  }
              }
          });
      } 

      function checkLogin(e) {
        if (!window.isAuthenticated) {
            e.preventDefault();
            window.location.href = "{{ route('login') }}";
            return false;
        }
        return true;
      }
    </script>
    {{-- =========================================
         FLOATING SUPPORT CHATBOT
         ========================================= --}}
   Floating Button 
    <button id="chatbot-toggle" onclick="toggleChatbot()" class="fixed bottom-6 right-6 w-14 h-14 bg-indigo-600 text-white rounded-full flex items-center justify-center shadow-lg hover:shadow-indigo-300 hover:bg-indigo-700 hover:-translate-y-1 transition-all z-[100] focus:outline-none">
        <i data-feather="message-circle" class="w-6 h-6"></i>
    </button>

    <!-- Chatbot Window -->
    <div id="chatbot-window" class="fixed bottom-24 right-6 w-[90vw] max-w-[380px] bg-white border border-gray-200 rounded-2xl shadow-2xl z-[100] flex flex-col transition-all duration-300 transform opacity-0 translate-y-4 pointer-events-none" style="height: 500px; max-height: 70vh;">
        <!-- Header -->
        <div class="bg-indigo-600 text-white p-4 flex justify-between items-center rounded-t-2xl">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center shrink-0">
                    <i data-feather="help-circle" class="w-4 h-4 text-white"></i>
                </div>
                <div>
                    <h3 class="font-bold text-sm m-0 leading-tight">MiniJobz Assistant</h3>
                    <p class="text-indigo-200 text-xs m-0">Online - How can we help?</p>
                </div>
            </div>
            <button onclick="toggleChatbot()" class="text-white hover:text-indigo-200 transition focus:outline-none rounded p-1">
                <i data-feather="x" class="w-5 h-5"></i>
            </button>
        </div> 

        <!-- Chat Area -->
        <div id="chatbot-messages" class="flex-1 p-4 bg-gray-50 overflow-y-auto flex flex-col gap-4 custom-scrollbar">
            <!-- Initial Greeting -->
            <div class="flex items-start gap-2">
                <div class="w-6 h-6 rounded-full bg-indigo-100 flex items-center justify-center shrink-0 mt-1">
                    <i data-feather="cpu" class="w-3 h-3 text-indigo-600"></i>
                </div>
                <div class="bg-white border border-gray-100 p-3 rounded-2xl rounded-tl-sm shadow-sm text-sm text-gray-700 inline-block max-w-[85%]">
                    Hi there! 👋 I'm your MiniJobz virtual assistant. Please select one of the common questions below to learn more.
                </div>
            </div>

            <!-- FAQ Buttons Container -->
            <div id="chatbot-faq-options" class="flex flex-col gap-2 pl-8">
                <button onclick="handleFaqClick('how-to-post')" class="text-left bg-white border border-indigo-100 hover:border-indigo-300 hover:bg-indigo-50 text-indigo-700 text-sm px-4 py-2 rounded-xl transition-colors shadow-sm w-fit">
                    How do I post a task?
                </button>
                <button onclick="handleFaqClick('how-to-apply')" class="text-left bg-white border border-indigo-100 hover:border-indigo-300 hover:bg-indigo-50 text-indigo-700 text-sm px-4 py-2 rounded-xl transition-colors shadow-sm w-fit">
                    How do I apply for a task?
                </button>
                <button onclick="handleFaqClick('payment')" class="text-left bg-white border border-indigo-100 hover:border-indigo-300 hover:bg-indigo-50 text-indigo-700 text-sm px-4 py-2 rounded-xl transition-colors shadow-sm w-fit">
                    How does payment work?
                </button>
                <button onclick="handleFaqClick('fees')" class="text-left bg-white border border-indigo-100 hover:border-indigo-300 hover:bg-indigo-50 text-indigo-700 text-sm px-4 py-2 rounded-xl transition-colors shadow-sm w-fit">
                    Are there any fees?
                </button>
            </div>
        </div>

        <!-- Input Area (Enabled for FAQ bot) -->
        <form id="chatbot-form" onsubmit="handleChatSubmit(event)" class="p-3 bg-white border-t border-gray-100 flex items-center gap-2 rounded-b-2xl">
            <input type="text" id="chatbot-input" placeholder="Ask me something..." class="flex-1 bg-gray-50 border border-gray-100 rounded-full px-4 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white outline-none transition-all text-gray-700">
            <button type="submit" class="w-9 h-9 rounded-full bg-indigo-600 text-white flex items-center justify-center hover:bg-indigo-700 transition shadow-sm">
                <i data-feather="send" class="w-4 h-4 ml-0.5 mt-0.5"></i>
            </button>
        </form>
    </div>

    <script>
        // Dictionary of FAQ Answers
        const chatbotFaqs = {
            'how-to-post': "To post a task, make sure you are logged in. Click the <strong>'Post a Task'</strong> button in the top navigation bar. Fill out details like the title, category, location, and your budget, then click <strong>'Publish'</strong>. Your task will instantly be visible to all Taskers!",
            'how-to-apply': "To apply for a task, go to the <strong>'Browse Tasks'</strong> page and click on a task you're interested in. You will see a <strong>'Make an offer'</strong> section where you can propose your price and send a message to the employer.",
            'payment': "Currently, MiniJobz connects people who need help with those who can provide it. Payment terms should be discussed directly between the Employer and the Tasker via our built-in <strong>Messages</strong> system before work begins.",
            'fees': "MiniJobz is currently <strong>100% free</strong> to use! There are no fees for posting tasks, making offers, or communicating. You keep everything you earn.",
            'profile': "You can manage your profile by clicking on your avatar in the navbar and selecting <strong>'Profile'</strong>. There you can update your bio, skills, and contact information.",
            'notifications': "Notifications alert you to new messages, offers on your tasks, or when your offer is accepted. You can view them by clicking the <strong>Bell icon</strong> in the navbar.",
            'security': "To update your password or manage account security, go to <strong>'Profile'</strong> and then select the <strong>'Security'</strong> tab.",
            'direct-quotes': "If you see a professional you like, you can send them a **Direct Quote** request from their profile. This lets you negotiate specifically with one person.",
            'dashboard': "Your <strong>Dashboard</strong> (My Tasks) is where you track all tasks you've posted or applied for. It helps you stay organized with your active jobs."
        };

        function toggleChatbot() {
            const wind = document.getElementById('chatbot-window');
            const btn = document.getElementById('chatbot-toggle');
            if (wind.classList.contains('opacity-0')) {
                // Open
                wind.classList.remove('opacity-0', 'translate-y-4', 'pointer-events-none');
                wind.classList.add('opacity-100', 'translate-y-0');
                btn.setAttribute('aria-expanded', 'true');
            } else {
                // Close
                wind.classList.remove('opacity-100', 'translate-y-0');
                wind.classList.add('opacity-0', 'translate-y-4', 'pointer-events-none');
                btn.setAttribute('aria-expanded', 'false');
            }
        }

        function handleFaqClick(faqKey) {
            const questionText = chatbotFaqs[faqKey].question || event.currentTarget.innerText;
            processUserMessage(questionText, faqKey);
        }

        function handleChatSubmit(e) {
            e.preventDefault();
            const input = document.getElementById('chatbot-input');
            const text = input.value.trim();
            if (!text) return;

            input.value = '';
            processUserMessage(text);
        }

        function processUserMessage(text, forcedKey = null) {
            const messagesContainer = document.getElementById('chatbot-messages');
            const optionsContainer = document.getElementById('chatbot-faq-options');
            
            // Hide options
            optionsContainer.style.display = 'none';

            // Add user bubble
            const userMsgHtml = `
                <div class="flex items-end justify-end mt-2 animate-fade-in-up">
                    <div class="bg-indigo-600 text-white p-3 rounded-2xl rounded-tr-sm shadow-sm text-sm inline-block max-w-[85%]">
                        ${text}
                    </div>
                </div>
            `;
            messagesContainer.insertAdjacentHTML('beforeend', userMsgHtml);
            messagesContainer.scrollTop = messagesContainer.scrollHeight;

            // Intent Matching Logic if no forced key
            let matchKey = forcedKey;
            if (!matchKey) {
                const lowerText = text.toLowerCase();
                
                // Keyword mapping
                const keywords = {
                    'how-to-post': ['post', 'create', 'publish', 'add task', 'list task', 'new task', 'upload task'],
                    'how-to-apply': ['apply', 'make offer', 'bid', 'work', 'get hired', 'send offer', 'start working'],
                    'payment': ['pay', 'money', 'get paid', 'transaction', 'payout', 'bank', 'transfer', 'cash', 'earn'],
                    'fees': ['commission', 'fee', 'free', 'cost', 'charge', 'price', 'expensive', 'cheap'],
                    'profile': ['profile', 'avatar', 'bio', 'description', 'my info', 'skills'],
                    'notifications': ['notification', 'bell', 'alert', 'notice', 'unread'],
                    'security': ['password', 'security', 'login', 'access', 'privacy', 'private'],
                    'direct-quotes': ['direct', 'quote', 'specific', 'professional', 'hire now'],
                    'dashboard': ['dashboard', 'my tasks', 'history', 'tracking', 'manage jobs'],
                    'greeting': ['hi', 'hello', 'hey', 'greetings', 'sup', 'yo', 'morning', 'evening']
                };

                for (const [key, words] of Object.entries(keywords)) {
                    if (words.some(word => lowerText.includes(word))) {
                        matchKey = key;
                        break;
                    }
                }
            }

            // Show typing
            const typingId = 'bot-typing-' + Date.now();
            const typingHtml = `
                <div id="${typingId}" class="flex items-start gap-2 mt-4 animate-fade-in-up">
                    <div class="w-6 h-6 rounded-full bg-indigo-100 flex items-center justify-center shrink-0 mt-1">
                        <i data-feather="cpu" class="w-3 h-3 text-indigo-600"></i>
                    </div>
                    <div class="bg-white border border-gray-100 p-3 rounded-2xl rounded-tl-sm shadow-sm flex gap-1 items-center h-[42px] max-w-[85%]">
                        <div class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce" style="animation-delay: -0.3s"></div>
                        <div class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce" style="animation-delay: -0.15s"></div>
                        <div class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce"></div>
                    </div>
                </div>
            `;
            messagesContainer.insertAdjacentHTML('beforeend', typingHtml);
            if (window.feather) window.feather.replace();
            messagesContainer.scrollTop = messagesContainer.scrollHeight;

            setTimeout(() => {
                document.getElementById(typingId)?.remove();

                let answer;
                if (matchKey === 'greeting') {
                    answer = "Hello! 👋 I'm here to help you navigate MiniJobz. You can ask me about posting tasks, applying for jobs, or how payments work.";
                } else if (matchKey) {
                    answer = chatbotFaqs[matchKey];
                } else {
                    answer = "I'm not exactly sure about that yet. 🤖 My knowledge is currently limited to the basics of MiniJobz. You can try selecting one of the common questions below, or contact support!";
                }

                const botResponseHtml = `
                    <div class="flex items-start gap-2 mt-2 animate-fade-in-up">
                        <div class="w-6 h-6 rounded-full bg-indigo-100 flex items-center justify-center shrink-0 mt-1">
                            <i data-feather="cpu" class="w-3 h-3 text-indigo-600"></i>
                        </div>
                        <div class="bg-white border border-gray-100 p-3 rounded-2xl rounded-tl-sm shadow-sm text-sm text-gray-700 leading-relaxed inline-block max-w-[85%]">
                            ${answer}
                        </div>
                    </div>
                `;
                messagesContainer.insertAdjacentHTML('beforeend', botResponseHtml);
                
                setTimeout(() => {
                     optionsContainer.style.display = 'flex';
                     messagesContainer.appendChild(optionsContainer);
                     messagesContainer.scrollTop = messagesContainer.scrollHeight;
                }, 800);

                if (window.feather) window.feather.replace();
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }, 1000);
        }
    </script>
    
    <style>
        .animate-fade-in-up {
            animation: fadeInUp 0.3s ease-out forwards;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

</body>
</html>
