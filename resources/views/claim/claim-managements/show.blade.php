@php
    use App\Enums\Claim\ClaimStatus;
    use App\Enums\FileAction;
    use App\Enums\FileResources;

    $canApprove = auth()->user()->hasFilePermission(FileResources::CLAIM_MANAGEMENTS, FileAction::APPROVE);
    $canReject = auth()->user()->hasFilePermission(FileResources::CLAIM_MANAGEMENTS, FileAction::CANCEL);
    $isDecidable = in_array($claim->status, [ClaimStatus::PENDING, ClaimStatus::SUBMITTED], true);
    $isImage = $claim->attachment_path
        && Str::endsWith(strtolower($claim->attachment_path), ['.jpg', '.jpeg', '.png', '.gif', '.webp']);
@endphp

@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="lg:col-8">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h3 class="fw-bold mb-0">{{ __('claims.management.show_title') }}</h3>
                        <span class="fg-secondary small">{{ __('claims.table.column_reference_number') }}:
                            {{ $claim->reference_number ?? 'N/A' }}</span>
                    </div>
                    <a class="btn btn-solid theme-secondary btn-sm" href="{{ route('claim.claim-managements.index') }}">
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
                                <span class="fg-secondary small d-block">{{ __('claims.table.column_status') }}</span>
                                <span class="badge {{ $claim->status->badgeClass() }} px-3 py-2">
                                    {{ __('claims.status.' . $claim->status->value) }}
                                </span>
                            </div>
                            <div class="text-end">
                                <span class="fg-secondary small d-block">{{ __('claims.management.applied_on') }}</span>
                                <span class="fw-medium">{{ $claim->created_at->format('d M Y') }}</span>
                            </div>
                        </div>

                        {{-- Details Grid --}}
                        <div class="row g-3 mb-4">
                            <div class="md:col-6">
                                <div class="fg-secondary small d-block">{{ __('claims.table.column_employee') }}</div>
                                <p class="fw-semibold mb-0">{{ $claim->user->name }}</p>
                            </div>
                            <div class="md:col-6">
                                <div class="fg-secondary small d-block">{{ __('claims.table.column_amount') }}</div>
                                <p class="fw-semibold mb-0">
                                    {{ __('claims.currency') }} {{ number_format((float) $claim->amount, 2) }}
                                </p>
                            </div>
                            <div class="md:col-6">
                                <div class="fg-secondary small d-block">{{ __('claims.table.column_receipt_date') }}</div>
                                <p class="fw-semibold mb-0">{{ $claim->receipt_date->format('d M Y') }}</p>
                            </div>
                            <div class="md:col-6">
                                <div class="fg-secondary small d-block">{{ __('claims.table.column_receipt_no') }}</div>
                                <p class="fw-semibold mb-0">{{ $claim->receipt_no ?: '-' }}</p>
                            </div>
                        </div>

                        {{-- Description --}}
                        <div class="mb-4">
                            <div class="fg-secondary small d-block">{{ __('claims.table.column_description') }}</div>
                            <div class="bg-1 fg-secondary rounded-3 p-3">
                                {{ $claim->description ?: '-' }}
                            </div>
                        </div>

                        {{-- Rejection Reason --}}
                        @if ($claim->status === ClaimStatus::REJECTED && $claim->rejection_reason)
                            <div class="mb-4">
                                <div class="fg-secondary small d-block">{{ __('claims.management.rejection_reason') }}</div>
                                <div class="bg-1 fg-danger rounded-3 p-3">
                                    {{ $claim->rejection_reason }}
                                </div>
                            </div>
                        @endif

                        {{-- Decision Info --}}
                        @if ($claim->approver && $claim->approved_at)
                            <div class="mb-4">
                                <div class="fg-secondary small d-block">{{ __('claims.management.decided_by') }}</div>
                                <p class="fw-semibold mb-0">
                                    {{ $claim->approver->name }}
                                    <span class="fg-secondary small fw-normal">·
                                        {{ $claim->approved_at->format('d M Y, H:i') }}</span>
                                </p>
                            </div>
                        @endif

                        {{-- Attachment --}}
                        <div class="mb-4">
                            <div class="fg-secondary small d-block mb-1">{{ __('claims.table.column_attachment') }}</div>
                            @if ($claim->attachment_path && $isImage)
                                <a data-fancybox="claim-attachment" data-caption="{{ $claim->reference_number }}"
                                    href="{{ Storage::url($claim->attachment_path) }}">
                                    <img class="img-thumbnail rounded-3 border"
                                        src="{{ Storage::url($claim->attachment_path) }}"
                                        alt="{{ __('claims.table.column_attachment') }}"
                                        style="max-height: 220px; cursor: pointer" />
                                </a>
                            @elseif ($claim->attachment_path)
                                <a class="btn btn-solid theme-info btn-sm" data-fancybox="claim-attachment"
                                    data-type="iframe" data-caption="{{ $claim->reference_number }}"
                                    href="{{ Storage::url($claim->attachment_path) }}">
                                    <i class="me-1" data-lucide="file-text"></i>{{ __('common.actions.view') }}
                                </a>
                            @else
                                <span class="fg-secondary small">-</span>
                            @endif
                        </div>

                        {{-- Actions --}}
                        <div class="d-flex justify-content-end border-top gap-2 pt-3">
                            <a class="btn btn-solid theme-secondary btn-sm"
                                href="{{ route('claim.claim-managements.index') }}">
                                <i class="me-1" data-lucide="arrow-left"></i>{{ __('common.actions.back') }}
                            </a>
                            @if ($isDecidable && $canReject)
                                <button class="btn btn-solid theme-danger btn-sm" type="button" id="openRejectDialog">
                                    <i class="me-1" data-lucide="x"></i>{{ __('claims.actions.reject') }}
                                </button>
                            @endif
                            @if ($isDecidable && $canApprove)
                                <button class="btn btn-solid theme-success btn-sm" type="button" id="openApproveDialog">
                                    <i class="me-1" data-lucide="check"></i>{{ __('claims.actions.approve') }}
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
        <dialog class="dialog" id="approveClaimDialog">
            <form action="{{ route('claim.claim-managements.approve', $claim) }}" method="POST">
                @csrf
                <div class="dialog-header">
                    <h5 class="dialog-title">{{ __('claims.management.approve_title') }}</h5>
                    <button class="btn-close" data-bs-dismiss="dialog" type="button"></button>
                </div>
                <div class="dialog-body">
                    <p class="mb-0">{{ __('claims.confirm.approve_claim') }}</p>
                    <p class="fg-secondary small mb-0 mt-2">
                        {{ __('claims.table.column_reference_number') }}:
                        <span class="fw-bold">{{ $claim->reference_number }}</span>
                    </p>
                </div>
                <div class="dialog-footer">
                    <button class="btn btn-solid theme-light" data-bs-dismiss="dialog"
                        type="button">{{ __('common.actions.cancel') }}</button>
                    <button class="btn btn-solid theme-success" type="submit">{{ __('claims.actions.approve') }}</button>
                </div>
            </form>
        </dialog>
    @endif

    @if ($isDecidable && $canReject)
        {{-- Reject Dialog --}}
        <dialog class="dialog" id="rejectClaimDialog">
            <form action="{{ route('claim.claim-managements.reject', $claim) }}" method="POST">
                @csrf
                <div class="dialog-header">
                    <h5 class="dialog-title">{{ __('claims.management.reject_title') }}</h5>
                    <button class="btn-close" data-bs-dismiss="dialog" type="button"></button>
                </div>
                <div class="dialog-body">
                    <p class="fg-secondary small mb-2">
                        {{ __('claims.table.column_reference_number') }}:
                        <span class="fw-bold">{{ $claim->reference_number }}</span>
                    </p>
                    <label class="form-label fw-medium" for="rejection_reason">{{ __('claims.management.rejection_reason') }}
                        <span class="fg-danger">*</span></label>
                    <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="3" maxlength="1000"
                        required>{{ old('rejection_reason') }}</textarea>
                </div>
                <div class="dialog-footer">
                    <button class="btn btn-solid theme-light" data-bs-dismiss="dialog"
                        type="button">{{ __('common.actions.cancel') }}</button>
                    <button class="btn btn-solid theme-danger" type="submit">{{ __('claims.actions.reject') }}</button>
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
                    bootstrap.Dialog.getOrCreateInstance(document.getElementById('approveClaimDialog')).show();
                });
            }

            if (rejectButton) {
                rejectButton.addEventListener('click', () => {
                    bootstrap.Dialog.getOrCreateInstance(document.getElementById('rejectClaimDialog')).show();
                });
            }

            @if ($errors->has('rejection_reason'))
                bootstrap.Dialog.getOrCreateInstance(document.getElementById('rejectClaimDialog')).show();
            @endif
        });
    </script>
@endsection