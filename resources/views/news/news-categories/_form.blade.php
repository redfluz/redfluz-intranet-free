@php($newsCategory = $newsCategory ?? null)

<div class="mb-3">
    <label class="form-label fw-bold" for="name">
        {{ __('news_categories.table.column_name') }}
    </label><input class="form-control @error('name') is-invalid @enderror" id="name" name="name"
        type="text" value="{{ old('name', $newsCategory?->name) }}" required />
    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label fw-bold" for="description">
        {{ __('news_categories.table.column_description') }}
    </label>
    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
        rows="4">{{ old('description', $newsCategory?->description) }}</textarea>
    @error('description')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
