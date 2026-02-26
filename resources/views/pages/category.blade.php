@extends('layout')

@section('content')
<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { 
        -ms-overflow-style: none; 
        scrollbar-width: none; 
        scroll-behavior: smooth;
    }
    @media (max-width: 767px) {
        .scroll-hint {
            animation: scroll-hint 2s ease-in-out infinite;
        }
        @keyframes scroll-hint {
            0%, 100% { transform: translateX(0); opacity: 0.5; }
            50% { transform: translateX(5px); opacity: 1; }
        }
    }
</style>
<section class="bg-white pt-8 pb-12">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex flex-col md:flex-row gap-10 items-start">

            <!-- Left: Categories list -->
            <aside class="w-full md:w-1/5 mb-3 md:mb-0">
                <h3 class="text-xl md:text-2xl font-bold text-blue-900 text-left md:mt-2 px-1 md:px-0">{{ __('category_page.sidebar_title') }}</h3>

                <div class="mt-2 md:mt-10 md:pr-4 relative z-10">
                    <!-- Vertical Line (Desktop only) -->
                    <div class="hidden md:block absolute top-3 -right-6 bottom-3 w-px bg-blue-200"></div>
                    <!-- Horizontal Line (Desktop only) -->
                    <div class="hidden md:block w-[110%] border-t-2 border-blue-200 mb-2"></div>

                    <!-- Mobile Scroll Indicator -->
                    <div id="mobile-scroll-hint" class="md:hidden flex items-center justify-between mb-2 px-1 transition-opacity duration-500">
                        <span class="text-[10px] uppercase tracking-wider text-blue-400 font-bold">{{ __('category_page.scroll_hint') ?? 'Scroll for more' }}</span>
                        <div class="flex space-x-1 scroll-hint">
                            <div class="w-1.5 h-1.5 rounded-full bg-blue-400"></div>
                            <div class="w-1.5 h-1.5 rounded-full bg-blue-300"></div>
                            <div class="w-1.5 h-1.5 rounded-full bg-blue-200"></div>
                        </div>
                    </div>

                    <div class="relative">
                        <ul id="categories-list" class="flex md:flex-col overflow-x-auto md:overflow-x-visible space-x-2 md:space-x-0 md:space-y-1 pb-2 md:pb-0 px-1 md:px-0 no-scrollbar relative z-10">
                            @php
                                $firstCategory = ($categories ?? collect())->first();
                                $fallbackImage = 'https://via.placeholder.com/1200x600?text=Category';
                            @endphp
                            @foreach(($categories ?? []) as $category)
                                <li class="flex-shrink-0 md:w-full">
                                    <button type="button" 
                                        class="w-max md:w-full text-left text-sm sm:text-base px-4 md:px-3 py-2 rounded-lg transition-colors duration-200 whitespace-nowrap
                                               {{ $loop->first ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-blue-600' }}"
                                        data-id="{{ $category->id }}"
                                        data-name="{{ $category->name }}"
                                        data-desc="{{ $category->description ?? '' }}"
                                        data-image="{{ $category->image_url ?? $fallbackImage }}">
                                        {{ $category->name }}
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                        <!-- Right Fade Mask for Mobile -->
                        <div id="mobile-scroll-fade" class="md:hidden absolute top-0 right-0 bottom-4 w-12 bg-gradient-to-l from-white to-transparent pointer-events-none z-20 transition-opacity duration-500"></div>
                    </div>
                </div>
            </aside>

            <!-- Mobile Separator Line -->
            <div class="md:hidden w-full border-t border-gray-200 mb-2 -mt-1"></div>

            <!-- Right: Category detail -->
            <div id="category-detail" class="w-full flex flex-col h-full px-2 md:px-4">

                <!-- Title & Image Section -->
                <div class="text-center mb-6 md:mb-8">
                    <h1 id="cat-title" class="text-2xl sm:text-4xl font-bold text-gray-900 mb-4 md:mb-10">
                        {{ $firstCategory->name ?? __('category_page.fallback_title') }}
                    </h1>

                    <!-- Increased height by 30px -->
                    <div class="w-full rounded-2xl overflow-hidden bg-gray-100 shadow-sm h-[280px] sm:h-[430px] relative">
                        <img id="cat-image" src="{{ $firstCategory->image_url ?? $fallbackImage }}" alt="Category" class="w-full h-full object-cover" loading="lazy">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-4 sm:p-6 text-white text-center">
                            <p id="cat-desc" class="text-sm sm:text-lg font-medium leading-relaxed max-w-3xl mx-auto drop-shadow-lg px-2">
                                {{ $firstCategory->description ?? __('category_page.fallback_desc') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- INTERACTIVE SERVICES SECTION -->
                <div class="bg-white rounded-xl">

                    <!-- 1. The Toggle (Finder vs Tasker) -->
                    <div class="flex flex-col items-center justify-center mb-4 md:mb-8">
                        <div class="inline-flex bg-gray-100 p-1.5 rounded-full relative shadow-inner">

                            <!-- Finder (default active) with blue selected-category style -->
                            <button id="btn-finder" onclick="switchRole('finder')" 
                                class="relative z-10 px-4 sm:px-8 py-2.5 rounded-full text-sm font-bold transition-all duration-300 
                                       bg-blue-50 text-blue-700 shadow-sm">
                                {{ __('category_page.finder_tab') }}
                            </button>

                            <!-- Tasker – will turn blue when selected -->
                            <button id="btn-tasker" onclick="switchRole('tasker')" 
                                class="relative z-10 px-4 sm:px-8 py-2.5 rounded-full text-sm font-bold transition-all duration-300 text-gray-500 hover:text-gray-700">
                                {{ __('category_page.tasker_tab') }}
                            </button>
                        </div>
                        <p id="role-helper-text" class="text-gray-500 text-sm mt-3 animate-fade-in">
                            {{ __('category_page.finder_helper') }}
                        </p>
                    </div>

                    <!-- 2. The Services Grid -->
                    <div id="services-container" class="finder-mode">
                        <div id="jobs-list" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3">
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

@php
    $jobsPayload = collect($jobsByCategory ?? [])->mapWithKeys(function($jobs, $catId) {
        return [
            $catId => collect($jobs)->map(function($job) {
                return [
                    'id' => $job->id,
                    'title' => (string) ($job->name ?? ''),
                ];
            }),
        ];
    });
@endphp

<script>
    const jobsData = @json($jobsPayload ?? []);
    const urls = {
        search: "{{ route('tasks') }}",
        postTask: "{{ url('post-task') }}"
    };

    let state = {
        categoryId: "{{ $firstCategory->id ?? '' }}",
        role: 'finder'
    };

    const elements = {
        list: document.getElementById('categories-list'),
        img: document.getElementById('cat-image'),
        title: document.getElementById('cat-title'),
        desc: document.getElementById('cat-desc'),
        jobsList: document.getElementById('jobs-list'),
        container: document.getElementById('services-container'),
        btnFinder: document.getElementById('btn-finder'),
        btnTasker: document.getElementById('btn-tasker'),
        helperText: document.getElementById('role-helper-text'),
        scrollHint: document.getElementById('mobile-scroll-hint'),
        fadeMask: document.getElementById('mobile-scroll-fade')
    };

    // Hide scroll hints on first interact
    elements.list.addEventListener('scroll', () => {
        if (elements.scrollHint) elements.scrollHint.style.opacity = '0';
        if (elements.fadeMask) elements.fadeMask.style.opacity = '0';
    }, { once: true });

    function switchRole(role) {
        if (role === 'tasker' && !window.isAuthenticated) {
            window.location.href = "{{ route('login') }}";
            return;
        }

        state.role = role;

        if(role === 'finder') {
            elements.btnFinder.className =
                "relative z-10 px-4 sm:px-8 py-2.5 rounded-full text-sm font-bold transition-all duration-300 bg-blue-50 text-blue-700 shadow-sm";
            elements.btnTasker.className =
                "relative z-10 px-4 sm:px-8 py-2.5 rounded-full text-sm font-bold transition-all duration-300 text-gray-500 hover:text-gray-700";

            elements.helperText.textContent = "{{ __('category_page.finder_helper') }}";
        } else {
            elements.btnFinder.className =
                "relative z-10 px-4 sm:px-8 py-2.5 rounded-full text-sm font-bold transition-all duration-300 text-gray-500 hover:text-gray-700";
            elements.btnTasker.className =
                "relative z-10 px-4 sm:px-8 py-2.5 rounded-full text-sm font-bold transition-all duration-300 bg-blue-50 text-blue-700 shadow-sm";

            elements.helperText.textContent = "{{ __('category_page.tasker_helper') }}";
        }

        renderJobs(state.categoryId);
    }

    function renderJobs(categoryId) {
        elements.jobsList.innerHTML = '';

        if (!categoryId || !jobsData[categoryId] || !jobsData[categoryId].length) {
            elements.jobsList.innerHTML = `
                <div class="col-span-full py-8 text-center text-gray-400">
                    <p>{{ __('category_page.no_services') }}</p>
                </div>`;
            return;
        }

        jobsData[categoryId].forEach(job => {
            const a = document.createElement('a');

            if (state.role === 'finder') {
                a.href = `${urls.search}?category=${categoryId}&job=${job.id}`;
            } else {
                a.href = `${urls.postTask}?category=${categoryId}&job=${job.id}`;
            }

            let classes =
                "group flex items-center justify-between px-4 py-3 rounded-lg border transition-all duration-200 text-sm font-medium ";

            // Blue theme for both roles now
            classes += "bg-white border-gray-200 text-gray-700 hover:border-blue-400 hover:shadow-md hover:-translate-y-0.5 hover:text-blue-600";
            const iconHtml = `<span class="text-gray-300 group-hover:text-blue-500 transition-colors ml-2">
                ${state.role === 'finder' ? "→" : "+"}
            </span>`;

            a.className = classes;
            a.innerHTML = `<span>${job.title}</span>${iconHtml}`;

            elements.jobsList.appendChild(a);
        });
    }

    elements.list.addEventListener('click', function(e){
        const btn = e.target.closest('button[data-id]');
        if (!btn) return;

        const allBtns = elements.list.querySelectorAll('button');
        allBtns.forEach(b =>
            b.className =
                "w-max md:w-full text-left text-sm sm:text-base px-4 md:px-3 py-2 rounded-lg transition-colors duration-200 text-gray-600 hover:bg-gray-50 hover:text-blue-600 whitespace-nowrap"
        );

        btn.className =
            "w-max md:w-full text-left text-sm sm:text-base px-4 md:px-3 py-2 rounded-lg transition-colors duration-200 bg-blue-50 text-blue-700 font-semibold whitespace-nowrap";

        state.categoryId = btn.getAttribute('data-id');
        elements.title.textContent = btn.getAttribute('data-name');
        elements.desc.textContent = btn.getAttribute('data-desc');
        elements.img.src = btn.getAttribute('data-image');

        renderJobs(state.categoryId);
    });

    renderJobs(state.categoryId);
</script>
@endsection

 