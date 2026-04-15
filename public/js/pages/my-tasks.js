document.addEventListener('DOMContentLoaded', function() {
    if (window.feather) window.feather.replace();

    // --- Hash Logic for Deep Linking ---
    (function() {
        const hash = window.location.hash;
        if (hash && hash.startsWith('#task-')) {
            const taskId = hash.replace('#task-', '');
            const url = new URL(window.location.href);
            if (url.searchParams.get('task_id') !== taskId) {
                url.searchParams.set('task_id', taskId);
                window.location.href = url.toString();
            }
        }
    })();

    // --- Controls & Menus ---
    const moreBtn = document.getElementById('more-btn');
    const moreMenu = document.getElementById('more-menu');
    
    if (moreBtn && moreMenu) {
        moreBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            moreMenu.classList.toggle('show');
        });
        document.addEventListener('click', () => moreMenu.classList.remove('show'));
    }

    // --- Task Details Modal ---
    window.openTaskDetailsModal = function () {
        const modal = document.getElementById('task-details-modal');
        if (modal) { 
            modal.classList.add('show'); 
            document.body.style.overflow = 'hidden'; 
        }
    };
    window.closeTaskDetailsModal = function () {
        const modal = document.getElementById('task-details-modal');
        if (modal) { 
            modal.classList.remove('show'); 
            document.body.style.overflow = ''; 
        }
    };
    const backdrop = document.getElementById('task-details-backdrop');
    if (backdrop) backdrop.addEventListener('click', () => window.closeTaskDetailsModal());

    // --- Offer Modal ---
    window.openOfferModal = function(data) {
        const modal = document.getElementById('offer-details-modal');
        if(!modal) return;

        const avatarEl = document.getElementById('modal-offer-avatar');
        if (data.avatarUrl) {
            avatarEl.innerHTML = `<img src="${data.avatarUrl}" class="w-full h-full rounded-full object-cover">`;
        } else {
            avatarEl.innerHTML = data.initials;
        }

        document.getElementById('modal-offer-name').innerText = data.name;
        document.getElementById('modal-offer-rating').innerText = data.rating;
        // In the template, we pass the translated "ago" if needed, or assume it's part of 'time'
        document.getElementById('modal-offer-time').innerText = data.timeText || data.time;
        document.getElementById('modal-offer-price').innerText = '€' + data.price;
        document.getElementById('modal-offer-message').innerText = data.message;

        const profileLink = document.getElementById('modal-profile-link');
        if (profileLink) profileLink.href = '/profile/' + data.userId;

        const form = document.getElementById('accept-offer-form');
        if(form) form.action = `/offers/${data.id}/accept`;

        const msgBtn = document.getElementById('message-tasker-btn');
        if(msgBtn) msgBtn.href = `/messages?user_id=${data.userId}`;

        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    };

    window.closeOfferModal = function() {
        const modal = document.getElementById('offer-details-modal');
        if(modal) { 
            modal.classList.remove('show'); 
            document.body.style.overflow = ''; 
        }
    };

    // --- Direct Quote Response Modal ---
    window.openDirectQuoteModal = function() {
        const modal = document.getElementById('direct-quote-modal');
        if(modal) {
            modal.classList.add('show');
            document.body.style.overflow = 'hidden';
            if(window.feather) window.feather.replace();
        }
    };
    window.closeDirectQuoteModal = function() {
        const modal = document.getElementById('direct-quote-modal');
        if(modal) { 
            modal.classList.remove('show'); 
            document.body.style.overflow = ''; 
        }
    };

    // --- Complete Modal ---
    window.openCompleteTaskModal = function() {
        const modal = document.getElementById('complete-task-modal');
        if(modal) {
            modal.classList.add('show');
            document.body.style.overflow = 'hidden';
            const choiceBtns = document.getElementById('complete-choice-buttons');
            const revForm = document.getElementById('complete-review-form');
            if(choiceBtns) choiceBtns.classList.remove('hidden');
            if(revForm) revForm.classList.add('hidden');
            if(typeof setRating === 'function') setRating(0);
        }
    };
    window.closeCompleteTaskModal = function() {
        const modal = document.getElementById('complete-task-modal');
        if(modal) { 
            modal.classList.remove('show'); 
            document.body.style.overflow = ''; 
        }
    };
    window.showReviewForm = function() {
        const choiceBtns = document.getElementById('complete-choice-buttons');
        const revForm = document.getElementById('complete-review-form');
        if(choiceBtns) choiceBtns.classList.add('hidden');
        if(revForm) revForm.classList.remove('hidden');
    };
    window.setRating = function(value) {
        const ratingInput = document.getElementById('rating-value');
        if(ratingInput) ratingInput.value = value;
        for(let i=1; i<=5; i++) {
            const icon = document.getElementById('star-'+i);
            if(icon) {
                if(i <= value) {
                    icon.classList.add('text-yellow-400');
                    icon.classList.remove('text-gray-300');
                    icon.style.fill = 'currentColor';
                } else {
                    icon.classList.remove('text-yellow-400');
                    icon.classList.add('text-gray-300');
                    icon.style.fill = 'none';
                }
            }
        }
        if(window.feather) window.feather.replace();
    };

    // --- Edit Task Modal Logic ---
    window.openEditTaskModal = function() {
        const modal = document.getElementById('edit-task-modal');
        if (modal) {
            modal.classList.add('show');
            document.body.style.overflow = 'hidden';
            if (window.feather) feather.replace();
        }
    };

    window.closeEditTaskModal = function() {
        const modal = document.getElementById('edit-task-modal');
        if (modal) {
            modal.classList.remove('show');
            document.body.style.overflow = '';
        }
    };

    // Category / Job Selection logic
    const editCategorySelect = document.getElementById('editCategorySelect');
    const editJobSelect = document.getElementById('editJobSelect');
    if (editCategorySelect && editJobSelect) {
        editCategorySelect.addEventListener('change', function() {
            const catId = this.value;
            const selectServiceLabel = editJobSelect.getAttribute('data-placeholder') || 'Select Service';
            editJobSelect.innerHTML = `<option value="">${selectServiceLabel}</option>`;
            if (!catId) return;
            
            // Assume allCategories is globally available or passed to window
            if (window.TASK_DATA && window.TASK_DATA.allCategories) {
                const category = window.TASK_DATA.allCategories.find(c => c.id == catId);
                if (category && category.jobs) {
                    const jobTranslations = window.TASK_DATA.jobTranslations || {};
                    category.jobs.forEach(job => {
                        const option = document.createElement('option');
                        option.value = job.id;
                        option.textContent = jobTranslations[job.name] || job.name;
                        editJobSelect.appendChild(option);
                    });
                }
            }
        });
    }

    // Task Type / Online logic
    const editTypeSelect = document.getElementById('editTypeSelect');
    const editLocationContainer = document.getElementById('editLocationContainer');
    const editLocationInput = document.getElementById('editLocationInput');
    if (editTypeSelect && editLocationContainer) {
        editTypeSelect.addEventListener('change', function() {
            if (this.value === 'online') {
                editLocationContainer.classList.add('hidden');
                if (editLocationInput) editLocationInput.value = 'Online';
            } else {
                editLocationContainer.classList.remove('hidden');
                if (editLocationInput && editLocationInput.value === 'Online') editLocationInput.value = '';
            }
        });
    }

    // Date / Time Selectors
    const editBeforeBtn = document.getElementById('editBeforeDateBtn');
    const editOnBtn = document.getElementById('editOnDateBtn');
    const editFlexibleBtn = document.getElementById('editFlexibleBtn');
    const editBeforeVal = document.getElementById('editBeforeDateValue');
    const editOnVal = document.getElementById('editOnDateValue');
    const editBeforeLabel = document.getElementById('editBeforeDateLabel');
    const editOnLabel = document.getElementById('editOnDateLabel');
    const editFlexInput = document.getElementById('edit_input_is_date_flexible');

    function resetEditDateOptions() {
        if(editBeforeBtn) editBeforeBtn.classList.remove('active');
        if(editOnBtn) editOnBtn.classList.remove('active');
        if(editFlexibleBtn) editFlexibleBtn.setAttribute('data-active', 'false');
        
        if(editBeforeVal) {
            editBeforeVal.value = '';
            editBeforeVal.style.pointerEvents = 'none';
        }
        if(editOnVal) {
            editOnVal.value = '';
            editOnVal.style.pointerEvents = 'none';
        }
        
        if(editBeforeLabel) editBeforeLabel.textContent = editBeforeLabel.getAttribute('data-placeholder');
        if(editOnLabel) editOnLabel.textContent = editOnLabel.getAttribute('data-placeholder');
        if(editFlexInput) editFlexInput.value = '0';
    }

    if(editBeforeBtn && editBeforeVal) {
        editBeforeBtn.addEventListener('click', () => {
            resetEditDateOptions();
            editBeforeBtn.classList.add('active');
            editBeforeVal.style.pointerEvents = 'auto';
            if ('showPicker' in HTMLInputElement.prototype) {
                try { editBeforeVal.showPicker(); } catch(e) {}
            }
        });
        editBeforeVal.addEventListener('change', (e) => {
            if(e.target.value) {
                const d = new Date(e.target.value);
                editBeforeLabel.textContent = d.toLocaleDateString(undefined, { month: 'short', day: 'numeric', year: 'numeric' });
            }
        });
    }

    if(editOnBtn && editOnVal) {
        editOnBtn.addEventListener('click', () => {
            resetEditDateOptions();
            editOnBtn.classList.add('active');
            editOnVal.style.pointerEvents = 'auto';
            if ('showPicker' in HTMLInputElement.prototype) {
                try { editOnVal.showPicker(); } catch(e) {}
            }
        });
        editOnVal.addEventListener('change', (e) => {
            if(e.target.value) {
                const d = new Date(e.target.value);
                editOnLabel.textContent = d.toLocaleDateString(undefined, { month: 'short', day: 'numeric', year: 'numeric' });
            }
        });
    }

    if(editFlexibleBtn) {
        editFlexibleBtn.addEventListener('click', () => {
            resetEditDateOptions();
            editFlexibleBtn.setAttribute('data-active', 'true');
            if(editFlexInput) editFlexInput.value = '1';
        });
    }

    // Time Checkbox Logic
    const editNeedTimeCheckbox = document.getElementById('editNeedTimeCheckbox');
    const editTimeOptions = document.getElementById('editTimeOfDayOptions');
    if(editNeedTimeCheckbox && editTimeOptions) {
        editNeedTimeCheckbox.addEventListener('change', function() {
            if(this.checked) {
                editTimeOptions.classList.remove('hidden');
            } else {
                editTimeOptions.classList.add('hidden');
                document.querySelectorAll('.modal-time-option input').forEach(ip => { ip.checked = false; });
                document.querySelectorAll('.modal-time-option').forEach(lb => { lb.classList.remove('selected'); });
            }
        });
    }
    
    document.querySelectorAll('.modal-time-option').forEach(label => {
        label.addEventListener('click', function(e) {
            setTimeout(() => {
                const checkbox = this.querySelector('input[type="checkbox"]');
                if (checkbox.checked) {
                    this.classList.add('selected');
                } else {
                    this.classList.remove('selected');
                }
            }, 10);
        });
    });

    // --- Global Keyboard Support ---
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' || e.key === ' ') {
            const target = e.target;
            if (target.getAttribute('role') === 'button' || target.getAttribute('tabindex') === '0') {
                if (target.tagName !== 'BUTTON' && target.tagName !== 'A' && target.tagName !== 'INPUT') {
                    e.preventDefault();
                    target.click();
                }
            }
        }
    });
});
