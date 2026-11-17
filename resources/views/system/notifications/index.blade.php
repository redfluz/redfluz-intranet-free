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

@php
    use App\Enums\FileAction;
    use App\Enums\FileResources;
    use App\Models\System\Notification;
    use App\Models\System\Setting;

    $canCreate = auth()->user()->hasFilePermission(FileResources::SYSTEM_NOTIFICATIONS, FileAction::ADD);
    $canRemove = auth()->user()->hasFilePermission(FileResources::SYSTEM_NOTIFICATIONS, FileAction::REMOVE);
@endphp
@props([
    /** @var Notification */
    'notifications',
])
@extends('layouts.app')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header d-flex align-items-center justify-content-between bg-1 py-3">
            <h4 class="fw-bold m-0">
                {{ __('notifications.title') }}
                <p class="fg-secondary small mb-0">{{ __('notifications.subtitle') }}</p>
            </h4>
            @if ($canCreate)
                <a class="btn btn-solid theme-primary btn-sm" href="{{ route('system.notifications.create') }}">
                    <i class="me-1" data-lucide="plus"></i>
                    {{ __('common.actions.add') }}
                </a>
            @endif
        </div>

        <div class="card-body">
            @include('partials.alerts')
            <!-- Search Bar -->
            <form autocomplete="off" class="row g-2 mb-3" method="GET" action="{{ route('system.notifications.index') }}">
                <div class="md:col-4">
                    <label class="visually-hidden" for="search">{{ __('common.actions.search') }}</label>
                    <div class="form-control form-adorn">
                        <span class="form-adorn-icon" aria-hidden="true"><i data-lucide="search"></i></span>
                        <input id="search" class="form-ghost" name="search" type="text" value="{{ $search ?? '' }}"
                            placeholder="{{ __('common.actions.search') }}">
                    </div>
                    @if (isset($search) && $search)
                        <a class="btn btn-solid theme-danger" href="{{ route('system.notifications.index') }}"
                            title="{{ __('common.actions.reset') }}">
                            <i data-lucide="x"></i>
                        </a>
                    @endif
                </div>
            </form>

            <div class="table-responsive rounded-5 overflow-hidden border">
                <table class="table-hover table-striped mb-0 table align-middle">
                    <thead class="bg-1">
                        <tr>
                            <th scope="col">{{ __('notifications.table.column_title') }}</th>
                            <th scope="col">{{ __('notifications.table.column_message') }}</th>
                            <th scope="col">{{ __('notifications.table.column_user') }}</th>
                            <th scope="col">{{ __('notifications.table.column_status') }}</th>
                            <th scope="col" class="col-date">{{ __('notifications.table.column_created_at') }}</th>
                            <th scope="col" class="col-action text-center">{{ __('common.labels.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($notifications as $notification)
                            <tr>
                                <td>
                                    <a class="fw-semibold text-decoration-none fg-primary"
                                        href="{{ route('system.notifications.show', $notification->id) }}">
                                        {{ $notification->title ?? 'N/A' }}
                                    </a>
                                </td>
                                <td>
                                    <span class="fg-secondary text-truncate d-inline-block" style="max-width: 300px">
                                        {{ $notification->message ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge theme-secondary">User #{{ $notification->notifiable_id }}</span>
                                </td>
                                <td>
                                    @if ($notification->read_at)
                                        <span class="badge theme-success border">
                                            <i data-lucide="check-check" class="me-1"></i>
                                            {{ __('notifications.status_read') }}
                                        </span>
                                    @else
                                        <span class="badge theme-warning border">
                                            <i data-lucide="clock" class="me-1"></i>
                                            {{ __('notifications.status_unread') }}
                                        </span>
                                    @endif
                                </td>
                                <td>{{ $notification->created_at->format(Setting::getDefaultDate()) ?? 'N/A' }}</td>
                                <td class="text-center">
                                    <a class="btn btn-sm btn-solid theme-info me-1"
                                        href="{{ route('system.notifications.show', $notification->id) }}"
                                        title="View Notification">
                                        <i data-lucide="eye"></i>
                                    </a>
                                    @if ($canRemove)
                                        <button class="btn btn-sm btn-solid theme-danger btn-delete"
                                            data-id="{{ $notification->id }}" type="button"
                                            title="{{ __('common.actions.delete') }}">
                                            <i data-lucide="trash-2"></i>
                                        </button>

                                        <form autocomplete="off" class="d-none" id="delete-form-{{ $notification->id }}"
                                            action="{{ route('system.notifications.destroy', $notification->id) }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="fg-secondary py-4 text-center" colspan="7">
                                    {{ __('common.labels.not_found_record') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-end mt-3">{{ $notifications->links() }}</div>
        </div>
    </div>
@endsection

@section('script')
    <script type="module">
        document.addEventListener('click', function(event) {
            const button = event.target.closest('.btn-delete');
            if (!button) return;
            confirmDialog({
                title: "{{ __('common.alerts.confirm_delete') }}",
                text: "{{ __('common.alerts.confirm_delete_message') }}",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: "{{ __('common.alerts.confirm_delete_yes') }}",
                confirmTone: 'danger',
            }).then((result) => {
                if (result.isConfirmed) document.getElementById('delete-form-' + button.dataset.id)
                    .submit();
            });
        });
    </script>
@endsection
