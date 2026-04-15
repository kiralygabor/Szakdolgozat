@extends('layout')
 
@section('title', __('messages_page.title'))
 
@section('hideFooter', true)
 
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/messages.css') }}">
@endpush
 
@section('content')
  <!-- FIX 1: Increased the subtraction in calc() slightly to prevent page scroll -->
  <div class="max-w-7xl mx-auto px-0 md:px-6">
    <div class="bg-white border-0 md:border border-gray-200 rounded-none md:rounded-2xl shadow-sm overflow-hidden flex flex-col md:flex-row h-[calc(100dvh-56px)] md:h-[750px]">
 
      <!-- Sidebar (aligned with Logo) -->
      <aside class="{{ $activeConversation ? 'hidden md:flex' : 'flex' }} w-full md:w-1/5 border-r border-gray-200 flex flex-col h-full bg-white">
        <div class="p-4 border-b border-gray-100">
          <h2 class="text-xl font-bold text-gray-800 mb-4">{{ __('messages_page.title') }}</h2>
          <div class="relative">
            <i data-feather="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-4 h-4"></i>
            <input type="text" placeholder="{{ __('messages_page.search_placeholder') }}" class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm">
          </div>
        </div>
 
        <div class="flex-1 overflow-y-auto custom-scrollbar">
            @forelse($conversations as $conversation)
                @php
                    $otherUser = $conversation->getOtherUser(Auth::id());
                    $lastMessage = $conversation->messages->first();
                    $isActive = $activeConversation && $activeConversation->id === $conversation->id;
                @endphp
                <a id="conv-{{ $conversation->id }}" href="?user_id={{ $otherUser->id }}" class="block p-4 hover:bg-gray-50 transition border-b border-gray-50 {{ $isActive ? 'bg-blue-50 border-l-4 border-l-blue-600' : '' }}">
                    <div class="flex items-center gap-3">
                        <div class="relative shrink-0">
                            <img src="{{ $otherUser->avatar_url }}" class="w-12 h-12 rounded-full object-cover border border-gray-200 shadow-sm">
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-baseline mb-1">
                                <h3 class="font-bold text-gray-900 truncate">{{ $otherUser->first_name }} {{ $otherUser->last_name }}</h3>
                                @if($lastMessage)
                                    <span class="text-xs text-gray-400 whitespace-nowrap conversation-time">{{ $lastMessage->created_at->diffForHumans(null, true, true) }}</span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-500 truncate conversation-snippet {{ $lastMessage && !$lastMessage->is_read && $lastMessage->sender_id !== Auth::id() ? 'font-bold text-gray-800' : '' }}">
                                {{ $lastMessage ? $lastMessage->body : __('messages_page.start_conversation') }}
                            </p>
                        </div>
                    </div>
                </a>
            @empty
                <div class="p-8 text-center text-gray-500">
                    <i data-feather="message-square" class="mx-auto mb-3 opacity-50 w-10 h-10"></i>
                    <p>{{ __('messages_page.no_conversations') }}</p>
                </div>
            @endforelse
        </div>
      </aside>
 
      <!-- Main Chat Area (aligned with Nav Links) -->
      <main class="{{ $activeConversation ? 'flex' : 'hidden md:flex' }} flex-1 flex flex-col h-full bg-white relative overflow-hidden">
        @if($activeConversation)
            @php $chatUser = $activeConversation->getOtherUser(Auth::id()); @endphp
 
            <!-- Chat Header -->
            <div class="p-3 md:p-4 border-b border-gray-200 flex items-center justify-between bg-white z-10 flex-shrink-0">
                <div class="flex items-center gap-2 md:gap-3">
                    <!-- Back button for mobile -->
                    <a href="{{ url()->current() }}" class="md:hidden p-1 text-gray-500 hover:text-gray-700">
                        <i data-feather="arrow-left" class="w-6 h-6"></i>
                    </a>
                    <div class="relative shrink-0">
                        <img src="{{ $chatUser->avatar_url }}" class="w-10 h-10 rounded-full object-cover border border-gray-200 shadow-sm">
                    </div>
                    <div class="min-w-0">
                        <h2 class="font-bold text-gray-900 truncate">{{ $chatUser->first_name }} {{ $chatUser->last_name }}</h2>
                        <span class="text-xs text-green-500 font-medium flex items-center gap-1">
                            <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span> {{ __('messages_page.online') }}
                        </span>
                    </div>
                </div>
 
                {{-- Actions --}}
                <div class="relative">
                    <button id="chat-options-btn" class="p-2 hover:bg-gray-100 rounded-full text-gray-400 hover:text-gray-600 transition">
                        <i data-feather="more-vertical" class="w-5 h-5"></i>
                    </button>
                    <!-- Dropdown Menu -->
                    <div id="chat-options-dropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 hidden z-50 overflow-hidden">
                        <a href="{{ route('public-profile', $chatUser->id) }}" class="flex items-center gap-2 px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition border-b border-gray-50 no-underline">
                            <i data-feather="user" class="w-4 h-4"></i> {{ __('messages_page.view_profile') }}
                        </a>
                        <button onclick="openUserReportModal({{ $chatUser->id }})" class="w-full flex items-center gap-2 px-4 py-3 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 transition border-0 bg-transparent text-left">
                            <i data-feather="flag" class="w-4 h-4"></i> {{ __('messages_page.report_user') }}
                        </button>
                    </div>
                </div>
            </div>
 
            <!-- Messages List -->
            <!-- flex-1 ensures it takes available space, overflow-y-auto makes IT scroll, not the page -->
            <div id="messages-container" class="flex-1 min-h-0 overflow-y-auto overflow-x-hidden p-4 space-y-4 bg-gray-50">
                <div class="flex justify-center py-4">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                </div>
            </div>
 
            <!-- Input Area -->
            <!-- flex-shrink-0 prevents it from being squashed or pushed out -->
            <div class="px-4 pt-3 pb-2 bg-white border-t border-gray-200 flex-shrink-0 z-20">
                <div id="attachment-preview" class="hidden mb-2 px-2"></div>
                <form id="message-form" class="flex items-center gap-2">
                    <input type="file" id="attachment-input" class="hidden" accept="image/*,.pdf,.doc,.docx">
                    <div class="flex-1 relative">
                        <textarea id="message-input" rows="1" placeholder="{{ __('messages_page.type_placeholder') }}" class="w-full pl-4 pr-12 py-3 bg-gray-100 border-0 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:bg-white transition resize-none custom-scrollbar" style="min-height: 48px; max-height: 120px;"></textarea>
                        <button type="button" id="attachment-btn" class="absolute right-3 top-1/2 transform -translate-y-1/2 -mt-0.5 text-gray-400 hover:text-blue-600 transition">
                            <i data-feather="paperclip" class="w-5 h-5"></i>
                        </button>
                    </div>
                    <button type="submit" class="h-12 w-12 flex items-center justify-center bg-blue-600 text-white rounded-full hover:bg-blue-700 transition shadow-lg hover:shadow-blue-200 flex-shrink-0 mb-1">
                        <i data-feather="send" class="w-5 h-5 ml-0.5"></i>
                    </button>
                </form>
            </div>
        @else
            <!-- Empty State -->
            <div class="flex-1 flex flex-col items-center justify-center text-gray-500 p-8 bg-gray-50/30">
                <div class="w-24 h-24 bg-blue-50/50 rounded-full flex items-center justify-center mb-6 shadow-sm ring-8 ring-white">
                    <i data-feather="message-circle" class="w-12 h-12 text-blue-500"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">{{ __('messages_page.select_chat_title') }}</h2>
                <p class="text-center max-w-md text-gray-500 leading-relaxed">{{ __('messages_page.select_chat_desc') }}</p>
                @if($conversations->isEmpty())
                    <div class="mt-8 flex flex-col items-center">
                        <p class="text-sm text-gray-400 mb-4">{{ __('messages_page.no_active_chats') }}</p>
                        <a href="{{ route('tasks') }}" class="btn inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-200 no-underline">
                            <i data-feather="search" class="w-4 h-4"></i>
                            {{ __('navbar.browse_tasks') }}
                        </a>
                    </div>
                @endif
            </div>
        @endif
      </main>
    </div>
  </div>
</section>
 
<!-- The JS remains exactly the same as the previous version -->
@push('scripts')
<script>
    window.MESSAGES_CONFIG = {
        authUserId: "{{ Auth::id() }}",
        authUserAvatar: "{{ Auth::user()->avatar_url }}",
        conversationId: "{{ $activeConversation ? $activeConversation->id : '' }}",
        appUrl: "{{ url('/') }}/",
        storageUrl: "{{ asset('storage') }}",
        otherUserAvatar: "{{ $activeConversation ? $activeConversation->getOtherUser(Auth::id())->avatar_url : '' }}",
        csrfToken: "{{ csrf_token() }}",
        translations: {
            failedLoad: "{{ __('messages_page.failed_load') }}",
            deleted: "{{ __('messages_page.deleted') }}",
            attachment: "{{ __('messages_page.attachment') }}",
            sentImage: "{{ __('messages_page.sent_image') }}",
            sentFile: "{{ __('messages_page.sent_file') }}",
            delete: "{{ __('messages_page.delete') }}",
            deletedMessage: "Message deleted"
        }
    };
</script>
<script src="{{ asset('js/pages/messages.js') }}"></script>
@endpush
 
@include('components.user-report-modal')
 
@endsection