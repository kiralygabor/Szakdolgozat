@extends('layout')

@section('content')
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
    /* Dual range slider thumbs (WebKit) */
    #price-menu input[type=range]::-webkit-slider-thumb {
      -webkit-appearance: none;
      appearance: none;
      width: 16px;
      height: 16px;
      background: #2563eb; /* blue-600 */
      border-radius: 9999px;
      border: 2px solid white;
      box-shadow: 0 0 0 1px rgba(0,0,0,0.1);
      position: relative;
      z-index: 10;
    }
    #price-menu input[type=range]::-webkit-slider-runnable-track {
      height: 0; /* hide default track in WebKit */
    }
    /* Firefox */
    #price-menu input[type=range]::-moz-range-thumb {
      width: 16px;
      height: 16px;
      background: #2563eb;
      border-radius: 9999px;
      border: 2px solid white;
      box-shadow: 0 0 0 1px rgba(0,0,0,0.1);
      position: relative;
      z-index: 10;
    }
    #price-menu input[type=range]::-moz-range-track {
      background: transparent;
      height: 0;
    }
  </style>
</head>
<body class="bg-gray-50 text-gray-900">
 
 
 
 <!-- Filters Bar -->
<section class="bg-white py-4 border-b">
  <form method="GET" action="{{ route('tasks') }}"
        class="max-w-7xl mx-auto flex flex-wrap items-center gap-3 px-6">
 
    <!-- Search -->
    <input
      name="q"
      value="{{ $filters['q'] ?? '' }}"
      type="text"
      placeholder="Search by city..."
      class="w-48 px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500"
    >
 
    <!-- Category -->
    <select name="category" class="px-4 py-3 rounded-xl border border-gray-300">
      <option value="">All Categories</option>
      @foreach(($categories ?? []) as $category)
        <option value="{{ $category->id }}" @selected(($filters['category'] ?? '') == $category->id)>
          {{ $category->name }}
        </option>
      @endforeach
    </select>
 
    <!-- Distance dropdown -->
    <div class="relative">
      <button type="button" id="distance-btn"
              class="px-4 py-3 rounded-xl border border-gray-300 flex items-center gap-2 bg-white hover:bg-gray-50">
        <span>Distance: <span id="distance-value-label">{{ $filters['distance'] ?? 20 }} km</span></span>
        <i data-feather="chevron-down" class="w-4 h-4"></i>
      </button>
      <div id="distance-menu"
           class="absolute mt-2 right-0 bg-white border rounded-xl shadow-lg p-4 w-56 hidden z-50">
        <label class="block text-sm text-gray-600 mb-2">Select distance (km):</label>
        <input
          id="distance"
          name="distance"
          type="range"
          min="1"
          max="100"
          step="1"
          value="{{ $filters['distance'] ?? 20 }}"
          class="w-full accent-blue-500 cursor-pointer"
          oninput="document.getElementById('distance-value').textContent = this.value + ' km';
                   document.getElementById('distance-value-label').textContent = this.value + ' km';"
        >
        <p id="distance-value" class="text-center text-sm mt-2 text-gray-700">{{ $filters['distance'] ?? 20 }} km</p>
      </div>
    </div>
 
    <!-- Price dropdown -->
    <div class="relative">
      <button type="button" id="price-btn"
              class="px-4 py-3 rounded-xl border border-gray-300 flex items-center gap-2 bg-white hover:bg-gray-50">
        <span>Price Range</span>
        <i data-feather="chevron-down" class="w-4 h-4"></i>
      </button>
      <div id="price-menu"
           class="absolute mt-2 right-0 bg-white border rounded-xl shadow-lg p-4 w-64 hidden z-50">
        <label class="block text-sm text-gray-600 mb-3">Select price range (€1,000 – €20,000)</label>
        <div class="relative pt-4 pb-2">
          <!-- Track -->
          <div class="absolute left-0 right-0 top-1/2 -translate-y-1/2 h-1 bg-gray-200 rounded"></div>
          <div id="price-track" class="absolute top-1/2 -translate-y-1/2 h-1 bg-blue-500 rounded" style="left:0%; right:50%"></div>
          <!-- Dual inputs (overlapped) -->
          <input id="price-min" name="min_price" type="range"
                 min="1000" max="20000" step="50"
                 value="{{ max(1000, (int)($filters['min_price'] ?? 1000)) }}"
                 class="w-full appearance-none bg-transparent pointer-events-auto cursor-pointer">
          <input id="price-max" name="max_price" type="range"
                 min="1000" max="20000" step="50"
                 value="{{ min(20000, (int)($filters['max_price'] ?? 20000)) }}"
                 class="w-full appearance-none bg-transparent pointer-events-auto cursor-pointer absolute inset-0">
        </div>
        <div class="mt-2 flex items-center justify-between text-sm text-gray-700">
          <span id="price-min-label">€{{ max(1000, (int)($filters['min_price'] ?? 1000)) }}</span>
          <span id="price-max-label">€{{ min(20000, (int)($filters['max_price'] ?? 20000)) }}</span>
        </div>
      </div>
    </div>
 
    <!-- Sort -->
    <select name="sort" class="px-4 py-3 rounded-xl border border-gray-300">
      <option value="recent" @selected(($filters['sort'] ?? 'recent')==='recent')>Sort by: Recently Posted</option>
      <option value="closest" @selected(($filters['sort'] ?? '')==='closest')>Closest to me</option>
      <option value="due" @selected(($filters['sort'] ?? '')==='due')>Due soon</option>
      <option value="lowest_price" @selected(($filters['sort'] ?? '')==='lowest_price')>Lowest Price</option>
      <option value="highest_price" @selected(($filters['sort'] ?? '')==='highest_price')>Highest Price</option>
    </select>
 
    <!-- Apply -->
    <button type="submit"
            class="px-4 py-3 rounded-xl bg-blue-600 text-white hover:bg-blue-700">
      Apply
    </button>
  </form>
