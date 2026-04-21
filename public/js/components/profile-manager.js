/**
 * Profile and Settings Manager
 * Handles tab switching, avatar previews, accessibility settings, and localized forms.
 */
import { Autocomplete } from '../modules/autocomplete.js';
import { ThemeEngine } from '../modules/ui-utils.js';

export class ProfileManager {
    constructor(options = {}) {
        this.options = options;
        this.init();
    }

    init() {
        this.initTabs();
        this.initAvatarPreview();
        this.initLocationAutocomplete();
        this.initSettingsLogic();
        this.initNotificationToggles();
        this.initPasswordToggles();
        this.initPhoneSelector();
        this.initFormConfirmations();
    }

    /**
     * Handle manual tab switching to ensure focus and accessibility control
     */
    initTabs() {
        const tabs = document.querySelectorAll('.sub-menu-link[data-section]');
        const panes = document.querySelectorAll('.tab-pane');

        const switchTab = (sectionId) => {
            tabs.forEach(t => t.classList.toggle('active', t.getAttribute('data-section') === sectionId));
            panes.forEach(p => {
                const isActive = p.id === sectionId;
                p.classList.toggle('show', isActive);
                p.classList.toggle('active', isActive);
            });
            // Update URL without reload if possible
            const url = new URL(window.location);
            url.searchParams.set('tab', sectionId);
            window.history.replaceState({}, '', url);
        };

        tabs.forEach(tab => {
            tab.addEventListener('click', (e) => {
                e.preventDefault();
                switchTab(tab.getAttribute('data-section'));
            });
        });

        // Initialize from URL param
        const urlParams = new URLSearchParams(window.location.search);
        const tabParam = urlParams.get('tab');
        if (tabParam) {
            switchTab(tabParam);
        }
    }

    /**
     * Preview uploaded avatar image before form submission
     */
    initAvatarPreview() {
        const input = document.getElementById('avatarInput');
        const preview = document.getElementById('avatarPreview');
        if (!input || !preview) return;

        input.addEventListener('change', () => {
            const file = input.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => (preview.src = e.target.result);
                reader.readAsDataURL(file);
            }
        });

