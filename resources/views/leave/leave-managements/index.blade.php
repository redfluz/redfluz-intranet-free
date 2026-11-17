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
    use App\Enums\Leave\LeaveStatus;
@endphp

@extends('layouts.app')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header d-flex align-items-center justify-content-between bg-1 py-3">
            <h4 class="fw-bold m-0">{{ __('leaves.admin.title') }}</h4>
        </div>

        <div class="card-body">
            @include('partials.alerts')

            <form class="row g-2 mb-4" method="GET" action="{{ route('leave.leave-managements.index') }}" autocomplete="off">
                <div class="md:col-4">
                    <label class="visually-hidden" for="search">{{ __('common.actions.search') }}</label>
                    <div class="form-control form-adorn">
                        <span class="form-adorn-icon" aria-hidden="true"><i data-lucide="search"></i></span>
                        <input id="search" class="form-ghost" name="search" type="text" value="{{ $search }}"
                            placeholder="{{ __('leaves.admin.search_placeholder') }}">
                    </div>
                </div>
                <div class="md:col-3">
                    <label class="visually-hidden" for="status">{{ __('leaves.table.column_status') }}</label>
                    <select id="status" class="form-control" name="status">
                        <option value="">{{ __('leaves.admin.all_status') }}</option>
                        @foreach (LeaveStatus::cases() as $statusOption)
                            <option value="{{ $statusOption->value }}"
                                {{ $status === $statusOption->value ? 'selected' : '' }}>
                                {{ __('leaves.status.' . $statusOption->value) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-2">
                    <label class="visually-hidden" for="start_date">{{ __('leaves.table.column_start_date') }}</label>
                    <input id="start_date" class="form-control" name="start_date" type="text" data-bs-toggle="datepicker"
                        value="{{ $startDate }}">
                </div>
                <div class="md:col-2">
                    <label class="visually-hidden" for="end_date">{{ __('leaves.table.column_end_date') }}</label>
                    <input id="end_date" class="form-control" name="end_date" type="text" data-bs-toggle="datepicker"
                        value="{{ $endDate }}">
                </div>
                <div class="md:col-1">
                    <button class="btn btn-solid theme-secondary w-100" type="submit"
                        title="{{ __('common.actions.filter') }}">
                        <i data-lucide="search"></i>
                    </button>
                </div>
            </form>

            <div class="table-responsive rounded-5 overflow-hidden border">
                <table class="table-hover table-striped table align-middle">
                    <thead class="bg-1">
                        <tr>
                            <th scope="col">{{ __('leaves.table.column_reference_number') }}</th>
                            <th scope="col">{{ __('leaves.table.column_employee') }}</th>
                            <th scope="col">{{ __('leaves.table.column_start_date') }}</th>
                            <th scope="col">{{ __('leaves.table.column_end_date') }}</th>
                            <th scope="col">{{ __('leaves.table.column_days') }}</th>
                            <th scope="col">{{ __('leaves.table.column_status') }}</th>
                            <th scope="col" class="text-end">{{ __('common.labels.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($leaves as $leave)
                            <tr>
                                <td><span class="fw-bold">{{ $leave->reference_number }}</span></td>
                                <td>
                                    <div class="fw-bold">{{ $leave->employee->name }}</div>
                                    <div class="fg-secondary small">{{ $leave->employee->role->name }}</div>
                                </td>
                                <td>{{ $leave->start_date->format('d M Y') }}</td>
                                <td>{{ $leave->end_date->format('d M Y') }}</td>
                                <td>{{ $leave->total_days }}</td>
                                <td>
                                    <span
                                        class="badge {{ $leave->status->badgeClass() }}">{{ __('leaves.status.' . $leave->status->value) }}</span>
                                    @if ($leave->status === LeaveStatus::REJECTED && $leave->rejection_reason)
                                        <div class="fg-secondary small mt-1" title="{{ $leave->rejection_reason }}">
                                            {{ Str::limit($leave->rejection_reason, 40) }}
                                        </div>
                                    @endif
                                </td>
                                <td class="text-end">
                                    @if (in_array($leave->status, [LeaveStatus::PENDING, LeaveStatus::SUBMITTED], true))
                                        <a class="btn btn-sm btn-solid theme-primary"
                                            href="{{ route('leave.leave-managements.show', $leave) }}">
                                            <i class="me-1" data-lucide="eye"></i>{{ __('common.actions.review') }}
                                        </a>
                                    @else
                                        <a class="btn btn-sm btn-solid theme-light"
                                            href="{{ route('leave.leave-managements.show', $leave) }}">
                                            <i class="me-1" data-lucide="eye"></i>{{ __('common.actions.view') }}
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="fg-secondary py-4 text-center" colspan="7">
                                    {{ __('leaves.admin.no_results') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $leaves->links() }}
        </div>
    </div>
@endsection