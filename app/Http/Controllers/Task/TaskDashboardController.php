<?php

/**
 * ============================================================================
 *
 * @author      redfluz
 *
 * @github      https://github.com/redfluz
 *
 * @youtube     https://youtube.com/@redfluz
 *
 * @contact     corex2020@icloud.com
 *
 * @customer
 *
 * @copyright   Copyright (c) 2026 redfluz. All Rights Reserved.
 * ============================================================================
 *
 * PROPRIETARY AND CONFIDENTIAL
 *
 * All code, concepts, and intellectual property contained in this file are the
 * exclusive property of redfluz. Unauthorized copying, distribution, modification,
 * reverse engineering, or public display of this software via any medium is
 * strictly prohibited without explicit written permission from the copyright owner.
 */

namespace App\Http\Controllers\Task;

use App\Enums\FileResources;
use App\Enums\Task\TaskPriority;
use App\Enums\Task\TaskStage;
use App\Http\Controllers\Controller;
use App\Models\System\User;
use App\Models\Task\Task;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

use function is_string;

class TaskDashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     * Display the task dashboard.
     *
     * @return View Returns the rendered Blade view.
     */
    public function index(): View
    {
        $this->allows(FileResources::TASK_DASHBOARD);

        $today = today();
        $openStages = collect(TaskStage::cases())
            ->reject(fn (TaskStage $stage): bool => $stage->isClosed())
            ->map(fn (TaskStage $stage): string => $stage->value)
            ->values()
            ->all();
        $closedStages = [
            TaskStage::SUCCESS->value,
            TaskStage::FAILURE->value,
        ];

        $openTasks = $this->departmentTaskQuery()->whereIn('stage', $openStages);

        $summary = [
            'open' => (clone $openTasks)->count(),
            'overdue' => (clone $openTasks)
                ->whereDate('due_date', '<', $today)
                ->count(),
            'due_today' => (clone $openTasks)
                ->whereDate('due_date', $today)
                ->count(),
            'completed_this_month' => $this->departmentTaskQuery()
                ->whereIn('stage', $closedStages)
                ->whereBetween('closed_at', [$today->copy()->startOfMonth(), $today->copy()->endOfMonth()])
                ->count(),
        ];

        $stageCounts = $this->departmentTaskQuery()
            ->selectRaw('stage, COUNT(*) as total')
            ->groupBy('stage')
            ->pluck('total', 'stage');

        $stageSummary = collect(TaskStage::cases())
            ->map(fn (TaskStage $stage): array => [
                'stage' => $stage,
                'total' => is_numeric($stageCounts[$stage->value] ?? null) ? (int) $stageCounts[$stage->value] : 0,
            ]);

        $priorityCounts = $this->departmentTaskQuery()
            ->whereIn('stage', $openStages)
            ->selectRaw('priority, COUNT(*) as total')
            ->groupBy('priority')
            ->pluck('total', 'priority');

        $prioritySummary = collect(TaskPriority::cases())
            ->map(fn (TaskPriority $priority): array => [
                'priority' => $priority,
                'total' => is_numeric($priorityCounts[$priority->value] ?? null) ? (int) $priorityCounts[$priority->value] : 0,
            ]);

        /** @var Collection<int, Task> $attentionTasks */
        $attentionTasks = $this->taskListQuery()
            ->whereIn('stage', $openStages)
            ->whereNotNull('due_date')
            ->whereDate('due_date', '<=', $today->copy()->addDays(7))
            ->orderBy('due_date')
            ->orderByRaw("CASE priority WHEN 'high' THEN 1 WHEN 'medium' THEN 2 WHEN 'low' THEN 3 ELSE 4 END")
            ->limit(8)
            ->get();

        /** @var Collection<int, Task> $myTasks */
        $myTasks = $this->taskListQuery()
            ->whereIn('stage', $openStages)
            ->where('assigned_to', auth()->id())
            ->orderByRaw('due_date IS NULL')
            ->orderBy('due_date')
            ->limit(6)
            ->get();

        /** @var Collection<int, Task> $recentClosedTasks */
        $recentClosedTasks = $this->taskListQuery()
            ->whereIn('stage', $closedStages)
            ->whereNotNull('closed_at')
            ->latest('closed_at')
            ->limit(6)
            ->get();

        // Six-month created vs closed trend, scoped to the same department rules.
        $trendStart = $today->copy()->startOfMonth()->subMonths(5);
        $trendTasks = $this->departmentTaskQuery()
            ->where('created_at', '>=', $trendStart)
            ->get(['created_at', 'closed_at']);

        $monthlyTrend = collect(range(0, 5))->map(function (int $offset) use ($today, $trendTasks): array {
            $month = $today->copy()->startOfMonth()->subMonths(5 - $offset);
            $start = $month->copy()->startOfMonth();
            $end = $month->copy()->endOfMonth();

            $month->locale(app()->getLocale());

            return [
                'label' => $month->translatedFormat('M'),
                'created' => $trendTasks->filter(fn (Task $task): bool => $task->created_at->between($start, $end))->count(),
                'closed' => $trendTasks->filter(fn (Task $task): bool => $task->closed_at?->between($start, $end) ?? false)->count(),
            ];
        });

        // Open workload by assignee, including the unassigned bucket.
        $openAssigneeIds = $this->departmentTaskQuery()
            ->whereIn('stage', $openStages)
            ->pluck('assigned_to');
        $assigneeNames = User::query()
            ->whereIn('id', $openAssigneeIds->filter())
            ->pluck('name', 'id');

        $workload = $openAssigneeIds->countBy()
            ->sortDesc()
            ->take(5)
            ->map(fn (int $total, ?string $id): array => [
                'name' => is_string($id) && isset($assigneeNames[$id])
                    ? $assigneeNames[$id]
                    : __('task_dashboard.labels.unassigned'),
                'total' => $total,
            ])
            ->values();

        return view('task.dashboard.index', compact(
            'summary',
            'stageSummary',
            'prioritySummary',
            'attentionTasks',
            'myTasks',
            'recentClosedTasks',
            'monthlyTrend',
            'workload',
            'today',
        ));
    }

    /**
     * Build the common task query used by dashboard lists.
     *
     * @return Builder<Task> The task query builder with eager-loaded relations.
     */
    private function taskListQuery(): Builder
    {
        return $this->departmentTaskQuery()->with([
            'assignee.department',
        ]);
    }

    /**
     * Scope dashboard figures using the signed-in user's organisation position.
     *
     * @return Builder<Task> The department-scoped task query builder.
     */
    private function departmentTaskQuery(): Builder
    {
        $user = auth()->user();
        if (! $user instanceof User) {
            return Task::query()->whereRaw('1 = 0');
        }
        $scope = $user->organization()->value('task_scope') ?? 'own';
        if ($scope === 'system') {
            return Task::query();
        }
        if ($scope === 'department' && filled($user->department_id)) {
            return Task::query()->where(function (Builder $departmentTasks) use ($user): void {
                $departmentTasks
                    ->whereHas('assignee', fn (Builder $assignee) => $assignee
                        ->where('department_id', $user->department_id))
                    ->orWhere(function (Builder $unassigned) use ($user): void {
                        $unassigned
                            ->whereNull('assigned_to')
                            ->whereHas('creator', fn (Builder $creator) => $creator
                                ->where('department_id', $user->department_id));
                    });
            });
        }

        return Task::query()->where(function (Builder $ownTasks) use ($user): void {
            $ownTasks
                ->where('assigned_to', $user->id)
                ->orWhere('created_by', $user->id);
        });
    }
}
