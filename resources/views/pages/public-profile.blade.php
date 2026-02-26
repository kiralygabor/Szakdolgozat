@extends('layout')

@section('title', __('public_profile.title', ['name' => $user->first_name]))

@section('content')
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
                                    <span class="text-sm">{{ $user->city->name ?? 'Remote' }}</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <i data-feather="calendar" class="w-3.5 h-3.5 text-blue-600"></i>
                                    <span class="text-sm">{{ __('public_profile.joined', ['date' => $user->created_at->format('M Y')]) }}</span>
                                </div>
                                
                                @if(auth()->id() !== $user->id)
                                    {{-- Report Flag beside Join Date --}}
                                    <button onclick="openReportModal()" class="flex items-center gap-1 text-gray-400 hover:text-red-500 transition-colors border-l pl-4 border-gray-200">
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
                                <textarea name="comment" rows="3" class="w-full rounded-xl border-gray-100 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-50 transition-all text-sm" placeholder="{{ __('public_profile.comment_placeholder_generic') }}" required></textarea>
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
<div class="fixed bottom-0 left-0 right-0 bg-white/90 backdrop-blur-md border-t border-gray-100 p-4 z-50 shadow-[0_-4px_12px_rgba(0,0,0,0.05)]">
    <div class="max-w-7xl mx-auto flex items-center justify-between">
        <div>
            <h4 class="font-bold text-gray-900 text-base">{{ __('public_profile.work_with', ['name' => $user->first_name]) }}</h4>
            <p class="text-xs text-gray-500 hidden md:block">{{ __('public_profile.work_with_footer_desc') }}</p>
        </div>
        <a href="#" class="bg-blue-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-100 text-sm">
            {{ __('public_profile.contact_me', ['name' => $user->first_name]) }}
        </a>
    </div>
</div>
@endif

@include('components.user-report-modal')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if(window.feather) feather.replace();
    });

    function openReportModal() {
        const modal = document.getElementById('user-report-modal');
        if(modal) modal.classList.remove('hidden');
    }

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