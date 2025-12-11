<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Osztálynapló</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

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
      #settings-menu.show { opacity: 1 !important; transform: translateY(0) !important; }

      .user-pic { width: 40px; border-radius: 50%; cursor: pointer; }
      .sub-menu-wrap { position: absolute; top: 60px; right: 0; width: 280px; max-height: 0; overflow: hidden; transition: max-height 0.3s ease; z-index: 50; }
      .sub-menu-wrap.open-menu { max-height: 500px; }
      .sub-menu { background: #fff; border-radius: 12px; padding: 15px; box-shadow: 0 6px 18px rgba(0,0,0,0.15); }
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
        width: 40px; /* Adjusted to match other nav items */
        height: 40px;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: rgb(255, 255, 255); /* Changed to white to match nav */
        border-radius: 50%;
        cursor: pointer;
        transition-duration: .3s;
        border: none;
      }
      .notification-btn:hover { background-color: #f3f4f6; } /* Light gray hover */
      .notification-btn .bell { width: 18px; }
      .notification-btn .bell path { fill: #4b5563; } /* Gray-600 to match other icons */
      
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
    </style>
    <!-- Feather icons (used in navbar) -->
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
</head>

<body>


    <!-- 🔹 NAVBAR (overrideable) -->
@hasSection('navbar')
@yield('navbar')
@else
<nav class="bg-white border-b border-gray-200 shadow-sm w-full z-50">
<div class="w-full flex justify-between items-center px-6 py-3">
<div class="flex items-center space-x-2 pl-4">
  <a href="{{ url('/index') }}" class="flex items-center">
    <img src="{{ asset('assets/img/logo.png') }}" 
         alt="Minijobz Logo" 
         class="h-8 w-auto object-contain"
         style="max-height: 32px;">
  </a>
</div>


 
<!-- CENTER: Nav Links -->
<div class="flex items-center space-x-5">
  <a href="{{ route('post-task') }}" onclick="return checkLogin(event)" class="px-4 py-2 rounded-lg bg-secondary-500 text-white hover:bg-secondary-600 font-semibold">
    Post a Task
  </a>
 
  <!-- Mega Menu -->
  <div id="categories-group" class="relative group">
    <a href="{{ url('category') }}" class="text-gray-600 hover:text-secondary-500 font-medium inline-flex items-center px-2 py-2">
      Categories
      <svg class="ml-2 w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
      </svg>
    </a>

  <!-- Mega Dropdown -->
  <div class="absolute left-0 mt-3 hidden group-hover:flex bg-white border border-gray-200 rounded-xl shadow-2xl w-[650px] z-50 overflow-hidden">

    <!-- Left Section -->
    <div class="w-1/3 bg-gray-50 border-r border-gray-200 p-5 flex flex-col justify-start">
      <h3 class="text-gray-800 font-semibold text-lg mb-2">Pick a type of task</h3>
      <p class="text-sm text-gray-500 leading-snug">Choose a service category to find professionals and tasks that match your needs.</p>
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
        <a href="/category" class="inline-block text-indigo-600 font-medium hover:underline">View All Categories →</a>
      </div>

    </div>
  </div>
</div>
 
  <a href="{{ url('tasks') }}" class="text-gray-600 hover:text-secondary-500">Browse Tasks</a>
  <a href="{{ url('howitworks') }}" class="text-gray-600 hover:text-secondary-500">How It Works</a>
</div>
 
<!-- RIGHT: Login / Signup / Settings -->
<div class="flex items-center space-x-3 pr-4">
  
  @guest
    <!-- Show Login and Sign Up for guests -->
    <a href="{{ route('login') }}" class="px-4 py-2 rounded-lg bg-primary-500 hover:bg-primary-600 text-white">
      Login
    </a>
    <a href="{{ route('register') }}" class="px-4 py-2 rounded-lg border border-primary-500 text-primary-500 hover:bg-primary-500/10">
      Sign Up
    </a>
  @endguest

  @auth
    <!-- Right: avatar dropdown -->
    <div class="relative ml-auto pr-4">
      @php
        $currentUser = auth()->user();
        $fullName = trim(($currentUser->first_name ?? '') . ' ' . ($currentUser->last_name ?? ''))
            ?: ($currentUser->name ?? $currentUser->email);
        $avatarSrc = $currentUser->avatar
            ? asset('storage/' . $currentUser->avatar)
            : asset('img/user.png');
      @endphp
      <button type="button" class="rounded-full overflow-hidden w-9 h-9 ring-1 ring-gray-300 hover:ring-gray-400" onclick="toggleMenu()">
        <img src="{{ $avatarSrc }}" alt="Profile" class="w-full h-full object-cover">
      </button>
      <div class="sub-menu-wrap" id="subMenu">
        <div class="sub-menu">
          <div class="user-info cursor-pointer" onclick="window.location.href='{{ route('profile') }}'">
            <h3>{{ $fullName }}</h3>
            <p class="text-gray-500 hover:text-indigo-600">Public Profile</p>
          </div>
          <hr>
          <a href="{{ route('my-tasks') }}" class="sub-menu-link flex items-center gap-2">
            <i data-feather="grid" class="w-4 h-4"></i> My Tasker Dashboard
          </a>
          <a href="{{ route('notifications') }}" class="sub-menu-link flex items-center gap-2">
            <i data-feather="bell" class="w-4 h-4"></i> Notifications
          </a>
          <a href="{{ route('profile') }}" class="sub-menu-link flex items-center gap-2">
            <i data-feather="user" class="w-4 h-4"></i> Profile
          </a>
          <a href="{{ route('profile') }}" class="sub-menu-link flex items-center gap-2">
            <i data-feather="settings" class="w-4 h-4"></i> Settings
          </a>
          <a href="{{ route('profile') }}" class="sub-menu-link flex items-center gap-2">
            <i data-feather="shield" class="w-4 h-4"></i> Security
          </a>
          <a href="{{ route('profile') }}" class="sub-menu-link flex items-center gap-2">
            <i data-feather="credit-card" class="w-4 h-4"></i> Billing
          </a>
          <hr>
          <a href="#" class="sub-menu-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
          <form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">
            @csrf
          </form>
        </div>
      </div>
    </div>

    <!-- Notification Bell & Dropdown -->
    <div class="relative">
        <button class="notification-btn" type="button" onclick="toggleNotifications()">
          <svg viewBox="0 0 448 512" class="bell">
            <path d="M224 0c-17.7 0-32 14.3-32 32V49.9C119.5 61.4 64 124.2 64 200v33.4c0 45.4-15.5 89.5-43.8 124.9L5.3 377c-5.8 7.2-6.9 17.1-2.9 25.4S14.8 416 24 416H424c9.2 0 17.6-5.3 21.6-13.6s2.9-18.2-2.9-25.4l-14.9-18.6C399.5 322.9 384 278.8 384 233.4V200c0-75.8-55.5-138.6-128-150.1V32c0-17.7-14.3-32-32-32zm0 96h8c57.4 0 104 46.6 104 104v33.4c0 47.9 13.9 94.6 39.7 134.6H72.3C98.1 328 112 281.3 112 233.4V200c0-57.4 46.6-104 104-104h8zm64 352H224 160c0 17 6.7 33.3 18.7 45.3s28.3 18.7 45.3 18.7s33.3-6.7 45.3-18.7s18.7-28.3 18.7-45.3z"></path>
          </svg>
          @php
              $unreadCount = auth()->user()->unreadNotifications()->count();
              $notifications = auth()->user()->notifications()->limit(5)->get();
          @endphp
          @if($unreadCount > 0)
            <span class="absolute top-0 right-0 flex items-center justify-center w-4 h-4 text-[9px] font-bold text-white bg-red-500 rounded-full border border-white transform translate-x-1 -translate-y-1">
                {{ $unreadCount }}
            </span>
          @endif
        </button>

        <!-- Dropdown Menu -->
        <div id="notification-dropdown" class="absolute right-0 mt-3 w-80 bg-white rounded-xl shadow-2xl border border-gray-100 overflow-hidden hidden transform origin-top-right transition-all duration-200 z-50">
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
                            <div>
                                <p class="text-sm text-gray-800 font-medium leading-snug">
                                    {{ $notification->data['message'] ?? 'New notification' }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
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
    <div id="settings-menu" class="hidden absolute right-0 mt-2 w-40 bg-white border border-gray-200 rounded-lg shadow-lg z-10 opacity-0 translate-y-2 transition-all duration-200 ease-out">
      <div class="flex flex-col">
        <div class="group relative">
          <div class="py-2 px-4 text-gray-700 font-semibold hover:bg-gray-100 cursor-pointer flex items-center gap-2">
            <i data-feather="chevron-left" class="w-4 h-4"></i>
            Theme
          </div>
          <div class="submenu absolute top-0 right-full w-48 bg-white border border-gray-200 rounded-lg shadow-lg opacity-0 scale-95 transform transition-all duration-200 ease-out pointer-events-none group-hover:opacity-100 group-hover:scale-100 group-hover:pointer-events-auto">
            <div class="px-4 py-2 hover:bg-gray-100 cursor-pointer" data-theme="light">Light</div>
            <div class="px-4 py-2 hover:bg-gray-100 cursor-pointer" data-theme="dark">Dark</div>
            <div class="px-4 py-2 hover:bg-gray-100 cursor-pointer" data-theme="system">System Default</div>
          </div>
        </div>
        <div class="group relative">
          <div class="py-2 px-4 text-gray-700 font-semibold hover:bg-gray-100 cursor-pointer flex items-center gap-2">
            <i data-feather="chevron-left" class="w-4 h-4"></i>
            Language
          </div>
          <div class="submenu absolute top-0 right-full w-48 bg-white border border-gray-200 rounded-lg shadow-lg opacity-0 scale-95 transform transition-all duration-200 ease-out pointer-events-none group-hover:opacity-100 group-hover:scale-100 group-hover:pointer-events-auto">
            <div class="px-4 py-2 hover:bg-gray-100 cursor-pointer">English</div>
            <div class="px-4 py-2 hover:bg-gray-100 cursor-pointer">Hungarian</div>
          </div>
        </div>
        <div class="group relative">
          <div class="py-2 px-4 text-gray-700 font-semibold hover:bg-gray-100 cursor-pointer flex items-center gap-2">
            <i data-feather="chevron-left" class="w-4 h-4"></i>
            Extras
          </div>
          <div class="submenu absolute top-0 right-full w-48 bg-white border border-gray-200 rounded-lg shadow-lg opacity-0 scale-95 transform transition-all duration-200 ease-out pointer-events-none group-hover:opacity-100 group-hover:scale-100 group-hover:pointer-events-auto">
            <div class="px-4 py-2 hover:bg-gray-100 cursor-pointer">Help / FAQ</div>
            <div class="px-4 py-2 hover:bg-gray-100 cursor-pointer">Contact / Support</div>
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
    <main>
        @yield('content')
    </main>

    <!-- 🔹 FOOTER -->
    <footer class="text-center py-3">
        <p>&copy; Király Gábor - Praszna Koppány - Nagy Gergely - 2025</p>
    </footer>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
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
        // Init theme on load
        try {
          var saved = localStorage.getItem('theme') || 'system';
          applyTheme(saved);
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
            // Theme option clicks
            var themeOptions = submenu.querySelectorAll('[data-theme]');
            themeOptions.forEach(function(opt){
              opt.addEventListener('click', function(){
                applyTheme(opt.getAttribute('data-theme'));
              });
            });
          });
        }
        
        // Avatar submenu toggle helper
        window.toggleMenu = function(){
          if (!subMenu) return;
          subMenu.classList.toggle('open-menu');
        };
        
        if (catGroup && catMenu) {
          var showCat = function(){
            catMenu.classList.remove('opacity-0','pointer-events-none','translate-y-2');
            catMenu.classList.add('opacity-100');
          };
          var hideCat = function(){
            catMenu.classList.remove('opacity-100');
            catMenu.classList.add('opacity-0');
            // delay pointer-events toggle to allow transition
            setTimeout(function(){ catMenu.classList.add('pointer-events-none','translate-y-2'); }, 150);
          };
          catGroup.addEventListener('mouseenter', showCat);
          catGroup.addEventListener('mouseleave', hideCat);
          // Also handle focus for accessibility
          catGroup.addEventListener('focusin', showCat);
          catGroup.addEventListener('focusout', function(e){
            if (!catGroup.contains(e.relatedTarget)) hideCat();
          });
        }
        if (window.feather && typeof window.feather.replace === 'function') {
          window.feather.replace();
        }
      })(); 

      // Notification Dropdown Logic
      function toggleNotifications() {
          const dropdown = document.getElementById('notification-dropdown');
          if (dropdown.classList.contains('hidden')) {
              dropdown.classList.remove('hidden');
              setTimeout(() => {
                  dropdown.classList.remove('opacity-0', 'scale-95');
                  dropdown.classList.add('opacity-100', 'scale-100');
              }, 10);
          } else {
              dropdown.classList.remove('opacity-100', 'scale-100');
              dropdown.classList.add('opacity-0', 'scale-95');
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
</body>
</html>
