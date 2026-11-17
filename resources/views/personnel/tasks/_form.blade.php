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
    use App\Enums\Task\TaskPriority;
    use App\Enums\Task\TaskStage;

    $task = $task ?? null;
@endphp

<ul class="nav nav-tabs mb-4" id="taskFormTabs">
    <li class="nav-item">
        <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general-pane"
            type="button" role="tab" aria-controls="general-pane" aria-selected="true">
            <i data-lucide="list" class="me-1"></i>
            {{ __('tasks.general') }}
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link" id="attachments-tab" data-bs-toggle="tab" data-bs-target="#attachments-pane"
            type="button" role="tab" aria-controls="attachments-pane" aria-selected="false">
            <i data-lucide="paperclip" class="me-1"></i>
            {{ __('tasks.attachments') }}
        </button>
    </li>
</ul>

<div class="tab-content" id="taskTabContent">
    {{-- TAB 1: General --}}
    <div class="tab-pane fade show active" id="general-pane" role="tabpanel" aria-labelledby="general-tab">
        <div class="mb-3">
            <label class="form-label fw-bold" for="title">
                {{ __('tasks.table.column_title') }}
            </label><input class="form-control @error('title') is-invalid @enderror" id="title"
                name="title" type="text" value="{{ old('title', $task?->title) }}" required />
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold" for="completion_note">
                {{ __('tasks.completion_note') }}
            </label>
            <textarea class="form-control @error('completion_note') is-invalid @enderror" id="completion_note"
                name="completion_note" rows="3" placeholder="{{ __('tasks.completion_note_placeholder') }}">{{ old('completion_note') }}</textarea>
            <div class="form-text">{{ __('tasks.completion_note_help') }}</div>
            @error('completion_note')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="row">
            <div class="md:col-6 mb-3">
                <label class="form-label fw-bold" for="stage">
                    {{ __('tasks.table.column_stage') }}
                </label>
                <select class="form-control @error('stage') is-invalid @enderror" id="stage" name="stage" required>
                    @foreach (TaskStage::cases() as $stage)
                        <option value="{{ $stage->value }}"
                            {{ old('stage', $task?->stage->value ?? TaskStage::INTRODUCTION->value) === $stage->value ? 'selected' : '' }}>
                            {{ __('tasks.stage.' . $stage->value) }}
                        </option>
                    @endforeach
                </select>
                @error('stage')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="md:col-6 mb-3">
                <label class="form-label fw-bold" for="priority">
                    {{ __('tasks.table.column_priority') }}
                </label>
                <select class="form-control @error('priority') is-invalid @enderror" id="priority" name="priority"
                    required>
                    @foreach (TaskPriority::cases() as $priorityOption)
                        <option value="{{ $priorityOption->value }}"
                            {{ old('priority', $task?->priority->value ?? TaskPriority::MEDIUM->value) === $priorityOption->value ? 'selected' : '' }}>
                            {{ __('tasks.priority.' . $priorityOption->value) }}
                        </option>
                    @endforeach
                </select>
                @error('priority')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold" for="due_date">
                {{ __('tasks.table.column_due_date') }}
            </label><input class="form-control @error('due_date') is-invalid @enderror" id="due_date" name="due_date"
                type="text" data-bs-toggle="datepicker" autocomplete="off"
                value="{{ old('due_date', $task?->due_date?->format('Y-m-d')) }}" />
            @error('due_date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- WYSIWYG Notes Field --}}
        <div class="mb-3">
            <label class="form-label fw-bold" for="notes">
                {{ __('tasks.instructions') }}
            </label>

            {{-- Hidden field sent to Laravel backend --}}
            <input id="notes" name="notes" type="hidden" value="{{ old('notes', $task?->notes) }}" />

            {{-- Quill container --}}
            <div id="wysiwyg-editor" style="height: 150px">{!! old('notes', $task?->notes) !!}</div>

            @error('notes')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>

    {{-- TAB 2: Task Attachments (Max 5 rows) --}}
    <div class="tab-pane fade" id="attachments-pane" role="tabpanel" aria-labelledby="attachments-tab">
        <div class="alert theme-info small py-2">
            <i data-lucide="info" class="me-1"></i>
            Allowed formats: <strong>Images (JPG, PNG, WEBP)</strong> and
            <strong>Documents (PDF, DOC, DOCX, XLS, XLSX, TXT)</strong>. Maximum 5 attachments total.
        </div>

        {{-- Existing Saved Attachments --}}
        @if ($task && $task->attachments->count() > 0)
            <div class="table-responsive mb-3">
                <table class="table-bordered table-sm table align-middle">
                    <thead class="bg-1">
                        <tr>
                            <th scope="col">File Name</th>
                            <th scope="col" style="width: 120px">Size</th>
                            <th scope="col" class="text-center" style="width: 100px">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($task->attachments as $attachment)
                            <tr id="attachment-row-{{ $attachment->id }}">
                                <td>
                                    @if (Str::endsWith(strtolower($attachment->file_path), ['.jpg', '.jpeg', '.png', '.gif', '.webp']))
                                        <a class="d-inline-flex align-items-center gap-2"
                                            data-fancybox="task-attachments"
                                            data-caption="{{ $attachment->original_name }}"
                                            href="{{ asset('storage/' . $attachment->file_path) }}">
                                            <img class="rounded-3 border"
                                                src="{{ asset('storage/' . $attachment->file_path) }}"
                                                alt="{{ $attachment->original_name }}"
                                                style="height: 40px; width: 40px; object-fit: cover; cursor: pointer" />
                                            <span>{{ $attachment->original_name }}</span>
                                        </a>
                                    @elseif (Str::endsWith(strtolower($attachment->file_path), '.pdf'))
                                        <a data-fancybox="task-attachments" data-type="iframe"
                                            data-caption="{{ $attachment->original_name }}"
                                            href="{{ asset('storage/' . $attachment->file_path) }}">
                                            <i data-lucide="paperclip"></i>
                                            {{ $attachment->original_name }}
                                        </a>
                                    @else
                                        <a href="{{ asset('storage/' . $attachment->file_path) }}" target="_blank">
                                            <i data-lucide="paperclip"></i>
                                            {{ $attachment->original_name }}
                                        </a>
                                    @endif
                                </td>
                                <td>{{ round($attachment->file_size / 1024, 2) }} KB</td>
                                <td class="text-center">
                                    <div class="form-check d-inline-block">
                                        <input class="check danger-check" id="del_att_{{ $attachment->id }}"
                                            name="delete_attachments[]" type="checkbox" value="{{ $attachment->id }}" />
                                        <label class="form-check-label fg-danger small"
                                            for="del_att_{{ $attachment->id }}">{{ __('common.actions.delete') }}</label>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        {{-- Dynamic File Upload Inputs --}}
        <div id="attachment-rows-container">
            <div class="attachment-row mb-2">
                <label class="visually-hidden" for="attachment_0">Attachment</label>
                <div class="form-control form-adorn">
                    <input class="form-ghost attachment-input" name="attachments[]" id="attachment_0" type="file"
                        accept=".jpg,.jpeg,.png,.webp,.pdf,.doc,.docx,.xls,.xlsx,.txt">
                    <button class="btn btn-solid theme-danger remove-row-btn d-none" type="button">
                        <i data-lucide="x"></i>
                    </button>
                </div>
            </div>
        </div>

        <button class="btn btn-solid theme-primary btn-sm mt-2" id="add-attachment-btn" type="button">
            <i class="me-1" data-lucide="plus"></i> Add Another File
        </button>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editorContainer = document.getElementById('wysiwyg-editor');
        const hiddenInput = document.getElementById('notes');

        if (editorContainer && hiddenInput && typeof Quill !== 'undefined') {
            const quill = new Quill(editorContainer, {
                theme: 'snow',
            });

            // Keep hidden textarea in sync with Quill editor content
            quill.on('text-change', () => {
                hiddenInput.value = quill.root.innerHTML;
            });
        }

        // Max 5 Dynamic Rows Logic
        const MAX_ROWS = 5;
        const container = document.getElementById('attachment-rows-container');
        const addBtn = document.getElementById('add-attachment-btn');

        function getActiveExistingCount() {
            const existingRows = document.querySelectorAll('[id^="attachment-row-"]');
            const checkedForDelete = document.querySelectorAll('input[name="delete_attachments[]"]:checked');
            return existingRows.length - checkedForDelete.length;
        }

        function updateButtons() {
            const currentRows = container.querySelectorAll('.attachment-row').length;
            const activeExisting = getActiveExistingCount();

            if (currentRows + activeExisting >= MAX_ROWS) {
                addBtn.classList.add('d-none');
            } else {
                addBtn.classList.remove('d-none');
            }

            container.querySelectorAll('.attachment-row').forEach((row) => {
                const removeBtn = row.querySelector('.remove-row-btn');
                if (currentRows > 1) {
                    removeBtn.classList.remove('d-none');
                } else {
                    removeBtn.classList.add('d-none');
                }
            });
        }

        addBtn.addEventListener('click', function() {
            const currentRows = container.querySelectorAll('.attachment-row').length;
            if (currentRows + getActiveExistingCount() >= MAX_ROWS) return;

            const newRow = document.createElement('div');
            newRow.className = 'attachment-row mb-2';
            newRow.innerHTML = `
<label class="visually-hidden" for="attachment_${currentRows}">Attachment</label>
<div class="form-control form-adorn">
<input type="file" name="attachments[]" id="attachment_${currentRows}" class="form-ghost attachment-input"
accept=".jpg,.jpeg,.png,.webp,.pdf,.doc,.docx,.xls,.xlsx,.txt">
<button class="btn btn-solid theme-danger remove-row-btn" type="button">
<i data-lucide="x"></i>
</button>
</div>
`;

            container.appendChild(newRow);
            updateButtons();
        });

        container.addEventListener('click', function(e) {
            if (e.target.closest('.remove-row-btn')) {
                e.target.closest('.attachment-row').remove();
                updateButtons();
            }
        });

        document.querySelectorAll('input[name="delete_attachments[]"]').forEach((cb) => {
            cb.addEventListener('change', updateButtons);
        });

        updateButtons();
    });
</script>