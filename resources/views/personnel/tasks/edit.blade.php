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

    $canUpdate = auth()->user()->hasFilePermission(FileResources::TASKS, FileAction::UPDATE);
    $canRemove = auth()->user()->hasFilePermission(FileResources::TASKS, FileAction::REMOVE);
@endphp

@extends('layouts.app')

@section('content')
    <div class="card md:col-8 mx-auto border-0 shadow-sm">
        <div class="card-header bg-1 py-3">
            <h4 class="fw-bold m-0">{{ __('common.actions.edit') }} — {{ $task->reference_number }}</h4>
        </div>

        <div class="card-body">
            @include('partials.errors')

            <form id="taskEditForm" action="{{ route('personnel.tasks.update', $task) }}" method="POST"
                enctype="multipart/form-data" autocomplete="off">
                @csrf
                @method('PUT')

                @include('personnel.tasks._form', ['task' => $task])

                <div class="d-flex justify-content-between border-top mt-4 pt-3">
                    <a class="btn btn-solid theme-secondary" href="{{ route('personnel.tasks.index') }}">
                        <i class="me-1" data-lucide="arrow-left"></i>
                        {{ __('common.actions.back') }}
                    </a>
                    <div class="d-flex gap-1">
                        @if ($canRemove)
                            <button class="btn btn-solid theme-danger" data-bs-toggle="dialog"
                                data-bs-target="#deleteTaskModal" type="button">
                                <i data-lucide="trash-2" class="me-1"></i>
                                {{ __('common.actions.delete') }}
                            </button>
                        @endif
                        @if ($canUpdate)
                            <button class="btn btn-solid theme-primary" type="submit">
                                <i data-lucide="refresh-cw" class="me-1"></i>
                                {{ __('common.actions.save') }}
                            </button>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Delete Task Dialog --}}
    <dialog class="dialog" id="deleteTaskModal">
        <div class="dialog-header">
            <h5 class="dialog-title">{{ __('tasks.delete_title') }}</h5>
            <button class="btn-close" data-bs-dismiss="dialog" type="button"></button>
        </div>
        <form action="{{ route('personnel.tasks.destroy', $task) }}" method="POST">
            @csrf
            @method('DELETE')
            <div class="dialog-body">
                <p>{{ __('tasks.delete_confirm') }}</p>
            </div>
            <div class="dialog-footer">
                <button class="btn btn-solid theme-secondary" data-bs-dismiss="dialog" type="button">
                    {{ __('common.actions.cancel') }}
                </button>
                <button class="btn btn-solid theme-danger" type="submit">
                    {{ __('common.actions.delete') }}
                </button>
            </div>
        </form>
    </dialog>
@endsection