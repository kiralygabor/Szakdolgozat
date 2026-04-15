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

        results.slice(0, 8).forEach(item => {
            const div = document.createElement('div');
            div.className = 'px-4 py-3 hover:bg-[var(--bg-hover)] cursor-pointer text-[var(--text-primary)] text-sm border-b border-[var(--border-base)] last:border-0';
            div.textContent = item.name;
            div.addEventListener('click', () => {
                this.input.value = item.name;
                this.hide();
                if (this.onSelect) this.onSelect(item);
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
