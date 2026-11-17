@extends('layouts.app')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header d-flex align-items-center justify-content-between bg-1 py-3">
            <div>
                <h4 class="fw-bold mb-1">{{ __('attendance.management_title') }}</h4>
                <p class="fg-secondary small mb-0">{{ __('attendance.management_subtitle') }}</p>
            </div>
        </div>

        <div class="card-body">
            @include('partials.alerts')

            <form class="row g-2 mb-4" method="GET" action="{{ route('attendance.attendance-managements.index') }}"
                autocomplete="off">
                <div class="md:col-3">
                    <label class="visually-hidden" for="search">{{ __('common.actions.search') }}</label>
                    <div class="form-control form-adorn">
                        <span class="form-adorn-icon" aria-hidden="true"><i data-lucide="search"></i></span>
                        <input id="search" class="form-ghost" name="search" type="text" value="{{ $search }}"
                            placeholder="{{ __('attendance.search_placeholder') }}">
                    </div>
                </div>
                <div class="md:col-2">
                    <label class="visually-hidden" for="department">{{ __('attendance.department') }}</label>
                    <select id="department" class="form-control" name="department">
                        <option value="">{{ __('attendance.all_departments') }}</option>
                        @foreach ($departments as $id => $name)
                            <option value="{{ $id }}" {{ $department === (string) $id ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-2">
                    <label class="visually-hidden" for="status">{{ __('attendance.status') }}</label>
                    <select id="status" class="form-control" name="status">
                        <option value="">{{ __('attendance.all_statuses') }}</option>
                        <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>
                            {{ __('attendance.completed') }}
                        </option>
                        <option value="in_progress" {{ $status === 'in_progress' ? 'selected' : '' }}>
                            {{ __('attendance.in_progress') }}
                        </option>
                        <option value="missing" {{ $status === 'missing' ? 'selected' : '' }}>
                            {{ __('attendance.not_clocked_in') }}
                        </option>
                    </select>
                </div>
                <div class="md:col-2">
                    <label class="visually-hidden" for="start_date">{{ __('attendance.date') }}</label>
                    <input id="start_date" class="form-control" name="start_date" type="text"
                        data-bs-toggle="datepicker" value="{{ $startDate }}"
                        placeholder="{{ __('attendance.date') }}">
                </div>
                <div class="md:col-2">
                    <label class="visually-hidden" for="end_date">{{ __('attendance.date') }}</label>
                    <input id="end_date" class="form-control" name="end_date" type="text"
                        data-bs-toggle="datepicker" value="{{ $endDate }}"
                        placeholder="{{ __('attendance.date') }}">
                </div>
                <div class="md:col-1">
                    <button class="btn btn-solid theme-secondary w-100" type="submit"
                        title="{{ __('common.actions.filter') }}">
                        <i data-lucide="search"></i>
                    </button>
                </div>
            </form>

            <div class="table-responsive rounded-5 overflow-hidden border">
                <table class="table-hover table-striped table align-middle">
                    <thead class="bg-1">
                        <tr>
                            <th scope="col">{{ __('attendance.employee') }}</th>
                            <th scope="col">{{ __('attendance.date') }}</th>
                            <th scope="col">{{ __('attendance.clock_in') }}</th>
                            <th scope="col">{{ __('attendance.clock_out') }}</th>
                            <th scope="col">{{ __('attendance.worked_hours') }}</th>
                            <th scope="col">{{ __('attendance.status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($attendances as $attendance)
                            @php
                                $hours = intdiv($attendance->worked_minutes, 60);
                                $minutes = $attendance->worked_minutes % 60;
                                $statusBadge = $attendance->clock_in === null
                                    ? 'theme-warning'
                                    : ($attendance->clock_out === null ? 'theme-info' : 'theme-success');
                                $statusLabel = $attendance->clock_in === null
                                    ? __('attendance.not_clocked_in')
                                    : ($attendance->clock_out === null
                                        ? __('attendance.in_progress')
                                        : __('attendance.completed'));
                            @endphp
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ $attendance->user?->name ?? '—' }}</div>
                                    <div class="fg-secondary small">
                                        {{ $attendance->user?->department?->name ?? $attendance->user?->email ?? '' }}
                                    </div>
                                </td>
                                <td>{{ $attendance->work_date?->format('d M Y') }}</td>
                                <td>{{ $attendance->clock_in?->format('H:i') ?? '—' }}</td>
                                <td>{{ $attendance->clock_out?->format('H:i') ?? '—' }}</td>
                                <td class="fw-semibold">{{ $hours }}h {{ str_pad((string) $minutes, 2, '0', STR_PAD_LEFT) }}m</td>
                                <td><span class="badge {{ $statusBadge }}">{{ $statusLabel }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center fg-secondary py-5" colspan="6">
                                    {{ __('attendance.no_records') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $attendances->links() }}</div>
        </div>
    </div>
@endsection
