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

namespace App\Http\Controllers\Leave;

use App\Constants\FlashMessage;
use App\Enums\FileAction;
use App\Enums\FileResources;
use App\Enums\Leave\LeaveStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Leave\LeaveManagement\RejectLeaveRequest;
use App\Models\Leave\Leave;
use App\Notifications\SystemAlert;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use function in_array;

/**
 * Administrator view of employee leave applications. The intranet edition
 * keeps the workflow to a single decision: approve or reject. There are no
 * leave types or entitlement settings.
 */
class LeaveManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     * Display a paginated grid of leave applications with filtering options.
     *
     * @param  Request  $request  The HTTP request instance containing query filters.
     * @return View The rendered Blade view.
     */
    public function index(Request $request): View
    {
        $this->allows(FileResources::LEAVE_MANAGEMENTS);

        $search = $request->string('search')->toString() ?: null;
        $status = $request->string('status')->toString() ?: null;
        $startDate = $request->string('start_date')->toString() ?: null;
        $endDate = $request->string('end_date')->toString() ?: null;

        $leaves = Leave::query()
            ->with(['employee'])
            ->when($search !== null, function ($q) use ($search) {
                $q->where(function ($sq) use ($search) {
                    $sq->where('reference_number', 'like', "%$search%")
                        ->orWhereHas('employee', fn ($eq) => $eq->where('name', 'like', "%$search%"));
                });
            })
            ->when($status !== null, fn ($q) => $q->where('status', $status))
            ->when($startDate !== null, fn ($q) => $q->whereDate('start_date', '>=', $startDate))
            ->when($endDate !== null, fn ($q) => $q->whereDate('end_date', '<=', $endDate))
            ->latest('start_date')
            ->paginate(self::PER_PAGE_GRID_NORMAL)
            ->withQueryString();

        return view('leave.leave-managements.index', compact('leaves', 'search', 'status', 'startDate', 'endDate'));
    }

    /**
     * Display the specified resource.
     * Show a single leave application with its attachment so the reviewer can
     * inspect the supporting document before approving or rejecting.
     *
     * @param  Leave  $leave  The leave model instance.
     * @return View The rendered Blade view.
     */
    public function show(Leave $leave): View
    {
        $this->allows(FileResources::LEAVE_MANAGEMENTS);

        $leave->load(['employee.role', 'approver']);

        return view('leave.leave-managements.show', compact('leave'));
    }

    /**
     * Approve a pending leave application.
     *
     * @param  Leave  $leave  The leave model instance.
     * @return RedirectResponse Redirect response to the leave management grid.
     */
    public function approve(Leave $leave): RedirectResponse
    {
        $this->allows(FileResources::LEAVE_MANAGEMENTS, FileAction::APPROVE);

        abort_unless(in_array($leave->status, [LeaveStatus::PENDING, LeaveStatus::SUBMITTED], true), 422);

        $leave->update([
            'status' => LeaveStatus::APPROVED->value,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'rejection_reason' => null,
        ]);

        $this->notifyEmployee($leave, LeaveStatus::APPROVED);

        return redirect()
            ->route('leave.leave-managements.index')
            ->with('success', FlashMessage::approved(FileResources::LEAVE_MANAGEMENTS));
    }

    /**
     * Reject a pending leave application with a required reason.
     *
     * @param  RejectLeaveRequest  $request  The validated reject request.
     * @param  Leave  $leave  The leave model instance.
     * @return RedirectResponse Redirect response to the leave management grid.
     */
    public function reject(RejectLeaveRequest $request, Leave $leave): RedirectResponse
    {
        abort_unless(in_array($leave->status, [LeaveStatus::PENDING, LeaveStatus::SUBMITTED], true), 422);

        $rejectionReason = $request->rejectionReason();

        $leave->update([
            'status' => LeaveStatus::REJECTED->value,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'rejection_reason' => $rejectionReason,
        ]);

        $this->notifyEmployee($leave, LeaveStatus::REJECTED, $rejectionReason);

        return redirect()
            ->route('leave.leave-managements.index')
            ->with('success', FlashMessage::rejected(FileResources::LEAVE_MANAGEMENTS));
    }

    /**
     * Notify the employee after a leave decision.
     *
     * @param  Leave  $leave  The decided leave.
     * @param  LeaveStatus  $status  The decision status.
     * @param  string|null  $rejectionReason  The rejection reason, when rejected.
     * @return void Does not return a value.
     */
    private function notifyEmployee(Leave $leave, LeaveStatus $status, ?string $rejectionReason = null): void
    {
        $employee = $leave->employee;
        if (! $employee) {
            return;
        }

        $decision = $status === LeaveStatus::APPROVED ? 'approved' : 'rejected';
        $employee->notify(new SystemAlert(
            __("leaves.notifications.{$decision}_title"),
            __("leaves.notifications.{$decision}_message", [
                'reference' => $leave->reference_number,
                'reason' => $rejectionReason,
            ]),
            route('personnel.leaves.index'),
            $employee->id
        ));
    }
}
