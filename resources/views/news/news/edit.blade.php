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
    use App\Enums\FileAction;
    use App\Enums\FileResources;

    $canUpdate = auth()->user()->hasFilePermission(FileResources::NEWS, FileAction::UPDATE);
@endphp
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="md:col-9">
                <div class="card">
                    <div class="card-header">{{ __('common.actions.edit') }}</div>
                    <div class="card-body">
                        @include('partials.errors')
                        <form autocomplete="off" action="{{ route('news.news.update', $news) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            @include('news.news._form', ['news' => $news])

                            <a class="btn btn-solid theme-secondary" href="{{ route('news.news.index') }}">
                                <i class="me-1" data-lucide="arrow-left"></i>
                                {{ __('common.actions.back') }}
                            </a>
                            @if ($canUpdate)
                                <button class="btn btn-solid theme-primary" type="submit">
                                    <i class="me-1" data-lucide="save"></i>
                                    {{ __('common.actions.save') }}
                                </button>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
