@php
    use App\Enums\FileAction;
    use App\Enums\FileResources;

    $canRemove = auth()->user()->hasFilePermission(FileResources::SYSTEM_NOTIFICATIONS, FileAction::REMOVE);
@endphp
@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        {{-- Top Action Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0 text-gray-800">{{ __('notifications.detail_title') }}</h1>
                <p class="fg-secondary small mb-0">{{ __('notifications.subtitle') }}</p>
            </div>
            <div class="d-flex gap-2">
                <a class="btn btn-solid theme-secondary" href="{{ route('system.notifications.index') }}">
                    <i class="me-1" data-lucide="arrow-left"></i>
                    {{ __('common.actions.back') }}
                </a>
                @if ($canRemove)
                    <form action="{{ route('system.notifications.destroy', $notification->id) }}" method="POST"
                        onsubmit="return confirm('Are you sure you want to delete this notification?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-solid theme-danger" type="submit">
                            <i data-lucide="trash-2" class="me-1"></i>
                            {{ __('common.actions.delete') }}
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <div class="row">
            {{-- Main Message Body --}}
            <div class="lg:col-8">
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-header border-bottom-0 bg-1 py-3">
                        <h5 class="fw-bold mb-0">{{ $notification->title ?? 'N/A' }}</h5>
                    </div>
                    <div class="card-body pt-0">
                        <hr class="fg-secondary mb-3 mt-0" />
                        <p class="fg-secondary small fw-semibold text-uppercase mb-2">
                            {{ __('notifications.table.column_message') }}
                        </p>
                        <div class="rounded-3 p-3" style="white-space: pre-wrap; word-break: break-word; line-height: 1.6">
                            {{ $notification->message ?? 'N/A' }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Meta & Recipient Sidebar --}}
            <div class="lg:col-4">
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-header bg-1 py-3">
                        <h6 class="fw-bold mb-0">Metadata & Recipient</h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            {{-- Recipient Info --}}
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="fg-secondary small">Recipient</span>
                                <span class="fw-semibold text-end">
                                    @if ($recipient)
                                        {{ $recipient->name }}
                                        <small class="d-block fg-secondary">({{ $recipient->email }})</small>
                                    @else
                                        <span class="badge theme-secondary">User #{{ $notification->notifiable_id }}</span>
                                    @endif
                                </span>
                            </li>

                            {{-- Status --}}
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="fg-secondary small">{{ __('notifications.table.column_status') }}</span>
                                @if ($notification->read_at)
                                    <span class="badge theme-success border">
                                        <i data-lucide="check-check" class="me-1"></i>
                                        {{ __('notifications.status_read') }}
                                    </span>
                                @else
                                    <span class="badge theme-warning border">
                                        <i data-lucide="clock" class="me-1"></i> {{ __('notifications.status_unread') }}
                                    </span>
                                @endif
                            </li>

                            {{-- Sent Date --}}
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="fg-secondary small">{{ __('notifications.table.column_created_at') }}</span>
                                <span class="small">
                                    {{ $notification->created_at->format('M d, Y H:i:s') }}
                                    <small
                                        class="d-block fg-secondary text-end">({{ $notification->created_at->diffForHumans() }})</small>
                                </span>
                            </li>

                            {{-- Read Date --}}
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="fg-secondary small">{{ __('notifications.table.column_read_at') }}</span>
                                <span class="small">
                                    @if ($notification->read_at)
                                        {{ $notification->read_at->format('M d, Y H:i:s') }}
                                    @else
                                        <span class="fg-secondary">—</span>
                                    @endif
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
