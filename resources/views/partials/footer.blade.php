    <!-- 🔹 FOOTER -->
    @hasSection('hideFooter')
    @else
      <footer class="pt-16 pb-8 mt-auto shadow-[0_-1px_2px_rgba(0,0,0,0.03)] border-t">
        <div class="container mx-auto px-6 max-w-7xl">
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">
            <!-- Column 1: Brand -->
            <div class="space-y-4">
              <a href="{{ route('index') }}" class="flex items-center logo-link">
                <img src="{{ asset('assets/img/logo.png') }}" alt="Minijobz"
                  class="logo-img h-8 w-auto dark:brightness-0 dark:invert mix-blend-multiply dark:mix-blend-normal">
              </a>
              <p class="text-sm leading-relaxed">
                {{ __('footer.brand_description') }}
              </p>
            </div>

            <!-- Column 2: Popular Categories -->
            <div>
              <h3 class="font-bold mb-4">{{ __('footer.popular_categories') }}</h3>
              <ul class="space-y-3 text-sm">
                <li><a href="{{ url('category') }}?category_id=2"
                    class="hover:text-[var(--primary-accent)] transition-colors">{{ __('navbar.mega_menu.home_services') }}</a></li>
                <li><a href="{{ url('category') }}?category_id=3"
                    class="hover:text-[var(--primary-accent)] transition-colors">{{ __('navbar.mega_menu.cleaning_maintenance') }}</a>
                </li>
                <li><a href="{{ url('category') }}?category_id=4"
                    class="hover:text-[var(--primary-accent)] transition-colors">{{ __('navbar.mega_menu.moving_delivery') }}</a></li>
                <li><a href="{{ url('category') }}?category_id=14"
                    class="hover:text-[var(--primary-accent)] transition-colors">{{ __('navbar.mega_menu.business_tech') }}</a></li>
                <li><a href="{{ url('category') }}?category_id=6"
                    class="hover:text-[var(--primary-accent)] transition-colors">{{ __('navbar.mega_menu.automotive') }}</a></li>
              </ul>
            </div>

            <!-- Column 3: Company -->
            <div>
              <h3 class="font-bold mb-4">{{ __('footer.company') }}</h3>
              <ul class="space-y-3 text-sm">
                <li><a href="{{ route('terms') }}"
                    class="hover:text-[var(--primary-accent)] transition-colors">{{ __('footer.terms_and_conditions') }}</a></li>
                <li><a href="{{ route('privacy') }}"
                    class="hover:text-[var(--primary-accent)] transition-colors">{{ __('footer.privacy_policy') }}</a></li>
                <li><a href="{{ route('help-faq') }}"
                    class="hover:text-[var(--primary-accent)] transition-colors">{{ __('footer.help_faq') }}</a></li>
                <li><a href="{{ route('contact-support') }}"
                    class="hover:text-[var(--primary-accent)] transition-colors">{{ __('footer.contact_support') }}</a></li>
                <li><a href="{{ route('guidelines') }}"
                    class="hover:text-[var(--primary-accent)] transition-colors">{{ __('footer.community_guidelines') }}</a></li>
              </ul>
            </div>

            <!-- Column 4: Pages -->
            <div>
              <h3 class="font-bold mb-4">{{ __('footer.pages') }}</h3>
              <ul class="space-y-3 text-sm">
                <li><a href="{{ route('tasks') }}"
                    class="hover:text-[var(--primary-accent)] transition-colors">{{ __('navbar.browse_tasks') }}</a></li>
                <li><a href="{{ route('howitworks') }}"
                    class="hover:text-[var(--primary-accent)] transition-colors">{{ __('navbar.how_it_works') }}</a></li>
                @guest
                  <li><a href="{{ route('login') }}"
                      class="hover:text-[var(--primary-accent)] transition-colors">{{ __('navbar.login') }}</a></li>
                  <li><a href="{{ route('register') }}"
                      class="hover:text-[var(--primary-accent)] transition-colors">{{ __('navbar.sign_up') }}</a></li>
                @endguest
                @auth
                  <li><a href="{{ route('my-tasks') }}"
                      class="hover:text-[var(--primary-accent)] transition-colors">{{ __('navbar.dashboard') }}</a></li>
                  <li><a href="{{ route('profile') }}"
                      class="hover:text-[var(--primary-accent)] transition-colors">{{ __('navbar.profile') }}</a></li>
                @endauth
              </ul>
            </div>
          </div>

          <div class="pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-sm">
              &copy; {{ date('Y') }} Minijobz. {{ __('footer.all_rights_reserved') }}
            </p>
            <div class="text-sm text-[var(--nav-muted)]">
              {{ __('footer.copyright_authors') }}
            </div>
          </div>
        </div>
      </footer>
    @endif
