/**
 * Task Explorer Component
 * Handles MapLibre integration, marker clustering, price range sliders,
 * and unified filter logic for the Browse Tasks page.
 */
import { DropdownManager } from '../modules/ui-utils.js';

export class TaskExplorer {
    constructor(options = {}) {
        this.options = {
            mapContainerId: 'map',
            tasksData: [],
            isAuthenticated: false,
            translations: {},
            routes: {},
            ...options
        };
        
        this.map = null;
        this.markers = {};
        this.cityCache = {
            get(name) { try { return JSON.parse(localStorage.getItem('geocode:' + name)); } catch { return null; } },
            set(name, coords) { try { localStorage.setItem('geocode:' + name, JSON.stringify(coords)); } catch { } }
        };

        this.init();
    }

    init() {
        this.initDropdowns();
        this.initMap();
        this.initPriceSliders();
        this.initFilterEvents();
        this.initMobileFilters();
    }

    initDropdowns() {
        const priceBtn = document.getElementById('price-btn');
        const priceMenu = document.getElementById('price-menu');
        if (priceBtn && priceMenu) DropdownManager.register('price', priceBtn, priceMenu);

        const typeBtn = document.getElementById('type-btn');
        const typeMenu = document.getElementById('type-menu');
        if (typeBtn && typeMenu) DropdownManager.register('type', typeBtn, typeMenu);
    }

    initMap() {
        const container = document.getElementById(this.options.mapContainerId);
        if (!container) return;

        this.map = new maplibregl.Map({
            container: this.options.mapContainerId,
            style: {
                version: 8,
                sources: {
                    'osm-tiles': {
                        type: 'raster',
                        tiles: ['https://a.tile.openstreetmap.org/{z}/{x}/{y}.png'],
                        tileSize: 256,
                        attribution: '© OpenStreetMap'
                    }
                },
                layers: [{
                    id: 'osm-tiles',
                    type: 'raster',
                    source: 'osm-tiles',
                    minzoom: 0,
                    maxzoom: 19
                }]
            },
            center: [19.1483, 47.1629], // Budapest
            zoom: 6.4,
            cooperativeGestures: true
        });

        this.map.addControl(new maplibregl.NavigationControl({ showCompass: false }), 'bottom-right');

        this.plotTasks();
    }

