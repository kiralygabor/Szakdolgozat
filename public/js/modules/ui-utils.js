/**
 * UI Utilities for MiniJobz
 * Handles drop-down management, theme application, and shared UI behaviors.
 */
import { Config } from './config.js';

/**
 * Manages global dropdowns to ensure only one is open at a time
 * and handles outside-click closure.
 */
export const DropdownManager = {
    registrations: [],

    /**
     * Register a dropdown with its toggle button
     * @param {string} id - Unique identifier
     * @param {HTMLElement} btn - Toggle button
     * @param {HTMLElement} menu - Dropdown element
     * @param {Object} options - Custom callbacks (onOpen, onClose)
     */
    register(id, btn, menu, options = {}) {
        if (!btn || !menu) return;
        
        const entry = { id, btn, menu, options };
        this.registrations.push(entry);

        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            this.toggle(id);
        });

        // Prevent closing when clicking inside, but allow setting-toggles to bubble
        menu.addEventListener('click', (e) => {
            const isActionable = e.target.closest('[data-theme], [data-lang], [data-acc-toggle]');
            if (!isActionable) {
                e.stopPropagation();
            }
        });
    },

    toggle(id) {
        const entry = this.registrations.find(r => r.id === id);
        if (!entry) return;

        const isHidden = entry.menu.classList.contains('hidden');
        if (isHidden) {
            this.closeAllExcept(id);
            this.open(entry);
        } else {
            this.close(entry);
        }
    },

    open(entry) {
        entry.menu.classList.remove('hidden', 'opacity-0', 'translate-y-2');
        entry.menu.classList.add('show', 'opacity-100');
        entry.btn.setAttribute('aria-expanded', 'true');
        if (entry.options.onOpen) entry.options.onOpen();
    },

    close(entry) {
        if (entry.menu.classList.contains('hidden')) return;
        
        entry.menu.classList.remove('show', 'opacity-100');
        entry.menu.classList.add('opacity-0', 'translate-y-2');
        entry.btn.setAttribute('aria-expanded', 'false');
        
        // Use timeout from config
        setTimeout(() => {
            if (!entry.menu.classList.contains('show')) {
                entry.menu.classList.add('hidden');
            }
        }, Config.timeouts.dropdown);

        if (entry.options.onClose) entry.options.onClose();
    },

    closeAllExcept(activeId = null) {
        this.registrations.forEach(entry => {
            if (entry.id !== activeId) this.close(entry);
        });
    },

    initGlobalHandlers() {
        document.addEventListener('click', () => this.closeAllExcept());
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') this.closeAllExcept();
        });
    }
};

/**
 * Handles theme and accessibility state
 */
export const ThemeEngine = {
    applyTheme(mode, skipSave = false) {
        const root = document.documentElement;
        const isAuth = window.isAuthenticated;

        if (mode === 'system') {
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            root.classList.toggle('dark', prefersDark);
            if (prefersDark) this.applyAcc('high-contrast', false, true);
        } else if (mode === 'dark') {
            root.classList.add('dark');
            this.applyAcc('high-contrast', false, true);
        } else {
            root.classList.remove('dark');
        }

        // Persist via cookie for instant refresh support
        document.cookie = `theme=${mode}; path=/; max-age=${60 * 60 * 24 * 365}`;

        if (window.userSettings) window.userSettings.theme = mode;
        if (!skipSave && isAuth) return this.saveSettings({ theme: mode });
        return Promise.resolve();
    },

    applyAcc(type, val, skipSave = false) {
        const root = document.documentElement;
        const isAuth = window.isAuthenticated;

        root.classList.toggle(type, val);

        if (val && type === 'high-contrast') {
            root.classList.remove('dark');
        }

        // Persist via cookie (translate type to cookie name correctly if needed)
        const cookieName = type === 'reduced-motion' ? 'reduced_motion' : (type === 'high-contrast' ? 'contrast' : type);
        const cookieVal = type === 'high-contrast' ? (val ? 'high' : 'standard') : val;
        document.cookie = `${cookieName}=${cookieVal}; path=/; max-age=${60 * 60 * 24 * 365}`;

        if (window.userSettings) window.userSettings[type.replace('-', '_')] = val;

        this.updateIndicator(type, val);

        if (!skipSave && isAuth) {
            const settings = {};
            settings[type.replace('-', '_')] = val;
            return this.saveSettings(settings);
        }
        return Promise.resolve();
    },

    updateIndicator(type, val) {
        console.log(`[ThemeEngine] Updating ${type} indicator to: ${val}`);
        const indicator = document.getElementById(`nav-${type}-indicator`);
        if (!indicator) {
            console.warn(`[ThemeEngine] Indicator not found: nav-${type}-indicator`);
            return;
        }

        indicator.classList.toggle('active', val);
        
        // Also update accessibility attributes if needed
        const btn = indicator.closest('button');
        if (btn) {
            btn.setAttribute('aria-pressed', val ? 'true' : 'false');
        }
    },

    async saveSettings(settings) {
        try {
            await fetch(Config.api.settings, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(settings)
            });
        } catch (e) {
            console.error('Failed to save settings:', e);
        }
    }
};

