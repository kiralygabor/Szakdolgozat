/**
 * Category Page — Handles sidebar navigation, role toggle, and service grid rendering.
 *
 * Dependencies:
 *   - window.CATEGORY_CONFIG (injected from Blade)
 *   - feather-icons (optional, for service card icons)
 */
document.addEventListener('DOMContentLoaded', () => {
    const { jobsData, urls, translations, isAuthenticated, firstCategoryId } = window.CATEGORY_CONFIG;

    const ACTIVE_CLASS = 'is-active';

    const elements = {
        list:       document.getElementById('categories-list'),
        img:        document.getElementById('cat-image'),
        title:      document.getElementById('cat-title'),
        desc:       document.getElementById('cat-desc'),
        jobsList:   document.getElementById('jobs-list'),
        btnFinder:  document.getElementById('btn-finder'),
        btnTasker:  document.getElementById('btn-tasker'),
        helperText: document.getElementById('role-helper-text'),
        scrollHint: document.getElementById('mobile-scroll-hint'),
        fadeMask:   document.getElementById('mobile-scroll-fade'),
    };

    if (!elements.list) return;

    /* ── State ─────────────────────────────────────────────────────────── */

    const urlParams = new URLSearchParams(window.location.search);
    const state = {
        categoryId: urlParams.get('category_id') || firstCategoryId,
        role: 'finder',
    };

    /* ── Role Toggle ───────────────────────────────────────────────────── */

    const ROLE_STYLES = {
        active:   'bg-blue-50 text-blue-700 shadow-sm dark:bg-blue-900/30 dark:text-blue-300',
        inactive: 'text-gray-500 hover:text-gray-700 dark:text-slate-400 dark:hover:text-slate-200',
    };
    const TOGGLE_BASE = 'relative z-10 px-6 sm:px-8 py-2.5 rounded-full text-sm transition-all duration-300';

    /**
     * Switches between Finder and Tasker modes.
     * Redirects unauthenticated users to login when selecting Tasker.
     */
    window.switchRole = function switchRole(role) {
        if (role === 'tasker' && !isAuthenticated) {
            window.location.href = urls.login;
            return;
        }

        state.role = role;

        const isFinder = role === 'finder';

        elements.btnFinder.className = `${TOGGLE_BASE} font-${isFinder ? 'black' : 'bold'} ${isFinder ? ROLE_STYLES.active : ROLE_STYLES.inactive}`;
        elements.btnTasker.className = `${TOGGLE_BASE} font-${isFinder ? 'bold' : 'black'} ${isFinder ? ROLE_STYLES.inactive : ROLE_STYLES.active}`;
        elements.helperText.textContent = isFinder ? translations.finderHelper : translations.taskerHelper;

        restartAnimation(elements.helperText);
        renderJobs(state.categoryId);
    };

    /* ── Category Selection ────────────────────────────────────────────── */

    elements.list.addEventListener('click', (e) => {
        const btn = e.target.closest('button[data-id]');
        if (!btn) return;

        setActiveCategory(btn);
        updateCategoryDetail(btn);
        renderJobs(state.categoryId);
        updateUrlParam('category_id', state.categoryId);
    });

    /**
     * Highlights the selected category button and deactivates others.
     */
    function setActiveCategory(activeBtn) {
        elements.list.querySelectorAll('.category-btn').forEach(btn => {
            btn.classList.remove(ACTIVE_CLASS);
        });
        activeBtn.classList.add(ACTIVE_CLASS);
    }

    /**
     * Updates the hero section (title, description, image) for the selected category.
     */
    function updateCategoryDetail(btn) {
        state.categoryId = btn.dataset.id;
        elements.title.textContent = btn.dataset.nameTranslated;
        elements.desc.textContent = btn.dataset.descTranslated;

        // Crossfade the hero image
        elements.img.style.opacity = '0';
        setTimeout(() => {
            elements.img.src = btn.dataset.image;
            elements.img.onload = () => { elements.img.style.opacity = '1'; };
        }, 200);
    }

    /* ── Services Grid ─────────────────────────────────────────────────── */

    /**
     * Renders the job/service cards for the given category.
     */
    function renderJobs(categoryId) {
        if (!elements.jobsList) return;
        elements.jobsList.innerHTML = '';

        const jobs = jobsData[categoryId];

        if (!categoryId || !jobs?.length) {
            elements.jobsList.innerHTML = buildEmptyState();
            replaceFeatherIcons();
            return;
        }

        const fragment = document.createDocumentFragment();

        jobs.forEach(job => {
            fragment.appendChild(buildJobCard(job, categoryId));
        });

        elements.jobsList.appendChild(fragment);
        replaceFeatherIcons();
    }

    /**
     * Creates a single service card element.
     */
    function buildJobCard(job, categoryId) {
        const a = document.createElement('a');

        const baseUrl = state.role === 'finder' ? urls.search : urls.postTask;
        const params = new URLSearchParams({ category: categoryId, job: job.id });
        a.href = `${baseUrl}?${params.toString()}`;

        a.className = [
            'group flex items-center justify-between px-4 py-3.5 rounded-xl border min-h-[72px]',
            'transition-all duration-300 transform hover:-translate-y-0.5 hover:shadow-md',
            'bg-white border-gray-100 text-gray-700 hover:border-blue-400 hover:text-blue-600',
            'dark:bg-slate-800 dark:border-slate-500 dark:text-slate-300',
            'dark:hover:border-blue-500 dark:hover:text-blue-400 dark:hover:bg-slate-700/50',
        ].join(' ');

        const icon = state.role === 'finder' ? 'arrow-right' : 'plus';
        a.innerHTML = `
            <span class="font-semibold tracking-tight leading-tight line-clamp-2 pr-2">${job.title}</span>
            <span class="flex-shrink-0 p-2 rounded-lg bg-gray-50 text-gray-400 group-hover:bg-blue-50 group-hover:text-blue-500 transition-colors dark:bg-slate-900/50">
                <i data-feather="${icon}" class="w-4 h-4"></i>
            </span>
        `;

        return a;
    }

    /**
     * Returns the HTML for an empty service state.
     */
    function buildEmptyState() {
        return `
            <div class="col-span-full py-12 text-center text-gray-400 dark:text-slate-500 animate-fade-in">
                <i data-feather="search" class="mx-auto mb-4 w-10 h-10 opacity-20"></i>
                <p class="text-lg font-medium">${translations.noServices}</p>
            </div>`;
    }

    /* ── Utilities ──────────────────────────────────────────────────────── */

    /** Triggers feather icon replacement if the library is loaded. */
    function replaceFeatherIcons() {
        if (window.feather) feather.replace();
    }

    /** Restarts a CSS animation by forcing a reflow. */
    function restartAnimation(el) {
        el.classList.remove('animate-fade-in');
        void el.offsetWidth;
        el.classList.add('animate-fade-in');
    }

    /** Updates a URL query parameter without reloading the page. */
    function updateUrlParam(key, value) {
        const url = new URL(window.location);
        url.searchParams.set(key, value);
        window.history.replaceState({}, '', url);
    }

    /* ── Mobile Scroll Hints ───────────────────────────────────────────── */

    elements.list.addEventListener('scroll', () => {
        if (elements.scrollHint) elements.scrollHint.style.opacity = '0';
        if (elements.fadeMask) elements.fadeMask.style.opacity = '0';
    }, { once: true });

    /* ── Initial Render ────────────────────────────────────────────────── */

    const initialBtn = elements.list.querySelector(`button[data-id="${state.categoryId}"]`);
    if (initialBtn) {
        initialBtn.click();
    } else {
        renderJobs(state.categoryId);
    }
});
