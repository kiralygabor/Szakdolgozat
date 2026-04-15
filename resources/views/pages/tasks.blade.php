@extends('layout')

@section('title', __('tasks_page.title') ?? 'Browse Tasks')

@push('styles')
    <link href="https://unpkg.com/maplibre-gl@3.6.1/dist/maplibre-gl.css" rel="stylesheet" />
    <link href="{{ asset('css/pages/tasks.css') }}" rel="stylesheet">
@endpush

@section('content')
  @php
    $source = ($tasks instanceof \Illuminate\Pagination\AbstractPaginator) ? $tasks->items() : ($tasks ?? []);
    
    // Sort logic: My Tasks (2) > Tasks I've offered on (1) > Others (0)
    $sortedSource = collect($source)->sortByDesc(function($task) {
        if (!Auth::check()) return 0;
        if ((int)$task->employer_id === Auth::id()) return 2;
        if ($task->offers && $task->offers->contains('user_id', Auth::id())) return 1;
        return 0;
    })->values()->all();

    $remoteCount = 0;
    
    $taskPoints = collect($sortedSource)->map(function($t) use (&$remoteCount) {
      $loc = (string)($t->location ?? $t->employer->city->name ?? '');
      // Detect online/remote tasks
      $isRemote = $t->task_type === 'online' || 
                  stripos($loc, 'remote') !== false || 
                  stripos($loc, 'online') !== false;
      
      if ($isRemote) {
          $remoteCount++;
      }

      return [ 
          'id' => $t->id ?? null, 
          'title' => $t->title ?? '', 
          'price' => (int)($t->price ?? 0), 
          'location' => $loc,
          'is_remote' => $isRemote,
          'is_my_task' => Auth::check() && $t->employer_id === Auth::id()
      ];
    })->filter(fn($r) => $r['id'] && $r['location'] && !$r['is_remote'])->values();

    $missingSteps = auth()->check() ? auth()->user()->getMissingProfileSteps() : [];
  @endphp

  <!-- FILTERS NAVBAR -->
  <section class="bg-gray-50 z-20 relative pt-4">
    <div class="max-w-7xl mx-auto px-4 md:px-6">
      <div class="bg-white border border-gray-200 rounded-2xl shadow-sm">
        
        <!-- Desktop Form (Hidden on mobile) -->
        <form method="GET" action="{{ route('tasks') }}" id="filters-form"
              class="flex items-center gap-4 px-6 py-2.5 hidden md:flex">

          <!-- Search Bar -->
          <div class="relative flex-grow max-w-md group border-r border-gray-100 pr-6 mr-2 flex items-center">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <i data-feather="search" class="h-4 w-4 text-gray-500 group-focus-within:text-blue-500" aria-hidden="true"></i>
            </div>
            <input
              id="search-q"
              name="q"
              value="{{ $filters['q'] ?? '' }}"
              type="text"
              placeholder="{{ __('tasks_page.search_placeholder') }}"
              aria-label="{{ __('tasks_page.search_placeholder') }}"
              class="w-full pl-10 pr-12 py-2 rounded-full bg-gray-100 border-transparent focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 text-sm transition-all outline-none"
              autocomplete="off"
            >
            <button type="submit" class="absolute right-7 py-2 inset-y-1 px-3 flex items-center justify-center bg-blue-600 text-white rounded-full hover:bg-blue-700 transition-colors shadow-sm" aria-label="Search">
               <i data-feather="search" class="w-3.5 h-3.5"></i>
            </button>
            <input type="hidden" name="city_search" id="city-search-hidden" value="{{ $filters['city_search'] ?? '' }}">
          </div>

          <!-- Filters -->
          <div class="flex items-center gap-3">

            <!-- Category -->
            <div class="relative">
                <select name="category" id="category-filter"
                        aria-label="Select Category"
                        class="appearance-none pl-3 pr-8 py-2 rounded-lg border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:ring-2 focus:ring-blue-100 outline-none cursor-pointer transition-all">
                  <option value="">{{ __('tasks_page.all_categories') }}</option>
                  @foreach(collect($categories ?? []) as $cat)
                    <option value="{{ $cat->id ?? '' }}" @selected(($filters['category'] ?? '') == ($cat->id ?? ''))>
                      {{ __('categories.' . ($cat->name ?? '')) }}
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
                        aria-label="Select Service"
                        class="appearance-none pl-3 pr-8 py-2 rounded-lg border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:ring-2 focus:ring-blue-100 outline-none cursor-pointer transition-all">
                  <option value="">{{ __('tasks_page.all_services') }}</option>
                  @if($filters['category'] ?? '')
                    @foreach($selectedCategoryJobs ?? [] as $job)
                        @if(is_object($job))
                            <option value="{{ $job->id }}" @selected(($filters['job'] ?? '') == $job->id)>
                                {{ __('jobs.' . $job->name) }}
                            </option>
                        @endif
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
                      aria-haspopup="true" aria-expanded="false" aria-controls="type-menu"
                      class="min-w-[120px] justify-between px-3 py-2 rounded-lg border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:border-blue-400 hover:text-blue-600 flex items-center gap-2 transition-all">
                <i data-feather="briefcase" class="w-3.5 h-3.5 text-gray-500" aria-hidden="true"></i>
                <span id="type-text">{{ __('tasks_page.type_btn') }}</span>
                <i data-feather="chevron-down" class="w-3.5 h-3.5 ml-1 text-gray-400" aria-hidden="true"></i>
              </button>

              <div id="type-menu" class="absolute mt-3 right-0 bg-white border border-gray-100 rounded-xl shadow-xl p-4 w-64 hidden z-50">
                 <div class="mb-3">
                   <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">{{ __('tasks_page.location_label') }}</label>
                   <input id="type-city-search" type="text" placeholder="{{ __('tasks_page.search_city_placeholder') }}"
                       class="w-full px-3 py-2 rounded-lg bg-gray-50 border border-gray-200 text-sm focus:border-blue-500 outline-none">
                   <div id="type-city-dropdown" class="mt-1 max-h-40 overflow-y-auto hidden border rounded-lg shadow-inner bg-white"></div>
                 </div>
                  <div class="mb-3">
                    <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">{{ __('tasks_page.mode_label') }}</label>
                    <div class="space-y-1">
                      <label class="flex items-center p-1.5 rounded hover:bg-gray-50 cursor-pointer">
                        <input type="radio" name="type" value="all" class="text-blue-600" @checked(($filters['type'] ?? 'all') === 'all')>
                        <span class="text-sm ml-2 text-gray-700">{{ __('tasks_page.mode_any') }}</span>
                      </label>
                      <label class="flex items-center p-1.5 rounded hover:bg-gray-50 cursor-pointer">
                        <input type="radio" name="type" value="in_person" class="text-blue-600" @checked(($filters['type'] ?? '') === 'in_person')>
                        <span class="text-sm ml-2 text-gray-700">{{ __('tasks_page.mode_in_person') }}</span>
                      </label>
                      <label class="flex items-center p-1.5 rounded hover:bg-gray-50 cursor-pointer">
                        <input type="radio" name="type" value="remote" class="text-blue-600" @checked(($filters['type'] ?? '') === 'remote')>
                        <span class="text-sm ml-2 text-gray-700">{{ __('tasks_page.mode_remote') }}</span>
                      </label>
                    </div>
                  </div>
                 <div class="flex justify-end gap-2 pt-2 border-t">
                   <button type="button" id="type-apply" class="w-full py-1.5 text-xs font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">{{ __('tasks_page.apply_filter') }}</button>
                 </div>
              </div>
            </div>

            <!-- Price -->
            <div class="relative">
              <button type="button" id="price-btn"
                      aria-haspopup="true" aria-expanded="false" aria-controls="price-menu"
                      class="min-w-[120px] justify-between px-3 py-2 rounded-lg border {{ (isset($filters['min_price']) || isset($filters['max_price']) && ($filters['min_price'] != 5 || $filters['max_price'] != 5000)) ? 'border-blue-400' : 'border-gray-300' }} bg-white text-sm font-medium text-gray-700 hover:border-blue-400 hover:text-blue-600 flex items-center gap-2 transition-all">
                <i data-feather="dollar-sign" class="w-3.5 h-3.5 text-gray-500" aria-hidden="true"></i>
                <span id="price-text" class="{{ (isset($filters['min_price']) || isset($filters['max_price']) && ($filters['min_price'] != 5 || $filters['max_price'] != 5000)) ? 'text-blue-700' : '' }}">
                  @if(isset($filters['min_price']) || isset($filters['max_price']) && ($filters['min_price'] != 5 || $filters['max_price'] != 5000))
                    €{{ number_format((int)($filters['min_price'] ?? 5), 0, '.', ',') }} - €{{ number_format((int)($filters['max_price'] ?? 5000), 0, '.', ',') }}
                  @else
                    {{ __('tasks_page.price_btn') }}
                  @endif
                </span>
                <i data-feather="chevron-down" class="w-3.5 h-3.5 ml-1 text-gray-400" aria-hidden="true"></i>
              </button>

              <div id="price-menu" class="absolute mt-2 right-0 bg-white border border-gray-200 rounded-lg shadow-lg hidden z-50" style="width: 340px; padding: 16px 20px 12px;">
                {{-- Header --}}
                <label class="block text-[11px] font-semibold text-gray-400 uppercase tracking-wide mb-2" style="letter-spacing:.05em;">{{ __('tasks_page.budget_range') }}</label>

                {{-- Price display box --}}
                <div class="border border-gray-300 rounded-md px-3 py-2 mb-5 text-center bg-gray-50">
                  <span id="price-display" class="text-[15px] font-semibold text-gray-800">
                    €{{ number_format((int)($filters['min_price'] ?? 5), 0, '.', ',') }} - €{{ number_format((int)($filters['max_price'] ?? 5000), 0, '.', ',') }}
                  </span>
                </div>

                {{-- Slider --}}
                <div class="range-slider" style="margin-bottom: 20px;">
                   <div class="track-bg relative h-1.5 bg-gray-200 rounded-full w-full top-1/2 -translate-y-1/2"></div>
                   <div id="price-track" class="track-fill absolute h-1.5 bg-blue-600 rounded-full top-1/2 -translate-y-1/2 pointer-events-none"></div>
                   <input id="price-min" name="min_price" type="range" min="5" max="5000" step="5" class="absolute w-full top-0 appearance-none bg-transparent pointer-events-none" style="z-index:3"
                          value="{{ max(5, (int)($filters['min_price'] ?? 5)) }}">
                   <input id="price-max" name="max_price" type="range" min="5" max="5000" step="5" class="absolute w-full top-0 appearance-none bg-transparent pointer-events-none" style="z-index:4"
                          value="{{ min(5000, (int)($filters['max_price'] ?? 5000)) }}">
                </div>

                {{-- Buttons --}}
                <div class="flex items-center justify-between gap-3 pt-3 border-t border-gray-100">
                  <button type="button" id="price-cancel" class="flex-1 py-1.5 text-sm font-semibold text-gray-600 hover:text-gray-800 rounded-md border border-gray-300 hover:bg-gray-50 transition-colors">{{ __('tasks_page.cancel') }}</button>
                  <button type="button" id="price-apply" class="flex-1 py-1.5 text-sm font-bold text-white bg-blue-600 rounded-md hover:bg-blue-700 transition-colors">{{ __('tasks_page.apply_price') }}</button>
                </div>
              </div>
            </div>

            <!-- Sort -->
            <div class="hidden lg:block h-8 w-px bg-gray-300 mx-4" aria-hidden="true"></div>
            <select name="sort" id="sort-filter" aria-label="Sort tasks by" class="bg-transparent py-2 text-sm font-medium text-gray-700 hover:text-gray-900 cursor-pointer outline-none">
                <option value="recent" @selected(($filters['sort'] ?? 'recent')==='recent')>{{ __('tasks_page.sort_recent') }}</option>
                <option value="closest" @selected(($filters['sort'] ?? '')==='closest')>{{ __('tasks_page.sort_closest') }}</option>
                <option value="due" @selected(($filters['sort'] ?? '')==='due')>{{ __('tasks_page.sort_due') }}</option>
                <option value="lowest_price" @selected(($filters['sort'] ?? '')==='lowest_price')>{{ __('tasks_page.sort_price_asc') }}</option>
                <option value="highest_price" @selected(($filters['sort'] ?? '')==='highest_price')>{{ __('tasks_page.sort_price_desc') }}</option>
            </select>
          </div>
        </form>

        <!-- Mobile Filter Trigger (Visible only on mobile) -->
        <div class="md:hidden px-4 py-3 bg-white">
            <button type="button" id="mobile-filter-trigger" 
                    aria-label="Open search and filters" aria-haspopup="dialog"
                    class="w-full h-11 flex items-center justify-between border border-gray-200 rounded-full px-5 bg-gray-50 hover:bg-gray-100 transition-colors cursor-pointer text-sm shadow-sm">
                <div class="flex items-center gap-3 overflow-hidden">
                    <i data-feather="search" class="w-4 h-4 text-blue-600"></i>
                    <div class="flex flex-col items-start overflow-hidden">
                        <span class="font-bold text-gray-900 text-[13px] leading-tight truncate">
                             @if($filters['q'] ?? '') 
                                "{{ $filters['q'] }}" 
                            @else 
                                {{ __('tasks_page.browse_everything') }}
                            @endif
                        </span>
                        <span class="text-[11px] text-gray-500 leading-tight">
                            @if($filters['type'] ?? '')
                                {{ $filters['type'] === 'remote' ? __('tasks_page.mode_remote') : __('tasks_page.mode_in_person') }}
                            @else
                                {{ __('tasks_page.any_mode') }}
                            @endif
                            ·
                            @if($filters['category'] ?? '')
                                {{ collect($categories ?? [])->firstWhere('id', $filters['category'])?->name ?? __('tasks_page.all_categories') }}
                            @else
                                {{ __('tasks_page.all_categories') }}
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
          <h2 class="text-base font-bold text-gray-800">{{ __('tasks_page.filters') }}</h2>
          <button type="button" id="close-mobile-filters" class="p-2 -mr-2 bg-gray-50 rounded-full text-gray-400 border border-gray-200">
              <i data-feather="x" class="w-4 h-4"></i>
          </button>
      </div>

      <!-- Modal Content -->
      <form id="mobile-filters-form" method="GET" action="{{ route('tasks') }}" class="flex-1 overflow-y-auto px-6 pt-5 pb-6 space-y-7 custom-scroll">
          
          <!-- Task Name Search -->
          <div>
              <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">{{ __('tasks_page.task_name') }}</label>
              <div class="relative">
                  <input type="text" name="q" placeholder="{{ __('tasks_page.search_placeholder') }}" value="{{ $filters['q'] ?? '' }}" 
                         class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-semibold text-gray-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-50 transition-all outline-none">
                  <div class="absolute right-4 top-1/2 -translate-y-1/2">
                      <i data-feather="search" class="w-4 h-4 text-gray-400"></i>
                  </div>
              </div>
          </div>
          
          <!-- Category -->
          <div>
              <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">{{ __('tasks_page.category') }}</label>
              <div class="relative">
                  <select name="category" id="mobile-category" class="w-full appearance-none bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-semibold text-gray-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-50 transition-all outline-none">
                      <option value="">{{ __('tasks_page.all_categories') }}</option>
                      @foreach(collect($categories ?? []) as $cat)
                          <option value="{{ $cat->id ?? '' }}" @selected(($filters['category'] ?? '') == ($cat->id ?? ''))>
                              {{ __('categories.' . ($cat->name ?? '')) }}
                          </option>
                      @endforeach
                  </select>
                  <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none">
                      <i data-feather="chevron-down" class="w-4 h-4 text-gray-400"></i>
                  </div>
              </div>
          </div>

          <!-- Mode -->
          <div>
              <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">{{ __('tasks_page.to_be_done') }}</label>
              <div class="flex p-1.5 bg-gray-100 rounded-xl border border-gray-200">
                  <button type="button" class="mobile-type-tab flex-1 py-2 text-xs font-bold rounded-lg transition-all {{ ($filters['type'] ?? 'all') === 'in_person' ? 'bg-white shadow-sm text-blue-600' : 'text-gray-500' }}" data-value="in_person">{{ __('tasks_page.mode_in_person') }}</button>
                  <button type="button" class="mobile-type-tab flex-1 py-2 text-xs font-bold rounded-lg transition-all {{ ($filters['type'] ?? 'all') === 'remote' ? 'bg-blue-600 text-white shadow-sm' : 'text-gray-500' }}" data-value="remote">{{ __('tasks_page.mode_remote') }}</button>
                  <button type="button" class="mobile-type-tab flex-1 py-2 text-xs font-bold rounded-lg transition-all {{ ($filters['type'] ?? 'all') === 'all' ? 'bg-white shadow-sm text-blue-600' : 'text-gray-500' }}" data-value="all">{{ __('tasks_page.mode_any') }}</button>
              </div>
              <input type="hidden" name="type" id="mobile-type-hidden" value="{{ $filters['type'] ?? 'all' }}">
          </div>

          <!-- Price -->
          <div>
              <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">{{ __('tasks_page.price_label') }}</label>
              <div class="text-center mb-6">
                  <span id="mobile-price-text" class="text-sm font-extrabold text-blue-600 bg-blue-50 px-4 py-2 rounded-full border border-blue-200">
                      €{{ number_format((int)($filters['min_price'] ?? 5)) }} - €{{ number_format((int)($filters['max_price'] ?? 5000)) }}
                  </span>
              </div>
              <div class="px-2">
                  <div class="range-slider relative">
                      <div class="track-bg relative h-1.5 bg-gray-200 rounded-full w-full top-1/2 -translate-y-1/2"></div>
                      <div id="mobile-price-track" class="track-fill absolute h-1.5 bg-blue-600 rounded-full top-1/2 -translate-y-1/2 pointer-events-none"></div>
                      <input id="mobile-price-min" name="min_price" type="range" class="absolute w-full top-0 appearance-none bg-transparent pointer-events-none" style="z-index:3" min="5" max="5000" step="5" value="{{ $filters['min_price'] ?? 5 }}">
                      <input id="mobile-price-max" name="max_price" type="range" class="absolute w-full top-0 appearance-none bg-transparent pointer-events-none" style="z-index:4" min="5" max="5000" step="5" value="{{ $filters['max_price'] ?? 5000 }}">
                  </div>
              </div>
          </div>
      </form>

      <!-- Modal Footer -->
      <div class="p-4 border-t border-gray-100 flex gap-3 bg-white">
          <button type="button" id="clear-mobile-filters" class="flex-1 py-3 text-sm font-bold text-gray-600 bg-gray-50 hover:bg-gray-100 border border-gray-300 rounded-xl transition-all">{{ __('tasks_page.cancel') }}</button>
          <button type="button" id="apply-mobile-filters" class="flex-1 py-3 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-md shadow-blue-100 transition-all">{{ __('tasks_page.apply_filter') }}</button>
      </div>
  </div>

  <!-- Main Content -->
 <section class="bg-gray-50 pt-6 md:pt-10 h-auto overflow-hidden">
   <div class="flex flex-col md:flex-row max-w-7xl mx-auto px-4 md:px-6 gap-4 md:gap-6 pb-6" style="height: 750px;">
     
      <!-- Left: Tasks Pane -->
     <div id="tasks-pane" class="flex flex-col w-full md:w-[360px] shrink-0 h-full">
        <div class="flex-1 overflow-y-auto custom-scroll pr-2 space-y-3 pb-8">
          @forelse ($sortedSource as $task)
            @php
                $isMyTask = Auth::check() && $task->employer_id === Auth::id();
                $hasOffer = Auth::check() && $task->offers->contains('user_id', Auth::id());
                
                $cardClasses = 'bg-white border-gray-200';
                if ($isMyTask) {
                    $cardClasses = 'bg-violet-50 border-violet-300 ring-2 ring-violet-100 ring-inset';
                } elseif ($hasOffer) {
                    $cardClasses = 'bg-blue-50 border-blue-300';
                }
            @endphp
            <div id="task-card-{{ $task->id }}" class="group task-card p-4 rounded-xl border hover:border-blue-400 hover:shadow-md transition-all duration-200 relative {{ $cardClasses }}" data-task-id="{{ $task->id }}">
             
              {{-- Status Badges (Top Row) --}}
              @if($isMyTask || $hasOffer)
                <div class="flex flex-wrap items-center gap-2 mb-1.5">
                    @if($isMyTask)
                        <span class="inline-flex items-center justify-center bg-violet-100 text-violet-700 rounded-full px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider title="{{ __('tasks_page.my_task') }}">
                            {{ __('tasks_page.my_task') }}
                        </span>
                    @endif
                    @if($hasOffer)
                        <span class="inline-flex items-center justify-center bg-blue-100 text-blue-600 rounded-full p-1" title="You made an offer">
                            <i data-feather="check-circle" class="w-3 h-3"></i>
                        </span>
                    @endif
                </div>
              @endif

              {{-- Title and Price Row --}}
              <div class="flex justify-between items-start mb-2 gap-4">
                <h3 class="text-sm font-bold text-gray-800 leading-tight group-hover:text-blue-600 transition-colors flex-1 ml-2">
                    {{ $task->title }}
                </h3>
                <span class="text-green-600 text-sm font-bold whitespace-nowrap mr-1">
                   €{{ number_format($task->price, 0) }}
                </span>
              </div>
 
              <p class="text-gray-500 text-xs mb-3 line-clamp-2 leading-relaxed ml-2">
                  {{ $task->description }}
              </p>
 
               <div class="flex flex-wrap gap-1.5 mb-3">
                  <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-gray-100 text-gray-600 text-[10px] font-semibold uppercase tracking-wide">
                     {{ $task->category ? __('categories.' . $task->category->name) : __('tasks_page.general') }}
                  </span>
                  <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-gray-100 text-gray-600 text-[10px] font-medium">
                     <i data-feather="map-pin" class="w-3 h-3 text-gray-400"></i> {{ $task->location === 'Remote' ? __('tasks_page.remote') : $task->location }}
                  </span>
               </div>
 
              <div class="pt-3 border-t border-gray-100 flex justify-between items-center mt-auto">
                 <div class="flex items-center gap-2">
                     <a href="{{ route('public-profile', $task->employer_id) }}" class="shrink-0 group/avatar" title="{{ $task->employer->first_name ?? 'User' }}">
                         <img src="{{ $task->employer->avatar_url ?? '' }}" alt="{{ $task->employer->first_name ?? 'User' }}" class="w-7 h-7 rounded-full object-cover border-2 border-gray-100 shadow-sm group-hover/avatar:border-blue-400 group-hover/avatar:shadow-md transition-all duration-200">
                     </a>
                     <span class="text-[10px] text-gray-400 font-medium">
                        {{ $task->created_at?->diffForHumans() }}
                     </span>
                 </div>
                  <div class="flex items-center gap-2">
                      @auth
                          @if(!$isMyTask)
                            <button type="button" onclick="event.stopPropagation(); openReportModal({{ $task->id }}, {{ $task->employer_id }})" class="relative z-20 text-xs font-semibold text-gray-400 hover:text-red-600 transition-colors p-1.5 rounded-full hover:bg-red-50" aria-label="Report this task" title="Report this task">
                                <i data-feather="flag" class="w-3.5 h-3.5"></i>
                            </button>
                          @endif
                      @endauth
                      @guest
                          <a href="{{ route('login', ['returnUrl' => route('tasks.show', $task->id)]) }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800 underline-offset-2 hover:underline transition-all">
                             {{ __('tasks_page.signin_to_offer') ?? 'Sign in to offer' }}
                          </a>
                      @else
                          @if($isMyTask)
                              <a href="{{ route('my-tasks') }}#task-{{ $task->id }}" class="text-xs font-semibold text-violet-700 bg-violet-100 hover:bg-violet-200 border border-transparent px-3 py-1.5 rounded-full transition-colors flex items-center gap-1 shadow-sm hover:shadow">
                                  <i data-feather="eye" class="w-3 h-3"></i> {{ __('tasks_page.view_details') ?? 'Details' }}
                              </a>
                          @elseif($hasOffer)
                             <form method="POST" action="{{ route('tasks.offers.destroy', $task->id) }}" class="inline m-0 p-0">
                                 @csrf
                                 @method('DELETE')
                                 <button type="submit" class="text-xs font-semibold text-red-700 bg-red-100 hover:bg-red-600 hover:text-white border border-transparent hover:border-red-600 px-3 py-1.5 rounded-full transition-all whitespace-nowrap inline-flex items-center gap-1 shadow-sm hover:shadow">
                                     <i data-feather="x" class="w-3 h-3"></i> {{ __('tasks_page.cancel_offer') ?? 'Cancel Offer' }}
                                 </button>
                             </form>
                          @else
                              @if(count($missingSteps) > 0)
                                 <button type="button" class="js-open-offer-requirements text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 px-4 py-1.5 rounded-full transition-all shadow-sm hover:shadow">
                                     {{ __('tasks_page.make_offer') ?? 'Make offer' }}
                                 </button>
                              @else
                                 <a href="{{ route('tasks.show', $task->id) }}" class="text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 px-4 py-1.5 rounded-full transition-all shadow-sm hover:shadow inline-block">
                                     {{ __('tasks_page.make_offer') ?? 'Make offer' }}
                                 </a>
                              @endif
                          @endif
                      @endguest
                  </div>
              </div>
            </div>
          @empty
            <div class="h-full bg-white rounded-2xl border border-dashed border-gray-300 p-10 text-center flex flex-col items-center justify-center space-y-4 shadow-sm tasks-empty-container">
              <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mb-2 tasks-empty-icon">
                 <i data-feather="search" class="w-8 h-8 text-blue-400"></i>
              </div>
              <div class="space-y-2">
                <h3 class="text-lg font-bold text-gray-800">{{ __('tasks_page.no_tasks_found') }}</h3>
                <p class="text-sm text-gray-500 leading-relaxed max-w-xs mx-auto">
                  {{ __('tasks_page.no_tasks_desc') }}
                </p>
              </div>
              <a href="{{ route('tasks') }}" class="tasks-clear-btn inline-flex items-center gap-2 text-sm font-bold text-blue-600 hover:bg-blue-700 hover:text-white bg-blue-50 px-6 py-2.5 rounded-full transition-all mt-4 border border-blue-100 shadow-sm">
                <i data-feather="refresh-cw" class="w-4 h-4"></i>
                {{ __('tasks_page.clear_filters') }}
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
      <div class="hidden md:flex flex-1 bg-gray-200 rounded-2xl overflow-hidden shadow-inner border border-gray-300 relative group">
        <div id="map"></div>
        
        <!-- Remote Tasks Pulsing Icon (Top Right) -->
        @if($remoteCount > 0)
            <div class="absolute top-4 right-4 z-30">
                <div class="relative">
                    <button type="button" 
                            id="cloud-toggle-btn"
                            onclick="toggleRemoteInfo()"
                            class="w-11 h-11 bg-gradient-to-br from-white to-blue-50 shadow-xl shadow-blue-900/10 border border-blue-100 rounded-full flex items-center justify-center text-blue-600 transition-all duration-300 relative animate-cloud-pulse cursor-pointer hover:scale-110 active:scale-95 group/cloud">
                        <i data-feather="cloud" class="w-5 h-5 group-hover/cloud:fill-blue-600 transition-colors"></i>
                        <div class="absolute -top-1 -right-1 bg-red-500 text-white text-[9px] font-black w-4 h-4 rounded-full flex items-center justify-center border-2 border-white shadow-sm pointer-events-none">
                            {{ $remoteCount }}
                        </div>
                    </button>

                    <div id="remote-info-pop" class="hidden absolute right-0 top-full mt-2 z-40 animate-fade-in-up">
                        <div class="bg-white border border-gray-100 text-gray-800 rounded-xl shadow-xl p-4 min-w-[220px] border-t-4 border-t-blue-500">
                           <div class="flex items-center gap-2 mb-2">
                               <i data-feather="info" class="w-3.5 h-3.5 text-blue-500"></i>
                               <div class="text-[10px] font-black text-blue-500 uppercase tracking-widest">{{ __('tasks_page.digital_opps') ?? 'Digital Opps' }}</div>
                           </div>
                           <p class="text-[11px] font-medium leading-relaxed text-gray-600">
                               {!! __('tasks_page.remote_tasks_info', ['count' => '<span class="text-blue-600 font-bold">' . $remoteCount . '</span>']) ?? 'There are <span class="text-blue-600 font-bold">' . $remoteCount . '</span> remote tasks available.' !!}
                           </p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="pointer-events-none absolute inset-x-0 top-0 h-6 bg-gradient-to-b from-black/5 to-transparent z-10"></div>
      </div>
 
    </div>
  </section>

  <!-- Modals -->
  @include('partials.profile-steps-modal')
  @include('components.report-modal')

  @push('scripts')
  <script src="https://unpkg.com/maplibre-gl@3.6.1/dist/maplibre-gl.js"></script>
  <script>
    // Pass original values matching what tasks.js needs
    window.TASKS_CONFIG = {
        taskPoints: @json($taskPoints),
        translations: {
            myTask: '{{ __("tasks_page.my_task") }}',
            viewDetails: '{{ __("tasks_page.view_details") }}'
        },
        urls: {
            login: '{{ route("login") }}'
        },
        filters: {
            min_price: {{ max(5, (int)($filters['min_price'] ?? 5)) }},
            max_price: {{ min(5000, (int)($filters['max_price'] ?? 5000)) }}
        }
    };
  </script>
  <script type="module">
      import { TaskReportManager } from '{{ asset('js/components/task-report-manager.js') }}';
      document.addEventListener('DOMContentLoaded', () => {
          new TaskReportManager();
      });
  </script>
  <script src="{{ asset('js/pages/tasks.js') }}"></script>
  @endpush
@endsection