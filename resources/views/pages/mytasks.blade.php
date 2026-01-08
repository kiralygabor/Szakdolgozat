@extends('layout')

@section('title', 'My Tasks')

{{-- 1. STYLES --}}
<style>
    /* --- General --- */
    body { background-color: #F3F4F6; font-family: 'Inter', sans-serif; }

    .main-wrapper { 
        max-width: 1200px; 
        margin: 0 auto; 
        padding: 40px 20px; 
        min-height: 85vh;
        display: flex; 
        flex-direction: column; 
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

    /* --- LEFT SIDE (Interactive Hub) --- */
    .hero-left {
        flex: 1.3;
        padding: 60px;
        display: flex;
        flex-direction: column;
        position: relative;
        background-color: #FFFFFF;
    }

    /* Dynamic Status Header */
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

    /* Custom Scrollbar */
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #E5E7EB; border-radius: 20px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background-color: #D1D5DB; }

    .offers-header { text-align: center; display: flex; flex-direction: column; align-items: center; }
    .illustration-box { margin-bottom: 8px; }
    .offers-header .questions-copy { margin-top: 6px !important; }
    .questions-copy { font-size: 14px; color: #6B7280; max-width: 360px; }

    /* --- RIGHT SIDE (Task Details) --- */
    .hero-right {
        flex: 1.2;
        background-color: #F7F2EB;
        padding: 50px;
        color: #111827;
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
    }

    /* Floating More Options */
    .more-options-container { position: absolute; top: 30px; right: 30px; z-index: 20; }
    .more-btn {
        width: 44px; height: 44px; border-radius: 50%;
        background: #EEF2FF;
        border: 1px solid #E5E7EB;
        color: #4B5563; display: flex; align-items: center; justify-content: center;
        cursor: pointer; transition: all 0.2s;
    }
    .more-btn:hover { background: #E0E7FF; transform: rotate(90deg); }

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
        transition: background 0.1s;
    }
    .dropdown-item:hover { background: #F3F4F6; color: #111827; }
    .dropdown-item.danger { color: #EF4444; }
    .dropdown-item.danger:hover { background: #FEF2F2; }

    /* Task Info */
    .task-label { color: #6B7280; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 12px; display: block; }
    .task-main-title { font-size: 32px; font-weight: 800; line-height: 1.2; margin-bottom: 10px; color: #111827; }
    
    .price-display { font-size: 36px; font-weight: 700; color: #2563EB; margin: 24px 0; letter-spacing: -1px; }

    /* Data Points */
    .data-row { display: flex; gap: 15px; margin-bottom: 20px; align-items: flex-start; }
    .data-icon { width: 40px; height: 40px; background: #E5E7EB; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #4B5563; flex-shrink: 0; }
    .data-text h4 { font-size: 12px; color: #6B7280; text-transform: uppercase; font-weight: 700; margin-bottom: 2px; }
    .data-text p { font-size: 15px; font-weight: 500; color: #111827; }

    /* --- DESCRIPTION STYLES --- */
    .description-toggle {
        margin-top: 18px;
        padding: 0;
        background: transparent;
        border: none;
        cursor: pointer;
        display: block; 
        width: 100%;
        text-align: left;
    }

    .task-description-truncated {
        font-size: 14px;
        color: #1F2933;
        line-height: 1.6em;
        margin-bottom: 4px;
        /* Ensures the container stays rigid even with short text */
        min-height: 1.6em; 
    }

    .description-arrow {
        width: 16px;
        height: 16px;
        color: #2563EB;
        transition: transform 0.2s;
        display: inline-block;
    }
    
    .description-toggle:hover .description-arrow {
        transform: translateY(3px);
    }

    .hero-right-details { margin-top: 28px; }

    /* --- MODAL UPDATED (NO INTERNAL SCROLL, FULL PAGE SCROLL) --- */
    .task-details-modal {
        position: fixed; 
        inset: 0; 
        display: flex; 
        justify-content: center;
        align-items: flex-start; /* Allows vertical growth */
        padding-top: 60px;
        padding-bottom: 60px;
        opacity: 0; 
        pointer-events: none; 
        transition: opacity 0.2s ease; 
        z-index: 50;
        overflow-y: auto; /* The overlay scrolls, not the inner box */
    }

    .task-details-modal.show { opacity: 1; pointer-events: auto; }
    
    .task-details-backdrop { 
        position: fixed; 
        inset: 0; 
        background-color: rgba(15, 23, 42, 0.55); 
    }
    
    .task-details-panel {
        position: relative; 
        background-color: #FFFFFF; 
        border-radius: 24px; 
        padding: 32px 36px;
        max-width: 640px; 
        width: 90%; 
        height: auto; /* Grows with content */
        box-shadow: 0 24px 60px rgba(15, 23, 42, 0.35);
        margin: auto; 
    }

    .task-details-title { font-size: 24px; font-weight: 800; margin-bottom: 12px; color: #111827; padding-right: 32px; }
    
    .task-details-body { 
        font-size: 15px; 
        color: #4B5563; 
        line-height: 1.7; 
        margin-bottom: 24px; 
        white-space: pre-wrap;
        overflow-wrap: break-word;
    }
    
    .task-details-actions { display: flex; justify-content: flex-end; gap: 12px; }
    .task-details-actions a, .task-details-actions button { border-radius: 999px; font-size: 14px; padding: 10px 20px; font-weight: 600; }
    .task-details-actions .primary { background-color: #2563EB; color: #FFFFFF; border: none; cursor: pointer; text-decoration: none; }
    .task-details-actions .ghost { background-color: #F3F4F6; color: #374151; border: none; cursor: pointer; }
    
    .task-details-close {
        position: absolute; top: 20px; right: 20px; width: 36px; height: 36px;
        border-radius: 999px; border: none; background-color: #F3F4F6;
        display: flex; align-items: center; justify-content: center; cursor: pointer; color: #6B7280;
    }
    .task-details-close:hover { background-color: #E5E7EB; color: #111827; }

    /* --- Other Tasks --- */
    .other-tasks-container { margin-top: 50px; }
    .compact-task-row {
        background: white; padding: 18px 24px; border-radius: 16px; border: 1px solid #E5E7EB;
        display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;
        transition: all 0.2s; cursor: pointer; text-decoration: none; color: inherit;
    }
    .compact-task-row:hover { border-color: #3B82F6; transform: translateX(4px); box-shadow: 0 4px 12px rgba(0,0,0,0.03); }

    /* Responsive */
    @media(max-width: 900px) {
        .task-hero { flex-direction: column; min-height: auto; }
        .hero-left, .hero-right { padding: 30px; }
    }

    /* --- Modern Aesthetic Updates --- */
    .modern-card {
        background: #FFFFFF;
        border-radius: 20px;
        border: 1px solid #F3F4F6;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        padding: 24px;
        margin-bottom: 24px;
    }

    /* Sleek Tabs */
    .modern-tabs-wrapper {
        display: inline-flex;
        background: #F1F5F9;
        padding: 4px;
        border-radius: 16px;
        position: relative;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.03);
    }
    
    .modern-tab {
        position: relative;
        padding: 10px 28px;
        font-size: 14px;
        font-weight: 600;
        color: #64748B;
        border-radius: 12px;
        transition: all 0.3s ease;
        text-decoration: none;
        z-index: 1;
    }
    
    .modern-tab:hover {
        color: #334155;
    }
    
    .modern-tab.active {
        background: #FFFFFF;
        color: #2563EB;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08), 0 1px 2px rgba(0,0,0,0.05);
        font-weight: 700;
    }

    /* Control Bar (Search & Filter) */
    .controls-bar {
        background: #FFFFFF;
        border-radius: 20px;
        padding: 16px;
        border: 1px solid #E5E7EB;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.03);
        margin-bottom: 32px;
    }

    .modern-search-wrapper {
        position: relative;
        flex: 1;
        max-width: 380px;
    }

    .modern-search-input {
        width: 100%;
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        padding: 12px 16px 12px 44px;
        border-radius: 12px;
        font-size: 14px;
        color: #334155;
        transition: all 0.2s;
    }
    
    .modern-search-input:focus {
        background: #FFFFFF;
        border-color: #3B82F6;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        outline: none;
    }

    .modern-filter-group {
        display: flex;
        gap: 4px;
        background: #F8FAFC;
        padding: 4px;
        border-radius: 12px;
        border: 1px solid #F1F5F9;
    }

    .filter-btn {
        padding: 8px 16px;
        font-size: 13px;
        font-weight: 600;
        color: #64748B;
        border-radius: 8px;
        transition: all 0.2s;
        text-decoration: none;
    }
    
    .filter-btn:hover {
        background: rgba(255,255,255,0.8);
        color: #334155;
    }
    
    .filter-btn.active {
        background: #FFFFFF;
        color: #2563EB;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    /* Modern Empty State */
    .modern-empty-state {
        text-align: center;
        padding: 80px 24px;
        background: #FFFFFF;
        border-radius: 32px;
        border: 2px dashed #E5E7EB;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .empty-illustration {
        width: 120px;
        height: 120px;
        background: linear-gradient(135deg, #F0F9FF 0%, #DBEAFE 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 24px;
        box-shadow: 0 10px 30px -10px rgba(59, 130, 246, 0.3);
        position: relative;
    }
    
    .empty-illustration i {
        color: #3B82F6;
        filter: drop-shadow(0 4px 6px rgba(59, 130, 246, 0.2));
    }

    .empty-illustration::before {
        content: '';
        position: absolute;
        inset: -12px;
        border-radius: 50%;
        border: 2px dashed #E0F2FE;
        animation: spin 30s linear infinite;
    }
    
    @keyframes spin { from {transform: rotate(0deg);} to {transform: rotate(360deg);} }

    .empty-title { font-size: 24px; font-weight: 800; color: #1E293B; margin-bottom: 12px; }
    .empty-desc { color: #64748B; font-size: 16px; max-width: 400px; line-height: 1.6; margin-bottom: 32px; }

    .cta-button {
        background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%);
        color: white;
        padding: 14px 32px;
        border-radius: 14px;
        font-weight: 700;
        font-size: 15px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
        text-decoration: none;
    }
    
    .cta-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(37, 99, 235, 0.3);
        color: white;
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
                   Posted Tasks
                </a>
                <a href="{{ route('my-tasks', ['view' => 'applied']) }}" 
                   class="modern-tab {{ ($viewMode ?? 'posted') === 'applied' ? 'active' : '' }}">
                   My Applications
                </a>
            </div>
        </div>

        {{-- SCENARIO 1: TASKS EXIST --}}
            <!-- Dashboard Header: Search & Filters -->
            <div class="controls-bar">
                <form method="GET" action="{{ route('my-tasks') }}" class="modern-search-wrapper">
                    <i data-feather="search" class="search-icon" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #94a3b8; width: 18px; height: 18px;"></i>
                    <input name="q" value="{{ $filters['q'] ?? '' }}" type="text" placeholder="Search your tasks..." class="modern-search-input" autocomplete="off">
                    <input type="hidden" name="status" value="{{ $filters['status'] ?? 'posted' }}">
                    <input type="hidden" name="view" value="{{ $viewMode ?? 'posted' }}">
                </form>

                <div class="modern-filter-group">
                    <a href="{{ route('my-tasks', array_merge(request()->query(), ['status' => 'posted'])) }}" class="filter-btn {{ ($filters['status'] ?? 'posted') === 'posted' ? 'active' : '' }}">Posted</a>
                    <a href="{{ route('my-tasks', array_merge(request()->query(), ['status' => 'pending'])) }}" class="filter-btn {{ ($filters['status'] ?? '') === 'pending' ? 'active' : '' }}">Pending</a>
                    <a href="{{ route('my-tasks', array_merge(request()->query(), ['status' => 'completed'])) }}" class="filter-btn {{ ($filters['status'] ?? '') === 'completed' ? 'active' : '' }}">Completed</a>
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
                                    <span class="status-dot" style="background-color:#10B981;"></span> Offer Accepted
                                </div>
                                <h1 class="hero-headline">You're Hired!</h1>
                                <p class="hero-subtext">
                                    The employer accepted your offer. Please start working on the task and coordinate details.
                                </p>
                                <div class="mt-6 flex gap-3">
                                    <a href="{{ route('messages', ['user_id' => $activeTask->employer_id]) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-full shadow-lg transition flex items-center gap-2 no-underline">
                                        <i data-feather="message-circle" class="w-5 h-5"></i> Contact Employer
                                    </a>
                                </div>
                            @else
                                <div class="status-badge" style="color:#DC2626;">
                                    <span class="status-dot" style="background-color:#EF4444;"></span> Offer Not Selected
                                </div>
                                <h1 class="hero-headline">Task Assigned</h1>
                                <p class="hero-subtext">
                                    This task was assigned to another Tasker. Don't worry, there are plenty more tasks!
                                </p>
                                <div class="mt-6">
                                    <a href="{{ route('tasks') }}" class="text-blue-600 font-bold hover:underline">Browse more tasks</a>
                                </div>
                            @endif

                        @elseif($activeTask->status === 'completed')
                            @if($activeTask->employee_id == auth()->id())
                                <div class="status-badge active">
                                    <span class="status-dot"></span> Job Done
                                </div>
                                <h1 class="hero-headline">Task Completed</h1>
                                <p class="hero-subtext">
                                    Great work! This task is marked as completed.
                                </p>
                            @else
                                <div class="status-badge">
                                    <span class="status-dot"></span> Closed
                                </div>
                                <h1 class="hero-headline">Task Completed</h1>
                                <p class="hero-subtext">
                                    This task has been completed by another Tasker.
                                </p>
                            @endif

                        @else
                            {{-- OPEN STATUS --}}
                            <div class="status-badge" style="color:#2563EB;">
                                <span class="status-dot" style="background-color:#3B82F6;"></span> Application Sent
                            </div>
                            <h1 class="hero-headline">Waiting for response</h1>
                            <p class="hero-subtext">
                                You offered <strong>£{{ number_format($myOffer->price ?? 0, 0) }}</strong>.
                                We'll let you know if the employer accepts it.
                            </p>
                            <div class="bg-blue-50 border border-blue-100 p-4 rounded-xl mt-6">
                                <h4 class="font-bold text-blue-900 text-sm mb-1">Your Message</h4>
                                <p class="text-blue-700 text-sm italic">"{{ $myOffer->message ?? '' }}"</p>
                            </div>
                        @endif

                    {{-- POSTED VIEW LOGIC (Existing) --}}
                    @elseif($activeTask->status === 'pending')
                         <div class="status-badge" style="color: #F59E0B;">
                            <span class="status-dot" style="background-color: #FBBF24;"></span> In Progress
                        </div>
                        <h1 class="hero-headline">Task is underway</h1>
                        <p class="hero-subtext">
                            You have accepted an offer. Once the work is done to your satisfaction, please mark the task as completed.
                        </p>
                        
                        {{-- Trigger Completion Modal --}}
                        <div class="mt-6">
                            <button type="button" onclick="openCompleteTaskModal()" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-full shadow-lg transition transform hover:-translate-y-1 flex items-center gap-2">
                                <i data-feather="check-circle" class="w-5 h-5"></i> Mark as Completed
                            </button>
                        </div>
                    
                    @elseif($activeTask->status === 'completed')
                        <div class="status-badge active">
                            <span class="status-dot"></span> Completed
                        </div>
                        <h1 class="hero-headline">Task Completed</h1>
                        <p class="hero-subtext">
                            This task has been successfully completed. 
                        </p>
                    
                    @elseif($hasOffers)
                        <div class="status-badge active">
                            <span class="status-dot"></span> New Activity
                        </div>
                        <h1 class="hero-headline">New offers!</h1>
                        <p class="hero-subtext">
                            You have <strong>{{ $offerCount }} offer(s)</strong>.
                            Discuss details with Taskers and accept an offer when you're ready.
                        </p>
                    @else
                        <div class="status-badge">
                            <span class="status-dot"></span> Task Posted
                        </div>
                        <h1 class="hero-headline">Sit tight while we<br>find your Taskers</h1>
                        <p class="hero-subtext">
                            We'll notify you when new offers come in.
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
                                    <h3 class="text-lg font-bold text-gray-800">You have {{ $offerCount }} offer(s).</h3>
                                    <p class="questions-copy mt-2">
                                        Review your offers and choose the Tasker that best matches your needs.
                                    </p>
                                @else
                                    <h3 class="text-lg font-bold text-gray-800">Waiting for offers</h3>
                                    <p class="questions-copy mt-2">
                                        Sit tight while we find Taskers. If you don’t receive offers, consider adjusting your budget or adding more details.
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
                                            avatarUrl: '{{ !empty($offer->user->avatar) ? asset('storage/' . $offer->user->avatar) : '' }}',
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
                                                @if(!empty($offer->user->avatar))
                                                    <img src="{{ asset('storage/' . $offer->user->avatar) }}" alt="Avatar" class="w-10 h-10 rounded-full object-cover border border-gray-200 shrink-0">
                                                @else
                                                    <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 font-bold text-sm shrink-0 border border-gray-200">
                                                        {{ substr($offer->user->first_name ?? 'T', 0, 1) }}
                                                    </div>
                                                @endif

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
                                                                    {{ $offer->created_at?->diffForHumans(null, true, true) }} ago
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
                                    <p class="text-sm text-gray-500">Offers are hidden because this task is {{ $activeTask->status }}.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="view-count">
                        <i data-feather="eye" style="width:14px;"></i> {{ $viewCount }} views
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
                                    <i data-feather="edit-2" style="width:16px;"></i> Edit task
                                </a>
                                <a href="{{ url('howitworks') }}" class="dropdown-item">
                                    <i data-feather="info" style="width:16px;"></i> How it works
                                </a>
                                <div style="height:1px; background:#F3F4F6; margin:6px 0;"></div>
                                <button class="dropdown-item danger w-full text-left">
                                    <i data-feather="x-circle" style="width:16px;"></i> Cancel task
                                </button>
                            </div>
                        </div>
                    @endif

                    <div>
                        <span class="task-label">{{ ucfirst($activeTask->status) }} Task</span>
                        <h2 class="task-main-title">{{ $activeTask->title }}</h2>
                        
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
                                    Read more <i data-feather="chevron-down" class="description-arrow"></i>
                                </div>
                            </button>
                        @endif

                        <div class="price-display">€{{ number_format($activeTask->price, 0) }}</div>
                    </div>

                    <div class="hero-right-details">
                        <div class="data-row">
                            <div class="data-icon"><i data-feather="calendar"></i></div>
                            <div class="data-text">
                                <h4>Due date</h4>
                                <p>Flexible</p>
                            </div>
                        </div>
                        <div class="data-row">
                            <div class="data-icon"><i data-feather="map-pin"></i></div>
                            <div class="data-text">
                                <h4>Location</h4>
                                <p>{{ optional(optional($activeTask->employer)->city)->name ?? 'Remote' }}</p>
                            </div>
                        </div>
                        <div class="data-row">
                            <div class="data-icon"><i data-feather="tag"></i></div>
                            <div class="data-text">
                                <h4>Category</h4>
                                <p>{{ optional($activeTask->category)->name ?? 'General' }}</p>
                            </div>
                        </div>
                        <div class="data-row">
                            <div class="data-icon"><i data-feather="file-text"></i></div>
                            <div class="data-text">
                                <h4>Job</h4>
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
                                <button type="button" class="ghost" onclick="closeTaskDetailsModal()">Close</button>
                                <a href="#" class="primary">Edit task</a>
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
                            
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">Task Completed</h3>
                            <p class="text-gray-500 mb-6">Would you like to leave a review for the Tasker?</p>
                            
                            {{-- Initial Choice Buttons --}}
                            <div id="complete-choice-buttons" class="grid grid-cols-1 gap-3">
                                <button onclick="showReviewForm()" class="h-14 w-full flex items-center justify-center gap-2 px-4 rounded-xl bg-blue-600 text-white font-bold hover:bg-blue-700 transition">
                                    <i data-feather="star" class="w-4 h-4"></i> Yes, leave a review
                                </button>
                                <form action="{{ route('advertisements.complete', $activeTask->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="h-14 w-full flex items-center justify-center gap-2 px-4 rounded-xl bg-gray-100 text-gray-600 font-bold hover:bg-gray-200 transition">
                                        No, just complete task
                                    </button>
                                </form>
                            </div>

                            {{-- Review Form (Hidden initially) --}}
                            <div id="complete-review-form" class="hidden">
                                <form action="{{ route('advertisements.complete', $activeTask->id) }}" method="POST">
                                    @csrf
                                    
                                    <div class="mb-4">
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Rating</label>
                                        <div class="flex gap-2 text-2xl" id="star-rating-input">
                                            @for($i=1; $i<=5; $i++)
                                                <i data-feather="star" class="cursor-pointer text-gray-300 hover:text-yellow-400 peer-checked:text-yellow-400" onclick="setRating({{ $i }})" id="star-{{ $i }}"></i>
                                            @endfor
                                        </div>
                                        <input type="hidden" name="stars" id="rating-value" required>
                                    </div>
                                    
                                    <div class="mb-6">
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Comment</label>
                                        <textarea name="comment" rows="3" class="w-full border border-gray-300 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Write a short review..."></textarea>
                                    </div>
                                    
                                    <button type="submit" class="h-14 w-full flex items-center justify-center gap-2 px-4 rounded-xl bg-blue-600 text-white font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-200">
                                        Complete & Review
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
                            <div class="flex items-center justify-between mb-6 pt-8">
                                <a id="modal-profile-link" href="#" class="flex items-center gap-4 group text-decoration-none">
                                    <div id="modal-offer-avatar" class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 font-bold text-2xl border border-gray-200 group-hover:border-blue-400 transition-colors">
                                        <!-- Initials via JS -->
                                    </div>
                                    <div>
                                        <h3 id="modal-offer-name" class="text-xl font-bold text-gray-900 leading-tight group-hover:text-blue-600 transition-colors"></h3>
                                        <div class="flex items-center gap-1 mt-1">
                                            <i data-feather="star" class="w-4 h-4 text-yellow-400 fill-current"></i>
                                            <span id="modal-offer-rating" class="text-sm font-bold text-gray-800"></span>
                                            <span class="text-gray-300 mx-1">•</span>
                                            <span id="modal-offer-time" class="text-xs text-gray-400 font-medium uppercase tracking-wide"></span>
                                        </div>
                                    </div>
                                </a>
                                <div class="text-right">
                                    <div id="modal-offer-price" class="text-2xl font-bold text-blue-600"></div>
                                    <div class="text-xs text-gray-400 uppercase font-bold mt-1">Offer Price</div>
                                </div>
                            </div>

                            {{-- Offer Body --}}
                            <div class="bg-gray-50 rounded-xl p-4 mb-6 border border-gray-100">
                                <h4 class="text-xs font-bold text-gray-400 uppercase mb-2">Message from Tasker</h4>
                                <p id="modal-offer-message" class="text-gray-700 text-sm leading-relaxed whitespace-pre-wrap break-words" style="overflow-wrap: break-word; word-break: break-word;"></p>
                            </div>

                            {{-- Actions --}}
                            <div class="grid grid-cols-2 gap-3">
                                <a id="message-tasker-btn" href="#" class="h-14 w-full flex items-center justify-center gap-2 px-4 rounded-xl bg-white border border-gray-200 text-gray-700 font-bold hover:bg-gray-50 transition text-center no-underline">
                                    <i data-feather="message-circle" class="w-4 h-4"></i> Message
                                </a>
                                
                                <form id="accept-offer-form" method="POST" action="" class="h-14">
                                    @csrf
                                    @if($activeTask->status === 'open')
                                        <button type="submit" class="h-full w-full flex items-center justify-center gap-2 px-4 rounded-xl bg-blue-600 border border-blue-600 text-white font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-200">
                                            <i data-feather="check" class="w-4 h-4"></i> Accept Offer
                                        </button>
                                    @else
                                        <button type="button" disabled class="h-full w-full flex items-center justify-center gap-2 px-4 rounded-xl bg-gray-200 border border-gray-200 text-gray-400 font-bold cursor-not-allowed">
                                            <i data-feather="check" class="w-4 h-4"></i> Offer Accepted
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
                    <h3 class="text-xl font-bold text-gray-800 mb-4 px-2">Other Tasks</h3>
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
                        No tasks yet
                    @elseif(($viewMode ?? 'posted') === 'applied')
                        No applications found
                    @else
                        No tasks found
                    @endif
                </h3>
                
                <p class="empty-desc">
                    @if(($viewMode ?? 'posted') === 'applied')
                        You haven't applied to any tasks in this category yet. Browse available tasks to get started.
                    @else
                        @if(($filters['status'] ?? 'posted') === 'posted')
                             @if(!empty($filters['q']))
                                We couldn't find any tasks matching your search.
                             @else
                                Put your task in front of thousands of people and get it done quickly.
                             @endif
                        @else
                            No tasks found with this status.
                        @endif
                    @endif
                </p>
                
                @if(($viewMode ?? 'posted') === 'applied')
                    <a href="{{ route('tasks') }}" class="cta-button">
                        <i data-feather="search" style="width:18px;"></i> Browse Tasks
                    </a>
                @else
                    <a href="{{ route('post-task') }}" class="cta-button">
                        <i data-feather="plus" style="width:18px;"></i> Post a New Task
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
            document.getElementById('modal-offer-time').innerText = data.time + ' ago';
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
