{{-- resources/views/leave/holidays/components/calendar.blade.php --}}

@php
    use Carbon\Carbon;

    $currentMonth = request('month', now()->month);

    $currentMonth = max(1, min(12, (int) $currentMonth));

    $monthDate = Carbon::create($selectedYear, $currentMonth, 1);

    $daysInMonth = $monthDate->daysInMonth;

    $startDay = $monthDate->dayOfWeek;

    $today = today();
@endphp

<div class="card border-0 shadow-sm">
    <div class="card-header border-bottom bg-1">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="fw-bold mb-0">{{ $monthDate->translatedFormat('F Y') }}</h5>
            </div>

            <div class="btn-group">
                @foreach (range(1, 12) as $m)
                    <a class="btn btn-sm {{ $currentMonth == $m ? 'btn-solid theme-primary' : 'btn-solid theme-light border' }}"
                        href="{{ route($holidayIndexRoute, [
                            'year' => $selectedYear,
                            'month' => $m,
                        ]) }}">
                        {{ Carbon::create()->month($m)->translatedFormat('M') }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <div class="card-body p-0">
        <table class="calendar-table mb-0 table">
            <thead>
                <tr>
                    @foreach (__('holidays.calendar.weekdays') as $weekday)
                        <th scope="col">{{ $weekday }}</th>
                    @endforeach
                </tr>
            </thead>

            <tbody>
                <tr>
                    @php
                        $cell = 0;
                    @endphp

                    @for ($i = 0; $i < $startDay; $i++)
                        <td class="empty"></td>

                        @php
                            $cell++;
                        @endphp
                    @endfor

                    @for ($day = 1; $day <= $daysInMonth; $day++)
                        @php
                            $date = Carbon::create($selectedYear, $currentMonth, $day);

                            $dateKey = $date->format('Y-m-d');
                            $holiday = $holidayMap->get($dateKey);
                            $dayLeaves = collect($leavesByDay[$dateKey] ?? []);

                            $class = '';

                            if ($holiday) {
                                $class = 'holiday';
                            } elseif ($date->isSaturday()) {
                                $class = 'saturday';
                            } elseif ($date->isSunday()) {
                                $class = 'sunday';
                            }

                            if ($date->equalTo($today)) {
                                $class .= ' today';
                            }
                        @endphp

                        @if ($cell > 0 && $cell % 7 == 0)
                </tr>

                <tr>
                    @endif

                    <td class="{{ trim($class) }}"
                        @if ($holiday) onclick="scrollToRow({{ $holiday->id }})"
 data-bs-toggle="tooltip"
 title="{{ $holiday->name }}" @endif>
                        <div class="day">
                            <div class="day-number">{{ $day }}</div>

                            @if ($holiday)
                                <div class="holiday-name">{{ \Illuminate\Support\Str::limit($holiday->name, 18) }}</div>
                            @endif

                            @foreach ($dayLeaves as $leave)
                                <div class="badge theme-primary fg-primary border-primary w-100 mb-1 text-wrap border text-start"
                                    title="{{ $leave->employee->name ?? '-' }}">
                                    <i class="me-1" data-lucide="calendar-clock"></i>
                                    {{ \Illuminate\Support\Str::limit($leave->employee->name ?? '-', 15) }}
                                </div>
                            @endforeach
                        </div>
                    </td>

                    @php
                        $cell++;
                    @endphp
                    @endfor

                    @php
                        $remain = (7 - ($cell % 7)) % 7;
                    @endphp

                    @for ($i = 0; $i < $remain; $i++)
                        <td class="empty"></td>
                    @endfor
                </tr>
            </tbody>
        </table>
    </div>
</div>
