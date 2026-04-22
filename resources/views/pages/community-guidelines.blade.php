@extends('layout')
 
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/static-pages.css') }}">
@endpush

@section('content')
<div class="static-container min-h-screen static-bg flex flex-col items-center pt-10 pb-20">
    <div class="w-full max-w-4xl px-6 static-card py-12 px-8 rounded-xl shadow-sm">
        <h1 class="text-4xl font-bold static-accent mb-8">{{ __('static_pages.community_guidelines.title') }}</h1>
       
        <div class="prose max-w-none static-text-main space-y-6">
            <p>{{ __('static_pages.community_guidelines.intro') }}</p>
           
            <h2 class="text-2xl font-semibold static-accent mt-8 mb-4">{{ __('static_pages.community_guidelines.section1_title') }}</h2>
            <p>{{ __('static_pages.community_guidelines.section1_body') }}</p>
           
            <h2 class="text-2xl font-semibold static-accent mt-8 mb-4">{{ __('static_pages.community_guidelines.section2_title') }}</h2>
            <p>{{ __('static_pages.community_guidelines.section2_body') }}</p>
           
            <h2 class="text-2xl font-semibold static-accent mt-8 mb-4">{{ __('static_pages.community_guidelines.section3_title') }}</h2>
            <p>{{ __('static_pages.community_guidelines.section3_body') }}</p>
           
            <h2 class="text-2xl font-semibold static-accent mt-8 mb-4">{{ __('static_pages.community_guidelines.section4_title') }}</h2>
            <p>{{ __('static_pages.community_guidelines.section4_body') }}</p>
           
            <h2 class="text-2xl font-semibold static-accent mt-8 mb-4">{{ __('static_pages.community_guidelines.section5_title') }}</h2>
            <p>{{ __('static_pages.community_guidelines.section5_body') }}</p>
           
            <p class="text-sm static-text-muted mt-12 static-bg p-4 rounded text-center">
                {{ __('static_pages.contact_support.last_updated', ['date' => date('Y-m-d')]) }}
            </p>
        </div>
    </div>
</div>
@endsection