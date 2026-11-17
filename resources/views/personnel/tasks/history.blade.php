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
    use App\Enums\Task\TaskStage;
@endphp

@extends('layouts.app')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header d-flex align-items-center justify-content-between bg-1 py-3">
            <div>
                <h4 class="fw-bold mb-1">{{ __('tasks.history') }}</h4>
                <small class="fg-secondary">{{ __('tasks.history_description') }}</small>
            </div>
            <a class="btn btn-solid theme-secondary btn-sm" href="{{ route('personnel.tasks.index') }}">
                <i class="me-1" data-lucide="arrow-left"></i>{{ __('tasks.active_tasks') }}
            </a>
        </div>

        <div class="card-body">
            @include('partials.alerts')

            <form class="row g-2 mb-4" method="GET" action="{{ route('personnel.tasks.history') }}" autocomplete="off">
                <div class="lg:col-4">
                    <label class="visually-hidden" for="search">{{ __('common.actions.search') }}</label>
                    <input id="search" class="form-control" name="search" type="text" value="{{ $search }}"
                        placeholder="{{ __('tasks.search_placeholder') }}">
                </div>
                <div class="lg:col-3">
                    <label class="visually-hidden" for="priority">Priority</label>
                    <select id="priority" class="form-control" name="priority">
                        <option value="">{{ __('tasks.all_priorities') }}</option>
                        @foreach (['low', 'medium', 'high'] as $priorityOption)
                            <option value="{{ $priorityOption }}" @selected($priority === $priorityOption)>
                                {{ __('tasks.priority.' . $priorityOption) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="lg:col-3">
                    <label class="visually-hidden" for="status">Status</label>
                    <select id="status" class="form-control" name="status">
                        <option value="">{{ __('tasks.all_completion_statuses') }}</option>
                        <option value="{{ TaskStage::SUCCESS->value }}" @selected($status === TaskStage::SUCCESS->value)>
                            {{ __('tasks.stage.success') }}
                        </option>
                        <option value="{{ TaskStage::FAILURE->value }}" @selected($status === TaskStage::FAILURE->value)>
                            {{ __('tasks.stage.failure') }}
                        </option>
                    </select>
                </div>
                <div class="lg:col-2 d-flex gap-1">
                    <button class="btn btn-solid theme-secondary flex-grow-1" type="submit">
                        <i data-lucide="search" class="me-1"></i>{{ __('common.actions.filter') }}
                    </button>
                    @if ($search || $priority || $status)
                        <a class="btn btn-solid theme-secondary" href="{{ route('personnel.tasks.history') }}"
                            title="{{ __('common.actions.reset') }}">
                            <i data-lucide="x"></i>
                        </a>
                    @endif
                </div>
            </form>

            <div class="table-responsive rounded-5 overflow-hidden border">
                <table class="table-hover table-striped mb-0 table align-middle">
                    <thead class="bg-1">
                        <tr>
                            <th scope="col">{{ __('tasks.table.column_reference_number') }}</th>
                            <th scope="col">{{ __('tasks.table.column_title') }}</th>
                            <th scope="col">{{ __('tasks.table.column_priority') }}</th>
                            <th scope="col">{{ __('tasks.table.column_stage') }}</th>
                            <th scope="col">{{ __('tasks.completed_date') }}</th>
                            <th scope="col" class="text-end">{{ __('tasks.table.column_actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tasks as $task)
                            <tr>
                                <td>{{ $task->reference_number }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $task->title }}</div>
                                </td>
                                <td>
                                    <span class="badge {{ $task->priority->badgeClass() }}">
                                        {{ __('tasks.priority.' . $task->priority->value) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $task->stage->badgeClass() }}">
                                        {{ __('tasks.stage.' . $task->stage->value) }}
                                    </span>
                                </td>
                                <td>{{ ($task->closed_at ?? $task->updated_at)->format('d/m/Y H:i') }}</td>
                                <td class="text-end">
                                    <a class="btn btn-sm btn-solid theme-info me-1"
                                        href="{{ route('personnel.tasks.edit', $task) }}"
                                        title="{{ __('tasks.view_task') }}">
                                        <i data-lucide="eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="fg-secondary py-4 text-center" colspan="6">{{ __('tasks.history_empty') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">{{ $tasks->links() }}</div>
        </div>
    </div>
@endsection
