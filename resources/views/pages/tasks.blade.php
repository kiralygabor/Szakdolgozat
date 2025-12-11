@extends('layout')

@section('content')
  <style>
    /* Custom Scrollbar */
    .custom-scroll::-webkit-scrollbar {
      width: 6px;
    }
    .custom-scroll::-webkit-scrollbar-track {
      background: transparent;
    }
    .custom-scroll::-webkit-scrollbar-thumb {
      background-color: #cbd5e1;
      border-radius: 9999px;
    }
    .custom-scroll::-webkit-scrollbar-thumb:hover {
      background-color: #94a3b8;
    }

    /* Modal Overlay */
    .modal-overlay {
      background: rgba(0, 0, 0, 0.6);
      backdrop-filter: blur(2px);
    }

    /* Map Styling */
    #map {
      width: 100%;
      height: 100%;
      border-radius: 0.75rem;
    }

    /* Range Slider Styling */
    #price-menu input[type=range]::-webkit-slider-thumb {
      -webkit-appearance: none;
      appearance: none;
      width: 16px;
      height: 16px;
      background: #2563eb;
      border-radius: 50%;
      border: 2px solid white;
      box-shadow: 0 1px 3px rgba(0,0,0,0.3);
      position: relative;
      z-index: 10;
      cursor: pointer;
      margin-top: -6px;
    }
    #price-menu input[type=range]::-webkit-slider-runnable-track {
      height: 4px;
      background: transparent;
    }

    /* --- Modal Specific Styles (Airtasker Look) --- */
    .step-icon-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #F8FAFC; /* Very light slate */
        display: flex;
        align-items: center;
        justify-content: center;
        color: #64748B; /* Slate 500 */
        flex-shrink: 0;
    }
    .step-add-btn {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background-color: #2563EB; /* Blue 600 */
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background-color 0.2s;
        flex-shrink: 0;
    }
    .step-add-btn:hover {
        background-color: #1d4ed8;
    }
  </style>

  <!-- MapLibre GL -->
  <link href="https://unpkg.com/maplibre-gl@3.6.1/dist/maplibre-gl.css" rel="stylesheet" />
  <script src="https://unpkg.com/maplibre-gl@3.6.1/dist/maplibre-gl.js"></script>
 
  <!-- FILTERS NAVBAR -->
  <section class="bg-white border-b border-gray-200 shadow-sm z-20 relative">
    <form method="GET" action="{{ route('tasks') }}" id="filters-form"
          class="max-w-7xl mx-auto flex items-center gap-4 px-6 py-3 h-16">
 
      <!-- Search Bar -->
      <div class="relative flex-grow max-w-md group">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
          <i data-feather="search" class="h-4 w-4 text-gray-400 group-focus-within:text-blue-500"></i>
        </div>
        <input
          id="search-q"
          name="q"
          value="{{ $filters['q'] ?? '' }}"
          type="text"
          placeholder="Search tasks, city or category..."
          class="w-full pl-10 pr-4 py-2 rounded-full bg-gray-100 border-transparent focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 text-sm transition-all outline-none"
          autocomplete="off"
        >
        <input type="hidden" name="city_search" id="city-search-hidden" value="{{ $filters['city_search'] ?? '' }}">
      </div>

      <!-- Separator -->
      <div class="h-8 w-px bg-gray-300 hidden md:block mx-4"></div>
 
      <!-- Filters -->
      <div class="flex items-center gap-3 h-full">
        
        <!-- Category -->
        <div class="relative">
            <select name="category" id="category-filter" 
                    class="appearance-none pl-3 pr-8 py-2 rounded-lg border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:ring-2 focus:ring-blue-100 outline-none cursor-pointer transition-all">
              <option value="">All Categories</option>
              @foreach(($categories ?? []) as $category)
                <option value="{{ $category->id }}" @selected(($filters['category'] ?? '') == $category->id)>
                  {{ $category->name }}
                </option>
              @endforeach
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
               <i data-feather="chevron-down" class="h-4 w-4"></i>
            </div>
        </div>
   
        <!-- Work Type -->
        <div class="relative">
          <button type="button" id="type-btn"
                  class="min-w-[120px] justify-between px-3 py-2 rounded-lg border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:border-blue-400 hover:text-blue-600 flex items-center gap-2 transition-all">
            <i data-feather="briefcase" class="w-3.5 h-3.5 text-gray-500"></i>
            <span id="type-text">Type</span>
            <i data-feather="chevron-down" class="w-3.5 h-3.5 ml-1 text-gray-400"></i>
          </button>
          
          <div id="type-menu" class="absolute mt-3 right-0 bg-white border border-gray-100 rounded-xl shadow-xl p-4 w-64 hidden z-50">
             <div class="mb-3">
               <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Location</label>
               <input id="type-city-search" type="text" placeholder="Search city..."
                   class="w-full px-3 py-2 rounded-lg bg-gray-50 border border-gray-200 text-sm focus:border-blue-500 outline-none">
               <div id="type-city-dropdown" class="mt-1 max-h-40 overflow-y-auto hidden border rounded-lg shadow-inner bg-white"></div>
             </div>
             <div class="mb-3">
                <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Mode</label>
                <div class="space-y-1">
                  <label class="flex items-center p-1.5 rounded hover:bg-gray-50 cursor-pointer">
                    <input type="radio" name="type" value="all" class="text-blue-600" @checked(($filters['type'] ?? 'all') === 'all')>
                    <span class="text-sm ml-2 text-gray-700">Any</span>
                  </label>
                  <label class="flex items-center p-1.5 rounded hover:bg-gray-50 cursor-pointer">
                    <input type="radio" name="type" value="in_person" class="text-blue-600" @checked(($filters['type'] ?? '') === 'in_person')>
                    <span class="text-sm ml-2 text-gray-700">In-Person</span>
                  </label>
                  <label class="flex items-center p-1.5 rounded hover:bg-gray-50 cursor-pointer">
                    <input type="radio" name="type" value="remote" class="text-blue-600" @checked(($filters['type'] ?? '') === 'remote')>
                    <span class="text-sm ml-2 text-gray-700">Remote</span>
                  </label>
                </div>
             </div>
             <div class="flex justify-end gap-2 pt-2 border-t">
               <button type="button" id="type-apply" class="w-full py-1.5 text-xs font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">Apply Filter</button>
             </div>
          </div>
        </div>
   
        <!-- Price -->
        <div class="relative">
          <button type="button" id="price-btn"
                  class="min-w-[120px] justify-between px-3 py-2 rounded-lg border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:border-blue-400 hover:text-blue-600 flex items-center gap-2 transition-all">
            <i data-feather="dollar-sign" class="w-3.5 h-3.5 text-gray-500"></i>
            <span id="price-text">Price</span>
            <i data-feather="chevron-down" class="w-3.5 h-3.5 ml-1 text-gray-400"></i>
          </button>
          
          <div id="price-menu" class="absolute mt-3 right-0 bg-white border border-gray-100 rounded-xl shadow-xl p-5 w-72 hidden z-50">
            <span class="text-xs font-bold text-gray-500 uppercase mb-4 block">Budget Range</span>
            <div class="relative h-8 mb-4">
               <div class="absolute top-1/2 w-full h-1 bg-gray-200 rounded-full -translate-y-1/2"></div>
               <div id="price-track" class="absolute top-1/2 h-1 bg-blue-500 rounded-full -translate-y-1/2 z-0"></div>
               <input id="price-min" name="min_price" type="range" min="1000" max="20000" step="50"
                      value="{{ max(1000, (int)($filters['min_price'] ?? 1000)) }}"
                      class="absolute w-full h-full opacity-0 cursor-pointer z-10">
               <input id="price-max" name="max_price" type="range" min="1000" max="20000" step="50"
                      value="{{ min(20000, (int)($filters['max_price'] ?? 20000)) }}"
                      class="absolute w-full h-full opacity-0 cursor-pointer z-20">
            </div>
            <div class="flex justify-between items-center mb-4 text-sm text-gray-700 font-medium">
                <span id="price-min-label">€1000</span>
                <span id="price-max-label">€20000</span>
            </div>
            <button type="button" id="price-apply" class="w-full py-1.5 text-xs font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">Apply Price</button>
          </div>
        </div>

        <!-- Sort -->
        <div class="hidden lg:block h-8 w-px bg-gray-300 mx-4"></div>
        <select name="sort" id="sort-filter" class="bg-transparent text-sm font-medium text-gray-600 hover:text-gray-900 cursor-pointer outline-none">
            <option value="recent" @selected(($filters['sort'] ?? 'recent')==='recent')>Sort: Recent</option>
            <option value="closest" @selected(($filters['sort'] ?? '')==='closest')>Sort: Closest</option>
            <option value="due" @selected(($filters['sort'] ?? '')==='due')>Sort: Due Soon</option>
            <option value="lowest_price" @selected(($filters['sort'] ?? '')==='lowest_price')>Price: Low to High</option>
            <option value="highest_price" @selected(($filters['sort'] ?? '')==='highest_price')>Price: High to Low</option>
        </select>
      </div>
    </form>
  </section>
 
  <!-- Main Content -->
  <section class="bg-gray-50 pt-8 h-[700px]">
   <div class="flex max-w-7xl mx-auto px-6 gap-6 h-full pb-6">
     
      <!-- Left: Tasks Pane -->
      <div id="tasks-pane" class="flex flex-col w-[360px] shrink-0 h-full">
        <div class="flex-1 overflow-y-auto custom-scroll pr-2 space-y-3">
          @forelse (($tasks ?? []) as $task)
            <div class="group bg-white p-4 rounded-xl border border-gray-200 hover:border-blue-400 hover:shadow-md transition-all duration-200 relative">
             
              <div class="flex justify-between items-start mb-1.5">
                <h3 class="text-sm font-bold text-gray-800 leading-tight group-hover:text-blue-600">
                    {{ $task->title }}
                </h3>
                <span class="text-green-600 text-sm font-bold whitespace-nowrap ml-2">
                   €{{ number_format($task->price, 0) }}
                </span>
              </div>
 
              <p class="text-gray-500 text-xs mb-3 line-clamp-2 leading-relaxed">
                  {{ $task->description }}
              </p>
 
              <div class="flex flex-wrap gap-1.5 mb-3">
                 <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-gray-100 text-gray-600 text-[10px] font-semibold uppercase tracking-wide">
                    {{ optional($task->category)->name ?? 'General' }}
                 </span>
                 <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-gray-100 text-gray-600 text-[10px] font-medium">
                    📍 {{ optional(optional($task->employer)->city)->name ?? 'Remote' }}
                 </span>
              </div>
 
              <div class="pt-2 border-t border-gray-50 flex justify-between items-center">
                 <span class="text-[10px] text-gray-400">
                    {{ $task->created_at?->diffForHumans(null, true, true) }} ago
                 </span>
                 @guest
                     <a href="{{ route('login', ['returnUrl' => route('tasks.show', $task->id)]) }}" class="text-xs font-semibold text-blue-600 hover:underline">
                        Sign in to make an offer
                     </a>
                 @else
                     <!-- Logic: if they have missing steps, show button that opens modal. Otherwise regular link. -->
                     <button type="button" class="js-open-offer-requirements text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 px-3 py-1.5 rounded-full transition-colors">
                        Make an offer
                     </button>
                 @endguest
              </div>
            </div>
          @empty
            <div class="text-center py-10 text-gray-400 text-sm">
               No tasks found.
            </div>
          @endforelse
         
          @if(isset($tasks) && $tasks->hasPages())
             <div class="py-2 text-xs">
                 {{ $tasks->links() }}
             </div>
          @endif
        </div>
      </div>
 
      <!-- Right: Map -->
      <div class="flex-1 bg-gray-200 rounded-xl overflow-hidden shadow-inner border border-gray-300 relative">
        <div id="map"></div>
        <div class="pointer-events-none absolute inset-x-0 top-0 h-6 bg-gradient-to-b from-black/5 to-transparent z-10"></div>
      </div>
 
    </div>
  </section>

  @php
    // --- FORCE SPECIFIC MODAL ITEMS ---
    // This array contains the exact items requested.
    $missingSteps = [
        'Upload a profile picture',
        'Add your date of birth',
        'Verify your mobile',
        'Link your bank account',
        'Add your billing address'
    ];
  @endphp

  <!-- MODAL: BEFORE YOU MAKE AN OFFER -->
  <!-- We use 'hidden' class by default. JS removes it to show. -->
  <div id="profile-steps-modal" class="fixed inset-0 modal-overlay flex items-center justify-center z-[60] hidden transition-opacity duration-300">
      <div class="bg-white w-full max-w-[480px] rounded-2xl shadow-2xl relative mx-4 animate-fade-in-up overflow-hidden">
          
          <!-- Close X Button -->
          <button type="button" id="profile-steps-close" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 z-10 p-1">
              <i data-feather="x" class="w-6 h-6"></i>
          </button>

          <!-- Modal Content -->
          <div class="pt-8 pb-6 px-8">
              
            <!-- Illustration: Trust & Verification -->
              <div class="flex justify-center mb-6">
                  <div class="relative w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center">
                      <!-- Blue Shield -->
                      <svg width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 22C12 22 20 18 20 12V5L12 2L4 5V12C4 18 12 22 12 22Z" fill="#2563EB" stroke="#2563EB" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M9 12L11 14L15 10" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                      </svg>
                      
                      <!-- Decorative Element (Small lock or star top right) -->
                      <div class="absolute -top-1 -right-1 bg-white p-1 rounded-full shadow-sm">
                          <div class="w-6 h-6 bg-yellow-400 rounded-full flex items-center justify-center text-white">
                              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                  <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                              </svg>
                          </div>
                      </div>
                  </div>
              </div>
              <!-- Header Text -->
              <div class="text-center mb-6">
                  <h2 class="text-2xl font-bold text-gray-900 mb-2">Before you make an offer</h2>
                  <p class="text-gray-500 text-[15px] leading-relaxed">
                      Help us keep Minijobz safe and fun, and fill in a few details.
                  </p>
              </div>

              <!-- Steps List -->
              <div class="space-y-2 mb-8">
                  @foreach($missingSteps as $step)
                      @php
                          // Map text to Feather Icon names
                          $iconName = 'check-circle'; // Fallback
                          $lower = strtolower($step);
                          
                          if(str_contains($lower, 'picture') || str_contains($lower, 'photo')) {
                              $iconName = 'user';
                          }
                          elseif(str_contains($lower, 'birth') || str_contains($lower, 'date')) {
                              $iconName = 'calendar';
                          }
                          elseif(str_contains($lower, 'mobile') || str_contains($lower, 'phone')) {
                              $iconName = 'smartphone';
                          }
                          elseif(str_contains($lower, 'bank') || str_contains($lower, 'payment')) {
                              $iconName = 'credit-card';
                          }
                          elseif(str_contains($lower, 'address') || str_contains($lower, 'location')) {
                              $iconName = 'map-pin';
                          }
                      @endphp

                      <!-- Single List Item -->
                      <a href="{{ route('profile') }}" class="flex items-center justify-between py-2 group cursor-pointer hover:bg-gray-50 rounded-xl px-2 transition-colors no-underline">
                          <div class="flex items-center gap-4">
                              <!-- Left Icon Circle -->
                              <div class="step-icon-circle">
                                  <i data-feather="{{ $iconName }}" class="w-5 h-5"></i>
                              </div>
                              <!-- Text -->
                              <span class="text-gray-700 font-medium text-[15px]">{{ $step }}</span>
                          </div>
                          
                          <!-- Right Plus Button -->
                          <div class="step-add-btn">
                              <i data-feather="plus" class="w-4 h-4"></i>
                          </div>
                      </a>
                  @endforeach
              </div>

              <!-- Footer Button -->
              <div class="mt-2">
                <a href="{{ route('profile') }}" class="block w-full py-3 bg-blue-50 hover:bg-blue-100 text-blue-600 font-bold text-center rounded-full transition-colors text-sm">
                    Continue
                </a>
              </div>
          </div>
      </div>
  </div>

  <script>
    // 1. Initialize Feather Icons
    function refreshIcons() {
        if (window.feather && typeof window.feather.replace === 'function') {
            window.feather.replace();
        }
    }
    refreshIcons();
    
    // 2. Map Setup
    const map = new maplibregl.Map({
      container: 'map',
      style: {
        version: 8,
        sources: {
          'osm-tiles': {
            type: 'raster',
            tiles: ['https://a.tile.openstreetmap.org/{z}/{x}/{y}.png'],
            tileSize: 256,
            attribution: '© OpenStreetMap'
          }
        },
        layers: [{
          id: 'osm-tiles',
          type: 'raster',
          source: 'osm-tiles',
          minzoom: 0,
          maxzoom: 19
        }]
      },
      center: [19.0402, 47.4979], // Budapest
      zoom: 12
    });
    map.addControl(new maplibregl.NavigationControl({ showCompass: false }), 'bottom-right');

    // 3. Map Data & Markers
    @php
      $source = ($tasks instanceof \Illuminate\Pagination\AbstractPaginator) ? $tasks->items() : ($tasks ?? []);
      $taskPoints = collect($source)->map(function($t){
        return [ 
            'id' => $t->id ?? null, 
            'title' => $t->title ?? '', 
            'price' => (int)($t->price ?? 0), 
            'city' => $t->employer->city->name ?? null 
        ];
      })->filter(fn($r) => $r['id'] && $r['city'])->values();
    @endphp
    const tasksData = @json($taskPoints);

    const cityCache = {
      get(name){ try { return JSON.parse(localStorage.getItem('geocode:'+name)); } catch { return null; } },
      set(name, coords){ try { localStorage.setItem('geocode:'+name, JSON.stringify(coords)); } catch {} }
    };

    async function geocodeCity(name){
      if (!name) return null;
      const cached = cityCache.get(name);
      if (cached) return cached;
      try {
        const res = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(name)}&limit=1`);
        const data = await res.json();
        if (data && data[0]) {
          const coords = { lat: parseFloat(data[0].lat), lng: parseFloat(data[0].lon) };
          cityCache.set(name, coords);
          return coords;
        }
      } catch (e) {}
      return null;
    }

    (async function plotTasks(){
      const uniqueCities = [...new Set(tasksData.map(t => t.city))];
      const cityToCoords = {};
      for (const city of uniqueCities) {
        const coords = await geocodeCity(city);
        if (coords) cityToCoords[city] = coords;
        await new Promise(r => setTimeout(r, 400));
      }

      const bounds = new maplibregl.LngLatBounds();
      tasksData.forEach(t => {
        const coords = cityToCoords[t.city];
        if (!coords) return;

        const el = document.createElement('div');
        el.innerHTML = `<div class="w-3 h-3 bg-blue-600 rounded-full border-2 border-white shadow-sm"></div>`;
        const popupHTML = `
            <div class="px-2 py-1 text-center">
                <div class="font-bold text-xs text-gray-800">${t.title}</div>
                <div class="text-xs text-blue-600 font-bold">€${t.price}</div>
            </div>
        `;
        new maplibregl.Marker({ element: el })
          .setLngLat([coords.lng, coords.lat])
          .setPopup(new maplibregl.Popup({ offset: 10, closeButton: false }).setHTML(popupHTML))
          .addTo(map);
        bounds.extend([coords.lng, coords.lat]);
      });
      if (!bounds.isEmpty()) map.fitBounds(bounds, { padding: 50, maxZoom: 14 });
    })();

    // 4. Dropdowns (Price/Type)
    function setupDropdown(btnId, menuId) {
        const btn = document.getElementById(btnId);
        const menu = document.getElementById(menuId);
        if(!btn || !menu) return;
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            const isHidden = menu.classList.contains('hidden');
            document.querySelectorAll('[id$="-menu"]').forEach(el => el.classList.add('hidden'));
            if(isHidden) menu.classList.remove('hidden');
        });
        menu.addEventListener('click', (e) => e.stopPropagation());
        document.addEventListener('click', () => menu.classList.add('hidden'));
    }
    setupDropdown('price-btn', 'price-menu');
    setupDropdown('type-btn', 'type-menu');

    ['category-filter', 'sort-filter'].forEach(id => {
        document.getElementById(id)?.addEventListener('change', () => document.getElementById('filters-form').submit());
    });

    // 5. Price Slider
    (function initPriceSlider() {
        const minEl = document.getElementById('price-min');
        const maxEl = document.getElementById('price-max');
        const track = document.getElementById('price-track');
        const labelMin = document.getElementById('price-min-label');
        const labelMax = document.getElementById('price-max-label');
        const priceText = document.getElementById('price-text');
        if (!minEl || !maxEl) return;
        function update() {
            let min = parseInt(minEl.value), max = parseInt(maxEl.value);
            if (min > max - 50) {
                if (this === minEl) minEl.value = max - 50;
                else maxEl.value = min + 50;
            }
            const pMin = ((minEl.value - 1000) / 19000) * 100;
            const pMax = ((maxEl.value - 1000) / 19000) * 100;
            track.style.left = pMin + "%";
            track.style.width = (pMax - pMin) + "%";
            labelMin.textContent = '€' + minEl.value;
            labelMax.textContent = '€' + maxEl.value;
            if(min !== 1000 || max !== 20000) {
                priceText.textContent = `€${minEl.value} - €${maxEl.value}`;
                priceText.classList.add('text-blue-600', 'font-bold');
            }
        }
        minEl.addEventListener('input', update);
        maxEl.addEventListener('input', update);
        update();
        document.getElementById('price-apply').addEventListener('click', () => document.getElementById('filters-form').submit());
    })();

    // 6. City Search
    const typeCitySearch = document.getElementById('type-city-search');
    const typeCityDropdown = document.getElementById('type-city-dropdown');
    const hiddenCity = document.getElementById('city-search-hidden');
    let searchTimeout;

    if(typeCitySearch) {
        typeCitySearch.addEventListener('input', (e) => {
            clearTimeout(searchTimeout);
            const q = e.target.value;
            if(q.length < 2) { typeCityDropdown.classList.add('hidden'); return; }
            
            searchTimeout = setTimeout(async () => {
                try {
                    const res = await fetch(`/api/cities?q=${q}`);
                    const cities = await res.json();
                    typeCityDropdown.innerHTML = '';
                    if(cities.length) {
                        typeCityDropdown.classList.remove('hidden');
                        cities.slice(0,8).forEach(c => {
                            const div = document.createElement('div');
                            div.className = 'px-3 py-2 text-sm hover:bg-blue-50 cursor-pointer text-gray-700';
                            div.textContent = c.name;
                            div.onclick = () => {
                                hiddenCity.value = c.name;
                                document.getElementById('filters-form').submit();
                            };
                            typeCityDropdown.appendChild(div);
                        });
                    }
                } catch(err){}
            }, 300);
        });
    }

    // 7. Modal Interaction Logic
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('profile-steps-modal');
        if (!modal) return;
        const openButtons = document.querySelectorAll('.js-open-offer-requirements');
        const closeBtn = document.getElementById('profile-steps-close');

        function showModal() { 
            modal.classList.remove('hidden'); 
            refreshIcons(); // Re-render icons since they were hidden
        }
        function hideModal() { 
            modal.classList.add('hidden'); 
        }

        // Open modal when "Make an offer" button is clicked
        openButtons.forEach(btn => btn.addEventListener('click', (e) => { 
            e.preventDefault(); 
            showModal(); 
        }));
        
        // Close on 'X'
        if (closeBtn) closeBtn.addEventListener('click', hideModal);
        
        // Close on Background Click
        modal.addEventListener('click', (e) => { 
            if (e.target === modal) hideModal(); 
        });
    });
  </script>
  @endsection