@php
    use App\Enums\FileAction;
    use App\Enums\FileResources;
    use App\Enums\Task\TaskPriority;
    use App\Enums\Task\TaskStage;

    $canEditTask = auth()->user()->hasFilePermission(FileResources::TASKS, FileAction::UPDATE);

    $stageTone = fn (TaskStage $stage): string => match ($stage) {
        TaskStage::INTRODUCTION => '--fg-4',
        TaskStage::FOLLOW_UP_1 => '--primary-base',
        TaskStage::FOLLOW_UP_2 => '--info-base',
        TaskStage::PROCEED => '--warning-base',
        TaskStage::SUCCESS => '--success-base',
        TaskStage::FAILURE => '--danger-base',
    };
    $priorityTone = fn (TaskPriority $priority): string => match ($priority) {
        TaskPriority::LOW => '--fg-4',
        TaskPriority::MEDIUM => '--warning-base',
        TaskPriority::HIGH => '--danger-base',
    };

    $chartData = [
        'trend' => [
            'labels' => $monthlyTrend->pluck('label')->values()->all(),
            'created' => $monthlyTrend->pluck('created')->values()->all(),
            'closed' => $monthlyTrend->pluck('closed')->values()->all(),
            'createdLabel' => __('task_dashboard.labels.created'),
            'closedLabel' => __('task_dashboard.labels.closed'),
        ],
        'stages' => [
            'labels' => $stageSummary->map(fn ($item) => __('tasks.stage.' . $item['stage']->value))->values()->all(),
            'values' => $stageSummary->pluck('total')->values()->all(),
            'colors' => $stageSummary->map(fn ($item) => $stageTone($item['stage']))->values()->all(),
        ],
        'priorities' => [
            'labels' => $prioritySummary->map(fn ($item) => __('tasks.priority.' . $item['priority']->value))->values()->all(),
            'values' => $prioritySummary->pluck('total')->values()->all(),
            'colors' => $prioritySummary->map(fn ($item) => $priorityTone($item['priority']))->values()->all(),
        ],
    ];

    $initials = function (?string $name): string {
        $parts = preg_split('/\s+/', trim($name ?? '')) ?: [];
        $letters = collect($parts)->filter()->take(2)->map(fn ($part) => mb_substr($part, 0, 1))->implode('');

        return mb_strtoupper($letters) ?: '?';
    };
@endphp

@props([
    'summary',
    'stageSummary',
    'prioritySummary',
    'attentionTasks',
    'myTasks',
    'recentClosedTasks',
    'monthlyTrend',
    'workload',
    'today',
])

@extends('layouts.app')

