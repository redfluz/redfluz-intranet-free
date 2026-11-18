{{--
/**
 * ============================================================================
 *
 * @author redfluz
 *
 * @github https://github.com/redfluz
 *
 * @youtube https://youtube.com/@redfluz
 *
 * @contact corex2020@icloud.com
 *
 * @customer
 *
 * @copyright Copyright (c) 2026 redfluz. All Rights Reserved.
 * ============================================================================
 *
 * PROPRIETARY AND CONFIDENTIAL
 *
 * All code, concepts, and intellectual property contained in this file are the
 * exclusive property of redfluz. Unauthorized copying, distribution, modification,
 * reverse engineering, or public display of this software via any medium is
 * strictly prohibited without explicit written permission from the copyright owner.
 */
--}}

@extends('layouts.app')

@section('content')
    <div class="card h-100 border-0 shadow-sm overflow-hidden">
        <div class="card-body d-flex flex-row gap-0 p-0" style="height: 75vh">
            <!-- Left Sidebar -->
            <div class="col-4 border-end d-flex flex-column overflow-auto bg-1">
                <div class="border-bottom d-flex justify-content-between align-items-center p-3">
                    <span class="fw-bold fg-secondary">{{ __('messages.conversation.title') }}</span>
                    <button class="btn btn-sm btn-solid theme-primary" data-bs-toggle="dialog"
                        data-bs-target="#newMessageModal" type="button">
                        <i class="me-1" data-lucide="plus"></i>
                        {{ __('messages.actions.new_message') }}
                    </button>
                </div>

                <div class="list-group list-group-flush flex-grow-1 overflow-auto">
                    @forelse ($recentUsers as $recent)
                        <div
                            class="list-group-item list-group-item-action d-flex align-items-center justify-content-between {{ $recent['user']->id === $user->id ? 'active' : '' }} p-2">
                            <a class="d-flex align-items-center flex-grow-1 text-decoration-none me-2"
                                href="{{ route('personnel.messages.show', $recent['user']->name) }}">
                                <i data-lucide="user-circle"
                                    class="me-2"
                                    style="width: 1.5em; height: 1.5em"></i>
                                <div>
                                    <div class="fw-semibold">{{ $recent['user']->name }}</div>
                                </div>
                            </a>

                            <form class="d-inline" action="{{ route('personnel.messages.destroy-contact', $recent['user']) }}"
                                method="POST"
                                data-confirm-message="{{ __('messages.confirm.delete_contact', ['name' => $recent['user']->name]) }}">
                                @csrf
                                @method('DELETE')
                                <button
                                    class="btn btn-sm {{ $recent['user']->id === $user->id ? '' : 'fg-danger' }} border-0 bg-transparent p-1"
                                    type="submit" onclick="event.stopPropagation()">
                                    <i data-lucide="x" style="width: 1.25em; height: 1.25em"></i>
                                </button>
                            </form>
                        </div>
                    @empty
                        <div class="fg-secondary small p-3 text-center">
                            {{ __('messages.empty.no_conversations') }}<br />
                            {{ __('messages.empty.new_message_hint') }}
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Right Side Stream -->
            <div class="chat-column col-8 d-flex flex-column position-relative">
                <div class="border-bottom fw-bold d-flex align-items-center justify-content-between bg-1 p-3">
                    <div class="d-flex align-items-center">
                        <i data-lucide="user-circle" class="fg-primary me-2" style="width: 1.25em; height: 1.25em"></i>
                        {{ $user->name }}
                    </div>

                    <form action="{{ route('personnel.messages.destroy-conversation', $user) }}" method="POST"
                        data-confirm-message="{{ __('messages.confirm.delete_current_conversation') }}">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-solid theme-danger" type="submit">
                            <i data-lucide="trash-2" class="me-1"></i>
                            {{ __('messages.actions.delete_conversation') }}
                        </button>
                    </form>
                </div>

                <!-- Messages Stream -->
                <div class="chat-stream flex-grow-1 overflow-auto" id="messages-box">
                    @foreach ($messages as $msg)
                        <div class="d-flex {{ $msg->sender_id === Auth::id() ? 'justify-content-end' : 'justify-content-start' }} group-msg mb-2"
                            id="message-item-{{ $msg->id }}">
                            <div class="chat-bubble rounded-5 position-relative {{ $msg->sender_id === Auth::id() ? 'bg-primary chat-bubble-out' : 'bg-1 border' }} p-2 px-3"
                                style="max-width: 70%">
                                <!-- Quoted Message Preview -->
                                @if ($msg->parent)
                                    <div class="chat-quote small mb-2 rounded-3 p-2">
                                        <div class="fw-bold fs-7">
                                            {{ $msg->parent->sender->name ?? __('messages.labels.user') }}
                                        </div>
                                        <div class="text-truncate">
                                            {{ $msg->parent->message ?? ($msg->parent->attachment ? __('messages.labels.attachment') : '') }}
                                        </div>
                                    </div>
                                @endif

                                @if ($msg->message)
                                    <div>{{ $msg->message }}</div>
                                @endif

                                @if ($msg->attachment)
                                    <div class="mt-2">
                                        @if ($msg->attachment_type === 'image')
                                            <div class="chat-image position-relative mt-1 d-inline-block">
                                                <a href="{{ asset('storage/' . $msg->attachment) }}" target="_blank"
                                                    rel="noopener">
                                                    <img class="img-fluid rounded-3 border"
                                                        src="{{ asset('storage/' . $msg->attachment) }}"
                                                        alt="{{ __('messages.labels.attachment') }}"
                                                        style="max-height: 200px" />
                                                </a>
                                                <a class="chat-image-download"
                                                    href="{{ asset('storage/' . $msg->attachment) }}"
                                                    download="{{ $msg->original_name ?? __('messages.actions.download_file') }}"
                                                    title="{{ __('messages.actions.download_file') }}">
                                                    <i data-lucide="download"></i>
                                                </a>
                                            </div>
                                        @else
                                            <a class="btn btn-sm {{ $msg->sender_id === Auth::id() ? 'btn-solid theme-light fg-primary' : 'btn-solid theme-primary' }} text-decoration-none"
                                                href="{{ asset('storage/' . $msg->attachment) }}" target="_blank"
                                                download="{{ $msg->original_name ?? __('messages.actions.download_file') }}">
                                                <i data-lucide="file-down" class="me-1"></i>
                                                {{ $msg->original_name ?? __('messages.actions.download_file') }}
                                            </a>
                                        @endif
                                    </div>
                                @endif

                                <!-- Message Actions (Reply / Single Delete) -->
                                <div class="chat-actions d-flex justify-content-end fs-7 mt-1 gap-2">
                                    <button class="chat-action" type="button" data-chat-action="reply"
                                        data-message-id="{{ $msg->id }}"
                                        data-sender="{{ $msg->sender_id === Auth::id() ? __('messages.labels.you') : $user->name }}"
                                        data-text="{{ $msg->message ?? __('messages.labels.attachment') }}"
                                        title="{{ __('messages.actions.reply') }}"
                                        aria-label="{{ __('messages.actions.reply') }}">
                                        <i data-lucide="reply"></i>
                                    </button>
                                    <button class="chat-action" type="button" data-chat-action="delete"
                                        data-message-id="{{ $msg->id }}"
                                        data-own="{{ $msg->sender_id === Auth::id() ? '1' : '0' }}"
                                        title="{{ __('messages.actions.delete') }}"
                                        aria-label="{{ __('messages.actions.delete') }}">
                                        <i data-lucide="trash-2"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Emoji Picker Popover -->
                <div class="position-absolute d-none rounded-3 shadow" id="emoji-picker-container"
                    style="bottom: 80px; right: 20px; z-index: 1050">
                    <emoji-picker id="emoji-picker"></emoji-picker>
                </div>

                <!-- Input Form -->
                <div class="border-top bg-1 p-3">
                    <!-- Quoted Message Indicator -->
                    <div class="small bg-1 border-start border-3 border-primary d-none align-items-center justify-content-between mb-2 p-2"
                        id="quote-preview">
                        <div>
                            <span class="fw-bold fg-primary" id="quote-sender"></span>
                            <div class="fg-secondary text-truncate" id="quote-text" style="max-width: 400px"></div>
                        </div>
                        <button class="btn btn-sm btn-link fg-danger text-decoration-none p-0" id="remove-quote"
                            type="button">
                            <i data-lucide="x"></i>
                        </button>
                    </div>

                    <!-- Attachment Preview Indicator -->
                    <div class="small fg-secondary d-none align-items-center mb-2 gap-2" id="file-preview">
                        <i data-lucide="paperclip"></i>
                        <span class="fw-semibold" id="file-name"></span>
                        <button class="btn btn-sm btn-link fg-danger text-decoration-none ms-1 p-0" id="remove-file"
                            type="button">
                            <i data-lucide="x"></i>
                        </button>
                    </div>

                    <form class="d-flex align-items-center gap-2" id="messages-form" enctype="multipart/form-data">
                        @csrf
                        <label class="visually-hidden" for="parent-id-input">Parent Id</label>
                        <input id="parent-id-input" name="parent_id" type="hidden" value="" />

                        <button class="btn btn-solid theme-secondary" id="emoji-toggle-btn" type="button">
                            <i data-lucide="smile"></i>
                        </button>

                        <label class="btn btn-solid theme-secondary mb-0 cursor-pointer" for="attachment-input">
                            -
                        </label>
                        <input class="d-none" id="attachment-input" name="attachment" type="file"
                            accept=".jpg,.jpeg,.png,.gif,.webp,.svg,.pdf,.doc,.docx,.xls,.xlsx,.csv" />
                        <label class="visually-hidden" for="message-input"> - </label>
                        <input class="form-control" id="message-input" name="message" type="text"
                            placeholder="{{ __('messages.placeholders.type_message') }}" autocomplete="off"
