@php
    $news = $news ?? null;
@endphp

<div class="mb-3">
    <label class="form-label" for="title"> {{ __('news.table.column_title') }} </label><input
        id="title" class="form-control @error('title') is-invalid @enderror" name="title"
        type="text" value="{{ old('title', $news?->title) }}" required />
    @error('title')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label" for="news_category_id">
        {{ __('news.table.column_news_category_id') }} </label>
    <select id="news_category_id"
        class="form-control @error('news_category_id') is-invalid @enderror"
        name="news_category_id" required>
        <option value="">{{ __('common.actions.select_option') }}</option>
        @foreach ($newsCategories as $newsCategory)
            <option value="{{ $newsCategory->id }}"
                {{ old('news_category_id', $news?->news_category_id) == $newsCategory->id ? 'selected' : '' }}>
                {{ $newsCategory->name }}
            </option>
        @endforeach
    </select>
    @error('news_category_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label" for="short_description">
        {{ __('news.table.column_short_description') }} </label>
    <textarea id="short_description" class="form-control @error('short_description') is-invalid @enderror"
        name="short_description" rows="2">{{ old('short_description', $news?->short_description) }}</textarea>
    @error('short_description')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label" for="content"> {{ __('news.table.column_content') }} </label>
    <textarea id="content" class="form-control @error('content') is-invalid @enderror" name="content" rows="6"
        required>{{ old('content', $news?->content) }}</textarea>
    @error('content')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label" for="featured_image">
        {{ __('news.table.column_featured_image') }} </label>
    @if ($news?->featuredImageUrl())
        <div class="mb-2">
            <img class="rounded-5 border" src="{{ $news->featuredImageUrl() }}"
                alt="Current Image Preview" style="height: 100px; object-fit: cover"
                referrerpolicy="no-referrer" />
        </div>
    @endif
    <input id="featured_image"
        class="form-control @error('featured_image') is-invalid @enderror" name="featured_image"
        type="file" accept=".jpg,.jpeg,.png,.webp,.gif" />
    <small class="fg-secondary">Accepted formats: JPG, PNG, WEBP, GIF (Max 5MB). Leave blank to keep the existing image.</small>
    @error('featured_image')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="row">
    <div class="md:col-6 mb-3">
        <label class="form-label" for="status"> {{ __('news.table.column_status') }} </label>
        <select id="status" class="form-control" name="status">
            <option value="draft" {{ old('status', $news?->status) == 'draft' ? 'selected' : '' }}>
                {{ __('common.status.draft') }}
            </option>
            <option value="published" {{ old('status', $news?->status) == 'published' ? 'selected' : '' }}>
                {{ __('common.status.published') }}
            </option>
            <option value="archived" {{ old('status', $news?->status) == 'archived' ? 'selected' : '' }}>
                {{ __('common.status.archived') }}
            </option>
        </select>
    </div>
    <div class="md:col-6 mb-3">
        <label class="form-label" for="published_at">
            {{ __('news.table.column_published_at') }} </label>
        <div class="d-flex gap-2">
            <input class="form-control" id="published_at" name="published_at" type="text"
                data-bs-toggle="datepicker" autocomplete="off"
                value="{{ old('published_at', $news?->published_at?->format('Y-m-d') ?? now()->format('Y-m-d')) }}">
            <label class="visually-hidden" for="published_time">{{ __('news.table.column_published_time') }}</label>
            <select class="form-control" id="published_time" name="published_time">
                @for ($h = 0; $h < 24; $h++)
                    @for ($m = 0; $m < 60; $m += 30)
                        <option value="{{ sprintf('%02d:%02d', $h, $m) }}"
                            {{ old('published_time', $news?->published_at?->format('H:i') ?? now()->format('H:i')) === sprintf('%02d:%02d', $h, $m) ? 'selected' : '' }}>
                            {{ sprintf('%02d:%02d', $h, $m) }}</option>
                    @endfor
                @endfor
            </select>
        </div>
    </div>
</div>
