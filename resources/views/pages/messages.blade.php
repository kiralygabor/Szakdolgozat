@extends('layout')
 
@section('title', __('messages_page.title'))
 
@section('hideFooter', true)
 
@section('content')
<style>
    /* Smooth fade in/out for the delete button */
    .delete-overlay {
        opacity: 0;
        visibility: hidden;
        transform: translateY(5px);
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
 
    /* Apply hover only directly on the bubble container, not the full width row */
    .message-bubble-container:hover .delete-overlay {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }
 
    /* Tighten footer for messages page */
    footer {
        padding-top: 2rem !important;
    }
</style>
 
<section class="py-0 md:py-8 bg-gray-50">
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
                <a href="?user_id={{ $otherUser->id }}" class="block p-4 hover:bg-gray-50 transition border-b border-gray-50 {{ $isActive ? 'bg-blue-50 border-l-4 border-l-blue-600' : '' }}">
                    <div class="flex items-center gap-3">
                        <div class="relative shrink-0">
                            <img src="{{ $otherUser->avatar_url }}" class="w-12 h-12 rounded-full object-cover border border-gray-200 shadow-sm">
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-baseline mb-1">
                                <h3 class="font-bold text-gray-900 truncate">{{ $otherUser->first_name }} {{ $otherUser->last_name }}</h3>
                                @if($lastMessage)
                                    <span class="text-xs text-gray-400 whitespace-nowrap">{{ $lastMessage->created_at->diffForHumans(null, true, true) }}</span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-500 truncate {{ $lastMessage && !$lastMessage->is_read && $lastMessage->sender_id !== Auth::id() ? 'font-bold text-gray-800' : '' }}">
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
      <main class="{{ $activeConversation ? 'flex' : 'hidden md:flex' }} flex-1 flex flex-col h-full bg-white relative overflow-hidden md:pl-10">
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
                        <button class="w-full flex items-center gap-2 px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition border-0 bg-transparent text-left">
                            <i data-feather="flag" class="w-4 h-4"></i> {{ __('messages_page.report_user') }}
                        </button>
                    </div>
                </div>
            </div>
 
            <!-- Messages List -->
            <!-- flex-1 ensures it takes available space, overflow-y-auto makes IT scroll, not the page -->
            <div id="messages-container" class="flex-1 min-h-0 overflow-y-auto overflow-x-hidden p-6 space-y-4 bg-gray-50">
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
                <h2 class="text-2xl font-bold text-gray-800 mb-2">{{ __('messages_page.select_chat_title') ?? 'Your Messages' }}</h2>
                <p class="text-center max-w-md text-gray-500 leading-relaxed">{{ __('messages_page.select_chat_desc') ?? 'Choose a conversation from the sidebar to start messaging, or browse tasks to find new opportunities.' }}</p>
                @if($conversations->isEmpty())
                    <div class="mt-8 flex flex-col items-center">
                        <p class="text-sm text-gray-400 mb-4">{{ __('messages_page.no_active_chats') ?? 'You don\'t have any active messages yet.' }}</p>
                        <a href="{{ route('tasks') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-200 no-underline">
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (window.feather) window.feather.replace();
 
        const authUserId = "{{ Auth::id() }}";
        const authUserAvatar = "{{ Auth::user()->avatar_url }}";
        const conversationId = "{{ $activeConversation ? $activeConversation->id : '' }}";
        const appUrl = "{{ url('/') }}/";
        let otherUserAvatar = "";
        @if($activeConversation)
            otherUserAvatar = "{{ $activeConversation->getOtherUser(Auth::id())->avatar_url }}";
        @endif
        const messagesContainer = document.getElementById('messages-container');
        const messageForm = document.getElementById('message-form');
        const messageInput = document.getElementById('message-input');
 
        // --- UI Handlers ---
        const chatOptionsBtn = document.getElementById('chat-options-btn');
        const chatOptionsDropdown = document.getElementById('chat-options-dropdown');
 
        if (chatOptionsBtn && chatOptionsDropdown) {
            chatOptionsBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                chatOptionsDropdown.classList.toggle('hidden');
            });
            document.addEventListener('click', (e) => {
                if (!chatOptionsDropdown.contains(e.target) && !chatOptionsBtn.contains(e.target)) {
                    chatOptionsDropdown.classList.add('hidden');
                }
            });
        }
 
        if(messageInput) {
            messageInput.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            });
            messageInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    messageForm.dispatchEvent(new Event('submit'));
                }
            });
        }
 
        const attachmentInput = document.getElementById('attachment-input');
        const attachmentBtn = document.getElementById('attachment-btn');
        const attachmentPreview = document.getElementById('attachment-preview');
 
        if(attachmentBtn) attachmentBtn.addEventListener('click', () => attachmentInput.click());
 
        if(attachmentInput) {
            attachmentInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            attachmentPreview.innerHTML = `
                                <div class="relative inline-block">
                                    <img src="${e.target.result}" class="h-20 w-20 object-cover rounded-lg border border-gray-200">
                                    <button type="button" onclick="clearAttachment()" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 shadow-sm hover:bg-red-600">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                    </button>
                                </div>`;
                        }
                        reader.readAsDataURL(file);
                    } else {
                        attachmentPreview.innerHTML = `
                            <div class="relative inline-block bg-gray-100 px-3 py-2 rounded-lg border border-gray-200 text-sm text-gray-700">
                                <span>${file.name}</span>
                                <button type="button" onclick="clearAttachment()" class="ml-2 text-red-500 font-bold">&times;</button>
                            </div>`;
                    }
                    attachmentPreview.classList.remove('hidden');
                }
            });
        }
 
        window.clearAttachment = function() {
            if(attachmentInput) attachmentInput.value = '';
            if(attachmentPreview) {
                attachmentPreview.innerHTML = '';
                attachmentPreview.classList.add('hidden');
            }
        }
 
        // --- Sending Logic ---
        if(messageForm) {
            messageForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const body = messageInput.value.trim();
                const file = attachmentInput.files[0];
 
                if (!body && !file) return;
 
                const formData = new FormData();
                formData.append('body', body);
                if (file) formData.append('attachment', file);
 
                let tempMsg = {
                    id: 'temp-' + Date.now(),
                    sender_id: authUserId,
                    body: body,
                    created_at: new Date().toISOString(),
                    is_temp: true
                };
 
                if (file && file.type.startsWith('image/')) {
                    tempMsg.attachment = URL.createObjectURL(file);
                    tempMsg.attachment_type = 'image';
                } else if (file) {
                    tempMsg.attachment = '#';
                    tempMsg.attachment_type = 'file';
                    tempMsg.attachment_name = file.name;
                }
 
                appendMessage(tempMsg);
 
                messageInput.value = '';
                messageInput.style.height = 'auto';
                clearAttachment();
                scrollToBottom();
 
                fetch(`${appUrl}conversations/${conversationId}/messages`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    lastMessageId = data.id;
                    const tempEl = document.getElementById(`message-${tempMsg.id}`);
                    if(tempEl) {
                        tempEl.id = `message-${data.id}`;
                        const delBtn = tempEl.querySelector('.delete-overlay button');
                        if(delBtn) delBtn.setAttribute('onclick', `deleteMessage('${data.id}', this)`);
                    }
                })
                .catch(err => console.error(err));
            });
        }
 
        // --- Polling Logic ---
        let lastMessageId = 0;
        if (conversationId) {
            loadMessages();
            setInterval(checkNewMessages, 3000);
        }
 
        function loadMessages() {
            fetch(`${appUrl}conversations/${conversationId}`)
                .then(res => res.json())
                .then(data => {
                    messagesContainer.innerHTML = '';
                    data.messages.forEach(msg => {
                        appendMessage(msg);
                        lastMessageId = Math.max(lastMessageId, msg.id);
                    });
                    scrollToBottom();
                })
                .catch(err => {
                    console.error("Failed to load messages:", err);
                    messagesContainer.innerHTML = '<div class="p-8 text-center text-red-500">Failed to load messages.</div>';
                });
        }
 
        function checkNewMessages() {
            fetch(`${appUrl}conversations/${conversationId}/check?last_message_id=${lastMessageId}`)
                .then(res => res.json())
                .then(messages => {
                    if (messages.length > 0) {
                        messages.forEach(msg => {
                            if (String(msg.sender_id) !== String(authUserId)) {
                                appendMessage(msg);
                                lastMessageId = Math.max(lastMessageId, msg.id);
                            }
                        });
                        scrollToBottom();
                    }
                });
        }
 
        // --- UPDATED APPEND FUNCTION ---
        function appendMessage(msg) {
            const isMe = String(msg.sender_id) === String(authUserId);
 
            const div = document.createElement('div');
            div.className = `flex items-end gap-2 ${isMe ? 'flex-row-reverse' : 'flex-row'} mb-8`;
            div.id = `message-${msg.id}`;
 
            const time = new Date(msg.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
 
            let contentHtml = '';
 
            if (msg.is_deleted) {
                contentHtml = `
                    <p class="text-sm italic text-gray-400 flex items-center gap-1">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"></line></svg>
                        Message deleted
                    </p>`;
            } else {
                if (msg.attachment) {
                    const attachmentUrl = msg.is_temp ? msg.attachment : `${appUrl}storage/${msg.attachment}`;
                    if (msg.attachment_type === 'image') {
                        contentHtml += `<div class="mb-2"><img src="${attachmentUrl}" class="max-w-full h-auto rounded-lg border border-gray-200 cursor-pointer hover:opacity-90 transition" onclick="window.open(this.src)" onload="scrollToBottom()"></div>`;
                    } else {
                        contentHtml += `
                            <a href="${attachmentUrl}" target="_blank" class="flex items-center gap-2 p-3 bg-gray-50 rounded-lg border border-gray-200 mb-2 hover:bg-gray-100 transition text-gray-700 no-underline">
                                <span class="text-sm font-medium truncate max-w-[150px]">Attachment</span>
                            </a>`;
                    }
                }
                if (msg.body) {
                    contentHtml += `<p class="text-sm leading-relaxed break-words">${escapeHtml(msg.body)}</p>`;
                }
            }
 
            // RED DELETE BUBBLE OUTSIDE (Fixed hover area by making it absolute relative to bubble)
            let deleteOverlay = '';
            if (isMe && !msg.is_deleted) {
                deleteOverlay = `
                    <div class="delete-overlay absolute top-[-10px] right-2 z-20 pointer-events-none group-hover:pointer-events-auto">
                        <button onclick="deleteMessage('${msg.id}', this)"
                                class="flex items-center gap-1 bg-red-500 hover:bg-red-600 text-white text-[10px] font-bold py-1 px-2 rounded-full shadow-md transition-all active:scale-95"
                                title="Delete Message">
                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="3 6 5 6 21 6"></polyline>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            </svg>
                            <span>Delete</span>
                        </button>
                    </div>
                `;
            }
 
            const avatarUrl = isMe ? authUserAvatar : otherUserAvatar;
 
            div.innerHTML = `
                <div class="shrink-0 mb-1">
                    <img src="${avatarUrl}" class="w-8 h-8 rounded-full object-cover border border-gray-200 shadow-sm">
                </div>
                <div class="max-w-[70%] relative message-bubble-container group">
                    ${deleteOverlay}
                    <div class="message-bubble transition-shadow ${isMe ? (msg.is_deleted ? 'bg-gray-50 border border-gray-200' : 'bg-blue-600 text-white') : (msg.is_deleted ? 'bg-gray-50 border border-gray-200' : 'bg-white border border-gray-200 text-gray-800')} px-4 py-2.5 rounded-2xl shadow-sm ${isMe ? 'rounded-br-none' : 'rounded-bl-none'}">
                        ${contentHtml}
                        <span class="text-[10px] ${isMe && !msg.is_deleted ? 'text-blue-100' : 'text-gray-400'} block ${isMe ? 'text-right' : 'text-left'} mt-1 opacity-70 font-medium">${time}</span>
                    </div>
                </div>
            `;
            messagesContainer.appendChild(div);
        }
 
        window.deleteMessage = function(messageId, btn) {
            if(String(messageId).startsWith('temp')) return;
            if(!confirm('Are you sure you want to delete this message?')) return;
 
            fetch(`${appUrl}conversations/${conversationId}/messages/${messageId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    const container = document.getElementById(`message-${messageId}`);
                    if(container) {
                        const contentBox = container.querySelector('.message-bubble');
                        if(contentBox) {
                            contentBox.className = 'message-bubble bg-gray-100 border border-gray-200 px-5 py-3 rounded-2xl shadow-sm rounded-br-none';
                            contentBox.innerHTML = `
                                <p class="text-sm italic text-gray-500 flex items-center gap-1">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"></line></svg>
                                    Message deleted
                                </p>
                                <span class="text-[10px] text-gray-400 block text-right mt-1 opacity-70">${new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</span>
                            `;
                        }
                        const overlay = container.querySelector('.delete-overlay');
                        if(overlay) overlay.remove();
                    }
                }
            });
        };
 
        function scrollToBottom() {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }
 
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML.replace(/\n/g, '<br>');
        }
    });
</script>
@endsection