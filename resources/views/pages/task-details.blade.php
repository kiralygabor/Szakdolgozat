@extends('layout')

@section('content')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/task-details.css') }}">
@endpush

<div class="task-details-container max-w-4xl mx-auto px-4 sm:px-6 py-8 font-sans">
   
    {{-- Progress Bar Section --}}
    <div class="w-full mb-6">
        @php
            $isOpen = $task->status === \App\Enums\TaskStatus::Open;
            $isAssigned = $task->status === \App\Enums\TaskStatus::Assigned; 
            $isCompleted = $task->status === \App\Enums\TaskStatus::Completed;
            
            $progressWidth = '1/3';
            if ($isAssigned) {
                $progressWidth = '2/3';
            } elseif ($isCompleted) {
                $progressWidth = 'full';
            }
        @endphp
        <div class="h-2 rounded-full progress-track overflow-hidden">
            <div class="h-full progress-fill transition-all duration-500" style="width: {{ $isCompleted ? '100%' : ($isAssigned ? '66%' : '33%') }}"></div>
        </div>
        {{-- Labels --}}
        <div class="flex justify-between text-[11px] font-semibold details-text-muted mt-2 relative">
            <span class="text-left w-1/3 {{ $isOpen || $isAssigned || $isCompleted ? 'text-[var(--details-success)]' : '' }}">{{ __('task_details.progress.open') }}</span>
            <span class="text-center w-1/3 {{ $isAssigned || $isCompleted ? 'text-[var(--details-success)]' : '' }}">{{ __('task_details.progress.assigned') }}</span>
            <span class="text-right w-1/3 {{ $isCompleted ? 'text-[var(--details-success)]' : '' }}">{{ __('task_details.progress.completed') }}</span>
        </div>
    </div>
 
    {{-- Back Link --}}
    <div class="mb-4 text-sm text-[var(--details-accent)]">
        <a href="{{ route('tasks') }}" class="inline-flex items-center hover:underline font-medium">
            <i data-feather="chevron-left" class="w-4 h-4 mr-1"></i>
            {{ __('task_details.return') }}
        </a>
    </div>
 
    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="mb-4 p-3 details-alert-success flex items-center">
            <i data-feather="check-circle" class="w-4 h-4 mr-2"></i>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-3 details-alert-error flex items-center">
            <i data-feather="alert-circle" class="w-4 h-4 mr-2"></i>
            {{ session('error') }}
        </div>
    @endif

    {{-- Main Content Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-stretch mb-8">
 
        {{-- LEFT COLUMN: Task Meta Info --}}
        <div class="md:col-span-5 flex flex-col space-y-8">
           
            {{-- Title --}}
            <h1 class="text-3xl font-extrabold details-text-main leading-tight">
                {{ $task->title }}
            </h1>
 
            {{-- Posted By --}}
            <a href="{{ $task->employer ? route('public-profile', $task->employer->id) : '#' }}" class="flex items-center space-x-4 group hover:details-surface-bg p-2 -ml-2 rounded-lg transition-colors text-decoration-none">
                <img src="{{ $task->employer->avatar_url }}" alt="Avatar" class="w-12 h-12 rounded-full object-cover border details-border-color group-hover:border-[var(--details-accent)] transition-colors">
                <div>
                    <p class="text-[11px] font-bold details-text-muted uppercase tracking-wide mb-1">{{ __('task_details.posted_by') }}</p>
                    <p class="text-base font-medium details-text-main group-hover:text-[var(--details-accent)] transition-colors">
                        {{ $task->employer->first_name ?? __('task_details.unknown') }} {{ $task->employer->last_name ?? '' }}
                        @if($task->employer && $task->employer->rating > 0)
                            <span class="inline-flex items-center gap-1 text-sm font-bold text-yellow-500 ml-2">
                                <i data-feather="star" class="w-4 h-4 fill-current"></i> {{ $task->employer->rating }}
                            </span>
                        @endif
                    </p>
                    <p class="text-xs details-text-muted mt-1">{{ $task->created_at->diffForHumans() }}</p>
                </div>
            </a>
 
            <div class="flex items-center space-x-4">
                <div class="w-10 h-10 rounded-full details-surface-bg flex items-center justify-center flex-shrink-0">
                    <i data-feather="map-pin" class="w-5 h-5 details-text-main"></i>
                </div>
                <div>
                    <p class="text-[11px] font-bold details-text-muted uppercase tracking-wide mb-1">{{ __('task_details.location') }}</p>
                    <p class="text-base font-medium details-text-main">
                        <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($task->location ?? __('task_details.remote')) }}" target="_blank" class="hover:text-[var(--primary-accent)] transition-colors">
                            {{ $task->location ?? __('task_details.remote') }}
                        </a>
                    </p>
                </div>
            </div>
 
            {{-- Date --}}
            <div class="flex items-center space-x-4">
                <div class="w-10 h-10 rounded-full details-surface-bg flex items-center justify-center flex-shrink-0">
                    <i data-feather="calendar" class="w-5 h-5 details-text-main"></i>
                </div>
                <div>
                    <p class="text-[11px] font-bold details-text-muted uppercase tracking-wide mb-1">{{ __('task_details.to_be_done') }}</p>
                    <p class="text-base font-medium details-text-main">
                        @if($task->is_date_flexible)
                            {{ __('Flexible / To be discussed') }}
                        @else
                            {{ $task->required_date ? $task->required_date->format('D, d M') : $task->created_at->addDays(30)->format('D, d M') }}
                        @endif
                    </p>
                    <p class="text-xs details-text-muted">{{ __('task_details.anytime') }}</p>
                </div>
            </div>
 
            {{-- NEW SECTION: Applications --}}
            <div class="flex items-center space-x-4">
                <div class="w-10 h-10 rounded-full details-surface-bg flex items-center justify-center flex-shrink-0">
                    <i data-feather="users" class="w-5 h-5 details-text-main"></i>
                </div>
                <div>
                    <p class="text-[11px] font-bold details-text-muted uppercase tracking-wide mb-1">{{ __('task_details.applications.label') }}</p>
                    <p class="text-base font-medium details-text-main">
                        {{ __('task_details.applications.offers_count', ['count' => $task->offers ? $task->offers->count() : 0]) }}
                    </p>
                    <p class="text-xs details-text-muted">
                        {{ ($task->offers && $task->offers->count() > 0) ? __('task_details.applications.view_candidates') : __('task_details.applications.be_first') }}
                    </p>
                </div>
            </div>
 
        </div>
 
        {{-- RIGHT COLUMN: Budget / Offer Box --}}
        <div class="md:col-span-7">
            {{-- h-full ensures same height as left col. Added white bg, modern shadow, and rounded-2xl --}}
            <div class="h-full budget-box rounded-2xl p-6 flex flex-col justify-between relative overflow-hidden">
               
                {{-- Decorative Top Line --}}
                <div class="absolute top-0 left-0 w-full h-1.5 bg-[var(--primary-accent)]"></div>
 
                {{-- Header Section --}}
                <div>
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <p class="text-[10px] font-bold details-text-muted uppercase tracking-widest">{{ __('task_details.budget.label') }}</p>
                            <div class="flex items-baseline gap-1">
                                <span class="text-4xl font-black details-text-main tracking-tight">€{{ number_format($task->price, 0) }}</span>
                            </div>
                        </div>
                        {{-- Status Badge --}}
                        @if($isOpen)
                            <div class="px-2 py-1 status-badge-open rounded-md text-[10px] font-bold uppercase tracking-wide">
                                {{ __('task_details.status.open') }}
                            </div>
                        @elseif($isAssigned)
                            <div class="px-2 py-1 status-badge-assigned rounded-md text-[10px] font-bold uppercase tracking-wide">
                                {{ __('task_details.status.assigned') }}
                            </div>
                        @elseif($isCompleted)
                            <div class="px-2 py-1 status-badge-completed rounded-md text-[10px] font-bold uppercase tracking-wide">
                                {{ __('task_details.status.completed') }}
                            </div>
                        @endif
                    </div>
 
                    {{-- Dashed Divider --}}
                    <div class="w-full border-b border-dashed details-border-color my-2"></div>
                </div>
 
                {{-- Form Section --}}
                @if($isOpen)
                    <form action="{{ route('tasks.offers.store', $task->id) }}" method="POST" class="flex-grow flex flex-col justify-end space-y-4">
                        @csrf
                        
                        {{-- Input Fields --}}
                        <div class="space-y-3">
                            
                            {{-- Price Input with Currency Prefix --}}
                            <div>
                                <label class="block text-xs font-semibold details-text-main mb-1 ml-1">
                                    {{ (auth()->id() == $task->employee_id) ? __('Your Quote') : __('task_details.form.your_offer') }}
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="details-text-muted font-bold sm:text-sm">€</span>
                                    </div>
                                    <input type="number" name="offer_price" value="{{ $task->price }}"
                                        class="w-full pl-7 pr-3 py-2.5 details-surface-bg border border-transparent details-text-main text-sm rounded-lg focus:ring-2 focus:ring-blue-50 focus:details-main-bg focus:details-border-color transition-all font-semibold"
                                        placeholder="0.00" required>
                                </div>
                            </div>
 
                            {{-- Message Input --}}
                            <div>
                                <label class="block text-xs font-semibold details-text-main mb-1 ml-1">{{ __('task_details.form.message') }}</label>
                                <textarea name="message" rows="3"
                                    class="w-full px-3 py-2.5 details-surface-bg border border-transparent details-text-main text-sm rounded-lg focus:ring-2 focus:ring-blue-500 focus:details-main-bg focus:details-border-color transition-all resize-none"
                                    placeholder="{{ (auth()->id() == $task->employee_id) ? __('Add a message with your quote...') : __('task_details.form.message_placeholder') }}" required></textarea>
                            </div>
                        </div>
 
                        <!-- Action Buttons -->
                        <div class="space-y-2">
                            <button type="submit" class="w-full py-3.5 bg-[var(--primary-accent)] hover:bg-[var(--primary-hover)] text-white font-bold rounded-xl shadow-lg border-2 border-transparent focus:border-[var(--bg-primary)] transition-all flex items-center justify-center gap-2 text-sm">
                                <span>{{ (auth()->id() == $task->employee_id) ? __('Send Quote') : __('task_details.form.submit') }}</span>
                                <i data-feather="arrow-right" class="w-4 h-4"></i>
                            </button>
                            
                            @auth
                                <button type="button" onclick="openReportModal({{ $task->id }}, {{ $task->employer_id }})" class="w-full py-2.5 details-surface-bg hover:bg-opacity-80 details-text-muted hover:text-[var(--details-error)] font-semibold rounded-xl transition-all flex items-center justify-center gap-2 text-sm relative z-20">
                                    <i data-feather="flag" class="w-4 h-4"></i>
                                    <span>{{ __('task_details.form.report') }}</span>
                                </button>
                            @endauth
                            
                            <!-- Micro Trust Text -->
                            <p class="text-center text-[10px] text-gray-400 mt-3 flex items-center justify-center gap-1">
                                <i data-feather="shield" class="w-3 h-3"></i> {{ __('task_details.form.secure') }}
                            </p>
                        </div>
                    </form>
                @else
                    {{-- Closed/Assigned State View --}}
                    <div class="flex-grow flex flex-col justify-center items-center text-center space-y-4 p-4">
                        <div class="w-16 h-16 details-surface-bg rounded-full flex items-center justify-center mb-2">
                            @if($isAssigned)
                                <i data-feather="user-check" class="w-8 h-8 text-yellow-500"></i>
                            @else
                                <i data-feather="check-circle" class="w-8 h-8 details-success-text"></i>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-lg font-bold details-text-main">
                                @if($isAssigned)
                                    {{ __('task_details.states.assigned_title') }}
                                @else
                                    {{ __('task_details.states.completed_title') }}
                                @endif
                            </h3>
                            <p class="text-sm details-text-muted mt-1">
                                @if($isAssigned)
                                    {{ __('task_details.states.assigned_desc') }}
                                @else
                                    {{ __('task_details.states.completed_desc') }}
                                @endif
                            </p>
                        </div>
                        @auth
                            @if($isAssigned && (Auth::id() === $task->employer_id || Auth::id() === $task->employee_id))
                                <a href="{{ route('messages', ['user_id' => (Auth::id() === $task->employer_id ? $task->employee_id : $task->employer_id)]) }}" 
                                   class="w-full py-3 bg-[var(--bg-primary)] border-2 border-[var(--details-accent)] text-[var(--details-accent)] font-bold rounded-xl flex items-center justify-center gap-2 hover:bg-[var(--bg-secondary)] transition-colors">
                                    <i data-feather="message-square" class="w-4 h-4"></i>
                                    {{ __('Message') }}
                                </a>
                            @endif

                            <button type="button" onclick="openReportModal({{ $task->id }}, {{ $task->employer_id }})" class="mt-4 px-4 py-2 details-main-bg border details-border-color hover:details-surface-bg details-text-muted hover:text-[var(--details-error)] text-xs font-semibold rounded-lg transition-colors flex items-center gap-2">
                                <i data-feather="flag" class="w-3 h-3"></i> {{ __('task_details.form.report') }}
                            </button>
                        @endauth
                    </div>
                @endif
 
            </div>
        </div>
 
    </div>
 
    {{-- DETAILS SECTION --}}
    <div class="w-full">
        <button id="details-toggle" type="button" class="inline-flex items-center text-sm font-semibold text-[var(--details-accent)] hover:underline focus:outline-none">
            <span id="details-toggle-text" data-more="{{ __('task_details.details.more') }}" data-less="{{ __('task_details.details.less') }}">{{ __('task_details.details.more') }}</span>
            <i id="details-toggle-icon" data-feather="chevron-down" class="w-4 h-4 ml-1 transition-transform"></i>
        </button>
 
        <div id="details-content" class="w-full mt-4 relative overflow-hidden max-h-20 transition-all duration-300">
            <h3 class="text-lg font-bold details-text-main mb-3">{{ __('task_details.details.description') }}</h3>
            <div class="prose prose-slate details-text-muted leading-relaxed max-w-none">
                <p>{{ $task->description }}</p>
            </div>
 
            @if($task->photos && count($task->photos) > 0)
            <div class="border-t details-border-color pt-4 mt-4">
                <div class="flex gap-4 flex-wrap">
                    @foreach($task->photos as $photo)
                        <a href="{{ asset('storage/' . $photo) }}" target="_blank" class="flex-shrink-0 block rounded-lg overflow-hidden border details-border-color hover:opacity-90 transition w-32 h-32">
                            <img src="{{ asset('storage/' . $photo) }}" alt="Task Photo" class="w-full h-full object-cover">
                        </a>
                    @endforeach
                </div>
            </div>
            @endif
           
            <div id="details-fade" class="absolute bottom-0 left-0 right-0 h-12 bg-gradient-to-t from-[var(--bg-primary)] to-transparent pointer-events-none"></div>
        </div>
    </div>
 
</div>
 
<script type="module">
    import { TaskDetailManager } from '{{ asset('js/components/task-detail-manager.js') }}';
    import { TaskReportManager } from '{{ asset('js/components/task-report-manager.js') }}';
    document.addEventListener('DOMContentLoaded', () => {
        new TaskDetailManager();
        new TaskReportManager();
        if (window.feather) {
            window.feather.replace();
        }
    });
</script>
 
<!-- Include Report Modal -->
@include('components.report-modal')
 
</div>
@endsection