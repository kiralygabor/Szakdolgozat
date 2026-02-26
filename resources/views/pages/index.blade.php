@extends('layout')

@section('content')
<style>
  /* --- ANIMATIONS --- */
  @keyframes scrollUp {
    0% { transform: translateY(0); }
    100% { transform: translateY(-50%); }
  }
  @keyframes scrollDown {
    0% { transform: translateY(-50%); }
    100% { transform: translateY(0); }
  }
  .animate-scroll-up .task-column { animation: scrollUp 40s linear infinite; }
  .animate-scroll-down .task-column { animation: scrollDown 40s linear infinite; }
  .task-column { display: flex; flex-direction: column; gap: 1rem; }

  @keyframes scroll-left { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }
  @keyframes scroll-right { 0% { transform: translateX(-50%); } 100% { transform: translateX(0); } }
  .animate-scroll-left { animation: scroll-left 40s linear infinite; }
  .animate-scroll-right { animation: scroll-right 40s linear infinite; }
  
  /* Pause animation on hover (optional) */
  .group:hover .animate-scroll-left,
  .group:hover .animate-scroll-right {
    animation-play-state: paused;
  }
  
  /* Edge fade mask */
  .scroll-mask {
    mask-image: linear-gradient(to right, transparent, black 5%, black 95%, transparent);
    -webkit-mask-image: linear-gradient(to right, transparent, black 5%, black 95%, transparent);
  }

  /* --- GLOBAL DARK MODE CONFIGURATION --- */
  .theme-toggle { position:fixed; right:1rem; top:1rem; z-index:1000; }
  
  /* Dark Mode Base Colors */
  .dark body, .dark { background-color: #0f172a; color: #f1f5f9; }
  
  /* Background Overrides for consistency */
  .dark .bg-white { background-color: #1e293b !important; border-color: #334155 !important; }
  .dark .bg-gray-50 { background-color: #0f172a !important; }
  .dark .bg-slate-50 { background-color: #0b1220 !important; } /* Deep black for contrast sections */
  .dark .bg-blue-50 { background-color: #172554 !important; }
  
  /* Text Color Overrides */
  .dark .text-gray-900 { color: #f8fafc !important; }
  .dark .text-gray-800 { color: #f1f5f9 !important; }
  .dark .text-gray-700 { color: #cbd5e1 !important; }
  .dark .text-gray-600 { color: #94a3b8 !important; }
  .dark .text-gray-500 { color: #64748b !important; }
  
  /* Border & Shadow Adjustments */
  .dark .border-gray-200 { border-color: #334155 !important; }
  .dark .shadow-sm, .dark .shadow, .dark .shadow-md, .dark .shadow-lg { 
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.5), 0 2px 4px -1px rgba(0, 0, 0, 0.3) !important; 
  }
  
  /* Specific Section Fixes */
  .dark .group:hover .bg-white { background-color: #1e293b !important; }

  /* --- TESTIMONIAL SLIDER STYLES --- */
  .perspective-container {
    position: relative;
    overflow: hidden; /* Essential for sliding effects */
  }
  #testimonial-content {
    /* Initial State */
    opacity: 1;
    transform: translateX(0);
    will-change: transform, opacity;
  }
</style>

<!-- Hero Section -->
<section class="relative h-[90vh] flex items-center justify-center bg-gray-900 text-white overflow-hidden">
  <!-- Background Image -->
  <img
    src="https://images.unsplash.com/photo-1519389950473-47ba0277781c?auto=format&fit=crop&w=2000&q=80"
    alt="People working together"
    class="absolute inset-0 w-full h-full object-cover opacity-60"
  />
 
  <!-- Overlay -->
  <div class="absolute inset-0 bg-gradient-to-r from-gray-900/80 via-gray-900/50 to-transparent"></div>
 
  <!-- Content -->
  <div class="relative z-10 text-center max-w-3xl px-6">
    <h1 class="text-6xl md:text-7xl font-extrabold mb-6 leading-tight">
      @lang('index.hero_title')
    </h1>
    <p class="text-lg md:text-xl text-gray-200 mb-8">
      {{ __('index.hero_subtitle') }}
    </p>
    <div class="flex flex-col sm:flex-row gap-4 justify-center">
      <a href="{{ route('post-task') }}" class="px-8 py-4 bg-indigo-500 hover:bg-indigo-600 rounded-full text-lg font-semibold shadow-lg transition text-white">
        {{ __('index.post_task') }}
      </a>
      <a href="#browse-tasks" class="px-8 py-4 bg-white text-gray-900 hover:bg-gray-100 rounded-full text-lg font-semibold shadow-lg transition">
        {{ __('index.browse_tasks') }}
      </a>
    </div>
  </div>
</section>
 
<!-- How It Works - Vertical Timeline & Trending Showcase -->
<section class="py-24 px-6 bg-white dark:bg-slate-900 transition-colors duration-300 relative overflow-hidden">
  
  <!-- Background Pattern -->
  <div class="absolute top-0 right-0 w-[45%] h-full bg-slate-50 dark:bg-slate-800/20 -skew-x-12 translate-x-20 pointer-events-none border-l border-slate-100 dark:border-slate-800"></div>

  <div class="max-w-7xl mx-auto grid lg:grid-cols-2 gap-20 items-center relative z-10">
    
    <!-- Left Side: The Process (Timeline) - UNCHANGED -->
    <div class="pr-0 lg:pr-10">
      <h2 class="text-4xl md:text-5xl font-extrabold text-gray-900 dark:text-white mb-12 leading-tight">
        @lang('index.how_it_works_title')
      </h2>
      
      <div class="space-y-12 relative">
        <div class="absolute left-6 top-4 bottom-12 w-0.5 bg-gray-200 dark:bg-slate-700 -translate-x-1/2"></div>

        <div class="relative flex gap-8 group">
          <div class="relative z-10 w-12 h-12 shrink-0 flex items-center justify-center rounded-full bg-white dark:bg-slate-900 border-2 border-indigo-100 dark:border-slate-600 text-indigo-600 shadow-sm group-hover:border-indigo-600 group-hover:scale-110 transition-all duration-300">
            <span class="font-bold text-lg">1</span>
          </div>
          <div class="pt-1">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">{{ __('index.step_1_title') }}</h3>
            <p class="text-gray-600 dark:text-slate-400 leading-relaxed text-lg">
              {{ __('index.step_1_desc') }}
            </p>
          </div>
        </div>

        <div class="relative flex gap-8 group">
          <div class="relative z-10 w-12 h-12 shrink-0 flex items-center justify-center rounded-full bg-white dark:bg-slate-900 border-2 border-indigo-100 dark:border-slate-600 text-indigo-600 shadow-sm group-hover:border-indigo-600 group-hover:scale-110 transition-all duration-300">
            <span class="font-bold text-lg">2</span>
          </div>
          <div class="pt-1">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">{{ __('index.step_2_title') }}</h3>
            <p class="text-gray-600 dark:text-slate-400 leading-relaxed text-lg">
              {{ __('index.step_2_desc') }}
            </p>
          </div>
        </div>

        <div class="relative flex gap-8 group">
          <div class="relative z-10 w-12 h-12 shrink-0 flex items-center justify-center rounded-full bg-white dark:bg-slate-900 border-2 border-indigo-100 dark:border-slate-600 text-indigo-600 shadow-sm group-hover:border-indigo-600 group-hover:scale-110 transition-all duration-300">
            <span class="font-bold text-lg">3</span>
          </div>
          <div class="pt-1">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">{{ __('index.step_3_title') }}</h3>
            <p class="text-gray-600 dark:text-slate-400 leading-relaxed text-lg">
              {{ __('index.step_3_desc') }}
            </p>
          </div>
        </div>
      </div>

      <div class="mt-16">
        <a href="{{ route('post-task') }}" class="inline-flex items-center justify-center px-8 py-4 text-base font-semibold text-white transition-all duration-200 bg-indigo-600 rounded-full hover:bg-indigo-700 shadow-lg hover:-translate-y-1">
          {{ __('index.start_for_free') }}
        </a>
      </div>
    </div>

    <!-- Right side: Expanded Marketplace Window -->
    <div class="relative px-2 lg:px-0">
      
      <!-- Double Background (Expanded & Tilted) -->
      <div class="absolute -inset-6 bg-gradient-to-tr from-indigo-50 to-blue-50 dark:from-slate-800 dark:to-slate-800 rounded-[3rem] transform rotate-2 border border-gray-100 dark:border-slate-700/50"></div>
      
      <!-- Main Container -->
      <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-2xl border border-gray-200 dark:border-slate-700 relative overflow-hidden h-[750px] transform transition-transform hover:scale-[1.002] duration-500 flex flex-col">
        
        <!-- Header: Clean "Trending" State -->
        <div class="relative z-30 flex items-center justify-between px-8 py-6 border-b border-gray-100 dark:border-slate-800 bg-white/95 dark:bg-slate-900/95 backdrop-blur-xl">
            
            <!-- Title -->
            <div>
              <h3 class="text-2xl font-extrabold text-gray-900 dark:text-white tracking-tight leading-none">{{ __('index.explore') }}</h3>
              <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 font-medium">{{ __('index.browse_local_services') }}</p>
            </div>

            <!-- Trending Indicator (Visual Only - Not a Button) -->
            <div class="flex items-center gap-2 px-4 py-2 bg-indigo-50 dark:bg-indigo-500/10 rounded-xl border border-indigo-100 dark:border-indigo-500/20">
                <div class="p-1 bg-indigo-100 dark:bg-indigo-500 rounded-full text-indigo-600 dark:text-white">
                  <i data-feather="trending-up" class="w-3 h-3"></i>
                </div>
                <div class="flex flex-col">
   
                  <span class="text-xs font-bold text-indigo-900 dark:text-indigo-100 leading-none">{{ __('index.trending_now') }}</span>
                </div>
            </div>
        </div>

        <!-- Scrolling Grid Content -->
        <div class="grid grid-cols-2 gap-5 relative flex-1 overflow-hidden p-6 bg-gray-50/50 dark:bg-slate-900/50">
          
          <!-- The Fog (Stronger Fade Masks) -->
          <div class="absolute top-0 left-0 right-0 h-24 bg-gradient-to-b from-gray-50/90 dark:from-slate-900/90 to-transparent z-10 pointer-events-none"></div>
          <div class="absolute bottom-0 left-0 right-0 h-24 bg-gradient-to-t from-gray-50/90 dark:from-slate-900/90 to-transparent z-10 pointer-events-none"></div>

          <!-- Left Column (Scrolls Up) -->
          <div class="space-y-5 animate-scroll-up">
            <template id="task-cards-left">
              <!-- Card: Handyman -->
              <div class="relative aspect-[4/5] rounded-[1.25rem] overflow-hidden group border border-white dark:border-slate-700 shadow-sm">
                <img src="assets/img/handyman.jpg" alt="Handyman" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000">
                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent"></div>
                <div class="absolute bottom-5 left-5 text-white">
                  <h4 class="font-bold text-lg leading-none mb-1">Handyman</h4>
                  <p class="text-xs text-gray-300 font-medium">Repairs & Installs</p>
                </div>
              </div>
              
              <!-- Card: Plumbing -->
              <div class="relative aspect-[4/5] rounded-[1.25rem] overflow-hidden group border border-white dark:border-slate-700 shadow-sm">
                <img src="assets/img/plumbing.jpg" alt="Plumbing" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000">
                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent"></div>
                <div class="absolute bottom-5 left-5 text-white">
                  <h4 class="font-bold text-lg leading-none mb-1">Plumbing</h4>
                  <p class="text-xs text-gray-300 font-medium">Leaks & Drains</p>
                </div>
              </div>

              <!-- Card: Delivery -->
              <div class="relative aspect-[4/5] rounded-[1.25rem] overflow-hidden group border border-white dark:border-slate-700 shadow-sm">
                <img src="assets/img/delivery.jpg" alt="Delivery" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000">
                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent"></div>
                <div class="absolute bottom-5 left-5 text-white">
                  <h4 class="font-bold text-lg leading-none mb-1">Delivery</h4>
                  <p class="text-xs text-gray-300 font-medium">Parcels & Food</p>
                </div>
              </div>

              <!-- Card: Gardening -->
              <div class="relative aspect-[4/5] rounded-[1.25rem] overflow-hidden group border border-white dark:border-slate-700 shadow-sm">
                <img src="assets/img/gardening.jpg" alt="Gardening" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000">
                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent"></div>
                <div class="absolute bottom-5 left-5 text-white">
                  <h4 class="font-bold text-lg leading-none mb-1">Gardening</h4>
                  <p class="text-xs text-gray-300 font-medium">Mowing & Planting</p>
                </div>
              </div>
            </template>
            <div class="task-column" id="col-left"></div>
          </div>

          <!-- Right Column (Scrolls Down) -->
          <div class="space-y-5 animate-scroll-down">
            <template id="task-cards-right">
              <!-- Card: Cleaning -->
              <div class="relative aspect-[4/5] rounded-[1.25rem] overflow-hidden group border border-white dark:border-slate-700 shadow-sm">
                <img src="assets/img/house_cleaning.jpg" alt="Cleaning" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000">
                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent"></div>
                <div class="absolute bottom-5 left-5 text-white">
                  <h4 class="font-bold text-lg leading-none mb-1">Cleaning</h4>
                  <p class="text-xs text-gray-300 font-medium">Home & Office</p>
                </div>
              </div>

              <!-- Card: Moving -->
              <div class="relative aspect-[4/5] rounded-[1.25rem] overflow-hidden group border border-white dark:border-slate-700 shadow-sm">
                <img src="assets/img/barbers.jpg" alt="Moving" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000">
                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent"></div>
                <div class="absolute bottom-5 left-5 text-white">
                  <h4 class="font-bold text-lg leading-none mb-1">Moving Help</h4>
                  <p class="text-xs text-gray-300 font-medium">Heavy Lifting</p>
                </div>
              </div>

              <!-- Card: Car Wash -->
              <div class="relative aspect-[4/5] rounded-[1.25rem] overflow-hidden group border border-white dark:border-slate-700 shadow-sm">
                <img src="assets/img/car_wash.jpg" alt="Car Wash" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000">
                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent"></div>
                <div class="absolute bottom-5 left-5 text-white">
                  <h4 class="font-bold text-lg leading-none mb-1">Car Wash</h4>
                  <p class="text-xs text-gray-300 font-medium">Mobile Detailing</p>
                </div>
              </div>

              <!-- Card: IT Support -->
              <div class="relative aspect-[4/5] rounded-[1.25rem] overflow-hidden group border border-white dark:border-slate-700 shadow-sm">
                <img src="assets/img/mechanic.jpg" alt="IT Support" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000">
                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent"></div>
                <div class="absolute bottom-5 left-5 text-white">
                  <h4 class="font-bold text-lg leading-none mb-1">IT Support</h4>
                  <p class="text-xs text-gray-300 font-medium">Tech & Setup</p>
                </div>
              </div>
            </template>
            <div class="task-column" id="col-right"></div>
          </div>
        </div>

      </div>
    </div>
  </div>
</section>

<!-- Why Choose Minijobz? -->
<!-- Light/Dark Responsive -->
<section class="relative py-24 px-6 bg-slate-50 transition-colors duration-300 overflow-hidden">
  
  <!-- Decorative Background Pattern (Dots) -->
  <div class="absolute inset-0 opacity-[0.4] dark:opacity-[0.1]" style="background-image: radial-gradient(#94a3b8 1px, transparent 1px); background-size: 32px 32px;"></div>
  
  <!-- Decorative Blur Blob -->
  <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[600px] h-[400px] bg-blue-200 dark:bg-blue-900 rounded-full blur-[120px] opacity-30 pointer-events-none"></div>

  <div class="relative max-w-7xl mx-auto">
    <!-- Header -->
    <div class="text-center max-w-3xl mx-auto mb-16">
      <h2 class="text-4xl md:text-5xl font-extrabold text-gray-900 tracking-tight mb-6">
        @lang('index.why_choose_title')
      </h2>
      <p class="text-lg text-gray-600 dark:text-gray-400 leading-relaxed">
        {{ __('index.why_choose_desc') }}
      </p>
    </div>

    <!-- Cards Grid -->
    <div class="grid md:grid-cols-3 gap-8 relative z-10">
      
      <!-- Card 1 -->
      <div class="group bg-white rounded-2xl p-8 border border-gray-200 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 ease-out relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-400 to-blue-600 transform origin-left scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></div>
        <div class="w-14 h-14 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center mb-6 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
          <i data-feather="zap" class="w-7 h-7"></i>
        </div>
        <h3 class="text-2xl font-bold text-gray-900 mb-3">{{ __('index.fast_title') }}</h3>
        <p class="text-gray-600 mb-6 leading-relaxed">
          {{ __('index.fast_desc') }}
        </p>
        <div class="flex items-center text-blue-600 font-semibold text-sm">
          <span>{{ __('index.fast_link') }}</span>
          <i data-feather="arrow-right" class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform"></i>
        </div>
      </div>

      <!-- Card 2 -->
      <div class="group bg-white rounded-2xl p-8 border border-gray-200 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 ease-out relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-400 to-emerald-600 transform origin-left scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></div>
        <div class="w-14 h-14 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-6 group-hover:bg-emerald-600 group-hover:text-white transition-colors duration-300">
          <i data-feather="shield" class="w-7 h-7"></i>
        </div>
        <h3 class="text-2xl font-bold text-gray-900 mb-3">{{ __('index.secure_title') }}</h3>
        <p class="text-gray-600 mb-6 leading-relaxed">
          {{ __('index.secure_desc') }}
        </p>
        <div class="flex items-center text-emerald-600 font-semibold text-sm">
          <span>{{ __('index.secure_link') }}</span>
          <i data-feather="arrow-right" class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform"></i>
        </div>
      </div>

      <!-- Card 3 -->
      <div class="group bg-white rounded-2xl p-8 border border-gray-200 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 ease-out relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-indigo-400 to-indigo-600 transform origin-left scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></div>
        <div class="w-14 h-14 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-6 group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-300">
          <i data-feather="sliders" class="w-7 h-7"></i>
        </div>
        <h3 class="text-2xl font-bold text-gray-900 mb-3">{{ __('index.price_title') }}</h3>
        <p class="text-gray-600 mb-6 leading-relaxed">
          {{ __('index.price_desc') }}
        </p>
        <div class="flex items-center text-indigo-600 font-semibold text-sm">
          <span>{{ __('index.price_link') }}</span>
          <i data-feather="arrow-right" class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform"></i>
        </div>
      </div>

    </div>
  </div>
</section>
 
 
<!-- Testimonials Section - Page Turn Effect -->
<section class="relative py-32 bg-white transition-colors duration-500 ease-in-out dark:bg-slate-900 overflow-hidden">
  
  <!-- Decorative Background Elements -->
  <div class="absolute top-10 right-10 text-gray-100 dark:text-slate-800 font-serif text-[400px] leading-none select-none pointer-events-none transition-colors duration-500">
    &rdquo;
  </div>
  <!-- Glowing Blob -->
  <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-indigo-100 dark:bg-indigo-600 rounded-full blur-[150px] opacity-50 dark:opacity-20 pointer-events-none transition-colors duration-500"></div>

  <div class="relative max-w-7xl mx-auto px-6 grid lg:grid-cols-2 gap-20 items-center">
    
    <!-- Left Column: Controls -->
    <div>
      <span class="text-indigo-600 dark:text-indigo-400 font-semibold tracking-wider uppercase text-sm mb-2 block">{{ __('index.community_stories') }}</span>
      <h2 class="text-5xl md:text-6xl font-extrabold text-gray-900 dark:text-white mb-6 leading-tight transition-colors duration-300">
        @lang('index.trusted_by_neighbors')
      </h2>
      <p class="text-lg text-gray-600 dark:text-slate-400 mb-12 max-w-md leading-relaxed transition-colors duration-300">
        {{ __('index.testimonial_intro') }}
      </p>
      
      <!-- Control Arrows -->
      <div class="flex space-x-4">
        <button id="prevBtn" class="group w-14 h-14 flex items-center justify-center rounded-full border border-gray-300 dark:border-slate-600 text-gray-600 dark:text-white hover:bg-gray-900 hover:text-white dark:hover:bg-white dark:hover:text-slate-900 hover:border-transparent transition-all duration-300 z-20">
          <i data-feather="arrow-left" class="w-6 h-6 group-hover:-translate-x-1 transition-transform"></i>
        </button>
        <button id="nextBtn" class="group w-14 h-14 flex items-center justify-center rounded-full border border-gray-300 dark:border-slate-600 text-gray-600 dark:text-white hover:bg-gray-900 hover:text-white dark:hover:bg-white dark:hover:text-slate-900 hover:border-transparent transition-all duration-300 z-20">
          <i data-feather="arrow-right" class="w-6 h-6 group-hover:translate-x-1 transition-transform"></i>
        </button>
      </div>
    </div>

    <!-- Right Column: Dynamic Content Card (Perspective Container) -->
    <div class="relative perspective-container">
      <!-- The Content Wrapper (The 'Page') -->
      <div id="testimonial-content">
        
        <!-- Large Quote -->
        <blockquote class="relative z-10">
          <p id="t-quote" class="text-2xl md:text-3xl font-medium text-gray-800 dark:text-slate-100 leading-relaxed font-serif italic transition-colors duration-300">
            "Minijobz helped me find reliable helpers in minutes. It’s fast, simple, and finally gave me my weekends back."
          </p>
        </blockquote>

        <!-- User Profile -->
        <div class="mt-10 flex items-center gap-5">
          <div class="relative">
            <div class="absolute inset-0 bg-indigo-200 dark:bg-indigo-500 rounded-full blur opacity-40"></div>
            <img id="t-img" src="https://i.pravatar.cc/150?img=32" alt="User" class="relative w-16 h-16 rounded-full object-cover border-2 border-white dark:border-slate-700 shadow-md">
          </div>
          <div>
            <h4 id="t-name" class="font-bold text-gray-900 dark:text-white text-xl transition-colors duration-300">Lisa Thompson</h4>
            <div class="flex items-center gap-2 mt-1">
              <span id="t-role" class="text-indigo-600 dark:text-indigo-400 text-sm font-medium uppercase tracking-wide transition-colors duration-300">Homeowner</span>
              <span class="w-1 h-1 bg-gray-400 dark:bg-slate-600 rounded-full"></span>
              <span class="text-gray-500 dark:text-slate-500 text-sm transition-colors duration-300">Verified User</span>
            </div>
          </div>
        </div>

      </div>
    </div>

  </div>
</section>
 
 
<!-- Popular Tasks Section -->
<section class="py-24 bg-gray-50 transition-colors duration-300 overflow-hidden relative">
<div class="max-w-7xl mx-auto px-6 mb-12 text-center">
<h2 class="text-4xl md:text-5xl font-extrabold text-gray-900 tracking-tight">
      See what others are getting done
</h2>
<p class="mt-4 text-lg text-gray-600">Real tasks, real prices, completed recently.</p>
</div>
 
  <!-- Container with Edge Fade Mask -->
  <div class="relative scroll-mask group">
   
    <!-- Row 1: Scrolls Left -->
    <div class="flex gap-5 mb-6 w-max animate-scroll-left">
      <!-- CARD SET 1 -->
        <div class="w-[280px] bg-white border border-gray-200 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between">
          <div>
            <div class="flex justify-between items-center mb-3">
              <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide bg-blue-100 text-blue-700">Delivery</span>
              <span class="font-bold text-gray-900 text-lg">$85</span>
            </div>
            <h3 class="text-gray-900 font-semibold text-sm leading-snug mb-4">King mattress pick up & delivery</h3>
          </div>
          <div class="flex items-center gap-3 pt-3 border-t border-gray-100">
            <img src="https://i.pravatar.cc/150?img=11" alt="User" class="w-9 h-9 rounded-full object-cover border border-gray-200">
            <div class="flex flex-col justify-center">
              <div class="flex text-amber-400 mb-0.5"><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i></div>
              <span class="text-[11px] text-gray-500 font-medium">12 reviews</span>
            </div>
          </div>
        </div>
        <div class="w-[280px] bg-white border border-gray-200 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between">
          <div>
            <div class="flex justify-between items-center mb-3">
              <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide bg-green-100 text-green-700">Cleaning</span>
              <span class="font-bold text-gray-900 text-lg">$450</span>
            </div>
            <h3 class="text-gray-900 font-semibold text-sm leading-snug mb-4">End of lease clean (3 Bedroom)</h3>
          </div>
          <div class="flex items-center gap-3 pt-3 border-t border-gray-100">
            <img src="https://i.pravatar.cc/150?img=5" alt="User" class="w-9 h-9 rounded-full object-cover border border-gray-200">
            <div class="flex flex-col justify-center">
              <div class="flex text-amber-400 mb-0.5"><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i></div>
              <span class="text-[11px] text-gray-500 font-medium">48 reviews</span>
            </div>
          </div>
        </div>
        <div class="w-[280px] bg-white border border-gray-200 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between">
          <div>
            <div class="flex justify-between items-center mb-3">
              <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide bg-purple-100 text-purple-700">Assembly</span>
              <span class="font-bold text-gray-900 text-lg">$120</span>
            </div>
            <h3 class="text-gray-900 font-semibold text-sm leading-snug mb-4">IKEA Wardrobe Assembly</h3>
          </div>
          <div class="flex items-center gap-3 pt-3 border-t border-gray-100">
            <img src="https://i.pravatar.cc/150?img=3" alt="User" class="w-9 h-9 rounded-full object-cover border border-gray-200">
            <div class="flex flex-col justify-center">
              <div class="flex text-amber-400 mb-0.5"><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i></div>
              <span class="text-[11px] text-gray-500 font-medium">32 reviews</span>
            </div>
          </div>
        </div>
        <div class="w-[280px] bg-white border border-gray-200 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between">
          <div>
            <div class="flex justify-between items-center mb-3">
              <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide bg-orange-100 text-orange-700">Moving</span>
              <span class="font-bold text-gray-900 text-lg">$95</span>
            </div>
            <h3 class="text-gray-900 font-semibold text-sm leading-snug mb-4">Sofa Delivery to 2nd Floor</h3>
          </div>
          <div class="flex items-center gap-3 pt-3 border-t border-gray-100">
            <img src="https://i.pravatar.cc/150?img=59" alt="User" class="w-9 h-9 rounded-full object-cover border border-gray-200">
            <div class="flex flex-col justify-center">
              <div class="flex text-amber-400 mb-0.5"><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 text-gray-300"></i></div>
              <span class="text-[11px] text-gray-500 font-medium">8 reviews</span>
            </div>
          </div>
        </div>
      <!-- CARD SET 2 (Duplicate) -->
        <div class="w-[280px] bg-white border border-gray-200 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between">
          <div><div class="flex justify-between items-center mb-3"><span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide bg-blue-100 text-blue-700">Delivery</span><span class="font-bold text-gray-900 text-lg">$85</span></div><h3 class="text-gray-900 font-semibold text-sm leading-snug mb-4">King mattress pick up & delivery</h3></div>
          <div class="flex items-center gap-3 pt-3 border-t border-gray-100"><img src="https://i.pravatar.cc/150?img=11" class="w-9 h-9 rounded-full object-cover border border-gray-200"><div class="flex flex-col justify-center"><div class="flex text-amber-400 mb-0.5"><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i></div><span class="text-[11px] text-gray-500 font-medium">12 reviews</span></div></div>
        </div>
        <div class="w-[280px] bg-white border border-gray-200 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between">
          <div><div class="flex justify-between items-center mb-3"><span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide bg-green-100 text-green-700">Cleaning</span><span class="font-bold text-gray-900 text-lg">$450</span></div><h3 class="text-gray-900 font-semibold text-sm leading-snug mb-4">End of lease clean (3 Bedroom)</h3></div>
          <div class="flex items-center gap-3 pt-3 border-t border-gray-100"><img src="https://i.pravatar.cc/150?img=5" class="w-9 h-9 rounded-full object-cover border border-gray-200"><div class="flex flex-col justify-center"><div class="flex text-amber-400 mb-0.5"><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i></div><span class="text-[11px] text-gray-500 font-medium">48 reviews</span></div></div>
        </div>
        <div class="w-[280px] bg-white border border-gray-200 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between">
          <div><div class="flex justify-between items-center mb-3"><span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide bg-purple-100 text-purple-700">Assembly</span><span class="font-bold text-gray-900 text-lg">$120</span></div><h3 class="text-gray-900 font-semibold text-sm leading-snug mb-4">IKEA Wardrobe Assembly</h3></div>
          <div class="flex items-center gap-3 pt-3 border-t border-gray-100"><img src="https://i.pravatar.cc/150?img=3" class="w-9 h-9 rounded-full object-cover border border-gray-200"><div class="flex flex-col justify-center"><div class="flex text-amber-400 mb-0.5"><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i></div><span class="text-[11px] text-gray-500 font-medium">32 reviews</span></div></div>
        </div>
        <div class="w-[280px] bg-white border border-gray-200 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between">
          <div><div class="flex justify-between items-center mb-3"><span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide bg-orange-100 text-orange-700">Moving</span><span class="font-bold text-gray-900 text-lg">$95</span></div><h3 class="text-gray-900 font-semibold text-sm leading-snug mb-4">Sofa Delivery to 2nd Floor</h3></div>
          <div class="flex items-center gap-3 pt-3 border-t border-gray-100"><img src="https://i.pravatar.cc/150?img=59" class="w-9 h-9 rounded-full object-cover border border-gray-200"><div class="flex flex-col justify-center"><div class="flex text-amber-400 mb-0.5"><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 text-gray-300"></i></div><span class="text-[11px] text-gray-500 font-medium">8 reviews</span></div></div>
        </div>
      <!-- CARD SET 3 (Third Duplicate for seamless loop) -->
        <div class="w-[280px] bg-white border border-gray-200 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between">
          <div><div class="flex justify-between items-center mb-3"><span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide bg-blue-100 text-blue-700">Delivery</span><span class="font-bold text-gray-900 text-lg">$85</span></div><h3 class="text-gray-900 font-semibold text-sm leading-snug mb-4">King mattress pick up & delivery</h3></div>
          <div class="flex items-center gap-3 pt-3 border-t border-gray-100"><img src="https://i.pravatar.cc/150?img=11" class="w-9 h-9 rounded-full object-cover border border-gray-200"><div class="flex flex-col justify-center"><div class="flex text-amber-400 mb-0.5"><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i></div><span class="text-[11px] text-gray-500 font-medium">12 reviews</span></div></div>
        </div>
        <div class="w-[280px] bg-white border border-gray-200 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between">
          <div><div class="flex justify-between items-center mb-3"><span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide bg-green-100 text-green-700">Cleaning</span><span class="font-bold text-gray-900 text-lg">$450</span></div><h3 class="text-gray-900 font-semibold text-sm leading-snug mb-4">End of lease clean (3 Bedroom)</h3></div>
          <div class="flex items-center gap-3 pt-3 border-t border-gray-100"><img src="https://i.pravatar.cc/150?img=5" class="w-9 h-9 rounded-full object-cover border border-gray-200"><div class="flex flex-col justify-center"><div class="flex text-amber-400 mb-0.5"><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i></div><span class="text-[11px] text-gray-500 font-medium">48 reviews</span></div></div>
        </div>
        <div class="w-[280px] bg-white border border-gray-200 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between">
          <div><div class="flex justify-between items-center mb-3"><span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide bg-purple-100 text-purple-700">Assembly</span><span class="font-bold text-gray-900 text-lg">$120</span></div><h3 class="text-gray-900 font-semibold text-sm leading-snug mb-4">IKEA Wardrobe Assembly</h3></div>
          <div class="flex items-center gap-3 pt-3 border-t border-gray-100"><img src="https://i.pravatar.cc/150?img=3" class="w-9 h-9 rounded-full object-cover border border-gray-200"><div class="flex flex-col justify-center"><div class="flex text-amber-400 mb-0.5"><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i></div><span class="text-[11px] text-gray-500 font-medium">32 reviews</span></div></div>
        </div>
        <div class="w-[280px] bg-white border border-gray-200 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between">
          <div><div class="flex justify-between items-center mb-3"><span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide bg-orange-100 text-orange-700">Moving</span><span class="font-bold text-gray-900 text-lg">$95</span></div><h3 class="text-gray-900 font-semibold text-sm leading-snug mb-4">Sofa Delivery to 2nd Floor</h3></div>
          <div class="flex items-center gap-3 pt-3 border-t border-gray-100"><img src="https://i.pravatar.cc/150?img=59" class="w-9 h-9 rounded-full object-cover border border-gray-200"><div class="flex flex-col justify-center"><div class="flex text-amber-400 mb-0.5"><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 text-gray-300"></i></div><span class="text-[11px] text-gray-500 font-medium">8 reviews</span></div></div>
        </div>
    </div>
 
    <!-- Row 2: Scrolls Right (FIXED: Added Duplicate Set) -->
    <div class="flex gap-5 w-max animate-scroll-right">
      <!-- CARD SET 1 -->
         <div class="w-[280px] bg-white border border-gray-200 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between">
          <div>
            <div class="flex justify-between items-center mb-3">
              <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide bg-red-100 text-red-700">Removals</span>
              <span class="font-bold text-gray-900 text-lg">$60</span>
            </div>
            <h3 class="text-gray-900 font-semibold text-sm leading-snug mb-4">Couch moved 1km down the road</h3>
          </div>
          <div class="flex items-center gap-3 pt-3 border-t border-gray-100">
            <img src="https://i.pravatar.cc/150?img=12" alt="User" class="w-9 h-9 rounded-full object-cover border border-gray-200">
            <div class="flex flex-col justify-center">
              <div class="flex text-amber-400 mb-0.5"><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i></div>
              <span class="text-[11px] text-gray-500 font-medium">22 reviews</span>
            </div>
          </div>
        </div>
        <div class="w-[280px] bg-white border border-gray-200 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between">
          <div>
            <div class="flex justify-between items-center mb-3">
              <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide bg-red-100 text-red-700">Removals</span>
              <span class="font-bold text-gray-900 text-lg">$506</span>
            </div>
            <h3 class="text-gray-900 font-semibold text-sm leading-snug mb-4">Removalist TODAY (Urgent)</h3>
          </div>
          <div class="flex items-center gap-3 pt-3 border-t border-gray-100">
            <img src="https://i.pravatar.cc/150?img=60" alt="User" class="w-9 h-9 rounded-full object-cover border border-gray-200">
            <div class="flex flex-col justify-center">
              <div class="flex text-amber-400 mb-0.5"><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i></div>
              <span class="text-[11px] text-gray-500 font-medium">145 reviews</span>
            </div>
          </div>
        </div>
        <div class="w-[280px] bg-white border border-gray-200 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between">
          <div>
            <div class="flex justify-between items-center mb-3">
              <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide bg-teal-100 text-teal-700">Gardening</span>
              <span class="font-bold text-gray-900 text-lg">$75</span>
            </div>
            <h3 class="text-gray-900 font-semibold text-sm leading-snug mb-4">Lawn Mowing & Weeding</h3>
          </div>
          <div class="flex items-center gap-3 pt-3 border-t border-gray-100">
            <img src="https://i.pravatar.cc/150?img=68" alt="User" class="w-9 h-9 rounded-full object-cover border border-gray-200">
            <div class="flex flex-col justify-center">
              <div class="flex text-amber-400 mb-0.5"><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 text-gray-300"></i></div>
              <span class="text-[11px] text-gray-500 font-medium">9 reviews</span>
            </div>
          </div>
        </div>
        <div class="w-[280px] bg-white border border-gray-200 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between">
          <div>
            <div class="flex justify-between items-center mb-3">
              <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide bg-indigo-100 text-indigo-700">Tech</span>
              <span class="font-bold text-gray-900 text-lg">$150</span>
            </div>
            <h3 class="text-gray-900 font-semibold text-sm leading-snug mb-4">Home Office Network Setup</h3>
          </div>
          <div class="flex items-center gap-3 pt-3 border-t border-gray-100">
            <img src="https://i.pravatar.cc/150?img=33" alt="User" class="w-9 h-9 rounded-full object-cover border border-gray-200">
            <div class="flex flex-col justify-center">
              <div class="flex text-amber-400 mb-0.5"><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i></div>
              <span class="text-[11px] text-gray-500 font-medium">41 reviews</span>
            </div>
          </div>
        </div>
      <!-- CARD SET 2 (THIS WAS MISSING: Duplicate for Loop) -->
         <div class="w-[280px] bg-white border border-gray-200 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between">
          <div><div class="flex justify-between items-center mb-3"><span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide bg-red-100 text-red-700">Removals</span><span class="font-bold text-gray-900 text-lg">$60</span></div><h3 class="text-gray-900 font-semibold text-sm leading-snug mb-4">Couch moved 1km down the road</h3></div>
          <div class="flex items-center gap-3 pt-3 border-t border-gray-100"><img src="https://i.pravatar.cc/150?img=12" class="w-9 h-9 rounded-full object-cover border border-gray-200"><div class="flex flex-col justify-center"><div class="flex text-amber-400 mb-0.5"><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i></div><span class="text-[11px] text-gray-500 font-medium">22 reviews</span></div></div>
        </div>
        <div class="w-[280px] bg-white border border-gray-200 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between">
          <div><div class="flex justify-between items-center mb-3"><span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide bg-red-100 text-red-700">Removals</span><span class="font-bold text-gray-900 text-lg">$506</span></div><h3 class="text-gray-900 font-semibold text-sm leading-snug mb-4">Removalist TODAY (Urgent)</h3></div>
          <div class="flex items-center gap-3 pt-3 border-t border-gray-100"><img src="https://i.pravatar.cc/150?img=60" class="w-9 h-9 rounded-full object-cover border border-gray-200"><div class="flex flex-col justify-center"><div class="flex text-amber-400 mb-0.5"><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i></div><span class="text-[11px] text-gray-500 font-medium">145 reviews</span></div></div>
        </div>
        <div class="w-[280px] bg-white border border-gray-200 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between">
          <div><div class="flex justify-between items-center mb-3"><span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide bg-teal-100 text-teal-700">Gardening</span><span class="font-bold text-gray-900 text-lg">$75</span></div><h3 class="text-gray-900 font-semibold text-sm leading-snug mb-4">Lawn Mowing & Weeding</h3></div>
          <div class="flex items-center gap-3 pt-3 border-t border-gray-100"><img src="https://i.pravatar.cc/150?img=68" class="w-9 h-9 rounded-full object-cover border border-gray-200"><div class="flex flex-col justify-center"><div class="flex text-amber-400 mb-0.5"><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 text-gray-300"></i></div><span class="text-[11px] text-gray-500 font-medium">9 reviews</span></div></div>
        </div>
        <div class="w-[280px] bg-white border border-gray-200 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between">
          <div><div class="flex justify-between items-center mb-3"><span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide bg-indigo-100 text-indigo-700">Tech</span><span class="font-bold text-gray-900 text-lg">$150</span></div><h3 class="text-gray-900 font-semibold text-sm leading-snug mb-4">Home Office Network Setup</h3></div>
          <div class="flex items-center gap-3 pt-3 border-t border-gray-100"><img src="https://i.pravatar.cc/150?img=33" class="w-9 h-9 rounded-full object-cover border border-gray-200"><div class="flex flex-col justify-center"><div class="flex text-amber-400 mb-0.5"><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i></div><span class="text-[11px] text-gray-500 font-medium">41 reviews</span></div></div>
        </div>
      <!-- CARD SET 3 (Third Duplicate for seamless loop) -->
         <div class="w-[280px] bg-white border border-gray-200 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between">
          <div><div class="flex justify-between items-center mb-3"><span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide bg-red-100 text-red-700">Removals</span><span class="font-bold text-gray-900 text-lg">$60</span></div><h3 class="text-gray-900 font-semibold text-sm leading-snug mb-4">Couch moved 1km down the road</h3></div>
          <div class="flex items-center gap-3 pt-3 border-t border-gray-100"><img src="https://i.pravatar.cc/150?img=12" class="w-9 h-9 rounded-full object-cover border border-gray-200"><div class="flex flex-col justify-center"><div class="flex text-amber-400 mb-0.5"><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i></div><span class="text-[11px] text-gray-500 font-medium">22 reviews</span></div></div>
        </div>
        <div class="w-[280px] bg-white border border-gray-200 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between">
          <div><div class="flex justify-between items-center mb-3"><span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide bg-red-100 text-red-700">Removals</span><span class="font-bold text-gray-900 text-lg">$506</span></div><h3 class="text-gray-900 font-semibold text-sm leading-snug mb-4">Removalist TODAY (Urgent)</h3></div>
          <div class="flex items-center gap-3 pt-3 border-t border-gray-100"><img src="https://i.pravatar.cc/150?img=60" class="w-9 h-9 rounded-full object-cover border border-gray-200"><div class="flex flex-col justify-center"><div class="flex text-amber-400 mb-0.5"><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i></div><span class="text-[11px] text-gray-500 font-medium">145 reviews</span></div></div>
        </div>
        <div class="w-[280px] bg-white border border-gray-200 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between">
          <div><div class="flex justify-between items-center mb-3"><span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide bg-teal-100 text-teal-700">Gardening</span><span class="font-bold text-gray-900 text-lg">$75</span></div><h3 class="text-gray-900 font-semibold text-sm leading-snug mb-4">Lawn Mowing & Weeding</h3></div>
          <div class="flex items-center gap-3 pt-3 border-t border-gray-100"><img src="https://i.pravatar.cc/150?img=68" class="w-9 h-9 rounded-full object-cover border border-gray-200"><div class="flex flex-col justify-center"><div class="flex text-amber-400 mb-0.5"><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 text-gray-300"></i></div><span class="text-[11px] text-gray-500 font-medium">9 reviews</span></div></div>
        </div>
        <div class="w-[280px] bg-white border border-gray-200 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between">
          <div><div class="flex justify-between items-center mb-3"><span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide bg-indigo-100 text-indigo-700">Tech</span><span class="font-bold text-gray-900 text-lg">$150</span></div><h3 class="text-gray-900 font-semibold text-sm leading-snug mb-4">Home Office Network Setup</h3></div>
          <div class="flex items-center gap-3 pt-3 border-t border-gray-100"><img src="https://i.pravatar.cc/150?img=33" class="w-9 h-9 rounded-full object-cover border border-gray-200"><div class="flex flex-col justify-center"><div class="flex text-amber-400 mb-0.5"><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i></div><span class="text-[11px] text-gray-500 font-medium">41 reviews</span></div></div>
        </div>
    </div>
 
  </div>
</section>
 
<!-- Take Minijobz Anywhere - Modern App Card -->
<section class="py-24 px-4 md:px-6 bg-white dark:bg-slate-900 transition-colors duration-300">
  
  <!-- Main Gradient Card -->
  <div class="max-w-7xl mx-auto bg-gradient-to-br from-indigo-600 to-violet-700 rounded-[2.5rem] shadow-2xl overflow-hidden relative">
    
    <!-- Decorative Background Glows -->
    <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-white opacity-10 blur-[120px] rounded-full pointer-events-none -translate-y-1/2 translate-x-1/2"></div>
    <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-indigo-400 opacity-20 blur-[100px] rounded-full pointer-events-none translate-y-1/3 -translate-x-1/3"></div>

    <div class="grid lg:grid-cols-12 gap-12 items-center relative z-10 p-8 md:p-16">
      
      <!-- Left Content (Text + Buttons) -->
      <div class="lg:col-span-7 flex flex-col justify-center text-center lg:text-left">
        <div class="inline-flex items-center justify-center lg:justify-start gap-2 mb-6">
          <span class="px-3 py-1 rounded-full bg-indigo-500/30 border border-indigo-400/30 text-indigo-100 text-xs font-bold uppercase tracking-widest backdrop-blur-sm">
            {{ __('index.mobile_app_badge') }}
          </span>
        </div>
        
        <h2 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white mb-6 leading-tight">
          @lang('index.mobile_app_title')
        </h2>
        
        <p class="text-lg text-indigo-100 mb-10 max-w-xl mx-auto lg:mx-0 leading-relaxed">
          {{ __('index.mobile_app_desc') }}
        </p>

        <!-- Buttons & QR Row -->
        <div class="flex flex-col sm:flex-row items-center gap-8 justify-center lg:justify-start">
          
          <!-- Store Buttons (Updated to White) -->
          <div class="flex flex-col gap-3 w-full sm:w-auto">
            <!-- Apple Button -->
            <a href="#" class="flex items-center gap-3 bg-white hover:bg-gray-50 text-gray-900 px-5 py-3 rounded-xl transition-all duration-300 shadow-lg hover:-translate-y-1">
              <img src="assets/img/apple.svg" alt="Apple Logo" class="w-8 h-8">
              <div class="text-left leading-none">
                <div class="text-[10px] uppercase tracking-wide opacity-60 mb-1">Download on the</div>
                <div class="text-base font-bold font-sans">App Store</div>
              </div>
            </a>
            
            <!-- Google Play Button -->
            <a href="#" class="flex items-center gap-3 bg-white hover:bg-gray-50 text-gray-900 px-5 py-3 rounded-xl transition-all duration-300 shadow-lg hover:-translate-y-1">
              <img src="assets/img/google-play.svg" alt="Google Play Logo" class="w-7 h-7">
              <div class="text-left leading-none">
                <div class="text-[10px] uppercase tracking-wide opacity-60 mb-1">Get it on</div>
                <div class="text-base font-bold font-sans">Google Play</div>
              </div>
            </a>
          </div>

          <!-- Divider for Mobile -->
          <div class="hidden sm:block w-px h-24 bg-indigo-400/30"></div>

          <!-- QR Code Block -->
          <div class="hidden sm:flex flex-col items-center gap-3">
            <div class="p-2 bg-white rounded-xl shadow-inner">
              <!-- Placeholder QR Code -->
              <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=MinijobzAppDownload" alt="Scan to download" class="w-24 h-24 mix-blend-multiply opacity-90">
            </div>
            <span class="text-xs font-medium text-indigo-200 tracking-wide uppercase">{{ __('index.scan_to_install') }}</span>
          </div>
        
        </div>
      </div>

      <!-- Right Image (Phone Mockup) -->
      <div class="lg:col-span-5 relative flex justify-center lg:justify-end h-full min-h-[300px] lg:min-h-auto mt-8 lg:mt-0">
        <!-- The Image Container - Tilted Effect -->
        <div class="relative w-64 md:w-80 lg:w-[22rem] transition-transform duration-500 hover:scale-[1.02] hover:-rotate-1">
          <!-- Phone Shadow/Glow -->
          <div class="absolute inset-4 bg-indigo-900 rounded-[3rem] blur-2xl opacity-60"></div>
          
          <!-- Actual Image -->
          <img 
            src="https://assets.codepen.io/7729268/iphone-mockup-minijobz.png" 
            onerror="this.src='assets/img/phone_14_01.webp'"
            alt="Minijobz App Interface" 
            class="relative z-10 drop-shadow-2xl transform lg:translate-y-12"
          >
          
          <!-- Floating Elements (Decoration) -->
          <div class="absolute -left-8 top-1/4 z-20 bg-white/90 backdrop-blur-md p-3 rounded-2xl shadow-xl animate-bounce" style="animation-duration: 3s;">
            <div class="flex items-center gap-3">
              <div class="w-8 h-8 bg-green-100 text-green-600 rounded-full flex items-center justify-center">
                <i data-feather="check" class="w-4 h-4"></i>
              </div>
              <div>
                <p class="text-xs font-bold text-gray-900">Task Completed!</p>
                <p class="text-[10px] text-gray-500">Payment Released</p>
              </div>
            </div>
          </div>

          <!-- Notification Card (Lowered to bottom-8) -->
          <div class="absolute -right-4 bottom-8 z-20 bg-white/90 backdrop-blur-md p-3 rounded-2xl shadow-xl animate-bounce" style="animation-duration: 4s; animation-delay: 1s;">
             <div class="flex items-center gap-3">
              <img src="https://i.pravatar.cc/150?img=12" class="w-8 h-8 rounded-full border border-gray-200">
              <div>
                <p class="text-xs font-bold text-gray-900">New Offer: $45</p>
                <p class="text-[10px] text-gray-500">James W. is interested</p>
              </div>
            </div>
          </div>

        </div>
      </div>

    </div>
  </div>
</section>
<script>
  // 1. Vertical Scrolling Column Duplication
  function duplicateCards(templateId, containerId) {
    const template = document.getElementById(templateId);
    const container = document.getElementById(containerId);
    if (template && container) {
      for (let i = 0; i < 3; i++) {
        container.appendChild(template.content.cloneNode(true));
      }
    }
  }
  duplicateCards('task-cards-left', 'col-left');
  duplicateCards('task-cards-right', 'col-right');

  // 2. Testimonials Logic (3D Page Turn / Card Flip)
  const testimonials = @json(__('index.testimonials'));

  let currentIndex = 0;
  const contentWrapper = document.getElementById('testimonial-content');
  const tQuote = document.getElementById('t-quote');
  const tName = document.getElementById('t-name');
  const tRole = document.getElementById('t-role');
  const tImg = document.getElementById('t-img');
  const nextBtn = document.getElementById('nextBtn');
  const prevBtn = document.getElementById('prevBtn');
  let autoScrollTimer;
  let isAnimating = false; // Prevent double clicks during animation

  function updateTestimonial(index, direction = 'next') {
    if (isAnimating) return;
    isAnimating = true;

    const container = document.querySelector('.perspective-container');
    const currentContent = document.getElementById('testimonial-content');
    
    // 1. Create a clone of the current content for the "Exit" animation
    // We clone it BEFORE updating the data, so it shows the old testimonial.
    const clone = currentContent.cloneNode(true);
    
    // Remove IDs from clone to avoid duplicates (optional but good practice)
    clone.removeAttribute('id');
    clone.querySelectorAll('[id]').forEach(el => el.removeAttribute('id'));

    // Position clone absolutely over the container
    clone.style.position = 'absolute';
    clone.style.top = '0';
    clone.style.left = '0';
    clone.style.width = '100%';
    clone.style.height = '100%';
    clone.style.zIndex = '10'; 
    
    // Append clone to container
    container.appendChild(clone);

    // 2. Update the "Real" element with NEW data
    const data = testimonials[index];
    if(tQuote) tQuote.textContent = `"${data.quote}"`;
    if(tName) tName.textContent = data.name;
    if(tRole) tRole.textContent = data.role;
    if(tImg) tImg.src = data.img;

    // 3. Prepare positions for Animation
    // Remove transitions temporarily to set initial positions instantly
    currentContent.style.transition = 'none';
    clone.style.transition = 'none';

    if (direction === 'next') {
        // Next: New comes from Right, Old goes Left
        currentContent.style.transform = 'translateX(100%)';
        currentContent.style.opacity = '0';
    } else {
        // Prev: New comes from Left, Old goes Right
        currentContent.style.transform = 'translateX(-100%)';
        currentContent.style.opacity = '0';
    }

    // Force reflow to apply initial positions
    void currentContent.offsetWidth;

    // 4. Animate
    const duration = 0.6; // seconds
    const ease = 'cubic-bezier(0.25, 1, 0.5, 1)'; // Smooth easeOut

    // Apply transition settings
    currentContent.style.transition = `transform ${duration}s ${ease}, opacity ${duration}s ${ease}`;
    clone.style.transition = `transform ${duration}s ${ease}, opacity ${duration}s ${ease}`;

    // Trigger the slide
    requestAnimationFrame(() => {
        if (direction === 'next') {
            clone.style.transform = 'translateX(-100%)';
            clone.style.opacity = '0';
        } else {
            clone.style.transform = 'translateX(100%)';
            clone.style.opacity = '0';
        }
        
        // Move new content to center
        currentContent.style.transform = 'translateX(0)';
        currentContent.style.opacity = '1';
    });

    // 5. Cleanup
    setTimeout(() => {
        if (clone.parentNode) clone.parentNode.removeChild(clone);
        isAnimating = false;
    }, duration * 1000);
  }

  function nextTestimonial() {
    currentIndex = (currentIndex + 1) % testimonials.length;
    updateTestimonial(currentIndex, 'next');
    resetAutoScroll();
  }

  function prevTestimonial() {
    currentIndex = (currentIndex - 1 + testimonials.length) % testimonials.length;
    updateTestimonial(currentIndex, 'prev');
    resetAutoScroll();
  }

  function resetAutoScroll() {
    clearInterval(autoScrollTimer);
    autoScrollTimer = setInterval(nextTestimonial, 6000);
  }

  if(nextBtn && prevBtn) {
    nextBtn.addEventListener('click', nextTestimonial);
    prevBtn.addEventListener('click', prevTestimonial);
    resetAutoScroll();
  }

  // 3. Theme Toggle Persistence
  (function(){
    var root = document.documentElement;
    var btn = document.getElementById('themeToggle');
    var label = document.getElementById('themeLabel');
    function setMode(mode){
      if(mode === 'dark'){
        root.classList.add('dark');
        localStorage.setItem('theme','dark');
        if(label) label.textContent = 'Disable dark mode';
      } else {
        root.classList.remove('dark');
        localStorage.setItem('theme','light');
        if(label) label.textContent = 'Enable dark mode';
      }
    }
    var initial = localStorage.getItem('theme') || 'light';
    setMode(initial);
    if(btn){
      btn.addEventListener('click', function(){
        var next = root.classList.contains('dark') ? 'light' : 'dark';
        setMode(next);
      });
    }
  })();
 
  // 4. Initialize Icons
  if (typeof feather !== 'undefined') {
    feather.replace();
  }
</script>
 
@endsection