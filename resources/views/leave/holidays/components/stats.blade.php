{{-- resources/views/leave/holidays/components/stats.blade.php --}}

<div class="row g-3">
    <div class="md:col-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-circle bg-10 fg-primary me-3 p-3">
                    <i class="fg-primary fs-4" data-lucide="calendar-days"></i>
                </div>

                <div>
                    <div class="fg-secondary small">{{ __('holidays.stats.total') }}</div>

                    <h3 class="fw-bold mb-0">{{ $holidays->count() }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="md:col-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-circle bg-10 fg-danger me-3 p-3">
                    <i class="fg-danger fs-4" data-lucide="flag"></i>
                </div>

                <div>
                    <div class="fg-secondary small">{{ __('holidays.stats.public') }}</div>

                    <h3 class="fw-bold mb-0">{{ $publicCount }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="md:col-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-circle bg-10 fg-warning me-3 p-3">
                    <i class="fg-warning fs-4" data-lucide="map-pin"></i>
                </div>

                <div>
                    <div class="fg-secondary small">{{ __('holidays.stats.state') }}</div>

                    <h3 class="fw-bold mb-0">{{ $stateCount }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="md:col-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-circle bg-10 fg-success me-3 p-3">
                    <i class="fg-success fs-4" data-lucide="clock"></i>
                </div>

                <div>
                    <div class="fg-secondary small">{{ __('holidays.stats.upcoming') }}</div>

                    <h3 class="fw-bold mb-0">{{ $upcomingCount }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>