        // Keyboard support for the label-based upload button
        const label = document.querySelector('label[for="avatarInput"]');
        if (label) {
            label.addEventListener('keydown', (e) => {
                if (e.key === ' ' || e.key === 'Enter') {
                    e.preventDefault();
                    input.click();
                }
            });
        }
    }

    /**
     * Initialize city search for profile location field
     */
    initLocationAutocomplete() {
        const input = document.getElementById('location');
        const hidden = document.getElementById('city_id');
        const suggestions = document.getElementById('location-suggestions');
        
        if (input && suggestions) {
            new Autocomplete({
                input: input,
                dropdown: suggestions,
                endpoint: '/api/cities',
                onSelect: (item) => {
                    if (hidden) hidden.value = item.id;
                }
            });
        }
    }

    /**
     * Logic for Theme, Language, and Accessibility settings
     */
    initSettingsLogic() {
        const themeSelect = document.getElementById('theme-select');
        const langSelect = document.getElementById('lang-select');
        const applyBtn = document.getElementById('apply-settings-btn');
        const masterAcc = document.getElementById('accessibility-master-toggle');
        const subOptions = document.getElementById('access-sub-options');
        const rmToggle = document.getElementById('reduced-motion-toggle');
        const hcToggle = document.getElementById('high-contrast-toggle');

        if (!applyBtn) return;

        // Sync UI states with window.userSettings
        const settings = window.userSettings || {};
        if (themeSelect) themeSelect.value = settings.theme || 'light';
        if (rmToggle) rmToggle.checked = settings.reduced_motion || false;
        if (hcToggle) hcToggle.checked = settings.high_contrast || false;
        
        if (masterAcc) {
            masterAcc.checked = (rmToggle?.checked || hcToggle?.checked);
            if (masterAcc.checked) subOptions?.classList.remove('hidden');
            masterAcc.addEventListener('change', () => subOptions?.classList.toggle('hidden', !masterAcc.checked));
        }

        // Logic coupling: Dark Mode and High Contrast are mutually exclusive
        if (hcToggle) {
            hcToggle.addEventListener('change', () => {
                if (hcToggle.checked && themeSelect) themeSelect.value = 'light';
            });
        }
        if (themeSelect) {
            themeSelect.addEventListener('change', () => {
                if (themeSelect.value === 'dark' && hcToggle) hcToggle.checked = false;
            });
        }

        applyBtn.addEventListener('click', async () => {
            const theme = themeSelect?.value || 'light';
            const isAccEnabled = masterAcc ? masterAcc.checked : false;
            const rm = isAccEnabled ? (rmToggle?.checked || false) : false;
            const hc = isAccEnabled ? (hcToggle?.checked || false) : false;

            // Apply locally and save to DB
            // We use Promise.all to save everything together if possible, 
            // though currently saveSettings sends separate requests.
            await Promise.all([
                ThemeEngine.applyTheme(theme),
                ThemeEngine.applyAcc('reduced-motion', rm),
                ThemeEngine.applyAcc('high-contrast', hc)
            ]);

            // Handle language redirect
            if (langSelect) {
                const newLocale = langSelect.value;
                if (newLocale !== this.options.currentLocale) {
                    this.submitLanguageChange(newLocale);
                    return;
                }
            }

            alert(this.options.translations.settingsSaved || 'Settings applied!');
        });
    }

    submitLanguageChange(locale) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/language/${locale}`;
        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = this.options.csrf;
        form.appendChild(csrf);
        document.body.appendChild(form);
        form.submit();
    }

    /**
     * Logic for notification digest and category grid
     */
    initNotificationToggles() {
        const digestToggle = document.getElementById('digest_toggle_profile');
        const categorySection = document.getElementById('category_selection_profile');
        const toggleBtn = document.getElementById('toggle_categories_btn');
        const grid = document.getElementById('categories_grid');
        const chevron = document.getElementById('toggle_categories_chevron');

        if (!digestToggle || !categorySection) return;

        digestToggle.addEventListener('change', () => {
            categorySection.classList.toggle('hidden', !digestToggle.checked);
            if (!digestToggle.checked && grid) {
                grid.classList.add('hidden');
                if (chevron) chevron.style.transform = '';
            }
        });

        if (toggleBtn && grid) {
            toggleBtn.addEventListener('click', () => {
                const isHidden = grid.classList.toggle('hidden');
                if (chevron) chevron.style.transform = isHidden ? '' : 'rotate(180deg)';
            });
        }
    }

    /**
     * Initialize all password visibility toggles on the page
     */
    initPasswordToggles() {
        document.querySelectorAll('.password-toggle').forEach(btn => {
            btn.addEventListener('click', () => {
                const targetId = btn.getAttribute('data-target');
                const input = document.getElementById(targetId);
                if (!input) return;

                const isPass = input.type === 'password';
                input.type = isPass ? 'text' : 'password';
                btn.classList.toggle('fa-eye', isPass);
                btn.classList.toggle('fa-eye-slash', !isPass);
            });
        });
    }

    /**
     * Handle phone country prefix dropdown
     */
    initPhoneSelector() {
        const btn = document.getElementById('dropdown-phone-button');
        const menu = document.getElementById('dropdown-phone');
        const flag = document.getElementById('selected-flag');
        const input = document.getElementById('phone');

        if (!btn || !menu) return;

        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            menu.classList.toggle('show');
        });

        document.addEventListener('click', () => menu.classList.remove('show'));

        menu.querySelectorAll('.country-option').forEach(opt => {
            opt.addEventListener('click', () => {
                const prefix = opt.getAttribute('data-prefix');
                const svgClone = opt.querySelector('svg').cloneNode(true);
                svgClone.classList.replace('me-3', 'me-2');

                if (flag) {
                    flag.innerHTML = '';
                    flag.appendChild(svgClone);
                    flag.appendChild(document.createTextNode(prefix));
                }
                
                menu.classList.remove('show');
                if (input) input.focus();
            });
        });
    }

    /**
     * Add native confirmation dialogues to sensitive forms
     */
    initFormConfirmations() {
        const profileForm = document.querySelector('form[action*="profile"]');
        if (profileForm) {
            profileForm.addEventListener('submit', (e) => {
                // Only if it's the main profile update form (form_type=profile)
                const type = profileForm.querySelector('input[name="form_type"]')?.value;
                if (type === 'profile') {
                    if (!confirm(this.options.translations.confirmUpdate || 'Are you sure you want to update your profile?')) {
                        e.preventDefault();
                    }
                }
            });
        }

        const deleteForm = document.querySelector('form[action*="profile"][method="POST"] input[name="_method"][value="DELETE"]')?.closest('form');
        if (deleteForm) {
            deleteForm.addEventListener('submit', (e) => {
                if (!confirm(this.options.translations.confirmDelete || 'Are you sure you want to delete your account? This action cannot be undone.')) {
                    e.preventDefault();
                }
            });
        }
    }
}
