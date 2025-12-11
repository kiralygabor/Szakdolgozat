@extends('layout')

@section('title', 'Messages')

@section('content')
<style>
    /* Smooth fade in/out for the delete button */
    .delete-overlay {
        opacity: 0;
        visibility: hidden;
        transform: translateY(5px);
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .message-wrapper:hover .delete-overlay {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }
</style>

<section class="py-8 min-h-screen bg-gray-50">
  <!-- FIX 1: Increased the subtraction in calc() slightly to prevent page scroll -->
  <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 h-[calc(100vh-140px)]">
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden h-full grid grid-cols-1 md:grid-cols-3">
      
      <!-- Sidebar -->
      <aside class="border-r border-gray-200 flex flex-col h-full">
        <div class="p-4 border-b border-gray-100">
          <h2 class="text-xl font-bold text-gray-800 mb-4">Messages</h2>
          <div class="relative">
            <i data-feather="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-4 h-4"></i>
            <input type="text" placeholder="Search messages..." class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm">
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
                        <div class="relative">
                            @if($otherUser->avatar)
                                <img src="{{ asset('storage/' . $otherUser->avatar) }}" class="w-12 h-12 rounded-full object-cover border border-gray-200">
                            @else
                                <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-lg">
                                    {{ substr($otherUser->first_name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-baseline mb-1">
                                <h3 class="font-bold text-gray-900 truncate">{{ $otherUser->first_name }} {{ $otherUser->last_name }}</h3>
                                @if($lastMessage)
                                    <span class="text-xs text-gray-400 whitespace-nowrap">{{ $lastMessage->created_at->diffForHumans(null, true, true) }}</span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-500 truncate {{ $lastMessage && !$lastMessage->is_read && $lastMessage->sender_id !== Auth::id() ? 'font-bold text-gray-800' : '' }}">
                                {{ $lastMessage ? $lastMessage->body : 'Start a conversation' }}
                            </p>
                        </div>
                    </div>
                </a>
            @empty
                <div class="p-8 text-center text-gray-500">
                    <i data-feather="message-square" class="mx-auto mb-3 opacity-50 w-10 h-10"></i>
                    <p>No conversations yet.</p>
                </div>
            @endforelse
        </div>
      </aside>

      <!-- Main Chat Area -->
      <!-- FIX 2: Added 'overflow-hidden' here. This forces the flex children to stay within bounds. -->
      <main class="col-span-2 flex flex-col h-full bg-white relative overflow-hidden">
        @if($activeConversation)
            @php $chatUser = $activeConversation->getOtherUser(Auth::id()); @endphp
            
            <!-- Chat Header -->
            <div class="p-4 border-b border-gray-200 flex items-center justify-between bg-white z-10 flex-shrink-0">
                <div class="flex items-center gap-3">
                    @if($chatUser->avatar)
                        <img src="{{ asset('storage/' . $chatUser->avatar) }}" class="w-10 h-10 rounded-full object-cover">
                    @else
                        <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 font-bold">
                            {{ substr($chatUser->first_name, 0, 1) }}
                        </div>
                    @endif
                    <div>
                        <h2 class="font-bold text-gray-900">{{ $chatUser->first_name }} {{ $chatUser->last_name }}</h2>
                        <span class="text-xs text-green-500 font-medium flex items-center gap-1">
                            <span class="w-2 h-2 bg-green-500 rounded-full"></span> Online
                        </span>
                    </div>
                </div>
                {{-- Actions --}}
                <div class="relative">
                    <button id="chat-options-btn" class="p-2 hover:bg-gray-100 rounded-full text-gray-500 transition">
                        <i data-feather="more-vertical" class="w-5 h-5"></i>
                    </button>
                    <!-- Dropdown Menu -->
                    <div id="chat-options-dropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 hidden z-50 overflow-hidden">
                        <a href="#" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition border-b border-gray-50">
                            <i data-feather="user" class="w-4 h-4 inline-block mr-2"></i> View Profile
                        </a>
                        <button class="w-full text-left px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition border-b border-gray-50">
                            <i data-feather="flag" class="w-4 h-4 inline-block mr-2"></i> Report User
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
                        <textarea id="message-input" rows="1" placeholder="Type a message..." class="w-full pl-4 pr-12 py-3 bg-gray-100 border-0 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:bg-white transition resize-none custom-scrollbar" style="min-height: 48px; max-height: 120px;"></textarea>
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
            <div class="flex-1 flex flex-col items-center justify-center text-gray-500 p-8">
                <div class="w-24 h-24 bg-blue-50 rounded-full flex items-center justify-center mb-6">
                    <i data-feather="message-circle" class="w-12 h-12 text-blue-500"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Your Messages</h2>
                <p class="text-center max-w-md">Select a conversation from the left to start chatting.</p>
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
        const conversationId = "{{ $activeConversation ? $activeConversation->id : '' }}";
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

                fetch(`/conversations/${conversationId}/messages`, {
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
            fetch(`/conversations/${conversationId}`)
                .then(res => res.json())
                .then(data => {
                    messagesContainer.innerHTML = ''; 
                    data.messages.forEach(msg => {
                        appendMessage(msg);
                        lastMessageId = Math.max(lastMessageId, msg.id);
                    });
                    scrollToBottom();
                });
        }

        function checkNewMessages() {
            fetch(`/conversations/${conversationId}/check?last_message_id=${lastMessageId}`)
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
            // 'message-wrapper' allows hover detection
            div.className = `flex ${isMe ? 'justify-end' : 'justify-start'} mb-5 message-wrapper group`; 
            div.id = `message-${msg.id}`;
            
            const time = new Date(msg.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

            let contentHtml = '';
            
            if (msg.is_deleted) {
                contentHtml = `
                    <p class="text-sm italic text-gray-500 flex items-center gap-1">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"></line></svg> 
                        Message deleted
                    </p>`;
            } else {
                if (msg.attachment) {
                    const attachmentUrl = msg.is_temp ? msg.attachment : `/storage/${msg.attachment}`;
                    if (msg.attachment_type === 'image') {
                        contentHtml += `<div class="mb-2"><img src="${attachmentUrl}" class="max-w-xs rounded-lg border border-gray-200 cursor-pointer hover:opacity-90 transition" onclick="window.open(this.src)" onload="scrollToBottom()"></div>`;
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

            // RED DELETE BUBBLE OUTSIDE
            // absolute -top-3 -right-2 puts it outside the top right corner
            let deleteOverlay = '';
            if (isMe && !msg.is_deleted) {
                deleteOverlay = `
                    <div class="delete-overlay absolute -top-3 -right-2 z-10">
                        <button onclick="deleteMessage('${msg.id}', this)" 
                                class="flex items-center gap-1 bg-red-500 hover:bg-red-600 text-white text-[10px] font-bold py-1 px-2 rounded-full shadow-md transition-colors"
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

            div.innerHTML = `
                <div class="max-w-[75%] relative">
                    ${deleteOverlay}
                    <div class="message-bubble ${isMe ? (msg.is_deleted ? 'bg-gray-100 border border-gray-200' : 'bg-blue-600 text-white') : (msg.is_deleted ? 'bg-gray-100 border border-gray-200' : 'bg-white border border-gray-200 text-gray-800')} px-5 py-3 rounded-2xl shadow-sm ${isMe ? 'rounded-br-none' : 'rounded-bl-none'}">
                        ${contentHtml}
                        <span class="text-[10px] ${isMe && !msg.is_deleted ? 'text-blue-100' : 'text-gray-400'} block text-right mt-1 opacity-70">${time}</span>
                    </div>
                </div>
            `;
            messagesContainer.appendChild(div);
        }

        window.deleteMessage = function(messageId, btn) {
            if(String(messageId).startsWith('temp')) return;
            if(!confirm('Are you sure you want to delete this message?')) return;

            fetch(`/conversations/${conversationId}/messages/${messageId}`, {
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