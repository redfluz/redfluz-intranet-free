@php
    use App\Enums\FileAction;
    use App\Enums\FileResources;
    use App\Models\News\News;

    $canCreate = auth()->user()->hasFilePermission(FileResources::NEWS, FileAction::ADD);
    $canUpdate = auth()->user()->hasFilePermission(FileResources::NEWS, FileAction::UPDATE);
    $canRemove = auth()->user()->hasFilePermission(FileResources::NEWS, FileAction::REMOVE);
@endphp
@props([
    /** @var News */
    'news',
])
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>{{ __('news.title') }}</h2>
            <a class="btn btn-sm btn-solid theme-primary" href="{{ route('news.news.create') }}">
                <i class="me-1" data-lucide="plus"></i>{{ __('common.actions.add') }}
            </a>
        </div>

        {{-- Render the alert partial here --}}
        @include('partials.alerts')

        <div class="row">
            @forelse ($newsList as $news)
                <div class="md:col-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        @if ($news->featuredImageUrl())
                            <img class="card-img-top" src="{{ $news->featuredImageUrl() }}" alt="{{ $news->title }}"
                                style="height: 200px; object-fit: cover" />
                        @endif
                        <div class="card-body">
                            <span class="badge theme-secondary mb-2"> {{ $news->category->name }} </span>
                            <h5 class="card-title">{{ $news->title }}</h5>
                            <p class="card-text fg-secondary">{{ Str::limit($news->short_description, 100) }}</p>
                        </div>
                        <div class="card-footer d-flex justify-content-between align-items-center bg-2">
                            <div class="d-flex gap-1">
                                @if ($canCreate)
                                    <a class="btn btn-sm btn-solid theme-info me-1 text-white"
                                        href="{{ route('news.news.show', $news->id) }}"
                                        title="{{ __('common.actions.view') }}">
                                        <i data-lucide="eye"></i>
                                    </a>
                                @endif
                                @if ($canUpdate)
                                    <a class="btn btn-sm btn-solid theme-primary me-1"
                                        href="{{ route('news.news.edit', $news->id) }}"
                                        title="{{ __('common.actions.edit') }}">
                                        <i data-lucide="square-pen"></i>
                                    </a>
                                @endif
                                @if ($canRemove)
                                    <form class="d-inline" id="delete-form-{{ $news->id }}"
                                        action="{{ route('news.news.destroy', $news) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-solid theme-danger btn-delete"
                                            data-id="{{ $news->id }}" type="button"
                                            title="{{ __('common.actions.delete') }}">
                                            <i data-lucide="trash-2"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <p class="fg-secondary">{{ __('common.labels.not_found_record') }}</p>
                </div>
            @endforelse
        </div>

        <div class="mt-3">{{ $newsList->links() }}</div>
    </div>

    {{-- Delete Confirmation Dialog --}}
    <dialog class="dialog" id="deleteConfirmModal">
        <form method="POST" id="deleteForm">
            @csrf
            @method('DELETE')
            <div class="dialog-header">
                <h5 class="dialog-title">{{ __('common.alerts.confirm_delete') }}</h5>
                <button class="btn-close" data-bs-dismiss="dialog" type="button"></button>
            </div>
            <div class="dialog-body">
                <p>{{ __('common.alerts.confirm_delete_message') }}</p>
            </div>
            <div class="dialog-footer">
                <button class="btn btn-solid theme-secondary" data-bs-dismiss="dialog" type="button">
                    {{ __('common.actions.cancel') }}
                </button>
                <button class="btn btn-solid theme-danger" type="submit">
                    {{ __('common.alerts.confirm_delete_yes') }}
                </button>
            </div>
        </form>
    </dialog>
@endsection

@section('script')
    <script type="module">
        document.addEventListener('click', function(e) {
            const deleteBtn = e.target.closest('.btn-delete');

            if (deleteBtn) {
                const newsId = deleteBtn.getAttribute('data-id');
                document.getElementById('deleteForm').action = "{{ route('news.news.destroy', '__ID__') }}"
                    .replace('__ID__', newsId);
                bootstrap.Dialog.getOrCreateInstance(document.getElementById('deleteConfirmModal')).show();
            }
        });
    </script>
@endsection
