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

    /* Dual Range Slider — Airtasker Style */
    .range-slider {
      position: relative;
      width: 100%;
      height: 30px;
    }
    .range-slider .track-bg {
      position: absolute;
      width: 100%;
      height: 6px;
      background: #e0e0e0;
      border-radius: 3px;
      top: 50%;
      transform: translateY(-50%);
      left: 0;
    }
    .range-slider .track-fill {
      position: absolute;
      height: 6px;
      background: #2563eb;
      border-radius: 3px;
      top: 50%;
      transform: translateY(-50%);
      pointer-events: none;
    }
    .range-slider input[type=range] {
      -webkit-appearance: none;
      appearance: none;
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: transparent;
      pointer-events: none;
      margin: 0;
      padding: 0;
      outline: none;
      left: 0;
    }
    .range-slider input[type=range]::-webkit-slider-thumb {
      -webkit-appearance: none;
      appearance: none;
      width: 21px;
      height: 21px;
      background: #ffffff;
      border-radius: 50%;
      border: 2px solid #2563eb;
      box-shadow: 0 1px 3px rgba(0,0,0,0.15);
      cursor: pointer;
      pointer-events: all;
      position: relative;
      z-index: 3;
      transition: box-shadow 0.15s;
      margin-top: -8px;
    }
    .range-slider input[type=range]::-webkit-slider-thumb:hover {
      box-shadow: 0 0 0 4px rgba(37,99,235,0.15), 0 1px 3px rgba(0,0,0,0.15);
    }
    .range-slider input[type=range]::-webkit-slider-thumb:active {
      box-shadow: 0 0 0 6px rgba(37,99,235,0.2), 0 1px 3px rgba(0,0,0,0.15);
    }
    .range-slider input[type=range]::-moz-range-thumb {
      width: 21px;
      height: 21px;
      background: #ffffff;
      border-radius: 50%;
      border: 2px solid #2563eb;
      box-shadow: 0 1px 3px rgba(0,0,0,0.15);
      cursor: pointer;
      pointer-events: all;
      margin-top: -8px;
    }
    .range-slider input[type=range]::-webkit-slider-runnable-track {
      height: 6px;
      background: transparent;
    }
    .range-slider input[type=range]::-moz-range-track {
      height: 6px;
      background: transparent;
    }
    .range-slider input[id*="price-min"] {
      z-index: 2;
    }
    .range-slider input[id*="price-max"] {
      z-index: 3;
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

    /* Map Customizations */
    .custom-task-popup .maplibregl-popup-content {
      border-radius: 1rem;
      padding: 0;
      box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1);
      border: 1px solid rgba(0,0,0,0.05);
      overflow: hidden;
    }
    .custom-task-popup .maplibregl-popup-tip {
      display: none;
    }
    .task-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
  </style>

  <!-- MapLibre GL -->
  <link href="https://unpkg.com/maplibre-gl@3.6.1/dist/maplibre-gl.css" rel="stylesheet" />
  <script src="https://unpkg.com/maplibre-gl@3.6.1/dist/maplibre-gl.js"></script>
 
  <!-- FILTERS NAVBAR -->
  <section class="bg-gray-50 z-20 relative pt-4">
    <div class="max-w-7xl mx-auto px-4 md:px-6">
      <div class="bg-white border border-gray-200 rounded-2xl shadow-sm">
        <!-- Desktop Form (Hidden on mobile) -->
        <form method="GET" action="{{ route('tasks') }}" id="filters-form"
              class="flex items-center gap-4 px-6 py-3 h-16 hidden md:flex">

          <!-- Search Bar -->
          <div class="relative flex-grow max-w-md group border-r pr-6 mr-2">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <i data-feather="search" class="h-4 w-4 text-gray-400 group-  focus-within:text-blue-500"></i>
            </div>
            <input
              id="search-q"
              name="q"
              value="{{ $filters['q'] ?? '' }}"
              type="text"
              placeholder="Search for a task name..."
              class="w-full pl-10 pr-12 py-2 rounded-full bg-gray-100 border-transparent focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 text-sm transition-all outline-none"
              autocomplete="off"
            >
            <button type="submit" class="absolute right-7 top-1 bottom-1 px-3 flex items-center justify-center bg-blue-600 text-white rounded-full hover:bg-blue-700 transition-colors shadow-sm">
               <i data-feather="search" class="w-3.5 h-3.5"></i>
            </button>
            <input type="hidden" name="city_search" id="city-search-hidden" value="{{ $filters['city_search'] ?? '' }}">
          </div>

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

            <!-- Job/Service -->
            <div class="relative {{ ($filters['category'] ?? '') ? '' : 'hidden' }}" id="job-filter-container">
                <select name="job" id="job-filter" 
                        class="appearance-none pl-3 pr-8 py-2 rounded-lg border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:ring-2 focus:ring-blue-100 outline-none cursor-pointer transition-all">
                  <option value="">All Services</option>
                  @if($filters['category'] ?? '')
                    @php
                        $selectedCategory = $categories->firstWhere('id', $filters['category']);
                        $jobs = $selectedCategory ? $selectedCategory->jobs : [];
                    @endphp
                    @foreach($jobs as $job)
                        <option value="{{ $job->id }}" @selected(($filters['job'] ?? '') == $job->id)>
                            {{ $job->name }}
                        </option>
                    @endforeach
                  @endif
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
                      class="min-w-[120px] justify-between px-3 py-2 rounded-lg border {{ (isset($filters['min_price']) || isset($filters['max_price']) && ($filters['min_price'] != 1000 || $filters['max_price'] != 20000)) ? 'border-blue-400' : 'border-gray-300' }} bg-white text-sm font-medium text-gray-700 hover:border-blue-400 hover:text-blue-600 flex items-center gap-2 transition-all">
                <i data-feather="dollar-sign" class="w-3.5 h-3.5 text-gray-500"></i>
                <span id="price-text" class="{{ (isset($filters['min_price']) || isset($filters['max_price']) && ($filters['min_price'] != 1000 || $filters['max_price'] != 20000)) ? 'text-blue-600' : '' }}">
                  @if(isset($filters['min_price']) || isset($filters['max_price']) && ($filters['min_price'] != 1000 || $filters['max_price'] != 20000))
                    €{{ number_format((int)($filters['min_price'] ?? 1000), 0, '.', ',') }} - €{{ number_format((int)($filters['max_price'] ?? 20000), 0, '.', ',') }}
                  @else
                    Price
                  @endif
                </span>
                <i data-feather="chevron-down" class="w-3.5 h-3.5 ml-1 text-gray-400"></i>
              </button>

              <div id="price-menu" class="absolute mt-2 right-0 bg-white border border-gray-200 rounded-lg shadow-lg hidden z-50" style="width: 340px; padding: 16px 20px 12px;">
                {{-- Header --}}
                <label class="block text-[11px] font-semibold text-gray-400 uppercase tracking-wide mb-2" style="letter-spacing:.05em;">Task Price</label>

                {{-- Price display box --}}
                <div class="border border-gray-300 rounded-md px-3 py-2 mb-5 text-center">
                  <span id="price-display" class="text-[15px] font-semibold text-gray-800">
                    €{{ number_format((int)($filters['min_price'] ?? 1000), 0, '.', ',') }} - €{{ number_format((int)($filters['max_price'] ?? 20000), 0, '.', ',') }}
                  </span>
                </div>

                {{-- Slider --}}
                <div class="range-slider" style="margin-bottom: 20px;">
                   <div class="track-bg"></div>
                   <div id="price-track" class="track-fill"></div>
                   <input id="price-min" name="min_price" type="range" min="1000" max="20000" step="50"
                          value="{{ max(1000, (int)($filters['min_price'] ?? 1000)) }}">
                   <input id="price-max" name="max_price" type="range" min="1000" max="20000" step="50"
                          value="{{ min(20000, (int)($filters['max_price'] ?? 20000)) }}">
                </div>

                {{-- Buttons --}}
                <div class="flex items-center justify-between gap-3 pt-3 border-t border-gray-100">
                  <button type="button" id="price-cancel" class="flex-1 py-2 text-sm font-semibold text-gray-600 hover:text-gray-800 rounded-md border border-gray-300 hover:bg-gray-50 transition-colors">Cancel</button>
                  <button type="button" id="price-apply" class="flex-1 py-2 text-sm font-bold text-white bg-blue-600 rounded-md hover:bg-blue-700 transition-colors">Apply</button>
                </div>
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

        <!-- Mobile Filter Trigger (Visible only on mobile) -->
        <div class="md:hidden px-4 py-3 bg-white">
            <button type="button" id="mobile-filter-trigger" 
                    class="w-full h-11 flex items-center justify-between border border-gray-200 rounded-full px-5 bg-gray-50 hover:bg-gray-100 transition-colors cursor-pointer text-sm shadow-sm">
                <div class="flex items-center gap-3 overflow-hidden">
                    <i data-feather="search" class="w-4 h-4 text-blue-600"></i>
                    <div class="flex flex-col items-start overflow-hidden">
                        <span class="font-bold text-gray-900 text-[13px] leading-tight truncate">
                             @if($filters['q'] ?? '') 
                                "{{ $filters['q'] }}" 
                            @else 
                                Browse everything
                            @endif
                        </span>
                        <span class="text-[11px] text-gray-500 leading-tight">
                            @if($filters['type'] ?? '')
                                {{ $filters['type'] === 'remote' ? 'Remote' : 'In-person' }}
                            @else
                                Any mode
                            @endif
                            ·
                            @if($filters['category'] ?? '')
                                {{ $categories->firstWhere('id', $filters['category'])->name ?? 'All' }}
                            @else
                                All categories
                            @endif
                        </span>
                    </div>
                </div>
                <div class="flex items-center gap-2 border-l pl-3 ml-2 shrink-0">
                    <i data-feather="sliders" class="w-4 h-4 text-gray-600"></i>
                </div>
            </button>
        </div>
      </div>
    </div>
  </section>

  <!-- MOBILE FILTERS MODAL -->
  <div id="mobile-filters-modal" class="fixed inset-0 z-[100] bg-white hidden flex-col">
      <!-- Modal Header -->
      <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
          <h2 class="text-base font-bold text-gray-800">Filters</h2>
          <button type="button" id="close-mobile-filters" class="p-2 -mr-2 bg-gray-50 rounded-full text-gray-400">
              <i data-feather="x" class="w-5 h-5"></i>
          </button>
      </div>

      <!-- Modal Content -->
      <form id="mobile-filters-form" method="GET" action="{{ route('tasks') }}" class="flex-1 overflow-y-auto px-6 pt-2 pb-6 space-y-7 custom-scroll">
          
          <!-- Task Name Search -->
          <div>
              <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Task Name</label>
              <div class="relative">
                  <input type="text" name="q" placeholder="Search for task name..." value="{{ $filters['q'] ?? '' }}" 
                         class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-5 py-4 text-[15px] font-semibold text-gray-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-50 transition-all outline-none">
                  <div class="absolute right-5 top-1/2 -translate-y-1/2">
                      <i data-feather="search" class="w-4 h-4 text-gray-400"></i>
                  </div>
              </div>
          </div>
          
          <!-- Category -->
          <div>
              <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Category</label>
              <div class="relative">
                  <select name="category" id="mobile-category" class="w-full appearance-none bg-gray-50 border border-gray-200 rounded-2xl px-5 py-4 text-[15px] font-semibold text-gray-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-50 transition-all outline-none">
                      <option value="">All Categories</option>
                      @foreach(($categories ?? []) as $category)
                          <option value="{{ $category->id }}" @selected(($filters['category'] ?? '') == $category->id)>
                              {{ $category->name }}
                          </option>
                      @endforeach
                  </select>
                  <div class="absolute inset-y-0 right-5 flex items-center pointer-events-none">
                      <i data-feather="chevron-down" class="w-5 h-5 text-gray-400"></i>
                  </div>
              </div>
          </div>

          <!-- To be done -->
          <div>
              <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">To be done</label>
              <div class="flex p-1.5 bg-gray-100 rounded-2xl border border-gray-200">
                  <button type="button" class="mobile-type-tab flex-1 py-3 text-[13px] font-bold rounded-xl transition-all {{ ($filters['type'] ?? 'all') === 'in_person' ? 'bg-white shadow-md text-blue-600' : 'text-gray-500' }}" data-value="in_person">In-person</button>
                  <button type="button" class="mobile-type-tab flex-1 py-3 text-[13px] font-bold rounded-xl transition-all {{ ($filters['type'] ?? 'all') === 'remote' ? 'bg-blue-900 text-white shadow-md' : 'text-gray-500' }}" data-value="remote">Remotely</button>
                  <button type="button" class="mobile-type-tab flex-1 py-3 text-[13px] font-bold rounded-xl transition-all {{ ($filters['type'] ?? 'all') === 'all' ? 'bg-white shadow-md text-blue-600' : 'text-gray-500' }}" data-value="all">All</button>
              </div>
              <input type="hidden" name="type" id="mobile-type-hidden" value="{{ $filters['type'] ?? 'all' }}">
          </div>

          <!-- Suburb -->
          <div>
              <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Suburb</label>
              <div class="relative">
                  <input type="text" id="mobile-city-search-input" placeholder="Search city or suburb..." value="{{ $filters['city_search'] ?? '' }}" class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-5 py-4 text-[15px] font-semibold text-gray-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-50 transition-all outline-none">
                  <div class="absolute right-5 top-1/2 -translate-y-1/2">
                      <i data-feather="map-pin" class="w-4 h-4 text-gray-400"></i>
                  </div>
                  <div id="mobile-city-results" class="absolute top-full left-0 right-0 mt-2 bg-white border border-gray-100 rounded-2xl shadow-2xl z-[110] hidden max-h-56 overflow-y-auto"></div>
              </div>
              <input type="hidden" name="city_search" id="mobile-city-hidden" value="{{ $filters['city_search'] ?? '' }}">
          </div>

          <!-- Task Price -->
          <div>
              <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-6">Task Price</label>
              <div class="text-center mb-8">
                  <span id="mobile-price-text" class="text-[15px] font-extrabold text-blue-600 bg-blue-50 px-5 py-2.5 rounded-full border border-blue-200">
                      €{{ number_format($filters['min_price'] ?? 1000) }} - €{{ number_format($filters['max_price'] ?? 20000) }}
                  </span>
              </div>
              <div class="px-2">
                  <div class="range-slider">
                      <div class="track-bg h-2"></div>
                      <div id="mobile-price-track" class="track-fill h-2"></div>
                      <input id="mobile-price-min" name="min_price" type="range" min="1000" max="20000" step="100" value="{{ $filters['min_price'] ?? 1000 }}">
                      <input id="mobile-price-max" name="max_price" type="range" min="1000" max="20000" step="100" value="{{ $filters['max_price'] ?? 20000 }}">
                  </div>
              </div>
          </div>

          <!-- Sort -->
          <div>
              <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Sort by</label>
              <div class="relative">
                  <select name="sort" class="w-full appearance-none bg-gray-50 border border-gray-200 rounded-2xl px-5 py-4 text-[15px] font-semibold text-gray-800 focus:bg-white focus:border-blue-500 outline-none">
                      <option value="recent" @selected(($filters['sort'] ?? 'recent')==='recent')>Most Recent</option>
                      <option value="closest" @selected(($filters['sort'] ?? '')==='closest')>Closest to me</option>
                      <option value="due" @selected(($filters['sort'] ?? '')==='due')>Due Soon</option>
                      <option value="lowest_price" @selected(($filters['sort'] ?? '')==='lowest_price')>Price: Low to High</option>
                      <option value="highest_price" @selected(($filters['sort'] ?? '')==='highest_price')>Price: High to Low</option>
                  </select>
                  <div class="absolute inset-y-0 right-5 flex items-center pointer-events-none">
                      <i data-feather="chevron-down" class="w-5 h-5 text-gray-400"></i>
                  </div>
              </div>
          </div>
      </form>

      <!-- Modal Footer -->
      <div class="p-6 border-t border-gray-100 flex gap-4 bg-white">
          <button type="button" id="clear-mobile-filters" class="flex-1 py-4 text-[15px] font-bold text-gray-500 bg-gray-50 hover:bg-gray-100 rounded-2xl transition-all">Cancel</button>
          <button type="button" id="apply-mobile-filters" class="flex-1 py-4 text-[15px] font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-2xl shadow-lg shadow-blue-100 transition-all">Apply</button>
      </div>
  </div>
 
  <!-- Main Content -->
 <section class="bg-gray-50 pt-6 md:pt-10 h-auto md:h-[700px] overflow-hidden">
   <div class="flex flex-col md:flex-row max-w-7xl mx-auto px-4 md:px-6 gap-4 md:gap-6 h-full pb-6">
     
      <!-- Left: Tasks Pane -->
     <div id="tasks-pane" class="flex flex-col w-full md:w-[360px] shrink-0 h-[600px] md:h-full">
        <div class="flex-1 overflow-y-auto custom-scroll pr-2 space-y-3">
          @forelse (($tasks ?? []) as $task)
            @php
                $hasOffer = Auth::check() && $task->offers->contains('user_id', Auth::id());
            @endphp
            <div id="task-card-{{ $task->id }}" class="group task-card p-4 rounded-xl border hover:border-blue-400 hover:shadow-md transition-all duration-200 relative {{ $hasOffer ? 'bg-blue-50 border-blue-300' : 'bg-white border-gray-200' }}" data-task-id="{{ $task->id }}">
             
              <div class="flex justify-between items-start mb-1.5">
                <h3 class="text-sm font-bold text-gray-800 leading-tight group-hover:text-blue-600">
                    @if($hasOffer)
                        <span class="inline-flex items-center justify-center bg-blue-100 text-blue-600 rounded-full p-1 mr-1" title="You made an offer">
                            <i data-feather="check-circle" class="w-3.5 h-3.5"></i>
                        </span>
                    @endif
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
                    📍 {{ $task->location ?? 'Remote' }}
                 </span>
              </div>
 
              <div class="pt-2 border-t border-gray-50 flex justify-between items-center">
                 <span class="text-[10px] text-gray-400">
                    {{ $task->created_at?->diffForHumans(null, true, true) }} ago
                 </span>
                 <div class="flex items-center gap-2">
                     @auth
                         <button type="button" onclick="openReportModal({{ $task->id }}, {{ $task->employer_id }})" class="text-xs font-semibold text-gray-500 hover:text-red-600 transition-colors" title="Report this task">
                             <i data-feather="flag" class="w-3.5 h-3.5"></i>
                         </button>
                     @endauth
                     @guest
                         <a href="{{ route('login', ['returnUrl' => route('tasks.show', $task->id)]) }}" class="text-xs font-semibold text-blue-600 hover:underline">
                            Sign in to make an offer
                         </a>
                     @else
                         @if($hasOffer)
                            <form method="POST" action="{{ route('tasks.offers.destroy', $task->id) }}" class="inline m-0 p-0">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs font-semibold text-red-600 hover:text-white bg-red-50 hover:bg-red-600 border border-red-200 hover:border-red-600 px-3 py-1.5 rounded-full transition-colors whitespace-nowrap inline-flex items-center gap-1">
                                    <i data-feather="x" class="w-3 h-3"></i> Cancel offer
                                </button>
                            </form>
                         @else
                             <!-- Logic: if they have missing steps, show button that opens modal. Otherwise regular link. -->
                             @if(count($missingSteps) > 0)
                                <button type="button" class="js-open-offer-requirements text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 px-3 py-1.5 rounded-full transition-colors">
                                    Make an offer
                                </button>
                             @else
                                <a href="{{ route('tasks.show', $task->id) }}" class="text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 px-3 py-1.5 rounded-full transition-colors">
                                    Make an offer
                                </a>
                             @endif
                         @endif
                     @endguest
                 </div>
              </div>
            </div>
          @empty
            <div class="h-full bg-white rounded-2xl border border-dashed border-gray-200 p-10 text-center flex flex-col items-center justify-center space-y-4 shadow-sm">
              <div class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center mb-2">
                 <i data-feather="search" class="w-10 h-10 text-blue-300"></i>
              </div>
              <div class="space-y-2">
                <h3 class="text-xl font-bold text-slate-800">No tasks found</h3>
                <p class="text-base text-slate-500 leading-relaxed max-w-[280px] mx-auto">
                  We couldn't find any tasks matching your criteria. Try adjusting your filters or search terms.
                </p>
              </div>
              <a href="{{ route('tasks') }}" class="inline-flex items-center gap-2 text-sm font-bold text-blue-600 hover:bg-blue-600 hover:text-white border border-blue-100 bg-blue-50/50 px-8 py-3 rounded-full transition-all mt-4 shadow-sm hover:shadow-md">
                <i data-feather="refresh-cw" class="w-4 h-4"></i>
                Clear all filters
              </a>
            </div>
          @endforelse
         
          @if(isset($tasks) && $tasks->hasPages())
             <div class="py-2 text-xs">
                 {{ $tasks->links() }}
             </div>
          @endif
        </div>
      </div>
 
    <!-- Right: Map (Hidden on mobile) -->
      <div class="hidden md:flex flex-1 bg-gray-200 rounded-xl overflow-hidden shadow-inner border border-gray-300 relative">
        <div id="map"></div>
        <div class="pointer-events-none absolute inset-x-0 top-0 h-6 bg-gradient-to-b from-black/5 to-transparent z-10"></div>
      </div>
 
    </div>
  </section>



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
        $loc = $t->location ?? $t->employer->city->name ?? null;
        $isRemote = $loc && stripos($loc, 'remote') !== false;
        return [ 
            'id' => $t->id ?? null, 
            'title' => $t->title ?? '', 
            'price' => (int)($t->price ?? 0), 
            'location' => $loc,
            'is_remote' => $isRemote
        ];
      })->filter(fn($r) => $r['id'] && $r['location'] && !$r['is_remote'])->values();
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
      const locations = [...new Set(tasksData.map(t => t.location))];
      const locationToCoords = {};
      for (const loc of locations) {
        const coords = await geocodeCity(loc);
        if (coords) locationToCoords[loc] = coords;
        await new Promise(r => setTimeout(r, 400));
      }

      const bounds = new maplibregl.LngLatBounds();
      const markers = {};

      tasksData.forEach(t => {
        const baseCoords = locationToCoords[t.location];
        if (!baseCoords) return;

        // Apply slight jitter to separate markers in same city
        const jitter = 0.008;
        const lng = baseCoords.lng + (Math.random() - 0.5) * jitter;
        const lat = baseCoords.lat + (Math.random() - 0.5) * jitter;

        const el = document.createElement('div');
        el.className = 'map-marker-container group';
        el.style.cursor = 'pointer';
        el.innerHTML = `
            <div class="relative flex items-center justify-center">
                <div class="absolute w-8 h-8 bg-blue-500/20 rounded-full animate-ping opacity-75"></div>
                <div class="w-4 h-4 bg-blue-600 rounded-full border-2 border-white shadow-lg relative z-10 transition-all duration-300 group-hover:scale-125 group-hover:bg-blue-500"></div>
            </div>
        `;

        const popupHTML = `
            <div class="p-3 min-w-[180px]">
                <div class="text-[10px] font-bold text-blue-500 uppercase tracking-wider mb-1">${t.location}</div>
                <div class="font-bold text-sm text-gray-900 mb-1 leading-tight">${t.title}</div>
                <div class="text-blue-600 font-extrabold text-sm mb-3">€${t.price.toLocaleString()}</div>
                <a href="/tasks/${t.id}" class="block w-full py-2 text-center bg-blue-600 text-white text-[11px] font-bold rounded-lg hover:bg-blue-700 transition-colors no-underline">View Details</a>
            </div>
        `;

        const marker = new maplibregl.Marker({ element: el })
          .setLngLat([lng, lat])
          .setPopup(new maplibregl.Popup({ 
              offset: 15, 
              closeButton: false,
              className: 'custom-task-popup'
          }).setHTML(popupHTML))
          .addTo(map);
        
        markers[t.id] = marker;
        bounds.extend([lng, lat]);

        // Hover Effect: Card -> Marker
        const card = document.getElementById(`task-card-${t.id}`);
        if(card) {
            card.addEventListener('mouseenter', () => {
                el.querySelector('.animate-ping').classList.remove('animate-ping');
                el.querySelector('.absolute').classList.add('scale-150', 'bg-blue-400/40');
                el.querySelector('.z-10').classList.add('scale-150', 'bg-blue-500');
            });
            card.addEventListener('mouseleave', () => {
                el.querySelector('.animate-ping')?.classList.add('animate-ping');
                el.querySelector('.absolute').classList.remove('scale-150', 'bg-blue-400/40');
                el.querySelector('.z-10').classList.remove('scale-150', 'bg-blue-500');
            });
        }

        // Hover Effect: Marker -> Card
        el.addEventListener('mouseenter', () => {
            if(card) {
                card.classList.add('border-blue-400', 'ring-4', 'ring-blue-50', 'shadow-xl', '-translate-y-1');
                card.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        });
        el.addEventListener('mouseleave', () => {
            if(card) {
                card.classList.remove('border-blue-400', 'ring-4', 'ring-blue-50', 'shadow-xl', '-translate-y-1');
            }
        });
      });

      if (!bounds.isEmpty()) map.fitBounds(bounds, { padding: 70, maxZoom: 13 });
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

    const categoriesData = @json($categories);

    ['category-filter', 'sort-filter', 'job-filter'].forEach(id => {
        document.getElementById(id)?.addEventListener('change', (e) => {
            if (id === 'category-filter') {
                const categoryId = e.target.value;
                const jobFilter = document.getElementById('job-filter');
                const jobContainer = document.getElementById('job-filter-container');
                
                if (categoryId) {
                    const category = categoriesData.find(c => c.id == categoryId);
                    if (category && category.jobs) {
                        jobFilter.innerHTML = '<option value="">All Services</option>';
                        category.jobs.forEach(j => {
                            jobFilter.innerHTML += `<option value="${j.id}">${j.name}</option>`;
                        });
                        jobContainer.classList.remove('hidden');
                    }
                } else {
                    jobContainer.classList.add('hidden');
                    jobFilter.value = '';
                }
            }
            document.getElementById('filters-form').submit();
        });
    });

    // 5. Dual-Thumb Price Range Slider (Airtasker style)
    (function initPriceSlider() {
        const minEl = document.getElementById('price-min');
        const maxEl = document.getElementById('price-max');
        const track = document.getElementById('price-track');
        const priceDisplay = document.getElementById('price-display');
        const priceText = document.getElementById('price-text');
        const priceBtn = document.getElementById('price-btn');
        const priceMenu = document.getElementById('price-menu');
        if (!minEl || !maxEl) return;

        const RANGE_MIN = 1000;
        const RANGE_MAX = 20000;
        const GAP = 100;

        function update(source) {
            let minVal = parseInt(minEl.value);
            let maxVal = parseInt(maxEl.value);

            // Enforce minimum gap
            if (minVal > maxVal - GAP) {
                if (source === 'min') {
                    minVal = maxVal - GAP;
                    minEl.value = minVal;
                } else {
                    maxVal = minVal + GAP;
                    maxEl.value = maxVal;
                }
            }

            // Colored track between thumbs
            const pMin = ((minVal - RANGE_MIN) / (RANGE_MAX - RANGE_MIN)) * 100;
            const pMax = ((maxVal - RANGE_MIN) / (RANGE_MAX - RANGE_MIN)) * 100;
            track.style.left = pMin + '%';
            track.style.right = (100 - pMax) + '%';
            track.style.width = 'auto';

            // Update price display box inside dropdown
            if (priceDisplay) {
                priceDisplay.textContent = `€${minVal.toLocaleString()} - €${maxVal.toLocaleString()}`;
            }
        }

        minEl.addEventListener('input', () => update('min'));
        maxEl.addEventListener('input', () => update('max'));

        // Initial update
        update('min');

        // Apply button — submit form & update button text
        document.getElementById('price-apply').addEventListener('click', () => {
            const minVal = parseInt(minEl.value);
            const maxVal = parseInt(maxEl.value);
            priceText.textContent = `€${minVal.toLocaleString()} - €${maxVal.toLocaleString()}`;
            priceText.classList.add('text-blue-600');
            priceBtn.classList.add('border-blue-400');
            document.getElementById('filters-form').submit();
        });

        // Cancel button — reset slider to original values & close
        document.getElementById('price-cancel').addEventListener('click', () => {
            // Reset to whatever the page loaded with
            minEl.value = {{ max(1000, (int)($filters['min_price'] ?? 1000)) }};
            maxEl.value = {{ min(20000, (int)($filters['max_price'] ?? 20000)) }};
            update('min');
            priceMenu.classList.add('hidden');
        });
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
    // 8. Search Input Enter Key
    const searchInput = document.getElementById('search-q');
    if (searchInput) {
        searchInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchInput.form.submit();
            }
        });
    }

    // 9. Mobile Filters Logic
    (function initMobileFilters() {
        const trigger = document.getElementById('mobile-filter-trigger');
        const modal = document.getElementById('mobile-filters-modal');
        const closeBtn = document.getElementById('close-mobile-filters');
        const cancelBtn = document.getElementById('clear-mobile-filters');
        const applyBtn = document.getElementById('apply-mobile-filters');
        const form = document.getElementById('mobile-filters-form');

        if (!trigger || !modal) return;

        trigger.addEventListener('click', () => {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden'; // Prevent scroll
            refreshIcons();
        });

        const hideModal = () => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        };

        [closeBtn, cancelBtn].forEach(btn => btn?.addEventListener('click', hideModal));

        // Type Tabs
        const typeTabs = document.querySelectorAll('.mobile-type-tab');
        const typeHidden = document.getElementById('mobile-type-hidden');
        typeTabs.forEach(tab => {
            tab.addEventListener('click', () => {
                typeTabs.forEach(t => {
                    t.classList.remove('bg-white', 'shadow-md', 'text-blue-600', 'bg-blue-900', 'text-white');
                    t.classList.add('text-gray-500');
                });
                
                const val = tab.dataset.value;
                typeHidden.value = val;
                
                if (val === 'remote') {
                    tab.classList.add('bg-blue-900', 'text-white', 'shadow-md');
                    tab.classList.remove('text-gray-500');
                } else {
                    tab.classList.add('bg-white', 'shadow-md', 'text-blue-600');
                    tab.classList.remove('text-gray-500');
                }
            });
        });

        // Price Slider (Mobile)
        const minEl = document.getElementById('mobile-price-min');
        const maxEl = document.getElementById('mobile-price-max');
        const track = document.getElementById('mobile-price-track');
        const priceText = document.getElementById('mobile-price-text');
        
        if (minEl && maxEl && track) {
            const RANGE_MIN = 1000;
            const RANGE_MAX = 20000;
            const GAP = 100;

            function updatePrice(source) {
                let minVal = parseInt(minEl.value);
                let maxVal = parseInt(maxEl.value);

                if (minVal > maxVal - GAP) {
                    if (source === 'min') {
                        minVal = maxVal - GAP;
                        minEl.value = minVal;
                    } else {
                        maxVal = minVal + GAP;
                        maxEl.value = maxVal;
                    }
                }

                const pMin = ((minVal - RANGE_MIN) / (RANGE_MAX - RANGE_MIN)) * 100;
                const pMax = ((maxVal - RANGE_MIN) / (RANGE_MAX - RANGE_MIN)) * 100;
                
                track.style.left = pMin + '%';
                track.style.right = (100 - pMax) + '%';
                track.style.width = 'auto';
                
                if (priceText) priceText.textContent = `€${minVal.toLocaleString()} - €${maxVal.toLocaleString()}`;
            }

            minEl.addEventListener('input', () => updatePrice('min'));
            maxEl.addEventListener('input', () => updatePrice('max'));
            updatePrice('min');
        }

        // City Search (Mobile)
        const cityInput = document.getElementById('mobile-city-search-input');
        const cityResults = document.getElementById('mobile-city-results');
        const cityHidden = document.getElementById('mobile-city-hidden');
        let mobileSearchTimeout;

        if (cityInput) {
            cityInput.addEventListener('input', (e) => {
                clearTimeout(mobileSearchTimeout);
                const q = e.target.value;
                if (q.length < 2) { cityResults.classList.add('hidden'); return; }

                mobileSearchTimeout = setTimeout(async () => {
                    try {
                        const res = await fetch(`/api/cities?q=${q}`);
                        const cities = await res.json();
                        cityResults.innerHTML = '';
                        if (cities.length) {
                            cityResults.classList.remove('hidden');
                            cities.slice(0, 8).forEach(c => {
                                const div = document.createElement('div');
                                div.className = 'px-5 py-4 text-[14px] font-medium hover:bg-blue-50 cursor-pointer text-gray-700 border-b border-gray-50 last:border-0';
                                div.innerHTML = `<i data-feather="map-pin" class="w-3.5 h-3.5 inline mr-2 text-gray-400"></i> ${c.name}`;
                                div.onclick = () => {
                                    cityInput.value = c.name;
                                    cityHidden.value = c.name;
                                    cityResults.classList.add('hidden');
                                    refreshIcons();
                                };
                                cityResults.appendChild(div);
                            });
                            refreshIcons();
                        } else {
                            cityResults.classList.add('hidden');
                        }
                    } catch (err) {}
                }, 300);
            });
        }

        applyBtn.addEventListener('click', () => {
            form.submit();
        });
    })();
  </script>

  <!-- Include Report Modal -->
  @include('components.report-modal')

  @endsection