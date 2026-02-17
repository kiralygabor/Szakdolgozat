@extends('layout')
 
@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 py-8 font-sans">
   
    {{-- Progress Bar Section --}}
    <div class="w-full mb-6">
        @php
            $progressWidth = '1/3'; // Default open
            $isOpen = $task->status === 'open';
            $isAssigned = $task->status === 'pending'; // In this app, pending = assigned/in-progress
            $isCompleted = $task->status === 'completed';
            
            if ($isAssigned) {
                $progressWidth = '2/3';
            } elseif ($isCompleted) {
                $progressWidth = 'full';
            }
        @endphp
        <div class="h-2 rounded-full bg-gray-200 overflow-hidden">
            <div class="h-full bg-green-500 transition-all duration-500" style="width: {{ $isCompleted ? '100%' : ($isAssigned ? '66%' : '33%') }}"></div>
        </div>
        {{-- Labels --}}
        <div class="flex justify-between text-[11px] font-semibold text-gray-500 mt-2 relative">
            <span class="text-left w-1/3 {{ $isOpen || $isAssigned || $isCompleted ? 'text-green-600' : '' }}">{{ __('task_details.progress.open') }}</span>
            <span class="text-center w-1/3 {{ $isAssigned || $isCompleted ? 'text-green-600' : '' }}">{{ __('task_details.progress.assigned') }}</span>
            <span class="text-right w-1/3 {{ $isCompleted ? 'text-green-600' : '' }}">{{ __('task_details.progress.completed') }}</span>
        </div>
    </div>
 
    {{-- Back Link --}}
    <div class="mb-4 text-sm text-blue-600">
        <a href="{{ route('tasks') }}" class="inline-flex items-center hover:underline font-medium">
            <i data-feather="chevron-left" class="w-4 h-4 mr-1"></i>
            {{ __('task_details.return') }}
        </a>
    </div>
 
    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="mb-4 p-3 rounded-md bg-green-50 text-green-700 text-sm">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-3 rounded-md bg-red-50 text-red-700 text-sm">
            {{ session('error') }}
        </div>
    @endif

    {{-- Main Content Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-stretch mb-8">
 
        {{-- LEFT COLUMN: Task Meta Info --}}
        <div class="md:col-span-5 flex flex-col space-y-8">
           
            {{-- Title --}}
            <h1 class="text-3xl font-extrabold text-slate-900 leading-tight">
                {{ $task->title }}
            </h1>
 
            {{-- Posted By --}}
            <a href="{{ $task->employer ? route('public-profile', $task->employer->id) : '#' }}" class="flex items-center space-x-4 group hover:bg-gray-50 p-2 -ml-2 rounded-lg transition-colors text-decoration-none">
                @if(!empty($task->employer->avatar))
                    <img src="{{ asset('storage/' . $task->employer->avatar) }}" alt="Avatar" class="w-12 h-12 rounded-full object-cover border border-gray-200 group-hover:border-blue-400 transition-colors">
                @else
                    <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-bold text-lg flex-shrink-0 group-hover:bg-blue-100 group-hover:text-blue-600 transition-colors">
                        {{ substr($task->employer->first_name ?? 'U', 0, 1) }}
                    </div>
                @endif
                <div>
                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wide mb-1">{{ __('task_details.posted_by') }}</p>
                    <p class="text-base font-medium text-slate-900 group-hover:text-blue-600 transition-colors">{{ $task->employer->first_name ?? 'Unknown' }} {{ $task->employer->last_name ?? '' }}</p>
                    <p class="text-xs text-gray-400 mt-1">{{ $task->created_at->diffForHumans() }}</p>
                </div>
            </a>
 
            {{-- Location --}}
            <div class="flex items-center space-x-4">
                <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center flex-shrink-0">
                    <i data-feather="map-pin" class="w-5 h-5 text-slate-900"></i>
                </div>
                <div>
                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wide mb-1">{{ __('task_details.location') }}</p>
                    <p class="text-base font-medium text-slate-900">
                        {{ $task->location ?? 'Remote' }}
                    </p>
                </div>
            </div>
 
            {{-- Date --}}
            <div class="flex items-center space-x-4">
                <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center flex-shrink-0">
                    <i data-feather="calendar" class="w-5 h-5 text-slate-900"></i>
                </div>
                <div>
                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wide mb-1">{{ __('task_details.to_be_done') }}</p>
                    <p class="text-base font-medium text-slate-900">{{ __('On') }} {{ \Carbon\Carbon::parse($task->created_at)->addDays(7)->format('D, d M') }}</p>
                    <p class="text-xs text-gray-500">{{ __('task_details.anytime') }}</p>
                </div>
            </div>
 
            {{-- NEW SECTION: Applications --}}
            <div class="flex items-center space-x-4">
                <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center flex-shrink-0">
                    <i data-feather="users" class="w-5 h-5 text-slate-900"></i>
                </div>
                <div>
                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wide mb-1">{{ __('task_details.applications.label') }}</p>
                    <p class="text-base font-medium text-slate-900">
                        {{ __('task_details.applications.offers_count', ['count' => $task->offers ? $task->offers->count() : 0]) }}
                    </p>
                    <p class="text-xs text-gray-500">
                        {{ ($task->offers && $task->offers->count() > 0) ? __('task_details.applications.view_candidates') : __('task_details.applications.be_first') }}
                    </p>
                </div>
            </div>
 
        </div>
 
        {{-- RIGHT COLUMN: Budget / Offer Box --}}
        <div class="md:col-span-7">
            {{-- h-full ensures same height as left col. Added white bg, modern shadow, and rounded-2xl --}}
            <div class="h-full bg-white border border-gray-100 rounded-2xl p-6 shadow-xl flex flex-col justify-between relative overflow-hidden group">
               
                {{-- Decorative Top Line --}}
                <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-blue-500 to-indigo-600"></div>
 
                {{-- Header Section --}}
                <div>
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ __('task_details.budget.label') }}</p>
                            <div class="flex items-baseline gap-1">
                                <span class="text-4xl font-black text-slate-800 tracking-tight">£{{ number_format($task->price, 0) }}</span>
                                <span class="text-sm font-medium text-gray-400">{{ __('task_details.budget.currency') }}</span>
                            </div>
                        </div>
                        {{-- Status Badge --}}
                        {{-- Status Badge --}}
                        @if($task->status === 'open')
                            <div class="px-2 py-1 bg-green-50 text-green-700 rounded-md text-[10px] font-bold uppercase tracking-wide border border-green-100">
                                {{ __('task_details.status.open') }}
                            </div>
                        @elseif($task->status === 'pending')
                            <div class="px-2 py-1 bg-yellow-50 text-yellow-700 rounded-md text-[10px] font-bold uppercase tracking-wide border border-yellow-100">
                                {{ __('task_details.status.assigned') }}
                            </div>
                        @elseif($task->status === 'completed')
                            <div class="px-2 py-1 bg-blue-50 text-blue-700 rounded-md text-[10px] font-bold uppercase tracking-wide border border-blue-100">
                                {{ __('task_details.status.completed') }}
                            </div>
                        @endif
                    </div>
 
                    {{-- Dashed Divider --}}
                    <div class="w-full border-b border-dashed border-gray-200 my-2"></div>
                </div>
 
                {{-- Form Section --}}
                @if($task->status === 'open')
                    <form action="{{ route('tasks.offers.store', $task->id) }}" method="POST" class="flex-grow flex flex-col justify-end space-y-4">
                        @csrf
                        
                        {{-- Input Fields --}}
                        <div class="space-y-3">
                            
                            {{-- Price Input with Currency Prefix --}}
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 mb-1 ml-1">{{ __('task_details.form.your_offer') }}</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 font-bold sm:text-sm">£</span>
                                    </div>
                                    <input type="number" name="offer_price" value="{{ $task->price }}"
                                        class="w-full pl-7 pr-3 py-2.5 bg-gray-50 border border-transparent text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-blue-500 focus:bg-white focus:border-transparent transition-all font-semibold"
                                        placeholder="0.00" required>
                                </div>
                            </div>

                            {{-- Message Input --}}
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 mb-1 ml-1">{{ __('task_details.form.message') }}</label>
                                <textarea name="message" rows="3"
                                    class="w-full px-3 py-2.5 bg-gray-50 border border-transparent text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-blue-500 focus:bg-white focus:border-transparent transition-all resize-none"
                                    placeholder="{{ __('task_details.form.message_placeholder') }}" required></textarea>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-2">
                            <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/30 transform transition-all active:scale-[0.98] flex items-center justify-center gap-2 text-sm">
                                <span>{{ __('task_details.form.submit') }}</span>
                                <i data-feather="arrow-right" class="w-4 h-4"></i>
                            </button>
                            
                            @auth
                                <button type="button" onclick="openReportModal({{ $task->id }}, {{ $task->employer_id }})" class="w-full py-2.5 bg-gray-100 hover:bg-red-50 text-gray-600 hover:text-red-600 font-semibold rounded-xl transition-all flex items-center justify-center gap-2 text-sm">
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
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-2">
                            @if($task->status === 'pending')
                                <i data-feather="user-check" class="w-8 h-8 text-yellow-500"></i>
                            @else
                                <i data-feather="check-circle" class="w-8 h-8 text-green-500"></i>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">
                                @if($task->status === 'pending')
                                    {{ __('task_details.states.assigned_title') }}
                                @else
                                    {{ __('task_details.states.completed_title') }}
                                @endif
                            </h3>
                            <p class="text-sm text-gray-500 mt-1">
                                @if($task->status === 'pending')
                                    {{ __('task_details.states.assigned_desc') }}
                                @else
                                    {{ __('task_details.states.completed_desc') }}
                                @endif
                            </p>
                        </div>
                        
                        @auth
                            <button type="button" onclick="openReportModal({{ $task->id }}, {{ $task->employer_id }})" class="mt-4 px-4 py-2 bg-white border border-gray-200 hover:bg-gray-50 text-gray-500 hover:text-red-600 text-xs font-semibold rounded-lg transition-colors flex items-center gap-2">
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
        <button id="details-toggle" type="button" class="inline-flex items-center text-sm font-semibold text-blue-600 hover:text-blue-700 focus:outline-none">
            <span id="details-toggle-text">{{ __('task_details.details.more') }}</span>
            <i id="details-toggle-icon" data-feather="chevron-down" class="w-4 h-4 ml-1 transition-transform"></i>
        </button>
 
        <div id="details-content" class="w-full mt-4 relative overflow-hidden max-h-20 transition-all duration-300">
            <h3 class="text-lg font-bold text-slate-900 mb-3">{{ __('task_details.details.description') }}</h3>
            <div class="prose prose-slate text-slate-600 leading-relaxed max-w-none">
                <p>{{ $task->description }}</p>
            </div>
 
            @if($task->photos && count($task->photos) > 0)
            <div class="border-t border-gray-100 pt-4 mt-4">
                <div class="flex gap-4 flex-wrap">
                    @foreach($task->photos as $photo)
                        <a href="{{ asset('storage/' . $photo) }}" target="_blank" class="flex-shrink-0 block rounded-lg overflow-hidden border border-gray-200 hover:opacity-90 transition w-32 h-32">
                            <img src="{{ asset('storage/' . $photo) }}" alt="Task Photo" class="w-full h-full object-cover">
                        </a>
                    @endforeach
                </div>
            </div>
            @endif
           
            <div id="details-fade" class="absolute bottom-0 left-0 right-0 h-12 bg-gradient-to-t from-white to-transparent pointer-events-none"></div>
        </div>
    </div>
 
