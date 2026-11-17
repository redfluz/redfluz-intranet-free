@php
    $leave = $leave ?? null;
@endphp
<div class="row g-3 mb-3">
    <div class="md:col-6">
        <label class="form-label small fw-medium" for="leaveStartDate">{{ __('leaves.dashboard.from_date') }}</label>
        <div class="form-control form-adorn">
            <div class="form-adorn-icon" aria-hidden="true">
                <i data-lucide="calendar"></i>
            </div>
            <input id="leaveStartDate" class="form-ghost @error('start_date') is-invalid @enderror" name="start_date"
                type="text" data-bs-toggle="datepicker" autocomplete="off"
                value="{{ old('start_date', $leave?->start_date?->format('Y-m-d')) }}" required>
        </div>
        @error('start_date')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>
    <div class="md:col-6">
        <label class="form-label small fw-medium" for="leaveEndDate">{{ __('leaves.dashboard.to_date') }}</label>
        <div class="form-control form-adorn">
            <div class="form-adorn-icon" aria-hidden="true">
                <i data-lucide="calendar"></i>
            </div>
            <input id="leaveEndDate" class="form-ghost @error('end_date') is-invalid @enderror" name="end_date"
                type="text" data-bs-toggle="datepicker" autocomplete="off"
                value="{{ old('end_date', $leave?->end_date?->format('Y-m-d')) }}" required>
        </div>
        @error('end_date')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="mb-3">
    <label class="form-label small fw-medium" for="reason">{{ __('leaves.table.column_reason') }}</label>
    <textarea id="reason" class="form-control @error('reason') is-invalid @enderror" name="reason" rows="3"
        placeholder="{{ __('leaves.dashboard.reason_placeholder') }}">{{ old('reason', $leave?->reason) }}</textarea>
    @error('reason')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-4">
    <label class="form-label small fw-medium" for="attachment">Medical Certificate /
        Attachment</label>
    @if ($leave?->attachment_path)
        <div class="mb-2">
            <a class="btn btn-sm btn-solid theme-info" data-fancybox="leave-attachment"
                data-type="{{ Str::endsWith(strtolower($leave->attachment_path), '.pdf') ? 'iframe' : 'image' }}"
                href="{{ Storage::url($leave->attachment_path) }}">
                Current Attachment
            </a>
        </div>
    @endif
    <input id="attachment" class="form-control @error('attachment') is-invalid @enderror" name="attachment"
        type="file" accept=".pdf,.jpg,.jpeg,.png" />
    <div class="form-text">
        {{ $leave?->attachment_path
            ? 'Accepted formats: PDF, JPG, PNG (Max 5MB). Leave blank to keep existing attachment.'
            : 'Accepted formats: PDF, JPG, PNG (Max 5MB).' }}
    </div>
    @error('attachment')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>