</section>
 
 
  <!-- Main Layout -->
  <section class="py-6">
    <div class="max-w-7xl mx-auto px-6 flex gap-6">
      <!-- Left: Tasks -->
      <div class="w-[380px] bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="h-[80vh] overflow-y-auto custom-scroll p-4 space-y-3">
          @forelse (($tasks ?? []) as $task)
            <div class="bg-gray-50 p-4 rounded-lg border hover:shadow-sm transition">
              <h3 class="text-base font-semibold mb-1">{{ $task->title }}</h3>
              <p class="text-gray-600 text-sm mb-2 line-clamp-2">{{ $task->description }}</p>
              <div class="flex justify-between items-center text-xs text-gray-500">
                <span>📍 {{ optional(optional($task->employer)->city)->name ?? 'Unknown' }}</span>
                <span>💰 €{{ number_format($task->price, 0) }}</span>
              </div>
              <div class="mt-2 flex items-center justify-between text-xs">
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-100">{{ optional($task->category)->name ?? 'General' }}</span>
                <span class="text-gray-400">{{ $task->created_at?->diffForHumans() }}</span>
              </div>
              <a href="/login" class="block mt-2 text-blue-600 font-medium text-sm hover:underline">Sign in to take</a>
            </div>
          @empty
            <div class="text-center text-gray-500 py-12">No tasks found. Try adjusting filters.</div>
          @endforelse
        </div>
        @if(isset($tasks))
          <div class="border-t px-4 py-3">{{ $tasks->links() }}</div>
        @endif
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
    const toggleDropdown = (btnId, menuId) => {
    const btn = document.getElementById(btnId);
    const menu = document.getElementById(menuId);
    btn.addEventListener('click', () => {
      menu.classList.toggle('hidden');
    });
    document.addEventListener('click', (e) => {
      if (!btn.contains(e.target) && !menu.contains(e.target)) {
        menu.classList.add('hidden');
      }
    });
  };
 
  toggleDropdown('distance-btn', 'distance-menu');
  toggleDropdown('price-btn', 'price-menu');

  // Dual slider logic for price range
  (function(){
    const minEl = document.getElementById('price-min');
    const maxEl = document.getElementById('price-max');
    const minLabel = document.getElementById('price-min-label');
    const maxLabel = document.getElementById('price-max-label');
    const track = document.getElementById('price-track');
    const btn = document.getElementById('price-btn');
    const MIN = 1000;
    const MAX = 20000;
    const STEP = 50;
    const clamp = (v, lo, hi) => Math.min(Math.max(v, lo), hi);
    const toPercent = (v) => ((v - MIN) / (MAX - MIN)) * 100;

    function sync(e){
      let minVal = parseInt(minEl.value, 10);
      let maxVal = parseInt(maxEl.value, 10);
      // Prevent overlap: enforce at least STEP between handles
      if (minVal > maxVal - STEP){
        if (e && e.target === minEl) {
          minVal = maxVal - STEP;
          minEl.value = clamp(minVal, MIN, MAX - STEP);
        } else {
          maxVal = minVal + STEP;
          maxEl.value = clamp(maxVal, MIN + STEP, MAX);
        }
      }
      // Update labels
      minLabel.textContent = '€' + minEl.value;
      maxLabel.textContent = '€' + maxEl.value;
      // Update track fill
      const left = toPercent(parseInt(minEl.value,10));
      const right = 100 - toPercent(parseInt(maxEl.value,10));
      track.style.left = left + '%';
      track.style.right = right + '%';
      // Update button text
      const span = btn.querySelector('span');
      if (span) span.textContent = `€${minEl.value} - €${maxEl.value}`;
    }

    if (minEl && maxEl) {
      minEl.addEventListener('input', sync);
      maxEl.addEventListener('input', sync);
      // Initialize
      sync();
    }
  })();
  </script>
</body>
</html>
@endsection
