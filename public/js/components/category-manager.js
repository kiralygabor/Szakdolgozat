/**
 * Category Manager Component
 * Handles category selection, role switching (Finder vs Tasker),
 * and dynamic service grid rendering.
 */
export class CategoryManager {
    constructor(options = {}) {
        this.jobsData = options.jobsData || {};
        this.urls = options.urls || {};
        this.translations = options.translations || {};
        this.isAuthenticated = options.isAuthenticated || false;

        this.state = {
            categoryId: options.initialCategoryId || '',
            role: 'finder'
        };

        this.init();
    }

    init() {
        this.cacheElements();
        this.initEventListeners();
        this.renderInitialState();
    }

    cacheElements() {
        this.elements = {
            list: document.getElementById('categories-list'),
            img: document.getElementById('cat-image'),
            title: document.getElementById('cat-title'),
            desc: document.getElementById('cat-desc'),
            jobsList: document.getElementById('jobs-list'),
            btnFinder: document.getElementById('btn-finder'),
            btnTasker: document.getElementById('btn-tasker'),
            helperText: document.getElementById('role-helper-text'),
            scrollHint: document.getElementById('mobile-scroll-hint'),
            fadeMask: document.getElementById('mobile-scroll-fade')
        };
    }

    initEventListeners() {
        if (this.elements.list) {
            this.elements.list.addEventListener('click', (e) => this.handleCategoryClick(e));
            this.elements.list.addEventListener('scroll', () => {
                if (this.elements.scrollHint) this.elements.scrollHint.style.opacity = '0';
                if (this.elements.fadeMask) this.elements.fadeMask.style.opacity = '0';
            }, { once: true });
        }

        if (this.elements.btnFinder) {
            this.elements.btnFinder.addEventListener('click', () => this.switchRole('finder'));
        }
        if (this.elements.btnTasker) {
            this.elements.btnTasker.addEventListener('click', () => this.switchRole('tasker'));
        }
    }

    switchRole(role) {
        if (role === 'tasker' && !this.isAuthenticated) {
            window.location.href = this.urls.login;
            return;
        }

        this.state.role = role;

        const activeClass = "relative z-10 px-4 sm:px-8 py-2.5 rounded-full text-sm font-bold transition-all duration-300 cat-btn-active shadow-sm";
        const inactiveClass = "relative z-10 px-4 sm:px-8 py-2.5 rounded-full text-sm font-bold transition-all duration-300 cat-btn-inactive";

        if (role === 'finder') {
            this.elements.btnFinder.className = activeClass;
            this.elements.btnTasker.className = inactiveClass;
            this.elements.helperText.textContent = this.translations.finderHelper;
        } else {
            this.elements.btnFinder.className = inactiveClass;
            this.elements.btnTasker.className = activeClass;
            this.elements.helperText.textContent = this.translations.taskerHelper;
        }

        this.renderJobs();
    }

    handleCategoryClick(e) {
        const btn = e.target.closest('button[data-id]');
        if (!btn) return;

        this.elements.list.querySelectorAll('button').forEach(b => {
            b.classList.remove('cat-btn-active');
            b.classList.add('cat-btn-inactive');
        });

        btn.classList.add('cat-btn-active');
        btn.classList.remove('cat-btn-inactive');

        this.state.categoryId = btn.getAttribute('data-id');
        this.elements.title.textContent = btn.getAttribute('data-name-translated');
        this.elements.desc.textContent = btn.getAttribute('data-desc-translated');
        this.elements.img.src = btn.getAttribute('data-image');

        this.renderJobs();
    }

    renderJobs() {
        const categoryId = this.state.categoryId;
        this.elements.jobsList.innerHTML = '';

        if (!categoryId || !this.jobsData[categoryId] || !this.jobsData[categoryId].length) {
            this.elements.jobsList.innerHTML = `
                <div class="col-span-full py-8 text-center category-text-muted">
                    <p>${this.translations.noServices}</p>
                </div>`;
            return;
        }

        this.jobsData[categoryId].forEach(job => {
            const a = document.createElement('a');
            const baseUrl = this.state.role === 'finder' ? this.urls.search : this.urls.postTask;
            a.href = `${baseUrl}?category=${categoryId}&job=${job.id}`;

            a.className = "group flex items-center justify-between p-4 rounded-xl transition-all duration-300 no-underline category-surface-bg border category-border-color category-text-main hover:border-[var(--primary-accent)] hover:shadow-md hover:-translate-y-0.5 hover:text-[var(--primary-accent)]";
            
            const iconHtml = `<span class="category-text-muted group-hover:text-[var(--primary-accent)] transition-colors ml-2">
                ${this.state.role === 'finder' ? "→" : "+"}
            </span>`;

            a.innerHTML = `<span>${job.title}</span>${iconHtml}`;
            this.elements.jobsList.appendChild(a);
        });
    }

    renderInitialState() {
        if (this.state.categoryId) {
            const preBtn = this.elements.list.querySelector(`button[data-id="${this.state.categoryId}"]`);
            if (preBtn) {
                preBtn.click();
            } else {
                this.renderJobs();
            }
        } else {
            this.renderJobs();
        }
    }
}
