@extends('layout')

@section('title', __('mytasks.title'))

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/my-tasks.css') }}">
@endpush

@section('content')

<section class="min-h-screen">
    <div class="main-wrapper">
        
        @php
            $tasksCollection = $tasks ?? collect([]);
            $hasTasks = $tasksCollection->count() > 0;
            
            // Backed Enum support for status comparisons
            $getStatusValue = fn($status) => $status->value ?? $status;

            $activeTask = $focusedTask ?? ($hasTasks ? $tasksCollection->first() : null);
            
            if ($activeTask) {
                $otherTasks = $tasksCollection->where('id', '!=', $activeTask->id)->values();
                $offerCount = $activeTask->offers->count(); 
                $hasOffers = $offerCount > 0;
                $viewCount = $activeTask->views_count ?? $activeTask->views ?? 0;
                $activeStatus = $getStatusValue($activeTask->status);
                $showOffers = $activeStatus === 'open' && in_array(($viewMode ?? 'posted'), ['posted', 'direct']);
            }
        @endphp

        {{-- Data for JS --}}
        <script>
            window.TASK_DATA = {
                allCategories: @json($allCategories ?? []),
                jobTranslations: @json(__('jobs'))
            };
        </script>

        {{-- MAIN TABS --}}
        <div class="flex justify-center mb-8">
            <div class="modern-tabs-wrapper">
                <a href="{{ route('my-tasks', ['view' => 'posted']) }}" 
                   class="modern-tab {{ ($viewMode ?? 'posted') === 'posted' ? 'active' : '' }}">
                   {{ __('mytasks.tabs.posted') }}
                </a>
                <a href="{{ route('my-tasks', ['view' => 'direct']) }}" 
                   class="modern-tab {{ ($viewMode ?? 'posted') === 'direct' ? 'active' : '' }}">
                   {{ __('mytasks.tabs.direct') }}
                </a>
                <a href="{{ route('my-tasks', ['view' => 'applied']) }}" 
                   class="modern-tab {{ ($viewMode ?? 'posted') === 'applied' ? 'active' : '' }}">
                   {{ __('mytasks.tabs.applied') }}
                </a>
            </div>
        </div>

        {{-- CONTROLS BAR --}}
        <div class="controls-bar">
            <form method="GET" action="{{ route('my-tasks') }}" class="modern-search-wrapper hidden md:block">
                <i data-feather="search" class="search-icon" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #94a3b8; width: 18px; height: 18px;"></i>
                <input name="q" value="{{ $filters['q'] ?? '' }}" type="text" placeholder="{{ __('mytasks.search_placeholder') }}" class="modern-search-input" autocomplete="off">
                <input type="hidden" name="status" value="{{ $filters['status'] ?? 'posted' }}">
                <input type="hidden" name="view" value="{{ $viewMode ?? 'posted' }}">
            </form>

            <div class="modern-filter-group">
                <a href="{{ route('my-tasks', ['view' => $viewMode ?? 'posted', 'status' => 'posted']) }}" class="filter-btn {{ in_array(($filters['status'] ?? 'posted'), ['posted', '']) ? 'active' : '' }}">{{ __('mytasks.filters.posted') }}</a>
                <a href="{{ route('my-tasks', ['view' => $viewMode ?? 'posted', 'status' => 'pending']) }}" class="filter-btn {{ in_array(($filters['status'] ?? ''), ['pending', 'assigned']) ? 'active' : '' }}">{{ __('mytasks.filters.pending') }}</a>
                <a href="{{ route('my-tasks', ['view' => $viewMode ?? 'posted', 'status' => 'completed']) }}" class="filter-btn {{ in_array(($filters['status'] ?? ''), ['completed']) ? 'active' : '' }}">{{ __('mytasks.filters.completed') }}</a>
            </div>
        </div>

        @if($hasTasks)
            
            <div class="task-hero">
                
                {{-- LEFT SIDE --}}
                <div class="hero-left">
                    @if(($viewMode ?? 'posted') === 'applied')
                        @php
                            $myOffer = $activeTask->offers->where('user_id', auth()->id())->first();
                        @endphp

                        @if($activeStatus === 'assigned')
                            @if($activeTask->employee_id == auth()->id())
                                <div class="status-badge active" style="color:#059669;">
                                    <span class="status-dot" style="background-color:#10B981;"></span> {{ __('mytasks.status.offer_accepted') }}
                                </div>
                                <h1 class="hero-headline">{{ __('mytasks.status.youre_hired') }}</h1>
                                <p class="hero-subtext">{{ __('mytasks.status.hired_desc') }}</p>
                                <div class="mt-6 flex gap-3">
                                    <a href="{{ route('messages', ['user_id' => $activeTask->employer_id]) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-full shadow-lg transition flex items-center gap-2 no-underline">
                                        <i data-feather="message-circle" class="w-5 h-5"></i> {{ __('mytasks.status.contact_employer') }}
                                    </a>
                                </div>
                            @else
                                <div class="status-badge" style="color:#DC2626;">
                                    <span class="status-dot" style="background-color:#EF4444;"></span> {{ __('mytasks.status.offer_not_selected') }}
                                </div>
                                <h1 class="hero-headline">{{ __('mytasks.status.task_assigned') }}</h1>
                                <p class="hero-subtext">{{ __('mytasks.status.assigned_desc') }}</p>
                                <div class="mt-6">
                                    <a href="{{ route('tasks') }}" class="text-blue-600 font-bold hover:underline">{{ __('mytasks.status.browse_more') }}</a>
                                </div>
                            @endif

                        @elseif($activeStatus === 'completed')
                            @if($activeTask->employee_id == auth()->id())
                                <div class="status-badge active">
                                    <span class="status-dot"></span> {{ __('mytasks.status.job_done') }}
                                </div>
                                <h1 class="hero-headline">{{ __('mytasks.status.task_completed') }}</h1>
                                <p class="hero-subtext">{{ __('mytasks.status.completed_desc') }}</p>
                            @else
                                <div class="status-badge">
                                    <span class="status-dot"></span> {{ __('mytasks.status.closed') }}
                                </div>
                                <h1 class="hero-headline">{{ __('mytasks.status.task_completed') }}</h1>
                                <p class="hero-subtext">{{ __('mytasks.status.other_completed_desc') }}</p>
                            @endif

                        @else
                            @if($myOffer)
                                <div class="flex items-center gap-2 mb-5">
                                    <div class="status-badge" style="color:#2563EB; margin-bottom: 0;">
                                        <span class="status-dot" style="background-color:#3B82F6;"></span> {{ __('mytasks.status.application_sent') }}
                                    </div>
                                    @if($activeTask->employee_id == auth()->id())
                                    <div class="flex items-center gap-1.5 bg-blue-50 text-blue-700 px-2 py-1 rounded-full border border-blue-100 h-fit">
                                        <i data-feather="user-check" class="w-3.5 h-3.5"></i>
                                        <span class="text-[10px] font-bold uppercase tracking-wider">{{ __('mytasks.status.directly_requested') }}</span>
                                    </div>
                                    @endif
                                </div>
                                <h1 class="hero-headline">{{ __('mytasks.status.waiting_response') }}</h1>
                                <p class="hero-subtext">
                                    {{ __('mytasks.status.you_offered') }} <strong>€{{ number_format($myOffer->price ?? 0, 0) }}</strong>.
                                    {{ __('mytasks.status.notify_offers') }}
                                </p>
                                <div class="bg-blue-50 border border-blue-100 p-4 rounded-xl mt-6">
                                    <h4 class="font-bold text-blue-900 text-sm mb-1">{{ __('mytasks.status.your_message') }}</h4>
                                    <p class="text-blue-700 text-sm italic">"{{ $myOffer->message ?? '' }}"</p>
                                </div>
                            @elseif($activeTask->employee_id == auth()->id())
                                <div class="status-badge" style="color:#6366f1;">
                                    <span class="status-dot" style="background-color:#6366f1;"></span> {{ __('mytasks.status.new_quote_request') }}
                                </div>
                                <h1 class="hero-headline">{{ __('mytasks.status.direct_request_headline') }}</h1>
                                <p class="hero-subtext">{{ __('mytasks.status.direct_request_subtext') }}</p>
                                <div class="mt-8 flex flex-wrap gap-4">
                                    <button type="button" onclick="openDirectQuoteModal()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3.5 px-8 rounded-full shadow-lg shadow-indigo-200 transition-all flex items-center gap-2 cursor-pointer">
                                        {{ __('mytasks.status.accept_or_counter') }} <i data-feather="arrow-right" class="w-5 h-5"></i>
                                    </button>
                                    <a href="{{ route('messages', ['user_id' => $activeTask->employer_id]) }}" class="bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 font-bold py-3.5 px-8 rounded-full transition-all flex items-center gap-2 no-underline">
                                        <i data-feather="message-circle" class="w-5 h-5"></i> {{ __('mytasks.status.message_employer') }}
                                    </a>
                                </div>
                            @endif
                        @endif

                    @elseif($activeStatus === 'assigned')
                        <div class="status-badge" style="color: #F59E0B;">
                            <span class="status-dot" style="background-color: #FBBF24;"></span> {{ __('mytasks.status.in_progress') }}
                        </div>
                        
                        @if($activeTask->employer_id == auth()->id())
                            @if($activeTask->is_direct)
                                <h1 class="hero-headline">{{ __('mytasks.status.request_accepted') }}</h1>
                                <p class="hero-subtext">{{ __('mytasks.status.request_accepted_desc') }}</p>
                            @else
                                <h1 class="hero-headline">{{ __('mytasks.status.task_underway') }}</h1>
                                <p class="hero-subtext">{{ __('mytasks.status.underway_desc') }}</p>
                            @endif
                            <div class="mt-6">
                                <button type="button" onclick="openCompleteTaskModal()" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-full shadow-lg transition transform hover:-translate-y-1 flex items-center gap-2">
                                    <i data-feather="check-circle" class="w-5 h-5"></i> {{ __('mytasks.status.mark_completed') }}
                                </button>
                            </div>
                        @else
                            <h1 class="hero-headline">{{ __('mytasks.status.waiting_for_completion') }}</h1>
                            <p class="hero-subtext">{{ __('mytasks.status.waiting_for_completion_desc') }}</p>
                            <div class="mt-6">
                                <a href="{{ route('messages', ['user_id' => $activeTask->employer_id]) }}" class="bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 font-bold py-3 px-8 rounded-full transition-all flex items-center gap-2 no-underline inline-flex">
                                    <i data-feather="message-circle" class="w-5 h-5"></i> {{ __('mytasks.status.contact_employer') }}
                                </a>
                            </div>
                        @endif
                    
                    @elseif($activeStatus === 'completed')
                        <div class="status-badge active">
                            <span class="status-dot"></span> {{ __('mytasks.filters.completed') }}
                        </div>
                        @if($activeTask->employer_id == auth()->id())
                            <h1 class="hero-headline">{{ __('mytasks.status.task_completed_employer') }}</h1>
                            <p class="hero-subtext">{{ __('mytasks.status.posted_completed_desc') }}</p>
                        @else
                            <h1 class="hero-headline">{{ __('mytasks.status.task_completed_employee') }}</h1>
                            <p class="hero-subtext">{{ __('mytasks.status.completed_desc') }}</p>
                        @endif


                    
                    @elseif($hasOffers)
                        @if(($viewMode ?? 'posted') === 'direct')
                            @if($activeTask->employer_id == auth()->id())
                                <div class="status-badge active" style="color:#6366f1;">
                                    <span class="status-dot" style="background-color:#6366f1;"></span> {{ __('mytasks.status.quote_received') }}
                                </div>
                                <h1 class="hero-headline">{{ __('mytasks.status.new_response') }}</h1>
                                <p class="hero-subtext">{{ __('mytasks.status.new_response_desc') }}</p>
                            @else
                                <div class="flex items-center gap-2 mb-5">
                                    <div class="status-badge" style="color:#2563EB; margin-bottom: 0;">
                                        <span class="status-dot" style="background-color:#3B82F6;"></span> {{ __('mytasks.status.application_sent') }}
                                    </div>
                                    <div class="flex items-center gap-1.5 bg-blue-50 text-blue-700 px-2 py-1 rounded-full border border-blue-100 h-fit">
                                        <i data-feather="user-check" class="w-3.5 h-3.5"></i>
                                        <span class="text-[10px] font-bold uppercase tracking-wider">{{ __('mytasks.status.directly_requested') }}</span>
                                    </div>
                                </div>
                                <h1 class="hero-headline">{{ __('mytasks.status.waiting_response') }}</h1>
                                @php
                                    $myDirectOffer = $activeTask->offers->where('user_id', auth()->id())->first();
                                @endphp
                                <p class="hero-subtext">
                                    {{ __('mytasks.status.you_offered') }} <strong>€{{ number_format($myDirectOffer->price ?? 0, 0) }}</strong>.
                                    {{ __('mytasks.status.notify_offers') }}
                                </p>
                            @endif
                        @else
                            <div class="status-badge active">
                                <span class="status-dot"></span> {{ __('mytasks.status.new_activity') }}
                            </div>
                            <h1 class="hero-headline">{{ __('mytasks.status.new_offers') }}</h1>
                            <p class="hero-subtext">{{ __('mytasks.status.offers_desc', ['count' => $offerCount]) }}.</p>
                        @endif
                    @else
                        @if(($viewMode ?? 'posted') === 'direct')
                            @if($activeTask->employer_id == auth()->id())
                                <div class="status-badge" style="color:#6B7280;">
                                    <span class="status-dot"></span> {{ __('mytasks.status.request_sent') }}
                                </div>
                                <h1 class="hero-headline">{{ __('mytasks.status.waiting_for_response') }}</h1>
                                <p class="hero-subtext">{{ __('mytasks.status.waiting_for_response_desc', ['name' => $activeTask->employee->first_name ?? 'the expert']) }}</p>
                            @else
                                <div class="status-badge" style="color:#6366f1;">
                                    <span class="status-dot" style="background-color:#6366f1;"></span> {{ __('mytasks.status.new_quote_request') }}
                                </div>
                                <h1 class="hero-headline">{{ __('mytasks.status.direct_request_headline') }}</h1>
                                <p class="hero-subtext">{{ __('mytasks.status.direct_request_subtext') }}</p>
                                <div class="mt-8 flex flex-wrap gap-4">
                                    <button type="button" onclick="openDirectQuoteModal()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3.5 px-8 rounded-full shadow-lg shadow-indigo-200 transition-all flex items-center gap-2 cursor-pointer">
                                        {{ __('mytasks.status.accept_or_counter') }} <i data-feather="arrow-right" class="w-5 h-5"></i>
                                    </button>
                                    <a href="{{ route('messages', ['user_id' => $activeTask->employer_id]) }}" class="bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 font-bold py-3.5 px-8 rounded-full transition-all flex items-center gap-2 no-underline">
                                        <i data-feather="message-circle" class="w-5 h-5"></i> {{ __('mytasks.status.message_employer') }}
                                    </a>
                                </div>
                            @endif
                        @else
                            <div class="status-badge">
                                <span class="status-dot"></span> {{ __('mytasks.status.task_posted') }}
                            </div>
                            <h1 class="hero-headline">{{ __('mytasks.status.find_taskers') }}</h1>
                            <p class="hero-subtext">{{ __('mytasks.status.notify_offers') }}</p>
                        @endif
                    @endif

                    <div class="offers-container mt-6">
                        @if(($viewMode ?? 'posted') === 'direct' && $activeStatus === 'open' && $activeTask->employer_id == auth()->id())
                            <div class="offers-header mb-4">
                                <div class="illustration-box">
                                    <div class="bg-indigo-100 p-3 rounded-full inline-block">
                                        <i data-feather="user-check" class="text-indigo-600 w-8 h-8"></i>
                                    </div>
                                </div>
                                @if($hasOffers)
                                    <h3 class="text-lg font-bold text-gray-800">{{ __('mytasks.status.review_quote') }}</h3>
                                    <p class="questions-copy mt-2">{{ __('mytasks.status.review_quote_desc') }}</p>
                                @else
                                    <h3 class="text-lg font-bold text-gray-800">{{ __('mytasks.status.awaiting_response') }}</h3>
                                    <p class="questions-copy mt-2">{{ __('mytasks.status.awaiting_response_desc') }}</p>
                                @endif
                            </div>
                        @elseif(($viewMode ?? 'posted') === 'posted' && $activeStatus === 'open')
                            <div class="offers-header mb-4">
                                <div class="illustration-box">
                                    <div class="bg-blue-100 p-3 rounded-full inline-block">
                                        <i data-feather="users" class="text-blue-600 w-8 h-8"></i>
                                    </div>
                                </div>
                                @if($hasOffers)
                                    <h3 class="text-lg font-bold text-gray-800">{{ __('mytasks.offers.header_count', ['count' => $offerCount]) }}</h3>
                                    <p class="questions-copy mt-2">{{ __('mytasks.offers.header_desc') }}</p>
                                @else
                                    <h3 class="text-lg font-bold text-gray-800">{{ __('mytasks.offers.waiting') }}</h3>
                                    <p class="questions-copy mt-2">{{ __('mytasks.offers.waiting_desc') }}</p>
                                @endif
                            </div>
                        @endif

                        @if($hasOffers && $showOffers && ($viewMode ?? 'posted') === 'direct' && $activeTask->employee_id == auth()->id())
                            @php $myDirectOffer = $activeTask->offers->where('user_id', auth()->id())->first(); @endphp
                            @if($myDirectOffer)
                                <div class="bg-indigo-50 border border-indigo-100 rounded-2xl p-5 dark:bg-indigo-950/40 dark:border-indigo-900">
                                    <div class="flex items-center gap-3 mb-3">
                                        <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center dark:bg-indigo-900/60">
                                            <i data-feather="check-circle" class="w-5 h-5 text-indigo-600 dark:text-indigo-400"></i>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-bold text-indigo-900 dark:text-indigo-100">{{ __('mytasks.status.your_offer_submitted') }}</h4>
                                            <p class="text-xs text-indigo-600 dark:text-indigo-400">{{ $myDirectOffer->created_at?->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between bg-white rounded-xl p-3 border border-indigo-100 dark:bg-slate-800/50 dark:border-indigo-900/50">
                                        <div>
                                            <span class="text-xs font-bold text-gray-500 uppercase dark:text-slate-400">{{ __('mytasks.modals.your_price_label') }}</span>
                                            <div class="text-xl font-bold text-indigo-600 dark:text-indigo-400">€{{ number_format($myDirectOffer->price, 0) }}</div>
                                        </div>
                                        <div class="text-right flex-1 ml-4">
                                            <span class="text-xs font-bold text-gray-500 uppercase dark:text-slate-400">{{ __('mytasks.status.your_message') }}</span>
                                            <p class="text-sm text-gray-700 italic mt-0.5 dark:text-slate-300">"{{ Illuminate\Support\Str::limit($myDirectOffer->message, 80, '...') }}"</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif

                        <div class="offers-list overflow-y-auto custom-scrollbar" style="max-height: 120px; padding-right: 8px;">
                            @if($hasOffers && $showOffers)
                                @if(($viewMode ?? 'posted') === 'direct' && $activeTask->employee_id == auth()->id())
                                    {{-- Already shown as card --}}
                                @else
                                    <div class="space-y-3">
                                        @foreach($activeTask->offers as $offer)
                                            <div onclick="openOfferModal({
                                                id: '{{ $offer->id }}',
                                                userId: '{{ $offer->user_id }}',
                                                initials: '{{ substr($offer->user->first_name ?? 'T', 0, 1) }}',
                                                avatarUrl: '{{ $offer->user->avatar_url }}',
                                                name: '{{ $offer->user->first_name ?? 'Tasker' }} {{ $offer->user->last_name ?? '' }}',
                                                rating: '{{ $offer->user->rating }}',
                                                timeText: '{{ $offer->created_at?->diffForHumans(null, true, true) }} {{ __('mytasks.stats.ago') }}',
                                                price: '{{ number_format($offer->price, 0) }}',
                                                message: `{{ addslashes($offer->message) }}`
                                            })" role="button" tabindex="0" aria-label="{{ __('Review offer from :name', ['name' => $offer->user->first_name ?? 'Tasker']) }}" class="group w-full p-3 rounded-xl border border-gray-200 bg-white hover:border-blue-400 hover:shadow-sm transition-all cursor-pointer relative overflow-hidden">
                                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-blue-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                                <div class="flex items-start gap-3">
                                                    <img src="{{ $offer->user->avatar_url }}" alt="Avatar" class="w-10 h-10 rounded-full object-cover border border-gray-200 shrink-0">
                                                    <div class="flex-1 min-w-0">
                                                        <div class="flex justify-between items-start">
                                                            <div>
                                                                <h4 class="text-sm font-bold text-gray-900 leading-tight">
                                                                    {{ $offer->user->first_name ?? 'Tasker' }} {{ $offer->user->last_name ?? '' }}
                                                                </h4>
                                                                <div class="flex items-center gap-1 mt-1">
                                                                    <i data-feather="star" class="w-3 h-3 text-yellow-400 fill-current"></i>
                                                                    <span class="text-xs font-bold text-gray-800">{{ $offer->user->rating }}</span>
                                                                    <span class="text-gray-300 mx-1">•</span>
                                                                    <span class="text-[10px] text-gray-400 font-medium uppercase tracking-wide">
                                                                        {{ $offer->created_at?->diffForHumans(null, true, true) }} {{ __('mytasks.stats.ago') }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="text-right">
                                                                <div class="text-base font-bold text-blue-600 leading-tight">
                                                                    €{{ number_format($offer->price, 0) }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <p class="text-xs text-gray-600 mt-2 leading-relaxed">
                                                            {{ \Illuminate\Support\Str::limit($offer->message, 50, '...') }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>

                    @if($activeStatus === 'open')
                    <div class="view-count">
                        <i data-feather="eye" style="width:14px;"></i> {{ $viewCount }} {{ __('mytasks.stats.views') }}
                    </div>
                    @endif
                </div>

                {{-- RIGHT SIDE --}}
                <div class="hero-right">

                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-2">
                                <span class="task-label">{{ __('mytasks.details.status_label', ['status' => __('mytasks.status_names.' . $activeStatus)]) }}</span>
                                @if(($viewMode ?? 'posted') === 'posted' && $activeTask->employee_id)
                                <div class="flex items-center gap-1.5 bg-blue-50 text-blue-700 px-2 py-0.5 rounded-full border border-blue-100">
                                    <i data-feather="user" class="w-3 h-3"></i>
                                    <span class="text-[10px] font-bold uppercase tracking-wider">{{ __('mytasks.modals.sent_to', ['name' => $activeTask->employee->first_name]) }}</span>
                                </div>
                                @endif
                            </div>

                            @if(($viewMode ?? 'posted') === 'posted' && $activeStatus === 'open')
                            <div class="management-pills">
                                <button type="button" class="pill-action" onclick="openEditTaskModal()">
                                    <i data-feather="edit-2" style="width:12px; height:12px;"></i>
                                    {{ __('mytasks.actions.edit_short') }}
                                </button>
                                <form action="{{ route('tasks.destroy', $activeTask->id) }}" method="POST" class="m-0 p-0" onsubmit="return confirm('{{ __('mytasks.modals.confirm_cancel') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="pill-action danger">
                                        <i data-feather="trash-2" style="width:12px; height:12px;"></i>
                                        {{ __('mytasks.actions.cancel_short') }}
                                    </button>
                                </form>
                            </div>
                            @endif
                        </div>
                        <h2 class="task-main-title">{{ $activeTask->title }}</h2>
                        
                        @if(($viewMode ?? 'posted') === 'applied' && $activeTask->employer)
                            <div class="mb-5">
                                <a href="{{ route('public-profile', $activeTask->employer->id) }}" class="inline-flex items-center gap-2 group no-underline">
                                    <div class="relative">
                                        <img src="{{ $activeTask->employer->avatar_url }}" class="w-7 h-7 rounded-full object-cover border-2 border-white shadow-sm group-hover:border-blue-100 transition-all" alt="{{ $activeTask->employer->first_name }}">
                                        <div class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 bg-green-500 border-2 border-white rounded-full"></div>
                                    </div>
                                    <span class="text-sm font-bold text-gray-600 group-hover:text-blue-600 transition-colors uppercase tracking-tight">
                                        {{ $activeTask->employer->first_name }} {{ $activeTask->employer->last_name }}
                                    </span>
                                </a>
                            </div>
                        @endif
                        
                        @if(!empty($activeTask->description))
                            <button type="button" class="description-toggle" onclick="openTaskDetailsModal()" title="{{ __('mytasks.actions.read_more_alt') }}">
                                <p class="task-description-truncated">
                                    {{ \Illuminate\Support\Str::limit($activeTask->description, 57, '...') }}
                                </p>
                                <div class="flex items-center gap-1 text-sm font-semibold text-blue-600 mt-1">
                                    {{ __('mytasks.actions.read_more') }} <i data-feather="chevron-down" class="description-arrow"></i>
                                </div>
                            </button>
                        @endif

                        <div class="price-display">€{{ number_format($activeTask->price, 0) }}</div>
                    </div>

                    <div class="hero-right-details">
                        <div class="data-row">
                            <div class="data-icon"><i data-feather="calendar"></i></div>
                            <div class="data-text">
                                <h4>{{ __('mytasks.details.due_date') }}</h4>
                                <p>
                                    @if($activeTask->is_date_flexible)
                                        {{ __('mytasks.details.flexible') }}
                                    @elseif($activeTask->required_date)
                                        {{ \Carbon\Carbon::parse($activeTask->required_date)->format('M d, Y') }}
                                    @elseif($activeTask->required_before_date)
                                      {{ __('mytasks.status.bc_before') }} {{ \Carbon\Carbon::parse($activeTask->required_before_date)->format('M d, Y') }}
                                    @else
                                        {{ __('mytasks.details.flexible') }}
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="data-row">
                            <div class="data-icon"><i data-feather="map-pin"></i></div>
                            <div class="data-text">
                                <h4>{{ __('mytasks.details.location') }}</h4>
                                <p>{{ optional(optional($activeTask->employer)->city)->name ?? 'Remote' }}</p>
                            </div>
                        </div>
                        <div class="data-row">
                            <div class="data-icon"><i data-feather="tag"></i></div>
                            <div class="data-text">
                                <h4>{{ __('mytasks.details.category') }}</h4>
                                <p>{{ __('categories.' . (optional(optional($activeTask->job)->category)->name ?? 'General')) }}</p>
                            </div>
                        </div>
                        <div class="data-row">
                            <div class="data-icon"><i data-feather="file-text"></i></div>
                            <div class="data-text">
                                <h4>{{ __('mytasks.details.job') }}</h4>
                                <p>{{ __('jobs.' . (optional($activeTask->job)->name ?? ('Job #'.$activeTask->jobs_id))) }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- MODAL: Full Description --}}
                    <div id="task-details-modal" class="task-details-modal">
                        <div id="task-details-backdrop" class="task-details-backdrop" onclick="closeTaskDetailsModal()"></div>
                        <div class="task-details-panel">
                            <button type="button" class="task-details-close" onclick="closeTaskDetailsModal()" aria-label="{{ __('mytasks.modals.close') }}">
                                <i data-feather="x" style="width:16px; height:16px;"></i>
                            </button>
                            @if(!empty($activeTask->description))
                                <div id="task-details-body" class="task-details-body whitespace-pre-wrap">{{ $activeTask->description }}</div>
                            @endif
                        </div>
                    </div>

                    {{-- MODAL: Complete & Review --}}
                    <div id="complete-task-modal" class="task-details-modal @if($errors->has('comment') || $errors->has('stars')) show @endif">
                        <div class="task-details-backdrop" onclick="closeCompleteTaskModal()"></div>
                        <div class="task-details-panel" style="max-width: 450px;">
                            <button type="button" class="task-details-close" onclick="closeCompleteTaskModal()" aria-label="{{ __('Close modal') }}">
                                <i data-feather="x" style="width:16px; height:16px;"></i>
                            </button>
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ __('mytasks.status.task_completed') }}</h3>
                            <p class="text-gray-500 mb-6">{{ __('mytasks.modals.leave_review_q') }}</p>
                            <div id="complete-choice-buttons" class="grid grid-cols-1 gap-3">
                                <button onclick="showReviewForm()" class="h-14 w-full flex items-center justify-center gap-2 px-4 rounded-xl bg-blue-600 text-white font-bold hover:bg-blue-700 transition">
                                    <i data-feather="star" class="w-4 h-4"></i> {{ __('mytasks.modals.leave_review_btn') }}
                                </button>
                                <form action="{{ route('tasks.complete', $activeTask->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="h-14 w-full flex items-center justify-center gap-2 px-4 rounded-xl bg-gray-100 text-gray-600 font-bold hover:bg-gray-200 transition">
                                        {{ __('mytasks.modals.just_complete_btn') }}
                                    </button>
                                </form>
                            </div>
                            <div id="complete-review-form" class="hidden">
                                <form action="{{ route('tasks.complete', $activeTask->id) }}" method="POST">
                                    @csrf
                                    <div class="mb-4">
                                        <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('mytasks.modals.rating') }}</label>
                                        <div class="flex gap-2 text-2xl" id="star-rating-input">
                                            @for($i=1; $i<=5; $i++)
                                                <i role="button" tabindex="0" aria-label="{{ __('mytasks.modals.rate_stars', ['count' => $i]) }}" data-feather="star" class="cursor-pointer text-gray-300 hover:text-yellow-400" onclick="setRating({{ $i }})" id="star-{{ $i }}"></i>
                                            @endfor
                                        </div>
                                        <input type="hidden" name="stars" id="rating-value" required>
                                    </div>
                                    <div class="mb-6">
                                        <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('mytasks.modals.comment') }}</label>
                                        <textarea name="comment" rows="3" class="w-full border border-gray-300 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('comment') border-red-500 @enderror" placeholder="{{ __('mytasks.modals.write_review_placeholder') }}" required></textarea>
                                        @error('comment')
                                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <button type="submit" class="h-14 w-full flex items-center justify-center gap-2 px-4 rounded-xl bg-blue-600 text-white font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-200">
                                        {{ __('mytasks.modals.complete_review_btn') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- MODAL: Offer Details --}}
                    <div id="offer-details-modal" class="task-details-modal" style="z-index: 60;">
                        <div class="task-details-backdrop" onclick="closeOfferModal()"></div>
                        <div class="task-details-panel" style="max-width: 500px;">
                            <button type="button" class="task-details-close" onclick="closeOfferModal()" aria-label="{{ __('mytasks.modals.close_modal') }}">
                                <i data-feather="x" style="width:16px; height:16px;"></i>
                            </button>
                            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
                                <a id="modal-profile-link" href="#" class="flex items-center gap-3 group text-decoration-none">
                                    <div id="modal-offer-avatar" class="w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 font-bold text-xl sm:text-2xl border border-gray-200 group-hover:border-blue-400 transition-colors shrink-0"></div>
                                    <div class="min-w-0">
                                        <h3 id="modal-offer-name" class="text-lg sm:text-xl font-bold text-gray-900 leading-tight group-hover:text-blue-600 transition-colors truncate"></h3>
                                        <div class="flex items-center gap-1 mt-1">
                                            <i data-feather="star" class="w-3.5 h-3.5 text-yellow-400 fill-current"></i>
                                            <span id="modal-offer-rating" class="text-xs sm:text-sm font-bold text-gray-800"></span>
                                            <span class="text-gray-300 mx-1">•</span>
                                            <span id="modal-offer-time" class="text-[10px] sm:text-xs text-gray-400 font-medium uppercase tracking-wide"></span>
                                        </div>
                                    </div>
                                </a>
                                <div class="w-full sm:w-auto flex sm:flex-col justify-between items-center sm:items-end border-t sm:border-t-0 pt-4 sm:pt-0 border-gray-100">
                                    <div id="modal-offer-price" class="text-2xl font-bold text-blue-600 order-2 sm:order-1"></div>
                                    <div class="text-[10px] text-gray-400 uppercase font-bold sm:mt-1 order-1 sm:order-2">{{ __('mytasks.modals.offer_price') }}</div>
                                </div>
                            </div>
                            <div class="bg-gray-50 rounded-2xl p-4 sm:p-5 mb-6 border border-gray-100">
                                <h4 class="text-[10px] sm:text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">{{ __('mytasks.modals.message_from_tasker') }}</h4>
                                <p id="modal-offer-message" class="text-gray-700 text-sm leading-relaxed whitespace-pre-wrap break-words" style="overflow-wrap: break-word; word-break: break-word;"></p>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <a id="message-tasker-btn" href="#" class="h-14 w-full flex items-center justify-center gap-2 px-4 rounded-xl bg-white border border-gray-200 text-gray-700 font-bold hover:bg-gray-50 transition text-center no-underline">
                                    <i data-feather="message-circle" class="w-4 h-4"></i> {{ __('mytasks.modals.message') }}
                                </a>
                                <form id="accept-offer-form" method="POST" action="" class="h-14">
                                    @csrf
                                    @if($activeStatus === 'open')
                                        <button type="submit" class="h-full w-full flex items-center justify-center gap-2 px-4 rounded-xl bg-blue-600 border border-blue-600 text-white font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-200">
                                            <i data-feather="check" class="w-4 h-4"></i> {{ __('mytasks.modals.accept_offer') }}
                                        </button>
                                    @else
                                        <button type="button" disabled class="h-full w-full flex items-center justify-center gap-2 px-4 rounded-xl bg-gray-200 border border-gray-200 text-gray-400 font-bold cursor-not-allowed">
                                            <i data-feather="check" class="w-4 h-4"></i> {{ __('mytasks.status.offer_accepted') }}
                                        </button>
                                    @endif
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- MODAL: Direct Quote Response --}}
                    @if($activeTask && $activeTask->employee_id == auth()->id() && $activeStatus === 'open' && !$activeTask->offers->where('user_id', auth()->id())->first())
                    <div id="direct-quote-modal" class="task-details-modal" style="z-index: 65;">
                        <div class="task-details-backdrop" onclick="closeDirectQuoteModal()"></div>
                        <div class="task-details-panel" style="max-width: 500px;">
                            <button type="button" class="task-details-close" onclick="closeDirectQuoteModal()" aria-label="{{ __('mytasks.modals.close_modal') }}">
                                <i data-feather="x" style="width:16px; height:16px;"></i>
                            </button>

                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center">
                                    <i data-feather="send" class="w-5 h-5 text-indigo-600"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900">{{ __('mytasks.modals.respond_quote') }}</h3>
                                    <p class="text-sm text-gray-500">{{ $activeTask->employer->first_name ?? '' }} · {{ $activeTask->title }}</p>
                                </div>
                            </div>

                            <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-4 mb-6">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-indigo-700">{{ __('mytasks.modals.their_budget') }}</span>
                                    <span class="text-lg font-bold text-indigo-800">€{{ number_format($activeTask->price ?? 0, 0) }}</span>
                                </div>
                            </div>

                            <form action="{{ route('tasks.accept-direct', $activeTask->id) }}" method="POST" class="mb-4">
                                @csrf
                                <button type="submit" class="h-14 w-full flex items-center justify-center gap-2 px-4 rounded-xl bg-green-600 text-white font-bold hover:bg-green-700 transition shadow-lg shadow-green-200 cursor-pointer">
                                    <i data-feather="check" class="w-4 h-4"></i> {{ __('mytasks.modals.accept_budget') }} (€{{ number_format($activeTask->price ?? 0, 0) }})
                                </button>
                            </form>

                            <div class="relative flex items-center my-5">
                                <div class="flex-grow border-t border-gray-200"></div>
                                <span class="mx-4 text-xs font-bold text-gray-400 uppercase">{{ __('mytasks.modals.or_counter') }}</span>
                                <div class="flex-grow border-t border-gray-200"></div>
                            </div>

                            <form action="{{ route('tasks.offers.store', $activeTask->id) }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('mytasks.modals.your_price_label') }}</label>
                                    <input type="number" name="offer_price" id="direct-quote-price" min="1" class="w-full border border-gray-300 rounded-xl p-3 text-lg font-bold focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" required placeholder="{{ __('mytasks.modals.your_price') }}">
                                </div>
                                <div class="mb-6">
                                    <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('mytasks.modals.message_label') }}</label>
                                    <textarea name="message" rows="3" class="w-full border border-gray-300 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" required placeholder="{{ __('mytasks.modals.describe_help') }}"></textarea>
                                </div>
                                <button type="submit" class="h-14 w-full flex items-center justify-center gap-2 px-4 rounded-xl bg-indigo-600 text-white font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-200 cursor-pointer">
                                    <i data-feather="send" class="w-4 h-4"></i> {{ __('mytasks.modals.send_counter') }}
                                </button>
                            </form>
                        </div>
                    </div>
                    @endif

                    {{-- MODAL: Edit Task --}}
                    @if($activeTask)
                    <div id="edit-task-modal" class="task-details-modal" style="z-index: 100;">
                        <div class="task-details-backdrop" onclick="closeEditTaskModal()"></div>
                        <div class="task-details-panel" style="max-width: 600px; max-height: 90vh; overflow-y: auto;">
                            <button type="button" class="task-details-close" onclick="closeEditTaskModal()" aria-label="{{ __('Close modal') }}">
                                <i data-feather="x" style="width:16px; height:16px;"></i>
                            </button>
                            <h3 class="text-2xl font-bold text-gray-900 mb-6">{{ __('mytasks.modals.edit_task') }}</h3>
                            
                            <form action="{{ route('tasks.update', $activeTask->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                
                                <div class="grid grid-cols-1 gap-4">
                                    {{-- Title --}}
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('mytasks.modals.task_title') }}</label>
                                        <input type="text" name="title" value="{{ old('title', $activeTask->title) }}" class="w-full border border-gray-300 rounded-lg p-3 outline-none focus:ring-2 focus:ring-blue-600 transition" required>
                                    </div>
                                    
                                    {{-- Category & Job --}}
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('mytasks.modals.category') }}</label>
                                            <select id="editCategorySelect" class="w-full border border-gray-300 rounded-lg p-3 bg-white outline-none focus:ring-2 focus:ring-blue-600 transition" required>
                                                <option value="">{{ __('mytasks.modals.select_category') }}</option>
                                                @foreach($allCategories as $cat)
                                                    <option value="{{ $cat->id }}" {{ ($activeTask->job->categories_id ?? null) == $cat->id ? 'selected' : '' }}>{{ __('categories.' . $cat->name) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('mytasks.modals.service') }}</label>
                                            <select id="editJobSelect" name="jobs_id" data-placeholder="{{ __('mytasks.modals.select_service') }}" class="w-full border border-gray-300 rounded-lg p-3 bg-white outline-none focus:ring-2 focus:ring-blue-600 transition" required>
                                                <option value="{{ $activeTask->jobs_id }}" selected>{{ isset($activeTask->job->name) ? __('jobs.' . $activeTask->job->name) : __('mytasks.modals.select_service') }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    {{-- Description --}}
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('mytasks.modals.details') }}</label>
                                        <textarea name="description" rows="4" class="w-full border border-gray-300 rounded-lg p-3 outline-none focus:ring-2 focus:ring-blue-600 transition" required>{{ old('description', $activeTask->description) }}</textarea>
                                    </div>

                                    {{-- Type & Location --}}
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('mytasks.modals.task_type') }}</label>
                                            <select id="editTypeSelect" name="task_type" class="w-full border border-gray-300 rounded-lg p-3 bg-white outline-none focus:ring-2 focus:ring-blue-600 transition" required>
                                                <option value="in-person" {{ $activeTask->task_type === 'in-person' ? 'selected' : '' }}>{{ __('mytasks.modals.in_person') }}</option>
                                                <option value="online" {{ $activeTask->task_type === 'online' ? 'selected' : '' }}>{{ __('mytasks.modals.online') }}</option>
                                            </select>
                                        </div>
                                        <div id="editLocationContainer" class="{{ $activeTask->task_type === 'online' ? 'hidden' : '' }}">
                                            <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('mytasks.modals.location') }}</label>
                                            <input type="text" name="location" id="editLocationInput" value="{{ old('location', $activeTask->location) }}" class="w-full border border-gray-300 rounded-lg p-3 outline-none focus:ring-2 focus:ring-blue-600 transition">
                                        </div>
                                    </div>
                                    
                                    {{-- Budget --}}
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('mytasks.modals.budget') }} (€)</label>
                                        <input type="number" name="price" min="5" max="9999" value="{{ old('price', $activeTask->price) }}" class="w-full border border-gray-300 rounded-lg p-3 outline-none focus:ring-2 focus:ring-blue-600 transition" required>
                                    </div>
                                    
                                    <div class="border-b border-gray-100 pb-5">
                                        <div class="mb-4">
                                            <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('post-task.step1.date_label') }}</label>
                                            
                                            <input type="hidden" name="is_date_flexible" id="edit_input_is_date_flexible" value="{{ $activeTask->is_date_flexible ? '1' : '0' }}" />

                                            <div class="flex flex-wrap gap-2">
                                                <div class="relative flex-1 min-w-[140px]">
                                                    <button type="button" class="modal-date-btn {{ $activeTask->required_before_date && !$activeTask->is_date_flexible ? 'active' : '' }}" id="editBeforeDateBtn">
                                                        <span id="editBeforeDateLabel" data-placeholder="{{ __('post-task.step1.before_date') }}">{{ $activeTask->required_before_date && !$activeTask->is_date_flexible ? Carbon\Carbon::parse($activeTask->required_before_date)->format('M d, Y') : __('post-task.step1.before_date') }}</span>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                                    </button>
                                                    <input type="date" name="required_before_date" class="absolute inset-0 opacity-0 w-full h-full cursor-pointer z-10" id="editBeforeDateValue" value="{{ $activeTask->required_before_date?->format('Y-m-d') }}" style="{{ $activeTask->required_before_date && !$activeTask->is_date_flexible ? '' : 'pointer-events: none;' }}" />
                                                </div>

                                                <div class="relative flex-1 min-w-[140px]">
                                                    <button type="button" class="modal-date-btn {{ $activeTask->required_date && !$activeTask->is_date_flexible ? 'active' : '' }}" id="editOnDateBtn">
                                                        <span id="editOnDateLabel" data-placeholder="{{ __('post-task.step1.on_date') }}">{{ $activeTask->required_date && !$activeTask->is_date_flexible ? Carbon\Carbon::parse($activeTask->required_date)->format('M d, Y') : __('post-task.step1.on_date') }}</span>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                                    </button>
                                                    <input type="date" name="required_date" class="absolute inset-0 opacity-0 w-full h-full cursor-pointer z-10" id="editOnDateValue" value="{{ $activeTask->required_date?->format('Y-m-d') }}" style="{{ $activeTask->required_date && !$activeTask->is_date_flexible ? '' : 'pointer-events: none;' }}" />
                                                </div>

                                                <button type="button" class="modal-pill-btn" id="editFlexibleBtn" data-active="{{ $activeTask->is_date_flexible ? 'true' : 'false' }}">
                                                    {{ __('post-task.step1.flexible') }}
                                                </button>
                                            </div>
                                        </div>

                                        @php
                                            $hasTime = is_array($activeTask->preferred_time) && count($activeTask->preferred_time) > 0;
                                        @endphp

                                        <div class="mt-4">
                                            <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-3 cursor-pointer">
                                                <input type="checkbox" id="editNeedTimeCheckbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" {{ $hasTime ? 'checked' : '' }} />
                                                <span>{{ __('post-task.step1.certain_time') }}</span>
                                            </label>

                                            <div id="editTimeOfDayOptions" class="grid grid-cols-2 sm:grid-cols-4 gap-2 {{ $hasTime ? '' : 'hidden' }}">
                                                @php $ptimes = $activeTask->preferred_time ?? []; @endphp
                                                <label class="modal-time-option {{ in_array('morning', $ptimes) ? 'selected' : '' }}" data-time="morning">
                                                    <input type="checkbox" name="preferred_time[]" value="morning" class="hidden" {{ in_array('morning', $ptimes) ? 'checked' : '' }}>
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 18a5 5 0 0 0-10 0"></path><line x1="12" y1="2" x2="12" y2="9"></line><line x1="4.22" y1="10.22" x2="5.64" y2="11.64"></line><line x1="1" y1="18" x2="3" y2="18"></line><line x1="21" y1="18" x2="23" y2="18"></line><line x1="18.36" y1="11.64" x2="19.78" y2="10.22"></line><line x1="23" y1="22" x2="1" y2="22"></line><polyline points="8 6 12 2 16 6"></polyline></svg>
                                                    <span class="font-bold text-xs text-gray-800">{{ __('post-task.step1.morning') }}</span>
                                                </label>
                                                <label class="modal-time-option {{ in_array('midday', $ptimes) ? 'selected' : '' }}" data-time="midday">
                                                    <input type="checkbox" name="preferred_time[]" value="midday" class="hidden" {{ in_array('midday', $ptimes) ? 'checked' : '' }}>
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
                                                    <span class="font-bold text-xs text-gray-800">{{ __('post-task.step1.midday') }}</span>
                                                </label>
                                                <label class="modal-time-option {{ in_array('afternoon', $ptimes) ? 'selected' : '' }}" data-time="afternoon">
                                                    <input type="checkbox" name="preferred_time[]" value="afternoon" class="hidden" {{ in_array('afternoon', $ptimes) ? 'checked' : '' }}>
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 18a5 5 0 0 0-10 0"></path><line x1="12" y1="9" x2="12" y2="2"></line><line x1="4.22" y1="10.22" x2="5.64" y2="11.64"></line><line x1="1" y1="18" x2="3" y2="18"></line><line x1="21" y1="18" x2="23" y2="18"></line><line x1="18.36" y1="11.64" x2="19.78" y2="10.22"></line><line x1="23" y1="22" x2="1" y2="22"></line><polyline points="16 5 12 9 8 5"></polyline></svg>
                                                    <span class="font-bold text-xs text-gray-800">{{ __('post-task.step1.afternoon') }}</span>
                                                </label>
                                                <label class="modal-time-option {{ in_array('evening', $ptimes) ? 'selected' : '' }}" data-time="evening">
                                                    <input type="checkbox" name="preferred_time[]" value="evening" class="hidden" {{ in_array('evening', $ptimes) ? 'checked' : '' }}>
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
                                                    <span class="font-bold text-xs text-gray-800">{{ __('post-task.step1.evening') }}</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Photos --}}
                                    <div class="mt-2">
                                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('mytasks.modals.update_photos') }}</label>
                                        <input type="file" name="photos[]" multiple accept="image/*" class="w-full border border-gray-300 rounded-lg p-3 bg-gray-50 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 transition">
                                        <p class="text-xs text-gray-500 mt-2 font-medium">{{ __('mytasks.modals.photos_help') }}</p>
                                    </div>

                                    <button type="submit" class="w-full h-14 mt-6 bg-blue-600 text-white font-bold text-lg rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-200 flex items-center justify-center gap-2">
                                        <i data-feather="save" class="w-5 h-5"></i>
                                        {{ __('mytasks.modals.save_changes') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endif

                </div>
            </div>

            {{-- OTHER TASKS LIST --}}
            @if($otherTasks->count() > 0)
                <div class="other-tasks-container">
                    <h3 class="text-xl font-bold text-gray-800 mb-4 px-2">{{ __('mytasks.modals.other_tasks') }}</h3>
                    @foreach($otherTasks as $task)
                        @php $otherStatus = $getStatusValue($task->status); @endphp
                        <a href="{{ request()->fullUrlWithQuery(['task_id' => $task->id]) }}" class="compact-task-row">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-500">
                                    <i data-feather="clipboard" style="width:18px;"></i>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-400 font-bold uppercase flex items-center gap-2">
                                        {{ __('mytasks.status_names.' . $otherStatus) }}
                                        @if((($viewMode ?? 'posted') === 'posted' || ($viewMode ?? 'posted') === 'direct') && $task->employee_id)
                                            <div class="flex items-center gap-1 bg-blue-50 text-blue-600 px-1.5 py-0.5 rounded-full border border-blue-100" title="{{ __('mytasks.modals.sent_to', ['name' => $task->employee->first_name]) }}">
                                                <i data-feather="user" style="width:10px; height:10px;"></i>
                                                <span class="text-[8px] tracking-tighter">{{ $task->employee->first_name }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <span class="text-base font-bold text-gray-800 hover:text-blue-600 transition">{{ $task->title }}</span>
                                </div>
                            </div>
                            <div class="font-bold text-gray-600">€{{ number_format($task->price, 0) }}</div>
                        </a>
                    @endforeach
                </div>
            @endif

        @else
            {{-- EMPTY STATE --}}
            <div class="modern-empty-state">
                <div class="empty-illustration">
                    <i data-feather="clipboard" style="width:48px; height:48px;"></i>
                </div>
                <h3 class="empty-title">
                    @if(($filters['status'] ?? 'posted') === 'posted' && empty($filters['q']) && ($viewMode ?? 'posted') === 'posted')
                        {{ __('mytasks.empty.no_tasks') }}
                    @elseif(($viewMode ?? 'posted') === 'applied')
                        {{ __('mytasks.empty.no_applications') }}
                    @elseif(($viewMode ?? 'posted') === 'direct')
                        {{ __('mytasks.empty.no_direct_requests') }}
                    @else
                        {{ __('mytasks.empty.no_tasks_found') }}
                    @endif
                </h3>
                <p class="empty-desc">
                    @if(($viewMode ?? 'posted') === 'applied')
                        {{ __('mytasks.empty.no_applications_desc') }}
                    @elseif(($viewMode ?? 'posted') === 'direct')
                        {{ __('mytasks.empty.no_direct_requests_desc') }}
                    @else
                        @if(($filters['status'] ?? 'posted') === 'posted')
                            @if(!empty($filters['q']))
                                {{ __('mytasks.empty.no_tasks_search') }}
                            @else
                                {{ __('mytasks.empty.no_tasks_posted_desc') }}
                            @endif
                        @else
                            {{ __('mytasks.empty.no_tasks_status') }}
                        @endif
                    @endif
                </p>
                @if(($viewMode ?? 'posted') === 'applied')
                    <a href="{{ route('tasks') }}" class="cta-button no-underline">
                        <i data-feather="search" style="width:18px;"></i> {{ __('mytasks.empty.browse_tasks_btn') }}
                    </a>
                @else
                    <a href="{{ route('post-task') }}" class="cta-button no-underline">
                        <i data-feather="plus" style="width:18px;"></i> {{ __('mytasks.empty.post_task_btn') }}
                    </a>
                @endif
            </div>
        @endif

    </div>
</section>

@endsection

@push('scripts')
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="{{ asset('js/pages/my-tasks.js') }}"></script>
@endpush
