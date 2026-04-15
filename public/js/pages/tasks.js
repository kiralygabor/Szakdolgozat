/**
 * Task Discovery & Exploration Logic
 * Extracted from tasks.blade.php for Clean Code
 */
document.addEventListener('DOMContentLoaded', () => {
    const { taskPoints, translations, urls, filters } = window.TASKS_CONFIG || {};
    const tasksData = taskPoints || [];

    // 1. Initialize Feather Icons
    function refreshIcons() {
        if (window.feather && typeof window.feather.replace === 'function') {
            window.feather.replace();
        }
    }
    refreshIcons();
    window.refreshIcons = refreshIcons; // expose globally if needed
    
    // 2. Map Setup
    const mapEl = document.getElementById('map');
    let map = null;
    if (mapEl && typeof maplibregl !== 'undefined') {
        map = new maplibregl.Map({
          container: 'map',
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
          center: [19.1483, 47.1629],
          zoom: 6.4
        });
        map.addControl(new maplibregl.NavigationControl({ showCompass: false }), 'bottom-right');

        // 3. Map Data & Markers
        const cityCache = {
          get(name){ try { return JSON.parse(localStorage.getItem('geocode:'+name)); } catch { return null; } },
          set(name, coords){ try { localStorage.setItem('geocode:'+name, JSON.stringify(coords)); } catch {} }
        };

        async function geocodeCity(name){
          if (!name) return null;
          const cached = cityCache.get(name);
          if (cached) return cached;
          try {
            const res = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(name)}&limit=1`);
            const data = await res.json();
            if (data && data[0]) {
              const coords = { lat: parseFloat(data[0].lat), lng: parseFloat(data[0].lon) };
              cityCache.set(name, coords);
              return coords;
            }
          } catch (e) {}
          return null;
        }

        (async function plotTasks(){
          const locations = [...new Set(tasksData.map(t => t.location))];
          const locationToCoords = {};
          for (const loc of locations) {
            const coords = await geocodeCity(loc);
            if (coords) locationToCoords[loc] = coords;
            await new Promise(r => setTimeout(r, 400));
          }

          const markers = {};

          tasksData.forEach(t => {
            const baseCoords = locationToCoords[t.location];
            if (!baseCoords) return;

            const jitter = 0.008;
            const lng = baseCoords.lng + (Math.random() - 0.5) * jitter;
            const lat = baseCoords.lat + (Math.random() - 0.5) * jitter;

            const el = document.createElement('div');
            el.className = 'map-marker-container group';
            el.style.cursor = 'pointer';

            const mColor = t.is_my_task ? '#9333EA' : '#2563EB';
            const pColor = t.is_my_task ? '#9333EA22' : '#2563EB22';

            el.innerHTML = `
                <div class="relative flex items-center justify-center">
                    <div class="absolute w-8 h-8 rounded-full animate-ping opacity-75" style="background-color: ${pColor}"></div>
                    <div class="w-4 h-4 rounded-full border-2 border-white shadow-lg relative z-10 transition-all duration-300" 
                         style="background-color: ${mColor}"></div>
                </div>
            `;

            const loginUrl = urls ? `${urls.login}?returnUrl=` + encodeURIComponent('/tasks/' + t.id) : '/login';
            const detailsUrl = t.is_my_task ? '/my-tasks#task-'+t.id : '/tasks/'+t.id;
            const finalUrl = (window.isAuthenticated || window.Auth) ? detailsUrl : loginUrl;
            
            const badge = t.is_my_task && translations ? `<span class="px-1.5 py-0.5 bg-violet-100 text-violet-700 text-[9px] font-bold uppercase rounded-md">${translations.myTask}</span>` : '';
            const btnText = translations ? translations.viewDetails : 'View Details';

            const popupHTML = `
                <div class="p-3 min-w-[180px]">
                    <div class="flex items-center justify-between gap-2 mb-1">
                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">${t.location}</div>
                        ${badge}
                    </div>
                    <div class="font-bold text-sm text-gray-900 mb-1 leading-tight">${t.title}</div>
                    <div class="text-blue-600 font-extrabold text-sm mb-3">€${t.price.toLocaleString()}</div>
                    <a href="${finalUrl}" class="btn block w-full py-2 text-center ${t.is_my_task ? 'bg-violet-600 hover:bg-violet-700' : 'bg-blue-600 hover:bg-blue-700'} text-white text-[11px] font-bold rounded-lg transition-colors no-underline">${btnText}</a>
                </div>
            `;

            const marker = new maplibregl.Marker({ element: el })
              .setLngLat([lng, lat])
              .setPopup(new maplibregl.Popup({ 
                  offset: 15, 
                  closeButton: false,
                  className: 'custom-task-popup'
              }).setHTML(popupHTML))
              .addTo(map);
            
            markers[t.id] = marker;

            // Hover Effect: Card -> Marker
            const card = document.getElementById(`task-card-${t.id}`);
            if(card) {
                const hColor = t.is_my_task ? '#3730A3' : '#1D4ED8'; 
                card.addEventListener('mouseenter', () => {
                    const markerEl = el.querySelector('.z-10');
                    const pingEl = el.querySelector('.absolute');
                    if(pingEl) pingEl.classList.add('scale-150');
                    if(markerEl) {
                        markerEl.classList.add('scale-150');
                        markerEl.style.backgroundColor = hColor;
                    }
                });
                card.addEventListener('mouseleave', () => {
                    const markerEl = el.querySelector('.z-10');
                    const pingEl = el.querySelector('.absolute');
                    if(pingEl) pingEl.classList.remove('scale-150');
                    if(markerEl) {
                        markerEl.classList.remove('scale-150');
                        markerEl.style.backgroundColor = mColor;
                    }
                });
            }
          });
        })();
    }

    // 4. Dropdowns (Price/Type)
    function setupDropdown(btnId, menuId) {
        const btn = document.getElementById(btnId);
        const menu = document.getElementById(menuId);
        if(!btn || !menu) return;
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            const isHidden = menu.classList.contains('hidden');
            document.querySelectorAll('[id$="-menu"]').forEach(el => el.classList.add('hidden'));
            if(isHidden) menu.classList.remove('hidden');
        });
        menu.addEventListener('click', (e) => e.stopPropagation());
        document.addEventListener('click', () => menu.classList.add('hidden'));
    }
    setupDropdown('price-btn', 'price-menu');
    setupDropdown('type-btn', 'type-menu');

    document.getElementById('category-filter')?.addEventListener('change', (e) => {
        const jobFilter = document.getElementById('job-filter');
        if (jobFilter) jobFilter.value = ''; 
        document.getElementById('filters-form').submit();
    });

    ['sort-filter', 'job-filter'].forEach(id => {
        document.getElementById(id)?.addEventListener('change', (e) => {
            document.getElementById('filters-form').submit();
        });
    });

    // 5. Dual-Thumb Price Range Slider
    (function initPriceSlider() {
        const minEl = document.getElementById('price-min');
        const maxEl = document.getElementById('price-max');
        const track = document.getElementById('price-track');
        const priceDisplay = document.getElementById('price-display');
        const priceText = document.getElementById('price-text');
        const priceBtn = document.getElementById('price-btn');
        const priceMenu = document.getElementById('price-menu');
        if (!minEl || !maxEl) return;

        const RANGE_MIN = 5;
        const RANGE_MAX = 5000;
        const GAP = 10;

        function update(source) {
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
            if(track) {
                track.style.left = pMin + '%';
                track.style.right = (100 - pMax) + '%';
                track.style.width = 'auto';
            }

            if (priceDisplay) {
                priceDisplay.textContent = `€${minVal.toLocaleString()} - €${maxVal.toLocaleString()}`;
            }
        }

        minEl.addEventListener('input', () => update('min'));
        maxEl.addEventListener('input', () => update('max'));
        update('min');

        document.getElementById('price-apply')?.addEventListener('click', () => {
            const minVal = parseInt(minEl.value);
            const maxVal = parseInt(maxEl.value);
            if(priceText) {
                priceText.textContent = `€${minVal.toLocaleString()} - €${maxVal.toLocaleString()}`;
                priceText.classList.add('text-blue-600');
            }
            if(priceBtn) priceBtn.classList.add('border-blue-400');
            document.getElementById('filters-form').submit();
        });

        document.getElementById('price-cancel')?.addEventListener('click', () => {
            if (filters) {
                minEl.value = filters.min_price || 5;
                maxEl.value = filters.max_price || 5000;
            } else {
                minEl.value = RANGE_MIN;
                maxEl.value = RANGE_MAX;
            }
            update('min');
            priceMenu.classList.add('hidden');
        });
    })();

    // 6. City Search
    const typeCitySearch = document.getElementById('type-city-search');
    const typeCityDropdown = document.getElementById('type-city-dropdown');
    const hiddenCity = document.getElementById('city-search-hidden');
    let searchTimeout;

    if(typeCitySearch) {
        typeCitySearch.addEventListener('input', (e) => {
            clearTimeout(searchTimeout);
            const q = e.target.value;
            if(q.length < 2) { typeCityDropdown.classList.add('hidden'); return; }
            
            searchTimeout = setTimeout(async () => {
                try {
                    const res = await fetch(`/api/cities?q=${q}`);
                    const cities = await res.json();
                    typeCityDropdown.innerHTML = '';
                    if(cities.length) {
                        typeCityDropdown.classList.remove('hidden');
                        cities.slice(0,8).forEach(c => {
                            const div = document.createElement('div');
                            div.className = 'px-3 py-2 text-sm hover:bg-blue-50 cursor-pointer text-gray-700';
                            div.textContent = c.name;
                            div.onclick = () => {
                                hiddenCity.value = c.name;
                                document.getElementById('filters-form').submit();
                            };
                            typeCityDropdown.appendChild(div);
                        });
                    }
                } catch(err){}
            }, 300);
        });
    }

    // 7. Modal Interaction Logic
    const modal = document.getElementById('profile-steps-modal');
    if (modal) {
        const openButtons = document.querySelectorAll('.js-open-offer-requirements');
        const closeBtn = document.getElementById('profile-steps-close');

        function showModal() { 
            modal.classList.remove('hidden'); 
            refreshIcons(); 
        }
        function hideModal() { 
            modal.classList.add('hidden'); 
        }

        openButtons.forEach(btn => btn.addEventListener('click', (e) => { 
            e.preventDefault(); 
            showModal(); 
        }));
        
        if (closeBtn) closeBtn.addEventListener('click', hideModal);
        
        modal.addEventListener('click', (e) => { 
            if (e.target === modal) hideModal(); 
        });
    }

    // 8. Search Input Enter Key
    const searchInput = document.getElementById('search-q');
    if (searchInput) {
        searchInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchInput.form.submit();
            }
        });
    }

    // 9. Mobile Filters Logic
    (function initMobileFilters() {
        const trigger = document.getElementById('mobile-filter-trigger');
        const mobModal = document.getElementById('mobile-filters-modal');
        const closeBtn = document.getElementById('close-mobile-filters');
        const cancelBtn = document.getElementById('clear-mobile-filters');
        const applyBtn = document.getElementById('apply-mobile-filters');
        const form = document.getElementById('mobile-filters-form');

        if (!trigger || !mobModal) return;

        trigger.addEventListener('click', () => {
            mobModal.classList.remove('hidden');
            mobModal.classList.add('flex');
            document.body.style.overflow = 'hidden'; 
            refreshIcons();
        });

        const hideMobModal = () => {
            mobModal.classList.add('hidden');
            mobModal.classList.remove('flex');
            document.body.style.overflow = '';
        };

        [closeBtn, cancelBtn].forEach(btn => btn?.addEventListener('click', hideMobModal));

        const typeTabs = document.querySelectorAll('.mobile-type-tab');
        const typeHidden = document.getElementById('mobile-type-hidden');
        typeTabs.forEach(tab => {
            tab.addEventListener('click', () => {
                typeTabs.forEach(t => {
                    t.classList.remove('bg-white', 'shadow-sm', 'text-blue-600', 'bg-blue-600', 'text-white');
                    t.classList.add('text-gray-500');
                });
                
                const val = tab.dataset.value;
                typeHidden.value = val;
                
                if (val === 'remote') {
                    tab.classList.add('bg-blue-600', 'text-white', 'shadow-sm');
                    tab.classList.remove('text-gray-500');
                } else {
                    tab.classList.add('bg-white', 'shadow-sm', 'text-blue-600');
                    tab.classList.remove('text-gray-500');
                }
            });
        });

        const minEl = document.getElementById('mobile-price-min');
        const maxEl = document.getElementById('mobile-price-max');
        const track = document.getElementById('mobile-price-track');
        const priceText = document.getElementById('mobile-price-text');
        
        if (minEl && maxEl && track) {
            const RANGE_MIN = 5;
            const RANGE_MAX = 5000;
            const GAP = 10;

            function updatePrice(source) {
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
                
                if (priceText) priceText.textContent = `€${minVal.toLocaleString()} - €${maxVal.toLocaleString()}`;
            }

            minEl.addEventListener('input', () => updatePrice('min'));
            maxEl.addEventListener('input', () => updatePrice('max'));
            updatePrice('min');
        }

        const cityInput = document.getElementById('mobile-city-search-input');
        const cityResults = document.getElementById('mobile-city-results');
        const cityHidden = document.getElementById('mobile-city-hidden');
        let mobileSearchTimeout;

        if (cityInput) {
            cityInput.addEventListener('input', (e) => {
                clearTimeout(mobileSearchTimeout);
                const q = e.target.value;
                if (q.length < 2) { cityResults.classList.add('hidden'); return; }

                mobileSearchTimeout = setTimeout(async () => {
                    try {
                        const res = await fetch(`/api/cities?q=${q}`);
                        const cities = await res.json();
                        cityResults.innerHTML = '';
                        if (cities.length) {
                            cityResults.classList.remove('hidden');
                            cities.slice(0, 8).forEach(c => {
                                const div = document.createElement('div');
                                div.className = 'px-5 py-4 text-[14px] font-medium hover:bg-blue-50 cursor-pointer text-gray-700 border-b border-gray-50 last:border-0';
                                div.innerHTML = `<i data-feather="map-pin" class="w-3.5 h-3.5 inline mr-2 text-gray-400"></i> ${c.name}`;
                                div.onclick = () => {
                                    cityInput.value = c.name;
                                    cityHidden.value = c.name;
                                    cityResults.classList.add('hidden');
                                    refreshIcons();
                                };
                                cityResults.appendChild(div);
                            });
                            refreshIcons();
                        } else {
                            cityResults.classList.add('hidden');
                        }
                    } catch (err) {}
                }, 300);
            });
        }

        if(applyBtn && form) applyBtn.addEventListener('click', () => form.submit());
    })();

    // 10. Remote Info Toggle
    window.toggleRemoteInfo = function() {
        const el = document.getElementById('remote-info-pop');
        if(!el) return;
        const isHidden = el.classList.contains('hidden');
        el.classList.toggle('hidden');
        if (isHidden) {
            refreshIcons();
            setTimeout(() => el.classList.add('hidden'), 5000);
        }
    };
});
