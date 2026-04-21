/**
 * Reusable Autocomplete Module for MiniJobz
 */
import { Config } from './config.js';

export class Autocomplete {
    /**
     * @param {Object} options 
     * @param {HTMLElement} options.input - Search input element
     * @param {HTMLElement} options.dropdown - Results container
     * @param {string} options.endpoint - API endpoint for search
     * @param {Function} options.onSelect - Choice callback (item) => void
     * @param {Function} options.onClear - Callback when cleared
     */
    constructor(options) {
        this.input = options.input;
        this.dropdown = options.dropdown;
        this.endpoint = options.endpoint;
        this.onSelect = options.onSelect;
        this.onClear = options.onClear;
        
        this.timeout = null;
        this.currentResults = [];

        this.init();
    }

    init() {
        if (!this.input || !this.dropdown) return;

        this.input.addEventListener('input', (e) => this.handleInput(e));
        
        // Keyboard navigation on input
        this.input.addEventListener('keydown', (e) => {
            const items = this.dropdown.querySelectorAll('[role="option"]');
            if (!items.length) return;

            if (e.key === 'ArrowDown') {
                e.preventDefault();
                items[0].focus();
            }
        });
        
        // Hide on outside click
        document.addEventListener('click', (e) => {
            if (!this.input.contains(e.target) && !this.dropdown.contains(e.target)) {
                this.hide();
            }
        });
    }

    handleInput(e) {
        const query = e.target.value.trim();
        clearTimeout(this.timeout);

        if (this.onClear) this.onClear();

        if (query.length < (Config.autocomplete.minChars || 2)) {
            this.hide();
            return;
        }

        this.timeout = setTimeout(() => this.search(query), Config.timeouts.debounce);
    }

    async search(query) {
        try {
            const res = await fetch(`${this.endpoint}?q=${encodeURIComponent(query)}`);
            const results = await res.json();
            this.render(results);
        } catch (err) {
            console.error('Search failed:', err);
            this.hide();
        }
    }

    render(results) {
        this.dropdown.innerHTML = '';
        this.currentResults = results;

        if (!results || results.length === 0) {
            this.hide();
            return;
        }

        results.slice(0, 8).forEach((item, index) => {
            const div = document.createElement('div');
            div.className = 'px-4 py-3 hover:bg-[var(--bg-hover)] focus:bg-[var(--bg-hover)] focus:outline-none cursor-pointer text-[var(--text-primary)] text-sm border-b border-[var(--border-base)] last:border-0';
            div.textContent = item.name;
            div.setAttribute('role', 'option');
            div.setAttribute('tabindex', '0');

            const select = () => {
                this.input.value = item.name;
                this.hide();
                if (this.onSelect) this.onSelect(item);
                this.input.focus();
            };

            div.addEventListener('click', select);
            div.addEventListener('keydown', (e) => {
                const items = this.dropdown.querySelectorAll('[role="option"]');
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    select();
                } else if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    const next = items[index + 1] || items[0];
                    if (next) next.focus();
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    const prev = items[index - 1] || this.input;
                    if (prev) prev.focus();
                } else if (e.key === 'Escape') {
                    this.hide();
                    this.input.focus();
                }
            });

            this.dropdown.appendChild(div);
        });

        this.show();
    }

    show() {
        this.dropdown.classList.remove('hidden');
    }

    hide() {
        this.dropdown.classList.add('hidden');
    }
}