</div>
 

    @php
        $missingSteps = $missingSteps ?? [];
    @endphp

    <!-- MODAL: BEFORE YOU MAKE AN OFFER (Copied from tasks.blade.php logic) -->
    <div id="profile-steps-modal" class="fixed inset-0 bg-black/60 backdrop-blur-[2px] flex items-center justify-center z-[60] hidden transition-opacity duration-300">
        <div class="bg-white w-full max-w-[480px] rounded-2xl shadow-2xl relative mx-4 overflow-hidden animate-fade-in-up">
            
            <!-- Close X Button -->
            <button type="button" id="profile-steps-close" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 z-10 p-1">
                <i data-feather="x" class="w-6 h-6"></i>
            </button>

            <!-- Modal Content -->
            <div class="pt-8 pb-6 px-8">
                
                <!-- Illustration: Trust & Verification -->
                <div class="flex justify-center mb-6">
                    <div class="relative w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 22C12 22 20 18 20 12V5L12 2L4 5V12C4 18 12 22 12 22Z" fill="#2563EB" stroke="#2563EB" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M9 12L11 14L15 10" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </div>

                <!-- Header Text -->
                <div class="text-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ __('task_details.modal.title') }}</h2>
                    <p class="text-gray-500 text-[15px] leading-relaxed">
                        {{ __('task_details.modal.desc') }}
                    </p>
                </div>

                <!-- Steps List -->
                <div class="space-y-2 mb-8">
                    @foreach($missingSteps as $step)
                        @php
                            $iconName = 'check-circle';
                            $lower = strtolower($step);
                            if(str_contains($lower, 'picture') || str_contains($lower, 'photo')) $iconName = 'user';
                            elseif(str_contains($lower, 'birth') || str_contains($lower, 'date')) $iconName = 'calendar';
                            elseif(str_contains($lower, 'mobile') || str_contains($lower, 'phone')) $iconName = 'smartphone';
                            elseif(str_contains($lower, 'bank') || str_contains($lower, 'payment')) $iconName = 'credit-card';
                            elseif(str_contains($lower, 'address') || str_contains($lower, 'location')) $iconName = 'map-pin';
                        @endphp

                        <a href="{{ route('profile') }}" class="flex items-center justify-between py-2 group cursor-pointer hover:bg-gray-50 rounded-xl px-2 transition-colors no-underline">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-slate-500 shrink-0">
                                    <i data-feather="{{ $iconName }}" class="w-5 h-5"></i>
                                </div>
                                <span class="text-gray-700 font-medium text-[15px]">{{ $step }}</span>
                            </div>
                            <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center shrink-0 group-hover:bg-blue-700 transition-colors">
                                <i data-feather="plus" class="w-4 h-4"></i>
                            </div>
                        </a>
                    @endforeach
                </div>

                <!-- Footer Button -->
                <div class="mt-2">
                    <a href="{{ route('profile') }}" class="block w-full py-3 bg-blue-50 hover:bg-blue-100 text-blue-600 font-bold text-center rounded-full transition-colors text-sm">
                        {{ __('task_details.modal.continue') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        if (window.feather) {
            window.feather.replace();
        }

        // Details Toggle Logic
        var detailsToggle = document.getElementById('details-toggle');
        var detailsContent = document.getElementById('details-content');
        var toggleText = document.getElementById('details-toggle-text');
        var toggleIcon = document.getElementById('details-toggle-icon');
        var detailsFade = document.getElementById('details-fade');

        if (detailsToggle && detailsContent && toggleText && toggleIcon) {
            detailsToggle.addEventListener('click', function() {
                var isCollapsed = detailsContent.classList.contains('max-h-20');

                if (isCollapsed) {
                    detailsContent.classList.remove('max-h-20', 'overflow-hidden');
                    if (detailsFade) detailsFade.classList.add('hidden');
                    toggleText.textContent = '{{ __('task_details.details.less') }}';
                    toggleIcon.classList.add('rotate-180');
                } else {
                    detailsContent.classList.add('max-h-20', 'overflow-hidden');
                    if (detailsFade) detailsFade.classList.remove('hidden');
                    toggleText.textContent = '{{ __('task_details.details.more') }}';
                    toggleIcon.classList.remove('rotate-180');
                }
            });
        }

        // Modal Logic
        const modal = document.getElementById('profile-steps-modal');
        const closeBtn = document.getElementById('profile-steps-close');
        
        // Show modal if there are missing steps and user tries to interact with form
        // Or if we want to block the form simply by intercepting the submit
        const offerForm = document.querySelector('form[action*="offers"]');
        const missingStepsCount = {{ count($missingSteps) }};

        if (offerForm && missingStepsCount > 0) {
            offerForm.addEventListener('submit', function(e) {
                e.preventDefault();
                modal.classList.remove('hidden');
                if (window.feather) window.feather.replace();
            });
            
            // Also modify the submit button to clearer indication if needed, 
            // but the intercept works fine.
        }

        if (closeBtn && modal) {
            closeBtn.addEventListener('click', () => modal.classList.add('hidden'));
            modal.addEventListener('click', (e) => {
                if (e.target === modal) modal.classList.add('hidden');
            });
        }
    });
</script>

<!-- Include Report Modal -->
@include('components.report-modal')

@endsection