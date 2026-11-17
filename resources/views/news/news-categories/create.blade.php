@php
    use App\Enums\FileAction;
    use App\Enums\FileResources;

    $canCreate = auth()->user()->hasFilePermission(FileResources::NEWS_CATEGORIES, FileAction::ADD);
@endphp
@extends('layouts.app')

@section('content')
    <div class="card md:col-8 mx-auto border-0 shadow-sm">
        <div class="card-header bg-1 py-3">
            <h5 class="fw-bold m-0">{{ __('common.actions.add') }}</h5>
        </div>

        <div class="card-body">
            @include('partials.errors')
            <form action="{{ route('news.news-categories.store') }}" method="POST">
                @csrf

                @include('news.news-categories._form')

                <div class="d-flex justify-content-between pt-2">
                    <a class="btn btn-solid theme-secondary" href="{{ route('news.news-categories.index') }}">
                        <i class="me-1" data-lucide="arrow-left"></i>
                        {{ __('common.actions.back') }}
                    </a>
                    @if ($canCreate)
                        <button class="btn btn-solid theme-primary" type="submit">
                            <i class="me-1" data-lucide="save"></i>
                            {{ __('common.actions.save') }}
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
@endsection
