/**
 * PostTaskForm Manager for MiniJobz
 * Handles multi-step form logic, validation, and dynamic UI for task creation.
 */
import { Autocomplete } from '../modules/autocomplete.js';
import { Config } from '../modules/config.js';

export class PostTaskForm {
    constructor(options) {
        this.form = document.getElementById(options.formId);
        this.categoriesData = options.categoriesData;
        
        // Navigation Elements
        this.panes = options.stepPanes.map(id => document.getElementById(id));
        this.backBtn = document.getElementById(options.backBtnId);
        this.nextBtn = document.getElementById(options.nextBtnId);
        this.submitBtn = document.getElementById(options.submitBtnId);
        this.stepIndex = 0;

        // Form Inputs
        this.categorySelect = document.getElementById('categorySelect');
        this.jobSelect = document.getElementById('jobSelect');
        this.taskInput = document.getElementById('taskDescription');
        this.budgetInput = document.getElementById('budgetInput');
        this.locationInput = document.getElementById('pickupSuburb');
        this.inPersonOption = document.getElementById('inPersonOption');
        this.onlineOption = document.getElementById('onlineOption');

        this.isLocationSelected = !!this.locationInput?.value;
        this.budgetErrorTimeout = null;

        this.init();
    }

    init() {
        if (!this.form) return;

        // 1. Navigation Event Listeners
        this.backBtn.addEventListener('click', () => this.handleBack());
        this.nextBtn.addEventListener('click', () => this.handleNext());
        this.form.addEventListener('submit', (e) => this.handleSubmit(e));

        // 2. Step Validation Listeners
        this.form.addEventListener('input', () => this.validateCurrent());
        this.form.addEventListener('change', () => this.validateCurrent());

        // 3. Category/Job Logic
        this.categorySelect.addEventListener('change', (e) => this.populateJobs(e.target.value));

        // 4. Autocomplete for Location
        if (this.locationInput) {
            new Autocomplete({
                input: this.locationInput,
                dropdown: document.getElementById('pickupSuburbDropdown'),
                endpoint: Config.api.cities,
                onSelect: () => {
                    this.isLocationSelected = true;
                    this.validateCurrent();
                },
                onClear: () => {
                    this.isLocationSelected = false;
                    this.validateCurrent();
                }
            });
        }

        // 5. Photo Upload Logic (Simplified registry)
        this.initPhotoUpload();
        this.initDatePickers();

        // 6. Location Type Selection
        this.inPersonOption.addEventListener('click', () => this.setWorkType(false));
        this.onlineOption.addEventListener('click', () => this.setWorkType(true));

        this.showStep(0);
    }

