@extends('layout')

@section('content')
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
      width: 111.4%;
      height: 84.4vh; /* will be overridden by JS to match tasks panel */
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
    /* Ensure proper layout */
    .main-container {
      display: flex;
      gap: 1.5rem;
      align-items: stretch;
    }
    .tasks-pane {
      flex: 0 0 380px;
    }
    .map-container {
      flex: 1;
      min-width: 0; /* Allow shrinking */
    }
  </style>
  <!-- MapLibre GL (Vector OpenStreetMap) -->
  <link href="https://unpkg.com/maplibre-gl@3.6.1/dist/maplibre-gl.css" rel="stylesheet" />
  <script src="https://unpkg.com/maplibre-gl@3.6.1/dist/maplibre-gl.js"></script>
 
  <!-- Filters Bar -->
  <section class="bg-white py-4 border-b relative z-50">
    <form method="GET" action="{{ route('tasks') }}" id="filters-form"
          class="max-w-7xl mx-auto flex flex-wrap items-center gap-3 px-6">
 
      <!-- Multi-search (title, description, city, category) -->
      <input
        id="search-q"
        name="q"
        value="{{ $filters['q'] ?? '' }}"
        type="text"
        placeholder="Search tasks, city or category..."
        class="w-72 px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500"
        autocomplete="off"
      >
      <input type="hidden" name="city_search" id="city-search-hidden" value="{{ $filters['city_search'] ?? '' }}">
 
      <!-- Category -->
      <select name="category" id="category-filter" class="px-4 py-3 rounded-xl border border-gray-300">
        <option value="">All Categories</option>
        @foreach(($categories ?? []) as $category)
          <option value="{{ $category->id }}" @selected(($filters['category'] ?? '') == $category->id)>
            {{ $category->name }}
          </option>
        @endforeach
      </select>
 
      <!-- Type Filter -->
      <div class="relative">
        <button type="button" id="type-btn"
                class="px-4 py-3 rounded-xl border border-gray-300 flex items-center gap-2 bg-white hover:bg-gray-50">
          <span id="type-text">Work Type: All</span>
          <i data-feather="chevron-down" class="w-4 h-4"></i>
        </button>
        <div id="type-menu"
             class="absolute mt-2 right-0 bg-white border rounded-xl shadow-lg p-4 w-64 hidden z-50">
          <label class="block text-sm text-gray-600 mb-2">Select work type:</label>
          <!-- Live city search inside work type menu -->
          <input id="type-city-search" type="text" placeholder="Search city..."
                 class="mb-2 w-full px-3 py-2 rounded border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500"
                 autocomplete="off">
          <div id="type-city-dropdown" class="mb-3 max-h-48 overflow-y-auto hidden border rounded">
            <!-- City suggestions appear here -->
          </div>
          <div class="space-y-2" id="type-options">
            <label class="flex items-center">
              <input type="radio" name="type" value="all" class="mr-2" @checked(($filters['type'] ?? 'all') === 'all')>
              <span class="text-sm">All</span>
            </label>
            <label class="flex items-center">
              <input type="radio" name="type" value="in_person" class="mr-2" @checked(($filters['type'] ?? '') === 'in_person')>
              <span class="text-sm">In-Person</span>
            </label>
            <label class="flex items-center">
              <input type="radio" name="type" value="remote" class="mr-2" @checked(($filters['type'] ?? '') === 'remote')>
              <span class="text-sm">Remote</span>
            </label>
          </div>
          <div class="mt-4 flex gap-2">
            <button type="button" id="type-apply" class="px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
              Apply
            </button>
            <button type="button" id="type-cancel" class="px-3 py-1 bg-gray-300 text-gray-700 text-sm rounded hover:bg-gray-400">
              Cancel
            </button>
          </div>
        </div>
      </div>
 
      <!-- Price dropdown -->
      <div class="relative">
        <button type="button" id="price-btn"
                class="px-4 py-3 rounded-xl border border-gray-300 flex items-center gap-2 bg-white hover:bg-gray-50">
          <span id="price-text">Price Range</span>
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
          <div class="mt-4 flex gap-2">
            <button type="button" id="price-apply" class="px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
              Apply
            </button>
            <button type="button" id="price-cancel" class="px-3 py-1 bg-gray-300 text-gray-700 text-sm rounded hover:bg-gray-400">
              Cancel
            </button>
          </div>
        </div>
      </div>
 
      <!-- Sort -->
      <select name="sort" id="sort-filter" class="px-4 py-3 rounded-xl border border-gray-300">
        <option value="recent" @selected(($filters['sort'] ?? 'recent')==='recent')>Sort by: Recently Posted</option>
        <option value="closest" @selected(($filters['sort'] ?? '')==='closest')>Closest to me</option>
        <option value="due" @selected(($filters['sort'] ?? '')==='due')>Due soon</option>
        <option value="lowest_price" @selected(($filters['sort'] ?? '')==='lowest_price')>Lowest Price</option>
        <option value="highest_price" @selected(($filters['sort'] ?? '')==='highest_price')>Highest Price</option>
      </select>
    </form>
  </section>
 
  <!-- Main Layout -->
  <section class="py-6">
    <div class="max-w-7xl mx-auto px-6 main-container">
      <!-- Left: Tasks -->
      <div id="tasks-pane" class="tasks-pane bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
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
      <div class="map-container">
        <div id="map" class="rounded-xl"></div>
      </div>
    </div>
  </section>

  <script>
    if (window.feather && typeof window.feather.replace === 'function') {
      window.feather.replace();
    }
    
    // Initialize MapLibre GL with OpenStreetMap style (green landscape, visible streets)
    const map = new maplibregl.Map({
      container: 'map',
      style: {
        version: 8,
        sources: {
          'osm-tiles': {
            type: 'raster',
            tiles: ['https://a.tile.openstreetmap.org/{z}/{x}/{y}.png'],
            tileSize: 256,
            attribution: '© OpenStreetMap contributors'
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
      center: [19.0402, 47.4979], // lng, lat (Budapest)
      zoom: 12,
      attributionControl: true
    });
    
    map.addControl(new maplibregl.NavigationControl({ showCompass: false }));

    // Prepare tasks data for map pins
    @php
      $source = ($tasks instanceof \Illuminate\Pagination\AbstractPaginator) ? $tasks->items() : ($tasks ?? []);
      $taskPoints = collect($source)->map(function($t){
        $id = is_object($t) && isset($t->id) ? $t->id : null;
        $title = is_object($t) && isset($t->title) ? $t->title : '';
        $price = is_object($t) && isset($t->price) ? (int) $t->price : 0;
        $city = '';
        if (is_object($t)) {
          $emp = $t->employer ?? null;
          if (is_object($emp)) {
            $c = $emp->city ?? null;
            if (is_object($c) && isset($c->name)) { $city = $c->name; }
          }
        }
        return [ 'id' => $id, 'title' => $title, 'price' => $price, 'city' => $city ];
      })->filter(function($row){ return !is_null($row['id']); })->values();
    @endphp
    const tasksData = @json($taskPoints);

    // Geocode cities via Nominatim with simple localStorage cache and rate limiting
    const cityCache = {
      get(name){
        try { return JSON.parse(localStorage.getItem('geocode:'+name) || 'null'); } catch { return null; }
      },
      set(name, coords){
        try { localStorage.setItem('geocode:'+name, JSON.stringify(coords)); } catch {}
      }
    };

    async function geocodeCity(name){
      if (!name) return null;
      const cached = cityCache.get(name);
      if (cached && typeof cached.lat === 'number' && typeof cached.lng === 'number') return cached;
      const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(name)}&limit=1`;
      const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
      if (!res.ok) return null;
      const data = await res.json();
      if (!Array.isArray(data) || !data[0]) return null;
      const coords = { lat: parseFloat(data[0].lat), lng: parseFloat(data[0].lon) };
      cityCache.set(name, coords);
      return coords;
    }

    (async function plotTasksOnMap(){
      try {
        const uniqueCities = Array.from(new Set(tasksData.map(t => t.city).filter(Boolean)));
        const cityToCoords = {};
        for (let i = 0; i < uniqueCities.length; i++) {
          const city = uniqueCities[i];
          // Rate limit: ~1 req / 500ms
          /* eslint-disable no-await-in-loop */
          const coords = await geocodeCity(city);
          if (coords) cityToCoords[city] = coords;
          await new Promise(r => setTimeout(r, 500));
        }

        // Optionally fit bounds to markers
        const bounds = new maplibregl.LngLatBounds();
        tasksData.forEach(t => {
          const coords = t.city ? cityToCoords[t.city] : null;
          if (!coords) return;
          const html = `<div class="text-sm"><div class=\"font-semibold\">${t.title}</div><div>${t.city}</div><div>€${(t.price||0).toLocaleString()}</div></div>`;
          const el = document.createElement('div');
          el.style.width = '12px';
          el.style.height = '12px';
          el.style.background = '#2563eb';
          el.style.border = '2px solid white';
          el.style.borderRadius = '50%';
          el.style.boxShadow = '0 1px 2px rgba(0,0,0,0.3)';

          new maplibregl.Marker({ element: el })
            .setLngLat([coords.lng, coords.lat])
            .setPopup(new maplibregl.Popup({ offset: 12 }).setHTML(html))
            .addTo(map);
          bounds.extend([coords.lng, coords.lat]);
        });
        if (!bounds.isEmpty()) {
          map.fitBounds(bounds, { padding: 30 });
        }
      } catch (e) {
        console.warn('Plot tasks map error', e);
      }
    })();

    // Sync map height to match tasks pane and make it wider
    (function syncMapSize(){
      const mapEl = document.getElementById('map');
      const tasksPane = document.getElementById('tasks-pane');
      if (!mapEl || !tasksPane) return;
      
      function applySize(){
        // Match height of scroll area inside tasks pane
        const scrollArea = tasksPane.querySelector('.h-[80vh]');
        const targetH = scrollArea ? scrollArea.getBoundingClientRect().height : tasksPane.getBoundingClientRect().height;
        mapEl.style.height = Math.max(300, Math.floor(targetH)) + 'px';
        
        // Make map wider by using the full available space
        const container = document.querySelector('.main-container');
        if (container) {
          const containerWidth = container.getBoundingClientRect().width;
          const tasksWidth = tasksPane.getBoundingClientRect().width;
          const gap = 24; // gap-6 = 1.5rem = 24px
          const mapWidth = containerWidth - tasksWidth - gap;
          mapEl.style.width = Math.max(400, mapWidth) + 'px';
        }
        
        // Trigger map resize
        if (map && typeof map.resize === 'function') {
          setTimeout(() => map.resize(), 50);
        }
      }
      
      applySize();
      window.addEventListener('resize', applySize);
      
      // Also resize when the map container becomes visible
      const observer = new MutationObserver(applySize);
      observer.observe(mapEl, { attributes: true, attributeFilter: ['style', 'class'] });
    })();

    // The rest of your existing JavaScript code remains the same...
    // Clean URL on load: remove default/empty params without reloading
    (function cleanUrl(){
      const url = new URL(window.location.href);
      const defaults = new Map(Object.entries({
        q: '', city_search: '', category: '', type: 'all', min_price: '1000', max_price: '20000', sort: 'recent'
      }));
      let changed = false;
      defaults.forEach((def, key) => {
        if (!url.searchParams.has(key)) return;
        const val = url.searchParams.get(key) || '';
        if (val === def) { url.searchParams.delete(key); changed = true; }
      });
      if (changed && window.history && history.replaceState) {
        history.replaceState({}, '', url.toString());
      }
    })();

    // Live city search INSIDE Work Type dropdown
    let typeCityTimeout;
    const typeCitySearch = document.getElementById('type-city-search');
    const typeCityDropdown = document.getElementById('type-city-dropdown');
    const cityHidden = document.getElementById('city-search-hidden');

    typeCitySearch.addEventListener('input', function() {
      clearTimeout(typeCityTimeout);
      const query = this.value.trim();
      if (query.length < 2) {
        typeCityDropdown.classList.add('hidden');
        typeCityDropdown.innerHTML = '';
        return;
      }
      typeCityTimeout = setTimeout(() => {
        fetch(`/api/cities?q=${encodeURIComponent(query)}`)
          .then(res => res.json())
          .then(cities => {
            typeCityDropdown.innerHTML = '';
            if (!Array.isArray(cities) || cities.length === 0) {
              typeCityDropdown.innerHTML = '<div class="p-3 text-gray-500 text-sm">No cities found</div>';
            } else {
              // Deduplicate by normalized name, prefer exact match on top
              const seen = new Set();
              const normalized = (s) => (s || '').toLowerCase();
              const exactName = cities.find(c => normalized(c.name) === normalized(query))?.name || null;
              const ordered = [];
              if (exactName) {
                ordered.push({ name: exactName });
                seen.add(normalized(exactName));
              }
              cities.forEach(city => {
                const key = normalized(city.name);
                if (seen.has(key)) return;
                seen.add(key);
                ordered.push({ name: city.name });
              });

              ordered.slice(0, 10).forEach(city => {
                const el = document.createElement('div');
                el.className = 'px-3 py-2 text-sm hover:bg-gray-50 cursor-pointer';
                el.textContent = city.name;
                el.addEventListener('click', () => {
                  cityHidden.value = city.name;
                  typeCityDropdown.classList.add('hidden');
                  document.getElementById('type-menu').classList.add('hidden');
                  submitForm();
                });
                typeCityDropdown.appendChild(el);
              });
            }
            typeCityDropdown.classList.remove('hidden');
          })
          .catch(() => {
            typeCityDropdown.classList.add('hidden');
          });
      }, 300);
    });

    // Auto-submit for category and sort filters
    document.getElementById('category-filter').addEventListener('change', submitForm);
    document.getElementById('sort-filter').addEventListener('change', submitForm);

    function submitForm() {
      const form = document.getElementById('filters-form');
      const defaults = {
        q: '',
        city_search: '',
        category: '',
        type: 'all',
        min_price: '1000',
        max_price: '20000',
        sort: 'recent',
      };
      const toDisable = [];
      Array.from(form.elements).forEach(el => {
        if (!el.name) return;
        const def = defaults[el.name];
        if (typeof def === 'undefined') return;
        const val = (el.value || '').toString();
        // For selects, compare value; for radios compare checked
        if (el.type === 'radio') {
          if (el.name === 'type' && el.value === def && el.checked) toDisable.push(el);
          return;
        }
        if (val === def) toDisable.push(el);
      });
      toDisable.forEach(el => el.disabled = true);
      form.submit();
      setTimeout(() => toDisable.forEach(el => el.disabled = false), 0);
    }

    // Dropdown toggle functionality
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
 
    toggleDropdown('price-btn', 'price-menu');
    toggleDropdown('type-btn', 'type-menu');

    // Price range functionality
    (function(){
      const minEl = document.getElementById('price-min');
      const maxEl = document.getElementById('price-max');
      const minLabel = document.getElementById('price-min-label');
      const maxLabel = document.getElementById('price-max-label');
      const track = document.getElementById('price-track');
      const priceText = document.getElementById('price-text');
      const priceApply = document.getElementById('price-apply');
      const priceCancel = document.getElementById('price-cancel');
      const MIN = 1000;
      const MAX = 20000;
      const STEP = 50;
      const clamp = (v, lo, hi) => Math.min(Math.max(v, lo), hi);
      const toPercent = (v) => ((v - MIN) / (MAX - MIN)) * 100;

      let originalMin = parseInt(minEl.value, 10);
      let originalMax = parseInt(maxEl.value, 10);

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
        priceText.textContent = `€${minEl.value} - €${maxEl.value}`;
      }

      if (minEl && maxEl) {
        minEl.addEventListener('input', sync);
        maxEl.addEventListener('input', sync);
        // Initialize
        sync();
      }

      // Apply button
      priceApply.addEventListener('click', () => {
        document.getElementById('price-menu').classList.add('hidden');
        submitForm();
      });

      // Cancel button
      priceCancel.addEventListener('click', () => {
        minEl.value = originalMin;
        maxEl.value = originalMax;
        sync();
        document.getElementById('price-menu').classList.add('hidden');
      });
    })();

    // Type filter functionality
    (function(){
      const typeText = document.getElementById('type-text');
      const typeApply = document.getElementById('type-apply');
      const typeCancel = document.getElementById('type-cancel');
      const typeRadios = document.querySelectorAll('input[name="type"]');
      const typeSearch = document.getElementById('type-search');
      const typeOptions = document.getElementById('type-options');
      
      const checkedTypeInput = document.querySelector('input[name="type"]:checked');
      let originalType = checkedTypeInput ? checkedTypeInput.value : 'all';

      function updateTypeText() {
        const selected = document.querySelector('input[name="type"]:checked');
        if (selected) {
          const text = selected.nextElementSibling.textContent;
          typeText.textContent = `Work Type: ${text}`;
        }
      }

      typeRadios.forEach(radio => {
        radio.addEventListener('change', updateTypeText);
      });

      // Live search within type options (only if input exists)
      if (typeSearch) {
        typeSearch.addEventListener('input', () => {
          const q = typeSearch.value.toLowerCase();
          Array.from(typeOptions.querySelectorAll('label')).forEach(label => {
            const txt = label.textContent.toLowerCase();
            label.style.display = txt.includes(q) ? '' : 'none';
          });
        });
      }

      // Apply button
      typeApply.addEventListener('click', () => {
        document.getElementById('type-menu').classList.add('hidden');
        submitForm();
      });

      // Cancel button
      typeCancel.addEventListener('click', () => {
        const original = document.querySelector(`input[name="type"][value="${originalType}"]`);
        if (original) original.checked = true;
        updateTypeText();
        document.getElementById('type-menu').classList.add('hidden');
      });

      // Initialize
      updateTypeText();
    })();
  </script>
@endsection