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
    use App\Enums\Claim\ClaimStatus;
    use App\Enums\FileAction;
    use App\Enums\FileResources;

    $canCreate = auth()->user()->hasFilePermission(FileResources::CLAIMS, FileAction::ADD);
    $canUpdate = auth()->user()->hasFilePermission(FileResources::CLAIMS, FileAction::UPDATE);
    $canRemove = auth()->user()->hasFilePermission(FileResources::CLAIMS, FileAction::REMOVE);
@endphp
@extends('layouts.app')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header d-flex align-items-center justify-content-between bg-1 py-3">
            <div>
                <h5 class="fw-bold mb-0">{{ __('claims.title') }}</h5>
                <p class="fg-secondary small mb-0">{{ __('claims.subtitle') }}</p>
            </div>
            @if ($canCreate)
                <a class="btn btn-solid theme-primary btn-sm" href="{{ route('personnel.claims.create') }}">
                    <i class="me-1" data-lucide="plus"></i>
                    {{ __('common.actions.add') }}
                </a>
            @endif
        </div>
        <div class="card-body">
            @include('partials.alerts')

            <div class="row g-3 mb-4">
                <div class="sm:col-6 md:col-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fg-secondary small fw-medium">{{ __('claims.pending_request') }}</div>
                                <div class="fs-3 fw-bold mt-1">{{ $pendingCount }}</div>
                            </div>
                            <i class="fg-warning" data-lucide="clock-3"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive rounded-5 overflow-hidden border">
                <table class="table-hover mb-0 table align-middle">
                    <thead class="bg-1">
                        <tr class="fg-secondary small">
                            <th scope="col">{{ __('claims.table.column_reference_number') }}</th>
                            <th scope="col">{{ __('claims.table.column_receipt_date') }}</th>
                            <th scope="col">{{ __('claims.table.column_receipt_no') }}</th>
                            <th scope="col">{{ __('claims.table.column_amount') }}</th>
                            <th scope="col">{{ __('claims.table.column_status') }}</th>
                            <th scope="col">{{ __('claims.table.column_attachment') }}</th>
                            <th scope="col" class="text-end">{{ __('common.labels.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentRequests as $claim)
                            <tr>
                                <td>
                                    <span class="fw-semibold"> {{ $claim->reference_number }} </span>
                                </td>
                                <td class="small">{{ $claim->receipt_date->format('d/m/Y') }}</td>
                                <td class="small">{{ $claim->receipt_no ?: '-' }}</td>
                                <td class="fw-semibold">
                                    {{ __('claims.currency') }} {{ number_format((float) $claim->amount, 2) }}
                                </td>
                                <td>
                                    <span class="badge {{ $claim->status->badgeClass() }}">
                                        {{ __('claims.status.' . $claim->status->value) }}
                                    </span>
                                </td>
                                <td>
                                    @if ($claim->attachment_path)
                                        <a class="btn btn-sm btn-solid theme-secondary px-2 py-0"
                                            data-fancybox="claim-attachment"
                                            data-type="{{ Str::endsWith(strtolower($claim->attachment_path), '.pdf') ? 'iframe' : 'image' }}"
                                            href="{{ Storage::url($claim->attachment_path) }}">
                                            {{ __('common.actions.view') }}
                                        </a>
                                    @else
                                        <span class="fg-secondary small">-</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    {{-- Editable only when DRAFT, PENDING, or SUBMITTED --}}
                                    @if (in_array($claim->status, [ClaimStatus::DRAFT, ClaimStatus::PENDING, ClaimStatus::SUBMITTED]))
                                        <div class="d-flex justify-content-end gap-1">
                                            @if ($canUpdate)
                                                <a class="btn btn-sm btn-solid theme-primary"
                                                    href="{{ route('personnel.claims.edit', $claim) }}"
                                                    title="{{ __('common.actions.edit') }}">
                                                    <i data-lucide="square-pen"></i>
                                                </a>
                                            @endif
                                            @if ($canRemove)
                                                <form action="{{ route('personnel.claims.destroy', $claim) }}"
                                                    method="POST"
                                                    data-confirm-message="{{ __('claims.confirm.cancel_claim') }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-solid theme-danger" type="submit">
                                                        {{ __('common.actions.cancel') }}
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    @else
                                        {{-- Locked once Approved or Rejected --}}
                                        <span class="btn btn-sm btn-solid theme-secondary disabled">
                                            {{ __('common.actions.locked') }}
                                        </span>
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

            @if ($recentRequests->hasPages())
                <div class="d-flex justify-content-end mt-3">{{ $recentRequests->links() }}</div>
            @endif
        </div>
    </div>
@endsection
