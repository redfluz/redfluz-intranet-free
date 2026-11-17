@php
    use App\Enums\Leave\LeaveStatus;
@endphp

@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="lg:col-8">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h3 class="fw-bold mb-0">Leave Request Details</h3>
                        <span class="fg-secondary small">Ref: {{ $leave->reference_number ?? 'N/A' }}</span>
                    </div>
                    <a class="btn btn-solid theme-secondary btn-sm" href="{{ route('personnel.leaves.index') }}"><i
                            class="me-1" data-lucide="arrow-left"></i>{{ __('common.actions.back') }}</a>
                </div>

                @include('partials.alerts')

                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        {{-- Status Header --}}
                        <div class="d-flex justify-content-between align-items-center border-bottom mb-4 pb-3">
                            <div>
                                <span class="fg-secondary small d-block">Status</span>
                                @php
                                    $statusClass = match ($leave->status) {
                                        LeaveStatus::APPROVED, LeaveStatus::APPROVED->value => 'bg-success',
                                        LeaveStatus::REJECTED, LeaveStatus::REJECTED->value => 'bg-danger',
                                        LeaveStatus::PENDING,
                                        LeaveStatus::SUBMITTED,
                                        LeaveStatus::PENDING->value,
                                        LeaveStatus::SUBMITTED->value
                                            => 'theme-warning fg-body',
                                        default => 'bg-secondary',
                                    };
                                    $statusValue = is_object($leave->status) ? $leave->status->value : $leave->status;
                                @endphp
                                <span class="badge {{ $statusClass }} text-capitalize px-3 py-2">
                                    {{ $statusValue }}
                                </span>
                            </div>
                            <div class="text-end">
                                <span class="fg-secondary small d-block">Applied On</span>
                                <span class="fw-medium"> {{ $leave->created_at->format('M d, Y') ?? '-' }} </span>
                            </div>
                        </div>

                        {{-- Details Grid --}}
                        <div class="row g-3 mb-4">
                            <div class="md:col-6">
                                <div class="fg-secondary small d-block">Total Days</div>
                                <p class="fw-semibold mb-0">
                                    {{ $leave->total_days }} {{ Str::plural('day', $leave->total_days) }}
                                </p>
                            </div>

                            <div class="md:col-6">
                                <div class="fg-secondary small d-block">{{ __('leaves.dashboard.from_date') }}</div>
                                <p class="fw-semibold mb-0">{{ $leave->start_date->format('M d, Y') }}</p>
                            </div>

                            <div class="md:col-6">
                                <div class="fg-secondary small d-block">{{ __('leaves.dashboard.to_date') }}</div>
                                <p class="fw-semibold mb-0">{{ $leave->end_date->format('M d, Y') }}</p>
                            </div>
                        </div>

                        {{-- Reason --}}
                        <div class="mb-4">
                            <div class="fg-secondary small d-block">{{ __('leaves.table.column_reason') }}</div>
                            <div class="bg-1 fg-secondary rounded-3 p-3">
                                {{ $leave->reason ?: 'No reason provided.' }}
                            </div>
                        </div>

                        {{-- Rejection Reason --}}
                        @if ($leave->status === LeaveStatus::REJECTED && $leave->rejection_reason)
                            <div class="mb-4">
                                <div class="fg-secondary small d-block">{{ __('leaves.admin.rejection_reason') }}</div>
                                <div class="bg-1 fg-danger rounded-3 p-3">
                                    {{ $leave->rejection_reason }}
                                </div>
                            </div>
                        @endif

                        {{-- Attachment --}}
                        <div class="mb-4">
                            <div class="fg-secondary small d-block mb-1">Medical Certificate / Attachment</div>
                            @if ($leave->attachment_path && Str::endsWith(strtolower($leave->attachment_path), ['.jpg', '.jpeg', '.png', '.gif', '.webp']))
                                <a data-fancybox="leave-attachment" data-caption="{{ $leave->reference_number }}"
                                    href="{{ Storage::url($leave->attachment_path) }}">
                                    <img class="img-thumbnail rounded-3 border"
                                        src="{{ Storage::url($leave->attachment_path) }}" alt="Medical Certificate / Attachment"
                                        style="max-height: 220px; cursor: pointer" />
                                </a>
                            @elseif ($leave->attachment_path)
                                <a class="btn btn-solid theme-info btn-sm" data-fancybox="leave-attachment"
                                    data-type="iframe" data-caption="{{ $leave->reference_number }}"
                                    href="{{ Storage::url($leave->attachment_path) }}">
                                    View Attached Document
                                </a>
                            @else
                                <span class="fg-secondary small">No attachment provided.</span>
                            @endif
                        </div>

                        {{-- Actions --}}
                        <div class="d-flex justify-content-end border-top gap-2 pt-3">
                            <a class="btn btn-solid theme-secondary btn-sm" href="{{ route('personnel.leaves.index') }}"><i
                                    class="me-1" data-lucide="arrow-left"></i>{{ __('common.actions.back') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
