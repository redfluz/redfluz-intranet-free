@php
    use App\Enums\FileAction;
    use App\Enums\FileResources;
    use App\Enums\Leave\LeaveStatus;

    $canApprove = auth()->user()->hasFilePermission(FileResources::LEAVE_MANAGEMENTS, FileAction::APPROVE);
    $canReject = auth()->user()->hasFilePermission(FileResources::LEAVE_MANAGEMENTS, FileAction::CANCEL);
    $isDecidable = in_array($leave->status, [LeaveStatus::PENDING, LeaveStatus::SUBMITTED], true);
    $isImage = $leave->attachment_path
        && Str::endsWith(strtolower($leave->attachment_path), ['.jpg', '.jpeg', '.png', '.gif', '.webp']);
@endphp

@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="lg:col-8">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h3 class="fw-bold mb-0">{{ __('leaves.admin.show_title') }}</h3>
                        <span class="fg-secondary small">{{ __('leaves.table.column_reference_number') }}:
                            {{ $leave->reference_number ?? 'N/A' }}</span>
                    </div>
                    <a class="btn btn-solid theme-secondary btn-sm" href="{{ route('leave.leave-managements.index') }}">
                        <i class="me-1" data-lucide="arrow-left"></i>{{ __('common.actions.back') }}
                    </a>
                </div>

                @include('partials.alerts')
                @include('partials.errors')

                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        {{-- Status Header --}}
                        <div class="d-flex justify-content-between align-items-center border-bottom mb-4 pb-3">
                            <div>
                                <span class="fg-secondary small d-block">{{ __('leaves.table.column_status') }}</span>
                                <span class="badge {{ $leave->status->badgeClass() }} px-3 py-2">
                                    {{ __('leaves.status.' . $leave->status->value) }}
                                </span>
                            </div>
                            <div class="text-end">
                                <span class="fg-secondary small d-block">{{ __('leaves.admin.applied_on') }}</span>
                                <span class="fw-medium">{{ $leave->created_at->format('d M Y') }}</span>
                            </div>
                        </div>

                        {{-- Details Grid --}}
                        <div class="row g-3 mb-4">
                            <div class="md:col-6">
                                <div class="fg-secondary small d-block">{{ __('leaves.table.column_employee') }}</div>
                                <p class="fw-semibold mb-0">{{ $leave->employee->name }}</p>
                                @if ($leave->employee?->role)
                                    <span class="fg-secondary small">{{ $leave->employee->role->name }}</span>
                                @endif
                            </div>
                            <div class="md:col-6">
                                <div class="fg-secondary small d-block">{{ __('leaves.table.column_days') }}</div>
                                <p class="fw-semibold mb-0">
                                    {{ $leave->total_days }} {{ Str::plural('day', $leave->total_days) }}
                                </p>
                            </div>
                            <div class="md:col-6">
                                <div class="fg-secondary small d-block">{{ __('leaves.table.column_start_date') }}</div>
                                <p class="fw-semibold mb-0">{{ $leave->start_date->format('d M Y') }}</p>
                            </div>
                            <div class="md:col-6">
                                <div class="fg-secondary small d-block">{{ __('leaves.table.column_end_date') }}</div>
                                <p class="fw-semibold mb-0">{{ $leave->end_date->format('d M Y') }}</p>
                            </div>
                        </div>

                        {{-- Reason --}}
                        <div class="mb-4">
                            <div class="fg-secondary small d-block">{{ __('leaves.table.column_reason') }}</div>
                            <div class="bg-1 fg-secondary rounded-3 p-3">
                                {{ $leave->reason ?: '-' }}
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

                        {{-- Decision Info --}}
                        @if ($leave->approver && $leave->approved_at)
                            <div class="mb-4">
                                <div class="fg-secondary small d-block">{{ __('leaves.admin.decided_by') }}</div>
                                <p class="fw-semibold mb-0">
                                    {{ $leave->approver->name }}
                                    <span class="fg-secondary small fw-normal">·
                                        {{ $leave->approved_at->format('d M Y, H:i') }}</span>
                                </p>
                            </div>
                        @endif

                        {{-- Attachment --}}
                        <div class="mb-4">
                            <div class="fg-secondary small d-block mb-1">{{ __('leaves.admin.attachment') }}</div>
                            @if ($leave->attachment_path && $isImage)
                                <a data-fancybox="leave-attachment" data-caption="{{ $leave->reference_number }}"
                                    href="{{ Storage::url($leave->attachment_path) }}">
                                    <img class="img-thumbnail rounded-3 border"
                                        src="{{ Storage::url($leave->attachment_path) }}"
                                        alt="{{ __('leaves.admin.attachment') }}"
                                        style="max-height: 220px; cursor: pointer" />
                                </a>
                            @elseif ($leave->attachment_path)
                                <a class="btn btn-solid theme-info btn-sm" data-fancybox="leave-attachment"
                                    data-type="iframe" data-caption="{{ $leave->reference_number }}"
                                    href="{{ Storage::url($leave->attachment_path) }}">
                                    <i class="me-1" data-lucide="file-text"></i>{{ __('common.actions.view') }}
                                </a>
                            @else
                                <span class="fg-secondary small">-</span>
                            @endif
                        </div>

                        {{-- Actions --}}
                        <div class="d-flex justify-content-end border-top gap-2 pt-3">
                            <a class="btn btn-solid theme-secondary btn-sm"
                                href="{{ route('leave.leave-managements.index') }}">
                                <i class="me-1" data-lucide="arrow-left"></i>{{ __('common.actions.back') }}
                            </a>
                            @if ($isDecidable && $canReject)
                                <button class="btn btn-solid theme-danger btn-sm" type="button" id="openRejectDialog">
                                    <i class="me-1" data-lucide="x"></i>{{ __('leaves.admin.reject') }}
                                </button>
                            @endif
                            @if ($isDecidable && $canApprove)
                                <button class="btn btn-solid theme-success btn-sm" type="button" id="openApproveDialog">
                                    <i class="me-1" data-lucide="check"></i>{{ __('leaves.admin.approve') }}
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($isDecidable && $canApprove)
        {{-- Approve Confirmation Dialog --}}
        <dialog class="dialog" id="approveLeaveDialog">
            <form action="{{ route('leave.leave-managements.approve', $leave) }}" method="POST">
                @csrf
                <div class="dialog-header">
                    <h5 class="dialog-title">{{ __('leaves.admin.approve_title') }}</h5>
                    <button class="btn-close" data-bs-dismiss="dialog" type="button"></button>
                </div>
                <div class="dialog-body">
                    <p class="mb-0">{{ __('leaves.admin.confirm_approve') }}</p>
                    <p class="fg-secondary small mb-0 mt-2">
                        {{ __('leaves.table.column_reference_number') }}:
                        <span class="fw-bold">{{ $leave->reference_number }}</span>
                    </p>
                </div>
                <div class="dialog-footer">
                    <button class="btn btn-solid theme-light" data-bs-dismiss="dialog"
                        type="button">{{ __('common.actions.cancel') }}</button>
                    <button class="btn btn-solid theme-success" type="submit">{{ __('leaves.admin.approve') }}</button>
                </div>
            </form>
        </dialog>
    @endif

    @if ($isDecidable && $canReject)
        {{-- Reject Dialog --}}
        <dialog class="dialog" id="rejectLeaveDialog">
            <form action="{{ route('leave.leave-managements.reject', $leave) }}" method="POST">
                @csrf
                <div class="dialog-header">
                    <h5 class="dialog-title">{{ __('leaves.admin.reject_title') }}</h5>
                    <button class="btn-close" data-bs-dismiss="dialog" type="button"></button>
                </div>
                <div class="dialog-body">
                    <p class="fg-secondary small mb-2">
                        {{ __('leaves.table.column_reference_number') }}:
                        <span class="fw-bold">{{ $leave->reference_number }}</span>
                    </p>
                    <label class="form-label fw-medium" for="rejection_reason">{{ __('leaves.admin.rejection_reason') }}
                        <span class="fg-danger">*</span></label>
                    <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="3" maxlength="1000"
                        required>{{ old('rejection_reason') }}</textarea>
                </div>
                <div class="dialog-footer">
                    <button class="btn btn-solid theme-light" data-bs-dismiss="dialog"
                        type="button">{{ __('common.actions.cancel') }}</button>
                    <button class="btn btn-solid theme-danger" type="submit">{{ __('leaves.admin.reject') }}</button>
                </div>
            </form>
        </dialog>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const approveButton = document.getElementById('openApproveDialog');
            const rejectButton = document.getElementById('openRejectDialog');

            if (approveButton) {
                approveButton.addEventListener('click', () => {
                    bootstrap.Dialog.getOrCreateInstance(document.getElementById('approveLeaveDialog')).show();
                });
            }

            if (rejectButton) {
                rejectButton.addEventListener('click', () => {
                    bootstrap.Dialog.getOrCreateInstance(document.getElementById('rejectLeaveDialog')).show();
                });
            }

            @if ($errors->has('rejection_reason'))
                bootstrap.Dialog.getOrCreateInstance(document.getElementById('rejectLeaveDialog')).show();
            @endif
        });
    </script>
@endsection