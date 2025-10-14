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
  <a href="#" class="px-4 py-2 rounded-lg bg-secondary-500 text-white hover:bg-secondary-600 font-semibold">
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
    <div id="categories-menu" class="absolute left-0 top-full mt-1 w-screen max-w-4xl rounded-lg border border-gray-200 bg-white shadow-xl z-50
        opacity-0 pointer-events-none transform translate-y-2
        transition-all duration-200 ease-out
        group-hover:opacity-100 group-hover:pointer-events-auto group-hover:translate-y-0">
      <div class="p-8 grid grid-cols-4 gap-8 text-sm">
        <div>
          <h4 class="font-bold text-gray-900 mb-3">Home Services</h4>
          <ul class="space-y-2">
            <li><a href="#" class="hover:text-secondary-500">Handyman</a></li>
            <li><a href="#" class="hover:text-secondary-500">Furniture Assembly</a></li>
            <li><a href="#" class="hover:text-secondary-500">Plumbing Help</a></li>
            <li><a href="#" class="hover:text-secondary-500">Electrical Repairs</a></li>
          </ul>
        </div>
        <div>
          <h4 class="font-bold text-gray-900 mb-3">Moving & Delivery</h4>
          <ul class="space-y-2">
            <li><a href="#" class="hover:text-secondary-500">Moving Help</a></li>
            <li><a href="#" class="hover:text-secondary-500">Heavy Lifting</a></li>
            <li><a href="#" class="hover:text-secondary-500">Grocery Delivery</a></li>
            <li><a href="#" class="hover:text-secondary-500">Courier Services</a></li>
          </ul>
        </div>
        <div>
          <h4 class="font-bold text-gray-900 mb-3">Cleaning & Maintenance</h4>
          <ul class="space-y-2">
            <li><a href="#" class="hover:text-secondary-500">Home Cleaning</a></li>
            <li><a href="#" class="hover:text-secondary-500">Deep Cleaning</a></li>
            <li><a href="#" class="hover:text-secondary-500">Garden Care</a></li>
            <li><a href="#" class="hover:text-secondary-500">Window Washing</a></li>
          </ul>
        </div>
        <div>
          <h4 class="font-bold text-gray-900 mb-3">Personal & Tutoring</h4>
          <ul class="space-y-2">
            <li><a href="#" class="hover:text-secondary-500">Pet Sitting</a></li>
            <li><a href="#" class="hover:text-secondary-500">Babysitting</a></li>
            <li><a href="#" class="hover:text-secondary-500">Math Tutor</a></li>
            <li><a href="#" class="hover:text-secondary-500">Language Lessons</a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
 
  <a href="{{ url('tasks') }}" class="text-gray-600 hover:text-secondary-500">Browse Tasks</a>
  <a href="{{ url('howitworks') }}" class="text-gray-600 hover:text-secondary-500">How It Works</a>
</div>
 
<!-- RIGHT: Login / Signup / Settings -->
<div class="flex items-center space-x-3 pr-4">
  <a href="{{ route('login') }}" class="px-4 py-2 rounded-lg bg-primary-500 hover:bg-primary-600 text-white">
    Login
  </a>
  <a href="{{ route('register') }}" class="px-4 py-2 rounded-lg border border-primary-500 text-primary-500 hover:bg-primary-500/10">
    Sign Up
  </a>
 
  <!-- Settings dropdown -->
  <div class="relative">
    <button id="settings-button" class="p-2 rounded-full hover:bg-gray-200 transition" type="button">
      <i data-feather="settings"></i>
    </button>
    <div id="settings-menu" class="hidden absolute right-0 mt-2 w-40 bg-white border border-gray-200 rounded-lg shadow-lg z-10 opacity-0 translate-y-2 transition-all duration-200 ease-out">
      <div class="flex flex-col">
        <div class="group relative">
          <div class="py-2 px-4 text-gray-700 font-semibold hover:bg-gray-100 cursor-pointer flex justify-between items-center">
            Theme
            <i data-feather="chevron-right" class="w-4 h-4"></i>
          </div>
          <div class="submenu absolute top-0 left-full w-48 bg-white border border-gray-200 rounded-lg shadow-lg opacity-0 scale-95 transform transition-all duration-200 ease-out pointer-events-none group-hover:opacity-100 group-hover:scale-100 group-hover:pointer-events-auto">
            <div class="px-4 py-2 hover:bg-gray-100 cursor-pointer">Light</div>
            <div class="px-4 py-2 hover:bg-gray-100 cursor-pointer">Dark</div>
            <div class="px-4 py-2 hover:bg-gray-100 cursor-pointer">System Default</div>
          </div>
        </div>
        <div class="group relative">
          <div class="py-2 px-4 text-gray-700 font-semibold hover:bg-gray-100 cursor-pointer flex justify-between items-center">
            Language
            <i data-feather="chevron-right" class="w-4 h-4"></i>
          </div>
          <div class="submenu absolute top-0 left-full w-48 bg-white border border-gray-200 rounded-lg shadow-lg opacity-0 scale-95 transform transition-all duration-200 ease-out pointer-events-none group-hover:opacity-100 group-hover:scale-100 group-hover:pointer-events-auto">
            <div class="px-4 py-2 hover:bg-gray-100 cursor-pointer">English</div>
            <div class="px-4 py-2 hover:bg-gray-100 cursor-pointer">Hungarian</div>
          </div>
        </div>
        <div class="group relative">
          <div class="py-2 px-4 text-gray-700 font-semibold hover:bg-gray-100 cursor-pointer flex justify-between items-center">
            Extras
            <i data-feather="chevron-right" class="w-4 h-4"></i>
          </div>
          <div class="submenu absolute top-0 left-full w-48 bg-white border border-gray-200 rounded-lg shadow-lg opacity-0 scale-95 transform transition-all duration-200 ease-out pointer-events-none group-hover:opacity-100 group-hover:scale-100 group-hover:pointer-events-auto">
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
        var suppressUntil = 0;
        // Categories mega menu behavior (ensures visibility even if CSS hover fails)
        var catGroup = document.getElementById('categories-group');
        var catMenu = document.getElementById('categories-menu');
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
          });
        }
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
    </script>
</body>
</html>
