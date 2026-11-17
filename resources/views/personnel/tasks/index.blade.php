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
    use App\Enums\Task\TaskStage;
    use App\Models\Task\Task;
    use Illuminate\Support\Collection;

    $canUpdate = auth()->user()->hasFilePermission(FileResources::TASKS, FileAction::UPDATE);
    $canAdd = auth()->user()->hasFilePermission(FileResources::TASKS, FileAction::ADD);
@endphp

@props([
    /** @var Collection<string, Collection<int, Task>> */
    'tasksByStage',
    'search' => '',
    'priority' => '',
    'assignedTo' => '',
])

@extends('layouts.app')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header d-flex align-items-center justify-content-between bg-1 py-3">
            <h4 class="fw-bold m-0">{{ __('tasks.title') }}</h4>
            <div class="d-flex gap-1">
                @if ($canAdd)
                    <a class="btn btn-solid theme-primary btn-sm" href="{{ route('personnel.tasks.create') }}">
                        <i class="me-1" data-lucide="plus"></i>{{ __('tasks.add_task') }}
                    </a>
                @endif
                <a class="btn btn-solid theme-secondary btn-sm" href="{{ route('personnel.tasks.history') }}">
                    <i class="me-1" data-lucide="history"></i>{{ __('tasks.history') }}
                </a>
            </div>
        </div>

        <div class="card-body">
            @include('partials.alerts')

            <div class="alert bg-1 small border py-2">
                <i data-lucide="info" class="me-1"></i>{{ __('tasks.completed_retention_notice') }}
            </div>

            <form class="row g-2 mb-4" method="GET" action="{{ route('personnel.tasks.index') }}" autocomplete="off">
                <div class="md:col-4">
                    <label class="visually-hidden" for="search">{{ __('common.actions.search') }}</label>
                    <div class="form-control form-adorn">
                        <span class="form-adorn-icon" aria-hidden="true"><i data-lucide="search"></i></span>
                        <input id="search" class="form-ghost" name="search" type="text" value="{{ $search }}"
                            placeholder="{{ __('tasks.search_placeholder') }}">
                    </div>
                </div>
                <div class="md:col-3">
                    <label class="visually-hidden" for="priority">Priority</label>
                    <select id="priority" class="form-control" name="priority">
                        <option value="">{{ __('tasks.all_priorities') }}</option>
                        <option value="low" {{ $priority === 'low' ? 'selected' : '' }}>
                            {{ __('tasks.priority.low') }}
                        </option>
                        <option value="medium" {{ $priority === 'medium' ? 'selected' : '' }}>
                            {{ __('tasks.priority.medium') }}
                        </option>
                        <option value="high" {{ $priority === 'high' ? 'selected' : '' }}>
                            {{ __('tasks.priority.high') }}
                        </option>
                    </select>
                </div>
                <div class="md:col-3">&nbsp;</div>
                <div class="md:col-2 d-flex gap-1">
                    <button class="btn btn-solid theme-secondary flex-grow-1" type="submit">
                        <i data-lucide="search" class="me-1"></i>
                        {{ __('common.actions.filter') }}
                    </button>
                    @if ($search || $priority || $assignedTo)
                        <a class="btn btn-solid theme-secondary" href="{{ route('personnel.tasks.index') }}"
                            title="{{ __('common.actions.reset') }}">
                            <i data-lucide="x"></i>
                        </a>
                    @endif
                </div>
            </form>

            <div class="task-board d-flex gap-3 overflow-auto pb-2">
                @foreach (TaskStage::cases() as $stage)
                    <div class="task-column flex-shrink-0" style="width: 280px">
                        <div class="d-flex align-items-center justify-content-between mb-2 px-1">
                            <span class="fw-bold small text-uppercase">
                                <span class="badge {{ $stage->badgeClass() }} me-1">&nbsp;</span>
                                {{ __('tasks.stage.' . $stage->value) }}
                            </span>
                            <span class="badge theme-light fg-body border">
                                {{ $tasksByStage[$stage->value]->count() }}
                            </span>
                        </div>

                        <div class="task-column-drop bg-1 rounded-5 p-2" data-stage="{{ $stage->value }}"
                            style="min-height: 400px">
                            @foreach ($tasksByStage[$stage->value] as $task)
                                <div class="card task-card mb-2 shadow-sm" data-task-id="{{ $task->id }}"
                                    data-stage-url="{{ route('personnel.tasks.update-stage', $task) }}"
                                    draggable="{{ $canUpdate && !$task->stage->isClosed() ? 'true' : 'false' }}">
                                    <div class="card-body p-2">
                                        <div class="d-flex justify-content-between align-items-start mb-1">
                                            <span class="badge {{ $task->priority->badgeClass() }}">
                                                {{ __('tasks.priority.' . $task->priority->value) }}
                                            </span>
                                            <small class="fg-secondary">{{ $task->reference_number }}</small>
                                        </div>

                                        <div class="fw-bold small mb-1">{{ $task->title }}</div>

                                        <div class="d-flex align-items-center justify-content-between mt-2">
                                            <small class="fg-secondary">
                                                @if ($task->due_date)
                                                    <i data-lucide="calendar"
                                                        class="me-1"></i>{{ $task->due_date->format('d M') }}
                                                @endif
                                            </small>
                                            <div>
                                                <a class="btn btn-sm btn-solid theme-info me-1"
                                                    href="{{ route('personnel.tasks.edit', $task) }}"
                                                    title="{{ __('tasks.view_task') }}">
                                                    <i data-lucide="eye"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Completion Note Dialog --}}
    <dialog class="dialog" id="completionNoteModal">
        <form id="completionNoteForm">
            <div class="dialog-header">
                <h5 class="dialog-title">{{ __('tasks.completion_note') }}</h5>
                <button class="btn-close" data-bs-dismiss="dialog" type="button"></button>
            </div>
            <div class="dialog-body">
                <label class="visually-hidden" for="completionNoteInput">{{ __('tasks.completion_note') }}</label>
                <textarea class="form-control" id="completionNoteInput" rows="3"
                    placeholder="{{ __('tasks.completion_note_placeholder') }}"></textarea>
                <div class="invalid-feedback d-block" id="completionNoteError" style="display: none !important;"></div>
            </div>
            <div class="dialog-footer">
                <button class="btn btn-solid theme-secondary" data-bs-dismiss="dialog" type="button">
                    {{ __('common.actions.cancel') }}
                </button>
                <button class="btn btn-solid theme-primary" type="submit">
                    {{ __('common.actions.save') }}
                </button>
            </div>
        </form>
    </dialog>

    {{-- Error Dialog --}}
    <dialog class="dialog" id="errorModal">
        <div class="dialog-header">
            <h5 class="dialog-title" id="errorModalTitle"></h5>
            <button class="btn-close" data-bs-dismiss="dialog" type="button"></button>
        </div>
        <div class="dialog-body">
            <p id="errorModalText"></p>
        </div>
        <div class="dialog-footer">
            <button class="btn btn-solid theme-primary" data-bs-dismiss="dialog" type="button">OK</button>
        </div>
    </dialog>
