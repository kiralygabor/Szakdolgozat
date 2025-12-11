@extends('layout')
 
@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 py-8 font-sans">
   
    {{-- Progress Bar Section --}}
    <div class="w-full mb-6">
        <div class="h-2 rounded-full bg-gray-200 overflow-hidden">
            <div class="h-full w-1/3 bg-green-500"></div>
        </div>
        {{-- Labels --}}
        <div class="flex justify-between text-[11px] font-semibold text-gray-500 mt-2 relative">
            <span class="text-left w-1/3">Open</span>
            <span class="text-center w-1/3">Assigned</span>
            <span class="text-right w-1/3">Completed</span>
        </div>
    </div>
 
    {{-- Back Link --}}
    <div class="mb-4 text-sm text-blue-600">
        <a href="{{ route('tasks') }}" class="inline-flex items-center hover:underline font-medium">
            <i data-feather="chevron-left" class="w-4 h-4 mr-1"></i>
            Return to map
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
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-bold text-lg flex-shrink-0">
                    {{ substr($task->employer->name ?? 'U', 0, 1) }}
                </div>
                <div>
                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wide mb-1">Posted by</p>
                    <p class="text-base font-medium text-slate-900">{{ $task->employer->name ?? 'Unknown User' }}</p>
                    <p class="text-xs text-gray-400 mt-1">{{ $task->created_at->diffForHumans() }}</p>
                </div>
            </div>
 
            {{-- Location --}}
            <div class="flex items-center space-x-4">
                <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center flex-shrink-0">
                    <i data-feather="map-pin" class="w-5 h-5 text-slate-900"></i>
                </div>
                <div>
                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wide mb-1">Location</p>
                    <p class="text-base font-medium text-slate-900">
                        {{ optional(optional($task->employer)->city)->name ?? 'Remote' }}
                    </p>
                </div>
            </div>
 
            {{-- Date --}}
            <div class="flex items-center space-x-4">
                <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center flex-shrink-0">
                    <i data-feather="calendar" class="w-5 h-5 text-slate-900"></i>
                </div>
                <div>
                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wide mb-1">To be done on</p>
                    <p class="text-base font-medium text-slate-900">On {{ \Carbon\Carbon::parse($task->created_at)->addDays(7)->format('D, d M') }}</p>
                    <p class="text-xs text-gray-500">Anytime</p>
                </div>
            </div>
 
            {{-- NEW SECTION: Applications --}}
            <div class="flex items-center space-x-4">
                <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center flex-shrink-0">
                    <i data-feather="users" class="w-5 h-5 text-slate-900"></i>
                </div>
                <div>
                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wide mb-1">Applications</p>
                    <p class="text-base font-medium text-slate-900">
                        {{ $task->offers ? $task->offers->count() : 0 }} Offers
                    </p>
                    <p class="text-xs text-gray-500">
                        {{ ($task->offers && $task->offers->count() > 0) ? 'View candidates' : 'Be the first' }}
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
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Task Budget</p>
                            <div class="flex items-baseline gap-1">
                                <span class="text-4xl font-black text-slate-800 tracking-tight">£{{ number_format($task->price, 0) }}</span>
                                <span class="text-sm font-medium text-gray-400">GBP</span>
                            </div>
                        </div>
                        {{-- Status Badge --}}
                        <div class="px-2 py-1 bg-green-50 text-green-700 rounded-md text-[10px] font-bold uppercase tracking-wide border border-green-100">
                            Open for offers
                        </div>
                    </div>
 
                    {{-- Dashed Divider --}}
                    <div class="w-full border-b border-dashed border-gray-200 my-2"></div>
                </div>
 
                {{-- Form Section --}}
                <form action="{{ route('tasks.offers.store', $task->id) }}" method="POST" class="flex-grow flex flex-col justify-end space-y-4">
                    @csrf
                   
                    {{-- Input Fields --}}
                    <div class="space-y-3">
                       
                        {{-- Price Input with Currency Prefix --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1 ml-1">Your Offer</label>
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
                            <label class="block text-xs font-semibold text-gray-500 mb-1 ml-1">Message</label>
                            <textarea name="message" rows="3"
                                class="w-full px-3 py-2.5 bg-gray-50 border border-transparent text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-blue-500 focus:bg-white focus:border-transparent transition-all resize-none"
                                placeholder="Hi, I can help you with this task because..." required></textarea>
                        </div>
                    </div>
 
                    {{-- Action Button --}}
                    <div>
                        <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/30 transform transition-all active:scale-[0.98] flex items-center justify-center gap-2 text-sm">
                            <span>Make an Offer</span>
                            <i data-feather="arrow-right" class="w-4 h-4"></i>
                        </button>
                        {{-- Micro Trust Text --}}
                        <p class="text-center text-[10px] text-gray-400 mt-3 flex items-center justify-center gap-1">
                            <i data-feather="shield" class="w-3 h-3"></i> Secure payment hold
                        </p>
                    </div>
                </form>
 
            </div>
        </div>
 
    </div>
 
    {{-- DETAILS SECTION --}}
    <div class="w-full">
        <button id="details-toggle" type="button" class="inline-flex items-center text-sm font-semibold text-blue-600 hover:text-blue-700 focus:outline-none">
            <span id="details-toggle-text">More details</span>
            <i id="details-toggle-icon" data-feather="chevron-down" class="w-4 h-4 ml-1 transition-transform"></i>
        </button>
 
        <div id="details-content" class="w-full mt-4 relative overflow-hidden max-h-20 transition-all duration-300">
            <h3 class="text-lg font-bold text-slate-900 mb-3">Description</h3>
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
 
<script>
    document.addEventListener("DOMContentLoaded", function() {
        if (window.feather) {
            window.feather.replace();
        }
 
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
                    toggleText.textContent = 'Less details';
                    toggleIcon.classList.add('rotate-180');
                } else {
                    detailsContent.classList.add('max-h-20', 'overflow-hidden');
                    if (detailsFade) detailsFade.classList.remove('hidden');
                    toggleText.textContent = 'More details';
                    toggleIcon.classList.remove('rotate-180');
                }
            });
        }
    });
</script>
@endsection