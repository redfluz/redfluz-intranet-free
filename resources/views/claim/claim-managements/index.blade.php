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
@endphp

@extends('layouts.app')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header d-flex align-items-center justify-content-between bg-1 py-3">
            <h4 class="fw-bold m-0">
                {{ __('claims.management.title') }}
                <p class="fg-secondary small mb-0">{{ __('claims.management.subtitle') }}</p>
            </h4>
        </div>

        <div class="card-body">
            @include('partials.alerts')
            @include('partials.errors')

            <form class="row g-2 mb-4" method="GET" action="{{ route('claim.claim-managements.index') }}" autocomplete="off">
                <div class="md:col-4">
                    <label class="visually-hidden" for="search">{{ __('common.actions.search') }}</label>
                    <div class="form-control form-adorn">
                        <span class="form-adorn-icon" aria-hidden="true"><i data-lucide="search"></i></span>
                        <input id="search" class="form-ghost" name="search" type="text" value="{{ $search ?? '' }}"
                            placeholder="{{ __('claims.filters.search_placeholder') }}">
                    </div>
                </div>
                <div class="md:col-3">
                    <label class="visually-hidden" for="status">{{ __('claims.table.column_status') }}</label>
                    <select id="status" class="form-control" name="status">
                        <option value="">{{ __('claims.filters.all_statuses') }}</option>
                        {{-- Only statuses reachable in the approve/reject workflow. --}}
                        @foreach ([ClaimStatus::DRAFT, ClaimStatus::SUBMITTED, ClaimStatus::PENDING, ClaimStatus::APPROVED, ClaimStatus::REJECTED] as $statusOption)
                            <option value="{{ $statusOption->value }}"
                                {{ ($status ?? '') === $statusOption->value ? 'selected' : '' }}>
                                {{ __('claims.status.' . $statusOption->value) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-2">
                    <label class="visually-hidden" for="start_date">{{ __('financial_reports.form.start_date') }}</label>
                    <input id="start_date" class="form-control" name="start_date" type="text" data-bs-toggle="datepicker"
                        value="{{ $startDate ?? '' }}">
                </div>
                <div class="md:col-2">
                    <label class="visually-hidden" for="end_date">{{ __('financial_reports.form.end_date') }}</label>
                    <input id="end_date" class="form-control" name="end_date" type="text" data-bs-toggle="datepicker"
                        value="{{ $endDate ?? '' }}">
                </div>
                <div class="md:col-1">
                    <button class="btn btn-solid theme-secondary w-100" type="submit"
                        title="{{ __('common.actions.filter') }}">
                        <i class="me-1" data-lucide="search"></i>
                    </button>
                </div>
            </form>

            <div class="table-responsive rounded-5 overflow-hidden border">
                <table class="table-hover table-striped mb-0 table align-middle">
                    <thead class="bg-1">
                        <tr>
                            <th scope="col">{{ __('claims.table.column_reference_number') }}</th>
                            <th scope="col">{{ __('claims.table.column_employee') }}</th>
                            <th scope="col">{{ __('claims.table.column_receipt_date') }}</th>
                            <th scope="col" class="text-end">{{ __('claims.table.column_amount') }}</th>
                            <th scope="col" class="text-center">{{ __('claims.table.column_status') }}</th>
                            <th scope="col" class="text-end">{{ __('common.labels.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($claims as $claim)
                            <tr>
                                <td><span class="fw-bold">{{ $claim->reference_number }}</span></td>
                                <td>
                                    <div class="fw-bold">{{ $claim->user->name }}</div>
                                </td>
                                <td>{{ $claim->receipt_date->format('d/m/Y') }}</td>
                                <td class="fw-semibold text-end">{{ number_format((float) $claim->amount, 2) }}</td>
                                <td class="text-center">
                                    <span
                                        class="badge {{ $claim->status->badgeClass() }}">{{ __('claims.status.' . $claim->status->value) }}</span>
                                    @if ($claim->status === ClaimStatus::REJECTED && $claim->rejection_reason)
                                        <div class="fg-secondary small mt-1" title="{{ $claim->rejection_reason }}">
                                            {{ Str::limit($claim->rejection_reason, 40) }}
                                        </div>
                                    @endif
                                </td>
                                <td class="text-end">
                                    @if (in_array($claim->status, [ClaimStatus::PENDING, ClaimStatus::SUBMITTED], true))
                                        <a class="btn btn-sm btn-solid theme-primary"
                                            href="{{ route('claim.claim-managements.show', $claim) }}">
                                            <i class="me-1" data-lucide="eye"></i>{{ __('common.actions.review') }}
                                        </a>
                                    @else
                                        <a class="btn btn-sm btn-solid theme-light"
                                            href="{{ route('claim.claim-managements.show', $claim) }}">
                                            <i class="me-1" data-lucide="eye"></i>{{ __('common.actions.view') }}
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="fg-secondary py-4 text-center" colspan="6">
                                    {{ __('claims.messages.no_records') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end mt-3">{{ $claims->links() }}</div>
        </div>
    </div>
@endsection