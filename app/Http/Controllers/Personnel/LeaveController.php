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

namespace App\Http\Controllers\Personnel;

use App\Constants\FlashMessage;
use App\Enums\DocumentSubType;
use App\Enums\FileAction;
use App\Enums\FileResources;
use App\Enums\Leave\LeaveStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Personnel\Leave\StoreLeaveRequest;
use App\Http\Requests\Personnel\Leave\UpdateLeaveRequest;
use App\Models\Leave\Leave;
use App\Models\System\User;
use App\Services\Personnel\LeaveService;
use App\Traits\GeneratesDocumentNumber;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use LogicException;
use Throwable;

/**
 * Handles employee leave application creation, modification, submission,
 * cancellation, and schedule overview.
 */
class LeaveController extends Controller
{
    use GeneratesDocumentNumber;

    /**
     * Create a new controller instance.
     *
     * @param  LeaveService  $leaveService  The leave service instance.
     */
    public function __construct(
        private readonly LeaveService $leaveService,
    ) {}

    /**
     * Display a listing of the resource.
     * Display the logged-in user's leave dashboard with summary, requests, and current absences.
     *
     * @return View The rendered leave dashboard view.
     */
    public function index(): View
    {
        $this->allows(FileResources::LEAVES);

        $user = auth()->user();
        abort_unless($user instanceof User, 401);

        $data = $this->leaveService->getDashboardData($user->id, self::PER_PAGE_GRID_NORMAL);

        return view('personnel.leaves.index', [
            'recentRequests' => $data['recent'],
            'pendingCount' => $data['pending_count'],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * Show the application form for creating a new leave request.
     *
     * @return View The rendered creation form view.
     */
    public function create(): View
    {
        $this->allows(FileResources::LEAVES, FileAction::ADD);

        $user = auth()->user();
        abort_unless($user instanceof User, 401);

        return view('personnel.leaves.create');
    }

    /**
     * Handle the incoming request to create a new resource.
     * Store a newly created leave request in storage as either a draft or submitted.
     *
     * @param  StoreLeaveRequest  $request  The validated HTTP request instance containing payload data.
     * @return RedirectResponse Redirects to the leave index view on success.
     *
     * @throws Throwable
     */
    public function store(StoreLeaveRequest $request): RedirectResponse
    {
        $user = $request->user();
        abort_if($user === null, 401);

        $validated = $request->validated();

        $startDate = Carbon::parse($request->string('start_date')->toString());
        $endDate = Carbon::parse($request->string('end_date')->toString());

        $totalDays = $this->leaveService->calculateTotalDays($startDate, $endDate);

        $attachmentPath = $this->leaveService->handleAttachment($request->file('attachment'));

        $status = $request->string('action')->toString() === 'submit'
            ? LeaveStatus::SUBMITTED->value
            : LeaveStatus::DRAFT->value;

        $referenceNumber = $this->generateDocumentNumber(DocumentSubType::LEAVE);

        $this->leaveService->createLeave($validated, $attachmentPath, $totalDays, $status, $user->id, $referenceNumber);

        return redirect()
            ->route('personnel.leaves.index')
            ->with('success', FlashMessage::created(FileResources::LEAVES));
    }

    /**
     * Show the form for editing an open resource.
     * Show the edit form for an unapproved leave request.
     *
     * @param  Leave  $leave  The target leave instance.
     * @return View The rendered edit view.
     */
    public function edit(Leave $leave): View
    {
        $this->allows(FileResources::LEAVES, FileAction::UPDATE);

        abort_unless($leave->user_id === auth()->id(), 403);
        abort_unless(in_array($leave->status, [LeaveStatus::DRAFT, LeaveStatus::PENDING, LeaveStatus::SUBMITTED]), 422);

        return view('personnel.leaves.edit', compact('leave'));
    }

    /**
     * Update an existing resource by its object.
     * Update an existing leave request details and attachment.
     *
     * @param  UpdateLeaveRequest  $request  The validated HTTP request instance with update data.
     * @param  Leave  $leave  The target leave instance to update.
     * @return RedirectResponse Redirects to the leave index view.
     */
    public function update(UpdateLeaveRequest $request, Leave $leave): RedirectResponse
    {
        $user = $request->user();
        abort_if($user === null, 401);

        abort_unless($leave->user_id === auth()->id(), 403);
        abort_unless(in_array($leave->status, [LeaveStatus::DRAFT, LeaveStatus::PENDING, LeaveStatus::SUBMITTED]), 422);

        $validated = $request->validated();

        $startDate = Carbon::parse($request->string('start_date')->toString());
        $endDate = Carbon::parse($request->string('end_date')->toString());

        $totalDays = $this->leaveService->calculateTotalDays($startDate, $endDate);

        $attachmentPath = $this->leaveService->handleAttachment($request->file('attachment'), $leave->attachment_path);

        $status = $request->string('action')->toString() === 'submit'
            ? LeaveStatus::SUBMITTED->value
            : LeaveStatus::DRAFT->value;

        $this->leaveService->updateLeave($leave, $validated, $attachmentPath, $totalDays, $status);

        return redirect()
            ->route('personnel.leaves.index')
            ->with('success', FlashMessage::updated(FileResources::LEAVES));
    }

    /**
     * Remove a resource by its object.
     * Delete an unapproved leave request and its stored attachment.
     *
     * @param  Leave  $leave  The leave instance to delete.
     * @return RedirectResponse Redirects to the leave index view.
     */
    public function destroy(Leave $leave): RedirectResponse
    {
        $this->allows(FileResources::LEAVES, FileAction::REMOVE);

        abort_unless($leave->user_id === auth()->id(), 403);
        abort_unless(in_array($leave->status, [LeaveStatus::DRAFT, LeaveStatus::PENDING, LeaveStatus::SUBMITTED]), 422, 'Only unapproved requests can be removed.');

        $this->leaveService->deleteLeave($leave);

        return redirect()
            ->route('personnel.leaves.index')
            ->with('success', FlashMessage::deleted(FileResources::LEAVES));
    }

    /**
     * Display the specified resource.
     * Display the details of a specific leave request.
     *
     * @param  Leave  $leave  The target leave instance.
     * @return View The rendered detail view.
     */
    public function show(Leave $leave): View
    {
        $this->allows(FileResources::LEAVES);

        abort_unless($leave->user_id === auth()->id(), 403);

        return view('personnel.leaves.show', compact('leave'));
    }

    /**
     * Display a month view calendar showing official holidays and active leaves.
     *
     * @param  Request  $request  The HTTP request instance with optional 'month' string parameter.
     * @return View The rendered calendar view.
     */
    public function calendar(Request $request): View
    {
        $monthInput = $request->string('month')->toString();
        $month = $monthInput !== ''
            ? Carbon::parse($monthInput.'-01')->startOfMonth()
            : Carbon::now()->startOfMonth();

        $data = $this->leaveService->buildCalendarData($month);

        return view('personnel.leaves.calendar', $data);
    }

    /**
     * Display a listing of the resource.
     * Display the read-only holiday calendar with approved employee leave entries.
     *
     * @param  Request  $request  The HTTP request instance containing the selected year and month.
     * @return View The rendered staff holiday calendar view.
     */
    public function holidays(Request $request): View
    {
        $this->allows(FileResources::PERSONNEL_HOLIDAYS);

        $year = $request->integer('year', now()->year);
        if ($year < 1900 || $year > 2100) {
            throw new LogicException('Invalid year.');
        }

        $data = $this->leaveService->buildHolidaysData($year);

        return view('leave.holidays.index', [
            'holidays' => $data['holidays'],
            'leaves' => $data['leaves'],
            'year' => $data['year'],
            'leavesByDay' => $data['leavesByDay'],
            'readOnly' => true,
            'holidayIndexRoute' => FileResources::PERSONNEL_HOLIDAYS->value,
        ]);
    }
}
