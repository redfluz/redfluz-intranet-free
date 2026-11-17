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

    $canCreate = auth()->user()->hasFilePermission(FileResources::SYSTEM_NOTIFICATIONS, FileAction::ADD);
@endphp
@props([
    /** @var User */
    'users',
    /** @var \App\Models\Management\Department */
    'departments',
    /** @var \App\Models\System\Role */
    'roles',
])
@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="lg:col-8">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="h3 mb-0 text-gray-800">{{ __('common.actions.send') }}</h1>
                        <p class="fg-secondary small mb-0">{{ __('notifications.broadcast') }}</p>
                    </div>
                    <a class="btn btn-solid theme-secondary btn-sm" href="{{ route('system.notifications.index') }}">
                        <i class="me-1" data-lucide="arrow-left"></i>
                        {{ __('common.actions.back') }}
                    </a>
                </div>

                @include('partials.alerts')

                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <form autocomplete="off" action="{{ route('system.notifications.store') }}" method="POST">
                            @csrf

                            {{-- Title --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold" for="title">
                                    {{ __('notifications.title') }}
                                    <span class="fg-danger">*</span>
                                </label>
                                <input class="form-control @error('title') is-invalid @enderror" id="title"
                                    name="title" type="text" value="{{ old('title') }}"
                                    placeholder="e.g. Scheduled System Upgrade" required />
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Recipients Multi-Select --}}
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label class="form-label fw-semibold mb-0" for="users">
                                        {{ __('notifications.select_recipients') }}
                                        <span class="fg-danger">*</span>
                                    </label>
                                    <button class="btn btn-link btn-sm text-decoration-none p-0" id="toggle-select-all"
                                        type="button">
                                        {{ __('common.actions.select_all') }}
                                    </button>
                                </div>

                                {{-- Optional Filters --}}
                                <div class="row g-2 mb-2">
                                    <div class="md:col-6">
                                        <label class="visually-hidden" for="filter-department"> - </label>
                                        <select class="form-control form-control-sm" id="filter-department"
>
                                            <option value="">{{ __('notifications.all_departments') }}</option>
                                            @foreach ($departments as $department)
                                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="md:col-6">
                                        <label class="visually-hidden" for="filter-role"> - </label>
                                        <select class="form-control form-control-sm" id="filter-role"
>
                                            <option value="">{{ __('notifications.all_roles') }}</option>
                                            @foreach ($roles as $role)
                                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <select class="form-control @error('users') is-invalid @enderror" id="users"
                                    name="users[]" multiple data-combobox required>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}" data-department="{{ $user->department_id }}"
                                            data-role="{{ $user->role_id }}"
                                            {{ is_array(old('users')) && in_array($user->id, old('users')) ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('users')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Message --}}
                            <div class="mb-4">
                                <label class="form-label fw-semibold" for="message">
                                    {{ __('notifications.table.column_message') }}
                                    <span class="fg-danger">*</span>
                                </label>
                                <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="4"
                                    placeholder="Write your announcement or notice here..." maxlength="2000" required>{{ old('message') }}</textarea>
                                <div class="d-flex justify-content-end form-text">
                                    <span id="char-count">0</span>/2000 characters
                                </div>
                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Submit Button --}}
                            <div class="d-flex justify-content-end gap-2">
                                <a class="btn btn-solid theme-light" href="{{ route('system.notifications.index') }}">
                                    {{ __('common.actions.cancel') }}
                                </a>
                                @if ($canCreate)
                                    <button class="btn btn-solid theme-primary btn-sm" type="submit">
                                        <i class="me-1" data-lucide="send"></i>
                                        {{ __('common.actions.send') }}
                                    </button>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectEl = document.getElementById('users');
            const toggleBtn = document.getElementById('toggle-select-all');
            const filterDepartment = document.getElementById('filter-department');
            const filterRole = document.getElementById('filter-role');
            let allSelected = false;

            const allUsers = [];
            for (const option of selectEl.options) {
                allUsers.push({
                    value: option.value,
                    text: option.textContent.trim(),
                    department: option.dataset.department || '',
                    role: option.dataset.role || '',
                });
            }

            let combobox = null;

            if (selectEl && typeof window.initCombobox === 'function') {
                combobox = window.initCombobox(selectEl, {
                    placeholder: '{{ __('notifications.select_recipients') }}...',
                });
            }

            function applyFilters() {
                if (!combobox) return;

                const deptId = filterDepartment.value;
                const roleId = filterRole.value;
                const selectedValues = [...selectEl.selectedOptions].map((o) => o.value);

                // Remove all non-placeholder options
                [...selectEl.options].forEach((o) => {
                    if (o.value !== '') o.remove();
                });

                const filtered = allUsers.filter((u) => {
                    const matchesDept = !deptId || u.department === deptId;
                    const matchesRole = !roleId || u.role === roleId;
                    return matchesDept && matchesRole;
                });

                for (const u of filtered) {
                    const opt = document.createElement('option');
                    opt.value = u.value;
                    opt.textContent = u.text;
                    if (selectedValues.includes(u.value)) opt.selected = true;
                    selectEl.append(opt);
                }

                combobox.refresh();
            }

            if (filterDepartment) {
                filterDepartment.addEventListener('change', applyFilters);
            }

            if (filterRole) {
                filterRole.addEventListener('change', applyFilters);
            }

            if (toggleBtn && combobox) {
                toggleBtn.addEventListener('click', function() {
                    allSelected = !allSelected;

                    if (allSelected) {
                        const allValues = [...selectEl.options].map((o) => o.value).filter((v) => v !== '');
                        for (const option of selectEl.options) {
                            option.selected = allValues.includes(option.value);
                        }
                    } else {
                        selectEl.autoComplete.clear();
                    }

                    toggleBtn.textContent = allSelected ? '{{ __('common.actions.deselect_all') }}' :
                        '{{ __('common.actions.select_all') }}';
                });
            }

            const messageInput = document.getElementById('message');
            const charCount = document.getElementById('char-count');

            if (messageInput && charCount) {
                const updateCount = () => (charCount.textContent = messageInput.value.length);
                messageInput.addEventListener('input', updateCount);
                updateCount();
            }
        });
    </script>
@endsection
