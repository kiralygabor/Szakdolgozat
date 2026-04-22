/**
 * Dashboard Manager for My Tasks
 * Handles task management modals, offer reviews, edit forms, and rating systems.
 */
import { ModalManager, StarRating, DateHelper } from '../modules/ui-utils.js';

export class DashboardManager {
    constructor(options = {}) {
        this.options = options;
        this.init();
    }

    init() {
        this.initHashHandling();
        this.initModals();
        this.initRatingSystem();
        this.initEditFormLogic();
        this.initUIInteractions();
    }

    /**
     * Handle task-specific hash links for better deep linking
     */
    initHashHandling() {
        const hash = window.location.hash;
        if (hash && hash.startsWith('#task-')) {
            const taskId = hash.replace('#task-', '');
            const url = new URL(window.location.href);
            if (url.searchParams.get('task_id') !== taskId) {
                url.searchParams.set('task_id', taskId);
                window.location.href = url.toString();
            }
        }
    }

    initModals() {
        // Standardize modal events
        ['task-details-modal', 'offer-details-modal', 'direct-quote-modal', 'complete-task-modal', 'edit-task-modal'].forEach(id => {
            ModalManager.bindEvents(id);
        });

        // Specialized open handlers attached to window for legacy onclicks (until fully migrated)
        window.openTaskDetailsModal = () => ModalManager.open('task-details-modal');
        window.closeTaskDetailsModal = () => ModalManager.close('task-details-modal');
        
        window.openOfferModal = (data) => this.handleOpenOfferModal(data);
        window.closeOfferModal = () => ModalManager.close('offer-details-modal');

        window.openDirectQuoteModal = () => ModalManager.open('direct-quote-modal');
        window.closeDirectQuoteModal = () => ModalManager.close('direct-quote-modal');

        window.openCompleteTaskModal = () => {
            ModalManager.open('complete-task-modal');
            document.getElementById('complete-choice-buttons')?.classList.remove('hidden');
            document.getElementById('complete-review-form')?.classList.add('hidden');
            this.ratingSystem?.setRating(0);
        };
        window.closeCompleteTaskModal = () => ModalManager.close('complete-task-modal');

        window.openEditTaskModal = () => ModalManager.open('edit-task-modal');
        window.closeEditTaskModal = () => ModalManager.close('edit-task-modal');
    }

    handleOpenOfferModal(data) {
        const modalId = 'offer-details-modal';
        const modal = document.getElementById(modalId);
        if(!modal) return;

        const avatarEl = document.getElementById('modal-offer-avatar');
        if (avatarEl) {
            avatarEl.innerHTML = data.avatarUrl 
                ? `<img src="${data.avatarUrl}" class="w-full h-full rounded-full object-cover">`
                : data.initials;
        }

        this.setText('modal-offer-name', data.name);
        this.setText('modal-offer-rating', data.rating);
        this.setText('modal-offer-time', `${data.time} ${this.options.translations.ago}`);
        this.setText('modal-offer-message', data.message);

        const priceEl = document.getElementById('modal-offer-price');
        if (priceEl) {
            priceEl.innerText = '€' + data.price;
            const budget = this.options.activeTaskPrice || 0;
            const offerPrice = parseFloat(data.price.replace(/,/g, ''));
            
            priceEl.style.color = (offerPrice > budget) ? 'var(--details-error)' 
                                : (offerPrice < budget) ? 'var(--details-success)' 
                                : 'var(--primary-accent)';
        }

        const profileLink = document.getElementById('modal-profile-link');
        if (profileLink) profileLink.href = '/profile/' + data.userId;

        const form = document.getElementById('accept-offer-form');
        if(form) form.action = `/offers/${data.id}/accept`;

        const msgBtn = document.getElementById('message-tasker-btn');
        if(msgBtn) msgBtn.href = `/messages?user_id=${data.userId}`;

        ModalManager.open(modalId);
    }

    setText(id, text) {
        const el = document.getElementById(id);
        if (el) el.innerText = text;
    }

    initRatingSystem() {
        const container = document.getElementById('star-rating-input');
        const input = document.getElementById('rating-value');
        if (container && input) {
            StarRating.init(container, input);
        }
        
        window.showReviewForm = () => {
            document.getElementById('complete-choice-buttons')?.classList.add('hidden');
            document.getElementById('complete-review-form')?.classList.remove('hidden');
        };

        window.setRating = (val) => StarRating.setRating(val, container.querySelectorAll('[data-rating]'), input);
    }

