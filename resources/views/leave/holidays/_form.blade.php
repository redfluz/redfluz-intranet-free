@php($holiday = $holiday ?? null)

<div class="mb-3">
    <label class="form-label fw-bold" for="name">{{ __('holidays.table.column_name') }}</label><input
        id="name" class="form-control @error('name') is-invalid @enderror" name="name"
        type="text" value="{{ old('name', $holiday?->name) }}" required />
    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label fw-bold" for="date">{{ __('holidays.table.column_date') }}</label>
    <div class="form-control form-adorn">
        <div class="form-adorn-icon" aria-hidden="true">
            <i data-lucide="calendar"></i>
        </div>
        <input id="date" class="form-ghost @error('date') is-invalid @enderror" name="date"
            type="text" data-bs-toggle="datepicker" autocomplete="off"
            value="{{ old('date', $holiday?->date?->format('Y-m-d')) }}" required>
    </div>
    @error('date')
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label fw-bold" for="type">{{ __('holidays.table.column_type') }}</label>
    <select id="type" class="form-control @error('type') is-invalid @enderror" name="type" required>
        <option value="public" {{ old('type', $holiday?->type?->value ?? 'public') === 'public' ? 'selected' : '' }}>
            {{ __('holidays.type.public') }}
        </option>
        <option value="company" {{ old('type', $holiday?->type?->value ?? 'public') === 'company' ? 'selected' : '' }}>
            {{ __('holidays.type.company') }}
        </option>
    </select>
    @error('type')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
