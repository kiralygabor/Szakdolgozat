@extends('layout')

@section('title', __('mytasks.title'))

{{-- 1. STYLES --}}
<style>
    /* --- General & Layout --- */
    body { background-color: #F3F4F6; font-family: 'Inter', sans-serif; }

    .main-wrapper { 
        max-width: 1200px; 
        margin: 0 auto; 
        padding: 40px 20px; 
        min-height: 85vh;
        display: flex; 
        flex-direction: column; 
    }

    @media (max-width: 768px) {
        .main-wrapper { padding: 20px 16px; }
    }

    /* --- HERO CARD CONTAINER --- */
    .task-hero {
        background-color: #FFFFFF;
        border-radius: 30px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: row;
        overflow: hidden;
        min-height: 600px; 
        border: 1px solid #E5E7EB;
    }

    @media(max-width: 900px) {
        .task-hero { flex-direction: column; min-height: auto; border-radius: 20px; }
    }

    /* --- LEFT SIDE (Interactive Hub) --- */
    .hero-left {
        flex: 1.3;
        padding: 60px;
        display: flex;
        flex-direction: column;
        position: relative;
        background-color: #FFFFFF;
    }

    @media(max-width: 900px) {
        .hero-left { padding: 32px 24px; }
    }

    /* Status & Headers */
    .status-badge {
        display: inline-flex; align-items: center; gap: 8px;
        font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;
        margin-bottom: 20px;
        color: #6B7280;
    }
    .status-badge.active { color: #059669; } 
    .status-dot { width: 8px; height: 8px; border-radius: 50%; background-color: #D1D5DB; }
    .status-badge.active .status-dot { background-color: #10B981; box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2); }

    .hero-headline {
        font-size: 42px; font-weight: 800; color: #111827; line-height: 1.1; margin-bottom: 16px;
    }
    @media (max-width: 768px) {
        .hero-headline { font-size: 28px; }
    }

    .hero-subtext {
        font-size: 16px; color: #4B5563; line-height: 1.6; max-width: 90%; margin-bottom: 15px;
    }
    
    .view-count {
        position: absolute; top: 24px; right: 24px;
        display: inline-flex; align-items: center; gap: 6px;
        font-size: 12px; font-weight: 600; color: #6B7280;
        background: #F9FAFB; padding: 6px 12px; border-radius: 999px;
        box-shadow: 0 4px 10px rgba(148, 163, 184, 0.25);
    }
    @media (max-width: 768px) {
        .view-count { top: 16px; right: 16px; }
    }

    /* Offers UI */
    .offers-header { text-align: center; display: flex; flex-direction: column; align-items: center; }
    .illustration-box { margin-bottom: 8px; }
    .questions-copy { font-size: 14px; color: #6B7280; max-width: 360px; }
    
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #E5E7EB; border-radius: 20px; }
    
    /* --- RIGHT SIDE (Task Details) --- */
    .hero-right {
        flex: 1.2;
        background-color: #F7F2EB;
        padding: 50px;
        color: #111827;
        position: relative;
        display: flex;
        flex-direction: column;
    }
    @media(max-width: 900px) {
        .hero-right { padding: 32px 24px; border-top: 1px solid #E5E7EB; }
    }

    .more-options-container { position: absolute; top: 30px; right: 30px; z-index: 20; }
    .more-btn {
        width: 44px; height: 44px; border-radius: 50%;
        background: #EEF2FF; border: 1px solid #E5E7EB;
        color: #4B5563; display: flex; align-items: center; justify-content: center;
        cursor: pointer; transition: all 0.2s;
    }
    
    .custom-dropdown {
        position: absolute; right: 0; top: 55px;
        background: white; width: 220px;
        border-radius: 16px; box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        padding: 8px; opacity: 0; transform: translateY(-10px); pointer-events: none;
        transition: all 0.2s ease;
    }
    .custom-dropdown.show { opacity: 1; transform: translateY(0); pointer-events: auto; }
    .dropdown-item {
        display: flex; align-items: center; gap: 10px;
        padding: 10px 12px; color: #374151; text-decoration: none;
        font-size: 14px; font-weight: 500; border-radius: 8px;
    }
    .dropdown-item:hover { background: #F3F4F6; color: #111827; }

    /* Task Content */
    .task-label { color: #6B7280; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 12px; display: block; }
    .task-main-title { font-size: 32px; font-weight: 800; line-height: 1.2; margin-bottom: 10px; color: #111827; }
    .price-display { font-size: 36px; font-weight: 700; color: #2563EB; margin: 24px 0; letter-spacing: -1px; }

    .data-row { display: flex; gap: 15px; margin-bottom: 20px; align-items: flex-start; }
    .data-icon { width: 40px; height: 40px; background: #E5E7EB; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #4B5563; flex-shrink: 0; }
    .data-text h4 { font-size: 12px; color: #6B7280; text-transform: uppercase; font-weight: 700; margin-bottom: 2px; }
    .data-text p { font-size: 15px; font-weight: 500; color: #111827; }

    /* Description UI */
    .description-toggle { margin-top: 18px; background: transparent; border: none; cursor: pointer; text-align: left; }
    .task-description-truncated { font-size: 14px; color: #1F2933; line-height: 1.6em; margin-bottom: 4px; }
    .description-arrow { width: 16px; height: 16px; color: #2563EB; transition: transform 0.2s; display: inline-block; }
    .description-toggle:hover .description-arrow { transform: translateY(3px); }

    /* --- MODERN TABS (Posted/Applied) --- */
    .modern-tabs-wrapper {
        display: inline-flex; background: #F1F5F9; padding: 4px; border-radius: 16px;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.03);
    }
    @media (max-width: 768px) {
        .modern-tabs-wrapper { width: 100%; }
    }
    .modern-tab {
        padding: 10px 28px; font-size: 14px; font-weight: 600; color: #64748B;
        border-radius: 12px; transition: all 0.3s ease; text-decoration: none;
    }
    @media (max-width: 768px) {
        .modern-tab { flex: 1; text-align: center; padding: 10px 8px; font-size: 13px; }
    }
    .modern-tab.active {
        background: #FFFFFF; color: #2563EB; font-weight: 700;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    /* --- CONTROLS BAR (Search & Status) --- */
    .controls-bar {
        background: #FFFFFF; border-radius: 20px; padding: 16px; border: 1px solid #E5E7EB;
        display: flex; justify-content: space-between; align-items: center;
        flex-wrap: wrap; gap: 12px; margin-bottom: 32px;
    }
    @media (max-width: 768px) {
        .controls-bar { padding: 12px; border-radius: 16px; margin-bottom: 24px; justify-content: center; }
    }

    .modern-search-wrapper { position: relative; flex: 1; max-width: 380px; }
    .modern-search-input {
        width: 100%; background: #F8FAFC; border: 1px solid #E2E8F0;
        padding: 12px 16px 12px 44px; border-radius: 12px; font-size: 14px; transition: all 0.2s;
    }
    .modern-search-input:focus { background: #FFFFFF; border-color: #3B82F6; box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1); outline: none; }

    .modern-filter-group { display: flex; gap: 4px; background: #F8FAFC; padding: 4px; border-radius: 12px; border: 1px solid #F1F5F9; }
    @media (max-width: 768px) {
        .modern-filter-group { width: 100%; justify-content: space-between; }
    }
    .filter-btn { padding: 8px 16px; font-size: 13px; font-weight: 600; color: #64748B; border-radius: 8px; transition: all 0.2s; text-decoration: none; }
    @media (max-width: 768px) {
        .filter-btn { flex: 1; text-align: center; padding: 8px 12px; font-size: 12px; }
    }
    .filter-btn.active { background: #FFFFFF; color: #2563EB; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }

    /* --- Modal & Overlays --- */
    /* --- Modal & Overlays --- */
    .task-details-modal {
        position: fixed; inset: 0; display: flex; justify-content: center; align-items: flex-start;
        padding: 60px 0; opacity: 0; pointer-events: none; transition: opacity 0.2s; z-index: 100; overflow-y: auto;
    }
    .task-details-modal.show { opacity: 1; pointer-events: auto; }
    .task-details-backdrop { position: fixed; inset: 0; background: rgba(15, 23, 42, 0.55); }
    .task-details-panel {
        position: relative; background: #FFFFFF; border-radius: 24px; padding: 32px 36px;
        max-width: 640px; width: 90%; margin: auto; box-shadow: 0 24px 60px rgba(15, 23, 42, 0.35);
    }

    .task-details-close {
        position: absolute; top: 16px; left: 16px;
        width: 36px; height: 36px; border-radius: 50%;
        background: #FFFFFF; border: 1px solid #E5E7EB; color: #6B7280;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; z-index: 50; transition: all 0.2s;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    .task-details-close:hover { background: #F9FAFB; color: #111827; transform: rotate(90deg); }

    @media (max-width: 640px) {
        .task-details-modal { padding: 20px 0; }
        .task-details-panel { padding: 48px 20px 24px; width: 95%; border-radius: 20px; }
        .task-details-close { top: 12px; left: 12px; }
    }

    /* Modern Empty State */
    .modern-empty-state {
        text-align: center; padding: 80px 24px; background: #FFFFFF;
        border-radius: 32px; border: 2px dashed #E5E7EB;
        display: flex; flex-direction: column; align-items: center; justify-content: center;
    }

    .empty-illustration {
        width: 120px; height: 120px; background: linear-gradient(135deg, #F0F9FF 0%, #DBEAFE 100%);
        border-radius: 50%; display: flex; align-items: center; justify-content: center;
        margin-bottom: 24px; box-shadow: 0 10px 30px -10px rgba(59, 130, 246, 0.3); position: relative;
    }
    
    .empty-illustration i { color: #3B82F6; filter: drop-shadow(0 4px 6px rgba(59, 130, 246, 0.2)); }
    .empty-illustration::before {
        content: ''; position: absolute; inset: -12px; border-radius: 50%;
        border: 2px dashed #E0F2FE; animation: spin 30s linear infinite;
    }
    
    @keyframes spin { from {transform: rotate(0deg);} to {transform: rotate(360deg);} }

    .empty-title { font-size: 24px; font-weight: 800; color: #1E293B; margin-bottom: 12px; }
    .empty-desc { color: #64748B; font-size: 16px; max-width: 400px; line-height: 1.6; margin-bottom: 32px; }

    .cta-button {
        background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%);
        color: white; padding: 14px 32px; border-radius: 14px;
        font-weight: 700; font-size: 15px; display: inline-flex;
        align-items: center; gap: 8px; transition: all 0.2s;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25); text-decoration: none;
    }
    
    .cta-button:hover {
        transform: translateY(-2px); box-shadow: 0 8px 16px rgba(37, 99, 235, 0.3); color: white;
    }

    /* --- Other Tasks --- */
    .other-tasks-container { margin-top: 50px; }
    .compact-task-row {
        background: white; padding: 18px 24px; border-radius: 16px; border: 1px solid #E5E7EB;
        display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;
        transition: all 0.2s; cursor: pointer; text-decoration: none; color: inherit;
    }
    .compact-task-row:hover { border-color: #3B82F6; transform: translateX(4px); box-shadow: 0 4px 12px rgba(0,0,0,0.03); }

    @media (max-width: 768px) {
        .compact-task-row { padding: 14px 16px; }
        .compact-task-row .text-base { font-size: 14px; }
    }
</style>

@section('content')

<section class="min-h-screen">
    <div class="main-wrapper">
        
        @php
            $tasksCollection = $tasks ?? collect([]);
            $hasTasks = $tasksCollection->count() > 0;
            
            if ($hasTasks) {
                // Check if a specific task ID was clicked via URL query
                $requestedTaskId = request('task_id');
                
                if ($requestedTaskId && $tasksCollection->contains('id', $requestedTaskId)) {
                    $activeTask = $tasksCollection->where('id', $requestedTaskId)->first();
                    $otherTasks = $tasksCollection->where('id', '!=', $requestedTaskId)->values();
                } else {
                    $activeTask = $tasksCollection->first();
                    $otherTasks = $tasksCollection->slice(1)->values();
                }
                
                $offerCount = $activeTask->offers_count ?? 0; 
                $hasOffers = $offerCount > 0;
                $viewCount = $activeTask->views ?? 0;
                
                // Offers only visible if task is Open AND we are the employer (default view)
                // If applying, we might want different logic?
                // Actually, if I am the employee, I shouldn't see OTHER people's offers details, 
                // but maybe just my own? For now, 'mytasks' relies on showing offers list.
                // We'll hide the generic offers list if we are in 'applied' mode, and show OUR offer details.
                
                $showOffers = $activeTask->status === 'open' && ($viewMode ?? 'posted') === 'posted';
            }
        @endphp

        {{-- MAIN TABS (Context Switcher) --}}
        <div class="flex justify-center mb-8">
            <div class="modern-tabs-wrapper">
                <a href="{{ route('my-tasks', ['view' => 'posted']) }}" 
                   class="modern-tab {{ ($viewMode ?? 'posted') === 'posted' ? 'active' : '' }}">
                   {{ __('mytasks.tabs.posted') }}
                </a>
                <a href="{{ route('my-tasks', ['view' => 'applied']) }}" 
                   class="modern-tab {{ ($viewMode ?? 'posted') === 'applied' ? 'active' : '' }}">
                   {{ __('mytasks.tabs.applied') }}
                </a>
            </div>
        </div>

        {{-- SCENARIO 1: TASKS EXIST --}}
            <!-- Dashboard Header: Search & Filters -->
            <div class="controls-bar">
                <form method="GET" action="{{ route('my-tasks') }}" class="modern-search-wrapper hidden md:block">
                    <i data-feather="search" class="search-icon" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #94a3b8; width: 18px; height: 18px;"></i>
                    <input name="q" value="{{ $filters['q'] ?? '' }}" type="text" placeholder="{{ __('mytasks.search_placeholder') }}" class="modern-search-input" autocomplete="off">
                    <input type="hidden" name="status" value="{{ $filters['status'] ?? 'posted' }}">
                    <input type="hidden" name="view" value="{{ $viewMode ?? 'posted' }}">
                </form>

                <div class="modern-filter-group">
                    <a href="{{ route('my-tasks', array_merge(request()->query(), ['status' => 'posted'])) }}" class="filter-btn {{ ($filters['status'] ?? 'posted') === 'posted' ? 'active' : '' }}">{{ __('mytasks.filters.posted') }}</a>
                    <a href="{{ route('my-tasks', array_merge(request()->query(), ['status' => 'pending'])) }}" class="filter-btn {{ ($filters['status'] ?? '') === 'pending' ? 'active' : '' }}">{{ __('mytasks.filters.pending') }}</a>
                    <a href="{{ route('my-tasks', array_merge(request()->query(), ['status' => 'completed'])) }}" class="filter-btn {{ ($filters['status'] ?? '') === 'completed' ? 'active' : '' }}">{{ __('mytasks.filters.completed') }}</a>
                </div>
            </div>
        @if($hasTasks)
            
            <div class="task-hero">
                
                {{-- LEFT SIDE (Dynamic Content) --}}
                <div class="hero-left">
                    {{-- APPLIED VIEW LOGIC --}}
                    @if(($viewMode ?? 'posted') === 'applied')
                        @php
                            // Find my offer for this task
                            $myOffer = $activeTask->offers->where('user_id', auth()->id())->first();
                        @endphp

                        @if($activeTask->status === 'pending')
                            @if($activeTask->employee_id == auth()->id())
                                <div class="status-badge active" style="color:#059669;">
                                    <span class="status-dot" style="background-color:#10B981;"></span> {{ __('mytasks.status.offer_accepted') }}
                                </div>
                                <h1 class="hero-headline">{{ __('mytasks.status.youre_hired') }}</h1>
                                <p class="hero-subtext">
                                    {{ __('mytasks.status.hired_desc') }}
                                </p>
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
                                <p class="hero-subtext">
                                    {{ __('mytasks.status.assigned_desc') }}
                                </p>
                                <div class="mt-6">
                                    <a href="{{ route('tasks') }}" class="text-blue-600 font-bold hover:underline">{{ __('mytasks.status.browse_more') }}</a>
                                </div>
                            @endif

                        @elseif($activeTask->status === 'completed')
                            @if($activeTask->employee_id == auth()->id())
                                <div class="status-badge active">
                                    <span class="status-dot"></span> {{ __('mytasks.status.job_done') }}
                                </div>
                                <h1 class="hero-headline">{{ __('mytasks.status.task_completed') }}</h1>
                                <p class="hero-subtext">
                                    {{ __('mytasks.status.completed_desc') }}
                                </p>
                            @else
                                <div class="status-badge">
                                    <span class="status-dot"></span> {{ __('mytasks.status.closed') }}
                                </div>
                                <h1 class="hero-headline">{{ __('mytasks.status.task_completed') }}</h1>
                                <p class="hero-subtext">
                                    {{ __('mytasks.status.other_completed_desc') }}
                                </p>
                            @endif

                        @else
                            {{-- OPEN STATUS --}}
                            <div class="status-badge" style="color:#2563EB;">
                                <span class="status-dot" style="background-color:#3B82F6;"></span> {{ __('mytasks.status.application_sent') }}
                            </div>
                            <h1 class="hero-headline">{{ __('mytasks.status.waiting_response') }}</h1>
                            <p class="hero-subtext">
                                {{ __('mytasks.status.you_offered') }} <strong>£{{ number_format($myOffer->price ?? 0, 0) }}</strong>.
                                {{ __('mytasks.status.notify_offers') }}
                            </p>
                            <div class="bg-blue-50 border border-blue-100 p-4 rounded-xl mt-6">
                                <h4 class="font-bold text-blue-900 text-sm mb-1">{{ __('mytasks.status.your_message') }}</h4>
                                <p class="text-blue-700 text-sm italic">"{{ $myOffer->message ?? '' }}"</p>
                            </div>
                        @endif

                    {{-- POSTED VIEW LOGIC (Existing) --}}
                    @elseif($activeTask->status === 'pending')
                         <div class="status-badge" style="color: #F59E0B;">
                            <span class="status-dot" style="background-color: #FBBF24;"></span> {{ __('mytasks.status.in_progress') }}
                        </div>
                        <h1 class="hero-headline">{{ __('mytasks.status.task_underway') }}</h1>
                        <p class="hero-subtext">
                            {{ __('mytasks.status.underway_desc') }}
                        </p>
                        
                        {{-- Trigger Completion Modal --}}
                        <div class="mt-6">
                            <button type="button" onclick="openCompleteTaskModal()" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-full shadow-lg transition transform hover:-translate-y-1 flex items-center gap-2">
                                <i data-feather="check-circle" class="w-5 h-5"></i> {{ __('mytasks.status.mark_completed') }}
                            </button>
                        </div>
                    
                    @elseif($activeTask->status === 'completed')
                        <div class="status-badge active">
                            <span class="status-dot"></span> {{ __('mytasks.filters.completed') }}
                        </div>
                        <h1 class="hero-headline">{{ __('mytasks.status.task_completed') }}</h1>
                        <p class="hero-subtext">
                            {{ __('mytasks.status.posted_completed_desc') }}
                        </p>
                    
                    @elseif($hasOffers)
                        <div class="status-badge active">
                            <span class="status-dot"></span> {{ __('mytasks.status.new_activity') }}
                        </div>
                        <h1 class="hero-headline">{{ __('mytasks.status.new_offers') }}</h1>
                        <p class="hero-subtext">
                            {{ __('mytasks.status.offers_desc', ['count' => $offerCount]) }}.
                        </p>
                    @else
                        <div class="status-badge">
                            <span class="status-dot"></span> {{ __('mytasks.status.task_posted') }}
                        </div>
                        <h1 class="hero-headline">{{ __('mytasks.status.find_taskers') }}</h1>
                        <p class="hero-subtext">
                            {{ __('mytasks.status.notify_offers') }}
                        </p>
                    @endif


                        <div class="offers-container mt-6">
                        @if(($viewMode ?? 'posted') === 'posted' && $activeTask->status === 'open')
                            <div class="offers-header mb-4">
                                <div class="illustration-box">
                                    <div class="bg-blue-100 p-3 rounded-full inline-block">
                                        <i data-feather="users" class="text-blue-600 w-8 h-8"></i>
                                    </div>
                                </div>
                                @if($hasOffers)
                                    <h3 class="text-lg font-bold text-gray-800">{{ __('mytasks.offers.header_count', ['count' => $offerCount]) }}</h3>
                                    <p class="questions-copy mt-2">
                                        {{ __('mytasks.offers.header_desc') }}
                                    </p>
                                @else
                                    <h3 class="text-lg font-bold text-gray-800">{{ __('mytasks.offers.waiting') }}</h3>
                                    <p class="questions-copy mt-2">
                                        {{ __('mytasks.offers.waiting_desc') }}
                                    </p>
                                @endif
                            </div>
                        @endif

                        <div class="offers-list overflow-y-auto custom-scrollbar" style="max-height: 120px; padding-right: 8px;">
                            @if($hasOffers && $showOffers)
                                <div class="space-y-3">
                                    @foreach($activeTask->offers as $offer)
                                        <div onclick="openOfferModal({
                                            id: '{{ $offer->id }}',
                                            userId: '{{ $offer->user_id }}',
                                            initials: '{{ substr($offer->user->first_name ?? 'T', 0, 1) }}',
                                            avatarUrl: '{{ $offer->user->avatar_url }}',
                                            name: '{{ $offer->user->first_name ?? 'Tasker' }} {{ $offer->user->last_name ?? '' }}',
                                            rating: '{{ $offer->user->rating }}',
                                            time: '{{ $offer->created_at?->diffForHumans(null, true, true) }}',
                                            price: '{{ number_format($offer->price, 0) }}',
                                            message: `{{ addslashes($offer->message) }}`
                                        })" class="group w-full p-3 rounded-xl border border-gray-200 bg-white hover:border-blue-400 hover:shadow-sm transition-all cursor-pointer relative overflow-hidden">
                                            {{-- Hover accent --}}
                                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-blue-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>

                                            <div class="flex items-start gap-3">
                                                {{-- Avatar --}}
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
                                                                £{{ number_format($offer->price, 0) }}
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
                            @elseif(!$showOffers && $hasTasks)
                                 <div class="text-center py-6">
                                    <div class="bg-gray-100 p-2 rounded-full inline-block mb-2">
                                        <i data-feather="check" class="text-gray-400 w-5 h-5"></i>
                                    </div>
                                     <p class="text-sm text-gray-500">{{ __('mytasks.offers.hidden', ['status' => $activeTask->status]) }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="view-count">
                        <i data-feather="eye" style="width:14px;"></i> {{ $viewCount }} {{ __('mytasks.stats.views') }}
                    </div>
                </div>

                {{-- RIGHT SIDE (Task Details) --}}
                <div class="hero-right">
                    
                    {{-- Floating More Options --}}
                    @if(($viewMode ?? 'posted') === 'posted')
                        <div class="more-options-container">
                            <button class="more-btn" id="more-btn">
                                <i data-feather="more-horizontal"></i>
                            </button>
                            <div class="custom-dropdown" id="more-menu">
                                <a href="#" class="dropdown-item">
                                    <i data-feather="edit-2" style="width:16px;"></i> {{ __('mytasks.actions.edit') }}
                                </a>
                                <a href="{{ url('howitworks') }}" class="dropdown-item">
                                    <i data-feather="info" style="width:16px;"></i> {{ __('mytasks.actions.howitworks') }}
                                </a>
                                <div style="height:1px; background:#F3F4F6; margin:6px 0;"></div>
                                <button class="dropdown-item danger w-full text-left">
                                    <i data-feather="x-circle" style="width:16px;"></i> {{ __('mytasks.actions.cancel') }}
                                </button>
                            </div>
                        </div>
                    @endif

                    <div>
                        <span class="task-label">{{ __('mytasks.details.status_label', ['status' => ucfirst($activeTask->status)]) }}</span>
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
                        
                        {{-- 
                           STRICT 50 CHAR LIMIT LOGIC:
                           Using Blade Str::limit to physically cut the text at 50 characters.
                        --}}
                        @if(!empty($activeTask->description))
                            <button type="button" class="description-toggle" onclick="openTaskDetailsModal()" title="Click to read full description">
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
                                <p>{{ __('mytasks.details.flexible') }}</p>
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
                                <p>{{ optional($activeTask->category)->name ?? 'General' }}</p>
                            </div>
                        </div>
                        <div class="data-row">
                            <div class="data-icon"><i data-feather="file-text"></i></div>
                            <div class="data-text">
                                <h4>{{ __('mytasks.details.job') }}</h4>
                                <p>{{ optional($activeTask->job)->name ?? ('Job #'.$activeTask->job_id) }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- MODAL FOR FULL DESCRIPTION (Variable height) --}}
                    <div id="task-details-modal" class="task-details-modal">
                        <div id="task-details-backdrop" class="task-details-backdrop"></div>
                        <div class="task-details-panel">
                            <button type="button" class="task-details-close" onclick="closeTaskDetailsModal()">
                                <i data-feather="x" style="width:16px; height:16px;"></i>
                            </button>
                            <div class="task-details-title">{{ $activeTask->title }}</div>
                            @if(!empty($activeTask->description))
                                {{-- Full Description Here --}}
                                <div class="task-details-body">{{ $activeTask->description }}</div>
                            @endif
                            <div class="task-details-actions">
                                <button type="button" class="ghost" onclick="closeTaskDetailsModal()">{{ __('mytasks.actions.close') }}</button>
                                <a href="#" class="primary">{{ __('mytasks.actions.edit') }}</a>
                            </div>
                        </div>
                    </div>

                    {{-- MODAL FOR COMPLETE & REVIEW --}}
                    <div id="complete-task-modal" class="task-details-modal">
                        <div class="task-details-backdrop" onclick="closeCompleteTaskModal()"></div>
                        <div class="task-details-panel" style="max-width: 450px;">
                            <button type="button" class="task-details-close" onclick="closeCompleteTaskModal()">
                                <i data-feather="x" style="width:16px; height:16px;"></i>
                            </button>
                            
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ __('mytasks.status.task_completed') }}</h3>
                            <p class="text-gray-500 mb-6">{{ __('Would you like to leave a review for the Tasker?') }}</p>
                            
                            {{-- Initial Choice Buttons --}}
                            <div id="complete-choice-buttons" class="grid grid-cols-1 gap-3">
                                <button onclick="showReviewForm()" class="h-14 w-full flex items-center justify-center gap-2 px-4 rounded-xl bg-blue-600 text-white font-bold hover:bg-blue-700 transition">
                                    <i data-feather="star" class="w-4 h-4"></i> {{ __('Yes, leave a review') }}
                                </button>
                                <form action="{{ route('advertisements.complete', $activeTask->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="h-14 w-full flex items-center justify-center gap-2 px-4 rounded-xl bg-gray-100 text-gray-600 font-bold hover:bg-gray-200 transition">
                                        {{ __('No, just complete task') }}
                                    </button>
                                </form>
                            </div>

                            {{-- Review Form (Hidden initially) --}}
                            <div id="complete-review-form" class="hidden">
                                <form action="{{ route('advertisements.complete', $activeTask->id) }}" method="POST">
                                    @csrf
                                    
                                    <div class="mb-4">
                                        <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('Rating') }}</label>
                                        <div class="flex gap-2 text-2xl" id="star-rating-input">
                                            @for($i=1; $i<=5; $i++)
                                                <i data-feather="star" class="cursor-pointer text-gray-300 hover:text-yellow-400 peer-checked:text-yellow-400" onclick="setRating({{ $i }})" id="star-{{ $i }}"></i>
                                            @endfor
                                        </div>
                                        <input type="hidden" name="stars" id="rating-value" required>
                                    </div>
                                    
                                    <div class="mb-6">
                                        <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('Comment') }}</label>
                                        <textarea name="comment" rows="3" class="w-full border border-gray-300 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="{{ __('Write a short review...') }}"></textarea>
                                    </div>
                                    
                                    <button type="submit" class="h-14 w-full flex items-center justify-center gap-2 px-4 rounded-xl bg-blue-600 text-white font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-200">
                                        {{ __('Complete & Review') }}
                                    </button>
                                </form>
                            </div>

                        </div>
                    </div>

                    {{-- MODAL FOR OFFER DETAILS --}}
                    <div id="offer-details-modal" class="task-details-modal" style="z-index: 60;">
                        <div class="task-details-backdrop" onclick="closeOfferModal()"></div>
                        <div class="task-details-panel" style="max-width: 500px;">
                            <button type="button" class="task-details-close" onclick="closeOfferModal()">
                                <i data-feather="x" style="width:16px; height:16px;"></i>
                            </button>
                            
                            {{-- Offer Header --}}
                            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
                                <a id="modal-profile-link" href="#" class="flex items-center gap-3 group text-decoration-none">
                                    <div id="modal-offer-avatar" class="w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 font-bold text-xl sm:text-2xl border border-gray-200 group-hover:border-blue-400 transition-colors shrink-0">
                                        <!-- Initials via JS -->
                                    </div>
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
                                    <div class="text-[10px] text-gray-400 uppercase font-bold sm:mt-1 order-1 sm:order-2">{{ __('Offer Price') }}</div>
                                </div>
                            </div>

                            {{-- Offer Body --}}
                            <div class="bg-gray-50 rounded-2xl p-4 sm:p-5 mb-6 border border-gray-100">
                                <h4 class="text-[10px] sm:text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">{{ __('Message from Tasker') }}</h4>
                                <p id="modal-offer-message" class="text-gray-700 text-sm leading-relaxed whitespace-pre-wrap break-words" style="overflow-wrap: break-word; word-break: break-word;"></p>
                            </div>

                            {{-- Actions --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <a id="message-tasker-btn" href="#" class="h-14 w-full flex items-center justify-center gap-2 px-4 rounded-xl bg-white border border-gray-200 text-gray-700 font-bold hover:bg-gray-50 transition text-center no-underline">
                                    <i data-feather="message-circle" class="w-4 h-4"></i> {{ __('Message') }}
                                </a>
                                
                                <form id="accept-offer-form" method="POST" action="" class="h-14">
                                    @csrf
                                    @if($activeTask->status === 'open')
                                        <button type="submit" class="h-full w-full flex items-center justify-center gap-2 px-4 rounded-xl bg-blue-600 border border-blue-600 text-white font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-200">
                                            <i data-feather="check" class="w-4 h-4"></i> {{ __('Accept Offer') }}
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

                </div>
            </div>

            {{-- 5. OTHER TASKS LIST (Swaps Active Task In-Place) --}}
            @if($otherTasks->count() > 0)
                <div class="other-tasks-container">
                    <h3 class="text-xl font-bold text-gray-800 mb-4 px-2">{{ __('Other Tasks') }}</h3>
                    @foreach($otherTasks as $task)
                        <a href="{{ request()->fullUrlWithQuery(['task_id' => $task->id]) }}" class="compact-task-row">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-500">
                                    <i data-feather="clipboard" style="width:18px;"></i>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-400 font-bold uppercase">{{ $task->status ?? 'Posted' }}</div>
                                    <span class="text-base font-bold text-gray-800 hover:text-blue-600 transition">{{ $task->title }}</span>
                                </div>
                            </div>
                            <div class="font-bold text-gray-600">€{{ number_format($task->price, 0) }}</div>
                        </a>
                    @endforeach
                </div>
            @endif

        {{-- SCENARIO 2: NO TASKS (PRESERVED) --}}
        @else
            <!-- Dashboard Header: Search & Filters (Always show to allow filtering even if empty, or just for consistency/reset) -->


            <!-- Modern Empty State -->
            <div class="modern-empty-state">
                <div class="empty-illustration">
                    <i data-feather="clipboard" style="width:48px; height:48px;"></i>
                </div>
                
                <h3 class="empty-title">
                     @if(($filters['status'] ?? 'posted') === 'posted' && empty($filters['q']))
                        {{ __('No tasks yet') }}
                    @elseif(($viewMode ?? 'posted') === 'applied')
                        {{ __('No applications found') }}
                    @else
                        {{ __('No tasks found') }}
                    @endif
                </h3>
                
                <p class="empty-desc">
                    @if(($viewMode ?? 'posted') === 'applied')
                        {{ __("You haven't applied to any tasks in this category yet. Browse available tasks to get started.") }}
                    @else
                        @if(($filters['status'] ?? 'posted') === 'posted')
                             @if(!empty($filters['q']))
                                {{ __("We couldn't find any tasks matching your search.") }}
                             @else
                                {{ __("Put your task in front of thousands of people and get it done quickly.") }}
                             @endif
                        @else
                            {{ __("No tasks found with this status.") }}
                        @endif
                    @endif
                </p>
                
                @if(($viewMode ?? 'posted') === 'applied')
                    <a href="{{ route('tasks') }}" class="cta-button">
                        <i data-feather="search" style="width:18px;"></i> {{ __('Browse Tasks') }}
                    </a>
                @else
                    <a href="{{ route('post-task') }}" class="cta-button">
                        <i data-feather="plus" style="width:18px;"></i> {{ __('Post a New Task') }}
                    </a>
                @endif
            </div>
        @endif

    </div>
</section>

<!-- Feather Icons & Logic -->
<script src="https://unpkg.com/feather-icons"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (window.feather) window.feather.replace();

        // More Options Toggle
        const moreBtn = document.getElementById('more-btn');
        const moreMenu = document.getElementById('more-menu');
        
        if (moreBtn && moreMenu) {
            moreBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                moreMenu.classList.toggle('show');
            });
            document.addEventListener('click', () => moreMenu.classList.remove('show'));
        }

        // Task details modal logic
        window.openTaskDetailsModal = function () {
            const modal = document.getElementById('task-details-modal');
            if (modal) {
                modal.classList.add('show');
                document.body.style.overflow = 'hidden'; 
            }
        };

        window.closeTaskDetailsModal = function () {
            const modal = document.getElementById('task-details-modal');
            if (modal) {
                modal.classList.remove('show');
                document.body.style.overflow = ''; 
            }
        };

        const backdrop = document.getElementById('task-details-backdrop');
        if (backdrop) {
            backdrop.addEventListener('click', () => window.closeTaskDetailsModal());
        }

        // --- OFFER MODAL LOGIC ---
        window.openOfferModal = function(data) {
            const modal = document.getElementById('offer-details-modal');
            if(!modal) return;

            // Populate Data
            const avatarEl = document.getElementById('modal-offer-avatar');
            if (data.avatarUrl) {
                avatarEl.innerHTML = `<img src="${data.avatarUrl}" class="w-full h-full rounded-full object-cover">`;
            } else {
                avatarEl.innerHTML = data.initials;
            }
            
            document.getElementById('modal-offer-name').innerText = data.name;
            document.getElementById('modal-offer-rating').innerText = data.rating;
            document.getElementById('modal-offer-time').innerText = data.time + ' {{ __("mytasks.stats.ago") }}';
            document.getElementById('modal-offer-price').innerText = '£' + data.price;
            document.getElementById('modal-offer-message').innerText = data.message;
            
            // Set Profile Link
            const profileLink = document.getElementById('modal-profile-link');
            if (profileLink) {
                profileLink.href = '/profile/' + data.userId;
            }

            // Set Form Action
            const form = document.getElementById('accept-offer-form');
            if(form) {
                form.action = `/offers/${data.id}/accept`;
            }

            // Set Message Button Link
            const msgBtn = document.getElementById('message-tasker-btn');
            if(msgBtn) {
                msgBtn.href = `/messages?user_id=${data.userId}`;
            }

            // Show Modal
            modal.classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        window.closeOfferModal = function() {
            const modal = document.getElementById('offer-details-modal');
            if(modal) {
                modal.classList.remove('show');
                document.body.style.overflow = '';
            }
        }

        // --- COMPLETE MODAL LOGIC ---
        window.openCompleteTaskModal = function() {
            const modal = document.getElementById('complete-task-modal');
            if(modal) {
                modal.classList.add('show');
                document.body.style.overflow = 'hidden';
                // Reset view
                document.getElementById('complete-choice-buttons').classList.remove('hidden');
                document.getElementById('complete-review-form').classList.add('hidden');
                // Reset rating stars
                setRating(0);
            }
        }

        window.closeCompleteTaskModal = function() {
            const modal = document.getElementById('complete-task-modal');
            if(modal) {
                modal.classList.remove('show');
                document.body.style.overflow = '';
            }
        }
        
        window.showReviewForm = function() {
            document.getElementById('complete-choice-buttons').classList.add('hidden');
            document.getElementById('complete-review-form').classList.remove('hidden');
        }

        window.setRating = function(value) {
            document.getElementById('rating-value').value = value;
            for(let i=1; i<=5; i++) {
                const icon = document.getElementById('star-'+i);
                if(i <= value) {
                    icon.classList.add('text-yellow-400');
                    icon.classList.remove('text-gray-300');
                    icon.style.fill = 'currentColor'; // solid star
                } else {
                    icon.classList.remove('text-yellow-400');
                    icon.classList.add('text-gray-300');
                    icon.style.fill = 'none';
                }
            }
            if(window.feather) window.feather.replace();
        }

    });
</script>
@endsection