    initEditFormLogic() {
        const editCat = document.getElementById('editCategorySelect');
        const editJob = document.getElementById('editJobSelect');
        const editType = document.getElementById('editTypeSelect');
        const locContainer = document.getElementById('editLocationContainer');
        const locInput = document.getElementById('editLocationInput');

        if (editCat && editJob) {
            editCat.addEventListener('change', () => {
                const catId = editCat.value;
                editJob.innerHTML = `<option value="">${this.options.translations.selectService}</option>`;
                if (!catId) return;

                const category = this.options.allCategories.find(c => c.id == catId);
                if (category && category.jobs) {
                    category.jobs.forEach(job => {
                        const option = document.createElement('option');
                        option.value = job.id;
                        option.textContent = this.options.jobTranslations[job.name] || job.name;
                        editJob.appendChild(option);
                    });
                }
            });
        }

        if (editType && locContainer) {
            editType.addEventListener('change', () => {
                const isOnline = editType.value === 'online';
                locContainer.classList.toggle('hidden', isOnline);
                if (isOnline && locInput) locInput.value = 'Online';
                else if (!isOnline && locInput?.value === 'Online') locInput.value = '';
            });
        }

        this.initEditDateLogic();
        this.initEditTimeLogic();
    }

    initEditDateLogic() {
        const config = {
            before: {
                btn: document.getElementById('editBeforeDateBtn'),
                input: document.getElementById('editBeforeDateValue'),
                label: document.getElementById('editBeforeDateLabel')
            },
            on: {
                btn: document.getElementById('editOnDateBtn'),
                input: document.getElementById('editOnDateValue'),
                label: document.getElementById('editOnDateLabel')
            },
            flexible: {
                btn: document.getElementById('editFlexibleBtn'),
                input: document.getElementById('edit_input_is_date_flexible')
            }
        };

        const resetAll = () => {
            Object.values(config).forEach(c => {
                if (c.btn) {
                    c.btn.classList.remove('active');
                    c.btn.setAttribute('data-active', 'false');
                }
                if (c.input) {
                    c.input.value = (c === config.flexible) ? '0' : '';
                    c.input.style.pointerEvents = 'none';
                }
            });
            if (config.before.label) config.before.label.textContent = this.options.translations.beforeDate;
            if (config.on.label) config.on.label.textContent = this.options.translations.onDate;
        };

        DateHelper.bindPicker(config.before.btn, config.before.input, config.before.label, resetAll);
        DateHelper.bindPicker(config.on.btn, config.on.input, config.on.label, resetAll);

        if (config.flexible.btn) {
            config.flexible.btn.addEventListener('click', () => {
                resetAll();
                config.flexible.btn.setAttribute('data-active', 'true');
                if (config.flexible.input) config.flexible.input.value = '1';
            });
        }

        // Clean up before submit
        const form = document.querySelector('#edit-task-modal form');
        if (form) {
            form.addEventListener('submit', () => {
                if (config.before.btn && !config.before.btn.classList.contains('active')) config.before.input.value = '';
                if (config.on.btn && !config.on.btn.classList.contains('active')) config.on.input.value = '';
            });
        }
    }

    initEditTimeLogic() {
        const master = document.getElementById('editNeedTimeCheckbox');
        const options = document.getElementById('editTimeOfDayOptions');
        if (!master || !options) return;

        master.addEventListener('change', () => {
            options.classList.toggle('hidden', !master.checked);
            if (!master.checked) {
                options.querySelectorAll('input').forEach(i => (i.checked = false));
                options.querySelectorAll('.modal-time-option').forEach(l => l.classList.remove('selected'));
            }
        });

        options.querySelectorAll('.modal-time-option').forEach(label => {
            label.addEventListener('click', () => {
                setTimeout(() => {
                    const cb = label.querySelector('input');
                    label.classList.toggle('selected', cb.checked);
                }, 10);
            });
        });
    }

    initUIInteractions() {
        // More Options Dropdown
        const moreBtn = document.getElementById('more-btn');
        const moreMenu = document.getElementById('more-menu');
        if (moreBtn && moreMenu) {
            moreBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                moreMenu.classList.toggle('show');
            });
            document.addEventListener('click', () => moreMenu.classList.remove('show'));
        }

        // A11y: Space/Enter on role="button"
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                const target = e.target;
                if (target.getAttribute('role') === 'button' || target.getAttribute('tabindex') === '0') {
                    if (!['BUTTON', 'A', 'INPUT'].includes(target.tagName)) {
                        e.preventDefault();
                        target.click();
                    }
                }
            }
        });
    }
}
