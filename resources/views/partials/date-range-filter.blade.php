{{--

/**
 * ================================<label class="visually-hidden" for="selectName">{{ $SelectName }}</label>
 ============================================
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
--}}

@php
    $placeholder = $placeholder ?? __('common.actions.search');
    // Optional extra filter, supplied only by pages that pass it. When absent the
    // grid below renders exactly as it did before this slot existed.
    $selectName = $selectName ?? null;
    $hasSelect = $selectName !== null;
    $searchColumn = $hasSelect ? 'md:col-3' : 'md:col-4';
    $dateColumn = $hasSelect ? 'md:col-2' : 'md:col-3';
    $actionColumn = $hasSelect ? 'md:col-3' : 'md:col-2';
@endphp

@php
    $hasActiveFilter = filled($search) || filled($startDate) || filled($endDate);
@endphp

<div class="accordion mb-4 accordion-gap">
    <details class="accordion-item" @if ($hasActiveFilter) open @endif>
        <summary class="accordion-header">
            <i class="me-2" data-lucide="list-filter"></i>
            <span class="fw-bold">{{ __('financial_reports.form.filters') }}</span>
            <i class="accordion-icon" data-lucide="chevron-down"></i>
        </summary>
        <div class="accordion-body">
            <form autocomplete="off" action="{{ $action }}" method="GET">
                <div class="row g-2 align-items-center">
                    <label class="visually-hidden" for="search">{{ __('common.actions.search') }}</label>
                    <div class="{{ $searchColumn }}">
                        <input id="search" class="form-control" name="search" type="text"
                            value="{{ $search }}" placeholder="{{ $placeholder }}">
                    </div>
                    <div class="{{ $dateColumn }}">
                        <label class="visually-hidden"
                            for="start_date">{{ __('financial_reports.form.start_date') }}</label>
                        <div class="form-control form-adorn">
                            <div class="form-adorn-icon" aria-hidden="true">
                                <i data-lucide="calendar"></i>
                            </div>
                            <input id="start_date" class="form-ghost" name="start_date" type="text"
                                data-bs-toggle="datepicker" autocomplete="off" value="{{ $startDate }}"
                                placeholder="{{ __('financial_reports.form.start_date') }}">
                        </div>
                    </div>
                    <div class="{{ $dateColumn }}">
                        <label class="visually-hidden"
                            for="end_date">{{ __('financial_reports.form.end_date') }}</label>
                        <div class="form-control form-adorn">
                            <div class="form-adorn-icon" aria-hidden="true">
                                <i data-lucide="calendar"></i>
                            </div>
                            <input id="end_date" class="form-ghost" name="end_date" type="text"
                                data-bs-toggle="datepicker" autocomplete="off" value="{{ $endDate }}"
                                placeholder="{{ __('financial_reports.form.end_date') }}">
                        </div>
                    </div>
                    @if ($hasSelect)
                        <div class="{{ $dateColumn }}">
                            <select id="selectName" class="form-control" name="{{ $selectName }}">
                                @foreach ($selectOptions as $selectValue => $selectText)
                                    <option value="{{ $selectValue }}" @selected((string) ($selectCurrent ?? '') === (string) $selectValue)>
                                        {{ $selectText }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    <div class="{{ $actionColumn }} d-flex gap-2">
                        <button class="btn btn-solid theme-primary flex-fill" type="submit">
                            <i class="me-1" data-lucide="search"></i>{{ __('common.actions.search') }}
                        </button>
                        <a class="btn btn-solid theme-secondary" href="{{ $action }}">
                            <i class="me-1" data-lucide="rotate-ccw"></i>{{ __('common.actions.reset') }}
                        </a>
                    </div>
                </div>
            </form>
        </div>

    </details>
</div>
