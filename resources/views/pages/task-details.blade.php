@extends('layout')

@section('content')
<style>
  html.dark .bg-white { background-color: #1e293b !important; }
  html.dark .border-gray-100 { border-color: #334155 !important; }
  html.dark .text-slate-900, html.dark .text-slate-800 { color: #ffffff !important; }
  html.dark .bg-gray-100, html.dark .bg-gray-50 { background-color: #0f172a !important; }
  html.dark #details-fade { background-image: linear-gradient(to top, #1e293b, transparent) !important; }
</style>

<div class="max-w-4xl mx-auto px-4 sm:px-6 py-8 font-sans">
   
    {{-- Progress Bar Section --}}
    <div class="w-full mb-6">
        @php
            $progressWidth = '1/3'; // Default open
            $isOpen = $task->status === 'open';
            $isAssigned = $task->status === 'assigned'; // In this app, assigned = in-progress
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
                <img src="{{ $task->employer->avatar_url }}" alt="Avatar" class="w-12 h-12 rounded-full object-cover border border-gray-200 group-hover:border-blue-400 transition-colors">
                <div>
                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wide mb-1">{{ __('task_details.posted_by') }}</p>
                    <p class="text-base font-medium text-slate-900 group-hover:text-blue-600 transition-colors">{{ $task->employer->first_name ?? __('task_details.unknown') }} {{ $task->employer->last_name ?? '' }}</p>
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
                        {{ $task->location ?? __('task_details.remote') }}
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
                    <p class="text-base font-medium text-slate-900">{{ __('task_details.on') }} {{ \Carbon\Carbon::parse($task->created_at)->addDays(7)->format('D, d M') }}</p>
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
            <div class="h-full bg-white border border-gray-100 rounded-2xl p-6 shadow-xl flex flex-col justify-between relative overflow-hidden">
               
                {{-- Decorative Top Line --}}
                <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-blue-500 to-indigo-600"></div>
 
                {{-- Header Section --}}
                <div>
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ __('task_details.budget.label') }}</p>
                            <div class="flex items-baseline gap-1">
                                <span class="text-4xl font-black text-slate-800 tracking-tight">€{{ number_format($task->price, 0) }}</span>
                            </div>
                        </div>
                        {{-- Status Badge --}}
                        {{-- Status Badge --}}
                        @if($task->status === 'open')
                            <div class="px-2 py-1 bg-green-50 text-green-700 rounded-md text-[10px] font-bold uppercase tracking-wide border border-green-100">
                                {{ __('task_details.status.open') }}
                            </div>
                        @elseif($task->status === 'assigned')
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
                                <label class="block text-xs font-semibold text-gray-800 mb-1 ml-1">
                                    {{ (auth()->id() == $task->employee_id) ? __('Your Quote') : __('task_details.form.your_offer') }}
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 font-bold sm:text-sm">€</span>
                                    </div>
                                    <input type="number" name="offer_price" value="{{ $task->price }}"
                                        class="w-full pl-7 pr-3 py-2.5 bg-gray-50 border border-transparent text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-blue-500 focus:bg-white focus:border-transparent transition-all font-semibold"
                                        placeholder="0.00" required>
                                </div>
                            </div>
 
                            {{-- Message Input --}}
                            <div>
                                <label class="block text-xs font-semibold text-gray-800 mb-1 ml-1">{{ __('task_details.form.message') }}</label>
                                <textarea name="message" rows="3"
                                    class="w-full px-3 py-2.5 bg-gray-50 border border-transparent text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-blue-500 focus:bg-white focus:border-transparent transition-all resize-none"
                                    placeholder="{{ (auth()->id() == $task->employee_id) ? __('Add a message with your quote...') : __('task_details.form.message_placeholder') }}" required></textarea>
                            </div>
                        </div>
 
                        <!-- Action Buttons -->
                        <div class="space-y-2">
                            <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/30 transform transition-all active:scale-[0.98] flex items-center justify-center gap-2 text-sm">
                                <span>{{ (auth()->id() == $task->employee_id) ? __('Send Quote') : __('task_details.form.submit') }}</span>
                                <i data-feather="arrow-right" class="w-4 h-4"></i>
                            </button>
                            
                            @auth
                                <button type="button" onclick="openReportModal({{ $task->id }}, {{ $task->employer_id }})" class="w-full py-2.5 bg-gray-100 hover:bg-red-50 text-gray-600 hover:text-red-600 font-semibold rounded-xl transition-all flex items-center justify-center gap-2 text-sm relative z-20">
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
                            @if($task->status === 'assigned')
                                <i data-feather="user-check" class="w-8 h-8 text-yellow-500"></i>
                            @else
                                <i data-feather="check-circle" class="w-8 h-8 text-green-500"></i>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">
                                @if($task->status === 'assigned')
                                    {{ __('task_details.states.assigned_title') }}
                                @else
                                    {{ __('task_details.states.completed_title') }}
                                @endif
                            </h3>
                            <p class="text-sm text-gray-500 mt-1">
                                @if($task->status === 'assigned')
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
           
            <div id="details-fade" class="absolute bottom-0 left-0 right-0 h-12 bg-gradient-to-t from-white dark:from-[#1e293b] to-transparent pointer-events-none"></div>
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
    });
</script>
 
<!-- Include Report Modal -->
@include('components.report-modal')
 
@endsection