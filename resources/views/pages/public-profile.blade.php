@extends('layout')
 
@section('title', __('public_profile.title', ['name' => $user->first_name]))
 
@section('content')
<style>
    /* Public Profile High Contrast Fixes */
    .high-contrast .bg-blue-50\/50 {
        background-color: #ffffff !important;
        border-bottom: 3px solid #000000 !important;
    }
    .high-contrast .rounded-2xl.border-gray-100 {
        border-color: #000000 !important;
        border-width: 3px !important;
    }
    .high-contrast img.rounded-2xl.border-4 {
        border-color: #000000 !important;
    }
    .high-contrast .bg-blue-50.text-blue-700,
    .high-contrast .bg-amber-50.text-amber-700 {
        background-color: #000000 !important;
        color: #ffffff !important;
        border: 2px solid #ffffff !important;
    }
    
    .high-contrast .profile-req-btn {
        background-color: #000000 !important;
        color: #ffffff !important;
        border: 2px solid #000000 !important;
        text-decoration: none !important;
    }
    .high-contrast .profile-req-btn:hover {
        background-color: #ffffff !important;
        color: #000000 !important;
    }
    
    .high-contrast .bg-blue-50.text-blue-700 *,
    .high-contrast .bg-amber-50.text-amber-700 * {
        color: #ffffff !important;
    }
    .high-contrast :not(.bg-blue-50) > .text-blue-600,
    .high-contrast :not(.bg-blue-50) > .text-blue-700 {
        color: #000000 !important;
        fill: #000000 !important;
    }
    .high-contrast .bg-blue-50 .text-blue-600,
    .high-contrast .bg-blue-50 .text-blue-700 {
        color: #ffffff !important;
        fill: #ffffff !important;
    }
    .high-contrast .fill-current {
        fill: #000000 !important;
    }
    .high-contrast .bg-blue-50 .fill-current {
        fill: #ffffff !important;
    }
    .high-contrast .border-dashed {
        border-color: #000000 !important;
        border-width: 3px !important;
    }
    .high-contrast .bg-blue-50.rounded-full {
        background-color: #000000 !important;
        color: #ffffff !important;
    }
    .high-contrast .bg-blue-50.rounded-full i,
    .high-contrast .bg-blue-50.rounded-full svg {
        color: #ffffff !important;
    }
    .high-contrast .fixed.bottom-0 {
        border-top: 5px solid #000000 !important;
        background-color: #ffffff !important;
    }
    /* Public Profile Dark Mode Fixes */
    html.dark .bg-gray-50 { background-color: #0f172a !important; color: #f8fafc !important; }
    html.dark .bg-white { background-color: #1e293b !important; color: #f8fafc !important; border-color: #334155 !important; }
    html.dark .bg-blue-50\/50 { background-color: #1e293b !important; border-bottom: 1px solid #334155 !important; }
    html.dark .bg-blue-50 { background-color: #334155 !important; color: #f8fafc !important; border-color: #475569 !important; }
    html.dark .text-blue-700 { color: #f8fafc !important; }
    html.dark .text-blue-600 { color: #60a5fa !important; }
    html.dark .text-gray-900 { color: #f8fafc !important; }
    html.dark .text-gray-500 { color: #94a3b8 !important; }
    html.dark .text-gray-400 { color: #64748b !important; }
    html.dark .border-gray-100,
    html.dark .border-gray-200 { border-color: #334155 !important; }
    html.dark .shadow-sm { shadow: none !important; }
    
    html.dark img.rounded-2xl.border-4 { border-color: #1e293b !important; background-color: #1e293b !important; }
    html.dark .ring-gray-50 { --tw-ring-color: #0f172a !important; }
    
    html.dark #star-rating i, 
    html.dark #star-rating svg { color: #334155 !important; }
    html.dark .text-blue-100,
    html.dark .text-blue-200 { color: #64748b !important; }
    html.dark .bg-blue-600 { background-color: #2563eb !important; }
    
    html.dark textarea.bg-gray-50 { background-color: #0f172a !important; color: #f8fafc !important; border-color: #334155 !important; }
    html.dark textarea.bg-gray-50:focus { background-color: #1e293b !important; border-color: #2563eb !important; }

    /* Fix the specific borders the user mentioned */
    html.dark .border-blue-100 { border-color: #334155 !important; }

    /* --- Modal Specific Styles (Airtasker Look) --- */
    .step-icon-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #F8FAFC; /* Very light slate */
        display: flex;
        align-items: center;
        justify-content: center;
        color: #64748B; /* Slate 500 */
        flex-shrink: 0;
    }
    .step-add-btn {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background-color: #2563EB; /* Blue 600 */
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background-color 0.2s;
        flex-shrink: 0;
    }
    .step-add-btn:hover {
        background-color: #1d4ed8;
    }

    /* Modal List Items High Contrast Overrides */
    .high-contrast #profile-steps-modal a:hover {
        background-color: #000000 !important;
        color: #ffffff !important;
    }
    .high-contrast #profile-steps-modal a:hover .step-icon-circle,
    .high-contrast #profile-steps-modal a:hover .step-add-btn {
        background-color: #ffffff !important;
        border-color: #ffffff !important;
    }
    .high-contrast #profile-steps-modal a:hover .step-icon-circle i,
    .high-contrast #profile-steps-modal a:hover .step-icon-circle svg,
    .high-contrast #profile-steps-modal a:hover .step-add-btn i,
    .high-contrast #profile-steps-modal a:hover .step-add-btn svg {
        color: #000000 !important;
        stroke: #000000 !important;
    }
    .high-contrast #profile-steps-modal a {
        border: 2px solid transparent !important;
    }
    .high-contrast #profile-steps-modal a:not(:hover) .step-icon-circle,
    .high-contrast #profile-steps-modal a:not(:hover) .step-add-btn {
        background-color: #000000 !important;
        border: 2px solid #000000 !important;
    }
    .high-contrast #profile-steps-modal a:not(:hover) .step-icon-circle i,
    .high-contrast #profile-steps-modal a:not(:hover) .step-icon-circle svg,
    .high-contrast #profile-steps-modal a:not(:hover) .step-add-btn i,
    .high-contrast #profile-steps-modal a:not(:hover) .step-add-btn svg {
        color: #ffffff !important;
        stroke: #ffffff !important;
    }
    
    .high-contrast #profile-steps-modal .mt-2 a {
        background-color: #000000 !important;
        color: #ffffff !important;
        border: 2px solid #000000 !important;
        text-decoration: none !important;
    }
    .high-contrast #profile-steps-modal .mt-2 a:hover {
        background-color: #ffffff !important;
        color: #000000 !important;
    }

    html.dark .step-icon-circle { background-color: #334155 !important; color: #cbd5e1 !important; }
    html.dark .modal-overlay .bg-white { background-color: #1e293b !important; }
</style>
<div class="bg-gray-50 min-h-screen py-12 px-6 pb-24">
    <div class="max-w-7xl mx-auto">
       
        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="mb-4 p-4 rounded-xl bg-green-50 border border-green-100 text-green-700 text-sm font-medium flex items-center shadow-sm">
                <i data-feather="check-circle" class="w-4 h-4 mr-2"></i>
                {{ session('success') }}
            </div>
        @endif
 
        <!-- User Header Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
            <div class="h-32 bg-blue-50/50 border-b border-gray-100"></div>
           
            <div class="px-8 pb-8 relative">
                <div class="relative -top-12 flex flex-col md:flex-row md:items-start justify-between">
                   
                    <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
                        <!-- Avatar -->
                        <div class="relative">
                            <img src="{{ $user->avatar_url }}" alt="{{ $user->first_name }}" class="w-32 h-32 rounded-2xl border-4 border-white object-cover shadow-sm bg-white">
                        </div>
                       
                        <!-- Name and Bio Info: Aligned to start at the same place -->
                        <div class="flex flex-col items-center md:items-start mt-12 md:mt-14">
                            @if(auth()->id() === $user->id)
                                <div class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-700 px-3 py-1 rounded-full border border-blue-100 mb-2">
                                    <i data-feather="user" class="w-3.5 h-3.5"></i>
                                    <span class="text-xs font-bold uppercase tracking-wider">{{ __('public_profile.viewing_own_profile') }}</span>
                                </div>
                            @endif
                            <h1 class="text-2xl font-bold text-gray-900 leading-tight">
                                {{ $user->first_name }} {{ $user->last_name }}
                            </h1>
                           
                            <div class="flex flex-wrap justify-center md:justify-start items-center gap-4 mt-2 text-gray-500 font-medium">
                                <div class="flex items-center gap-1">
                                    <i data-feather="map-pin" class="w-3.5 h-3.5 text-blue-600"></i>
                                    <span class="text-sm">{{ $user->city->name ?? __('public_profile.remote') }}</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <i data-feather="calendar" class="w-3.5 h-3.5 text-blue-600"></i>
                                    <span class="text-sm">{{ __('public_profile.joined', ['date' => $user->created_at->format('M Y')]) }}</span>
                                </div>
                               
                                @if(auth()->id() !== $user->id)
                                    {{-- Report Flag beside Join Date --}}
                                    <button onclick="openUserReportModal({{ $user->id }})" class="flex items-center gap-1 text-gray-400 hover:text-red-500 transition-colors border-l pl-4 border-gray-200">
                                        <i data-feather="flag" class="w-3.5 h-3.5"></i>
                                        <span class="text-sm">{{ __('public_profile.report_user') }}</span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
 
                    <!-- Rating Badge -->
                    <div class="mt-6 md:mt-14 flex flex-col items-center md:items-end">
                        <div class="flex items-center bg-blue-50 px-3 py-1.5 rounded-lg border border-blue-100">
                             <span class="text-xl font-bold text-blue-700 mr-2">{{ $user->rating > 0 ? number_format($user->rating, 1) : __('public_profile.new') }}</span>
                             <div class="flex text-blue-600">
                                @for($i=1; $i<=5; $i++)
                                    <i data-feather="star" class="w-3.5 h-3.5 {{ $i <= round($user->rating) ? 'fill-current' : 'text-blue-200' }}"></i>
                                @endfor
                             </div>
                        </div>
                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-2">{{ __('public_profile.reviews_count', ['count' => $reviews->count()]) }}</span>
                    </div>
                </div>
            </div>
        </div>
 
        <!-- Reviews Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-1">
                @auth
                    @if($canReview && auth()->id() !== $user->id)
                    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 sticky top-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-6">{{ __('public_profile.leave_review') }}</h3>
                        <form action="{{ route('public-profile.review', $user->id) }}" method="POST">
                            @csrf
                            <div class="mb-6">
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">{{ __('public_profile.rating') }}</label>
                                <div class="flex gap-2 text-blue-100" id="star-rating" onmouseleave="resetStars()">
                                    @for($i=1; $i<=5; $i++)
                                        <label class="cursor-pointer" onmouseenter="highlightStars({{ $i }})">
                                            <input type="radio" name="stars" value="{{ $i }}" class="hidden" onclick="setRating({{ $i }})">
                                            <i data-feather="star" class="w-6 h-6 transition-transform hover:scale-110"></i>
                                        </label>
                                    @endfor
                                </div>
                                <input type="hidden" name="stars" id="stars-input" value="5">
                            </div>
                            <div class="mb-6">
                                <textarea name="comment" rows="3" class="w-full rounded-xl border-gray-100 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-50 transition-all text-sm @error('comment') border-red-500 ring-red-50 @enderror" placeholder="{{ __('public_profile.comment_placeholder_generic') }}" required></textarea>
                                @error('comment')
                                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-xl font-bold hover:bg-blue-700 transition shadow-sm shadow-blue-100">
                                {{ __('public_profile.post_review') }}
                            </button>
                        </form>
                    </div>
                    @endif
                @endauth
            </div>
 
            <div class="{{ ($canReview && auth()->id() !== $user->id) ? 'lg:col-span-2' : 'lg:col-span-3' }} space-y-4">
                <div class="flex items-center gap-4 mb-2">
                    <h3 class="text-xl font-bold text-gray-900">{{ __('public_profile.reviews', ['count' => $reviews->count()]) }}</h3>
                   
                    {{-- Inline Warning --}}
                    @auth
                        @if(!$canReview && auth()->id() !== $user->id)
                        <div class="flex items-center gap-1.5 bg-amber-50 text-amber-700 px-3 py-1 rounded-full border border-amber-100">
                            <i data-feather="info" class="w-3.5 h-3.5"></i>
                            <span class="text-xs font-medium">{{ __('public_profile.review_after_test_warning') }}</span>
                        </div>
                        @endif
                    @endauth
                </div>
               
                @forelse($reviews as $review)
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex gap-6">
                        <img src="{{ $review->reviewer->avatar_url }}" class="w-12 h-12 rounded-xl object-cover ring-4 ring-gray-50">
                        <div class="flex-1">
                            <div class="flex justify-between items-start mb-1">
                                <h4 class="font-bold text-gray-900 text-sm">{{ $review->reviewer->first_name }}</h4>
                                <span class="text-[10px] font-bold text-gray-300 uppercase tracking-tight">{{ $review->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="flex text-blue-500 mb-3">
                                @for($i=1; $i<=5; $i++)
                                    <i data-feather="star" class="w-3.5 h-3.5 {{ $i <= $review->stars ? 'fill-current' : 'text-blue-100' }}"></i>
                                @endfor
                            </div>
                            <p class="text-gray-600 text-sm leading-relaxed italic">"{{ $review->comment }}"</p>
                        </div>
                    </div>
                @empty
                    <!-- Restored Empty State Icon -->
                    <div class="text-center py-20 bg-white rounded-2xl border-2 border-dashed border-gray-100">
                        <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i data-feather="message-circle" class="w-8 h-8 text-blue-200"></i>
                        </div>
                        <p class="text-gray-400 text-sm font-medium">{{ __('public_profile.no_reviews', ['name' => $user->first_name]) }}</p>
                    </div>
                @endforelse
            </div>
        </div>
 
 
    </div>
</div>
 
@if(auth()->id() !== $user->id)
{{-- Sticky Bottom Bar --}}
<div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 z-50 shadow-[0_-4px_20px_rgba(0,0,0,0.08)]">
    <div class="max-w-7xl mx-auto flex items-center justify-between px-6 py-4">
        <div>
            <h4 class="font-bold text-gray-900 text-base">{{ __('public_profile.work_with_footer', ['name' => $user->first_name]) }}</h4>
            <p class="text-sm text-gray-500 hidden md:block">{{ __('public_profile.work_with_footer_action') }}</p>
        </div>
        @if(count($missingSteps) > 0)
            <button onclick="showProfileStepsModal()" class="btn bg-blue-600 text-white px-8 py-3.5 rounded-full font-bold hover:bg-blue-700 active:scale-95 transition-all text-sm whitespace-nowrap border-2 border-transparent profile-req-btn">
                {{ __('public_profile.request_quote') }}
            </button>
        @else
            <a href="{{ route('post-task', ['for_user' => $user->id]) }}" class="btn bg-blue-600 text-white px-8 py-3.5 rounded-full font-bold hover:bg-blue-700 active:scale-95 transition-all text-sm whitespace-nowrap border-2 border-transparent profile-req-btn">
                {{ __('public_profile.request_quote') }}
            </a>
        @endif
    </div>
</div>
@endif

{{-- Account Completion Modal (Reused from tasks page) --}}
@if(count($missingSteps) > 0)
<div id="profile-steps-modal" class="fixed inset-0 z-[100] hidden">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="closeProfileStepsModal()"></div>
    
    <!-- Modal Panel -->
    <div class="absolute inset-0 flex items-center justify-center p-4 pointer-events-none">
        <div class="bg-white w-full max-w-[420px] rounded-3xl shadow-2xl pointer-events-auto transform transition-all overflow-hidden">
            <!-- Close Header -->
            <div class="flex justify-end p-4 absolute right-0 top-0 z-10">
                <button onclick="closeProfileStepsModal()" class="p-2 hover:bg-gray-100 rounded-full transition-colors text-gray-400 hover:text-gray-600">
                    <i data-feather="x" class="w-5 h-5"></i>
                </button>
            </div>

            <div class="p-8 pt-10">
                <!-- Visual Icon (Top Center) -->
                <div class="flex justify-center mb-6">
                    <div class="relative">
                        <div class="w-20 h-20 rounded-2xl bg-blue-50 flex items-center justify-center">
                            <!-- Blue Shield -->
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 22C12 22 20 18 20 12V5L12 2L4 5V12C4 18 12 22 12 22Z" fill="#2563EB" stroke="#2563EB" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M9 12L11 14L15 10" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            
                            <!-- Decorative Element -->
                            <div class="absolute -top-1 -right-1 bg-white p-1 rounded-full shadow-sm">
                                <div class="w-6 h-6 bg-yellow-400 rounded-full flex items-center justify-center text-white">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Header Text -->
                <div class="text-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ __('tasks_page.before_offer_title') }}</h2>
                    <p class="text-gray-500 text-[15px] leading-relaxed">
                        {{ __('tasks_page.before_offer_desc') }}
                    </p>
                </div>

                <!-- Steps List -->
                <div class="space-y-2 mb-8">
                    @foreach($missingSteps as $step)
                        <!-- Single List Item -->
                        <a href="{{ route('profile') }}" class="flex items-center justify-between py-2 group cursor-pointer hover:bg-gray-50 rounded-xl px-2 transition-colors no-underline">
                            <div class="flex items-center gap-4">
                                <!-- Left Icon Circle -->
                                <div class="step-icon-circle">
                                    <i data-feather="{{ $step['icon'] }}" class="w-5 h-5"></i>
                                </div>
                                <!-- Text -->
                                <span class="text-gray-700 font-medium text-[15px]">{{ $step['text'] }}</span>
                            </div>
                            
                            <!-- Right Plus Button -->
                            <div class="step-add-btn">
                                <i data-feather="plus" class="w-4 h-4"></i>
                            </div>
                        </a>
                    @endforeach
                </div>

                <!-- Footer Button -->
                <div class="mt-2">
                  <a href="{{ route('profile') }}" class="btn block w-full py-3 bg-blue-50 hover:bg-blue-100 text-blue-600 font-bold text-center rounded-full transition-colors text-sm border-2 border-transparent">
                      {{ __('tasks_page.continue') }}
                  </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@include('components.user-report-modal')

<script>
    function showProfileStepsModal() {
        const modal = document.getElementById('profile-steps-modal');
        if (modal) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            if (window.feather) feather.replace();
        }
    }

    function closeProfileStepsModal() {
        const modal = document.getElementById('profile-steps-modal');
        if (modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        if(window.feather) feather.replace();
    });
 
    function highlightStars(count) {
        const icons = document.querySelectorAll('#star-rating i, #star-rating svg');
        icons.forEach((icon, idx) => {
             if (idx < count) {
                 icon.classList.add('text-blue-600', 'fill-current');
                 icon.classList.remove('text-blue-100');
             } else {
                 icon.classList.remove('text-blue-600', 'fill-current');
                 icon.classList.add('text-blue-100');
             }
        });
    }
 
    function resetStars() {
        const val = document.getElementById('stars-input').value || 5;
        highlightStars(val);
    }
 
    function setRating(val) {
        document.getElementById('stars-input').value = val;
        highlightStars(val);
    }
    resetStars();
</script>
@endsection