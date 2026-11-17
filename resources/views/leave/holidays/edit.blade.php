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
    <div class="card md:col-6 mx-auto border-0 shadow-sm">
        <div class="card-header bg-1 py-3">
            <h4 class="fw-bold m-0">
                <i class="me-2" data-lucide="square-pen"></i>
                {{ __('common.actions.edit') }}
            </h4>
        </div>
        <div class="card-body">
            @include('partials.errors')

            <form action="{{ route('leave.holidays.update', $holiday) }}" method="POST" autocomplete="off">
                @csrf
                @method('PUT')

                @include('leave.holidays._form', ['holiday' => $holiday])

                <div class="d-flex justify-content-between border-top mt-4 pt-3">
                    <a class="btn btn-solid theme-secondary btn-sm" href="{{ route('leave.holidays.index') }}">
                        <i class="me-1" data-lucide="arrow-left"></i>{{ __('common.actions.back') }}
                    </a>
                    <button class="btn btn-solid theme-primary btn-sm" type="submit">
                        <i class="me-1" data-lucide="save"></i>{{ __('common.actions.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
