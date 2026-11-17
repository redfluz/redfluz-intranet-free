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

namespace App\Services\Dashboard;

use App\Enums\Claim\ClaimStatus;
use App\Enums\Leave\LeaveStatus;
use App\Models\Claim\Claim;
use App\Models\Leave\Leave;
use App\Models\News\News;
use App\Models\Task\Task;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class DashboardPersonnelService
{
    /**
     * Get paginated published news articles for the primary feed.
     *
     * @param  int  $perPage  The page size.
     * @return LengthAwarePaginator<int, News> The published news page.
     */
    public function getNewsFeed(int $perPage = 10): LengthAwarePaginator
    {
        return News::query()
            ->with('category')
            ->where('status', 'published')
            ->latest('published_at')
            ->paginate($perPage);
    }

    /**
     * Get active tasks assigned to the current user.
     *
     * @param  string  $userId  The assignee UUID.
     * @param  int  $limit  The maximum number of tasks.
     * @return Collection<int, Task> The active tasks.
     */
    public function getMyTasks(string $userId, int $limit = 5): Collection
    {
        return Task::query()
            ->where('assigned_to', $userId)
            ->whereNull('closed_at')
            ->orderBy('due_date', 'asc')
            ->take($limit)
            ->get();
    }

    /**
     * Get a plain leave summary for the current user: pending requests and
     * approved days taken this year. The intranet edition has no entitlements.
     *
     * @param  string  $userId  The user ID.
     * @return array{pending_count: int, approved_days: int}
     */
    public function getLeaveSummary(string $userId): array
    {
        $pendingCount = Leave::query()
            ->where('user_id', $userId)
            ->whereIn('status', [LeaveStatus::PENDING->value, LeaveStatus::SUBMITTED->value])
            ->count();

        $approvedDays = (int) Leave::query()
            ->where('user_id', $userId)
            ->where('status', LeaveStatus::APPROVED->value)
            ->whereYear('start_date', now()->year)
            ->sum('total_days');

        return [
            'pending_count' => $pendingCount,
            'approved_days' => $approvedDays,
        ];
    }

    /**
     * Get recent leave requests for the current user.
     *
     * @param  string  $userId  The applicant UUID.
     * @param  int  $limit  The maximum number of requests.
     * @return Collection<int, Leave> The recent requests.
     */
    public function getRecentLeaves(string $userId, int $limit = 5): Collection
    {
        return Leave::query()
            ->where('user_id', $userId)
            ->latest('start_date')
            ->take($limit)
            ->get();
    }

    /**
     * Get recent claim submissions and pending count for the current user.
     *
     * @param  string  $userId  The user ID.
     * @param  int  $limit  The maximum number of recent claims.
     * @return array{recent: Collection<int, Claim>, pending_count: int}
     */
    public function getClaimSummary(string $userId, int $limit = 5): array
    {
        $recent = Claim::query()
            ->where('user_id', $userId)
            ->latest('receipt_date')
            ->take($limit)
            ->get();

        $pendingCount = Claim::query()
            ->where('user_id', $userId)
            ->whereIn('status', [ClaimStatus::PENDING->value, ClaimStatus::SUBMITTED->value])
            ->count();

        return [
            'recent' => $recent,
            'pending_count' => $pendingCount,
        ];
    }
}
