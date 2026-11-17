@php
    use App\Enums\FileAction;
    use App\Enums\FileResources;
    use App\Models\Leave\Holiday;

    $readOnly = $readOnly ?? false;
    $holidayIndexRoute = $holidayIndexRoute ?? FileResources::HOLIDAYS->value;
    $canCreate = !$readOnly && auth()->user()->hasFilePermission(FileResources::HOLIDAYS, FileAction::ADD);
    $canUpdate = !$readOnly && auth()->user()->hasFilePermission(FileResources::HOLIDAYS, FileAction::UPDATE);
    $canRemove = !$readOnly && auth()->user()->hasFilePermission(FileResources::HOLIDAYS, FileAction::REMOVE);

    $selectedYear = (int) ($year ?? now()->year);

    $holidayMap = $holidays->keyBy(function ($holiday) {
        return $holiday->date->format('Y-m-d');
    });

    $publicCount = $holidays->where('type.value', 'public')->count();
    $stateCount = $holidays->where('type.value', 'state')->count();

    $upcomingCount = $holidays->filter(fn($holiday) => $holiday->date->gte(today()))->count();
@endphp
@props([
    /** @var Holiday */
    '$holidays',
])
@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">
                    <i class="fg-primary me-2" data-lucide="calendar-days"></i>

                    {{ __('holidays.title') }}
                </h3>

                <div class="fg-secondary">{{ __('holidays.dashboard_subtitle') }}</div>
            </div>

            <div class="d-flex gap-2">
                <div class="btn-group">
                    <a class="btn btn-solid theme-secondary"
                        href="{{ route($holidayIndexRoute, ['year' => $selectedYear - 1]) }}">
                        <i data-lucide="chevron-left"></i>
                    </a>

                    <button class="btn btn-solid theme-light fw-bold">{{ $selectedYear }}</button>

                    <a class="btn btn-solid theme-secondary"
                        href="{{ route($holidayIndexRoute, ['year' => $selectedYear + 1]) }}">
                        <i data-lucide="chevron-right"></i>
                    </a>
                </div>

                @if ($canCreate)
                    <a class="btn btn-solid theme-primary btn-sm" href="{{ route('leave.holidays.create') }}">
                        <i class="me-1" data-lucide="plus"></i>
                        {{ __('common.actions.add') }}
                    </a>
                @endif
            </div>
        </div>

        {{-- Stats --}}
        @include('leave.holidays.components.stats')

        {{-- Calendar + Upcoming --}}
        <div class="row mt-4">
            <div class="lg:col-9">
                @include('leave.holidays.components.legend')

                @include('leave.holidays.components.calendar')
            </div>

            <div class="lg:col-3">
                @include('leave.holidays.components.upcoming')
            </div>
        </div>

        {{-- Holiday and staff leave grids --}}
        <div class="mt-4">
            <ul class="nav nav-tabs" id="holidayLeaveTabs">
                <li class="nav-item">
                    <button class="nav-link active" id="holidays-tab" data-bs-toggle="tab" data-bs-target="#holidays-pane"
                        type="button" role="tab" aria-controls="holidays-pane" aria-selected="true">
                        <i class="me-1" data-lucide="calendar-days"></i>{{ __('holidays.tabs.holidays') }}
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="people-on-leave-tab" data-bs-toggle="tab"
                        data-bs-target="#people-on-leave-pane" type="button" role="tab"
                        aria-controls="people-on-leave-pane" aria-selected="false">
                        <i class="me-1" data-lucide="users"></i>{{ __('holidays.tabs.people_on_leave') }}
                    </button>
                </li>
            </ul>
            <div class="tab-content pt-3" id="holidayLeaveTabsContent">
                <div class="tab-pane fade show active" id="holidays-pane" role="tabpanel" aria-labelledby="holidays-tab"
                    tabindex="0">
                    @include('leave.holidays.components.table')
                </div>
                <div class="tab-pane fade" id="people-on-leave-pane" role="tabpanel" aria-labelledby="people-on-leave-tab"
                    tabindex="0">
                    @include('leave.holidays.components.people-on-leave')
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Confirmation Dialog --}}
    <dialog class="dialog" id="deleteConfirmModal">
        <form method="POST" id="deleteForm">
            @csrf
            @method('DELETE')
            <div class="dialog-header">
                <h5 class="dialog-title">{{ __('common.alerts.confirm_delete') }}</h5>
                <button class="btn-close" data-bs-dismiss="dialog" type="button"></button>
            </div>
            <div class="dialog-body">
                <p>{{ __('common.alerts.confirm_delete_message') }}</p>
            </div>
            <div class="dialog-footer">
                <button class="btn btn-solid theme-secondary" data-bs-dismiss="dialog" type="button">
                    {{ __('common.actions.cancel') }}
                </button>
                <button class="btn btn-solid theme-danger" type="submit">
                    {{ __('common.alerts.confirm_delete_yes') }}
                </button>
            </div>
        </form>
    </dialog>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function(el) {
                new bootstrap.Tooltip(el);
            });
        });

        function scrollToRow(id) {
            const row = document.getElementById('holiday-row-' + id);

            if (!row) return;

            row.scrollIntoView({
                behavior: 'smooth',

                block: 'center',
            });

            row.classList.add('table-active-highlight');

            setTimeout(function() {
                row.classList.remove('table-active-highlight');
            }, 2000);
        }

        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-delete');

            if (!btn) return;

            const id = btn.dataset.id;
            document.getElementById('deleteForm').action = "{{ route('leave.holidays.destroy', '__ID__') }}"
                .replace('__ID__', id);
            bootstrap.Dialog.getOrCreateInstance(document.getElementById('deleteConfirmModal')).show();
        });
    </script>
@endsection
