@extends('layout')

@section('title', __('notifications_page.title'))

@section('content')
<section class="py-8">
  <div class="max-w-5xl mx-auto px-6">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">{{ __('notifications_page.title') }}</h1>

    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm">
      <div class="p-4 border-b border-gray-200 flex items-center justify-between">
        <span class="text-sm text-gray-600">{{ __('notifications_page.latest_updates') }}</span>
        <a href="#" class="text-sm text-blue-600 hover:underline">{{ __('notifications_page.mark_all_read') }}</a>
      </div>
      <div class="divide-y divide-gray-200">
        @forelse(($notifications ?? []) as $n)
          <div class="p-4 flex items-start gap-3 hover:bg-gray-50">
            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 flex-shrink-0">!
            </div>
            <div class="flex-1">
              <p class="text-gray-900 font-medium">{{ $n->title ?? __('notifications_page.default_title') }}</p>
              <p class="text-sm text-gray-600">{{ $n->message ?? '' }}</p>
              <div class="text-xs text-gray-500 mt-1">{{ $n->created_at?->diffForHumans() }}</div>
            </div>
          </div>
        @empty
          <div class="p-8 text-center">
            <p class="text-gray-600 mb-4">{{ __('notifications_page.empty_state') }}</p>
            <a href="{{ route('tasks') }}" class="inline-flex items-center px-5 py-2.5 rounded-full bg-indigo-600 text-white hover:bg-indigo-700">{{ __('notifications_page.browse_tasks') }}</a>
          </div>
        @endforelse
      </div>
    </div>
  </div>
  </section>
@endsection


