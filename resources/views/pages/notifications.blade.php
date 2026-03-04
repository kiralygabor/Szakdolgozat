@extends('layout')

@section('title', __('notifications_page.title'))

@section('content')
<section class="py-8">
  <div class="max-w-5xl mx-auto px-6">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">{{ __('notifications_page.title') }}</h1>

    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm">
      <div class="p-4 border-b border-gray-200 flex items-center justify-between">
        <span class="text-sm text-gray-600">{{ __('notifications_page.latest_updates') }}</span>
        <a href="#" id="mark-all-read" class="text-sm text-blue-600 hover:underline">{{ __('notifications_page.mark_all_read') }}</a>
      </div>
      <div class="divide-y divide-gray-200">
        @forelse(($notifications ?? []) as $n)
          <a href="{{ $n->data['link'] ?? '#' }}" class="p-4 flex items-start gap-3 hover:bg-gray-50 no-underline transition-colors {{ $n->read_at ? 'opacity-60' : 'bg-blue-50/20' }}">
            <div class="w-10 h-10 rounded-full {{ ($n->data['type'] ?? '') === 'success' ? 'bg-green-100 text-green-600' : 'bg-blue-100 text-blue-600' }} flex items-center justify-center flex-shrink-0">
              <i data-feather="{{ ($n->data['type'] ?? '') === 'success' ? 'check-circle' : 'bell' }}" class="w-5 h-5"></i>
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-gray-900 font-bold mb-0 truncate">{{ $n->data['title'] ?? __('notifications_page.default_title') }}</p>
              <p class="text-sm text-gray-600 mb-0 line-clamp-2">{{ $n->data['message'] ?? '' }}</p>
              <div class="text-[10px] text-gray-400 mt-1 uppercase font-semibold tracking-wider">{{ $n->created_at?->diffForHumans() }}</div>
            </div>
          </a>
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
  <script>
    document.getElementById('mark-all-read').addEventListener('click', function(e) {
      e.preventDefault();
      
      fetch('{{ route("notifications.mark-read") }}', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        },
        body: JSON.stringify({})
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          window.location.reload();
        }
      })
      .catch(error => {
        console.error('Error marking notifications as read:', error);
      });
    });
  </script>
@endsection


