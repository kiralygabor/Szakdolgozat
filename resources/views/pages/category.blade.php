@extends('layout')

@section('content')
<section class="bg-white pt-8 pb-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-[260px_minmax(0,1fr)] gap-10 md:gap-14 items-start">

            <!-- Left: Categories list -->
            <aside class="relative md:pr-6 md:-translate-x-12 md:transform">
                <h3 class="text-2xl font-bold text-blue-900 text-left mt-2">Categories</h3>

                <div class="mt-10 pr-4 relative z-10">
                    <!-- Vertical Line -->
                    <div class="hidden md:block absolute top-3 -right-6 bottom-3 w-px bg-blue-200"></div>
                    <!-- Horizontal Line -->
                    <div class="w-[110%] border-t-2 border-blue-200 mb-2"></div>

                    <ul id="categories-list" class="space-y-1">
                        @php
                            $firstCategory = ($categories ?? collect())->first();
                            $fallbackImage = 'https://via.placeholder.com/1200x600?text=Category';
                        @endphp
                        @foreach(($categories ?? []) as $category)
                            <li>
                                <button type="button" 
                                    class="w-full text-left text-sm sm:text-base px-3 py-2 rounded-lg transition-colors duration-200
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
                </div>
            </aside>

            <!-- Right: Category detail -->
            <div id="category-detail" class="w-full flex flex-col h-full px-1 md:px-4">

                <!-- Title & Image Section -->
                <div class="text-center mb-8">
                    <h1 id="cat-title" class="text-3xl sm:text-4xl font-bold text-gray-900 mb-10">
                        {{ $firstCategory->name ?? 'Select a Category' }}
                    </h1>

                    <!-- Increased height by 30px -->
                    <div class="w-full rounded-2xl overflow-hidden bg-gray-100 shadow-sm h-[330px] sm:h-[430px] relative">
                        <img id="cat-image" src="{{ $firstCategory->image_url ?? $fallbackImage }}" alt="Category" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
                            <p id="cat-desc" class="text-base sm:text-lg font-medium leading-relaxed max-w-3xl mx-auto drop-shadow-md">
                                {{ $firstCategory->description ?? 'Find the best services or offer your skills here.' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- INTERACTIVE SERVICES SECTION -->
                <div class="bg-white rounded-xl">
                    
                    <!-- 1. The Toggle (Finder vs Tasker) -->
                    <div class="flex flex-col items-center justify-center mb-8">
                        <div class="inline-flex bg-gray-100 p-1.5 rounded-full relative shadow-inner">

                            <!-- Finder (default active) with blue selected-category style -->
                            <button id="btn-finder" onclick="switchRole('finder')" 
                                class="relative z-10 px-8 py-2.5 rounded-full text-sm font-bold transition-all duration-300 
                                       bg-blue-50 text-blue-700 shadow-sm">
                                I'm a Finder
                            </button>

                            <!-- Tasker – will turn blue when selected -->
                            <button id="btn-tasker" onclick="switchRole('tasker')" 
                                class="relative z-10 px-8 py-2.5 rounded-full text-sm font-bold transition-all duration-300 text-gray-500 hover:text-gray-700">
                                I'm a Tasker
                            </button>
                        </div>
                        <p id="role-helper-text" class="text-gray-500 text-sm mt-3 animate-fade-in">
                            Select a service below to find professionals.
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
        search: "{{ url('search') }}",
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
        helperText: document.getElementById('role-helper-text')
    };

    function switchRole(role) {
        if (role === 'tasker' && !window.isAuthenticated) {
            window.location.href = "{{ route('login') }}";
            return;
        }

        state.role = role;

        if(role === 'finder') {
            elements.btnFinder.className =
                "relative z-10 px-8 py-2.5 rounded-full text-sm font-bold transition-all duration-300 bg-blue-50 text-blue-700 shadow-sm";
            elements.btnTasker.className =
                "relative z-10 px-8 py-2.5 rounded-full text-sm font-bold transition-all duration-300 text-gray-500 hover:text-gray-700";

            elements.helperText.textContent = "Select a service below to find professionals.";
        } else {
            elements.btnFinder.className =
                "relative z-10 px-8 py-2.5 rounded-full text-sm font-bold transition-all duration-300 text-gray-500 hover:text-gray-700";
            elements.btnTasker.className =
                "relative z-10 px-8 py-2.5 rounded-full text-sm font-bold transition-all duration-300 bg-blue-50 text-blue-700 shadow-sm";

            elements.helperText.textContent = "Select your skill below to post a task.";
        }

        renderJobs(state.categoryId);
    }

    function renderJobs(categoryId) {
        elements.jobsList.innerHTML = '';

        if (!categoryId || !jobsData[categoryId] || !jobsData[categoryId].length) {
            elements.jobsList.innerHTML = `
                <div class="col-span-full py-8 text-center text-gray-400">
                    <p>No specific services listed yet.</p>
                </div>`;
            return;
        }

        jobsData[categoryId].forEach(job => {
            const a = document.createElement('a');

            if (state.role === 'finder') {
                a.href = `${urls.search}?category=${categoryId}&service=${job.id}`;
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
                "w-full text-left text-sm sm:text-base px-3 py-2 rounded-lg transition-colors duration-200 text-gray-600 hover:bg-gray-50 hover:text-blue-600"
        );

        btn.className =
            "w-full text-left text-sm sm:text-base px-3 py-2 rounded-lg transition-colors duration-200 bg-blue-50 text-blue-700 font-semibold";

        state.categoryId = btn.getAttribute('data-id');
        elements.title.textContent = btn.getAttribute('data-name');
        elements.desc.textContent = btn.getAttribute('data-desc');
        elements.img.src = btn.getAttribute('data-image');

        renderJobs(state.categoryId);
    });

    renderJobs(state.categoryId);
</script>
@endsection