    async geocodeCity(name) {
        if (!name) return null;
        const cached = this.cityCache.get(name);
        if (cached) return cached;
        
        try {
            const res = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(name)}&limit=1`);
            const data = await res.json();
            if (data && data[0]) {
                const coords = { lat: parseFloat(data[0].lat), lng: parseFloat(data[0].lon) };
                this.cityCache.set(name, coords);
                return coords;
            }
        } catch (e) {
            console.error('Geocoding failed for', name, e);
        }
        return null;
    }

    async plotTasks() {
        const locations = [...new Set(this.options.tasksData.map(t => t.location))];
        const locationToCoords = {};
        
        for (const loc of locations) {
            const coords = await this.geocodeCity(loc);
            if (coords) locationToCoords[loc] = coords;
            await new Promise(r => setTimeout(r, 400)); // Rate limiting for Nominatim
        }

        const bounds = new maplibregl.LngLatBounds();

        this.options.tasksData.forEach(t => {
            const baseCoords = locationToCoords[t.location];
            if (!baseCoords) return;

            // Jitter
            const jitter = 0.008;
            const lng = baseCoords.lng + (Math.random() - 0.5) * jitter;
            const lat = baseCoords.lat + (Math.random() - 0.5) * jitter;

            const el = document.createElement('div');
            el.className = 'map-marker-container group';
            el.style.cursor = 'pointer';

            const mColor = t.is_my_task ? 'var(--my-task-marker)' : 'var(--task-marker)';
            const pColor = t.is_my_task ? 'var(--my-task-marker-ping)' : 'var(--task-marker-ping)';

            el.innerHTML = `
                <div class="relative flex items-center justify-center">
                    <div class="absolute w-8 h-8 rounded-full animate-ping opacity-75" style="background-color: ${pColor}"></div>
                    <div class="w-4 h-4 rounded-full border-2 border-white shadow-lg relative z-10 transition-all duration-300" 
                         style="background-color: ${mColor}"></div>
                </div>
            `;

            const loginUrl = `${this.options.routes.login}?returnUrl=${encodeURIComponent('/tasks/' + t.id)}`;
            const detailsUrl = t.is_my_task ? '/my-tasks#task-' + t.id : '/tasks/' + t.id;
            const finalUrl = this.options.isAuthenticated ? detailsUrl : loginUrl;

            const popupHTML = `
                <div class="p-3 min-w-[200px]">
                    <div class="flex items-center justify-between gap-2 mb-2">
                        <div class="text-[10px] font-bold browse-text-muted uppercase tracking-wider">${t.location}</div>
                        ${t.is_my_task ? `<span class="px-2 py-0.5 bg-[var(--nav-dropdown-hover)] text-[var(--primary-accent)] text-[9px] font-bold uppercase rounded-md border border-[var(--primary-accent)]">${this.options.translations.myTask}</span>` : ''}
                    </div>
                    <div class="font-bold text-sm browse-text-main mb-1 leading-tight">${t.title}</div>
                    <div class="text-[var(--primary-accent)] font-extrabold text-sm mb-4">€${t.price.toLocaleString()}</div>
                    <a href="${finalUrl}" class="btn block w-full py-2.5 text-center bg-[var(--primary-accent)] hover:bg-[var(--primary-hover)] text-white text-[11px] font-bold rounded-lg transition-all shadow-md no-underline">${this.options.translations.viewDetails}</a>
                </div>
            `;

            const marker = new maplibregl.Marker({ element: el })
                .setLngLat([lng, lat])
                .setPopup(new maplibregl.Popup({
                    offset: 15,
                    closeButton: false,
                    className: 'custom-task-popup'
                }).setHTML(popupHTML))
                .addTo(this.map);

            this.markers[t.id] = marker;
            bounds.extend([lng, lat]);

            this.bindCardMarkerInteractivity(t.id, el, mColor);
        });

        // Uncomment if you want map to fit markers on load
        // if (!bounds.isEmpty()) this.map.fitBounds(bounds, { padding: 70, maxZoom: 13 });
    }

    bindCardMarkerInteractivity(taskId, markerEl, defaultColor) {
        const card = document.getElementById(`task-card-${taskId}`);
        if (!card) return;

        const highlightColor = defaultColor; // Or specialized highlight color
        const innerDot = markerEl.querySelector('.z-10');
        const pingEl = markerEl.querySelector('.animate-ping');

        card.addEventListener('mouseenter', () => {
            if (pingEl) pingEl.classList.add('scale-150');
            if (innerDot) {
                innerDot.classList.add('scale-150');
                innerDot.style.filter = 'brightness(0.9)';
            }
        });

        card.addEventListener('mouseleave', () => {
            if (pingEl) pingEl.classList.remove('scale-150');
            if (innerDot) {
                innerDot.classList.remove('scale-150');
                innerDot.style.filter = '';
            }
        });
    }

    initPriceSliders() {
        this.setupSlider('price-min', 'price-max', 'price-track', 'price-display', 'price-text');
        this.setupSlider('mobile-price-min', 'mobile-price-max', 'mobile-price-track', 'mobile-price-text', null);
    }

    setupSlider(minId, maxId, trackId, displayId, btnTextId) {
        const minEl = document.getElementById(minId);
        const maxEl = document.getElementById(maxId);
        const track = document.getElementById(trackId);
        const display = document.getElementById(displayId);
        
        if (!minEl || !maxEl || !track) return;

        const RANGE_MIN = 5;
        const RANGE_MAX = 5000;
        const GAP = 10;

        const update = (source) => {
            let minVal = parseInt(minEl.value);
            let maxVal = parseInt(maxEl.value);

            if (minVal > maxVal - GAP) {
                if (source === 'min') {
                    minVal = maxVal - GAP;
                    minEl.value = minVal;
                } else {
                    maxVal = minVal + GAP;
                    maxEl.value = maxVal;
                }
            }

            const pMin = ((minVal - RANGE_MIN) / (RANGE_MAX - RANGE_MIN)) * 100;
            const pMax = ((maxVal - RANGE_MIN) / (RANGE_MAX - RANGE_MIN)) * 100;
            
            track.style.left = pMin + '%';
            track.style.right = (100 - pMax) + '%';
            track.style.width = 'auto';

            if (display) display.textContent = `€${minVal.toLocaleString()} - €${maxVal.toLocaleString()}`;
        };

        minEl.addEventListener('input', () => update('min'));
        maxEl.addEventListener('input', () => update('max'));
        update('min');

        // Bind Apply button if exists
        const applyBtnId = minId.startsWith('mobile') ? 'apply-mobile-filters' : 'price-apply';
        document.getElementById(applyBtnId)?.addEventListener('click', () => {
            const form = document.getElementById(minId.startsWith('mobile') ? 'mobile-filters-form' : 'filters-form');
            form?.submit();
        });

        const cancelBtnId = minId.startsWith('mobile') ? 'clear-mobile-filters' : 'price-cancel';
        document.getElementById(cancelBtnId)?.addEventListener('click', () => {
            DropdownManager.closeAllExcept();
        });
    }

    initFilterEvents() {
        const form = document.getElementById('filters-form');
        if (!form) return;

        ['category-filter', 'job-filter', 'sort-filter'].forEach(id => {
            document.getElementById(id)?.addEventListener('change', (e) => {
                if (id === 'category-filter') {
                    const jobFilter = document.getElementById('job-filter');
                    if (jobFilter) jobFilter.value = '';
                }
                form.submit();
            });
        });

        // City Search
        this.initCitySearch('type-city-search', 'type-city-dropdown', 'city-search-hidden', form);
    }

    initCitySearch(inputId, dropdownId, hiddenId, form) {
        const input = document.getElementById(inputId);
        const dropdown = document.getElementById(dropdownId);
        const hidden = document.getElementById(hiddenId);
        if (!input || !dropdown) return;

        let timeout;
        input.addEventListener('input', (e) => {
            clearTimeout(timeout);
            const q = e.target.value;
            if (q.length < 2) { dropdown.classList.add('hidden'); return; }

            timeout = setTimeout(async () => {
                try {
                    const res = await fetch(`/api/cities?q=${encodeURIComponent(q)}`);
                    const cities = await res.json();
                    dropdown.innerHTML = '';
                    if (cities.length) {
                        dropdown.classList.remove('hidden');
                        cities.slice(0, 8).forEach(c => {
                            const div = document.createElement('div');
                            div.className = 'px-3 py-2 text-sm hover:bg-[var(--bg-secondary)] cursor-pointer browse-text-main border-b browse-border-color last:border-0';
                            div.textContent = c.name;
                            div.onclick = () => {
                                hidden.value = c.name;
                                form.submit();
                            };
                            dropdown.appendChild(div);
                        });
                    }
                } catch (err) { }
            }, 300);
        });
    }

    initMobileFilters() {
        const trigger = document.getElementById('mobile-filter-trigger');
        const modal = document.getElementById('mobile-filters-modal');
        const closeBtns = ['close-mobile-filters', 'clear-mobile-filters'];
        const form = document.getElementById('mobile-filters-form');

        if (!trigger || !modal) return;

        trigger.addEventListener('click', () => {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
            if (window.feather) window.feather.replace();
        });

        const close = () => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        };

        closeBtns.forEach(id => document.getElementById(id)?.addEventListener('click', close));

        // Mobile Tabs
        const typeTabs = document.querySelectorAll('.mobile-type-tab');
        const typeHidden = document.getElementById('mobile-type-hidden');
        typeTabs.forEach(tab => {
            tab.addEventListener('click', () => {
                const val = tab.dataset.value;
                typeHidden.value = val;
                typeTabs.forEach(t => {
                    const isActive = t === tab;
                    t.style.backgroundColor = isActive ? 'var(--primary-accent)' : 'var(--bg-secondary)';
                    t.style.color = isActive ? '#ffffff' : 'var(--text-secondary)';
                });
            });
        });

        // Mobile City Search
        this.initCitySearch('mobile-city-search-input', 'mobile-city-results', 'mobile-city-hidden', form);
    }
}

// Attach legacy global handlers for Blade templates
window.toggleRemoteInfo = () => {
    const el = document.getElementById('remote-info-pop');
    if (!el) return;
    const isHidden = el.classList.contains('hidden');
    el.classList.toggle('hidden');
    if (isHidden) {
        if (window.feather) window.feather.replace();
        setTimeout(() => el.classList.add('hidden'), 5000);
    }
};
