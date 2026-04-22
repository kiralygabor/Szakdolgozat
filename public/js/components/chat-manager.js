/**
 * Chat Manager Component
 * Handles real-time polling, message rendering, file attachments,
 * and conversation state for the MiniJobz messaging system.
 */
import { DropdownManager } from '../modules/ui-utils.js';

export class ChatManager {
    constructor(options = {}) {
        this.options = {
            conversationId: null,
            authUserId: null,
            authUserAvatar: null,
            otherUserAvatar: null,
            appUrl: '/',
            csrfToken: '',
            pollingInterval: 3000,
            translations: {},
            ...options
        };

        this.lastMessageId = 0;
        this.container = document.getElementById('messages-container');
        this.form = document.getElementById('message-form');
        this.input = document.getElementById('message-input');
        this.attachmentInput = document.getElementById('attachment-input');
        this.attachmentPreview = document.getElementById('attachment-preview');
        
        this.init();
    }

    init() {
        this.initUI();
        this.initEvents();
        if (this.options.conversationId) {
            this.loadMessages();
            this.startPolling();
        }
    }

    initUI() {
        if (window.feather) window.feather.replace();

        // Chat options dropdown
        const optionsBtn = document.getElementById('chat-options-btn');
        const optionsMenu = document.getElementById('chat-options-dropdown');
        if (optionsBtn && optionsMenu) {
            DropdownManager.register('chat-options', optionsBtn, optionsMenu);
        }
    }

