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
                    <h3 class="fw-bold mb-0">{{ __('leaves.dashboard.apply_button') }}</h3>
                    <a class="btn btn-solid theme-secondary btn-sm" href="{{ route('personnel.leaves.index') }}"> Back to List
                    </a>
                </div>

                @include('partials.alerts')

                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <form id="leaveApplicationForm" action="{{ route('personnel.leaves.store') }}" method="POST"
                            enctype="multipart/form-data" autocomplete="off">
                            @csrf

                            @include('personnel.leaves._form')

                            <div class="d-flex justify-content-end gap-2 pt-2">
                                <a class="btn btn-solid theme-light"
                                    href="{{ route('personnel.leaves.index') }}">{{ __('common.actions.cancel') }}</a>
                                <button class="btn btn-solid theme-secondary px-4" name="action" type="submit"
                                    value="draft">
                                    Save Draft
                                </button>
                                <button class="btn btn-solid theme-primary px-4" name="action" type="submit"
                                    value="submit">
                                    {{ __('leaves.dashboard.submit_button') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
