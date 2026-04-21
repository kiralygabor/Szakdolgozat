/**
 * Global Layout Logic for MiniJobz
 * Refactored for Clean Code using ES Modules.
 */
import { Config } from './modules/config.js';
import { DropdownManager, ThemeEngine } from './modules/ui-utils.js';

document.addEventListener('DOMContentLoaded', () => {
    // 1. Initialize Global UI Components
    initGlobalUI();

    // 2. Initialize Theme & Accessibility
    initThemeSystems();

    // 3. Initialize Shared Handlers
    initSharedHandlers();
});

/**
 * Register all persistent dropdowns and menus
 */
function initGlobalUI() {
    // Settings Dropdown
    const settingsBtn = document.getElementById('settings-button');
    const settingsMenu = document.getElementById('settings-menu');
    if (settingsBtn && settingsMenu) {
        DropdownManager.register('settings', settingsBtn, settingsMenu);
    }

    // Notifications Dropdown
    const notifBtn = document.getElementById('notifications-menu-button');
    const notifMenu = document.getElementById('notification-dropdown');
    if (notifBtn && notifMenu) {
        DropdownManager.register('notifications', notifBtn, notifMenu, {
            onOpen: () => {
                notifMenu.classList.remove('opacity-0', 'scale-95');
                notifMenu.classList.add('opacity-100', 'scale-100');
            },
            onClose: () => {
                notifMenu.classList.remove('opacity-100', 'scale-100');
                notifMenu.classList.add('opacity-0', 'scale-95');
            }
        });
    }

    // Profile Dropdown (Avatar)
    const avatarBtn = document.getElementById('user-menu-button');
    const avatarMenu = document.getElementById('subMenu');
    if (avatarBtn && avatarMenu) {
        DropdownManager.register('profile', avatarBtn, avatarMenu, {
            onOpen: () => avatarMenu.classList.add('open-menu'),
            onClose: () => avatarMenu.classList.remove('open-menu')
        });
    }

    // Categories Mega Menu Behavior (Hover or Focus based)
    initCategoriesMenu();

    // Mobile Sidebar
    initMobileNav();

    // 4. Navbar Specific Handlers
    initNavbarHandlers();

    DropdownManager.initGlobalHandlers();
}

/**
 * Handle Theme & Accessibility Initialization
 */
function initThemeSystems() {
    try {
        const getCookie = (name) => {
            const value = `; ${document.cookie}`;
            const parts = value.split(`; ${name}=`);
            if (parts.length === 2) return parts.pop().split(';').shift();
            return null;
        };

        const settings = window.userSettings || {};
        const cookieTheme = getCookie('theme');
        const cookieRM = getCookie('reduced_motion');
        const cookieHC = getCookie('contrast');

        const theme = cookieTheme || settings.theme || 'light';
        const reducedMotion = cookieRM !== null ? (cookieRM === 'true') : (settings.reduced_motion || false);
        const highContrast = cookieHC !== null ? (cookieHC === 'high') : (settings.high_contrast || false);

        ThemeEngine.applyTheme(theme, true);
        ThemeEngine.applyAcc('reduced-motion', reducedMotion, true);
        ThemeEngine.applyAcc('high-contrast', highContrast, true);

        // System theme change listener
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
            if (window.userSettings && window.userSettings.theme === 'system') {
                ThemeEngine.applyTheme('system', true);
            }
        });

        // Global Event Delegation for Settings
        const settingsHandler = (e) => {
            const themeOpt = e.target.closest('[data-theme]');
            if (themeOpt) {
                e.preventDefault();
                ThemeEngine.applyTheme(themeOpt.dataset.theme);
            }

            const langOpt = e.target.closest('[data-lang]');
            if (langOpt) {
                e.preventDefault();
                handleLanguageChange(langOpt.dataset.lang);
            }
        };

        document.body.addEventListener('click', settingsHandler);
        // Explicitly attach to settings-menu to bypass preventPropagation from DropdownManager
        document.getElementById('settings-menu')?.addEventListener('click', settingsHandler);

        // Toggle helpers attached to window for legacy Blade onclicks
        window.toggleAccessibilitySetting = (type) => {
            const current = (window.userSettings && type.replace('-', '_') in window.userSettings)
                ? window.userSettings[type.replace('-', '_')]
                : document.documentElement.classList.contains(type);

            const newVal = !current;
            if (type === 'high-contrast' && newVal) {
                ThemeEngine.applyTheme('light');
            } else if (type === 'high-contrast' && !newVal) {
                const restoredTheme = (window.userSettings && window.userSettings.theme) ? window.userSettings.theme : 'light';
                ThemeEngine.applyTheme(restoredTheme);
            }
            ThemeEngine.applyAcc(type, newVal);
        };

        window.toggleMenu = () => DropdownManager.toggle('profile');
        window.toggleNotifications = () => DropdownManager.toggle('notifications');

    } catch (e) {
        console.error('Theme system initialization failed:', e);
    }
}

/**
 * Shared Handlers (Language, Logout, Notifications)
 */
