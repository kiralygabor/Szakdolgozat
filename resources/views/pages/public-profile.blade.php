@extends('layout')

@section('title', $user->first_name . '\'s Profile')

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="mb-4 p-4 rounded-xl bg-green-50 border border-green-100 text-green-700 text-sm font-medium flex items-center shadow-sm">
                <i data-feather="check-circle" class="w-4 h-4 mr-2"></i>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-4 p-4 rounded-xl bg-red-50 border border-red-100 text-red-700 text-sm font-medium flex items-center shadow-sm">
                <i data-feather="alert-circle" class="w-4 h-4 mr-2"></i>
                {{ session('error') }}
            </div>
        @endif

        <!-- User Header Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
            <div class="h-32 bg-gradient-to-r from-blue-600 to-indigo-700"></div>
            <div class="px-8 pb-8 relative">
                <div class="relative -top-16 flex items-end">
                    <!-- Avatar -->
                    <div class="relative">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->first_name }}" class="w-32 h-32 rounded-full border-4 border-white object-cover shadow-lg bg-white">
                        @else
                            <div class="w-32 h-32 rounded-full border-4 border-white bg-gray-200 flex items-center justify-center text-gray-500 font-bold text-4xl shadow-lg">
                                {{ substr($user->first_name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="-mt-12 flex flex-col md:flex-row justify-between items-start md:items-end">
                    <div>
                        <h1 class="text-3xl font-extrabold text-gray-900">{{ $user->first_name }} {{ $user->last_name }}</h1>
                        <div class="flex items-center gap-4 mt-2 text-gray-600">
                            <!-- Location -->
                            <div class="flex items-center gap-1">
                                <i data-feather="map-pin" class="w-4 h-4 text-gray-400"></i>
                                <span class="text-sm font-medium">{{ $user->city->name ?? 'Unknown Location' }}</span>
                            </div>
                            <!-- Joined Date -->
                            <div class="flex items-center gap-1">
                                <i data-feather="calendar" class="w-4 h-4 text-gray-400"></i>
                                <span class="text-sm font-medium">Joined {{ $user->created_at->format('M Y') }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Rating Badge -->
                    <div class="mt-4 md:mt-0 flex flex-col items-center md:items-end">
                         <div class="flex items-center bg-amber-50 px-3 py-1 rounded-full border border-amber-100">
                             <span class="text-2xl font-bold text-amber-500 mr-2">{{ $user->rating > 0 ? $user->rating : 'New' }}</span>
                             <div class="flex text-amber-400">
                                @for($i=1; $i<=5; $i++)
                                    <i data-feather="star" class="w-4 h-4 {{ $i <= round($user->rating) ? 'fill-current' : 'text-gray-300' }}"></i>
                                @endfor
                             </div>
                         </div>
                         <span class="text-xs text-gray-400 font-medium mt-1">{{ $reviews->count() }} reviews</span>
                         @auth
                            @if(auth()->id() !== $user->id)
                                <button onclick="openUserReportModal('{{ $user->account_id }}')" class="mt-3 text-red-500 hover:text-red-700 text-sm font-medium flex items-center transition-colors">
                                    <i data-feather="flag" class="w-4 h-4 mr-1"></i>
                                    Report User
                                </button>
                            @endif
                         @endauth
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            
            <!-- Left Column: Stats / Bio (Placeholder) -->
            <div class="space-y-6">
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wide mb-4">About</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        {{ $user->bio ?? 'This user hasn\'t written a bio yet.' }}
                    </p>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wide mb-4">Verification</h3>
                    <ul class="space-y-3">
                        <li class="flex items-center gap-3 text-sm text-gray-700">
                            <i data-feather="check-circle" class="w-5 h-5 text-green-500"></i>
                            Email Verified
                        </li>
                        <li class="flex items-center gap-3 text-sm text-gray-700">
                            <i data-feather="{{ $user->phone_number ? 'check-circle' : 'circle' }}" class="w-5 h-5 {{ $user->phone_number ? 'text-green-500' : 'text-gray-300' }}"></i>
                            Phone Verified
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Right Column: Reviews -->
            <div class="md:col-span-2 space-y-6">
                
                <!-- Review Form -->
                @auth
                    @if($canReview)
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Leave a Review</h3>
                        <form action="{{ route('public-profile.review', $user->id) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Rating</label>
                                <div class="flex gap-2 text-gray-300 transition-colors" id="star-rating" onmouseleave="resetStars()">
                                    @for($i=1; $i<=5; $i++)
                                        <label class="cursor-pointer" onmouseenter="highlightStars({{ $i }})">
                                            <input type="radio" name="stars" value="{{ $i }}" class="hidden" onclick="setRating({{ $i }})">
                                            <i data-feather="star" class="w-8 h-8 text-gray-300"></i>
                                        </label>
                                    @endfor
                                </div>
                                <input type="hidden" name="stars" id="stars-input" value="5">
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Comment</label>
                                <textarea name="comment" rows="3" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" placeholder="Share your experience working with {{ $user->first_name }}..." maxlength="150" required></textarea>
                            </div>
                            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-full font-semibold hover:bg-blue-700 transition">Post Review</button>
                        </form>
                    </div>
                    @else
                        @if(auth()->id() !== $user->id)
                        <div class="bg-blue-50 rounded-xl p-6 border border-blue-100 text-center">
                            <i data-feather="lock" class="w-6 h-6 text-blue-400 mx-auto mb-2"></i>
                            <p class="text-sm text-blue-800 font-medium">To leave a review, you must complete a task with {{ $user->first_name }}.</p>
                        </div>
                        @endif
                    @endif
                @endauth

                <!-- Reviews List -->
                <div class="space-y-4">
                    <h3 class="text-xl font-bold text-gray-900">Reviews ({{ $reviews->count() }})</h3>
                    
                    @forelse($reviews as $review)
                        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 flex gap-4">
                            <!-- Reviewer Avatar -->
                            <div class="shrink-0">
                                @if($review->reviewer && $review->reviewer->avatar)
                                    <img src="{{ asset('storage/' . $review->reviewer->avatar) }}" class="w-12 h-12 rounded-full object-cover">
                                @else
                                    <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 font-bold">
                                        {{ substr($review->reviewer->first_name ?? 'A', 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            
                            <div class="flex-1">
                                <div class="flex justify-between items-start mb-1">
                                    <h4 class="font-bold text-gray-900">{{ $review->reviewer->first_name ?? 'Anonymous' }} {{ $review->reviewer->last_name ?? '' }}</h4>
                                    <span class="text-xs text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="flex text-amber-400 mb-2">
                                    @for($i=1; $i<=5; $i++)
                                        <i data-feather="star" class="w-3 h-3 {{ $i <= $review->stars ? 'fill-current' : 'text-gray-300' }}"></i>
                                    @endfor
                                </div>
                                <p class="text-gray-600 text-sm leading-relaxed">
                                    {{ $review->comment }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10 bg-white rounded-xl border border-gray-100 border-dashed">
                            <i data-feather="message-square" class="w-10 h-10 text-gray-300 mx-auto mb-3"></i>
                            <p class="text-gray-500">No reviews yet. Be the first to review {{ $user->first_name }}!</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@include('components.user-report-modal')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if(window.feather) feather.replace();
    });

    function highlightStars(count) {
        const icons = document.querySelectorAll('#star-rating i, #star-rating svg');
        icons.forEach((icon, idx) => {
             if (idx < count) {
                 icon.classList.add('text-amber-500', 'fill-current');
                 icon.classList.remove('text-gray-300');
             } else {
                 icon.classList.remove('text-amber-500', 'fill-current');
                 icon.classList.add('text-gray-300');
             }
        });
    }

    function resetStars() {
        // Get currently selected input value, or default to 5
        const val = document.getElementById('stars-input').value || 5;
        highlightStars(val);
    }

    function setRating(val) {
        document.getElementById('stars-input').value = val;
        highlightStars(val);
    }
    
    // Initialize default state (5 stars)
    resetStars();
</script>
@endsection
