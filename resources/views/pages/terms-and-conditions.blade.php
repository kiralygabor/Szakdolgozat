@extends('layout')
 
@section('content')
<div class="min-h-screen bg-gray-50 flex flex-col items-center pt-10 pb-20">
    <div class="w-full max-w-4xl px-6 bg-white py-12 px-8 rounded-xl shadow-sm">
        <h1 class="text-4xl font-bold text-blue-900 mb-8">{{ __('static_pages.terms_conditions.title') }}</h1>
       
        <div class="prose max-w-none text-gray-700 space-y-6">
            <p>{{ __('static_pages.terms_conditions.intro') }}</p>
           
            <h2 class="text-2xl font-semibold text-blue-800 mt-8 mb-4">{{ __('static_pages.terms_conditions.section1_title') }}</h2>
            <p>{{ __('static_pages.terms_conditions.section1_body') }}</p>
           
            <h2 class="text-2xl font-semibold text-blue-800 mt-8 mb-4">{{ __('static_pages.terms_conditions.section2_title') }}</h2>
            <ul class="list-disc pl-6 space-y-2">
                <li>{{ __('static_pages.terms_conditions.section2_li1') }}</li>
                <li>{{ __('static_pages.terms_conditions.section2_li2') }}</li>
                <li>{{ __('static_pages.terms_conditions.section2_li3') }}</li>
            </ul>
           
            <h2 class="text-2xl font-semibold text-blue-800 mt-8 mb-4">{{ __('static_pages.terms_conditions.section3_title') }}</h2>
            <p>{{ __('static_pages.terms_conditions.section3_body') }}</p>
           
            <h2 class="text-2xl font-semibold text-blue-800 mt-8 mb-4">{{ __('static_pages.terms_conditions.section4_title') }}</h2>
            <p>{{ __('static_pages.terms_conditions.section4_body') }}</p>
           
            <p class="text-sm text-gray-500 mt-12 bg-gray-50 p-4 rounded text-center">
                {{ __('static_pages.contact_support.last_updated', ['date' => date('Y-m-d')]) }}
            </p>
        </div>
    </div>
</div>
@endsection