function initSharedHandlers() {
    window.logout = () => {
        document.cookie = "theme=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
        document.cookie = "contrast=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
        document.cookie = "reduced_motion=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
        const form = document.getElementById('universal-logout-form');
        if (form) form.submit();
    };

    window.markNotificationsRead = async () => {
        try {
            const res = await fetch(Config.api.notificationsMarkRead, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Content-Type': 'application/json'
                }
            });
            const data = await res.json();
            if (data.success) {
                document.querySelectorAll('.notification-btn span, .mobile-profile-btn span, .unread-badge').forEach(el => el.remove());
                const listContainer = document.querySelector('#notification-dropdown .max-h-\\[400px\\]');
                if (listContainer) {
                    listContainer.innerHTML = `<div class="p-8 text-center var(--text-muted)"><i data-feather="bell-off" class="mx-auto mb-2 opacity-50"></i><p class="text-sm">No new notifications</p></div>`;
                    if (window.feather) feather.replace();
                }
                DropdownManager.closeAllExcept();
                return true;
            }
        } catch (e) {
            console.error('Failed to mark notifications read:', e);
        }
        return false;
    };

    window.checkLogin = (e) => {
        if (!window.isAuthenticated) {
            e.preventDefault();
            window.location.href = "/login";
            return false;
        }
        return true;
    };

    if (window.feather) window.feather.replace();
}

/**
 * Specific Logic for Categories Mega Menu
 */
function initCategoriesMenu() {
    const group = document.getElementById('categories-group');
    const menu = document.getElementById('categories-menu');
    if (!group || !menu) return;

    let timeout;
    const isRM = () => document.documentElement.classList.contains('reduced-motion');

    const show = () => {
        clearTimeout(timeout);
        menu.classList.remove('hidden');
        requestAnimationFrame(() => {
            menu.classList.remove('opacity-0', 'pointer-events-none', 'translate-y-2');
            menu.classList.add('opacity-100');
        });
    };

    const hide = () => {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            menu.classList.remove('opacity-100');
            menu.classList.add('opacity-0');
            setTimeout(() => {
                if (menu.classList.contains('opacity-0')) {
                    menu.classList.add('hidden', 'pointer-events-none', 'translate-y-2');
                }
            }, isRM() ? 0 : Config.timeouts.dropdown);
        }, isRM() ? 0 : 300);
    };

    group.addEventListener('mouseenter', show);
    group.addEventListener('mouseleave', hide);
    group.addEventListener('focusin', show);
    group.addEventListener('focusout', (e) => {
        if (!group.contains(e.relatedTarget)) hide();
    });
}

/**
 * Handle Language Change Submission
 */
function handleLanguageChange(locale) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/language/${locale}`;
    const csrf = document.createElement('input');
    csrf.type = 'hidden';
    csrf.name = '_token';
    csrf.value = document.querySelector('meta[name="csrf-token"]')?.content || '';
    form.appendChild(csrf);
    document.body.appendChild(form);
    form.submit();
}

/**
 * Mobile Navigation Logic
 */
function initMobileNav() {
    const hamburger = document.getElementById('mobileHamburger');
    const sidebar = document.getElementById('mobileSidebar');
    const overlay = document.getElementById('mobileSidebarOverlay');
    const profileBtn = document.getElementById('mobileProfileBtn');
    const profileDropdown = document.getElementById('mobileProfileDropdown');

    if (hamburger && sidebar && overlay) {
        const toggle = () => {
            const active = sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
            hamburger.classList.toggle('active');
            document.body.style.overflow = active ? 'hidden' : '';
        };
        hamburger.addEventListener('click', toggle);
        overlay.addEventListener('click', toggle);
        document.getElementById('mobileSidebarClose')?.addEventListener('click', toggle);
    }

    if (profileBtn && profileDropdown) {
        profileBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            profileDropdown.classList.toggle('active');
        });
        document.addEventListener('click', () => profileDropdown.classList.remove('active'));
    }
}

/**
 * Modern event listeners for navbar triggers
 */
function initNavbarHandlers() {
    // 1. Logout Triggers
    document.querySelectorAll('.logout-trigger').forEach(el => {
        el.addEventListener('click', (e) => {
            e.preventDefault();
            const form = document.getElementById('universal-logout-form');
            if (form) form.submit();
        });
    });

    // 2. Check Login Triggers
    document.querySelectorAll('.check-login-trigger').forEach(el => {
        el.addEventListener('click', (e) => {
            if (!window.isAuthenticated) {
                e.preventDefault();
                window.location.href = "/login";
            }
        });
    });

    // 3. Accessibility Toggles
    document.querySelectorAll('[data-acc-toggle]').forEach(el => {
        el.addEventListener('click', () => {
            const type = el.dataset.accToggle;
            const current = (window.userSettings && type.replace('-', '_') in window.userSettings)
                ? window.userSettings[type.replace('-', '_')]
                : document.documentElement.classList.contains(type);

            const newVal = !current;
            if (type === 'high-contrast' && newVal) {
                ThemeEngine.applyTheme('light');
            } else if (type === 'high-contrast' && !newVal) {
                const restoredTheme = (window.userSettings && window.userSettings.theme) ? window.userSettings.theme : 'light';
                ThemeEngine.applyTheme(restoredTheme);
            }
            ThemeEngine.applyAcc(type, newVal);
        });
    });

    // 4. Mark All Read
    document.querySelectorAll('.mark-all-read-trigger').forEach(el => {
        el.addEventListener('click', () => window.markNotificationsRead());
    });

    // 5. Settings Submenu Triggers (Keyboard Support)
    document.querySelectorAll('.submenu-trigger').forEach(trigger => {
        trigger.addEventListener('click', (e) => {
            const submenu = trigger.nextElementSibling;
            if (submenu && submenu.classList.contains('submenu')) {
                // If this is a click from keyboard (Enter), toggle it
                // We don't preventDefault so that click-based interactions still work
                const wasShowing = submenu.classList.contains('show-submenu');

                // Close others
                document.querySelectorAll('.submenu.show-submenu').forEach(s => s.classList.remove('show-submenu'));

                if (!wasShowing) {
                    submenu.classList.add('show-submenu');
                }
            }
        });
    });
}