>

                        <button class="btn btn-solid theme-primary px-4" type="submit">
                            <i data-lucide="send"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Dialog -->
    <dialog id="newMessageModal">
        <div class="dialog-header">
            <h4 class="modal-title" id="newMessageModalLabel">
                <i data-lucide="user-plus" class="me-1"></i>
                {{ __('messages.modal.new_message_title') }}
            </h4>
            <button class="btn-close" data-bs-dismiss="dialog" type="button"
></button>
        </div>
        <div class="dialog-body">
            <form id="start-message-form" autocomplete="off">
                <label class="form-label fw-semibold" for="select-user">
                    {{ __('messages.labels.select_person') }}
                </label>
                <select class="form-control" id="select-user" required>
                    <option value="" selected disabled>{{ __('messages.placeholders.choose_person') }}</option>
                    @foreach ($allUsers as $person)
                        <option value="{{ $person->name }}">{{ $person->name }}</option>
                    @endforeach
                </select>
                <div class="mt-3 text-end">
                    <button class="btn btn-solid theme-secondary me-2" data-bs-dismiss="dialog" type="button">
                        {{ __('messages.actions.cancel') }}
                    </button>
                    <button class="btn btn-solid theme-primary" type="submit">
                        {{ __('messages.actions.start_message') }}
                    </button>
                </div>
            </form>
        </div>
    </dialog>

    {{-- Delete Message Confirmation Dialog --}}
    <dialog class="dialog" id="deleteMessageModal">
        <div class="dialog-header">
            <button class="btn-close" data-bs-dismiss="dialog" type="button"></button>
        </div>
        <div class="dialog-body">
            <p>{{ __('messages.confirm.delete_message') }}</p>
        </div>
        <div class="dialog-footer">
            <button class="btn btn-solid theme-secondary" data-bs-dismiss="dialog" type="button">
                {{ __('common.actions.cancel') }}
            </button>
            <button class="btn btn-solid theme-secondary" type="button" id="deleteForMe">
                {{ __('messages.actions.delete_for_me') }}
            </button>
            <button class="btn btn-solid theme-danger" type="button" id="deleteForEveryone">
                {{ __('messages.actions.delete_for_everyone') }}
            </button>
        </div>
    </dialog>
