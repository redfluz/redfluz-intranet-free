@php use Carbon\Carbon; @endphp
@inject('dash', App\Services\Dashboard\DashboardPersonnelService::class)
@extends('layouts.app')

@php
    $userId = auth()->id();
    $newsList = $dash->getNewsFeed(8);
    $tasks = $dash->getMyTasks($userId, 5);
    $leaveSummary = $dash->getLeaveSummary($userId);
    $recentLeaves = $dash->getRecentLeaves($userId, 5);
    $claimsData = $dash->getClaimSummary($userId, 5);
@endphp

@section('content')
    <div class="dashboard-wrapper px-md-4 px-3 py-4">
        <div class="container-fluid max-w-7xl">
            <div class="row g-4">
                {{-- LEFT COLUMN (75%) - MAIN NEWS FEED --}}
                <div class="lg:col-8 col-12">
                    <div class="d-flex justify-content-between align-items-center border-bottom mb-4 border-2 pb-2">
                        <div>
                            <h2 class="h4 fw-bold fg-body d-flex align-items-center mb-1 gap-2">
                                <span
                                    class="theme-primary rounded-5 d-inline-flex align-items-center justify-content-center p-2 fg-body shadow-sm"
                                    style="width: 34px; height: 34px">
                                    <i data-lucide="newspaper" class="fs-6"></i>
                                </span>
                                {{ __('personnel_dashboard.feed.title') }}
                            </h2>
                            <p class="fg-secondary small mb-0 ms-1">{{ __('personnel_dashboard.feed.subtitle') }}</p>
                        </div>
                    </div>

                    <div class="d-flex flex-column gap-4">
                        @forelse ($newsList as $news)
                            <div class="card feed-card rounded-5 overflow-hidden border-0">
                                @if ($news->featuredImageUrl())
                                    <a class="d-block overflow-hidden" href="{{ route('news.news.show', $news) }}">
                                        <img class="card-img-top object-fit-cover feed-img"
                                            src="{{ $news->featuredImageUrl() }}" alt="{{ $news->title }}"
                                            style="height: 320px" />
                                    </a>
                                @endif

                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center mb-2 gap-2">
                                        @if ($news->category)
                                            <span class="badge theme-primary fw-semibold rounded-pill px-3 py-1.5 fg-body">
                                                {{ $news->category->name }}
                                            </span>
                                        @endif
                                        <span class="fg-secondary small">&bull;</span>
                                        <time class="fg-secondary small fw-medium" datetime="{{ $news->published_at }}">
                                            {{ Carbon::parse($news->published_at)->locale(app()->getLocale())->translatedFormat(__('personnel_dashboard.date_format')) }}
                                        </time>
                                        <span class="fg-secondary small">&bull;</span>
                                        <span class="fg-secondary small fw-medium">
                                            {{ trans_choice('personnel_dashboard.feed.views', $news->views_count, ['count' => $news->views_count]) }}
                                        </span>
                                    </div>

                                    <h3 class="card-title h5 fw-bold mb-2">
                                        <a class="fg-body text-decoration-none feed-title"
                                            href="{{ route('news.news.show', $news) }}">
                                            {{ $news->title }}
                                        </a>
                                    </h3>

                                    @if ($news->short_description)
                                        <p class="card-text fg-secondary small mb-3 leading-relaxed">
                                            {{ $news->short_description }}
                                        </p>
                                    @endif

                                    <div class="border-top d-flex justify-content-between align-items-center pt-3">
                                        <a class="btn btn-sm btn-link fg-primary text-decoration-none fw-bold d-inline-flex align-items-center gap-1 p-0"
                                            href="{{ route('news.news.show', $news) }}">
                                            {{ __('personnel_dashboard.feed.read_article') }}
                                            <i data-lucide="arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="card fg-secondary rounded-5 border-0 p-5 text-center">
                                {{ __('personnel_dashboard.feed.empty') }}
                            </div>
                        @endforelse
                    </div>

                    <div class="d-flex justify-content-center mt-4">{{ $newsList->links() }}</div>
                </div>

                {{-- RIGHT COLUMN (25%) - SIDEBAR WIDGETS --}}
                <div class="lg:col-4 col-12">
                    <div class="d-flex flex-column gap-4">
                        {{-- LEAVE STATS WIDGET --}}
                        <div class="card widget-card rounded-5 overflow-hidden border-0">
                            <div
                                class="card-header widget-header d-flex justify-content-between align-items-center px-4 py-3">
                                <h6 class="fw-bold fg-body d-flex align-items-center mb-0 gap-2">
                                    <span class="badge theme-indigo fg-indigo rounded-5 p-1">
                                        <i data-lucide="calendar-check"></i>
                                    </span>
                                    {{ __('personnel_dashboard.leave.title') }}
                                </h6>
                                <a class="small fg-primary text-decoration-none fw-bold"
                                    href="{{ route('personnel.leaves.index') }}">{{ __('personnel_dashboard.view_all') }}</a>
                            </div>
                            <div class="card-body p-4">
                                <div class="d-flex flex-column gap-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span
                                            class="fg-secondary small fw-semibold">{{ __('personnel_dashboard.leave.pending_requests') }}</span>
                                        <span
                                            class="badge theme-warning fg-body rounded-pill fw-bold px-3 py-1.5">
                                            {{ $leaveSummary['pending_count'] }}
                                        </span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span
                                            class="fg-secondary small fw-semibold">{{ __('personnel_dashboard.leave.approved_days') }}</span>
                                        <span
                                            class="badge theme-primary fg-primary border-primary rounded-pill fw-bold border px-3 py-1.5">
                                            @php($approvedDays = $leaveSummary['approved_days'])
                                            {{ trans_choice('personnel_dashboard.days', $approvedDays, ['count' => $approvedDays]) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- MY TASKS WIDGET --}}
                        <div class="card widget-card rounded-5 overflow-hidden border-0">
                            <div
                                class="card-header widget-header d-flex justify-content-between align-items-center px-4 py-3">
                                <h6 class="fw-bold fg-body d-flex align-items-center mb-0 gap-2">
                                    <span class="badge theme-amber fg-amber rounded-5 p-1">
                                        <i data-lucide="check-square"></i>
                                    </span>
                                    {{ __('personnel_dashboard.tasks.title') }}
                                </h6>
                                <a class="small fg-primary text-decoration-none fw-bold"
                                    href="{{ route('personnel.tasks.index') }}">{{ __('personnel_dashboard.tasks.board') }}</a>
                            </div>
                            <div class="card-body p-4">
                                <div class="d-flex flex-column gap-3">
                                    @forelse ($tasks as $task)
                                        <div class="border-bottom border-light-subtle last-border-0 pb-2">
                                            <a class="fg-body fw-bold text-decoration-none small d-block text-truncate feed-title"
                                                href="{{ route('personnel.tasks.edit', $task) }}">
                                                {{ $task->title }}
                                            </a>
                                            <div
                                                class="d-flex justify-content-between align-items-center extra-small fg-secondary mt-2">
                                                <span class="badge theme-secondary fg-secondary rounded-3 border px-2 py-1">
                                                    {{ __('tasks.stage.' . ($task->stage->value ?? $task->stage)) }}
                                                </span>
                                                @if ($task->due_date)
                                                    <span
                                                        class="{{ Carbon::parse($task->due_date)->isPast() ? 'fg-danger fw-bold' : 'fg-secondary fw-medium' }}">
                                                        {{ __('personnel_dashboard.tasks.due', ['date' => Carbon::parse($task->due_date)->locale(app()->getLocale())->translatedFormat(__('personnel_dashboard.short_date_format'))]) }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    @empty
                                        <p class="small fg-secondary mb-0">{{ __('personnel_dashboard.tasks.empty') }}</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        {{-- RECENT LEAVE REQUESTS WIDGET --}}
                        <div class="card widget-card rounded-5 overflow-hidden border-0">
                            <div
                                class="card-header widget-header d-flex justify-content-between align-items-center px-4 py-3">
                                <h6 class="fw-bold fg-body d-flex align-items-center mb-0 gap-2">
                                    <span class="badge theme-emerald fg-emerald rounded-5 p-1">
                                        <i data-lucide="clock"></i>
                                    </span>
                                    {{ __('personnel_dashboard.leave.recent') }}
                                </h6>
                                <a class="btn btn-solid theme-primary btn-sm"
                                    href="{{ route('personnel.leaves.create') }}"><i class="me-1"
                                        data-lucide="plus"></i>{{ __('common.actions.add') }}</a>
                            </div>
                            <div class="card-body p-4">
                                <div class="d-flex flex-column gap-3">
                                    @forelse ($recentLeaves as $leave)
                                        <div
                                            class="d-flex justify-content-between align-items-center border-bottom border-light-subtle last-border-0 pb-2">
                                            <div>
                                                <p class="small fw-bold fg-body mb-0">
                                                    {{ Carbon::parse($leave->start_date)->locale(app()->getLocale())->translatedFormat(__('personnel_dashboard.short_date_format')) }}
                                                    -
                                                    {{ Carbon::parse($leave->end_date)->locale(app()->getLocale())->translatedFormat(__('personnel_dashboard.short_date_format')) }}
                                                </p>
                                                <span class="extra-small fg-secondary fw-medium">
                                                    {{ trans_choice('personnel_dashboard.days', $leave->total_days, ['count' => $leave->total_days]) }}
                                                </span>
                                            </div>
                                            <span
                                                class="badge rounded-pill extra-small text-uppercase fw-bold @if ($leave->status->value === 'approved') theme-success fg-body
 @elseif($leave->status->value === 'rejected') theme-danger fg-body
 @else theme-warning fg-body @endif px-2 py-1">
                                                {{ __('leaves.status.' . $leave->status->value) }}
                                            </span>
                                        </div>
                                    @empty
                                        <p class="small fg-secondary mb-0">{{ __('personnel_dashboard.leave.empty') }}</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        {{-- CLAIMS SUMMARY WIDGET --}}
                        <div class="card widget-card rounded-5 overflow-hidden border-0">
                            <div
                                class="card-header widget-header d-flex justify-content-between align-items-center px-4 py-3">
                                <h6 class="fw-bold fg-body d-flex align-items-center mb-0 gap-2">
                                    <span class="badge theme-rose fg-rose rounded-5 p-1">
                                        <i data-lucide="receipt"></i>
                                    </span>
                                    {{ __('personnel_dashboard.claims.title') }}
                                </h6>
                                <a class="small fg-primary text-decoration-none fw-bold"
                                    href="{{ route('personnel.claims.index') }}">{{ __('personnel_dashboard.view_all') }}</a>
                            </div>
                            <div class="card-body p-4">
                                @if ($claimsData['pending_count'] > 0)
                                    <div
                                        class="alert theme-warning fg-warning-emphasis border-warning extra-small rounded-5 fw-medium mb-3 border px-3 py-2">
                                        <i data-lucide="circle-alert" class="me-1"></i>
                                        {{ trans_choice('personnel_dashboard.claims.pending', $claimsData['pending_count'], ['count' => $claimsData['pending_count']]) }}
                                    </div>
                                @endif

                                <div class="d-flex flex-column gap-3">
                                    @forelse ($claimsData['recent'] as $claim)
                                        <div
                                            class="d-flex justify-content-between align-items-center border-bottom border-light-subtle last-border-0 pb-2">
                                            <div>
                                                <p class="small fw-bold fg-body mb-0">
                                                    {{ $claim->reference_number }}
                                                </p>
                                                <span class="extra-small fg-secondary fw-medium">
                                                    {{ Carbon::parse($claim->receipt_date)->locale(app()->getLocale())->translatedFormat(__('personnel_dashboard.date_format')) }}
                                                </span>
                                            </div>
                                            <div class="text-end">
                                                <p class="small fw-bold fg-body mb-0">
                                                    {{ __('claims.currency') }} {{ number_format($claim->amount, 2) }}
                                                </p>
                                                <span class="extra-small text-uppercase fw-semibold fg-secondary">
                                                    {{ __('claims.status.' . $claim->status->value) }}
                                                </span>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="small fg-secondary mb-0">
                                            {{ __('personnel_dashboard.claims.empty') }}
                                        </p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Contrast Slate Background Wrapper */
        .dashboard-wrapper {
            background-color: var(--bg-body);
            min-height: 100vh;
        }

        /* Card Elevation & Crisp Border Rules */
        .feed-card,
        .widget-card {
            background-color: var(--bg-1);
            border: 1px solid var(--border-color) !important;
            box-shadow: 0 4px 12px color-mix(in srgb, var(--fg-body) 10%, transparent) !important;
            transition:
                transform 0.2s ease,
                box-shadow 0.2s ease;
        }

        .feed-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px color-mix(in srgb, var(--fg-body) 16%, transparent) !important;
        }

        .widget-header {
            background-color: var(--bg-2);
            border-bottom: 1px solid var(--border-color);
        }

        /* Micro Utility Classes */
        .extra-small {
            font-size: 0.725rem;
        }

        .btn-xs {
            font-size: 0.725rem;
            line-height: 1.2;
        }

        .last-border-0:last-child {
            border-bottom: 0 !important;
            padding-bottom: 0 !important;
        }

        /* Category Badge Contrast Colors */
        .bg-indigo-subtle {
            background-color: var(--primary-bg-muted);
        }

        .fg-indigo {
            color: var(--primary-fg-emphasis);
        }

        .bg-amber-subtle {
            background-color: color-mix(in srgb, var(--warning-base) 18%, var(--bg-1));
        }

        .fg-amber {
            color: var(--warning-fg-emphasis);
        }

        .bg-emerald-subtle {
            background-color: color-mix(in srgb, var(--success-base) 18%, var(--bg-1));
        }

        .fg-emerald {
            color: var(--success-fg-emphasis);
        }

        .bg-rose-subtle {
            background-color: color-mix(in srgb, var(--danger-base) 18%, var(--bg-1));
        }

        .fg-rose {
            color: var(--danger-fg-emphasis);
        }

        .feed-img {
            transition: transform 0.3s ease;
        }

        .feed-card:hover .feed-img {
            transform: scale(1.015);
        }

        .feed-title:hover {
            color: var(--primary-fg-emphasis) !important;
        }
    </style>
@endsection
