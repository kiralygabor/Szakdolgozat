document.addEventListener('DOMContentLoaded', function() {
    if (window.feather) window.feather.replace();

    const config = window.MESSAGES_CONFIG || {};
    const authUserId = config.authUserId;
    const authUserAvatar = config.authUserAvatar;
    const conversationId = config.conversationId;
    const appUrl = config.appUrl;
    const storageUrl = (config.storageUrl || (appUrl + 'storage')).replace(/\/$/, '');
    let otherUserAvatar = config.otherUserAvatar || '';
    const translations = config.translations || {};

    const messagesContainer = document.getElementById('messages-container');
    const messageForm = document.getElementById('message-form');
    const messageInput = document.getElementById('message-input');
    
    // Track messages that user wants to delete before they finish sending
    const pendingDeletes = {};

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

    window.clearAttachment = function(force = false) {
        if (!attachmentInput || !attachmentInput.files.length) return;
        
        const shouldConfirm = !force && attachmentInput.files.length > 0;
        
        if (!shouldConfirm || confirm(translations.confirmClearAttachment || 'Remove this attachment?')) {
            attachmentInput.value = '';
            if (attachmentPreview) {
                attachmentPreview.innerHTML = '';
                attachmentPreview.classList.add('hidden');
            }
        }
    };

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
            clearAttachment(true);
            scrollToBottom();

            fetch(`${appUrl}messages/${conversationId}/messages`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': config.csrfToken },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                const tempId = tempMsg.id;
                lastMessageId = data.id;

                // Check if user requested deletion while it was sending
                if (pendingDeletes[tempId]) {
                    deleteMessage(data.id, null, true); // force delete without confirm
                    delete pendingDeletes[tempId];
                    return;
                }

                const tempEl = document.getElementById(`message-${tempId}`);
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
        fetch(`${appUrl}messages/${conversationId}`)
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
                messagesContainer.innerHTML = `<div class="p-8 text-center text-red-500">${translations.failedLoad || 'Failed to load messages'}</div>`;
            });
    }

    function checkNewMessages() {
        fetch(`${appUrl}messages/${conversationId}/check?last_message_id=${lastMessageId}`)
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
                    ${translations.deleted || 'Deleted'}
                </p>`;
        } else {
            if (msg.attachment) {
                const attachmentUrl = msg.is_temp ? msg.attachment : (msg.attachment_url || `${storageUrl}/${msg.attachment}`);
                
                // Handle different enum serialization formats (raw string vs object)
                let type = msg.attachment_type;
                if (type && typeof type === 'object') type = type.value || type.name;
                type = String(type || '').toLowerCase();

                // Fallback: check extension if type is unclear
                const isImage = type === 'image' || (msg.attachment && msg.attachment.match(/\.(jpg|jpeg|png|gif|webp)$/i));

                if (isImage) {
                    contentHtml += `
                        <div class="mb-2 attachment-image-container">
                            <img src="${attachmentUrl}" 
                                 class="max-w-full h-auto rounded-lg border border-gray-200 cursor-pointer hover:opacity-90 transition shadow-sm" 
                                 onclick="window.open(this.src)" 
                                 onload="scrollToBottom()" 
                                 onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');">
                            <div class="hidden p-3 bg-red-50/10 border border-red-500/20 rounded-lg text-xs text-red-400">
                                <i data-feather="image-off" class="w-3 h-3 inline mr-1"></i> Image failed to load
                            </div>
                        </div>`;
                } else {
                    const fileName = msg.attachment_name || msg.attachment.split('/').pop() || translations.attachment || 'Attachment';
                    contentHtml += `
                        <a href="${attachmentUrl}" target="_blank" class="flex items-center gap-2 p-3 bg-white/10 rounded-lg border border-white/20 mb-2 hover:bg-white/20 transition text-inherit no-underline">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline></svg>
                            <span class="text-sm font-medium truncate max-w-[150px]">${fileName}</span>
                        </a>`;
                }
            }
            if (msg.body) {
                contentHtml += `<p class="message-body-text text-sm leading-relaxed break-words">${escapeHtml(msg.body)}</p>`;
            }
        }

        // DELETE BUTTON
        let deleteOverlay = '';
        if (isMe && !msg.is_deleted) {
            deleteOverlay = `
                <div class="delete-overlay absolute -top-3 -left-2 z-20 pointer-events-none group-hover:pointer-events-auto">
                    <button onclick="deleteMessage('${msg.id}', this)"
                            class="w-7 h-7 flex items-center justify-center bg-red-500 hover:bg-red-600 text-white rounded-full shadow-lg transition-all active:scale-90"
                            title="${translations.delete || 'Delete'}">
                        <svg class="delete-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                        </svg>
                        <div class="delete-spinner hidden animate-spin rounded-full h-3 w-3 border-2 border-white border-t-transparent"></div>
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
                    <span class="message-time text-[10px] ${isMe && !msg.is_deleted ? 'text-blue-100' : 'text-gray-400'} block ${isMe ? 'text-right' : 'text-left'} mt-1 opacity-70 font-medium">${time}</span>
                </div>
            </div>
        `;
        messagesContainer.appendChild(div);

        // Update sidebar info
        if (msg.body || msg.attachment) {
            const bodyText = msg.body ? msg.body : (msg.attachment_type === 'image' ? (translations.sentImage || 'Sent an image') : (translations.sentFile || 'Sent a file'));
            updateSidebar(conversationId, bodyText, isMe);
        }
    }

    window.deleteMessage = function(messageId, btn, force = false) {
        if (!conversationId) return;
        
        // Handle temporary messages
        if (String(messageId).startsWith('temp')) {
            if (force || confirm(translations.confirmDelete || 'Are you sure you want to delete this message?')) {
                pendingDeletes[messageId] = true;
                const container = document.getElementById(`message-${messageId}`);
                if (container) container.remove();
            }
            return;
        }

        if (!force && !confirm(translations.confirmDelete || 'Are you sure you want to delete this message?')) return;

        // Visual feedback
        if (btn) {
            btn.disabled = true;
            const icon = btn.querySelector('.delete-icon');
            const spinner = btn.querySelector('.delete-spinner');
            if (icon) icon.classList.add('hidden');
            if (spinner) spinner.classList.remove('hidden');
        }

        fetch(`${appUrl}messages/${conversationId}/messages/${messageId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': config.csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const container = document.getElementById(`message-${messageId}`);
                if (container) {
                    const contentBox = container.querySelector('.message-bubble');
                    if (contentBox) {
                        contentBox.className = `message-bubble bg-gray-100 border border-gray-200 px-4 py-2.5 rounded-2xl shadow-sm ${String(container.className).includes('flex-row-reverse') ? 'rounded-br-none' : 'rounded-bl-none'}`;
                        contentBox.innerHTML = `
                            <p class="text-sm italic text-gray-400 flex items-center gap-1 mb-0">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"></line></svg>
                                ${translations.deleted || 'Deleted'}
                            </p>
                            <span class="text-[10px] text-gray-400 block text-right mt-1 opacity-70">${new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</span>
                        `;
                    }
                    const overlay = container.querySelector('.delete-overlay');
                    if (overlay) overlay.remove();

                    updateSidebar(conversationId, translations.deletedMessage || 'Message deleted', true);
                }
            } else {
                alert(data.message || 'Failed to delete message');
                if (btn) {
                    btn.disabled = false;
                    const icon = btn.querySelector('.delete-icon');
                    const spinner = btn.querySelector('.delete-spinner');
                    if (icon) icon.classList.remove('hidden');
                    if (spinner) spinner.classList.add('hidden');
                }
            }
        })
        .catch(err => {
            console.error("Delete error:", err);
            alert('A network error occurred');
            if (btn) {
                btn.disabled = false;
                const icon = btn.querySelector('.delete-icon');
                const spinner = btn.querySelector('.delete-spinner');
                if (icon) icon.classList.remove('hidden');
                if (spinner) spinner.classList.add('hidden');
            }
        });
    };

    function scrollToBottom() {
        if (messagesContainer) {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML.replace(/\n/g, '<br>');
    }

    function updateSidebar(cid, body, isMe) {
        const convEl = document.getElementById(`conv-${cid}`);
        if (!convEl) return;

        // Updated snippet text
        const snippet = convEl.querySelector('.conversation-snippet');
        if (snippet) {
            snippet.textContent = body;
            if (!isMe) {
                snippet.classList.add('font-bold', 'text-gray-800');
            } else {
                snippet.classList.remove('font-bold', 'text-gray-800');
            }
        }

        // Update timestamp
        const timeEl = convEl.querySelector('.conversation-time');
        if (timeEl) {
            timeEl.textContent = '1m'; // Simplified "just now"
        }

        // Move to top
        const container = convEl.parentElement;
        if (container && container.firstChild !== convEl) {
            container.prepend(convEl);
        }
    }
});

// --- Modal Handlers ---
window.openUserReportModal = function(reportedAccountId) {
    const modal = document.getElementById('user-report-modal');
    const input = document.getElementById('user-report-reported-account-id');
    const desc = document.getElementById('user-report-description');
    
    if (input) input.value = reportedAccountId;
    if (desc) desc.value = '';
    
    if (modal) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden'; // Prevent background scrolling
    }
    
    // Close dropdown if it's open when clicking report
    const dropdown = document.getElementById('chat-options-dropdown');
    if (dropdown) dropdown.classList.add('hidden');
};

window.closeUserReportModal = function() {
    const modal = document.getElementById('user-report-modal');
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = ''; // Restore background scrolling
    }
};
