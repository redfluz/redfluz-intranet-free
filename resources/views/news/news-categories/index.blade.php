{{--

/**
 * ============================================================================
 *
 * @author redfluz
 *
 * @github https://github.com/redfluz
 *
 * @youtube https://youtube.com/@redfluz
 *
 * @contact corex2020@icloud.com
 *
 * @customer
 *
 * @copyright Copyright (c) 2026 redfluz. All Rights Reserved.
 * ============================================================================
 *
 * PROPRIETARY AND CONFIDENTIAL
 *
 * All code, concepts, and intellectual property contained in this file are the
 * exclusive property of redfluz. Unauthorized copying, distribution, modification,
 * reverse engineering, or public display of this software via any medium is
 * strictly prohibited without explicit written permission from the copyright owner.
 */
--}}

@php
    use App\Enums\FileAction;
    use App\Enums\FileResources;
    use App\Models\News\NewsCategory;

    $canCreate = auth()->user()->hasFilePermission(FileResources::NEWS_CATEGORIES, FileAction::ADD);
    $canUpdate = auth()->user()->hasFilePermission(FileResources::NEWS_CATEGORIES, FileAction::UPDATE);
    $canRemove = auth()->user()->hasFilePermission(FileResources::NEWS_CATEGORIES, FileAction::REMOVE);
@endphp
@props([
    /** @var NewsCategory */
    'newsCategory',
])
@extends('layouts.app')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header d-flex align-items-center justify-content-between bg-1 py-3">
            <h4 class="fw-bold m-0">
                {{ __('news_categories.title') }}
                <p class="fg-secondary small mb-0">{{ __('news_categories.subtitle') }}</p>
            </h4>
            @if ($canCreate)
                <a class="btn btn-sm btn-solid theme-primary" href="{{ route('news.news-categories.create') }}">
                    <i class="me-1" data-lucide="plus"></i>
                    {{ __('common.actions.add') }}
                </a>
            @endif
        </div>

        <div class="card-body">
            @include('partials.alerts')
            <!-- Search Bar -->
            <form autocomplete="off" class="row g-2 mb-3" method="GET" action="{{ route('news.news-categories.index') }}">
                <div class="md:col-4">
                    <label class="visually-hidden" for="search">{{ __('common.actions.search') }}</label>
                    <div class="form-control form-adorn">
                        <span class="form-adorn-icon" aria-hidden="true"><i data-lucide="search"></i></span>
                        <input id="search" class="form-ghost" name="search" type="text" value="{{ $search }}"
                            placeholder="{{ __('common.actions.search') }}">
                    </div>
                    @if ($search)
                        <a class="btn btn-solid theme-danger" href="{{ route('news.news-categories.index') }}"
                            title="{{ __('common.actions.reset') }}"><i data-lucide="x"></i></a>
                    @endif
                </div>
            </form>

            <div class="table-responsive rounded-5 overflow-hidden border">
                <table class="table-hover table-striped mb-0 table align-middle">
                    <thead class="bg-1">
                        <tr>
                            <th scope="col">{{ __('news_categories.table.column_name') }}</th>
                            <th scope="col">{{ __('news_categories.table.column_description') }}</th>
                            <th scope="col" class="col-date">{{ __('common.labels.created_at') }}</th>
                            <th scope="col" class="col-action text-center">{{ __('common.labels.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($newsCategories as $newsCategory)
                            <tr>
                                <td class="fw-bold">{{ $newsCategory->name }}</td>
                                <td>{{ $newsCategory->description ?? 'N/A' }}</td>
                                <td>{{ $newsCategory->created_at->format('d M Y') ?? 'N/A' }}</td>
                                <td class="text-center">
                                    @if ($canUpdate)
                                        <a class="btn btn-sm btn-solid theme-primary me-1"
                                            href="{{ route('news.news-categories.edit', $newsCategory) }}"
                                            title="{{ __('common.actions.edit') }}">
                                            <i data-lucide="square-pen"></i>
                                        </a>
                                    @endif
                                    @if ($canRemove)
                                        <button class="btn btn-sm btn-solid theme-danger btn-delete"
                                            data-id="{{ $newsCategory->id }}" type="button"
                                            title="{{ __('common.actions.delete') }}">
                                            <i data-lucide="trash-2"></i>
                                        </button>

                                        <form class="d-none" id="delete-form-{{ $newsCategory->id }}"
                                            action="{{ route('news.news-categories.destroy', $newsCategory) }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="fg-secondary py-4 text-center" colspan="5">
                                    {{ __('common.labels.not_found_record') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-end mt-3">{{ $newsCategories->links() }}</div>
        </div>
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
                const dataId = deleteBtn.getAttribute('data-id');
                document.getElementById('deleteForm').action =
                    "{{ route('news.news-categories.destroy', '__ID__') }}".replace('__ID__', dataId);
                bootstrap.Dialog.getOrCreateInstance(document.getElementById('deleteConfirmModal')).show();
            }
        });
    </script>
@endsection
