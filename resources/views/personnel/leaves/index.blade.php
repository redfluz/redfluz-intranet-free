@php
    use App\Enums\Leave\LeaveStatus;
@endphp

@extends('layouts.app')

@push('styles')
    <style>
        .stat-card {
            border: none;
            border-radius: 10px;
            transition: transform 0.2s ease-in-out;
        }

        .stat-card:hover {
            transform: translateY(-2px);
        }

        .icon-square {
            width: 48px;
            height: 48px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }
    </style>
@endpush

@section('content')
    <div class="container">
        @include('partials.alerts')

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">{{ __('leaves.dashboard.title') }}</h3>
                <p class="fg-secondary small mb-0">{{ __('leaves.dashboard.subtitle') }}</p>
            </div>
            <div class="d-flex gap-2">
                <a class="btn btn-solid theme-secondary shadow-sm" href="{{ route('personnel.leaves.calendar') }}">
                    {{ __('leaves.calendar.title') }}
                </a>
                <a class="btn btn-solid theme-secondary shadow-sm" href="{{ route('personnel.leaves.holidays') }}">
                    {{ __('holidays.title') }}
                </a>
                <a class="btn btn-solid theme-primary btn-sm" href="{{ route('personnel.leaves.create') }}">
                    <i class="me-1" data-lucide="plus"></i>{{ __('common.actions.add') }}
                </a>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-12 sm:col-6 md:col-3">
                <div class="card stat-card p-3 shadow-sm">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span
                                class="fg-secondary small fw-medium d-block">{{ __('leaves.dashboard.pending_requests') }}</span>
                            <h4 class="fw-bold mb-0 mt-1">{{ $pendingCount }}</h4>
                        </div>
                        <div class="icon-square theme-warning fg-warning"><i data-lucide="calendar-clock"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center border-0 bg-1 py-3">
                        <h6 class="fw-bold mb-0">{{ __('leaves.dashboard.recent_requests') }}</h6>
                    </div>
                    <div class="table-responsive rounded-5 overflow-hidden border">
                        <table class="table-hover mb-0 table align-middle">
                            <thead class="bg-1">
                                <tr class="fg-secondary small">
                                    <th scope="col">{{ __('leaves.table.column_dates') }}</th>
                                    <th scope="col">{{ __('leaves.table.column_days') }}</th>
                                    <th scope="col">{{ __('leaves.table.column_status') }}</th>
                                    <th scope="col">{{ __('leaves.table.column_reason') }}</th>
                                    <th scope="col">Attachment</th>
                                    <th scope="col" class="text-end">{{ __('leaves.table.column_action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentRequests as $leave)
                                    <tr>
                                        <td class="small">
                                            {{ $leave->start_date->format('M d') }}
                                            @if (!$leave->start_date->isSameDay($leave->end_date))
                                                - {{ $leave->end_date->format('M d, Y') }}
                                            @else
                                                , {{ $leave->start_date->format('Y') }}
                                            @endif
                                        </td>
                                        <td>{{ $leave->total_days }} {{ __('leaves.dashboard.days_suffix') }}</td>
                                        <td>
                                            <span class="badge {{ $leave->status->badgeClass() }}">
                                                {{ __('leaves.status.' . $leave->status->value) }}
                                            </span>
                                        </td>
                                        <td class="fg-secondary small">{{ $leave->reason ?: '-' }}</td>
                                        <td>
                                            @if ($leave->attachment_path)
                                                <a class="btn btn-sm btn-solid theme-secondary px-2 py-0"
                                                    data-fancybox="leave-attachment"
                                                    data-type="{{ Str::endsWith(strtolower($leave->attachment_path), '.pdf') ? 'iframe' : 'image' }}"
                                                    href="{{ Storage::url($leave->attachment_path) }}">
                                                    View MC
                                                </a>
                                            @else
                                                <span class="fg-secondary small">-</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            @if (in_array($leave->status, [LeaveStatus::DRAFT, LeaveStatus::PENDING]))
                                                <div class="d-flex justify-content-end gap-1">
                                                    <a class="btn btn-sm btn-solid theme-primary"
                                                        href="{{ route('personnel.leaves.edit', $leave) }}">
                                                        Edit
                                                    </a>
                                                    <form action="{{ route('personnel.leaves.destroy', $leave) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('{{ __('leaves.dashboard.confirm_cancel') }}');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-sm btn-solid theme-danger" type="submit">
                                                            {{ __('leaves.dashboard.cancel_button') }}
                                                        </button>
                                                    </form>
                                                </div>
                                            @else
                                                <a class="btn btn-sm btn-solid theme-secondary"
                                                    href="{{ route('personnel.leaves.show', $leave->id) }}">
                                                    {{ __('leaves.dashboard.details_button') }}
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="fg-secondary py-4 text-center" colspan="6">
                                            {{ __('leaves.dashboard.no_requests') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if ($recentRequests->hasPages())
                        <div class="card-footer border-0 bg-1 py-3">{{ $recentRequests->links() }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
