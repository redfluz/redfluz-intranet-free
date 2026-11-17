<div class="card border-0 shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center bg-1">
        <div>
            <h5 class="fw-bold mb-0">
                <i class="fg-success me-2" data-lucide="users"></i>
                {{ __('holidays.people_on_leave.title') }}
            </h5>
            <small class="fg-secondary">{{ $leaves->count() }} {{ __('holidays.people_on_leave.subtitle') }}
                {{ $selectedYear }}</small>
        </div>
    </div>

    <div class="table-responsive rounded-5 overflow-hidden border">
        <table class="table-hover mb-0 table align-middle">
            <thead class="bg-1">
                <tr>
                    <th scope="col">{{ __('holidays.people_on_leave.employee') }}</th>
                    <th scope="col">{{ __('holidays.people_on_leave.department') }}</th>
                    <th scope="col">{{ __('holidays.people_on_leave.start_date') }}</th>
                    <th scope="col">{{ __('holidays.people_on_leave.end_date') }}</th>
                    <th scope="col" class="text-end">{{ __('holidays.people_on_leave.days') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($leaves as $leave)
                    <tr>
                        <td>
                            <div class="fw-semibold">
                                {{ $leave->employee?->name ?? __('holidays.people_on_leave.unknown_employee') }}
                            </div>
                            @if ($leave->employee?->role)
                                <small class="fg-secondary">{{ $leave->employee->role->name }}</small>
                            @endif
                        </td>
                        <td>{{ $leave->employee?->department?->name ?? '—' }}</td>
                        <td>{{ $leave->start_date->format('d M Y') }}</td>
                        <td>{{ $leave->end_date->format('d M Y') }}</td>
                        <td class="fw-semibold text-end">{{ $leave->total_days }}</td>
                    </tr>
                @empty
                    <tr>
                        <td class="fg-secondary py-5 text-center" colspan="5">
                            {{ __('holidays.people_on_leave.empty') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
