  <!-- 🔹 NAVBAR (overrideable) -->
  @hasSection('navbar')
    @yield('navbar')
  @else

    {{-- ===== MOBILE NAVBAR (visible < 768px)=====--}} 
    <nav class="mobile-navbar bg-[var(--nav-bg)] border-b border-[var(--nav-border)] shadow-sm" aria-label="Mobile main navigation">
      {{-- Left: Hamburger --}}
      <button class="mobile-hamburger" id="mobileHamburger" aria-label="Open sidebar menu" aria-controls="mobileSidebar"
        aria-expanded="false">
        <span class="bg-[var(--text-primary)]"></span>
        <span class="bg-[var(--text-primary)]"></span>
        <span class="bg-[var(--text-primary)]"></span>
      </button>

      {{-- Center: Logo --}}
      <a href="{{ route('index') }}" class="flex items-center logo-link">
        <img src="{{ asset('assets/img/logo.png') }}" alt="Minijobz Logo" class="logo-img dark:brightness-0 dark:invert"
          style="height: 28px; width: auto; object-fit: contain;">
      </a>

      {{-- Right: Profile picture / Login --}}
      <div style="position: relative;">
        @auth
          @php
            $currentUser = auth()->user();
            $fullName = trim(($currentUser->first_name ?? '') . ' ' . ($currentUser->last_name ?? ''))
              ?: ($currentUser->name ?? $currentUser->email);
            $avatarSrc = $currentUser->avatar_url;
            $unreadCount = $currentUser->unreadNotifications()->count();
            $notifications = $currentUser->unreadNotifications()->limit(5)->get();
          @endphp
          <button class="mobile-profile-btn" id="mobileProfileBtn" type="button" aria-label="Open profile menu"
            aria-controls="mobileProfileDropdown" aria-expanded="false">
            <img src="{{ $avatarSrc }}" alt="" aria-hidden="true" class="w-8 h-8 rounded-full object-cover border border-[var(--nav-border)]">
            @if($unreadCount > 0)
              <span class="sr-only">{{ $unreadCount }} unread notifications</span>
              <span class="notification-dot bg-[var(--details-error)] text-white" aria-hidden="true">{{ $unreadCount }}</span>
            @endif
          </button>

          {{-- Mobile profile dropdown --}}
          <div class="mobile-profile-dropdown bg-[var(--nav-dropdown-bg)] border border-[var(--nav-dropdown-border)] shadow-xl rounded-xl" id="mobileProfileDropdown">
            <a href="{{ route('public-profile', auth()->id()) }}"
              class="mobile-profile-dropdown-user block no-underline transition-colors hover:bg-[var(--nav-dropdown-hover)] p-4 border-b border-[var(--nav-dropdown-border)]">
              <h4 class="text-sm font-bold text-[var(--text-primary)] mb-0">{{ $fullName }}</h4>
              <p class="text-xs text-[var(--nav-muted)] mt-1 mb-0">{{ __('navbar.public_profile') }}</p>
            </a>
            <div class="mobile-profile-dropdown-links p-2">
              <a href="{{ route('my-tasks') }}" class="flex items-center gap-2 p-2 rounded-lg text-[var(--text-primary)] hover:bg-[var(--nav-dropdown-hover)] no-underline">
                <i data-feather="grid" class="w-4 h-4"></i> {{ __('navbar.dashboard') }}
              </a>
              <a href="{{ route('messages') }}" class="flex items-center gap-2 p-2 rounded-lg text-[var(--text-primary)] hover:bg-[var(--nav-dropdown-hover)] no-underline">
                <i data-feather="message-square" class="w-4 h-4"></i> {{ __('navbar.messages') ?? 'Messages' }}
              </a>
              <a href="{{ route('notifications') }}" class="flex items-center gap-2 p-2 rounded-lg text-[var(--text-primary)] hover:bg-[var(--nav-dropdown-hover)] no-underline">
                <i data-feather="bell" class="w-4 h-4"></i> {{ __('navbar.notifications') }}
                @if($unreadCount > 0)
                  <span class="unread-badge bg-blue-600 text-white text-[10px] px-1.5 py-0.5 rounded-full ml-auto">{{ $unreadCount }}</span>
                @endif
              </a>
              @if($unreadCount > 0)
                <button type="button" class="mark-all-read-trigger w-full flex items-center gap-2 p-2 rounded-lg text-[var(--primary-accent)] hover:bg-[var(--nav-dropdown-hover)] transition-colors text-xs font-bold pl-8">
                  <i data-feather="check-square" class="w-3.5 h-3.5"></i>
                  {{ __('navbar.mark_all_read') }}
                </button>
              @endif
              <a href="{{ route('profile') }}" class="flex items-center gap-2 p-2 rounded-lg text-[var(--text-primary)] hover:bg-[var(--nav-dropdown-hover)] no-underline">
                <i data-feather="user" class="w-4 h-4"></i> {{ __('navbar.profile') }}
              </a>
            </div>
            <div class="mobile-profile-dropdown-divider border-t border-[var(--nav-dropdown-border)]"></div>
            <div class="mobile-profile-dropdown-links p-2">
              <a href="#" class="logout-trigger flex items-center gap-2 p-2 rounded-lg text-[var(--details-error)] hover:bg-[var(--details-error-bg)] no-underline">
                <i data-feather="log-out" class="w-4 h-4"></i> {{ __('navbar.logout') }}
              </a>
            </div>
          </div>
        @endauth

        @guest
          <div class="mobile-guest-btns flex items-center gap-2">
            <a href="{{ route('login') }}" class="mobile-login-btn px-4 py-1.5 rounded-lg bg-[var(--primary-accent)] text-white text-sm font-medium no-underline">{{ __('navbar.login') }}</a>
            <a href="{{ route('register') }}" class="mobile-signup-btn px-4 py-1.5 rounded-lg border border-[var(--primary-accent)] text-[var(--primary-accent)] text-sm font-medium no-underline">{{ __('navbar.sign_up') }}</a>
          </div>
        @endguest
      </div>
      </nav>

      {{-- Mobile Sidebar Overlay --}}
      <div class="mobile-sidebar-overlay" id="mobileSidebarOverlay"></div>

      {{-- Mobile Sidebar --}}
      <div class="mobile-sidebar bg-[var(--nav-bg)] border-r border-[var(--nav-border)]" id="mobileSidebar" role="dialog" aria-modal="true" aria-label="Main menu sidebar">
        <div class="mobile-sidebar-header border-b border-[var(--nav-border)] p-4 flex justify-between items-center">
          <a href="{{ route('index') }}" class="logo-link">
            <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" class="logo-img dark:brightness-0 dark:invert"
              style="height: 26px; width: auto;">
          </a>
          <button class="mobile-sidebar-close text-[var(--text-primary)] hover:bg-[var(--bg-hover)] rounded-lg p-1" id="mobileSidebarClose" aria-label="Close menu">&times;</button>
        </div>

        @auth
          <a href="{{ route('public-profile', auth()->id()) }}"
            class="mobile-sidebar-user flex items-center gap-3 no-underline transition-colors hover:bg-[var(--nav-dropdown-hover)] p-4 border-b border-[var(--nav-border)]">
            <img src="{{ $avatarSrc }}" alt="Profile" class="w-10 h-10 rounded-full object-cover border border-[var(--nav-border)]">
            <div>
              <div class="mobile-sidebar-user-name text-sm font-bold text-[var(--text-primary)]">{{ $fullName }}</div>
              <div class="mobile-sidebar-user-sub text-xs text-[var(--nav-muted)]">{{ __('navbar.public_profile') }}</div>
            </div>
          </a>
        @endauth

        <div class="mobile-sidebar-nav p-4">
          <div class="mobile-sidebar-section-label text-[10px] uppercase tracking-wider font-bold text-[var(--nav-muted)] mb-3">{{ __('navbar.categories') }}</div>
          <a href="{{ url('category') }}" class="mobile-sidebar-link flex items-center gap-3 p-2 rounded-lg text-[var(--text-primary)] hover:bg-[var(--nav-dropdown-hover)] no-underline mb-1">
            <i data-feather="grid" class="w-4 h-4"></i> {{ __('navbar.categories') }}
          </a>
          <a href="{{ route('messages') }}" class="mobile-sidebar-link flex items-center gap-3 p-2 rounded-lg text-[var(--text-primary)] hover:bg-[var(--nav-dropdown-hover)] no-underline mb-1">
            <i data-feather="message-square" class="w-4 h-4"></i> {{ __('navbar.messages') ?? 'Messages' }}
          </a>

          <a href="{{ route('post-task') }}" class="check-login-trigger mobile-sidebar-cta btn w-full bg-[var(--primary-accent)] text-white font-bold py-3 rounded-xl mt-4 text-center">
            {{ __('navbar.post_task') }}
          </a>

          <div class="mobile-sidebar-divider border-t border-[var(--nav-border)] my-6"></div>
          <div class="mobile-sidebar-section-label text-[10px] uppercase tracking-wider font-bold text-[var(--nav-muted)] mb-3">{{ __('navbar.navigation') }}</div>

          <a href="{{ url('/index') }}" class="mobile-sidebar-link flex items-center gap-3 p-2 rounded-lg text-[var(--text-primary)] hover:bg-[var(--nav-dropdown-hover)] no-underline mb-1">
            <i data-feather="home" class="w-4 h-4"></i> {{ __('navbar.home') }}
          </a>
          <a href="{{ url('tasks') }}" class="mobile-sidebar-link flex items-center gap-3 p-2 rounded-lg text-[var(--text-primary)] hover:bg-[var(--nav-dropdown-hover)] no-underline mb-1">
            <i data-feather="search" class="w-4 h-4"></i> {{ __('navbar.browse_tasks') }}
          </a>
          <a href="{{ url('howitworks') }}" class="mobile-sidebar-link flex items-center gap-3 p-2 rounded-lg text-[var(--text-primary)] hover:bg-[var(--nav-dropdown-hover)] no-underline mb-1">
            <i data-feather="help-circle" class="w-4 h-4"></i> {{ __('navbar.how_it_works') }}
          </a>

          @auth
            <div class="mobile-sidebar-divider border-t border-[var(--nav-border)] my-6"></div>
            <div class="mobile-sidebar-section-label text-[10px] uppercase tracking-wider font-bold text-[var(--nav-muted)] mb-3">{{ __('navbar.dashboard') }}</div>
            <a href="{{ route('my-tasks') }}" class="mobile-sidebar-link flex items-center gap-3 p-2 rounded-lg text-[var(--text-primary)] hover:bg-[var(--nav-dropdown-hover)] no-underline mb-1">
              <i data-feather="clipboard" class="w-4 h-4"></i> {{ __('navbar.dashboard') }}
            </a>
            <a href="{{ route('notifications') }}" class="mobile-sidebar-link flex items-center gap-3 p-2 rounded-lg text-[var(--text-primary)] hover:bg-[var(--nav-dropdown-hover)] no-underline mb-1">
              <i data-feather="bell" class="w-4 h-4"></i> {{ __('navbar.notifications') }}
              @if($unreadCount > 0)
                  <span class="unread-badge bg-blue-600 text-white text-[10px] px-1.5 py-0.5 rounded-full ml-auto">{{ $unreadCount }}</span>
              @endif
            </a>
          @endauth

          <div class="mobile-sidebar-divider border-t border-[var(--nav-border)] my-6"></div>
          <div class="mobile-sidebar-section-label text-[10px] uppercase tracking-wider font-bold text-[var(--nav-muted)] mb-3">{{ __('navbar.settings') }}</div>
          <a href="{{ route('profile', ['tab' => 'account']) }}" class="mobile-sidebar-link flex items-center gap-3 p-2 rounded-lg text-[var(--text-primary)] hover:bg-[var(--nav-dropdown-hover)] no-underline mb-1">
            <i data-feather="settings" class="w-4 h-4"></i> {{ __('navbar.settings') }}
          </a>

          <div class="mobile-sidebar-divider border-t border-[var(--nav-border)] my-6"></div>

        </div>

        @guest
          <div class="mobile-sidebar-guest-container p-4 space-y-3">
            <a href="{{ route('login') }}" class="mobile-sidebar-cta btn w-full bg-[var(--primary-accent)] text-white font-bold py-3 rounded-xl block text-center">
              {{ __('navbar.login') }}</a>
            <a href="{{ route('register') }}" class="mobile-sidebar-cta btn w-full border border-[var(--primary-accent)] text-[var(--primary-accent)] font-bold py-3 rounded-xl block text-center">
              {{ __('navbar.sign_up') }}</a>
          </div>
        @endguest
      </div>

      {{-- ===== DESKTOP NAVBAR (visible >= 768px) ===== --}}
      <nav class="desktop-navbar bg-[var(--nav-bg)] border-b border-[var(--nav-border)] shadow-sm w-full z-[2000] relative"
        aria-label="Desktop main navigation">
        <div class="max-w-7xl mx-auto flex justify-between items-center px-6 py-3">
          <!-- LEFT: Logo (aligned with Sidebar location) -->
          <div class="flex items-center md:w-1/5">
            <a href="{{ route('index') }}" class="flex items-center logo-link">
              <img src="{{ asset('assets/img/logo.png') }}" alt="Minijobz"
                class="logo-img h-8 w-auto dark:brightness-0 dark:invert">
            </a>
          </div>

          <!-- CENTER & RIGHT: Center Links and Auth (aligned with Section location) -->
          <div class="flex-1 flex justify-between items-center md:pl-10">
            <div class="flex items-center space-x-5">
              <a href="{{ route('post-task') }}" class="check-login-trigger px-4 py-2 rounded-lg bg-[var(--primary-accent)] hover:bg-[var(--primary-hover)] text-white font-semibold btn transition-colors">
                {{ __('navbar.post_task') }}
              </a>

              <!-- Mega Menu -->
              <div id="categories-group" class="relative group">
                <a href="{{ url('category') }}" id="categories-toggle"
                  class="text-[var(--nav-link)] hover:text-[var(--nav-link-hover)] font-medium inline-flex items-center px-2 py-2 transition-colors"
                  aria-haspopup="true" aria-expanded="false">
                  {{ __('navbar.categories') }}
                  <svg class="ml-2 w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                  </svg>
                </a>

                <!-- Mega Dropdown -->
                <div id="categories-menu"
                  class="absolute left-0 mt-3 hidden flex bg-[var(--nav-dropdown-bg)] border border-[var(--nav-dropdown-border)] rounded-xl shadow-2xl w-[650px] z-50 overflow-hidden before:absolute before:-top-3 before:left-0 before:w-full before:h-3 before:content-[''] before:block">

                  <!-- Left Section -->
                  <div class="w-1/3 bg-[var(--nav-dropdown-hover)] border-r border-[var(--nav-dropdown-border)] p-5 flex flex-col justify-start">
                    <h3 class="text-[var(--text-primary)] font-semibold text-lg mb-2">{{ __('navbar.pick_task_type') }}</h3>
                    <p class="text-sm text-[var(--nav-muted)] leading-snug">{{ __('navbar.choose_category_desc') }}</p>
                  </div>

                  <!-- Right Section -->
                  <div class="w-2/3 grid grid-cols-2 gap-6 p-6">

                    <div>
                      <a href="{{ url('category') }}?category_id=2"
                        class="font-semibold text-[var(--primary-accent)] mb-2 hover:underline block">{{ __('navbar.mega_menu.home_services') }}</a>
                      <ul class="space-y-1 text-[var(--nav-muted)] text-sm">
                        <li>{{ __('navbar.mega_menu.handyman') }}</li>
                        <li>{{ __('navbar.mega_menu.plumbing') }}</li>
                        <li>{{ __('navbar.mega_menu.electrical') }}</li>
                        <li>{{ __('navbar.mega_menu.carpentry') }}</li>
                        <li>{{ __('navbar.mega_menu.painting') }}</li>
                        <li>{{ __('navbar.mega_menu.roofing') }}</li>
                      </ul>
                    </div>

                    <div>
                      <a href="{{ url('category') }}?category_id=3"
                        class="font-semibold text-[var(--primary-accent)] mb-2 hover:underline block">{{ __('navbar.mega_menu.cleaning_maintenance') }}</a>
                      <ul class="space-y-1 text-[var(--nav-muted)] text-sm">
                        <li>{{ __('navbar.mega_menu.house_cleaning') }}</li>
                        <li>{{ __('navbar.mega_menu.carpet_cleaning') }}</li>
                        <li>{{ __('navbar.mega_menu.window_cleaning') }}</li>
                        <li>{{ __('navbar.mega_menu.laundry') }}</li>
                        <li>{{ __('navbar.mega_menu.rubbish_removal') }}</li>
                        <li>{{ __('navbar.mega_menu.gardening') }}</li>
                      </ul>
                    </div>

                    <div>
                      <a href="{{ url('category') }}?category_id=4"
                        class="font-semibold text-[var(--primary-accent)] mb-2 hover:underline block">{{ __('navbar.mega_menu.moving_delivery') }}</a>
                      <ul class="space-y-1 text-[var(--nav-muted)] text-sm">
                        <li>{{ __('navbar.mega_menu.removals') }}</li>
                        <li>{{ __('navbar.mega_menu.courier') }}</li>
                        <li>{{ __('navbar.mega_menu.delivery') }}</li>
                        <li>{{ __('navbar.mega_menu.food_delivery') }}</li>
                        <li>{{ __('navbar.mega_menu.grocery_delivery') }}</li>
                        <li>{{ __('navbar.mega_menu.vehicle_transport') }}</li>
                      </ul>
                    </div>

                    <div>
                      <a href="{{ url('category') }}?category_id=5"
                        class="font-semibold text-[var(--primary-accent)] mb-2 hover:underline block">{{ __('navbar.mega_menu.personal_care') }}</a>
                      <ul class="space-y-1 text-[var(--nav-muted)] text-sm">
                        <li>{{ __('navbar.mega_menu.hairdressers') }}</li>
                        <li>{{ __('navbar.mega_menu.beauticians') }}</li>
                        <li>{{ __('navbar.mega_menu.makeup_artists') }}</li>
                        <li>{{ __('navbar.mega_menu.barbers') }}</li>
                        <li>{{ __('navbar.mega_menu.fitness') }}</li>
                        <li>{{ __('navbar.mega_menu.health_wellness') }}</li>
                      </ul>
                    </div>

                    <div>
                      <a href="{{ url('category') }}?category_id=14"
                        class="font-semibold text-[var(--primary-accent)] mb-2 hover:underline block">{{ __('navbar.mega_menu.business_tech') }}</a>
                      <ul class="space-y-1 text-[var(--nav-muted)] text-sm">
                        <li>{{ __('navbar.mega_menu.accounting') }}</li>
                        <li>{{ __('navbar.mega_menu.admin') }}</li>
                        <li>{{ __('navbar.mega_menu.marketing') }}</li>
                        <li>{{ __('navbar.mega_menu.design') }}</li>
                        <li>{{ __('navbar.mega_menu.web') }}</li>
                        <li>{{ __('navbar.mega_menu.writing') }}</li>
                      </ul>
                    </div>

                    <div>
                      <a href="{{ url('category') }}?category_id=6"
                        class="font-semibold text-[var(--primary-accent)] mb-2 hover:underline block">{{ __('navbar.mega_menu.automotive') }}</a>
                      <ul class="space-y-1 text-[var(--nav-muted)] text-sm">
                        <li>{{ __('navbar.mega_menu.car_wash') }}</li>
                        <li>{{ __('navbar.mega_menu.car_detailing') }}</li>
                        <li>{{ __('navbar.mega_menu.car_service') }}</li>
                        <li>{{ __('navbar.mega_menu.car_repair') }}</li>
                        <li>{{ __('navbar.mega_menu.mechanic') }}</li>
                        <li>{{ __('navbar.mega_menu.motorcycle_mechanic') }}</li>
                      </ul>
                    </div>

                    <!-- View All -->
                    <div class="col-span-2 text-center border-t border-[var(--nav-dropdown-border)] pt-3 mt-2">
                      <a href="/category"
                        class="inline-block text-[var(--primary-accent)] font-medium hover:underline">{{ __('navbar.view_all_categories') }}
                        →</a>
                    </div>

                  </div>
                </div>
              </div>

              <a href="{{ url('tasks') }}"
                class="text-[var(--nav-link)] hover:text-[var(--primary-hover)] transition-colors">{{ __('navbar.browse_tasks') }}</a>
              <a href="{{ url('howitworks') }}"
                class="text-[var(--nav-link)] hover:text-[var(--primary-hover)] transition-colors">{{ __('navbar.how_it_works') }}</a>
            </div>

            <!-- RIGHT: Login / Signup / Settings -->
            <div class="flex items-center space-x-3 pr-4">

              @guest
                <!-- Show Login and Sign Up for guests -->
                <a href="{{ route('login') }}" class="px-4 py-2 rounded-lg bg-[var(--primary-accent)] hover:bg-[var(--primary-hover)] text-white btn transition-colors">
                  {{ __('navbar.login') }}
                </a>
                <a href="{{ route('register') }}" class="px-4 py-2 rounded-lg border border-[var(--primary-accent)] text-[var(--primary-accent)] hover:bg-[var(--nav-dropdown-hover)] no-underline transition-colors btn navbar-btn-bordered">
                  {{ __('navbar.sign_up') }}
                </a>
              @endguest

              @auth
                <!-- Right: avatar dropdown -->
                <div class="relative ml-auto pr-4">
                  <button type="button"
                    class="rounded-full overflow-hidden w-9 h-9 ring-1 ring-[var(--nav-border)] hover:ring-[var(--primary-accent)] focus:outline-none focus:ring-2 focus:ring-[var(--primary-accent)]"
                    id="user-menu-button" aria-expanded="false" aria-haspopup="true"
                    aria-label="Open user profile menu">
                    <img src="{{ $avatarSrc }}" alt="" class="w-full h-full object-cover">
                  </button>
                  <div class="sub-menu-wrap" id="subMenu" role="menu" aria-labelledby="user-menu-button"
                    aria-orientation="vertical">
                    <div class="sub-menu bg-[var(--nav-dropdown-bg)] border border-[var(--nav-dropdown-border)] shadow-xl rounded-xl p-2">
                      <a href="{{ route('public-profile', Auth::id()) }}"
                        class="user-info group block px-4 py-3 rounded-lg hover:bg-[var(--nav-dropdown-hover)] transition-colors no-underline border-b border-[var(--nav-dropdown-border)] mb-1">
                        <h3 class="text-base font-bold text-[var(--text-primary)] group-hover:text-[var(--primary-accent)] transition-colors">
                          {{ $fullName }}
                        </h3>
                        <p class="text-xs text-[var(--nav-muted)] mb-0">{{ __('navbar.public_profile') }}</p>
                      </a>
                      
                      <div class="p-1">
                        <a href="{{ route('my-tasks') }}" class="flex items-center gap-2 p-2 rounded-lg text-[var(--text-primary)] hover:bg-[var(--nav-dropdown-hover)] no-underline mb-1">
                          <i data-feather="grid" class="w-4 h-4"></i> {{ __('navbar.dashboard') }}
                        </a>
                        <a href="{{ route('messages') }}" class="flex items-center gap-2 p-2 rounded-lg text-[var(--text-primary)] hover:bg-[var(--nav-dropdown-hover)] no-underline mb-1">
                          <i data-feather="message-square" class="w-4 h-4"></i> {{ __('navbar.messages') }}
                        </a>
                        <a href="{{ route('notifications') }}" class="flex items-center gap-2 p-2 rounded-lg text-[var(--text-primary)] hover:bg-[var(--nav-dropdown-hover)] no-underline mb-1">
                          <i data-feather="bell" class="w-4 h-4"></i> {{ __('navbar.notifications') }}
                        </a>
                        <a href="{{ route('profile') }}" class="flex items-center gap-2 p-2 rounded-lg text-[var(--text-primary)] hover:bg-[var(--nav-dropdown-hover)] no-underline mb-1">
                          <i data-feather="user" class="w-4 h-4"></i> {{ __('navbar.profile') }}
                        </a>
                        <a href="{{ route('profile', ['tab' => 'account']) }}" class="flex items-center gap-2 p-2 rounded-lg text-[var(--text-primary)] hover:bg-[var(--nav-dropdown-hover)] no-underline mb-1">
                          <i data-feather="settings" class="w-4 h-4"></i> {{ __('navbar.settings') }}
                        </a>
                      </div>
                      
                      <hr class="border-t border-[var(--nav-dropdown-border)] my-1">
                      <a href="#" class="logout-trigger flex items-center gap-2 p-2 rounded-lg text-[var(--details-error)] hover:bg-[var(--details-error-bg)] no-underline">
                        <i data-feather="log-out" class="w-4 h-4"></i> {{ __('navbar.logout') }}
                      </a>
                    </div>
                  </div>
                </div>

                <!-- Notification Bell & Dropdown -->
                <div class="relative">
                  <button class="notification-btn focus:outline-none focus:ring-2 focus:ring-[var(--primary-accent)] rounded-full p-2 hover:bg-[var(--nav-dropdown-hover)] transition-colors"
                    type="button" id="notifications-menu-button" aria-expanded="false"
                    aria-haspopup="true" aria-label="Notifications">
                    <svg viewBox="0 0 448 512" class="bell w-5 h-5 fill-[var(--nav-link)]" aria-hidden="true">
                      <path
                        d="M224 0c-17.7 0-32 14.3-32 32V49.9C119.5 61.4 64 124.2 64 200v33.4c0 45.4-15.5 89.5-43.8 124.9L5.3 377c-5.8 7.2-6.9 17.1-2.9 25.4S14.8 416 24 416H424c9.2 0 17.6-5.3 21.6-13.6s2.9-18.2-2.9-25.4l-14.9-18.6C399.5 322.9 384 278.8 384 233.4V200c0-75.8-55.5-138.6-128-150.1V32c0-17.7-14.3-32-32-32zm0 96h8c57.4 0 104 46.6 104 104v33.4c0 47.9 13.9 94.6 39.7 134.6H72.3C98.1 328 112 281.3 112 233.4V200c0-57.4 46.6-104 104-104h8zm64 352H224 160c0 17 6.7 33.3 18.7 45.3s28.3 18.7 45.3 18.7s33.3-6.7 45.3-18.7s18.7-28.3 18.7-45.3z">
                      </path>
                    </svg>
                    @if($unreadCount > 0)
                      <span class="sr-only">{{ $unreadCount }} unread notifications</span>
                      <span
                        class="absolute top-0 right-0 flex items-center justify-center w-4 h-4 text-[9px] font-bold text-white bg-[var(--details-error)] rounded-full border border-[var(--nav-bg)] transform translate-x-1 -translate-y-1"
                        aria-hidden="true">
                        {{ $unreadCount }}
                      </span>
                    @endif
                  </button>

                  <!-- Dropdown Menu -->
                  <div id="notification-dropdown" role="menu" aria-labelledby="notifications-menu-button"
                    class="absolute right-0 mt-3 w-80 bg-[var(--nav-dropdown-bg)] rounded-xl shadow-2xl border border-[var(--nav-dropdown-border)] overflow-hidden hidden transform origin-top-right transition-all duration-200 z-50">
                    <div class="p-4 border-b border-[var(--nav-dropdown-border)] flex justify-between items-center bg-[var(--nav-dropdown-hover)]/30">
                      <h3 class="font-bold text-[var(--text-primary)] text-sm uppercase tracking-wider">{{ __('navbar.notifications') }}</h3>
                      @if($unreadCount > 0)
                        <button type="button" class="mark-all-read-trigger group flex items-center gap-1.5 text-xs font-bold text-[var(--primary-accent)] hover:text-[var(--primary-hover)] transition-colors">
                          <i data-feather="check-square" class="w-3.5 h-3.5 group-hover:scale-110 transition-transform"></i>
                          <span>{{ __('navbar.mark_all_read') }}</span>
                        </button>
                      @endif
                    </div>

                    <div class="max-h-[400px] overflow-y-auto">
                      @forelse($notifications as $notification)
                        <a href="{{ $notification->data['link'] ?? '#' }}"
                          class="block p-4 hover:bg-[var(--nav-dropdown-hover)] transition-colors border-b border-[var(--nav-dropdown-border)] {{ $notification->read_at ? 'opacity-75' : 'bg-[var(--primary-accent)]/5' }} no-underline">
                          <div class="flex gap-3">
                            <div class="mt-1">
                              <div class="w-8 h-8 rounded-full bg-[var(--primary-accent)]/10 text-[var(--primary-accent)] flex items-center justify-center">
                                <i data-feather="bell" style="width:14px; height:14px;"></i>
                              </div>
                            </div>
                            <div class="flex-1 min-w-0">
                              <p class="text-sm text-[var(--text-primary)] font-bold mb-0 truncate">
                                {{ $notification->data['title'] ?? __('navbar.notification_default_title') }}
                              </p>
                              <p class="text-xs text-[var(--nav-muted)] mb-0 truncate">
                                {{ $notification->data['message'] ?? '' }}
                              </p>
                              <p class="text-[10px] text-[var(--nav-muted)] mt-1 uppercase font-semibold">
                                {{ $notification->created_at->diffForHumans() }}
                              </p>
                            </div>
                          </div>
                        </a>
                      @empty
                        <div class="p-8 text-center text-[var(--nav-muted)]">
                          <i data-feather="bell-off" class="mx-auto mb-2 opacity-50"></i>
                          <p class="text-sm">{{ __('navbar.no_notifications') }}</p>
                        </div>
                      @endforelse
                    </div>

                    <div class="p-3 bg-[var(--nav-dropdown-hover)] text-center border-t border-[var(--nav-dropdown-border)]">
                      <a href="{{ route('notifications') }}"
                        class="text-sm font-bold text-[var(--primary-accent)] hover:text-[var(--primary-hover)] no-underline">{{ __('navbar.view_all_notifications') }}</a>
                    </div>
                  </div>
                </div>
              @endauth

              <!-- Settings dropdown -->
              <div class="relative">
                <button id="settings-button"
                  class="p-2 rounded-full hover:bg-[var(--nav-dropdown-hover)] text-[var(--nav-link)] transition-colors"
                  type="button" aria-label="Toggle accessibility settings">
                  <i data-feather="settings" class="w-5 h-5"></i>
                </button>
                <div id="settings-menu"
                  class="hidden absolute right-0 mt-2 w-56 bg-[var(--nav-dropdown-bg)] border border-[var(--nav-dropdown-border)] rounded-lg shadow-lg z-[60] opacity-0 translate-y-2 transition-all duration-200 ease-out p-1">
                  <div class="flex flex-col" role="none">
                    <div class="group relative" role="none">
                      <button type="button"
                        class="w-full text-left py-2.5 px-3 text-[var(--text-primary)] font-semibold hover:bg-[var(--nav-dropdown-hover)] rounded-lg flex items-center gap-3 transition-colors">
                        <i data-feather="chevron-left"
                          class="w-4 h-4 text-[var(--nav-muted)] group-hover:-translate-x-0.5 transition-transform"></i>
                        <i data-feather="sun" class="w-4 h-4 text-[var(--nav-muted)]"></i>
                        <span>{{ __('navbar.theme') }}</span>
                      </button>
                      <div
                        class="submenu hidden absolute top-0 right-full w-48 bg-[var(--nav-dropdown-bg)] border border-[var(--nav-dropdown-border)] rounded-lg shadow-lg group-hover:block p-1 z-50"
                        role="menu">
                        <button type="button" class="w-full text-left px-3 py-2 text-sm text-[var(--text-primary)] hover:bg-[var(--nav-dropdown-hover)] rounded-lg transition-colors"
                          data-theme="light" role="menuitem">{{ __('navbar.light') }}</button>
                        <button type="button" class="w-full text-left px-3 py-2 text-sm text-[var(--text-primary)] hover:bg-[var(--nav-dropdown-hover)] rounded-lg transition-colors"
                          data-theme="dark" role="menuitem">{{ __('navbar.dark') }}</button>
                        <button type="button" class="w-full text-left px-3 py-2 text-sm text-[var(--text-primary)] hover:bg-[var(--nav-dropdown-hover)] rounded-lg transition-colors"
                          data-theme="system" role="menuitem">{{ __('navbar.system_default') }}</button>
                      </div>
                    </div>
                    <!-- Language -->
                    <div class="group relative" role="none">
                      <button type="button"
                        class="w-full text-left py-2.5 px-3 text-[var(--text-primary)] font-semibold hover:bg-[var(--nav-dropdown-hover)] rounded-lg flex items-center gap-3 transition-colors">
                        <i data-feather="chevron-left"
                          class="w-4 h-4 text-[var(--nav-muted)] group-hover:-translate-x-0.5 transition-transform"></i>
                        <i data-feather="globe" class="w-4 h-4 text-[var(--nav-muted)]"></i>
                        <span>{{ __('navbar.language') }}</span>
                      </button>
                      <div
                        class="submenu hidden absolute top-0 right-full w-48 bg-[var(--nav-dropdown-bg)] border border-[var(--nav-dropdown-border)] rounded-lg shadow-lg group-hover:block p-1 z-50"
                        role="menu">
                        <button type="button" class="w-full text-left px-3 py-2 text-sm text-[var(--text-primary)] hover:bg-[var(--nav-dropdown-hover)] rounded-lg transition-colors"
                          data-lang="en" role="menuitem">{{ __('navbar.english') }}</button>
                        <button type="button" class="w-full text-left px-3 py-2 text-sm text-[var(--text-primary)] hover:bg-[var(--nav-dropdown-hover)] rounded-lg transition-colors"
                          data-lang="hu" role="menuitem">{{ __('navbar.hungarian') }}</button>
                      </div>
                    </div>
                    <!-- Accessibility -->
                    <div class="group relative" role="none">
                      <button type="button"
                        class="w-full text-left py-2.5 px-3 text-[var(--text-primary)] font-semibold hover:bg-[var(--nav-dropdown-hover)] rounded-lg flex items-center gap-3 transition-colors">
                        <i data-feather="chevron-left"
                          class="w-4 h-4 text-[var(--nav-muted)] group-hover:-translate-x-0.5 transition-transform"></i>
                        <i data-feather="eye" class="w-4 h-4 text-[var(--nav-muted)]"></i>
                        <span>{{ __('navbar.accessibility') }}</span>
                      </button>
                      <div
                        class="submenu hidden absolute top-0 right-full w-56 bg-[var(--nav-dropdown-bg)] border border-[var(--nav-dropdown-border)] rounded-lg shadow-lg group-hover:block p-1 z-50"
                        role="menu">
                        <button type="button" data-acc-toggle="reduced-motion"
                          class="w-full text-left px-3 py-2 hover:bg-[var(--nav-dropdown-hover)] rounded flex items-center justify-between text-sm transition-colors text-[var(--text-primary)]"
                          role="menuitem">
                          <span>{{ __('navbar.reduced_motion') }}</span>
                          <div id="nav-reduced-motion-indicator"
                            class="acc-slider-track w-8 h-4 bg-[var(--border-base)] rounded-full relative">
                            <div class="acc-slider-circle absolute bg-[var(--nav-bg)] rounded-full">
                            </div>
                          </div>
                        </button>
                        <button type="button" data-acc-toggle="high-contrast"
                          class="w-full text-left px-3 py-2 hover:bg-[var(--nav-dropdown-hover)] rounded flex items-center justify-between text-sm transition-colors text-[var(--text-primary)]"
                          role="menuitem">
                          <span>{{ __('navbar.high_contrast') }}</span>
                          <div id="nav-high-contrast-indicator"
                            class="acc-slider-track w-8 h-4 bg-[var(--border-base)] rounded-full relative">
                            <div class="acc-slider-circle absolute bg-[var(--nav-bg)] rounded-full">
                            </div>
                          </div>
                        </button>
                      </div>
                    </div>
                    <!-- Extras -->
                    <div class="group relative" role="none">
                      <button type="button"
                        class="w-full text-left py-2.5 px-3 text-[var(--text-primary)] font-semibold hover:bg-[var(--nav-dropdown-hover)] rounded-lg flex items-center gap-3 transition-colors">
                        <i data-feather="chevron-left"
                          class="w-4 h-4 text-[var(--nav-muted)] group-hover:-translate-x-0.5 transition-transform"></i>
                        <i data-feather="more-horizontal" class="w-4 h-4 text-[var(--nav-muted)]"></i>
                        <span>{{ __('navbar.extras') }}</span>
                      </button>
                      <div
                        class="submenu hidden absolute top-0 right-full w-48 bg-[var(--nav-dropdown-bg)] border border-[var(--nav-dropdown-border)] rounded-lg shadow-lg group-hover:block p-1 z-50"
                        role="menu">
                        <a href="{{ route('help-faq') }}" target="_blank"
                          class="block px-3 py-2 hover:bg-[var(--nav-dropdown-hover)] rounded-lg transition-colors text-[var(--text-primary)] no-underline text-sm"
                          role="menuitem">{{ __('navbar.help_faq') ?? 'Help / FAQ' }}</a>
                        <a href="{{ route('contact-support') }}" target="_blank"
                          class="block px-3 py-2 hover:bg-[var(--nav-dropdown-hover)] rounded-lg transition-colors text-[var(--text-primary)] no-underline text-sm"
                          role="menuitem">{{ __('navbar.contact_support') ?? 'Contact / Support' }}</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </nav>
  @endif
