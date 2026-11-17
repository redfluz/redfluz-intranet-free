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
    <div class="card h-100 border-0 shadow-sm">
        <div class="card-body d-flex flex-row p-0" style="height: 75vh">
            <!-- Left Sidebar: Conversations & User Selector -->
            <div class="col-4 border-end d-flex flex-column overflow-auto bg-1">
                <div class="border-bottom d-flex justify-content-between align-items-center p-3">
                    <span class="fw-bold fg-secondary">{{ __('messages.conversation.title') }}</span>
                    <button class="btn btn-sm btn-solid theme-primary" data-bs-toggle="dialog" data-bs-target="#newChatModal"
                        type="button">
                        <i class="me-1" data-lucide="plus"></i>
                        {{ __('messages.actions.new_message') }}
                    </button>
                </div>

                <div class="list-group list-group-flush flex-grow-1 overflow-auto">
                    @forelse ($recentUsers as $recent)
                        <a class="list-group-item list-group-item-action d-flex align-items-center"
                            href="{{ route('personnel.messages.show', $recent['user']->name) }}">
                            <i data-lucide="user-circle" class="fg-secondary me-2" style="width: 1.5em; height: 1.5em"></i>
                            <div>
                                <div class="fw-semibold">{{ $recent['user']->name }}</div>
                            </div>
                        </a>
                    @empty
                        <div class="fg-secondary small p-3 text-center">
                            {{ __('messages.empty.no_conversations') }}<br />
                            {{ __('messages.empty.new_message_hint') }}
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Right Side: Empty State -->
            <div class="col-8 d-flex flex-column align-items-center justify-content-center fg-secondary">
                <i data-lucide="message-circle" class="fg-secondary mb-3 opacity-50" style="width: 3em; height: 3em"></i>
                <h5>{{ __('messages.empty.your_messages') }}</h5>
                <p>{{ __('messages.empty.select_contact') }}</p>
            </div>
        </div>
    </div>

    <!-- Dialog: Select Person to Chat -->
    <dialog id="newChatModal">
        <div class="dialog-header">
            <h4 class="modal-title" id="newChatModalLabel">
                <i data-lucide="user-plus" class="me-1"></i>
                {{ __('messages.modal.new_message_title') }}
            </h4>
            <button class="btn-close" data-bs-dismiss="dialog" type="button"
></button>
        </div>
        <div class="dialog-body">
            <form id="start-chat-form" autocomplete="off">
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
@endsection

@section('script')
    <script>
        const chatForm = document.getElementById('start-chat-form');

        if (chatForm) {
            chatForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const userSelect = document.getElementById('select-user');
                const selectedUsername = userSelect ? userSelect.value : null;

                if (selectedUsername) {
                    const showConversationUrl =
                        {{ Illuminate\Support\Js::from(route('personnel.messages.show', ['user' => '__USER_NAME__'])) }};
                    window.location.href = showConversationUrl.replace(
                        '__USER_NAME__',
                        encodeURIComponent(selectedUsername),
                    );
                }
            });
        }
    </script>
@endsection