    initDatePickers() {
        const onDateBtn = document.getElementById('onDateBtn');
        const onDateValue = document.getElementById('onDateValue');
        const onDateLabel = document.getElementById('onDateLabel');
        const beforeDateBtn = document.getElementById('beforeDateBtn');
        const beforeDateValue = document.getElementById('beforeDateValue');
        const beforeDateLabel = document.getElementById('beforeDateLabel');
        const flexibleBtn = document.querySelector('[data-option="flexible"]');

        const resetDateOptions = () => {
            onDateBtn.classList.remove('active');
            beforeDateBtn.classList.remove('active');
            flexibleBtn?.setAttribute('data-active', 'false');
            onDateValue.value = '';
            beforeDateValue.value = '';
            // Reset to defaults
            onDateLabel.textContent = onDateLabel.dataset.default;
            beforeDateLabel.textContent = beforeDateLabel.dataset.default;
            this.validateCurrent();
        };

        onDateBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            resetDateOptions();
            onDateBtn.classList.add('active');
            setTimeout(() => onDateValue.showPicker(), 50);
        });

        beforeDateBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            resetDateOptions();
            beforeDateBtn.classList.add('active');
            setTimeout(() => beforeDateValue.showPicker(), 50);
        });

        const formatDate = (val) => {
            const d = new Date(val + 'T00:00:00');
            return d.toLocaleDateString(document.documentElement.lang === 'hu' ? 'hu-HU' : 'en-US', {
                month: 'short', day: 'numeric', year: 'numeric'
            });
        };

        onDateValue.addEventListener('change', () => {
            if (onDateValue.value) {
                onDateLabel.textContent = formatDate(onDateValue.value);
                onDateBtn.classList.add('active');
                this.validateCurrent();
            }
        });

        beforeDateValue.addEventListener('change', () => {
            if (beforeDateValue.value) {
                beforeDateLabel.textContent = formatDate(beforeDateValue.value);
                beforeDateBtn.classList.add('active');
                this.validateCurrent();
            }
        });

        flexibleBtn?.addEventListener('click', () => {
            resetDateOptions();
            flexibleBtn.setAttribute('data-active', 'true');
            this.validateCurrent();
        });
    }

    showStep(index) {
        this.stepIndex = index;
        this.panes.forEach((p, idx) => p.classList.toggle('hidden', idx !== index));
        
        this.backBtn.disabled = index === 0;
        this.nextBtn.classList.toggle('hidden', index === this.panes.length - 1);
        this.submitBtn.classList.toggle('hidden', index !== this.panes.length - 1);
        
        this.updateSidebar(index);
        this.validateCurrent();
        if (window.feather) feather.replace();
    }

    handleBack() {
        if (this.stepIndex > 0) this.showStep(this.stepIndex - 1);
    }

    handleNext() {
        if (this.stepIndex < this.panes.length - 1) this.showStep(this.stepIndex + 1);
    }

    validateCurrent() {
        let isValid = false;

        switch(this.stepIndex) {
            case 0: // Step 1: Category & Title
                isValid = this.validateStep1();
                break;
            case 1: // Step 2: Location
                isValid = this.validateStep2();
                break;
            case 2: // Step 3: Details
                isValid = document.getElementById('taskDetails').value.trim().length > 0;
                break;
            case 3: // Step 4: Budget
                isValid = this.validateBudget();
                break;
        }

        this.nextBtn.disabled = !isValid;
        this.nextBtn.classList.toggle('opacity-60', !isValid);
        this.submitBtn.disabled = !isValid;
        this.submitBtn.classList.toggle('opacity-60', !isValid);
    }

    validateStep1() {
        const titleOk = this.taskInput.value.trim().length > 0;
        const categoryOk = this.categorySelect.value !== "";
        const jobOk = this.jobSelect.value !== "";
        
        const onDate = document.getElementById('onDateValue').value;
        const beforeDate = document.getElementById('beforeDateValue').value;
        const isFlexible = document.querySelector('[data-option="flexible"]')?.getAttribute('data-active') === 'true';
        const dateOk = onDate || beforeDate || isFlexible;

        return titleOk && categoryOk && jobOk && dateOk;
    }

    validateStep2() {
        const isOnline = this.onlineOption.classList.contains('selected');
        return isOnline || (!isOnline && this.isLocationSelected);
    }

    validateBudget() {
        const val = this.budgetInput.value.trim();
        if (!val) return false;
        
        const n = Number(val);
        return n >= 5 && n <= 5000;
    }

    setWorkType(isOnline) {
        this.inPersonOption.classList.toggle('selected', !isOnline);
        this.onlineOption.classList.toggle('selected', isOnline);
        document.getElementById('locationInputs').classList.toggle('hidden', isOnline);
        this.validateCurrent();
    }

    populateJobs(catId, selectedJobId = null) {
        this.jobSelect.innerHTML = '<option value="">Select a service</option>';
        if (!catId) return;

        const category = this.categoriesData.find(c => c.id == catId);
        if (category && category.jobs) {
            category.jobs.forEach(job => {
                const opt = document.createElement('option');
                opt.value = job.id;
                opt.textContent = job.name;
                if (selectedJobId && job.id == selectedJobId) opt.selected = true;
                this.jobSelect.appendChild(opt);
            });
        }
        this.validateCurrent();
    }

    updateSidebar(index) {
        const items = document.querySelectorAll('#sidebarSteps li');
        items.forEach((li, idx) => {
            const isActive = idx === index;
            li.classList.toggle('font-bold', isActive);
            li.classList.toggle('details-text-main', isActive);
            li.classList.toggle('border-l-4', isActive);
            li.classList.toggle('border-[var(--primary-accent)]', isActive);
            li.classList.toggle('pl-3', isActive);
            li.classList.toggle('-ml-4', isActive);
            
            li.classList.toggle('details-text-muted', !isActive);
            li.classList.toggle('font-medium', !isActive);
        });
    }

    initPhotoUpload() {
        const selector = document.getElementById('photoSelectorInput');
        const container = document.getElementById('photoPreviewContainer');
        const submission = document.getElementById('photoSubmissionInput');
        let files = [];

        const render = () => {
            container.innerHTML = '';
            container.style.display = files.length ? 'block' : 'none';
            files.forEach((file, idx) => {
                const div = document.createElement('div');
                div.className = 'photo-preview';
                div.innerHTML = `<img src="${URL.createObjectURL(file)}"><span>×</span>`;
                div.querySelector('span').onclick = () => {
                    files.splice(idx, 1);
                    update();
                    render();
                };
                container.appendChild(div);
            });
        };

        const update = () => {
            const dt = new DataTransfer();
            files.forEach(f => dt.items.add(f));
            submission.files = dt.files;
        };

        selector?.addEventListener('change', (e) => {
            Array.from(e.target.files).forEach(f => {
                if (f.type.startsWith('image/')) files.push(f);
            });
            update();
            render();
            selector.value = '';
        });

        document.getElementById('photoUploadPlus')?.addEventListener('click', () => selector.click());
    }

    handleSubmit(e) {
        if (this.submitBtn.disabled) {
            e.preventDefault();
            return;
        }

        const isOnline = this.onlineOption.classList.contains('selected');
        document.getElementById('input_task_type').value = isOnline ? 'online' : 'in-person';
        
        const flexibleBtn = document.querySelector('[data-option="flexible"]');
        document.getElementById('input_is_date_flexible').value = flexibleBtn?.getAttribute('data-active') === 'true' ? '1' : '0';

        this.submitBtn.disabled = true;
        this.submitBtn.innerHTML = '<i data-feather="loader" class="animate-spin w-4 h-4 inline mr-2"></i> Submitting...';
        if (window.feather) feather.replace();
    }
}
