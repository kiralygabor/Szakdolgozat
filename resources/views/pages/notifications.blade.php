@extends('layout')

@section('title', __('notifications_page.title'))

@section('content')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/notifications.css') }}">
@endpush

<section class="py-12 notifications-container">
  <div class="max-w-4xl mx-auto px-6">
    
    <!-- Page Header -->
    <div class="notif-header-section flex items-center justify-between">
      <h1 class="text-3xl font-extrabold tracking-tight notif-title-glow">
        {{ __('notifications_page.title') }}
      </h1>
      
      @if(($notifications ?? collect())->isNotEmpty())
        <button id="mark-all-read" class="mark-all-read-trigger text-sm font-bold text-[var(--primary-accent)] hover:text-[var(--primary-hover)] flex items-center gap-2 px-4 py-2 rounded-full hover:bg-[var(--primary-accent-soft)] transition-all">
          <i data-feather="check-done" class="w-4 h-4"></i>
          {{ __('notifications_page.mark_all_read') }}
        </button>
      @endif
    </div>

    <!-- Notifications List Card -->
    <div class="notif-card-modern">
      <div class="notif-card-header">
        <span class="text-xs font-bold uppercase tracking-widest text-[var(--text-secondary)] opacity-60">
          {{ __('notifications_page.latest_updates') }}
        </span>
      </div>

      <div class="divide-y divide-[var(--border-base)]">
        @forelse($notifications ?? [] as $n)
          <a href="{{ $n->data['link'] ?? '#' }}" class="notif-item-modern {{ $n->read_at ? '' : 'unread' }}">
            <div class="notif-icon-wrapper">
              @php
                $icon = 'bell';
                if (($n->data['type'] ?? '') === 'success') $icon = 'check-circle';
                if (($n->data['type'] ?? '') === 'message') $icon = 'message-square';
                if (($n->data['type'] ?? '') === 'offer') $icon = 'tag';
              @endphp
              <i data-feather="{{ $icon }}" class="w-6 h-6"></i>
            </div>

            <div class="notif-content">
              <h3 class="notif-title">{{ $n->data['title'] ?? __('notifications_page.default_title') }}</h3>
              <p class="notif-message">{{ $n->data['message'] ?? '' }}</p>
              <div class="notif-time">{{ $n->created_at?->diffForHumans() }}</div>
            </div>
            
            <div class="flex items-center">
              <i data-feather="chevron-right" class="w-5 h-5 text-[var(--text-secondary)] opacity-20"></i>
            </div>
          </a>
        @empty
          <div class="notif-empty-state">
            <div class="notif-empty-icon">
              <i data-feather="bell-off" class="w-10 h-10"></i>
            </div>
            <h2 class="text-xl font-bold text-[var(--text-primary)] mb-2">{{ __('notifications_page.empty_state') }}</h2>
            <p class="text-[var(--text-secondary)] mb-8 max-w-sm mx-auto">
              You're all caught up! When you receive new offers or messages, they will appear here.
            </p>
            <a href="{{ route('tasks') }}" class="btn inline-flex items-center px-8 py-3 rounded-full bg-[var(--primary-accent)] text-white font-bold hover:bg-[var(--primary-hover)] transition-all transform hover:-translate-y-1 shadow-xl shadow-[var(--primary-accent-glow)]">
              {{ __('notifications_page.browse_tasks') }}
            </a>
          </div>
        @endforelse
      </div>
    </div>
  </div>
</section>

<script type="module">
  document.querySelectorAll('.mark-all-read-trigger').forEach(el => {
    el.addEventListener('click', async (e) => {
      e.preventDefault();
      if (typeof window.markNotificationsRead === 'function') {
        const success = await window.markNotificationsRead();
        if (success) window.location.reload();
      }
    });
  });
  
  // Refresh feather icons
  if (window.feather) feather.replace();
</script>
@endsection
