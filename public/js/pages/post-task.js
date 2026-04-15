/**
 * PostTask Manager for MiniJobz
 * Handles multi-step form logic, photo uploads, and dynamic service population.
 */
export class PostTaskManager {
    constructor(config) {
        this.config = config;
        this.stepIndex = 0;
        this.allPhotos = [];
        this.isLocationSelected = false;
        this.budgetErrorTimeout = null;

        this.initElements();
        this.bindEvents();
        this.initializeState();
        this.showStep(0);
    }

    initElements() {
        this.panes = [
            document.getElementById('step-1'),
            document.getElementById('step-2'),
            document.getElementById('step-3'),
            document.getElementById('step-4'),
        ];
        this.backBtn = document.getElementById('backBtn');
        this.nextBtn = document.getElementById('nextBtn');
        this.submitBtn = document.getElementById('submitBtn');
        this.sidebarItems = document.querySelectorAll('#sidebarSteps li');
        
        this.form = document.getElementById('postTaskForm');
        this.categorySelect = document.getElementById('categorySelect');
        this.jobSelect = document.getElementById('jobSelect');
        this.taskInput = document.getElementById('taskDescription');
        this.budgetInput = document.getElementById('budgetInput');
        this.budgetError = document.getElementById('budgetError');
        this.budgetWrapper = document.getElementById('budgetWrapper');
        this.taskDetails = document.getElementById('taskDetails');
        
        this.onDateBtn = document.getElementById('onDateBtn');
        this.onDateValue = document.getElementById('onDateValue');
        this.onDateLabel = document.getElementById('onDateLabel');
        this.beforeDateBtn = document.getElementById('beforeDateBtn');
        this.beforeDateValue = document.getElementById('beforeDateValue');
        this.beforeDateLabel = document.getElementById('beforeDateLabel');
        this.flexibleBtn = document.querySelector('[data-option="flexible"]');

        this.inPersonOption = document.getElementById('inPersonOption');
        this.onlineOption = document.getElementById('onlineOption');
        this.pickupSuburb = document.getElementById('pickupSuburb');
        this.pickupSuburbDropdown = document.getElementById('pickupSuburbDropdown');

        this.photoUploadPlus = document.getElementById('photoUploadPlus');
        this.photoSelectorInput = document.getElementById('photoSelectorInput');
        this.photoSubmissionInput = document.getElementById('photoSubmissionInput');
        this.photoPreviewContainer = document.getElementById('photoPreviewContainer');

        this.needTimeCheckbox = document.getElementById('needTimeCheckbox');
        this.timeOfDayOptions = document.getElementById('timeOfDayOptions');
        this.timeOptions = document.querySelectorAll('.time-option');
    }

