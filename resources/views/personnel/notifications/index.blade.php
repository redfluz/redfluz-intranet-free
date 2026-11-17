@php
    use App\Enums\FileAction;
    use App\Enums\FileResources;
    use App\Models\System\Notification;

    $canRemove = auth()->user()->hasFilePermission(FileResources::SYSTEM_NOTIFICATIONS, FileAction::REMOVE);
@endphp
@props([
    /** @var Notification */
    'notification',
])
@extends('layouts.app')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header d-flex align-items-center justify-content-between bg-1 py-3">
            <h5 class="fw-bold m-0">
                {{ __('notifications.title') }}
                <p class="fg-secondary small mb-0">{{ __('notifications.subtitle') }}</p>
            </h5>
            @if (auth()->user()->unreadNotifications()->count() > 0)
                <form action="{{ route('personnel.notifications.mark-all-as-read') }}" method="POST">
                    @csrf
                    <button class="btn btn-solid theme-primary btn-sm" type="submit">
                        <i data-lucide="check-check" class="me-1"></i>
                        {{ __('notifications.mark_all_read') }}
                    </button>
                </form>
            @endif
        </div>

        <div class="card-body">
            {{-- Render the alert partial here --}}
            @include('partials.alerts')

            <!-- Notification Table -->
            <div class="table-responsive rounded-5 overflow-hidden border">
                <table class="table-hover table-striped mb-0 table align-middle">
                    <thead class="bg-1">
                        <tr>
                            <th scope="col">    </th>
                            <th scope="col">{{ __('notifications.table.column_title') }}</th>
                            <th scope="col">{{ __('notifications.table.column_message') }}</th>
                            <th scope="col" class="col-date">{{ __('common.labels.created_at') }}</th>
                            <th scope="col" class="col-action text-center">{{ __('common.labels.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($notifications as $notification)
                            @php
                                $isUnread = is_null($notification->read_at);
                            @endphp
                            <tr class="{{ $isUnread ? 'fw-bold' : '' }}">
                                <td>
                                    @if ($isUnread)
                                        <span class="badge theme-primary rounded-circle p-2">
                                            <i data-lucide="bell"></i>
                                        </span>
                                    @else
                                        <span class="badge theme-secondary rounded-circle p-2 opacity-50">
                                            <i data-lucide="bell-off"></i>
                                        </span>
                                    @endif
                                </td>
                                <td>{{ $notification->title ?? __('notifications.detail_title') }}</td>
                                <td class="fg-secondary small">{{ $notification->message }}</td>
                                <td>
                                    <i data-lucide="clock" class="me-1"></i>
                                    {{ $notification->created_at->diffForHumans() }}
                                </td>
                                <td class="text-center">
                                    <a class="btn btn-sm btn-solid theme-info me-1 text-white"
                                        href="{{ route('personnel.notifications.show', $notification->id) }}"
                                        title="View Notification">
                                        <i data-lucide="eye"></i>
                                    </a>

                                    @if ($canRemove)
                                        <button class="btn btn-sm btn-solid theme-danger btn-delete"
                                            data-id="{{ $notification->id }}" type="button"
                                            title="{{ __('common.actions.delete') }}">
                                            <i data-lucide="trash-2"></i>
                                        </button>

                                        <form class="d-none" id="delete-form-{{ $notification->id }}"
                                            action="{{ route('personnel.notifications.destroy', $notification->id) }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="fg-secondary py-4 text-center" colspan="5">
                                    {{ __('common.labels.not_found_record') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Links -->
            @if ($notifications->hasPages())
                <div class="d-flex justify-content-end mt-3">{{ $notifications->links() }}</div>
            @endif
        </div>
    </div>

    {{-- Delete Confirmation Dialog --}}
    <dialog class="dialog" id="deleteConfirmModal">
        <form method="POST" id="deleteForm">
            @csrf
            @method('DELETE')
            <div class="dialog-header">
                <h5 class="dialog-title">{{ __('common.alerts.confirm_delete') }}</h5>
                <button class="btn-close" data-bs-dismiss="dialog" type="button"></button>
            </div>
            <div class="dialog-body">
                <p>{{ __('common.alerts.confirm_delete_message') }}</p>
            </div>
            <div class="dialog-footer">
                <button class="btn btn-solid theme-secondary" data-bs-dismiss="dialog" type="button">
                    {{ __('common.actions.cancel') }}
                </button>
                <button class="btn btn-solid theme-danger" type="submit">
                    {{ __('common.alerts.confirm_delete_yes') }}
                </button>
            </div>
        </form>
    </dialog>
@endsection

@section('script')
    <script type="module">
        document.addEventListener('click', function(e) {
            const deleteBtn = e.target.closest('.btn-delete');

            if (deleteBtn) {
                const dataId = deleteBtn.getAttribute('data-id');
                document.getElementById('deleteForm').action =
                    "{{ route('personnel.notifications.destroy', '__ID__') }}".replace('__ID__', dataId);
                bootstrap.Dialog.getOrCreateInstance(document.getElementById('deleteConfirmModal')).show();
            }
        });
    </script>
@endsection
