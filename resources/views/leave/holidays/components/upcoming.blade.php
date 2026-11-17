{{-- resources/views/leave/holidays/components/upcoming.blade.php --}}

@php
    $upcomingHolidays = $holidays->filter(fn($holiday) => $holiday->date->gte(today()))->sortBy('date')->take(6);
@endphp

<div class="card border-0 shadow-sm">
    <div class="card-header bg-1">
        <h6 class="fw-bold mb-0">
            <i class="fg-danger me-2" data-lucide="clock"></i>

            {{ __('holidays.upcoming.title') }}
        </h6>
    </div>

    <div class="card-body p-0">
        @forelse ($upcomingHolidays as $holiday)
            <div role="button" class="upcoming-item" style="cursor: pointer; padding: 20px" tabindex="0"
                onclick="scrollToRow({{ $holiday->id }})"
                onkeydown="if(event.key === 'Enter') { scrollToRow({{ $holiday->id }}); }">
                <div class="upcoming-date">
                    <strong> {{ $holiday->date->format('d') }} </strong>

                    <span> {{ strtoupper($holiday->date->format('M')) }} </span>
                </div>

                <div class="flex-grow-1">
                    <div class="upcoming-title">{{ $holiday->name }}</div>

                    <div class="fg-secondary small mt-1">
                        <i class="me-1" data-lucide="calendar"></i>

                        {{ $holiday->date->translatedFormat('l') }}
                    </div>

                    <div class="mt-2">
                        <span class="badge {{ $holiday->type->badgeClass() }}"> {{ $holiday->type->label() }} </span>
                    </div>
                </div>
            </div>

        @empty
            <div class="fg-secondary py-5 text-center">
                <i class="fs-2 d-block mb-3" data-lucide="calendar-check"></i>

                {{ __('holidays.upcoming.empty') }}
            </div>
        @endforelse
    </div>
</div>
