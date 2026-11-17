<div class="d-flex justify-content-end mb-3 gap-2">
    <span class="badge rounded-pill theme-danger"> {{ __('holidays.legend.holiday') }} </span>

    <span class="badge rounded-pill fg-body" style="background: #ffe8a3"> {{ __('holidays.days.saturday') }} </span>

    <span class="badge rounded-pill fg-primary" style="background: #cfe7ff"> {{ __('holidays.days.sunday') }} </span>

    @if (!empty($leavesByDay))
        <span class="badge rounded-pill theme-primary fg-primary border-primary border">
            {{ __('leaves.calendar.on_leave_legend') }}
        </span>
    @endif
</div>
