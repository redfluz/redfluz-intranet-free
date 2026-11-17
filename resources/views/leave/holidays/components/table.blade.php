{{-- resources/views/leave/holidays/components/table.blade.php --}}

<div class="card border-0 shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center bg-1">
        <div>
            <h5 class="fw-bold mb-0">
                <i class="fg-primary me-2" data-lucide="list"></i>

                {{ __('holidays.table.title') }}
            </h5>

            <small class="fg-secondary">{{ $holidays->count() }} {{ __('holidays.table.count_suffix') }}
                {{ $selectedYear }}</small>
        </div>
    </div>

    <div class="table-responsive rounded-5 overflow-hidden border">
        <table class="table-hover table-holiday mb-0 table align-middle">
            <thead class="bg-1">
                <tr>
                    <th scope="col" width="150">{{ __('holidays.table.column_date') }}</th>

                    <th scope="col" width="120">{{ __('holidays.table.column_day') }}</th>

                    <th scope="col">{{ __('holidays.table.column_name') }}</th>

                    <th scope="col" width="160">{{ __('holidays.table.column_type') }}</th>

                    @if ($canUpdate || $canRemove)
                        <th scope="col" class="text-end" width="150">{{ __('holidays.table.column_action') }}</th>
                    @endif
                </tr>
            </thead>

            <tbody>
                @forelse ($holidays->sortBy('date') as $holiday)
                    @php
                        $isWeekend = $holiday->date->isSaturday() || $holiday->date->isSunday();
                    @endphp

                    <tr id="holiday-row-{{ $holiday->id }}">
                        <td>
                            <div class="fw-bold">{{ $holiday->date->format('d M Y') }}</div>

                            <small class="fg-secondary"> {{ $holiday->date->format('Y') }} </small>
                        </td>

                        <td>
                            @if ($holiday->date->isSunday())
                                <span class="badge theme-primary"> {{ __('holidays.days.sunday') }} </span>
                            @elseif ($holiday->date->isSaturday())
                                <span class="badge theme-warning fg-body"> {{ __('holidays.days.saturday') }} </span>
                            @else
                                {{ $holiday->date->translatedFormat('l') }}
                            @endif
                        </td>

                        <td>
                            <div class="fw-semibold">{{ $holiday->name }}</div>

                            <small class="fg-secondary"> {{ $holiday->date->diffForHumans() }} </small>
                        </td>

                        <td>
                            <span class="badge {{ $holiday->type->badgeClass() }}">
                                {{ $holiday->type->label() }}
                            </span>
                        </td>

                        @if ($canUpdate || $canRemove)
                            <td class="text-end">
                                @if ($canUpdate)
                                    <a class="btn btn-sm btn-solid theme-primary me-1"
                                        href="{{ route('leave.holidays.edit', $holiday) }}"
                                        title="{{ __('common.actions.edit') }}">
                                        <i data-lucide="square-pen"></i>
                                    </a>
                                @endif

                                @if ($canRemove)
                                    <button class="btn btn-sm btn-solid theme-danger btn-delete"
                                        data-id="{{ $holiday->id }}" type="button"
                                        title="{{ __('common.actions.delete') }}">
                                        <i data-lucide="trash-2"></i>
                                    </button>

                                    <form class="d-none" id="delete-form-{{ $holiday->id }}"
                                        action="{{ route('leave.holidays.destroy', $holiday) }}" method="POST">
                                        @csrf

                                        @method('DELETE')
                                    </form>
                                @endif
                            </td>
                        @endif
                    </tr>

                @empty
                    <tr>
                        <td class="py-5 text-center" colspan="{{ $canUpdate || $canRemove ? 5 : 4 }}">
                            <i class="fs-1 fg-secondary d-block mb-3" data-lucide="calendar-x"></i>

                            <div class="fg-secondary">{{ __('holidays.no_results') }}</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