@endsection

@section('script')
    <script type="module">
        // Drag-and-drop stage updates
        const columns = document.querySelectorAll('.task-column-drop');
        let draggedCard = null;

        const completionNoteModal = document.getElementById('completionNoteModal');
        const completionNoteForm = document.getElementById('completionNoteForm');
        const completionNoteInput = document.getElementById('completionNoteInput');
        const completionNoteError = document.getElementById('completionNoteError');
        const errorModal = document.getElementById('errorModal');

        function showError(title, message) {
            document.getElementById('errorModalTitle').textContent = title;
            document.getElementById('errorModalText').textContent = message;
            bootstrap.Dialog.getOrCreateInstance(errorModal).show();
        }

        function showCompletionNoteDialog() {
            return new Promise((resolve) => {
                completionNoteInput.value = '';
                completionNoteError.style.display = 'none';

                const dialogInstance = bootstrap.Dialog.getOrCreateInstance(completionNoteModal);
                dialogInstance.show();

                const handleSubmit = (e) => {
                    e.preventDefault();
                    const value = completionNoteInput.value.trim();
                    if (!value) {
                        completionNoteError.textContent = @json(__('validation.required', ['attribute' => __('tasks.completion_note')]));
                        completionNoteError.style.display = 'block';
                        return;
                    }
                    completionNoteForm.removeEventListener('submit', handleSubmit);
                    completionNoteModal.removeEventListener('hide.bs.dialog', handleHide);
                    resolve({
                        confirmed: true,
                        value: value
                    });
                    dialogInstance.hide();
                };

                const handleHide = () => {
                    completionNoteForm.removeEventListener('submit', handleSubmit);
                    completionNoteModal.removeEventListener('hide.bs.dialog', handleHide);
                    resolve({
                        confirmed: false,
                        value: null
                    });
                };

                completionNoteForm.addEventListener('submit', handleSubmit);
                completionNoteModal.addEventListener('hide.bs.dialog', handleHide);
            });
        }

        document.querySelectorAll(".task-card[draggable='true']").forEach((card) => {
            card.addEventListener('dragstart', () => {
                draggedCard = card;
                card.classList.add('opacity-50');
            });
            card.addEventListener('dragend', () => {
                card.classList.remove('opacity-50');
                draggedCard = null;
            });
        });

        columns.forEach((column) => {
            column.addEventListener('dragover', (e) => e.preventDefault());

            column.addEventListener('drop', async (e) => {
                e.preventDefault();
                if (!draggedCard) return;

                const newStage = column.dataset.stage;
                const position = column.querySelectorAll('.task-card').length;
                let completionNote = null;

                if (['success', 'failure'].includes(newStage)) {
                    const result = await showCompletionNoteDialog();

                    if (!result.confirmed) {
                        window.location.reload();
                        return;
                    }
                    completionNote = result.value;
                }

                column.appendChild(draggedCard);

                try {
                    const response = await fetch(draggedCard.dataset.stageUrl, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .content,
                            Accept: 'application/json',
                        },
                        body: JSON.stringify({
                            stage: newStage,
                            position,
                            completion_note: completionNote,
                        }),
                    });

                    if (!response.ok) {
                        throw new Error('Failed to update stage');
                    }
                } catch (error) {
                    showError(
                        @json(__('tasks.stage_update_failed')),
                        error.message
                    );
                    window.location.reload();
                }
            });
        });
    </script>
@endsection
