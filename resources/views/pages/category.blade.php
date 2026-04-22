@extends('layout')

@section('title', __('category_page.title') ?? 'Categories')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/category.css') }}">
@endpush

@section('content')
    <section class="bg-white dark:bg-slate-900 pt-6 pb-8 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex flex-col md:flex-row gap-10 items-start">

                {{-- Sidebar: Category Navigation --}}
                <aside class="w-full md:w-1/5 mb-3 md:mb-0">
                    <h3
                        class="text-xl md:text-2xl font-black text-blue-900 dark:text-blue-400 text-left md:mt-2 px-1 md:px-0 tracking-tight">
                        {{ __('category_page.sidebar_title') }}
                    </h3>

                    <div class="mt-2 md:mt-6 md:pr-4 relative z-10">
                        {{-- Vertical Separator (Desktop) --}}
                        <div class="hidden md:block absolute top-3 -right-6 bottom-3 w-px bg-blue-100 dark:bg-blue-900/30">
                        </div>

                        {{-- Mobile Scroll Indicator --}}
                        <div id="mobile-scroll-hint"
                            class="md:hidden flex items-center justify-between mb-2 px-1 transition-opacity duration-500">
                            <span
                                class="text-[10px] uppercase font-black tracking-widest text-blue-400/70 dark:text-blue-500/50">
                                {{ __('category_page.scroll_hint') ?? 'Scroll for more' }}
                            </span>
                            <div class="flex space-x-1 scroll-hint">
                                <div class="w-1.5 h-1.5 rounded-full bg-blue-400"></div>
                                <div class="w-1.5 h-1.5 rounded-full bg-blue-300"></div>
                                <div class="w-1.5 h-1.5 rounded-full bg-blue-200"></div>
                            </div>
                        </div>

                        <div class="relative">
                            <ul id="categories-list"
                                class="flex md:flex-col overflow-x-auto md:overflow-x-visible space-x-2 md:space-x-0 md:space-y-0 pb-2 md:pb-0 px-1 md:px-0 no-scrollbar relative z-10">
                                @php
                                    $firstCategory = ($categories ?? collect())->first();
                                    $fallbackImage = 'https://via.placeholder.com/1200x600?text=Category';
                                @endphp
                                @foreach($categories ?? [] as $category)
                                    @php /** @var \App\Models\Category $category */ @endphp
                                    <li class="flex-shrink-0 md:w-full">
                                        <button type="button" class="category-btn {{ $loop->first ? 'is-active' : '' }}"
                                            data-id="{{ $category->id }}" data-name="{{ $category->name }}"
                                            data-name-translated="{{ __('categories.' . $category->name) }}"
                                            data-desc-translated="{{ __('categories.' . $category->name . '_desc') }}"
                                            data-image="{{ $category->image_url ?? $fallbackImage }}">
                                            {{ __('categories.' . $category->name) }}
                                        </button>
                                    </li>
                                @endforeach
                            </ul>
                            {{-- Right Fade Mask (Mobile) --}}
                            <div id="mobile-scroll-fade"
                                class="md:hidden absolute top-0 right-0 bottom-4 w-12 bg-gradient-to-l from-white dark:from-slate-900 to-transparent pointer-events-none z-20 transition-opacity duration-500">
                            </div>
                        </div>
                    </div>
                </aside>

                {{-- Mobile Separator --}}
                <div class="md:hidden w-full border-t border-gray-100 dark:border-slate-800 mb-6 -mt-1"></div>

                {{-- Main Content: Category Detail --}}
                <div id="category-detail" class="w-full flex flex-col h-full px-2 md:px-4">

                    {{-- Title & Hero Image --}}
                    <div class="text-center mb-6 md:mb-8">
                        <h1 id="cat-title"
                            class="text-3xl sm:text-4xl font-black text-gray-900 dark:text-white mb-4 md:mb-6 tracking-tight">
                            {{ $firstCategory ? __('categories.' . $firstCategory->name) : __('category_page.fallback_title') }}
                        </h1>

                        <div
                            class="w-full rounded-[2rem] overflow-hidden bg-gray-100 dark:bg-slate-800 shadow-xl h-[250px] sm:h-[420px] relative group">
                            <img id="cat-image" src="{{ $firstCategory->image_url ?? $fallbackImage }}" alt="Category"
                                class="w-full h-full object-cover transition-all duration-700 group-hover:scale-105"
                                loading="lazy">

                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>

                            <div class="absolute bottom-0 left-0 right-0 p-8 sm:p-12 text-white text-center">
                                <p id="cat-desc"
                                    class="text-base sm:text-xl font-medium leading-relaxed max-w-3xl mx-auto drop-shadow-2xl px-4 animate-fade-in">
                                    {{ $firstCategory ? __('categories.' . $firstCategory->name . '_desc') : __('category_page.fallback_desc') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Interactive Services Section --}}
                    <div class="rounded-3xl">

                        {{-- Role Toggle (Finder / Tasker) --}}
                        <div class="flex flex-col items-center justify-center mb-6 md:mb-8">
                            <div
                                class="inline-flex bg-gray-100 dark:bg-slate-800/80 p-1.5 rounded-full relative shadow-inner">
                                <button id="btn-finder" onclick="switchRole('finder')" class="relative z-10 px-6 sm:px-8 py-2.5 rounded-full text-sm font-black transition-all duration-300
                                           bg-blue-50 text-blue-700 shadow-sm dark:bg-blue-900/30 dark:text-blue-300">
                                    {{ __('category_page.finder_tab') }}
                                </button>

                                <button id="btn-tasker" onclick="switchRole('tasker')"
                                    class="relative z-10 px-6 sm:px-8 py-2.5 rounded-full text-sm font-bold transition-all duration-300 text-gray-500 hover:text-gray-700 dark:text-slate-400 dark:hover:text-slate-200">
                                    {{ __('category_page.tasker_tab') }}
                                </button>
                            </div>
                            <p id="role-helper-text"
                                class="text-gray-500 dark:text-slate-400 text-sm font-medium mt-3 animate-fade-in">
                                {{ __('category_page.finder_helper') }}
                            </p>
                        </div>

                        {{-- Services Grid --}}
                        <div id="services-container" class="finder-mode px-2">
                            <div id="jobs-list"
                                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 sm:gap-4">
                                {{-- Populated dynamically via JS --}}
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    @php
        $jobsPayload = collect($jobsByCategory ?? [])->mapWithKeys(function ($jobs, $catId) {
            return [
                $catId => collect($jobs)->map(function ($job) {
                    return [
                        'id' => $job->id,
                        'title' => (string) (__('jobs.' . $job->name) ?? $job->name),
                    ];
                }),
            ];
        });
    @endphp

    @push('scripts')
        <script>
            window.CATEGORY_CONFIG = {
                jobsData: @json($jobsPayload ?? []),
                urls: {
                    search: "{{ route('tasks') }}",
                    postTask: "{{ url('post-task') }}",
                    login: "{{ route('login') }}"
                },
                translations: {
                    finderHelper: "{{ __('category_page.finder_helper') }}",
                    taskerHelper: "{{ __('category_page.tasker_helper') }}",
                    noServices: "{{ __('category_page.no_services') }}"
                },
                isAuthenticated: {{ auth()->check() ? 'true' : 'false' }},
                firstCategoryId: "{{ $firstCategory->id ?? '' }}"
            };
        </script>
        <script src="{{ asset('js/pages/category.js') }}"></script>
    @endpush
@endsection