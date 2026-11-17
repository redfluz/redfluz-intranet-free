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

@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="lg:col-8">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h3 class="fw-bold mb-0">{{ __('claims.edit_title') }}</h3>
                    <a class="btn btn-solid theme-secondary btn-sm" href="{{ route('personnel.claims.index') }}">
                        {{ __('common.actions.back') }}
                    </a>
                </div>

                @include('partials.alerts')

                @if ($errors->any())
                    <div class="alert theme-danger" role="alert">
                        <div class="fw-semibold mb-1">{{ __('claims.create.validation_errors') }}</div>
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <form id="claim-form" action="{{ route('personnel.claims.update', $claim) }}" method="POST"
                            enctype="multipart/form-data" autocomplete="off">
                            @csrf
                            @method('PUT')

                            <div class="row g-3 mb-3">
                                <div class="md:col-6">
                                    <label class="form-label small fw-medium"
                                        for="receipt_date">{{ __('claims.table.column_receipt_date') }}
                                        <span class="fg-danger">*</span></label>
                                    <div class="form-control form-adorn">
                                        <div class="form-adorn-icon" aria-hidden="true">
                                            <i data-lucide="calendar"></i>
                                        </div>
                                        <input class="form-ghost @error('receipt_date') is-invalid @enderror"
                                            id="receipt_date" name="receipt_date" type="text"
                                            data-bs-toggle="datepicker" autocomplete="off"
                                            value="{{ old('receipt_date', $claim->receipt_date->format('Y-m-d')) }}"
                                            required />
                                    </div>
                                    @error('receipt_date')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="md:col-6">
                                    <label class="form-label small fw-medium"
                                        for="receipt_no">{{ __('claims.table.column_receipt_no') }}</label>
                                    <input class="form-control @error('receipt_no') is-invalid @enderror" id="receipt_no"
                                        name="receipt_no" type="text" value="{{ old('receipt_no', $claim->receipt_no) }}" />
                                    @error('receipt_no')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-medium" for="amount">{{ __('claims.table.column_amount') }}
                                    <span class="fg-danger">*</span></label>
                                <div class="form-control form-adorn">
                                    <span>{{ __('claims.currency') }}</span>
                                    <input class="form-ghost @error('amount') is-invalid @enderror" id="amount"
                                        name="amount" type="number" value="{{ old('amount', $claim->amount) }}"
                                        step="0.01" min="0" required />
                                </div>
                                @error('amount')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-medium"
                                    for="description">{{ __('claims.table.column_description') }}</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                    rows="3" placeholder="{{ __('claims.placeholders.description') }}">{{ old('description', $claim->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label small fw-medium"
                                    for="attachment">{{ __('claims.table.column_attachment') }}</label>
                                <input class="form-control @error('attachment') is-invalid @enderror" id="attachment"
                                    name="attachment" type="file" accept=".pdf,.jpg,.jpeg,.png" />
                                @if ($claim->attachment_path)
                                    <div class="form-text">
                                        {{ __('claims.wizard.current_file') }}:
                                        <a class="fg-primary text-decoration-underline"
                                            data-fancybox="claim-attachment"
                                            data-type="{{ Str::endsWith(strtolower($claim->attachment_path), '.pdf') ? 'iframe' : 'image' }}"
                                            href="{{ Storage::url($claim->attachment_path) }}">
                                            {{ __('claims.wizard.view_attachment') }}
                                        </a>
                                    </div>
                                @else
                                    <div class="form-text">{{ __('claims.wizard.accepted_formats') }}</div>
                                @endif
                                @error('attachment')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-end gap-2 pt-2">
                                <a class="btn btn-solid theme-light"
                                    href="{{ route('personnel.claims.index') }}">{{ __('common.actions.cancel') }}</a>
                                <button class="btn btn-solid theme-secondary px-4" name="action" type="submit"
                                    value="draft">
                                    {{ __('claims.wizard.save_draft') }}
                                </button>
                                <button class="btn btn-solid theme-primary px-4" name="action" type="submit"
                                    value="submit">
                                    {{ __('claims.wizard.update_submit') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection