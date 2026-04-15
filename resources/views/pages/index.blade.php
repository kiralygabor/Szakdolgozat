@extends('layout')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/index.css') }}">
@endpush

@section('content')
<div class="index-container">

  <!-- Hero Section -->
  <section class="relative h-[90vh] flex items-center justify-center bg-[var(--bg-primary)] index-hero-text-color overflow-hidden">
    <!-- Background Image -->
    <img src="https://images.unsplash.com/photo-1519389950473-47ba0277781c?auto=format&fit=crop&w=2000&q=80"
      alt="People working together" class="absolute inset-0 w-full h-full object-cover opacity-100" />

    <!-- Overlay -->
    <div class="absolute inset-0 index-hero-overlay-bg"></div>

    <!-- Content -->
    <div class="relative z-10 text-center max-w-3xl px-6">
      <h1 class="text-6xl md:text-7xl font-extrabold mb-6 leading-tight">
        @lang('index.hero_title')
      </h1>
      <p class="text-lg md:text-xl index-hero-text-color opacity-90 mb-8">
        {{ __('index.hero_subtitle') }}
      </p>
      <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <a href="{{ route('post-task') }}"
          class="btn px-8 py-4 bg-[var(--primary-accent)] hover:bg-[var(--primary-hover)] rounded-full text-lg font-semibold shadow-lg transition text-white">
          {{ __('index.post_task') }}
        </a>
        <a href="{{ route('tasks') }}"
          class="btn px-8 py-4 bg-[var(--bg-primary)] text-[var(--text-primary)] hover:bg-[var(--bg-hover)] rounded-full text-lg font-semibold shadow-lg transition border border-[var(--border-base)]">
          {{ __('index.browse_tasks') }}
        </a>
      </div>
    </div>
  </section>

  <!-- How It Works - Vertical Timeline & Trending Showcase -->
  <section class="py-24 px-6 index-section-bg transition-colors duration-300 relative overflow-hidden">

    <!-- Background Pattern -->
    <div
      class="absolute top-0 right-0 w-[45%] h-full index-section-alt -skew-x-12 translate-x-20 pointer-events-none border-l border-slate-100 dark:border-slate-800 skew-bg">
    </div>

    <div class="max-w-7xl mx-auto grid lg:grid-cols-2 gap-20 items-center relative z-10">

      <!-- Left Side: The Process (Timeline) - UNCHANGED -->
      <div class="pr-0 lg:pr-10">
        <h2 class="text-4xl md:text-5xl font-extrabold index-text-main mb-12 leading-tight">
          @lang('index.how_it_works_title')
        </h2>

        <div class="space-y-12 relative">
          <div class="absolute left-6 top-4 bottom-12 w-0.5 index-border-color -translate-x-1/2"></div>

          <div class="relative flex gap-8 group">
            <div
              class="relative z-10 w-12 h-12 shrink-0 flex items-center justify-center rounded-full index-section-bg border-2 border-[var(--primary-accent)] border-opacity-10 index-border-color text-[var(--primary-accent)] shadow-sm group-hover:border-[var(--primary-accent)] group-hover:scale-110 transition-all duration-300">
              <span class="font-bold text-lg">1</span>
            </div>
            <div class="pt-1">
              <h3 class="text-xl font-bold index-text-main mb-3">{{ __('index.step_1_title') }}</h3>
              <p class="index-text-muted leading-relaxed text-lg">
                {{ __('index.step_1_desc') }}
              </p>
            </div>
          </div>

          <div class="relative flex gap-8 group">
            <div
              class="relative z-10 w-12 h-12 shrink-0 flex items-center justify-center rounded-full index-section-bg border-2 border-[var(--primary-accent)] border-opacity-10 index-border-color text-[var(--primary-accent)] shadow-sm group-hover:border-[var(--primary-accent)] group-hover:scale-110 transition-all duration-300">
              <span class="font-bold text-lg">2</span>
            </div>
            <div class="pt-1">
              <h3 class="text-xl font-bold index-text-main mb-3">{{ __('index.step_2_title') }}</h3>
              <p class="index-text-muted leading-relaxed text-lg">
                {{ __('index.step_2_desc') }}
              </p>
            </div>
          </div>

          <div class="relative flex gap-8 group">
            <div
              class="relative z-10 w-12 h-12 shrink-0 flex items-center justify-center rounded-full index-section-bg border-2 border-[var(--primary-accent)] border-opacity-10 index-border-color text-[var(--primary-accent)] shadow-sm group-hover:border-[var(--primary-accent)] group-hover:scale-110 transition-all duration-300">
              <span class="font-bold text-lg">3</span>
            </div>
            <div class="pt-1">
              <h3 class="text-xl font-bold index-text-main mb-3">{{ __('index.step_3_title') }}</h3>
              <p class="index-text-muted leading-relaxed text-lg">
                {{ __('index.step_3_desc') }}
              </p>
            </div>
          </div>
        </div>

        <div class="mt-16">
          <a href="{{ route('post-task') }}"
            class="btn inline-flex items-center justify-center px-8 py-4 text-base font-semibold text-white transition-all duration-200 bg-[var(--primary-accent)] rounded-full hover:bg-[var(--primary-hover)] shadow-lg hover:-translate-y-1">
            {{ __('index.start_for_free') }}
          </a>
        </div>
      </div>

      <!-- Right side: Expanded Marketplace Window -->
      <div class="relative px-2 lg:px-0">

        <!-- Double Background (Expanded & Tilted) -->
        <div
          class="absolute -inset-6 bg-gradient-to-tr from-indigo-50 to-blue-50 dark:from-slate-800 dark:to-slate-800 rounded-[3rem] transform rotate-2 border border-gray-100 dark:border-slate-700/50 how-it-works-right">
        </div>

        <!-- Main Container -->
        <div
          class="index-section-bg rounded-[2.5rem] shadow-2xl border index-border-color relative overflow-hidden h-[750px] transform transition-transform hover:scale-[1.002] duration-500 flex flex-col how-it-works-inner">

          <!-- Header: Clean "Trending" State -->
          <div
            class="relative z-30 flex items-center justify-between px-8 py-6 border-b index-border-color index-section-bg backdrop-blur-xl">

            <!-- Title -->
            <div>
              <h3 class="text-2xl font-extrabold index-text-main tracking-tight leading-none">
                {{ __('index.explore') }}</h3>
              <p class="text-sm index-text-muted mt-1 font-medium">{{ __('index.browse_local_services') }}
              </p>
            </div>

            <!-- Trending Indicator (Visual Only - Not a Button) -->
            <div
              class="flex items-center gap-2 px-4 py-2 index-section-alt rounded-xl border index-border-color">
              <div class="p-1 cat-blue rounded-full">
                <i data-feather="trending-up" class="w-3 h-3"></i>
              </div>
              <div class="flex flex-col">

                <span
                  class="text-xs font-bold index-text-main leading-none">{{ __('index.trending_now') }}</span>
              </div>
            </div>
          </div>

          <!-- Scrolling Grid Content -->
          <div id="mockup-grid-parent"
            class="grid grid-cols-2 gap-5 relative flex-1 overflow-hidden p-6 index-section-alt transition-all duration-300">

            <!-- The Fog (Premium Fade Masks) -->
            <div
              class="absolute top-0 left-0 right-0 h-32 index-fog-top z-20 pointer-events-none">
            </div>
            <div
              class="absolute bottom-0 left-0 right-0 h-32 index-fog-bottom z-20 pointer-events-none">
            </div>

            <!-- Left Column (Scrolls Up) -->
            <div class="space-y-5 animate-scroll-up">
              <template id="task-cards-left">
                <!-- Card: Handyman -->
                <div
                  class="relative aspect-[4/5] rounded-[1.25rem] overflow-hidden group border index-border-color shadow-sm">
                  <img src="assets/img/handyman.jpg" alt="Handyman"
                    class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000">
                  <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent"></div>
                  <div class="absolute bottom-5 left-5 text-white">
                    <h4 class="font-bold text-lg leading-none mb-1 how-it-works-text">
                      {{ __('index.trending_categories.handyman') }}</h4>
                    <p class="text-xs text-gray-300 font-medium how-it-works-text-p">
                      {{ __('index.trending_categories.handyman_desc') }}</p>
                  </div>
                </div>

                <!-- Card: Plumbing -->
                <div
                  class="relative aspect-[4/5] rounded-[1.25rem] overflow-hidden group border border-white dark:border-slate-700 shadow-sm">
                  <img src="assets/img/plumbing.jpg" alt="Plumbing"
                    class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000">
                  <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent"></div>
                  <div class="absolute bottom-5 left-5 text-white">
                    <h4 class="font-bold text-lg leading-none mb-1 how-it-works-text">
                      {{ __('index.trending_categories.plumbing') }}</h4>
                    <p class="text-xs text-gray-300 font-medium how-it-works-text-p">
                      {{ __('index.trending_categories.plumbing_desc') }}</p>
                  </div>
                </div>

                <!-- Card: Delivery -->
                <div
                  class="relative aspect-[4/5] rounded-[1.25rem] overflow-hidden group border border-white dark:border-slate-700 shadow-sm">
                  <img src="assets/img/delivery.jpg" alt="Delivery"
                    class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000">
                  <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent"></div>
                  <div class="absolute bottom-5 left-5 text-white">
                    <h4 class="font-bold text-lg leading-none mb-1 how-it-works-text">
                      {{ __('index.trending_categories.delivery') }}</h4>
                    <p class="text-xs text-gray-300 font-medium how-it-works-text-p">
                      {{ __('index.trending_categories.delivery_desc') }}</p>
                  </div>
                </div>

                <!-- Card: Gardening -->
                <div
                  class="relative aspect-[4/5] rounded-[1.25rem] overflow-hidden group border border-white dark:border-slate-700 shadow-sm">
                  <img src="assets/img/gardening.jpg" alt="Gardening"
                    class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000">
                  <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent"></div>
                  <div class="absolute bottom-5 left-5 text-white">
                    <h4 class="font-bold text-lg leading-none mb-1 how-it-works-text">
                      {{ __('index.trending_categories.gardening') }}</h4>
                    <p class="text-xs text-gray-300 font-medium how-it-works-text-p">
                      {{ __('index.trending_categories.gardening_desc') }}</p>
                  </div>
                </div>
              </template>
              <div class="task-column" id="col-left"></div>
            </div>

            <!-- Right Column (Scrolls Down) -->
            <div class="space-y-5 animate-scroll-down">
              <template id="task-cards-right">
                <!-- Card: Cleaning -->
                <div
                  class="relative aspect-[4/5] rounded-[1.25rem] overflow-hidden group border border-white dark:border-slate-700 shadow-sm">
                  <img src="assets/img/house_cleaning.jpg" alt="Cleaning"
                    class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000">
                  <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent"></div>
                  <div class="absolute bottom-5 left-5 text-white">
                    <h4 class="font-bold text-lg leading-none mb-1 how-it-works-text">
                      {{ __('index.trending_categories.cleaning') }}</h4>
                    <p class="text-xs text-gray-300 font-medium how-it-works-text-p">
                      {{ __('index.trending_categories.cleaning_desc') }}</p>
                  </div>
                </div>

                <!-- Card: Beauty & Barber -->
                <div
                  class="relative aspect-[4/5] rounded-[1.25rem] overflow-hidden group border border-white dark:border-slate-700 shadow-sm">
                  <img src="assets/img/barbers.jpg" alt="Beauty"
                    class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000">
                  <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent"></div>
                  <div class="absolute bottom-5 left-5 text-white">
                    <h4 class="font-bold text-lg leading-none mb-1 how-it-works-text">
                      {{ __('index.trending_categories.beauty') }}</h4>
                    <p class="text-xs text-gray-300 font-medium how-it-works-text-p">
                      {{ __('index.trending_categories.beauty_desc') }}</p>
                  </div>
                </div>

                <!-- Card: Car Wash -->
                <div
                  class="relative aspect-[4/5] rounded-[1.25rem] overflow-hidden group border border-white dark:border-slate-700 shadow-sm">
                  <img src="assets/img/car_wash.jpg" alt="Car Wash"
                    class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000">
                  <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent"></div>
                  <div class="absolute bottom-5 left-5 text-white">
                    <h4 class="font-bold text-lg leading-none mb-1 how-it-works-text">
                      {{ __('index.trending_categories.car_wash') }}</h4>
                    <p class="text-xs text-gray-300 font-medium how-it-works-text-p">
                      {{ __('index.trending_categories.car_wash_desc') }}</p>
                  </div>
                </div>

                <!-- Card: Mechanic -->
                <div
                  class="relative aspect-[4/5] rounded-[1.25rem] overflow-hidden group border border-white dark:border-slate-700 shadow-sm">
                  <img src="assets/img/mechanic.jpg" alt="Mechanic"
                    class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000">
                  <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent"></div>
                  <div class="absolute bottom-5 left-5 text-white">
                    <h4 class="font-bold text-lg leading-none mb-1 how-it-works-text">
                      {{ __('index.trending_categories.mechanic') }}</h4>
                    <p class="text-xs text-gray-300 font-medium how-it-works-text-p">
                      {{ __('index.trending_categories.mechanic_desc') }}</p>
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
  <section class="relative py-24 px-6 index-section-alt transition-colors duration-300 overflow-hidden">

    <div class="relative max-w-7xl mx-auto">
      <!-- Header -->
      <div class="max-w-3xl mb-16 relative z-10">
        <h2 class="text-4xl md:text-5xl font-extrabold index-text-main tracking-tight mb-6">
          @lang('index.why_choose_title')
        </h2>
        <p class="text-lg index-text-muted leading-relaxed">
          {{ __('index.why_choose_desc') }}
        </p>
      </div>

      <!-- Cards Grid -->
      <div class="grid md:grid-cols-3 gap-8 relative z-10">

        <!-- Card 1 -->
        <div
          class="group feature-card rounded-2xl p-8 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 ease-out relative overflow-hidden">
          <div
            class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-400 to-blue-600 transform origin-left scale-x-0 group-hover:scale-x-100 transition-transform duration-300">
          </div>
          <div
            class="feature-icon-box cat-blue mb-6">
            <i data-feather="zap" class="w-7 h-7"></i>
          </div>
          <h3 class="text-2xl font-bold index-text-main mb-3">{{ __('index.fast_title') }}</h3>
          <p class="index-text-muted mb-6 leading-relaxed">
            {{ __('index.fast_desc') }}
          </p>
        </div>

        <!-- Card 2 -->
        <div
          class="group feature-card rounded-2xl p-8 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 ease-out relative overflow-hidden">
          <div
            class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-400 to-emerald-600 transform origin-left scale-x-0 group-hover:scale-x-100 transition-transform duration-300">
          </div>
          <div
            class="feature-icon-box cat-green mb-6">
            <i data-feather="shield" class="w-7 h-7"></i>
          </div>
          <h3 class="text-2xl font-bold index-text-main mb-3">{{ __('index.secure_title') }}</h3>
          <p class="index-text-muted mb-6 leading-relaxed">
            {{ __('index.secure_desc') }}
          </p>
        </div>

        <!-- Card 3 -->
        <div
          class="group feature-card rounded-2xl p-8 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 ease-out relative overflow-hidden">
          <div
            class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-indigo-400 to-indigo-600 transform origin-left scale-x-0 group-hover:scale-x-100 transition-transform duration-300">
          </div>
          <div
            class="feature-icon-box cat-purple mb-6">
            <i data-feather="sliders" class="w-7 h-7"></i>
          </div>
          <h3 class="text-2xl font-bold index-text-main mb-3">{{ __('index.price_title') }}</h3>
          <p class="index-text-muted mb-6 leading-relaxed">
            {{ __('index.price_desc') }}
          </p>
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
    <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-indigo-100 dark:bg-indigo-600 rounded-full blur-[150px] opacity-50 dark:opacity-20 pointer-events-none transition-colors duration-500">
    </div>
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
      <!-- Right Column: Dynamic Content Card -->
      <div class="relative perspective-container">
        <div id="testimonial-content">
          <blockquote class="relative z-10">
            <p id="t-quote" class="text-2xl md:text-3xl font-medium text-gray-800 dark:text-slate-100 leading-relaxed font-serif italic transition-colors duration-300">
              "Minijobz helped me find reliable helpers in minutes. It’s fast, simple, and finally gave me my weekends back."
            </p>
          </blockquote>
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
  <section class="py-24 index-section-alt transition-colors duration-300 overflow-hidden relative">
    <div class="max-w-7xl mx-auto px-6 mb-12 text-center">
      <h2 class="text-4xl md:text-5xl font-extrabold index-text-main tracking-tight">
        {{ __('index.popular_tasks_title') }}
      </h2>
      <p class="mt-4 text-lg index-text-muted">{{ __('index.popular_tasks_subtitle') }}</p>
    </div>

    <!-- Container with Edge Fade Mask -->
    <div class="relative scroll-mask group">

      <!-- Row 1: Scrolls Left -->
      <div class="flex gap-5 mb-6 w-max animate-scroll-left">
        <!-- CARD SET 1 -->
        <div
          class="w-[280px] popular-task-card rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between">
          <div>
            <div class="flex justify-between items-center mb-3">
              <span
                class="cat-badge cat-blue">{{ __('footer.delivery') }}</span>
              <span class="font-bold index-text-main text-lg">€85</span>
            </div>
            <h3 class="index-text-main font-semibold text-sm leading-snug mb-4">{{ __('index.popular_tasks.task_1_title') }}
            </h3>
          </div>
          <div class="flex items-center gap-3 pt-3 border-t index-border-color">
            <img src="https://i.pravatar.cc/150?img=11" alt="User"
              class="w-9 h-9 rounded-full object-cover border index-border-color">
            <div class="flex flex-col justify-center">
              <div class="flex text-amber-400 mb-0.5"><i data-feather="star" class="w-3 h-3 fill-current"></i><i
                  data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star"
                  class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i
                  data-feather="star" class="w-3 h-3 fill-current"></i></div>
              <span class="text-[11px] index-text-muted font-medium">12 {{ __('index.popular_tasks.reviews') }}</span>
            </div>
          </div>
        </div>
        <div
          class="w-[280px] index-section-bg index-border-color rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between popular-task-card">
          <div>
            <div class="flex justify-between items-center mb-3">
              <span
                class="cat-badge cat-green">{{ __('footer.cleaning') }}</span>
              <span class="font-bold index-text-main text-lg">€450</span>
            </div>
            <h3 class="index-text-main font-semibold text-sm leading-snug mb-4">{{ __('index.popular_tasks.task_2_title') }}
            </h3>
          </div>
          <div class="flex items-center gap-3 pt-3 border-t index-border-color">
            <img src="https://i.pravatar.cc/150?img=5" alt="User"
              class="w-9 h-9 rounded-full object-cover border index-border-color">
            <div class="flex flex-col justify-center">
              <div class="flex text-amber-400 mb-0.5"><i data-feather="star" class="w-3 h-3 fill-current"></i><i
                  data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star"
                  class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i
                  data-feather="star" class="w-3 h-3 fill-current"></i></div>
              <span class="text-[11px] index-text-muted font-medium">48 {{ __('index.popular_tasks.reviews') }}</span>
            </div>
          </div>
        </div>
        <div
          class="w-[280px] index-section-bg index-border-color rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between popular-task-card">
          <div>
            <div class="flex justify-between items-center mb-3">
              <span
                class="cat-badge cat-purple">{{ __('footer.assembly') }}</span>
              <span class="font-bold index-text-main text-lg">€120</span>
            </div>
            <h3 class="index-text-main font-semibold text-sm leading-snug mb-4">{{ __('index.popular_tasks.task_3_title') }}
            </h3>
          </div>
          <div class="flex items-center gap-3 pt-3 border-t index-border-color">
            <img src="https://i.pravatar.cc/150?img=3" alt="User"
              class="w-9 h-9 rounded-full object-cover border index-border-color">
            <div class="flex flex-col justify-center">
              <div class="flex text-amber-400 mb-0.5"><i data-feather="star" class="w-3 h-3 fill-current"></i><i
                  data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star"
                  class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i
                  data-feather="star" class="w-3 h-3 fill-current"></i></div>
              <span class="text-[11px] index-text-muted font-medium">32 {{ __('index.popular_tasks.reviews') }}</span>
            </div>
          </div>
        </div>
        <div
          class="w-[280px] index-section-bg index-border-color rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between popular-task-card">
          <div>
            <div class="flex justify-between items-center mb-3">
              <span
                class="cat-badge cat-orange">{{ __('footer.moving') }}</span>
              <span class="font-bold index-text-main text-lg">€95</span>
            </div>
            <h3 class="index-text-main font-semibold text-sm leading-snug mb-4">{{ __('index.popular_tasks.task_4_title') }}
            </h3>
          </div>
          <div class="flex items-center gap-3 pt-3 border-t index-border-color">
            <img src="https://i.pravatar.cc/150?img=59" alt="User"
              class="w-9 h-9 rounded-full object-cover border index-border-color">
            <div class="flex flex-col justify-center">
              <div class="flex text-amber-400 mb-0.5"><i data-feather="star" class="w-3 h-3 fill-current"></i><i
                  data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star"
                  class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i
                  data-feather="star" class="w-3 h-3 index-text-muted opacity-30"></i></div>
              <span class="text-[11px] index-text-muted font-medium">8 {{ __('index.popular_tasks.reviews') }}</span>
            </div>
          </div>
        </div>
        <!-- CARD SET 2 (Duplicate) -->
        <div
          class="w-[280px] index-section-bg index-border-color rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between popular-task-card">
          <div>
            <div class="flex justify-between items-center mb-3">
              <span
                class="cat-badge cat-blue">{{ __('footer.delivery') }}</span>
              <span class="font-bold index-text-main text-lg">€85</span>
            </div>
            <h3 class="index-text-main font-semibold text-sm leading-snug mb-4">{{ __('index.popular_tasks.task_1_title') }}
            </h3>
          </div>
          <div class="flex items-center gap-3 pt-3 border-t index-border-color">
            <img src="https://i.pravatar.cc/150?img=11" alt="User"
              class="w-9 h-9 rounded-full object-cover border index-border-color">
            <div class="flex flex-col justify-center">
              <div class="flex text-amber-400 mb-0.5"><i data-feather="star" class="w-3 h-3 fill-current"></i><i
                  data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star"
                  class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i
                  data-feather="star" class="w-3 h-3 fill-current"></i></div>
              <span class="text-[11px] index-text-muted font-medium">12 {{ __('index.popular_tasks.reviews') }}</span>
            </div>
          </div>
        </div>
        <div
          class="w-[280px] index-section-bg index-border-color rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between popular-task-card">
          <div>
            <div class="flex justify-between items-center mb-3">
              <span
                class="cat-badge cat-green">{{ __('footer.cleaning') }}</span>
              <span class="font-bold index-text-main text-lg">€450</span>
            </div>
            <h3 class="index-text-main font-semibold text-sm leading-snug mb-4">{{ __('index.popular_tasks.task_2_title') }}
            </h3>
          </div>
          <div class="flex items-center gap-3 pt-3 border-t index-border-color"><img src="https://i.pravatar.cc/150?img=5"
              alt="User" class="w-9 h-9 rounded-full object-cover border index-border-color">
            <div class="flex flex-col justify-center">
              <div class="flex text-amber-400 mb-0.5"><i data-feather="star" class="w-3 h-3 fill-current"></i><i
                  data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star"
                  class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i
                  data-feather="star" class="w-3 h-3 fill-current"></i></div><span
                class="text-[11px] index-text-muted font-medium">48 {{ __('index.popular_tasks.reviews') }}</span>
            </div>
          </div>
        </div>
        <div
          class="w-[280px] index-section-bg index-border-color rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between popular-task-card">
          <div>
            <div class="flex justify-between items-center mb-3">
              <span
                class="cat-badge cat-blue">{{ __('footer.delivery') }}</span>
              <span class="font-bold index-text-main text-lg">€85</span>
            </div>
            <h3 class="index-text-main font-semibold text-sm leading-snug mb-4">{{ __('index.popular_tasks.task_1_title') }}
            </h3>
          </div>
          <div class="flex items-center gap-3 pt-3 border-t index-border-color">
            <img src="https://i.pravatar.cc/150?img=11" alt="User"
              class="w-9 h-9 rounded-full object-cover border index-border-color">
            <div class="flex flex-col justify-center">
              <div class="flex text-amber-400 mb-0.5"><i data-feather="star" class="w-3 h-3 fill-current"></i><i
                  data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star"
                  class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i
                  data-feather="star" class="w-3 h-3 fill-current"></i></div>
              <span class="text-[11px] index-text-muted font-medium">12 {{ __('index.popular_tasks.reviews') }}</span>
            </div>
          </div>
        </div>
        <div
          class="w-[280px] bg-white border border-gray-200 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between">
          <div>
            <div class="flex justify-between items-center mb-3"><span
                class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide bg-orange-100 text-orange-700">{{ __('footer.moving') }}</span><span
                class="font-bold text-gray-900 text-lg">€95</span></div>
            <h3 class="text-gray-900 font-semibold text-sm leading-snug mb-4">{{ __('index.popular_tasks.task_4_title') }}
            </h3>
          </div>
          <div class="flex items-center gap-3 pt-3 border-t border-gray-100"><img src="https://i.pravatar.cc/150?img=59"
              alt="User" class="w-9 h-9 rounded-full object-cover border border-gray-200">
            <div class="flex flex-col justify-center">
              <div class="flex text-amber-400 mb-0.5"><i data-feather="star" class="w-3 h-3 fill-current"></i><i
                  data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star"
                  class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i
                  data-feather="star" class="w-3 h-3 text-gray-300"></i></div><span
                class="text-[11px] text-gray-500 font-medium">8 {{ __('index.popular_tasks.reviews') }}</span>
            </div>
          </div>
        </div>
        <!-- CARD SET 3 (Third Duplicate for seamless loop) -->
        <div
          class="w-[280px] bg-white border border-gray-200 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between">
          <div>
            <div class="flex justify-between items-center mb-3"><span
                class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide bg-blue-100 text-blue-700">{{ __('footer.delivery') }}</span><span
                class="font-bold text-gray-900 text-lg">€85</span></div>
            <h3 class="text-gray-900 font-semibold text-sm leading-snug mb-4">{{ __('index.popular_tasks.task_1_title') }}
            </h3>
          </div>
          <div class="flex items-center gap-3 pt-3 border-t border-gray-100"><img src="https://i.pravatar.cc/150?img=11"
              alt="User" class="w-9 h-9 rounded-full object-cover border border-gray-200">
            <div class="flex flex-col justify-center">
              <div class="flex text-amber-400 mb-0.5"><i data-feather="star" class="w-3 h-3 fill-current"></i><i
                  data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star"
                  class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i
                  data-feather="star" class="w-3 h-3 fill-current"></i></div><span
                class="text-[11px] text-gray-500 font-medium">12 {{ __('index.popular_tasks.reviews') }}</span>
            </div>
          </div>
        </div>
        <div
          class="w-[280px] bg-white index-border-color rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between popular-task-card">
          <div>
            <div class="flex justify-between items-center mb-3">
              <span
                class="cat-badge cat-green">{{ __('footer.cleaning') }}</span>
              <span class="font-bold index-text-main text-lg">€450</span>
            </div>
            <h3 class="index-text-main font-semibold text-sm leading-snug mb-4">{{ __('index.popular_tasks.task_2_title') }}
            </h3>
          </div>
          <div class="flex items-center gap-3 pt-3 border-t index-border-color"><img src="https://i.pravatar.cc/150?img=5"
              alt="User" class="w-9 h-9 rounded-full object-cover border index-border-color">
            <div class="flex flex-col justify-center">
              <div class="flex text-amber-400 mb-0.5"><i data-feather="star" class="w-3 h-3 fill-current"></i><i
                  data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star"
                  class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i
                  data-feather="star" class="w-3 h-3 fill-current"></i></div><span
                class="text-[11px] index-text-muted font-medium">48 {{ __('index.popular_tasks.reviews') }}</span>
            </div>
          </div>
        </div>
        <div
          class="w-[280px] bg-white index-border-color rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between popular-task-card">
          <div>
            <div class="flex justify-between items-center mb-3"><span
                class="cat-badge cat-purple">{{ __('footer.assembly') }}</span><span
                class="font-bold index-text-main text-lg">€120</span></div>
            <h3 class="index-text-main font-semibold text-sm leading-snug mb-4">{{ __('index.popular_tasks.task_3_title') }}
            </h3>
          </div>
          <div class="flex items-center gap-3 pt-3 border-t index-border-color"><img src="https://i.pravatar.cc/150?img=3"
              alt="User" class="w-9 h-9 rounded-full object-cover border index-border-color">
            <div class="flex flex-col justify-center">
              <div class="flex text-amber-400 mb-0.5"><i data-feather="star" class="w-3 h-3 fill-current"></i><i
                  data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star"
                  class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i
                  data-feather="star" class="w-3 h-3 fill-current"></i></div><span
                class="text-[11px] index-text-muted font-medium">32 {{ __('index.popular_tasks.reviews') }}</span>
            </div>
          </div>
        </div>
        <div
          class="w-[280px] bg-white index-border-color rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between popular-task-card">
          <div>
            <div class="flex justify-between items-center mb-3"><span
                class="cat-badge cat-orange">{{ __('footer.moving') }}</span><span
                class="font-bold index-text-main text-lg">€95</span></div>
            <h3 class="index-text-main font-semibold text-sm leading-snug mb-4">{{ __('index.popular_tasks.task_4_title') }}
            </h3>
          </div>
          <div class="flex items-center gap-3 pt-3 border-t index-border-color"><img src="https://i.pravatar.cc/150?img=59"
              alt="User" class="w-9 h-9 rounded-full object-cover border index-border-color">
            <div class="flex flex-col justify-center">
              <div class="flex text-amber-400 mb-0.5"><i data-feather="star" class="w-3 h-3 fill-current"></i><i
                  data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star"
                  class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i
                  data-feather="star" class="w-3 h-3 index-text-muted opacity-30"></i></div><span
                class="text-[11px] index-text-muted font-medium">8 {{ __('index.popular_tasks.reviews') }}</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Row 2: Scrolls Right -->
      <div class="flex gap-5 w-max animate-scroll-right">
        <!-- CARD SET 1 -->
        <div
          class="w-[280px] bg-white index-border-color rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between popular-task-card">
          <div>
            <div class="flex justify-between items-center mb-3">
              <span
                class="cat-badge cat-red">{{ __('footer.removals') }}</span>
              <span class="font-bold index-text-main text-lg">€60</span>
            </div>
            <h3 class="index-text-main font-semibold text-sm leading-snug mb-4">{{ __('index.popular_tasks.task_5_title') }}
            </h3>
          </div>
          <div class="flex items-center gap-3 pt-3 border-t index-border-color">
            <img src="https://i.pravatar.cc/150?img=12" alt="User"
              class="w-9 h-9 rounded-full object-cover border index-border-color">
            <div class="flex flex-col justify-center">
              <div class="flex text-amber-400 mb-0.5"><i data-feather="star" class="w-3 h-3 fill-current"></i><i
                  data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star"
                  class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i
                  data-feather="star" class="w-3 h-3 fill-current"></i></div>
              <span class="text-[11px] index-text-muted font-medium">22 {{ __('index.popular_tasks.reviews') }}</span>
            </div>
          </div>
        </div>
        <div
          class="w-[280px] bg-white index-border-color rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between popular-task-card">
          <div>
            <div class="flex justify-between items-center mb-3">
              <span
                class="cat-badge cat-red">{{ __('footer.removals') }}</span>
              <span class="font-bold index-text-main text-lg">€506</span>
            </div>
            <h3 class="index-text-main font-semibold text-sm leading-snug mb-4">{{ __('index.popular_tasks.task_6_title') }}
            </h3>
          </div>
          <div class="flex items-center gap-3 pt-3 border-t index-border-color">
            <img src="https://i.pravatar.cc/150?img=60" alt="User"
              class="w-9 h-9 rounded-full object-cover border index-border-color">
            <div class="flex flex-col justify-center">
              <div class="flex text-amber-400 mb-0.5"><i data-feather="star" class="w-3 h-3 fill-current"></i><i
                  data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star"
                  class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i
                  data-feather="star" class="w-3 h-3 fill-current"></i></div>
              <span class="text-[11px] index-text-muted font-medium">145 {{ __('index.popular_tasks.reviews') }}</span>
            </div>
          </div>
        </div>
        <div
          class="w-[280px] bg-white index-border-color rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between popular-task-card">
          <div>
            <div class="flex justify-between items-center mb-3">
              <span
                class="cat-badge cat-teal">{{ __('footer.gardening') }}</span>
              <span class="font-bold index-text-main text-lg">€75</span>
            </div>
            <h3 class="index-text-main font-semibold text-sm leading-snug mb-4">{{ __('index.popular_tasks.task_7_title') }}
            </h3>
          </div>
          <div class="flex items-center gap-3 pt-3 border-t index-border-color">
            <img src="https://i.pravatar.cc/150?img=68" alt="User"
              class="w-9 h-9 rounded-full object-cover border index-border-color">
            <div class="flex flex-col justify-center">
              <div class="flex text-amber-400 mb-0.5"><i data-feather="star" class="w-3 h-3 fill-current"></i><i
                  data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star"
                  class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i
                  data-feather="star" class="w-3 h-3 index-text-muted opacity-30"></i></div>
              <span class="text-[11px] index-text-muted font-medium">9 {{ __('index.popular_tasks.reviews') }}</span>
            </div>
          </div>
        </div>
        <div
          class="w-[280px] bg-white index-border-color rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between popular-task-card">
          <div>
            <div class="flex justify-between items-center mb-3">
              <span
                class="cat-badge cat-blue">{{ __('footer.tech') }}</span>
              <span class="font-bold index-text-main text-lg">€150</span>
            </div>
            <h3 class="index-text-main font-semibold text-sm leading-snug mb-4">{{ __('index.popular_tasks.task_8_title') }}
            </h3>
          </div>
          <div class="flex items-center gap-3 pt-3 border-t index-border-color">
            <img src="https://i.pravatar.cc/150?img=33" alt="User"
              class="w-9 h-9 rounded-full object-cover border index-border-color">
            <div class="flex flex-col justify-center">
              <div class="flex text-amber-400 mb-0.5"><i data-feather="star" class="w-3 h-3 fill-current"></i><i
                  data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star"
                  class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i
                  data-feather="star" class="w-3 h-3 fill-current"></i></div>
              <span class="text-[11px] index-text-muted font-medium">41 {{ __('index.popular_tasks.reviews') }}</span>
            </div>
          </div>
        </div>
        <!-- CARD SET 2 (THIS WAS MISSING: Duplicate for Loop) -->
        <div
          class="w-[280px] index-section-bg index-border-color rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between popular-task-card">
          <div>
            <div class="flex justify-between items-center mb-3">
              <span
                class="cat-badge cat-red">{{ __('footer.removals') }}</span>
              <span class="font-bold index-text-main text-lg">€60</span>
            </div>
            <h3 class="index-text-main font-semibold text-sm leading-snug mb-4">{{ __('index.popular_tasks.task_5_title') }}
            </h3>
          </div>
          <div class="flex items-center gap-3 pt-3 border-t index-border-color">
            <img src="https://i.pravatar.cc/150?img=12" alt="User"
              class="w-9 h-9 rounded-full object-cover border index-border-color">
            <div class="flex flex-col justify-center">
              <div class="flex text-amber-400 mb-0.5"><i data-feather="star" class="w-3 h-3 fill-current"></i><i
                  data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star"
                  class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i
                  data-feather="star" class="w-3 h-3 fill-current"></i></div>
              <span class="text-[11px] index-text-muted font-medium">22 {{ __('index.popular_tasks.reviews') }}</span>
            </div>
          </div>
        </div>
        <div
          class="w-[280px] bg-white index-border-color rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between popular-task-card">
          <div>
            <div class="flex justify-between items-center mb-3">
              <span
                class="cat-badge cat-red">{{ __('footer.removals') }}</span>
              <span class="font-bold index-text-main text-lg">€506</span>
            </div>
            <h3 class="index-text-main font-semibold text-sm leading-snug mb-4">{{ __('index.popular_tasks.task_6_title') }}
            </h3>
          </div>
          <div class="flex items-center gap-3 pt-3 border-t index-border-color">
            <img src="https://i.pravatar.cc/150?img=60" alt="User"
              class="w-9 h-9 rounded-full object-cover border index-border-color">
            <div class="flex flex-col justify-center">
               <div class="flex text-amber-400 mb-0.5"><i data-feather="star" class="w-3 h-3 fill-current"></i><i
                  data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star"
                  class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i
                  data-feather="star" class="w-3 h-3 fill-current"></i></div>
              <span class="text-[11px] index-text-muted font-medium">145 {{ __('index.popular_tasks.reviews') }}</span>
            </div>
          </div>
        </div>
        <div
          class="w-[280px] bg-white index-border-color rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between popular-task-card">
          <div>
            <div class="flex justify-between items-center mb-3">
              <span
                class="cat-badge cat-teal">{{ __('footer.gardening') }}</span>
              <span class="font-bold index-text-main text-lg">€75</span>
            </div>
            <h3 class="index-text-main font-semibold text-sm leading-snug mb-4">{{ __('index.popular_tasks.task_7_title') }}
            </h3>
          </div>
          <div class="flex items-center gap-3 pt-3 border-t index-border-color">
            <img src="https://i.pravatar.cc/150?img=68" alt="User"
              class="w-9 h-9 rounded-full object-cover border index-border-color">
            <div class="flex flex-col justify-center">
              <div class="flex text-amber-400 mb-0.5"><i data-feather="star" class="w-3 h-3 fill-current"></i><i
                  data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star"
                  class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i
                  data-feather="star" class="w-3 h-3 index-text-muted opacity-30"></i></div>
              <span class="text-[11px] index-text-muted font-medium">9 {{ __('index.popular_tasks.reviews') }}</span>
            </div>
          </div>
        </div>
        <div
          class="w-[280px] bg-white index-border-color rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between popular-task-card">
          <div>
            <div class="flex justify-between items-center mb-3">
              <span
                class="cat-badge cat-blue">{{ __('footer.tech') }}</span>
              <span class="font-bold index-text-main text-lg">€150</span>
            </div>
            <h3 class="index-text-main font-semibold text-sm leading-snug mb-4">{{ __('index.popular_tasks.task_8_title') }}
            </h3>
          </div>
          <div class="flex items-center gap-3 pt-3 border-t index-border-color">
            <img src="https://i.pravatar.cc/150?img=33" alt="User"
              class="w-9 h-9 rounded-full object-cover border index-border-color">
            <div class="flex flex-col justify-center">
              <div class="flex text-amber-400 mb-0.5"><i data-feather="star" class="w-3 h-3 fill-current"></i><i
                  data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star"
                  class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i
                  data-feather="star" class="w-3 h-3 fill-current"></i></div>
              <span class="text-[11px] index-text-muted font-medium">41 {{ __('index.popular_tasks.reviews') }}</span>
            </div>
          </div>
        </div>
        <!-- CARD SET 3 (Third Duplicate for seamless loop) -->
        <div
          class="w-[280px] bg-white index-border-color rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between popular-task-card">
          <div>
            <div class="flex justify-between items-center mb-3">
              <span
                class="cat-badge cat-red">{{ __('footer.removals') }}</span>
              <span class="font-bold index-text-main text-lg">€60</span>
            </div>
            <h3 class="index-text-main font-semibold text-sm leading-snug mb-4">{{ __('index.popular_tasks.task_5_title') }}
            </h3>
          </div>
          <div class="flex items-center gap-3 pt-3 border-t index-border-color">
            <img src="https://i.pravatar.cc/150?img=12" alt="User"
              class="w-9 h-9 rounded-full object-cover border index-border-color">
            <div class="flex flex-col justify-center">
              <div class="flex text-amber-400 mb-0.5"><i data-feather="star" class="w-3 h-3 fill-current"></i><i
                  data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star"
                  class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i
                  data-feather="star" class="w-3 h-3 fill-current"></i></div>
              <span class="text-[11px] index-text-muted font-medium">22 {{ __('index.popular_tasks.reviews') }}</span>
            </div>
          </div>
        </div>
        <div
          class="w-[280px] bg-white index-border-color rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between popular-task-card">
          <div>
            <div class="flex justify-between items-center mb-3">
              <span
                class="cat-badge cat-red">{{ __('footer.removals') }}</span>
              <span class="font-bold index-text-main text-lg">€506</span>
            </div>
            <h3 class="index-text-main font-semibold text-sm leading-snug mb-4">{{ __('index.popular_tasks.task_6_title') }}
            </h3>
          </div>
          <div class="flex items-center gap-3 pt-3 border-t index-border-color">
            <img src="https://i.pravatar.cc/150?img=60" alt="User"
              class="w-9 h-9 rounded-full object-cover border index-border-color">
            <div class="flex flex-col justify-center">
              <div class="flex text-amber-400 mb-0.5"><i data-feather="star" class="w-3 h-3 fill-current"></i><i
                  data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star"
                  class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i
                  data-feather="star" class="w-3 h-3 fill-current"></i></div>
              <span class="text-[11px] index-text-muted font-medium">145 {{ __('index.popular_tasks.reviews') }}</span>
            </div>
          </div>
        </div>
        <div
          class="w-[280px] bg-white index-border-color rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between popular-task-card">
          <div>
            <div class="flex justify-between items-center mb-3">
              <span
                class="cat-badge cat-teal">{{ __('footer.gardening') }}</span>
              <span class="font-bold index-text-main text-lg">€75</span>
            </div>
            <h3 class="index-text-main font-semibold text-sm leading-snug mb-4">{{ __('index.popular_tasks.task_7_title') }}
            </h3>
          </div>
          <div class="flex items-center gap-3 pt-3 border-t index-border-color">
            <img src="https://i.pravatar.cc/150?img=68" alt="User"
              class="w-9 h-9 rounded-full object-cover border index-border-color">
            <div class="flex flex-col justify-center">
              <div class="flex text-amber-400 mb-0.5"><i data-feather="star" class="w-3 h-3 fill-current"></i><i
                  data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star"
                  class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i
                  data-feather="star" class="w-3 h-3 index-text-muted opacity-30"></i></div>
              <span class="text-[11px] index-text-muted font-medium">9 {{ __('index.popular_tasks.reviews') }}</span>
            </div>
          </div>
        </div>
        <div
          class="w-[280px] bg-white index-border-color rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between popular-task-card">
          <div>
            <div class="flex justify-between items-center mb-3">
              <span
                class="cat-badge cat-blue">{{ __('footer.tech') }}</span>
              <span class="font-bold index-text-main text-lg">€150</span>
            </div>
            <h3 class="index-text-main font-semibold text-sm leading-snug mb-4">{{ __('index.popular_tasks.task_8_title') }}
            </h3>
          </div>
          <div class="flex items-center gap-3 pt-3 border-t index-border-color">
            <img src="https://i.pravatar.cc/150?img=33" alt="User"
              class="w-9 h-9 rounded-full object-cover border index-border-color">
            <div class="flex flex-col justify-center">
              <div class="flex text-amber-400 mb-0.5"><i data-feather="star" class="w-3 h-3 fill-current"></i><i
                  data-feather="star" class="w-3 h-3 fill-current"></i><i data-feather="star"
                  class="w-3 h-3 fill-current"></i><i data-feather="star" class="w-3 h-3 fill-current"></i><i
                  data-feather="star" class="w-3 h-3 fill-current"></i></div>
              <span class="text-[11px] index-text-muted font-medium">41 {{ __('index.popular_tasks.reviews') }}</span>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>

  <!-- Take Minijobz Anywhere - Modern App Card -->
  <section class="py-24 px-4 md:px-6 index-section-bg transition-colors duration-300">

    <!-- Main Gradient Card -->
    <div
      class="max-w-7xl mx-auto app-card-gradient rounded-[2.5rem] shadow-2xl overflow-hidden relative">

      <!-- Decorative Background Glows -->
      <div
        class="absolute top-0 right-0 w-[600px] h-[600px] bg-white opacity-10 blur-[120px] rounded-full pointer-events-none -translate-y-1/2 translate-x-1/2">
      </div>
      <div
        class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-[var(--primary-accent)] opacity-20 blur-[100px] rounded-full pointer-events-none translate-y-1/3 -translate-x-1/3">
      </div>

      <div class="grid lg:grid-cols-12 gap-12 items-center relative z-10 p-8 md:p-16">

        <!-- Left Content (Text + Buttons) -->
        <div class="lg:col-span-7 flex flex-col justify-center text-center lg:text-left">
          <div class="inline-flex items-center justify-center lg:justify-start gap-2 mb-6">
            <span
              class="px-3 py-1 rounded-full bg-white/20 border border-white/30 text-white text-xs font-bold uppercase tracking-widest backdrop-blur-sm">
              {{ __('index.mobile_app_badge') }}
            </span>
          </div>

          <h2 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white mb-6 leading-tight">
            @lang('index.mobile_app_title')
          </h2>

          <p class="text-lg text-white opacity-80 mb-10 max-w-xl mx-auto lg:mx-0 leading-relaxed">
            {{ __('index.mobile_app_desc') }}
          </p>

          <!-- Buttons & QR Row -->
          <div class="flex flex-col sm:flex-row items-center gap-8 justify-center lg:justify-start">

            <!-- Store Buttons -->
            <div class="flex gap-4">
              <a href="#">
                <img src="https://upload.wikimedia.org/wikipedia/commons/3/3c/Download_on_the_App_Store_Badge.svg"
                  alt="App Store" class="h-12 cursor-pointer transition-transform hover:scale-105">
              </a>
              <a href="#">
                <img src="https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg"
                  alt="Google Play" class="h-12 cursor-pointer transition-transform hover:scale-105">
              </a>
            </div>

            <!-- Divider for Mobile -->
            <div class="hidden sm:block w-px h-24 bg-[var(--primary-accent)] opacity-30"></div>

            <!-- QR Code Block -->
            <div class="hidden sm:flex flex-col items-center gap-3">
              <div class="qr-code-wrapper p-2 bg-white rounded-xl shadow-inner">
                <!-- Placeholder QR Code -->
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=MinijobzAppDownload"
                  alt="Scan to download" class="w-24 h-24 mix-blend-multiply opacity-90">
              </div>
              <span
                class="text-xs font-medium text-[var(--primary-accent)] filter brightness-200 tracking-wide uppercase">{{ __('index.scan_to_install') }}</span>
            </div>

          </div>
        </div>

        <!-- Right Image (Phone Mockup) -->
        <div
          class="lg:col-span-5 relative flex items-center justify-center lg:justify-end h-full min-h-[300px] lg:min-h-auto mt-8 lg:mt-0">
          <!-- The Image Container - Tilted Effect -->
          <div
            class="relative w-64 md:w-80 lg:w-[22rem] transition-transform duration-500 hover:scale-[1.02] hover:-rotate-1">
            <!-- Phone Shadow/Glow -->
            <div class="absolute inset-4 bg-[var(--primary-accent)] filter brightness-50 rounded-[3rem] blur-2xl opacity-60"></div>

            <!-- Actual Image -->
            <img src="https://assets.codepen.io/7729268/iphone-mockup-minijobz.png"
              onerror="this.src='assets/img/phone_14_01.webp'" alt="Minijobz App Interface"
              class="relative z-10 drop-shadow-2xl transform lg:translate-y-12">

            <!-- Floating Elements (Decoration) -->
            <div
              class="mockup-notification mockup-notif-1 absolute -left-8 top-1/4 z-20 p-3 rounded-2xl shadow-xl animate-bounce backdrop-blur-md border border-[var(--index-border)]">
              <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full flex items-center justify-center mockup-success-badge">
                  <i data-feather="check" class="w-4 h-4"></i>
                </div>
                <div>
                  <p class="text-xs font-bold index-text-main">{{ __('index.mockup.task_completed') }}</p>
                  <p class="text-[10px] index-text-muted">{{ __('index.mockup.payment_released') }}</p>
                </div>
              </div>
            </div>

            <!-- Notification Card -->
            <div
              class="mockup-notification mockup-notif-2 absolute -right-4 bottom-8 z-20 p-3 rounded-2xl shadow-xl animate-bounce backdrop-blur-md border index-border-color">
              <div class="flex items-center gap-3">
                <img src="https://i.pravatar.cc/150?img=12" class="w-8 h-8 rounded-full border index-border-color">
                <div>
                  <p class="text-xs font-bold index-text-main">{{ __('index.mockup.new_offer') }}</p>
                  <p class="text-[10px] index-text-muted">James W. {{ __('index.mockup.interested') }}</p>
                </div>
              </div>
            </div>

          </div>
        </div>

      </div>
    </div>
  </section>
<script type="module">
    import { HomeManager } from "{{ asset('js/components/home-manager.js') }}";
    document.addEventListener('DOMContentLoaded', () => {
        new HomeManager({
            testimonials: @json(__('index.testimonials'))
        });
    });
</script>

</div>
@endsection