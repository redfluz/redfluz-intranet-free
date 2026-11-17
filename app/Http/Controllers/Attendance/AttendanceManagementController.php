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

namespace App\Http\Controllers\Attendance;

use App\Enums\FileResources;
use App\Http\Controllers\Controller;
use App\Models\Attendance\Attendance;
use App\Models\Management\Department;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

/**
 * Human Resources view of employee punch-in and punch-out records.
 */
class AttendanceManagementController extends Controller
{
    /**
     * Display a paginated grid of attendance records with filtering options.
     *
     * @param  Request  $request  The HTTP request instance containing query filters.
     * @return View The rendered Blade view.
     */
    public function index(Request $request): View
    {
        $this->allows(FileResources::ATTENDANCE_MANAGEMENTS);

        $search = $request->string('search')->toString() ?: null;
        $department = $request->string('department')->toString() ?: null;
        $status = $request->string('status')->toString() ?: null;
        $startDate = $request->string('start_date')->toString() ?: null;
        $endDate = $request->string('end_date')->toString() ?: null;

        $attendances = Attendance::query()
            ->with(['user.department'])
            ->when($search !== null, function ($query) use ($search): void {
                $query->whereHas('user', function ($userQuery) use ($search): void {
                    $userQuery
                        ->where('name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%");
                });
            })
            ->when($department !== null, fn ($query) => $query
                ->whereHas('user', fn ($userQuery) => $userQuery->where('department_id', $department)))
            ->when($status === 'completed', fn ($query) => $query->whereNotNull('clock_out'))
            ->when($status === 'in_progress', fn ($query) => $query
                ->whereNotNull('clock_in')->whereNull('clock_out'))
            ->when($status === 'missing', fn ($query) => $query->whereNull('clock_in'))
            ->when($startDate !== null, fn ($query) => $query->whereDate('work_date', '>=', $startDate))
            ->when($endDate !== null, fn ($query) => $query->whereDate('work_date', '<=', $endDate))
            ->latest('work_date')
            ->latest('clock_in')
            ->paginate(self::PER_PAGE_GRID_NORMAL)
            ->withQueryString();

        $departments = Department::query()->orderBy('name')->pluck('name', 'id');

        return view('attendance.attendance-managements.index', compact(
            'attendances',
            'departments',
            'search',
            'department',
            'status',
            'startDate',
            'endDate',
        ));
    }
}
