@extends('layouts.app')

@section('content')
    <div class="container">
        @include('partials.alerts')

        <div class="card border-0 shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center bg-1 py-3">
                <div>
                    <h5 class="fw-bold mb-0">{{ __('leaves.calendar.title') }}</h5>
                    <p class="fg-secondary small mb-0">{{ __('leaves.calendar.subtitle') }}</p>
                </div>
                <a class="btn btn-solid theme-secondary btn-sm" href="{{ route('personnel.leaves.index') }}">
                    {{ __('common.actions.back') }}
                </a>
            </div>

            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                    <a class="btn btn-solid theme-secondary btn-sm"
                        href="{{ route('personnel.leaves.calendar', ['month' => $month->copy()->subMonth()->format('Y-m')]) }}">
                        {{ __('leaves.calendar.previous_month') }}
                    </a>
                    <form class="d-flex align-items-center gap-2" action="{{ route('personnel.leaves.calendar') }}"
                        method="GET">
                        <label class="visually-hidden" for="month">{{ __('leaves.calendar.month') }}</label>
                        <input id="month" class="form-control form-control-sm" name="month" type="month"
                            value="{{ $month->format('Y-m') }}">
                        <button class="btn btn-solid theme-primary btn-sm"
                            type="submit">{{ __('common.actions.search') }}</button>
                    </form>
                    <a class="btn btn-solid theme-secondary btn-sm"
                        href="{{ route('personnel.leaves.calendar', ['month' => $month->copy()->addMonth()->format('Y-m')]) }}">
                        {{ __('leaves.calendar.next_month') }}
                    </a>
                </div>

                <h4 class="fw-bold mb-4 text-center">{{ $month->translatedFormat('F Y') }}</h4>

                <div class="d-flex justify-content-end mb-3 flex-wrap gap-2">
                    <span class="badge rounded-pill theme-danger"> {{ __('leaves.calendar.holiday_legend') }} </span>
                    <span class="badge rounded-pill theme-primary fg-primary border-primary border">
                        {{ __('leaves.calendar.on_leave_legend') }}
                    </span>
                    <span class="badge rounded-pill fg-body" style="background: #ffe8a3">Saturday</span>
                    <span class="badge rounded-pill fg-primary" style="background: #cfe7ff">Sunday</span>
                </div>

                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="calendar-table mb-0 table">
                                <thead>
                                    <tr>
                                        @foreach (range(0, 6) as $dayOffset)
                                            <th scope="col">
                                                {{ $month->copy()->startOfWeek(\Carbon\Carbon::SUNDAY)->addDays($dayOffset)->translatedFormat('D') }}
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        @php
                                            $calendarCell = 0;
                                            $firstDayOffset = \Carbon\Carbon::create($month->year, $month->month, 1)
                                                ->dayOfWeek;
                                        @endphp

                                        @for ($blankDay = 0; $blankDay < $firstDayOffset; $blankDay++)
                                            <td class="empty"></td>
                                            @php $calendarCell++; @endphp
                                        @endfor

                                        @for ($day = 1; $day <= $month->daysInMonth; $day++)
                                            @php
                                                $date = $month->copy()->day($day);
                                                $dateKey = $date->toDateString();
                                                $holiday = $holidays->get($dateKey);
                                                $dayLeaves = collect($leavesByDay[$dateKey] ?? []);
                                                $dayClass = $holiday
                                                    ? 'holiday'
                                                    : ($date->isSaturday()
                                                        ? 'saturday'
                                                        : ($date->isSunday()
                                                            ? 'sunday'
                                                            : ''));

                                                if ($date->isToday()) {
                                                    $dayClass .= ' today';
                                                }
                                            @endphp

                                            @if ($calendarCell > 0 && $calendarCell % 7 === 0)
                                    </tr>
                                    <tr>
                                        @endif

                                        <td class="{{ trim($dayClass) }}">
                                            <div class="day">
                                                <div class="day-number">{{ $day }}</div>

                                                @if ($holiday)
                                                    <div class="holiday-name mb-1" title="{{ $holiday->name }}">
                                                        {{ \Illuminate\Support\Str::limit($holiday->name, 20) }}
                                                    </div>
                                                @endif

                                                @foreach ($dayLeaves as $leave)
                                                    <div class="badge theme-primary fg-primary border-primary w-100 mb-1 text-wrap border text-start"
                                                        title="{{ $leave->employee->name ?? '-' }}">
                                                        <i data-lucide="user-check" class="me-1"></i>
                                                        {{ \Illuminate\Support\Str::limit($leave->employee->name ?? '-', 16) }}
                                                    </div>
                                                @endforeach
                                            </div>
                                        </td>

                                        @php $calendarCell++; @endphp
                                        @endfor

                                        @for ($blankDay = 0; $blankDay < (7 - ($calendarCell % 7)) % 7; $blankDay++)
                                            <td class="empty"></td>
                                        @endfor
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <h6 class="fw-bold mb-3">{{ __('leaves.calendar.schedule_list') }}</h6>

                <div class="table-responsive rounded-3 border">
                    <table class="table-hover mb-0 table align-middle">
                        <thead class="bg-1">
                            <tr>
                                <th scope="col">{{ __('leaves.calendar.employee') }}</th>
                                <th scope="col">{{ __('leaves.calendar.department') }}</th>
                                <th scope="col">{{ __('leaves.calendar.leave_dates') }}</th>
                                <th scope="col" class="text-center">{{ __('leaves.table.column_days') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $monthLeaves = collect($leavesByDay)->flatten(1)->unique('id')->sortBy('start_date');
                            @endphp
                            @forelse ($monthLeaves as $leave)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ $leave->employee->name ?? '-' }}</div>
                                        <div class="fg-secondary small">{{ $leave->employee->email ?? '-' }}</div>
                                    </td>
                                    <td>{{ $leave->employee->department->name ?? '-' }}</td>
                                    <td>
                                        {{ $leave->start_date->format('d M Y') }}
                                        @if (!$leave->start_date->isSameDay($leave->end_date))
                                            - {{ $leave->end_date->format('d M Y') }}
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $leave->total_days }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="fg-secondary py-4 text-center" colspan="4">
                                        {{ __('leaves.calendar.no_leave') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($holidays->isNotEmpty())
                    <div class="mt-4">
                        <h6 class="fw-bold">{{ __('leaves.calendar.holiday_legend') }}</h6>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach ($holidays as $holiday)
                                <span class="badge theme-warning fg-body">
                                    {{ $holiday->date->format('d M') }}: {{ $holiday->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