    initEvents() {
        if (this.input) {
            this.input.addEventListener('input', () => {
                this.input.style.height = 'auto';
                this.input.style.height = (this.input.scrollHeight) + 'px';
            });

            this.input.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    this.form?.dispatchEvent(new Event('submit'));
                }
            });
        }

        const attachBtn = document.getElementById('attachment-btn');
        if (attachBtn) attachBtn.addEventListener('click', () => this.attachmentInput?.click());

        if (this.attachmentInput) {
            this.attachmentInput.addEventListener('change', (e) => this.handleFileSelect(e));
        }

        if (this.form) {
            this.form.addEventListener('submit', (e) => this.handleSubmit(e));
        }

        // Global delete handler attached to window for simpler legacy compatibility
        window.deleteMessage = (id, btn) => this.deleteMessage(id, btn);
        window.clearAttachment = () => this.clearAttachment();
    }

    handleFileSelect(e) {
        const file = e.target.files[0];
        if (!file || !this.attachmentPreview) return;

        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = (event) => {
                this.attachmentPreview.innerHTML = `
                    <div class="relative inline-block">
                        <img src="${event.target.result}" class="h-20 w-20 object-cover rounded-lg border border-gray-200">
                        <button type="button" onclick="clearAttachment()" class="absolute -top-2 -right-2 bg-[var(--details-error)] text-white rounded-full p-1 shadow-sm hover:opacity-90">
                            <i data-feather="x" class="w-3 h-3"></i>
                        </button>
                    </div>`;
                if (window.feather) window.feather.replace();
            };
            reader.readAsDataURL(file);
        } else {
            this.attachmentPreview.innerHTML = `
                <div class="relative inline-block bg-[var(--bg-secondary)] px-3 py-2 rounded-lg border border-[var(--border-color)] text-sm msg-text-main">
                    <span>${file.name}</span>
                    <button type="button" onclick="clearAttachment()" class="ml-2 text-[var(--details-error)] font-bold">&times;</button>
                </div>`;
        }
        this.attachmentPreview.classList.remove('hidden');
    }

    clearAttachment() {
        if (this.attachmentInput) this.attachmentInput.value = '';
        if (this.attachmentPreview) {
            this.attachmentPreview.innerHTML = '';
            this.attachmentPreview.classList.add('hidden');
        }
    }

    async handleSubmit(e) {
        e.preventDefault();
        const body = this.input.value.trim();
        const file = this.attachmentInput?.files[0];

        if (!body && !file) return;

        const formData = new FormData();
        formData.append('body', body);
        if (file) formData.append('attachment', file);

        const tempId = 'temp-' + Date.now();
        const tempMsg = {
            id: tempId,
            sender_id: this.options.authUserId,
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

        this.appendMessage(tempMsg);
        this.input.value = '';
        this.input.style.height = 'auto';
        this.clearAttachment();
        this.scrollToBottom();

        try {
            const res = await fetch(`${this.options.appUrl}conversations/${this.options.conversationId}/messages`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': this.options.csrfToken },
                body: formData
            });
            const data = await res.json();
            
            const tempEl = document.getElementById(`message-${tempId}`);
            if (tempEl) {
                tempEl.id = `message-${data.id}`;
                const delBtn = tempEl.querySelector('.delete-overlay button');
                if (delBtn) delBtn.setAttribute('onclick', `deleteMessage('${data.id}', this)`);
                this.lastMessageId = Math.max(this.lastMessageId, data.id);
            }
        } catch (err) {
            console.error('Failed to send message:', err);
        }
    }

    loadMessages() {
        fetch(`${this.options.appUrl}conversations/${this.options.conversationId}`)
            .then(res => res.json())
            .then(data => {
                this.container.innerHTML = '';
                data.messages.forEach(msg => {
                    this.appendMessage(msg);
                    this.lastMessageId = Math.max(this.lastMessageId, msg.id);
                });
                this.scrollToBottom();
            })
            .catch(err => {
                console.error("Failed to load messages:", err);
                this.container.innerHTML = `<div class="p-8 text-center text-[var(--details-error)]">${this.options.translations.failedLoad}</div>`;
            });
    }

    startPolling() {
        setInterval(() => this.checkNewMessages(), this.options.pollingInterval);
    }

    async checkNewMessages() {
        try {
            const res = await fetch(`${this.options.appUrl}conversations/${this.options.conversationId}/check?last_message_id=${this.lastMessageId}`);
            const messages = await res.json();
            if (messages.length > 0) {
                messages.forEach(msg => {
                    if (String(msg.sender_id) !== String(this.options.authUserId)) {
                        this.appendMessage(msg);
                        this.lastMessageId = Math.max(this.lastMessageId, msg.id);
                    }
                });
                this.scrollToBottom();
            }
        } catch (err) { }
    }

    appendMessage(msg) {
        const isMe = String(msg.sender_id) === String(this.options.authUserId);
        const div = document.createElement('div');
        div.className = `flex items-end gap-2 ${isMe ? 'flex-row-reverse' : 'flex-row'} mb-8`;
        div.id = `message-${msg.id}`;

        const time = new Date(msg.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

        let contentHtml = '';
        if (msg.is_deleted) {
            contentHtml = `
                <p class="text-sm italic msg-text-muted flex items-center gap-1">
                    <i data-feather="slash" class="w-3 h-3"></i>
                    ${this.options.translations.deleted}
                </p>`;
        } else {
            if (msg.attachment) {
                const attachmentUrl = msg.is_temp ? msg.attachment : `${this.options.appUrl}storage/${msg.attachment}`;
                if (msg.attachment_type === 'image') {
                    contentHtml += `<div class="mb-2"><img src="${attachmentUrl}" class="max-w-full h-auto rounded-lg border msg-border-color cursor-pointer hover:opacity-90 transition" onclick="window.open(this.src)" onload="window.chatManager.scrollToBottom()"></div>`;
                } else {
                    contentHtml += `
                        <a href="${attachmentUrl}" target="_blank" class="flex items-center gap-2 p-3 msg-surface rounded-lg border msg-border-color mb-2 hover:opacity-80 transition msg-text-main no-underline">
                            <span class="text-sm font-medium truncate max-w-[150px]">${this.options.translations.attachment}</span>
                        </a>`;
                }
            }
            if (msg.body) {
                contentHtml += `<p class="text-sm leading-relaxed break-words">${this.escapeHtml(msg.body)}</p>`;
            }
        }

        let deleteOverlay = '';
        if (isMe && !msg.is_deleted) {
            deleteOverlay = `
                <div class="delete-overlay absolute top-[-10px] right-2 z-20 pointer-events-none group-hover:pointer-events-auto">
                    <button onclick="deleteMessage('${msg.id}', this)"
                            class="flex items-center gap-1 bg-[var(--details-error)] hover:opacity-90 text-white text-[10px] font-bold py-1 px-2 rounded-full shadow-md transition-all active:scale-95"
                            title="Delete Message">
                        <i data-feather="trash-2" class="w-2.5 h-2.5"></i>
                        <span>${this.options.translations.delete}</span>
                    </button>
                </div>
            `;
        }

        const avatarUrl = isMe ? this.options.authUserAvatar : this.options.otherUserAvatar;

        div.innerHTML = `
            <div class="shrink-0 mb-1">
                <img src="${avatarUrl}" class="w-8 h-8 rounded-full object-cover border msg-border-color shadow-sm">
            </div>
            <div class="max-w-[70%] relative message-bubble-container group">
                ${deleteOverlay}
                <div class="message-bubble transition-shadow ${isMe ? (msg.is_deleted ? 'msg-surface border msg-border-color' : 'msg-bubble-sent') : (msg.is_deleted ? 'msg-surface border msg-border-color' : 'msg-bubble-received')} px-4 py-2.5 rounded-2xl shadow-sm ${isMe ? 'rounded-br-none' : 'rounded-bl-none'}">
                    ${contentHtml}
                    <span class="text-[10px] ${isMe && !msg.is_deleted ? 'opacity-80' : 'msg-text-muted'} block ${isMe ? 'text-right' : 'text-left'} mt-1 opacity-70 font-medium">${time}</span>
                </div>
            </div>
        `;
        
        this.container.appendChild(div);
        if (window.feather) window.feather.replace();

        // Update sidebar info
        if (msg.body || msg.attachment) {
            const body = msg.body ? msg.body : (msg.attachment_type === 'image' ? this.options.translations.sentImage : this.options.translations.sentFile);
            this.updateSidebar(this.options.conversationId, body, isMe);
        }
    }

    async deleteMessage(messageId, btn) {
        if (String(messageId).startsWith('temp')) return;
        if (!confirm(this.options.translations.confirmDelete)) return;

        try {
            const res = await fetch(`${this.options.appUrl}conversations/${this.options.conversationId}/messages/${messageId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': this.options.csrfToken,
                    'Content-Type': 'application/json'
                }
            });
            const data = await res.json();
            if (data.success) {
                const container = document.getElementById(`message-${messageId}`);
                if (container) {
                    const contentBox = container.querySelector('.message-bubble');
                    if (contentBox) {
                        contentBox.className = 'message-bubble msg-surface border msg-border-color px-5 py-3 rounded-2xl shadow-sm rounded-br-none';
                        contentBox.innerHTML = `
                            <p class="text-sm italic msg-text-muted flex items-center gap-1">
                                <i data-feather="slash" class="w-3 h-3"></i>
                                ${this.options.translations.deleted}
                            </p>
                            <span class="text-[10px] msg-text-muted block text-right mt-1 opacity-70">${new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</span>
                        `;
                        if (window.feather) window.feather.replace();
                    }
                    const overlay = container.querySelector('.delete-overlay');
                    if (overlay) overlay.remove();
                    this.updateSidebar(this.options.conversationId, this.options.translations.messageDeleted, true);
                }
            }
        } catch (err) {
            console.error('Delete failed:', err);
        }
    }

    updateSidebar(cid, body, isMe) {
        const convEl = document.getElementById(`conv-${cid}`);
        if (!convEl) return;

        const snippet = convEl.querySelector('.conversation-snippet');
        if (snippet) {
            snippet.textContent = body;
            snippet.classList.toggle('font-bold', !isMe);
            snippet.classList.toggle('msg-text-main', !isMe);
        }

        const timeEl = convEl.querySelector('.conversation-time');
        if (timeEl) timeEl.textContent = '1m';

        const parent = convEl.parentElement;
        if (parent && parent.firstChild !== convEl) {
            parent.prepend(convEl);
        }
    }

    scrollToBottom() {
        if (this.container) {
            this.container.scrollTop = this.container.scrollHeight;
        }
    }

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML.replace(/\n/g, '<br>');
    }
}