/**
 * Standardizes Modal interactions across the app
 */
export const ModalManager = {
    open(modalId) {
        const modal = document.getElementById(modalId);
        if (!modal) return;
        
        modal.classList.remove('hidden');
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
        
        // Re-init feather icons if they exist in the modal
        if (window.feather) window.feather.replace();

        // Accessible focus
        const firstInput = modal.querySelector('input, button, textarea');
        if (firstInput) firstInput.focus();
    },

    close(modalId) {
        const modal = document.getElementById(modalId);
        if (!modal) return;
        
        modal.classList.remove('show');
        setTimeout(() => {
            if (!modal.classList.contains('show')) {
                modal.classList.add('hidden');
            }
        }, 300);
        document.body.style.overflow = '';
    },

    /**
     * Set up backdrop and close-button listeners for a modal
     */
    bindEvents(modalId, closeBtnSelector = '.modal-close') {
        const modal = document.getElementById(modalId);
        if (!modal) return;

        // Close on backdrop click (assuming the modal-content doesn't stopPropagation)
        modal.addEventListener('click', (e) => {
            if (e.target === modal) this.close(modalId);
        });

        // Close on escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && modal.classList.contains('show')) {
                this.close(modalId);
            }
        });

        // Close buttons
        modal.querySelectorAll(closeBtnSelector).forEach(btn => {
            btn.addEventListener('click', () => this.close(modalId));
        });
    }
};

/**
 * Shared Star Rating logic
 */
export const StarRating = {
    init(container, input, options = {}) {
        if (!container || !input) return;
        
        this.options = {
            selectedClass: options.selectedClass || 'text-yellow-500',
            unselectedClass: options.unselectedClass || 'text-gray-300',
            onChange: options.onChange || null
        };

        container.setAttribute('role', 'radiogroup');
        const stars = container.querySelectorAll('[data-rating]');
        
        const handleRating = (star) => {
            const val = star.getAttribute('data-rating');
            this.setRating(val, stars, input);
            if (this.options.onChange) this.options.onChange(val);
        };

        // Delegation for Click and Keydown
        container.addEventListener('click', (e) => {
            const star = e.target.closest('[data-rating]');
            if (star) {
                e.preventDefault();
                handleRating(star);
            }
        });

        container.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                const star = e.target.closest('[data-rating]');
                if (star) {
                    e.preventDefault();
                    handleRating(star);
                }
            }
        });

        // Mouse events for precise highlighting
        stars.forEach(star => {
            star.addEventListener('mouseenter', () => {
                const val = star.getAttribute('data-rating');
                this.highlight(val, stars);
            });
        });

        container.addEventListener('mouseleave', () => {
            this.highlight(input.value, stars);
        });

        // Set initial state
        this.highlight(input.value, stars);
    },

    setRating(val, stars, input) {
        input.value = val;
        this.highlight(val, stars);
    },

    highlight(val, stars) {
        const rating = parseFloat(val);
        stars.forEach(star => {
            const starVal = parseFloat(star.getAttribute('data-rating'));
            const isSelected = starVal <= rating;
            
            if (this.options) {
                star.classList.toggle(this.options.selectedClass, isSelected);
                star.classList.toggle(this.options.unselectedClass, !isSelected);
            } else {
                // Fallback for direct calls without init options
                star.classList.toggle('text-yellow-500', isSelected);
                star.classList.toggle('text-gray-300', !isSelected);
            }
            
            star.setAttribute('aria-checked', isSelected ? 'true' : 'false');
            
            if (window.feather) {
                const svg = star.querySelector('svg') || star;
                svg.style.fill = isSelected ? 'currentColor' : 'none';
            }
        });
    }
};

/**
 * Shared logic for custom date picker button overlays
 */
export const DateHelper = {
    bindPicker(btn, input, label, onReset = null) {
        if (!btn || !input || !label) return;

        btn.addEventListener('click', () => {
            if (onReset) onReset();
            
            btn.classList.add('active');
            input.style.pointerEvents = 'auto'; 
            
            if ('showPicker' in HTMLInputElement.prototype) {
                try { input.showPicker(); } catch(e) {}
            } else {
                input.focus();
            }
        });

        input.addEventListener('change', (e) => {
            if (e.target.value) {
                const d = new Date(e.target.value);
                const formatted = d.toLocaleDateString(undefined, { 
                    month: 'short', 
                    day: 'numeric', 
                    year: 'numeric' 
                });
                label.textContent = formatted;
            }
        });
    }
};
