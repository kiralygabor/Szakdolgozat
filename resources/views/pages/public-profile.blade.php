@extends('layout')
 
@section('title', __('public_profile.title', ['name' => $user->first_name]))
 
@push('styles')
    <link href="{{ asset('css/pages/public-profile.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="public-profile-container min-h-screen py-12 px-6 pb-40 settings-container">
    <div class="max-w-7xl mx-auto">
       
        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="mb-4 p-4 details-alert-success flex items-center shadow-sm">
                <i data-feather="check-circle" class="w-4 h-4 mr-2"></i>
                {{ session('success') }}
            </div>
        @endif
 
        <!-- User Header Card -->
        <div class="public-card rounded-2xl shadow-sm overflow-hidden mb-8">
            <div class="h-32 public-header-hero border-b"></div>
           
            <div class="px-8 pb-8 relative">
                <div class="relative -top-12 flex flex-col md:flex-row md:items-start justify-between">
                   
                    <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
                        <!-- Avatar -->
                        <div class="relative">
                            <img src="{{ $user->avatar_url }}" alt="{{ $user->first_name }}" class="w-32 h-32 rounded-2xl border-4 var(--profile-card-bg) object-cover shadow-sm profile-bg border-[var(--profile-bg)]">
                        </div>
                       
                        <!-- Name and Bio Info: Aligned to start at the same place -->
                        <div class="flex flex-col items-center md:items-start mt-12 md:mt-14">
                            @if(auth()->id() === $user->id)
                                <div class="inline-flex items-center gap-1.5 profile-info-badge px-3 py-1 rounded-full mb-2">
                                    <i data-feather="user" class="w-3.5 h-3.5"></i>
                                    <span class="text-xs font-bold uppercase tracking-wider">{{ __('public_profile.viewing_own_profile') }}</span>
                                </div>
                            @endif
                            <h1 class="text-2xl font-bold profile-text-main leading-tight">
                                {{ $user->first_name }} {{ $user->last_name }}
                            </h1>
                           
                            <div class="flex flex-wrap justify-center md:justify-start items-center gap-4 mt-2 profile-text-muted font-medium">
                                <div class="flex items-center gap-1">
                                    <i data-feather="map-pin" class="w-3.5 h-3.5 star-icon"></i>
                                    <span class="text-sm font-semibold">{{ $user->city->name ?? __('public_profile.remote') }}</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <i data-feather="calendar" class="w-3.5 h-3.5 star-icon"></i>
                                    <span class="text-sm font-semibold">{{ __('public_profile.joined', ['date' => $user->created_at->format('M Y')]) }}</span>
                                </div>
                               
                                @if(auth()->id() !== $user->id)
                                    {{-- Report Flag beside Join Date --}}
                                    <button onclick="openUserReportModal({{ $user->id }})" class="flex items-center gap-1 profile-text-muted hover:text-[var(--details-error)] transition-colors border-l pl-4 profile-border-color">
                                        <i data-feather="flag" class="w-3.5 h-3.5"></i>
                                        <span class="text-sm font-semibold">{{ __('public_profile.report_user') }}</span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
 
                    <!-- Rating Badge -->
                    <div class="mt-6 md:mt-14 flex flex-col items-center md:items-end">
                        <div class="flex items-center rating-badge-container px-3 py-1.5 rounded-lg shadow-sm">
                             <span class="text-xl font-bold rating-value {{ $user->rating > 0 ? 'mr-2' : '' }} text-white">{{ $user->rating > 0 ? number_format($user->rating, 1) : __('public_profile.new') }}</span>
                             @if($user->rating > 0)
                                <i data-feather="star" class="w-5 h-5 fill-white text-white"></i>
                             @endif
                             </div>
                        <span class="text-[10px] profile-text-muted font-bold uppercase tracking-widest mt-2">{{ __('public_profile.reviews_count', ['count' => $reviews->count()]) }}</span>
                    </div>
                </div>
            </div>
        </div>
 
        <!-- Reviews Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            <div class="{{ ($canReview && auth()->id() !== $user->id) ? 'lg:col-span-2' : 'lg:col-span-3' }} space-y-4">
                <div class="flex items-center gap-4 mb-2">
                    <h3 class="text-xl font-bold profile-text-main">{{ __('public_profile.reviews', ['count' => $reviews->count()]) }}</h3>
                   
                    {{-- Inline Warning --}}
                    @auth
                        @if(!$canReview && auth()->id() !== $user->id)
                        <div class="flex items-center gap-1.5 profile-info-badge px-3 py-1 rounded-full border profile-border-color">
                            <i data-feather="info" class="w-3.5 h-3.5"></i>
                            <span class="text-xs font-semibold">{{ __('public_profile.review_after_test_warning') }}</span>
                        </div>
                        @endif
                    @endauth
                </div>
               
                @forelse($reviews as $review)
                    <div class="public-card rounded-2xl p-6 shadow-sm flex gap-6">
                        <img src="{{ $review->reviewer->avatar_url }}" class="w-12 h-12 rounded-xl object-cover ring-4 profile-header-bg">
                        <div class="flex-1">
                            <div class="flex justify-between items-start mb-1">
                                <h4 class="font-bold profile-text-main text-sm">{{ $review->reviewer->first_name }}</h4>
                                <span class="text-[10px] font-bold profile-text-muted uppercase tracking-tight">{{ $review->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="flex mb-3">
                                @for($i=1; $i<=5; $i++)
                                    <i data-feather="star" class="w-3.5 h-3.5 star-icon {{ $i <= $review->stars ? 'fill-current' : 'empty' }}"></i>
                                @endfor
                            </div>
                            <p class="profile-text-muted text-sm leading-relaxed italic">"{{ $review->comment }}"</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-20 public-card rounded-2xl">
                        <div class="w-16 h-16 profile-info-badge rounded-full flex items-center justify-center mx-auto mb-4">
                            <i data-feather="message-circle" class="w-8 h-8 star-icon"></i>
                        </div>
                        <p class="profile-text-muted text-sm font-bold">{{ __('public_profile.no_reviews', ['name' => $user->first_name]) }}</p>
                    </div>
                @endforelse
            </div>

            <div class="lg:col-span-1">
                @auth
                    @if($canReview && auth()->id() !== $user->id)
                    <div class="public-card rounded-2xl shadow-sm p-6 sticky top-6 z-10">
                        <h3 class="text-lg font-bold profile-text-main mb-6">{{ __('public_profile.leave_review') }}</h3>
                        <form action="{{ route('public-profile.review', $user->id) }}" method="POST">
                            @csrf
                            <div class="mb-6">
                                <label class="block text-[10px] font-bold profile-text-muted uppercase tracking-widest mb-3">{{ __('public_profile.rating') }}</label>
                                <div class="flex gap-2" id="star-rating">
                                    @for($i=1; $i<=5; $i++)
                                        <button type="button" class="cursor-pointer" data-rating="{{ $i }}">
                                            <i data-feather="star" class="w-6 h-6 star-icon transition-transform hover:scale-110"></i>
                                        </button>
                                    @endfor
                                </div>
                                <input type="hidden" name="stars" id="stars-input" value="5">
                            </div>
                            <div class="mb-6">
                                <textarea name="comment" rows="3" class="w-full rounded-xl profile-border-color profile-bg focus:profile-bg focus:border-[var(--profile-accent)] focus:ring-4 focus:ring-[var(--primary-accent-focus)] transition-all text-sm profile-text-main @error('comment') border-red-500 ring-red-50 @enderror" placeholder="{{ __('public_profile.comment_placeholder_generic') }}" required></textarea>
                            </div>
                            <button type="submit" class="w-full bg-[var(--profile-accent)] text-white py-3 rounded-xl font-bold hover:bg-[var(--primary-hover)] transition shadow-sm border-2 border-transparent">
                                {{ __('public_profile.post_review') }}
                            </button>
                        </form>
                    </div>
                    @endif
                @endauth
            </div>
        </div>
 
 
    </div>
</div>
 
@if(auth()->id() !== $user->id)
@php
    $missingSteps = auth()->check() ? auth()->user()->getMissingProfileSteps() : [];
@endphp
{{-- Sticky Bottom Bar --}}
<div class="fixed bottom-0 left-0 right-0 sticky-footer-bar z-[1000] border-t shadow-[0_-5px_15px_-3px_rgba(0,0,0,0.05)]">
    <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between px-6 py-3 gap-4">
        <div class="text-center sm:text-left">
            <h4 class="font-bold profile-text-main text-base">{{ __('public_profile.work_with_footer', ['name' => $user->first_name]) }}</h4>
            <p class="text-[10px] profile-text-muted uppercase tracking-wider font-bold">{{ __('public_profile.work_with_footer_action') }}</p>
        </div>
        <div class="flex items-center gap-4">
            @if(count($missingSteps) > 0)
                <button onclick="openProfileStepsModal()" class="w-full sm:w-auto bg-indigo-600 text-white px-8 py-2.5 rounded-full font-bold hover:bg-indigo-700 active:scale-95 transition-all text-sm whitespace-nowrap shadow-sm">
                    {{ __('public_profile.request_quote') }}
                </button>
            @else
                <a href="{{ route('post-task', ['for_user' => $user->id]) }}" class="w-full sm:w-auto bg-indigo-600 text-white px-8 py-2.5 rounded-full font-bold hover:bg-indigo-700 active:scale-95 transition-all text-sm whitespace-nowrap shadow-sm">
                    {{ __('public_profile.request_quote') }}
                </a>
            @endif
        </div>
    </div>
</div>
@endif

@include('partials.profile-steps-modal')

@include('components.user-report-modal')

<script type="module">
    import { PublicProfileManager } from '{{ asset('js/components/public-profile-manager.js') }}';
    document.addEventListener('DOMContentLoaded', () => {
        new PublicProfileManager();
    });
</script>
@endsection