@section('content')
    <div class="td-page">
        <header class="td-header">
            <div>
                <div class="td-eyebrow"><span class="td-eyebrow-dot"></span>{{ __('task_dashboard.eyebrow') }}</div>
                <h1 class="td-title">{{ __('task_dashboard.title') }}</h1>
                <p class="td-subtitle">{{ __('task_dashboard.subtitle') }}</p>
            </div>
            <div class="td-header-side">
                <span class="td-date-chip">
                    <i data-lucide="calendar-days"></i>
                    {{ $today->copy()->locale(app()->getLocale())->translatedFormat('D, d M Y') }}
                </span>
                <a class="btn btn-solid theme-primary" href="{{ route('personnel.tasks.index') }}">
                    {{ __('task_dashboard.actions.open_board') }}
                    <i class="ms-1" data-lucide="arrow-up-right"></i>
                </a>
            </div>
        </header>

        <div class="row g-3 td-kpis">
            @foreach ([
                ['label' => __('task_dashboard.summary.open'), 'value' => $summary['open'], 'note' => __('task_dashboard.summary.open_note'), 'icon' => 'list-checks', 'tone' => 'primary'],
                ['label' => __('task_dashboard.summary.overdue'), 'value' => $summary['overdue'], 'note' => __('task_dashboard.summary.overdue_note'), 'icon' => 'triangle-alert', 'tone' => 'danger'],
                ['label' => __('task_dashboard.summary.due_today'), 'value' => $summary['due_today'], 'note' => __('task_dashboard.summary.due_today_note'), 'icon' => 'calendar-clock', 'tone' => 'warning'],
                ['label' => __('task_dashboard.summary.completed'), 'value' => $summary['completed_this_month'], 'note' => __('task_dashboard.summary.completed_note'), 'icon' => 'circle-check-big', 'tone' => 'success'],
            ] as $kpi)
                <div class="col-12 col-sm-6 xl:col-3">
                    <div class="td-kpi td-kpi--{{ $kpi['tone'] }}">
                        <div class="td-kpi-top">
                            <span class="td-kpi-label">{{ $kpi['label'] }}</span>
                            <span class="td-kpi-icon"><i data-lucide="{{ $kpi['icon'] }}"></i></span>
                        </div>
                        <div class="td-kpi-value">{{ number_format($kpi['value']) }}</div>
                        <div class="td-kpi-note">{{ $kpi['note'] }}</div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="row g-3 mt-1">
            <div class="col-12 xl:col-8">
                <section class="td-panel td-col-panel">
                    <div class="td-panel-head">
                        <div>
                            <h2>{{ __('task_dashboard.sections.throughput') }}</h2>
                            <p>{{ __('task_dashboard.sections.throughput_note') }}</p>
                        </div>
                        <span class="td-tag">{{ __('task_dashboard.labels.last_6_months') }}</span>
                    </div>
                    <div class="td-panel-body">
                        <div class="td-chart"><canvas id="td-trend-chart"></canvas></div>
                        <div class="td-chart-legend">
                            <span><i class="td-legend-dot" style="--td-tone: var(--primary-base)"></i>{{ __('task_dashboard.labels.created') }}</span>
                            <span><i class="td-legend-dot" style="--td-tone: var(--success-base)"></i>{{ __('task_dashboard.labels.closed') }}</span>
                        </div>
                    </div>
                </section>
            </div>

            <div class="col-12 xl:col-4">
                <section class="td-panel td-col-panel">
                    <div class="td-panel-head">
                        <div>
                            <h2>{{ __('task_dashboard.sections.pipeline') }}</h2>
                            <p>{{ __('task_dashboard.sections.pipeline_note') }}</p>
                        </div>
                    </div>
                    <div class="td-panel-body">
                        <div class="td-doughnut-wrap">
                            <div class="td-chart td-chart--doughnut"><canvas id="td-stage-chart"></canvas></div>
                            <div class="td-doughnut-center">
                                <strong>{{ number_format($stageSummary->sum('total')) }}</strong>
                                <small>{{ trans_choice('task_dashboard.labels.tasks', $stageSummary->sum('total'), ['count' => $stageSummary->sum('total')]) }}</small>
                            </div>
                        </div>
                        <ul class="td-legend-list">
                            @foreach ($stageSummary as $item)
                                <li style="--td-tone: var({{ $stageTone($item['stage']) }})">
                                    <span class="td-legend-dot"></span>
                                    <span class="td-legend-label">{{ __('tasks.stage.' . $item['stage']->value) }}</span>
                                    <span class="td-legend-value">{{ $item['total'] }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </section>
            </div>
        </div>

        <div class="row g-3 mt-1">
            <div class="col-12 xl:col-7">
                <section class="td-panel td-col-panel">
                    <div class="td-panel-head">
                        <div>
                            <h2>{{ __('task_dashboard.sections.attention') }}</h2>
                            <p>{{ __('task_dashboard.sections.attention_note') }}</p>
                        </div>
                        <span class="td-count">{{ $attentionTasks->count() }}</span>
                    </div>
                    <div class="td-panel-body td-panel-body--flush">
                        <div class="td-list">
                            @forelse ($attentionTasks as $task)
                                @php
                                    $dayDifference = (int) $today->diffInDays($task->due_date, false);
                                    $dueTone = $dayDifference < 0 ? 'danger' : ($dayDifference === 0 ? 'warning' : 'muted');
                                    $dueText = $dayDifference < 0
                                        ? trans_choice('task_dashboard.labels.overdue_by', abs($dayDifference), ['days' => abs($dayDifference)])
                                        : ($dayDifference === 0
                                            ? __('task_dashboard.labels.due_today')
                                            : trans_choice('task_dashboard.labels.due_in', $dayDifference, ['days' => $dayDifference]));
                                @endphp
                                <div class="td-task" style="--td-tone: var({{ $priorityTone($task->priority) }})">
                                    <span class="td-priority-bar"></span>
                                    <span class="td-avatar">{{ $initials($task->assignee->name ?? null) }}</span>
                                    <div class="td-task-main">
                                        <div class="td-task-title">{{ $task->title }}</div>
                                        <div class="td-meta">
                                            <span>{{ $task->assignee->name ?? __('task_dashboard.labels.unassigned') }}</span>
                                            <span class="td-meta-separator">&middot;</span>
                                            <span>{{ __('tasks.stage.' . $task->stage->value) }}</span>
                                        </div>
                                    </div>
                                    <div class="td-task-side">
                                        <span class="td-due td-due--{{ $dueTone }}">{{ $dueText }}</span>
                                        @if ($canEditTask)
                                            <a class="td-icon-btn" href="{{ route('personnel.tasks.edit', $task) }}"
                                                title="{{ __('common.actions.edit') }}">
                                                <i data-lucide="square-pen"></i>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="td-empty">
                                    <i data-lucide="badge-check"></i>
                                    <p>{{ __('task_dashboard.empty.attention') }}</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </section>
            </div>

            <div class="col-12 xl:col-5">
                <section class="td-panel mb-3">
                    <div class="td-panel-head">
                        <div>
                            <h2>{{ __('task_dashboard.sections.priority') }}</h2>
                            <p>{{ __('task_dashboard.sections.priority_note') }}</p>
                        </div>
                    </div>
                    <div class="td-panel-body">
                        <div class="td-chart td-chart--bars"><canvas id="td-priority-chart"></canvas></div>
                    </div>
                </section>

                <section class="td-panel">
                    <div class="td-panel-head">
                        <div>
                            <h2>{{ __('task_dashboard.sections.workload') }}</h2>
                            <p>{{ __('task_dashboard.sections.workload_note') }}</p>
                        </div>
                    </div>
                    <div class="td-panel-body">
                        @forelse ($workload as $item)
                            <div class="td-stat-row">
                                <div class="td-stat-head">
                                    <span class="td-stat-name">{{ $item['name'] }}</span>
                                    <span class="td-stat-value">{{ $item['total'] }}</span>
                                </div>
                                <div class="td-stat-track">
                                    <div class="td-stat-fill" style="width: {{ round(($item['total'] / max(1, $workload->max('total'))) * 100) }}%"></div>
                                </div>
                            </div>
                        @empty
                            <p class="td-inline-empty">{{ __('task_dashboard.labels.no_workload') }}</p>
                        @endforelse
                    </div>
                </section>
            </div>
        </div>

        <div class="row g-3 mt-1">
            <div class="col-12 lg:col-6">
                <section class="td-panel td-col-panel">
                    <div class="td-panel-head">
                        <div>
                            <h2>{{ __('task_dashboard.sections.my_tasks') }}</h2>
                            <p>{{ __('task_dashboard.sections.my_tasks_note') }}</p>
                        </div>
                    </div>
                    <div class="td-panel-body td-panel-body--flush">
                        @forelse ($myTasks as $task)
                            <div class="td-compact">
                                <div class="td-compact-main">
                                    <span class="td-compact-title">{{ $task->title }}</span>
                                    <span class="td-meta">{{ __('tasks.stage.' . $task->stage->value) }} &middot; {{ $task->due_date?->format('d M Y') ?? __('task_dashboard.labels.no_due_date') }}</span>
                                </div>
                                @if ($canEditTask)
                                    <a class="td-icon-btn" href="{{ route('personnel.tasks.edit', $task) }}"
                                        title="{{ __('common.actions.edit') }}">
                                        <i data-lucide="square-pen"></i>
                                    </a>
                                @endif
                            </div>
                        @empty
                            <p class="td-inline-empty">{{ __('task_dashboard.empty.my_tasks') }}</p>
                        @endforelse
                    </div>
                </section>
            </div>

            <div class="col-12 lg:col-6">
                <section class="td-panel td-col-panel">
                    <div class="td-panel-head">
                        <div>
                            <h2>{{ __('task_dashboard.sections.recent_closed') }}</h2>
                            <p>{{ __('task_dashboard.sections.recent_closed_note') }}</p>
                        </div>
                    </div>
                    <div class="td-panel-body td-panel-body--flush">
                        @forelse ($recentClosedTasks as $task)
                            <div class="td-compact">
                                <div class="td-compact-main">
                                    <span class="td-compact-title">{{ $task->title }}</span>
                                    <span class="td-meta">{{ __('task_dashboard.labels.closed_on', ['date' => $task->closed_at->format('d M Y')]) }}</span>
                                </div>
                                <span class="td-stage-pill" style="--td-tone: var({{ $stageTone($task->stage) }})">
                                    {{ __('tasks.stage.' . $task->stage->value) }}
                                </span>
                            </div>
                        @empty
                            <p class="td-inline-empty">{{ __('task_dashboard.empty.recent_closed') }}</p>
                        @endforelse
                    </div>
                </section>
            </div>
        </div>
    </div>

    <style>
        .td-page {
            --td-radius: 1.05rem;
            padding-bottom: 1.5rem;
        }

        .td-header {
            align-items: flex-end;
            background: var(--bg-1);
            border: 1px solid var(--border-color);
            border-radius: var(--td-radius);
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            justify-content: space-between;
            margin-bottom: 1.1rem;
            padding: 1.1rem 1.25rem;
        }

        .td-eyebrow {
            align-items: center;
            color: var(--fg-2);
            display: flex;
            font-size: .66rem;
            font-weight: 700;
            gap: .55rem;
            letter-spacing: .14em;
            text-transform: uppercase;
        }

        .td-eyebrow-dot {
            background: var(--primary-base);
            border-radius: 50%;
            box-shadow: 0 0 0 4px color-mix(in srgb, var(--primary-base) 14%, transparent);
            height: 6px;
            width: 6px;
        }

        .td-title {
            font-size: 1.65rem;
            font-weight: 750;
            letter-spacing: -.035em;
            line-height: 1.2;
            margin: .35rem 0 .2rem;
        }

        .td-subtitle {
            color: var(--fg-2);
            font-size: .85rem;
            margin: 0;
        }

        .td-header-side {
            align-items: center;
            display: flex;
            flex-wrap: wrap;
            gap: .6rem;
        }

        .td-date-chip {
            align-items: center;
            background: var(--bg-1);
            border: 1px solid var(--border-color);
            border-radius: 999px;
            color: var(--fg-2);
            display: inline-flex;
            font-size: .76rem;
            gap: .45rem;
            padding: .5rem .85rem;
        }

        .td-date-chip svg {
            height: .95rem;
            width: .95rem;
        }

        .td-kpi {
            background: var(--bg-1);
            border: 1px solid var(--border-color);
            border-radius: var(--td-radius);
            height: 100%;
            overflow: hidden;
            padding: 1.1rem 1.2rem;
            position: relative;
        }

        .td-kpi::before {
            background: var(--td-accent);
            bottom: 0;
            content: '';
            left: 0;
            position: absolute;
            top: 0;
            width: 3px;
        }

        .td-kpi--primary { --td-accent: var(--primary-base); }
        .td-kpi--danger { --td-accent: var(--danger-base); }
        .td-kpi--warning { --td-accent: var(--warning-base); }
        .td-kpi--success { --td-accent: var(--success-base); }

        .td-kpi-top {
            align-items: flex-start;
            display: flex;
            justify-content: space-between;
        }

        .td-kpi-label {
            color: var(--fg-2);
            font-size: .7rem;
            font-weight: 700;
            letter-spacing: .07em;
            text-transform: uppercase;
        }

        .td-kpi-icon {
            align-items: center;
            background: color-mix(in srgb, var(--td-accent) 12%, var(--bg-2));
            border-radius: .8rem;
            color: var(--td-accent);
            display: grid;
            height: 2.4rem;
            justify-content: center;
            width: 2.4rem;
        }

        .td-kpi-icon svg { height: 1.1rem; width: 1.1rem; }

        .td-kpi-value {
            font-size: 2.1rem;
            font-weight: 750;
            letter-spacing: -.045em;
            line-height: 1.1;
            margin: .7rem 0 .25rem;
        }

        .td-kpi-note {
            color: var(--fg-2);
            font-size: .74rem;
        }

        .td-panel {
            background: var(--bg-1);
            border: 1px solid var(--border-color);
            border-radius: var(--td-radius);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .td-col-panel {
            height: 100%;
        }

        .td-panel-head {
            align-items: flex-start;
            background: color-mix(in srgb, var(--bg-2) 55%, var(--bg-1));
            border-bottom: 1px solid var(--border-color);
            display: flex;
            gap: 1rem;
            justify-content: space-between;
            padding: 1rem 1.2rem;
        }

        .td-panel-head h2 {
            font-size: .93rem;
            font-weight: 700;
            letter-spacing: -.01em;
            margin: 0;
        }

        .td-panel-head p {
            color: var(--fg-2);
            font-size: .73rem;
            margin: .2rem 0 0;
        }

        .td-panel-body {
            flex: 1;
            padding: 1.1rem 1.2rem;
        }

        .td-panel-body--flush { padding: .35rem 1.2rem .6rem; }

        .td-tag,
        .td-count {
            background: var(--bg-2);
            border: 1px solid var(--border-color);
            border-radius: 999px;
            color: var(--fg-2);
            flex-shrink: 0;
            font-size: .7rem;
            font-weight: 650;
            padding: .3rem .65rem;
            white-space: nowrap;
        }

        .td-count {
            background: color-mix(in srgb, var(--danger-base) 12%, var(--bg-1));
            border-color: color-mix(in srgb, var(--danger-base) 24%, transparent);
            color: color-mix(in srgb, var(--danger-base) 72%, var(--fg-body));
            font-size: .8rem;
        }

        .td-chart {
            height: 244px;
            position: relative;
        }

        .td-chart--doughnut { height: 190px; }
        .td-chart--bars { height: 150px; }

        .td-chart-legend {
            border-top: 1px solid var(--border-color);
            display: flex;
            gap: 1.25rem;
            justify-content: center;
            margin-top: .6rem;
            padding-top: .75rem;
        }

        .td-chart-legend span {
            align-items: center;
            color: var(--fg-2);
            display: inline-flex;
            font-size: .74rem;
            gap: .45rem;
        }

        .td-legend-dot {
            background: var(--td-tone);
            border-radius: 50%;
            display: inline-block;
            flex-shrink: 0;
            height: 7px;
            width: 7px;
        }

        .td-doughnut-wrap {
            position: relative;
        }

        .td-doughnut-center {
            align-items: center;
            display: flex;
            flex-direction: column;
            inset: 0;
            justify-content: center;
            pointer-events: none;
            position: absolute;
        }

        .td-doughnut-center strong {
            font-size: 1.55rem;
            font-weight: 750;
            letter-spacing: -.04em;
            line-height: 1;
        }

        .td-doughnut-center small {
            color: var(--fg-2);
            font-size: .68rem;
            margin-top: .2rem;
        }

        .td-legend-list {
            list-style: none;
            margin: .75rem 0 0;
            padding: 0;
        }

        .td-legend-list li {
            align-items: center;
            border-top: 1px solid var(--border-color);
            display: flex;
            gap: .6rem;
            padding: .55rem 0;
        }

        .td-legend-label {
            flex: 1;
            font-size: .78rem;
        }

        .td-legend-value {
            font-size: .78rem;
            font-weight: 700;
        }

        .td-task {
            align-items: center;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            gap: .85rem;
            min-width: 0;
            padding: .85rem 0;
        }

        .td-task:last-child { border-bottom: 0; }

        .td-priority-bar {
            align-self: stretch;
            background: var(--td-tone);
            border-radius: 999px;
            flex: 0 0 3px;
        }

        .td-avatar {
            align-items: center;
            background: color-mix(in srgb, var(--primary-base) 13%, var(--bg-2));
            border-radius: 50%;
            color: var(--primary-base);
            display: grid;
            flex-shrink: 0;
            font-size: .66rem;
            font-weight: 700;
            height: 2rem;
            justify-content: center;
            width: 2rem;
        }

        .td-task-main { flex: 1; min-width: 0; }

        .td-task-title,
        .td-compact-title {
            display: block;
            font-size: .84rem;
            font-weight: 650;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .td-meta {
            color: var(--fg-2);
            display: flex;
            flex-wrap: wrap;
            font-size: .71rem;
            gap: .35rem;
            margin-top: .2rem;
        }

        .td-meta-separator { opacity: .5; }

        .td-task-side {
            align-items: center;
            display: flex;
            flex-shrink: 0;
            gap: .5rem;
        }

        .td-due {
            border-radius: 999px;
            font-size: .68rem;
            font-weight: 650;
            padding: .28rem .6rem;
            white-space: nowrap;
        }

        .td-due--danger {
            background: color-mix(in srgb, var(--danger-base) 12%, var(--bg-1));
            color: color-mix(in srgb, var(--danger-base) 72%, var(--fg-body));
        }

        .td-due--warning {
            background: color-mix(in srgb, var(--warning-base) 14%, var(--bg-1));
            color: color-mix(in srgb, var(--warning-base) 72%, var(--fg-body));
        }

        .td-due--muted {
            background: var(--bg-2);
            color: var(--fg-2);
        }

        .td-icon-btn {
            align-items: center;
            border: 1px solid var(--border-color);
            border-radius: .55rem;
            color: var(--fg-2);
            display: inline-grid;
            height: 1.9rem;
            justify-content: center;
            text-decoration: none;
            width: 1.9rem;
        }

        .td-icon-btn:hover {
            background: var(--primary-bg-subtle);
            color: var(--primary-base);
        }

        .td-icon-btn svg { height: .9rem; width: .9rem; }

        .td-stat-row { padding: .55rem 0 .7rem; }
        .td-stat-row + .td-stat-row { border-top: 1px solid var(--border-color); }

        .td-stat-head {
            align-items: baseline;
            display: flex;
            justify-content: space-between;
            margin-bottom: .45rem;
        }

        .td-stat-name { font-size: .78rem; font-weight: 600; }
        .td-stat-value { font-size: .78rem; font-weight: 700; }

        .td-stat-track {
            background: var(--bg-2);
            border-radius: 999px;
            height: 5px;
            overflow: hidden;
        }

        .td-stat-fill {
            background: var(--primary-base);
            border-radius: 999px;
            height: 100%;
        }

        .td-compact {
            align-items: center;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            gap: .75rem;
            justify-content: space-between;
            padding: .8rem 0;
        }

        .td-compact:last-child { border-bottom: 0; }
        .td-compact-main { min-width: 0; }
        .td-compact .td-meta { display: block; }

        .td-stage-pill {
            background: color-mix(in srgb, var(--td-tone) 12%, var(--bg-1));
            border-radius: 999px;
            color: color-mix(in srgb, var(--td-tone) 72%, var(--fg-body));
            flex-shrink: 0;
            font-size: .68rem;
            font-weight: 650;
            padding: .3rem .6rem;
        }

        .td-empty {
            align-items: center;
            color: var(--fg-2);
            display: flex;
            flex-direction: column;
            gap: .5rem;
            padding: 2.25rem 0;
            text-align: center;
        }

        .td-empty svg {
            color: var(--success-base);
            height: 1.75rem;
            width: 1.75rem;
        }

        .td-empty p { font-size: .8rem; margin: 0; }

        .td-inline-empty {
            color: var(--fg-2);
            font-size: .78rem;
            margin: 0;
            padding: 1.25rem 0;
            text-align: center;
        }

        @media (max-width: 767.98px) {
            .td-header { align-items: flex-start; }
            .td-task { align-items: flex-start; }
            .td-task-side { flex-direction: column; align-items: flex-end; }
            .td-chart { height: 210px; }
        }
    </style>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (!window.Chart) {
                return;
            }

            const styles = getComputedStyle(document.documentElement);
            const css = (name, fallback) => (styles.getPropertyValue(name) || fallback).trim();
            const grid = 'rgba(128, 128, 128, 0.14)';

            Chart.defaults.font.family = css('--body-font-family', 'Inter, sans-serif');
            Chart.defaults.color = css('--fg-2', '#6b7280');
            Chart.defaults.font.size = 11;

            const data = @json($chartData);

            const trendCanvas = document.getElementById('td-trend-chart');
            if (trendCanvas) {
                new Chart(trendCanvas, {
                    type: 'line',
                    data: {
                        labels: data.trend.labels,
                        datasets: [
                            {
                                label: data.trend.createdLabel,
                                data: data.trend.created,
                                borderColor: css('--primary-base', '#5865f2'),
                                backgroundColor: css('--primary-base', '#5865f2'),
                                borderWidth: 2,
                                tension: 0.35,
                                pointRadius: 2,
                                pointHoverRadius: 4,
                                pointBackgroundColor: css('--bg-1', '#fff'),
                                pointBorderWidth: 2,
                            },
                            {
                                label: data.trend.closedLabel,
                                data: data.trend.closed,
                                borderColor: css('--success-base', '#198754'),
                                backgroundColor: css('--success-base', '#198754'),
                                borderWidth: 2,
                                borderDash: [5, 4],
                                tension: 0.35,
                                pointRadius: 2,
                                pointHoverRadius: 4,
                                pointBackgroundColor: css('--bg-1', '#fff'),
                                pointBorderWidth: 2,
                            },
                        ],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: { mode: 'index', intersect: false },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: css('--bg-1', '#fff'),
                                titleColor: css('--fg-body', '#111'),
                                bodyColor: css('--fg-2', '#666'),
                                borderColor: css('--border-color', '#ddd'),
                                borderWidth: 1,
                                padding: 10,
                                displayColors: true,
                            },
                        },
                        scales: {
                            x: { grid: { display: false }, border: { display: false } },
                            y: {
                                beginAtZero: true,
                                grid: { color: grid },
                                border: { display: false },
                                ticks: { precision: 0, maxTicksLimit: 5 },
                            },
                        },
                    },
                });
            }

            const stageCanvas = document.getElementById('td-stage-chart');
            if (stageCanvas) {
                new Chart(stageCanvas, {
                    type: 'doughnut',
                    data: {
                        labels: data.stages.labels,
                        datasets: [{
                            data: data.stages.values,
                            backgroundColor: data.stages.colors.map((name) => css(name, '#8c8c8c')),
                            borderWidth: 0,
                            borderRadius: 3,
                            spacing: 2,
                            hoverOffset: 5,
                        }],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '72%',
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: css('--bg-1', '#fff'),
                                titleColor: css('--fg-body', '#111'),
                                bodyColor: css('--fg-2', '#666'),
                                borderColor: css('--border-color', '#ddd'),
                                borderWidth: 1,
                                padding: 10,
                            },
                        },
                    },
                });
            }

            const priorityCanvas = document.getElementById('td-priority-chart');
            if (priorityCanvas) {
                new Chart(priorityCanvas, {
                    type: 'bar',
                    data: {
                        labels: data.priorities.labels,
                        datasets: [{
                            data: data.priorities.values,
                            backgroundColor: data.priorities.colors.map((name) => css(name, '#8c8c8c')),
                            borderRadius: 5,
                            barThickness: 14,
                        }],
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: css('--bg-1', '#fff'),
                                titleColor: css('--fg-body', '#111'),
                                bodyColor: css('--fg-2', '#666'),
                                borderColor: css('--border-color', '#ddd'),
                                borderWidth: 1,
                                padding: 10,
                            },
                        },
                        scales: {
                            x: {
                                beginAtZero: true,
                                grid: { color: grid },
                                border: { display: false },
                                ticks: { precision: 0, maxTicksLimit: 4 },
                            },
                            y: { grid: { display: false }, border: { display: false } },
                        },
                    },
                });
            }
        });
    </script>
@endpush