    bindEvents() {
        // Navigation
        this.backBtn.addEventListener('click', () => this.prevStep());
        this.nextBtn.addEventListener('click', () => this.nextStep());

        // Step 1: Category & Content
        this.categorySelect.addEventListener('change', (e) => this.populateJobs(e.target.value));
        this.jobSelect.addEventListener('change', () => this.validateCurrent());
        this.taskInput.addEventListener('input', () => this.validateCurrent());

        // Dates
        this.onDateBtn.addEventListener('click', (e) => this.handleDatePicker(e, 'on'));
        this.beforeDateBtn.addEventListener('click', (e) => this.handleDatePicker(e, 'before'));
        this.onDateValue.addEventListener('change', () => this.updateDateLabel('on'));
        this.beforeDateValue.addEventListener('change', () => this.updateDateLabel('before'));
        if (this.flexibleBtn) {
            this.flexibleBtn.addEventListener('click', () => {
                this.resetDateOptions();
                this.flexibleBtn.setAttribute('data-active', 'true');
                this.validateCurrent();
            });
        }

        // Times
        this.needTimeCheckbox.addEventListener('click', () => {
            this.timeOfDayOptions.classList.toggle('hidden', !this.needTimeCheckbox.checked);
            if (!this.needTimeCheckbox.checked) {
                document.querySelectorAll('input[name="preferred_time[]"]').forEach(cb => cb.checked = false);
                this.timeOptions.forEach(opt => opt.classList.remove('selected'));
            }
        });
        this.timeOptions.forEach(option => {
            const checkbox = option.querySelector('input[type="checkbox"]');
            checkbox.addEventListener('change', () => option.classList.toggle('selected', checkbox.checked));
            option.addEventListener('keydown', (e) => {
                if (e.key === ' ' || e.key === 'Enter') {
                    e.preventDefault();
                    checkbox.checked = !checkbox.checked;
                    checkbox.dispatchEvent(new Event('change'));
                }
            });
        });

        // Step 2: Location
        this.inPersonOption.addEventListener('click', () => this.handleLocationSelect(false));
        this.onlineOption.addEventListener('click', () => this.handleLocationSelect(true));
        [this.inPersonOption, this.onlineOption].forEach(el => {
            el.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    this.handleLocationSelect(el.id === 'onlineOption');
                }
            });
        });
        if (this.pickupSuburb) {
            this.pickupSuburb.addEventListener('input', (e) => this.handleSuburbInput(e));
        }

        // Step 3: Details & Photos
        this.taskDetails.addEventListener('input', () => this.validateCurrent());
        this.photoUploadPlus.addEventListener('click', () => this.photoSelectorInput.click());
        this.photoSelectorInput.addEventListener('change', (e) => this.handlePhotoSelection(e));

        // Step 4: Budget
        this.budgetInput.addEventListener('input', () => this.validateCurrent());

        // Submission
        this.form.addEventListener('submit', (e) => this.handleSubmit(e));
    }

    showStep(i) {
        this.stepIndex = i;
        this.panes.forEach((p, idx) => p.classList.toggle('hidden', idx !== i));
        this.backBtn.disabled = i === 0;
        this.nextBtn.classList.toggle('hidden', i === this.panes.length - 1);
        this.submitBtn.classList.toggle('hidden', i !== this.panes.length - 1);
        this.updateSidebar(i);
        this.validateCurrent();
        if (window.feather) window.feather.replace();
    }

    prevStep() {
        if (this.stepIndex > 0) this.showStep(this.stepIndex - 1);
    }

    nextStep() {
        if (this.stepIndex === 0) {
            if (!this.validateDate()) return;
        }
        if (this.stepIndex < this.panes.length - 1) this.showStep(this.stepIndex + 1);
    }

    updateSidebar(i) {
        this.sidebarItems.forEach((li, idx) => {
            const isActive = idx === i;
            li.classList.toggle('font-bold', isActive);
            li.classList.toggle('details-text-main', isActive);
            li.classList.toggle('border-l-4', isActive);
            li.classList.toggle('border-[var(--primary-accent)]', isActive);
            li.classList.toggle('pl-3', isActive);
            li.classList.toggle('-ml-4', isActive);
        });
    }

    validateCurrent() {
        let ok = true;
        if (this.stepIndex === 0) {
            const titleOk = this.taskInput.value.trim().length > 0;
            const categoryOk = this.categorySelect.value !== "";
            const jobOk = this.jobSelect.value !== "";
            const isFlexible = this.flexibleBtn && this.flexibleBtn.getAttribute('data-active') === 'true';
            const dateOk = this.onDateValue.value !== "" || this.beforeDateValue.value !== "" || isFlexible;
            ok = titleOk && categoryOk && jobOk && dateOk;
        } else if (this.stepIndex === 1) {
            const isInPerson = this.inPersonOption.classList.contains('selected');
            ok = !isInPerson || (isInPerson && this.isLocationSelected);
        } else if (this.stepIndex === 2) {
            ok = this.taskDetails.value.trim().length > 0;
        } else if (this.stepIndex === 3) {
            ok = this.validateBudget();
        }

        this.nextBtn.disabled = !ok;
        this.nextBtn.classList.toggle('opacity-60', !ok);
        this.nextBtn.classList.toggle('cursor-not-allowed', !ok);
        this.submitBtn.disabled = !ok;
        this.submitBtn.classList.toggle('opacity-60', !ok);
        this.submitBtn.classList.toggle('cursor-not-allowed', !ok);
    }

    validateDate() {
        const isFlexible = this.flexibleBtn && this.flexibleBtn.getAttribute('data-active') === 'true';
        if (!isFlexible) {
            const dStr = this.onDateValue.value || this.beforeDateValue.value;
            if (dStr) {
                const d = new Date(dStr + 'T00:00:00');
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                if (d < today) {
                    this.showDateError(this.config.i18n.dateError);
                    return false;
                }
            }
        }
        this.hideDateError();
        return true;
    }

    validateBudget() {
        const val = this.budgetInput.value.trim();
        const serverError = this.budgetWrapper.parentElement.querySelector('.server-error');
        clearTimeout(this.budgetErrorTimeout);
        if (serverError) serverError.classList.add('hidden');

        if (val === "") {
            this.budgetError.classList.add('hidden');
            this.budgetWrapper.classList.remove('is-invalid');
            return false;
        }

        const n = Number(val);
        const isValidRange = (n >= 5 && n <= 5000);
        if (isValidRange) {
            this.budgetError.classList.add('hidden');
            this.budgetWrapper.classList.remove('is-invalid');
            return true;
        } else {
            this.budgetErrorTimeout = setTimeout(() => {
                const currentVal = this.budgetInput.value.trim();
                const currentN = Number(currentVal);
                if (currentVal !== "" && (currentN < 5 || currentN > 5000)) {
                    this.budgetError.classList.remove('hidden');
                    this.budgetWrapper.classList.add('is-invalid');
                }
            }, 800);
            return false;
        }
    }

    populateJobs(catId, selectedJobId = null) {
        this.jobSelect.innerHTML = `<option value="">${this.config.i18n.servicePlaceholder}</option>`;
        if (!catId) return;
        const category = this.config.categories.find(c => c.id == catId);
        const uniqueJobs = new Map();
        if (category && category.jobs) {
            category.jobs.forEach(job => {
                if (!uniqueJobs.has(job.id)) uniqueJobs.set(job.id, job);
            });
            uniqueJobs.forEach(job => {
                const opt = document.createElement('option');
                opt.value = job.id;
                opt.textContent = job.name;
                if (selectedJobId && job.id == selectedJobId) opt.selected = true;
                this.jobSelect.appendChild(opt);
            });
        }
        this.validateCurrent();
    }

    handleDatePicker(e, type) {
        e.stopPropagation();
        this.resetDateOptions();
        const btn = type === 'on' ? this.onDateBtn : this.beforeDateBtn;
        const input = type === 'on' ? this.onDateValue : this.beforeDateValue;
        btn.classList.add('active');
        setTimeout(() => { if (input.showPicker) input.showPicker(); }, 50);
    }

    updateDateLabel(type) {
        const input = type === 'on' ? this.onDateValue : this.beforeDateValue;
        const label = type === 'on' ? this.onDateLabel : this.beforeDateLabel;
        const btn = type === 'on' ? this.onDateBtn : this.beforeDateBtn;

        if (input.value) {
            const date = new Date(input.value + 'T00:00:00');
            label.textContent = date.toLocaleDateString(this.config.locale, { month: 'short', day: 'numeric', year: 'numeric' });
            btn.classList.add('active');
            this.validateCurrent();
        }
    }

    resetDateOptions() {
        this.onDateBtn.classList.remove('active');
        this.beforeDateBtn.classList.remove('active');
        if (this.flexibleBtn) this.flexibleBtn.setAttribute('data-active', 'false');
        this.onDateValue.value = '';
        this.beforeDateValue.value = '';
        this.onDateLabel.textContent = this.config.i18n.onDate;
        this.beforeDateLabel.textContent = this.config.i18n.beforeDate;
        this.hideDateError();
        this.validateCurrent();
    }

    handleLocationSelect(isOnline) {
        this.inPersonOption.classList.toggle('selected', !isOnline);
        this.onlineOption.classList.toggle('selected', isOnline);
        this.inPersonOption.setAttribute('aria-checked', !isOnline);
        this.onlineOption.setAttribute('aria-checked', isOnline);
        document.getElementById('locationInputs').classList.toggle('hidden', isOnline);
        this.validateCurrent();
    }

    handleSuburbInput(e) {
        this.isLocationSelected = false;
        clearTimeout(this.searchTimeout);
        const q = e.target.value.trim();
        if (q.length < 2) {
            this.pickupSuburbDropdown.classList.add('hidden');
            return;
        }
        this.searchTimeout = setTimeout(async () => {
            try {
                const res = await fetch(`/api/cities?q=${encodeURIComponent(q)}`);
                const cities = await res.json();
                this.pickupSuburbDropdown.innerHTML = '';
                if (!cities || !cities.length) {
                    this.pickupSuburbDropdown.classList.add('hidden');
                    return;
                }
                cities.slice(0, 8).forEach(c => {
                    const d = document.createElement('div');
                    d.className = 'location-autocomplete-item';
                    d.textContent = c.name;
                    d.onclick = () => {
                        this.pickupSuburb.value = c.name;
                        this.isLocationSelected = true;
                        this.pickupSuburbDropdown.classList.add('hidden');
                        this.validateCurrent();
                    };
                    this.pickupSuburbDropdown.appendChild(d);
                });
                this.pickupSuburbDropdown.classList.remove('hidden');
            } catch (err) {}
        }, 300);
    }

    handlePhotoSelection(e) {
        const files = Array.from(e.target.files || []);
        files.forEach(file => {
            if (file.type && file.type.startsWith('image/')) {
                this.allPhotos.push({
                    file: file,
                    url: URL.createObjectURL(file)
                });
            }
        });
        this.updateSubmissionFiles();
        this.renderPreviews();
        this.photoSelectorInput.value = '';
    }

    updateSubmissionFiles() {
        const dt = new DataTransfer();
        this.allPhotos.forEach(item => dt.items.add(item.file));
        this.photoSubmissionInput.files = dt.files;
    }

    renderPreviews() {
        this.photoPreviewContainer.innerHTML = '';
        this.photoPreviewContainer.style.display = this.allPhotos.length ? 'flex' : 'none';
        this.allPhotos.forEach((item, index) => {
            const div = document.createElement('div');
            div.className = 'photo-preview';
            div.innerHTML = `
                <img src="${item.url}" alt="Preview">
                <div class="remove-photo" tabindex="0" role="button" aria-label="Remove">×</div>
            `;
            div.querySelector('.remove-photo').onclick = (e) => {
                e.stopPropagation();
                this.allPhotos.splice(index, 1);
                this.updateSubmissionFiles();
                this.renderPreviews();
            };
            this.photoPreviewContainer.appendChild(div);
        });
    }

    handleSubmit(e) {
        if (this.submitBtn.disabled) {
            e.preventDefault();
            return;
        }
        const isOnline = this.onlineOption.classList.contains('selected');
        document.getElementById('input_task_type').value = isOnline ? 'online' : 'in-person';
        const flexibleActive = this.flexibleBtn && this.flexibleBtn.getAttribute('data-active') === 'true';
        document.getElementById('input_is_date_flexible').value = flexibleActive ? '1' : '0';

        this.submitBtn.disabled = true;
        this.submitBtn.classList.add('opacity-60', 'cursor-not-allowed');
        this.submitBtn.innerHTML = `<i data-feather="loader" class="animate-spin w-4 h-4 inline mr-2"></i> ${this.config.i18n.submitting}`;
        if (window.feather) window.feather.replace();
    }

    showDateError(msg) {
        const err = document.getElementById('clientDateError');
        if (err) { err.textContent = msg; err.classList.remove('hidden'); }
    }

    hideDateError() {
        const err = document.getElementById('clientDateError');
        if (err) err.classList.add('hidden');
        document.querySelectorAll('.server-date-error').forEach(e => e.classList.add('hidden'));
    }

    initializeState() {
        // Handle pre-selections
        if (this.config.preCat) {
            this.categorySelect.value = this.config.preCat;
            this.populateJobs(this.config.preCat, this.config.preJob);
        }

        // Restore task type
        const initialTaskType = document.getElementById('input_task_type').value;
        this.handleLocationSelect(initialTaskType === 'online');

        // Location pre-fill
        if (this.pickupSuburb && this.pickupSuburb.value.trim() !== '') {
            this.isLocationSelected = true;
        }

        // Restore flexible date
        const isFlexible = document.getElementById('input_is_date_flexible').value === '1';
        if (isFlexible && this.flexibleBtn) this.flexibleBtn.setAttribute('data-active', 'true');

        // Restore preferred times
        if (this.config.oldTimes && this.config.oldTimes.length > 0) {
            this.needTimeCheckbox.checked = true;
            this.timeOfDayOptions.classList.remove('hidden');
            this.config.oldTimes.forEach(time => {
                const el = document.querySelector(`.time-option[data-time="${time}"]`);
                if (el) {
                    el.classList.add('selected');
                    const cb = el.querySelector('input[type="checkbox"]');
                    if (cb) cb.checked = true;
                }
            });
        }
    }
}
