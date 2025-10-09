<!-- resources/views/tasks.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Browse Tasks - Minijobz</title>
  <link rel="icon" type="image/x-icon" href="/static/favicon.ico">
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/feather-icons"></script>
  <!-- Leaflet (OpenStreetMap) -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
 
  <style>
    /* Smooth dropdown animation */
    #settings-menu.show {
      opacity: 1;
      transform: translateY(0);
      pointer-events: auto;
    }
    /* Scrollbar styling */
    .custom-scroll::-webkit-scrollbar {
      width: 8px;
    }
    .custom-scroll::-webkit-scrollbar-thumb {
      background-color: rgba(156, 163, 175, 0.5);
      border-radius: 9999px;
    }
    .custom-scroll::-webkit-scrollbar-thumb:hover {
      background-color: rgba(107, 114, 128, 0.7);
    }
    #map {
  width: 100%;
  height: 80vh;
  z-index: 0; /* ensure dropdowns stay above map */
}
  </style>
</head>
<body class="bg-gray-50 text-gray-900">
 
  <!-- Navigation -->
  <nav class="bg-white border-b border-gray-200 shadow-sm">
    <div class="max-w-6xl mx-auto flex justify-between items-center px-6 py-3">
      <!-- Left side -->
      <div class="flex items-center space-x-5">
        <div class="flex items-center space-x-2">
          <i data-feather="zap" class="text-secondary-500"></i>
          <span class="text-xl font-bold text-gray-900">Minijobz</span>
        </div>
        <a href="#" class="px-4 py-2 rounded-lg bg-secondary-500 text-white hover:bg-secondary-600 font-semibold">
          Post a Task
        </a>
        <a href="#" class="text-gray-600 hover:text-secondary-500">Categories</a>
        <a href="#" class="text-gray-600 hover:text-secondary-500">Browse Tasks</a>
        <a href="#" class="text-gray-600 hover:text-secondary-500">How It Works</a>
      </div>
 
      <!-- Right side -->
      <div class="flex items-center space-x-3 relative">
        <button class="px-4 py-2 rounded-lg bg-primary-500 hover:bg-primary-600 text-white">
          Login
        </button>
        <button class="px-4 py-2 rounded-lg border border-primary-500 text-primary-500 hover:bg-primary-500/10">
          Sign Up
        </button>
 
        <!-- Settings dropdown -->
        <div class="relative">
          <button id="settings-button" class="p-2 rounded-full hover:bg-gray-200 transition">
            <i data-feather="settings"></i>
          </button>
 
          <!-- Dropdown -->
          <div
            id="settings-menu"
            class="hidden absolute right-0 mt-2 w-40 bg-white border border-gray-200 rounded-lg shadow-lg z-10 opacity-0 translate-y-2 transition-all duration-200 ease-out"
          >
            <!-- Main items -->
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
 
  <!-- Filters Bar -->
  <section class="bg-white py-4 border-b">
    <div class="max-w-6xl mx-auto flex flex-wrap gap-3 items-center px-6">
      <input type="text" placeholder="Search tasks..." class="flex-1 px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500">
 
      <select class="px-4 py-3 rounded-xl border border-gray-300">
        <option>All Categories</option>
        <option>Cleaning</option>
        <option>Delivery</option>
        <option>Tech</option>
        <option>Design</option>
      </select>
 
      <input type="text" placeholder="Location" class="px-4 py-3 rounded-xl border border-gray-300">
 
      <select class="px-4 py-3 rounded-xl border border-gray-300">
        <option>In-person or Remote</option>
        <option>In-person</option>
        <option>Remote</option>
      </select>
 
      <select class="px-4 py-3 rounded-xl border border-gray-300">
        <option>Sort by: Recently Posted</option>
        <option>Closest to me</option>
        <option>Due soon</option>
      </select>
    </div>
  </section>
 
  <!-- Main Layout -->
  <section class="py-6">
    <div class="max-w-7xl mx-auto px-6 flex gap-6">
      <!-- Left: Tasks -->
      <div class="w-[350px] bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="h-[80vh] overflow-y-auto custom-scroll p-4 space-y-3">
          @foreach (range(1, 15) as $i)
            <div class="bg-gray-50 p-4 rounded-lg border hover:shadow-sm transition">
              <h3 class="text-lg font-semibold mb-1">Task #{{ $i }} - Sample Task Title</h3>
              <p class="text-gray-600 text-sm mb-2">Short description of the task — something simple and clear.</p>
              <div class="flex justify-between items-center text-xs text-gray-500">
                <span>📍 Budapest</span>
                <span>💰 €{{ rand(10, 100) }}</span>
              </div>
              <a href="/login" class="block mt-2 text-blue-600 font-medium text-sm hover:underline">Sign in to take</a>
            </div>
          @endforeach
        </div>
      </div>
 
      <!-- Right: Map -->
      <div id="map" class="flex-1 rounded-xl h-[80vh]"></div>
    </div>
  </section>
 
  <script>
    feather.replace();
    const map = L.map('map').setView([47.4979, 19.0402], 12); // Centered on Budapest
 
// Add the OpenStreetMap tiles
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  maxZoom: 19,
  attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);
 
// Add a marker example
L.marker([47.4979, 19.0402]).addTo(map)
  .bindPopup('Budapest Center')
  .openPopup();
    // Settings dropdown logic
    const btn = document.getElementById("settings-button");
    const menu = document.getElementById("settings-menu");
    btn.addEventListener("click", () => {
      menu.classList.toggle("hidden");
      setTimeout(() => menu.classList.toggle("show"), 10);
    });
  </script>
</body>
</html>