@endsection

@section('script')
    <script type="module" src="https://cdn.jsdelivr.net/npm/emoji-picker-element@^1/index.js"></script>

    <style>
        /* Let the message stream scroll inside the flex column instead of
           stretching the card past its 75vh height. */
        .chat-column,
        .chat-stream {
            min-height: 0;
        }

        .chat-stream {
            overscroll-behavior: contain;
            padding: 1rem 1.25rem 1.5rem;
            scrollbar-gutter: stable;
        }

        /* --primary-contrast follows the selected palette: dark ink on the
           pastel default, white on saturated blue palettes. Hardcoded
           .text-white washed out on the default blue bubble. */
        .chat-bubble-out {
            color: var(--primary-contrast);
        }

        /* Quoted/replied message keeps its own surface and ink regardless of
           the bubble colour, so it stays readable in every palette. */
        .chat-quote {
            background-color: var(--bg-1);
            border: 0;
            border-inline-start: 4px solid var(--primary-base);
            color: var(--fg-body);
        }

        /* Icons must follow the bubble ink, not .btn-link's --link-color.
           In electric_blast the link hover colour IS the bubble colour, so
           the reply/trash icons vanished on hover. `color: inherit` gives
           white on outgoing bubbles, body ink on incoming ones, in every
           palette. */
        /* 44x44 hit area for touch, with a 20px icon. Colour still inherits
           the bubble ink, so the icons read on both sides in every palette. */
        .chat-action {
            align-items: center;
            background: none;
            border: 0;
            border-radius: 999px;
            color: inherit;
            cursor: pointer;
            display: inline-flex;
            font: inherit;
            height: 44px;
            justify-content: center;
            line-height: 1;
            padding: 0;
            width: 44px;
        }

        .chat-action:hover,
        .chat-action:focus-visible,
        .chat-action:active {
            background-color: color-mix(in srgb, currentColor 12%, transparent);
            color: inherit;
        }

        .chat-action:focus-visible {
            outline: 2px solid currentColor;
            outline-offset: 2px;
        }

        .chat-action svg {
            color: currentColor;
            height: 20px;
            width: 20px;
        }

        .chat-actions {
            opacity: 1;
        }

        .chat-image > a:first-child {
            display: block;
        }

        .chat-image-download {
            align-items: center;
            background: var(--bg-1);
            border: 1px solid var(--border-color);
            border-radius: 999px;
            box-shadow: 0 2px 8px rgb(0 0 0 / 18%);
            color: var(--fg-body);
            display: inline-flex;
            height: 30px;
            justify-content: center;
            position: absolute;
            right: 8px;
            text-decoration: none;
            top: 8px;
            width: 30px;
        }

        .chat-image-download:hover {
            background: var(--primary-bg-subtle);
            color: var(--primary-base);
        }

        .chat-image-download svg {
            height: 14px;
            width: 14px;
        }
    </style>

    <script>
        window.activeChatPartnerId = {{ Illuminate\Support\Js::from($user->id) }};
        window.activeChatPartnerName = {{ Illuminate\Support\Js::from($user->name) }};
        window.messageTranslations = {
            attachment: {{ Illuminate\Support\Js::from(__('messages.labels.attachment')) }},
            confirmDeleteMessage: {{ Illuminate\Support\Js::from(__('messages.confirm.delete_message')) }},
            downloadFile: {{ Illuminate\Support\Js::from(__('messages.actions.download_file')) }},
            fileTooLarge: {{ Illuminate\Support\Js::from(__('messages.errors.file_too_large')) }},
            reply: {{ Illuminate\Support\Js::from(__('messages.actions.reply')) }},
            delete: {{ Illuminate\Support\Js::from(__('messages.actions.delete')) }},
            user: {{ Illuminate\Support\Js::from(__('messages.labels.user')) }},
            you: {{ Illuminate\Support\Js::from(__('messages.labels.you')) }},
        };

        const escapeHtml = function(value) {
            return String(value ?? '').replace(/[&<>"']/g, (char) => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;',
            })[char]);
        };

        const messageBox = document.getElementById('messages-box');
        messageBox.scrollTop = messageBox.scrollHeight;

        // --- Quote Logic ---
        const parentIdInput = document.getElementById('parent-id-input');
        const quotePreview = document.getElementById('quote-preview');
        const quoteSender = document.getElementById('quote-sender');
        const quoteText = document.getElementById('quote-text');
        const removeQuoteBtn = document.getElementById('remove-quote');

        window.quoteMessage = function(id, sender, text) {
            parentIdInput.value = id;
            quoteSender.textContent = sender;
            quoteText.textContent = text;
            quotePreview.classList.remove('d-none');
            quotePreview.classList.add('d-flex');
            document.getElementById('message-input').focus();
        };

        removeQuoteBtn.addEventListener('click', function() {
            parentIdInput.value = '';
            quotePreview.classList.add('d-none');
            quotePreview.classList.remove('d-flex');
        });

        // --- Delete Single Message (for me / for everyone) ---
        const deleteModal = document.getElementById('deleteMessageModal');
        const deleteForMeBtn = document.getElementById('deleteForMe');
        const deleteForEveryoneBtn = document.getElementById('deleteForEveryone');
        const destroyMessageUrl =
            {{ Illuminate\Support\Js::from(route('personnel.messages.destroy-message', ['message' => '__MESSAGE_ID__'])) }};
        let pendingDelete = null;

        window.deleteSingleMessage = function(id, own) {
            pendingDelete = { id: id };
            // Only the sender may recall a message for everyone.
            deleteForEveryoneBtn.classList.toggle('d-none', !own);
            bootstrap.Dialog.getOrCreateInstance(deleteModal).show();
        };

        deleteModal.addEventListener('hidden.bs.dialog', function() {
            pendingDelete = null;
        });

        const submitDelete = function(mode) {
            if (!pendingDelete) return;

            const id = pendingDelete.id;
            pendingDelete = null;
            bootstrap.Dialog.getInstance(deleteModal)?.hide();

            fetch(destroyMessageUrl.replace('__MESSAGE_ID__', encodeURIComponent(id)), {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    },
                    body: JSON.stringify({ mode: mode }),
                })
                .then((res) => res.json())
                .then((data) => {
                    if (data.status === 'success') {
                        document.getElementById(`message-item-${id}`)?.remove();
                    }
                });
        };

        deleteForMeBtn.addEventListener('click', function() {
            submitDelete('me');
        });
        deleteForEveryoneBtn.addEventListener('click', function() {
            submitDelete('everyone');
        });

        // --- Chat action buttons (delegated) ---
        // One listener covers server-rendered bubbles and messages appended
        // later by Echo or the send form. No inline handlers to break.
        messageBox.addEventListener('click', function(event) {
            const button = event.target.closest('[data-chat-action]');
            if (!button || !messageBox.contains(button)) return;

            const id = button.dataset.messageId;
            if (!id) return;

            if (button.dataset.chatAction === 'reply') {
                window.quoteMessage(id, button.dataset.sender, button.dataset.text);
            } else if (button.dataset.chatAction === 'delete') {
                window.deleteSingleMessage(id, button.dataset.own === '1');
            }
        });

        // --- Emoji Picker Logic ---
        const emojiToggleBtn = document.getElementById('emoji-toggle-btn');
        const emojiPickerContainer = document.getElementById('emoji-picker-container');
        const emojiPicker = document.getElementById('emoji-picker');
        const messageInput = document.getElementById('message-input');

        emojiToggleBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            emojiPickerContainer.classList.toggle('d-none');
        });

        emojiPicker.addEventListener('emoji-click', (event) => {
            messageInput.value += event.detail.unicode;
            messageInput.focus();
        });

        document.addEventListener('click', (e) => {
            if (!emojiPickerContainer.contains(e.target) && e.target !== emojiToggleBtn) {
                emojiPickerContainer.classList.add('d-none');
            }
        });

        // --- Attachment Preview ---
        const attachmentInput = document.getElementById('attachment-input');
        const filePreview = document.getElementById('file-preview');
        const fileNameSpan = document.getElementById('file-name');
        const removeFileBtn = document.getElementById('remove-file');

        attachmentInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const file = this.files[0];
                if (file.size > 10 * 1024 * 1024) {
                    alert(window.messageTranslations.fileTooLarge);
                    this.value = '';
                    filePreview.classList.add('d-none');
                    filePreview.classList.remove('d-flex');
                    return;
                }
                fileNameSpan.textContent = file.name;
                filePreview.classList.remove('d-none');
                filePreview.classList.add('d-flex');
            }
        });

        removeFileBtn.addEventListener('click', function() {
            attachmentInput.value = '';
            filePreview.classList.add('d-none');
            filePreview.classList.remove('d-flex');
        });

        // --- Append Incoming Message ---
        window.appendIncomingMessage = function(msg) {
            if (!msg || !msg.id || String(msg.sender_id) !== String(window.activeChatPartnerId)) {
                return;
            }
            if (document.getElementById(`message-item-${msg.id}`)) {
                return;
            }

            const wrapper = document.createElement('div');
            wrapper.id = `message-item-${msg.id}`;
            wrapper.className = 'd-flex justify-content-start mb-2';

            let quoteHtml = '';
            if (msg.parent) {
                const senderName = msg.parent.sender ? msg.parent.sender.name : window.messageTranslations.user;
                quoteHtml = `
 <div class="chat-quote p-2 mb-2 rounded-3 small">
 <div class="fw-bold fs-7">${escapeHtml(senderName)}</div>
 <div class="text-truncate">${escapeHtml(msg.parent.message || window.messageTranslations.attachment)}</div>
 </div>`;
            }

            let attachmentHtml = '';
            if (msg.attachment) {
                if (msg.attachment_type === 'image') {
                    attachmentHtml =
                        `<div class="mt-2 chat-image position-relative d-inline-block"><a href="/storage/${encodeURI(msg.attachment)}" target="_blank" rel="noopener"><img src="/storage/${encodeURI(msg.attachment)}" class="img-fluid rounded-3 border" style="max-height: 200px;"></a><a class="chat-image-download" href="/storage/${encodeURI(msg.attachment)}" download="${escapeHtml(msg.original_name || window.messageTranslations.downloadFile)}" title="${escapeHtml(window.messageTranslations.downloadFile)}"><i data-lucide="download"></i></a></div>`;
                } else {
                    attachmentHtml =
                        `<div class="mt-2"><a href="/storage/${encodeURI(msg.attachment)}" target="_blank" download="${escapeHtml(msg.original_name || window.messageTranslations.downloadFile)}" class="btn btn-sm btn-solid theme-primary text-decoration-none"><i data-lucide="file-down"></i> ${escapeHtml(msg.original_name || window.messageTranslations.downloadFile)}</a></div>`;
                }
            }

            wrapper.innerHTML = `
 <div class="p-2 px-3 rounded-5 bg-1 border" style="max-width: 70%;">
 ${quoteHtml}
 ${msg.message ? `<div>${escapeHtml(msg.message)}</div>` : ''}
 ${attachmentHtml}
 <div class="chat-actions mt-1 d-flex justify-content-end gap-2 fs-7">
 <button type="button" class="chat-action" data-chat-action="reply" data-message-id="${escapeHtml(msg.id)}" data-sender="${escapeHtml(window.activeChatPartnerName)}" data-text="${escapeHtml(msg.message || window.messageTranslations.attachment)}" title="${escapeHtml(window.messageTranslations.reply)}" aria-label="${escapeHtml(window.messageTranslations.reply)}">
 <i data-lucide="reply"></i>
 </button>
 <button type="button" class="chat-action" data-chat-action="delete" data-message-id="${escapeHtml(msg.id)}" data-own="0" title="${escapeHtml(window.messageTranslations.delete)}" aria-label="${escapeHtml(window.messageTranslations.delete)}">
 <i data-lucide="trash-2"></i>
 </button>
 </div>
 </div>`;

            messageBox.appendChild(wrapper);
            messageBox.scrollTop = messageBox.scrollHeight;

            fetch("{{ route('personnel.messages.read', $user->id) }}", {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                },
            });
        };

        // --- Form Submit Handling ---
        document.getElementById('messages-form').addEventListener('submit', function(e) {
            e.preventDefault();

            const text = messageInput.value.trim();
            const file = attachmentInput.files[0];
            if (!text && !file) return;

            const formData = new FormData(this);

            this.reset();
            filePreview.classList.add('d-none');
            filePreview.classList.remove('d-flex');
            quotePreview.classList.add('d-none');
            quotePreview.classList.remove('d-flex');
            parentIdInput.value = '';
            emojiPickerContainer.classList.add('d-none');

            fetch("{{ route('personnel.messages.send', $user->name) }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    },
                    body: formData,
                })
                .then((res) => res.json())
                .then((data) => {
                    if (data.status === 'success' && data.message) {
                        const msg = data.message;
                        const wrapper = document.createElement('div');
                        wrapper.id = `message-item-${msg.id}`;
                        wrapper.className = 'd-flex justify-content-end mb-2';

                        let quoteHtml = '';
                        if (msg.parent) {
                            const senderName = msg.parent.sender ?
                                msg.parent.sender.name :
                                window.messageTranslations.user;
                            quoteHtml = `
 <div class="chat-quote p-2 mb-2 rounded-3 small">
 <div class="fw-bold fs-7">${escapeHtml(senderName)}</div>
 <div class="text-truncate">${escapeHtml(msg.parent.message || window.messageTranslations.attachment)}</div>
 </div>`;
                        }

                        let attachmentHtml = '';
                        if (msg.attachment) {
                            if (msg.attachment_type === 'image') {
                                attachmentHtml =
                                    `<div class="mt-2 chat-image position-relative d-inline-block"><a href="/storage/${encodeURI(msg.attachment)}" target="_blank" rel="noopener"><img src="/storage/${encodeURI(msg.attachment)}" class="img-fluid rounded-3 border" style="max-height: 200px;"></a><a class="chat-image-download" href="/storage/${encodeURI(msg.attachment)}" download="${escapeHtml(msg.original_name || window.messageTranslations.downloadFile)}" title="${escapeHtml(window.messageTranslations.downloadFile)}"><i data-lucide="download"></i></a></div>`;
                            } else {
                                attachmentHtml =
                                    `<div class="mt-2"><a href="/storage/${encodeURI(msg.attachment)}" target="_blank" download="${escapeHtml(msg.original_name || window.messageTranslations.downloadFile)}" class="badge theme-light fg-body border p-2 text-decoration-none"><i data-lucide="file-down"></i> ${escapeHtml(msg.original_name || window.messageTranslations.downloadFile)}</a></div>`;
                            }
                        }

                        wrapper.innerHTML = `
 <div class="chat-bubble chat-bubble-out p-2 px-3 rounded-5 bg-primary" style="max-width: 70%;">
 ${quoteHtml}
 ${msg.message ? `<div>${escapeHtml(msg.message)}</div>` : ''}
 ${attachmentHtml}
 <div class="chat-actions mt-1 d-flex justify-content-end gap-2 fs-7">
 <button type="button" class="chat-action" data-chat-action="reply" data-message-id="${escapeHtml(msg.id)}" data-sender="${escapeHtml(window.messageTranslations.you)}" data-text="${escapeHtml(msg.message || window.messageTranslations.attachment)}" title="${escapeHtml(window.messageTranslations.reply)}" aria-label="${escapeHtml(window.messageTranslations.reply)}">
 <i data-lucide="reply"></i>
 </button>
 <button type="button" class="chat-action" data-chat-action="delete" data-message-id="${escapeHtml(msg.id)}" data-own="1" title="${escapeHtml(window.messageTranslations.delete)}" aria-label="${escapeHtml(window.messageTranslations.delete)}">
 <i data-lucide="trash-2"></i>
 </button>
 </div>
 </div>`;

                        messageBox.appendChild(wrapper);
                        messageBox.scrollTop = messageBox.scrollHeight;
                    }
                });
        });

        // --- Start New Message ---
        document.getElementById('start-message-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const selectedUsername = document.getElementById('select-user').value;
            if (selectedUsername) {
                const showConversationUrl =
                    {{ Illuminate\Support\Js::from(route('personnel.messages.show', ['user' => '__USER_NAME__'])) }};
                window.location.href = showConversationUrl.replace(
                    '__USER_NAME__',
                    encodeURIComponent(selectedUsername),
                );
            }
        });
    </script>
@endsection
