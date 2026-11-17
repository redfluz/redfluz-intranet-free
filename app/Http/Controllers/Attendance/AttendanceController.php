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

use App\Http\Controllers\Controller;
use App\Http\Requests\Attendance\ClockInRequest;
use App\Http\Requests\Attendance\ClockOutRequest;
use App\Http\Requests\Attendance\IndexAttendanceRequest;
use App\Models\Attendance\Attendance;
use App\Support\Data\Attendance\AttendanceData;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * Handles web-based clock-in and clock-out records for the authenticated user.
 */
class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     * Display the authenticated user's attendance records for a selected month.
     *
     * @param  IndexAttendanceRequest  $request  The authorised attendance index request.
     * @return View Returns the rendered attendance view.
     */
    public function index(IndexAttendanceRequest $request): View
    {
        $user = $request->user();
        abort_if($user === null, 401);

        $month = Carbon::createFromFormat(
            'Y-m',
            $request->string('month', now()->format('Y-m'))->toString()
        );
        abort_if($month === null, 422, 'Invalid month.');
        $month = $month->startOfMonth();

        $records = Attendance::query()
            ->where('user_id', $user->id)
            ->whereBetween('work_date', [$month->toDateString(), $month->copy()->endOfMonth()->toDateString()])
            ->latest('work_date')
            ->get();

        $today = Attendance::query()->firstOrCreate(
            ['user_id' => $user->id, 'work_date' => today()],
            ['worked_minutes' => 0]
        );

        return view('attendance.index', compact('records', 'today', 'month'));
    }

    /**
     * Record the authenticated user's clock-in for today.
     *
     * @param  ClockInRequest  $request  The authorised clock-in request.
     * @return RedirectResponse Redirects back to the attendance view.
     */
    public function clockIn(ClockInRequest $request): RedirectResponse
    {
        $user = $request->user();
        abort_if($user === null, 401);

        $record = Attendance::query()->firstOrCreate(
            ['user_id' => $user->id, 'work_date' => today()],
            ['worked_minutes' => 0]
        );

        abort_if($record->clock_in !== null, 422, __('attendance.errors.already_clocked_in'));

        $data = AttendanceData::from(['clock_in' => now()]);

        $record->update([
            'clock_in' => $data->clockIn,
        ]);

        return back()->with('success', __('attendance.messages.clocked_in'));
    }

    /**
     * Record the authenticated user's clock-out for today and calculate worked minutes.
     *
     * @param  ClockOutRequest  $request  The authorised clock-out request.
     * @return RedirectResponse Redirects back to the attendance view.
     */
    public function clockOut(ClockOutRequest $request): RedirectResponse
    {
        $user = $request->user();
        abort_if($user === null, 401);

        $record = Attendance::query()
            ->where('user_id', $user->id)
            ->whereDate('work_date', today())
            ->firstOrFail();

        abort_if($record->clock_in === null, 422, __('attendance.errors.not_clocked_in'));
        abort_if($record->clock_out !== null, 422, __('attendance.errors.already_clocked_out'));

        $record->clock_out = now();
        $record->recalculateWorkedMinutes();
        $record->save();

        return back()->with('success', __('attendance.messages.clocked_out'));
    }
}
