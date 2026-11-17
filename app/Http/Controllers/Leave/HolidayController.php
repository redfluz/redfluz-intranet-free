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
use App\Http\Controllers\Controller;
use App\Http\Requests\Leave\Holiday\StoreHolidayRequest;
use App\Http\Requests\Leave\Holiday\UpdateHolidayRequest;
use App\Models\Leave\Holiday;
use App\Models\Leave\Leave;
use App\Support\Data\Leave\HolidayData;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use LogicException;

/**
 * Admin CRUD for the company holiday calendar. Days registered here are
 * excluded when Leave::workingDaysBetween() computes how many days a
 * leave application actually consumes, and are shown blocked-out on the
 * "who's on leave" calendar.
 */
class HolidayController extends Controller
{
    /**
     * Display a listing of holidays.
     *
     * @param  Request  $request  The HTTP request instance containing query filters.
     * @return View Returns the rendered Blade view.
     */
    public function index(Request $request): View
    {
        $this->allows(FileResources::HOLIDAYS);

        $year = $request->integer('year', now()->year);

        /** @var Collection<int, Holiday> $holidays */
        $holidays = Holiday::query()
            ->whereYear('date', $year)
            ->oldest('date')
            ->get();

        $yearStart = Carbon::create($year) ?? throw new LogicException('Invalid year.');
        $yearStart = $yearStart->startOfDay();
        $yearEnd = Carbon::create($year, 12, 31) ?? throw new LogicException('Invalid year.');
        $yearEnd = $yearEnd->endOfDay();
        $leaves = Leave::approvedOverlappingRange($yearStart, $yearEnd);
        $leavesByDay = [];

        /** @var Leave $leave */
        foreach ($leaves as $leave) {
            $cursor = $leave->start_date->copy()->max($yearStart);
            $stop = $leave->end_date->copy()->min($yearEnd);

            while ($cursor->lte($stop)) {
                $leavesByDay[$cursor->toDateString()][] = $leave;
                $cursor->addDay();
            }
        }

        return view('leave.holidays.index', compact('holidays', 'year', 'leaves', 'leavesByDay'));
    }

    /**
     * Show the form for creating a new holiday.
     *
     * @return View Returns the rendered Blade view.
     */
    public function create(): View
    {
        $this->allows(FileResources::HOLIDAYS, FileAction::ADD);

        return view('leave.holidays.create');
    }

    /**
     * Handle the incoming request to create a new resource.
     * Store a newly created holiday.
     *
     * @param  StoreHolidayRequest  $request  The validated holiday data.
     * @return RedirectResponse Redirect response to the holiday list.
     */
    public function store(StoreHolidayRequest $request): RedirectResponse
    {
        $data = HolidayData::from($request->validated() + ['created_by' => auth()->id()]);

        Holiday::query()->create([
            'name' => $data->name,
            'date' => $data->date?->format('Y-m-d'),
            'type' => $data->type?->value,
            'created_by' => $data->createdBy,
        ]);

        return redirect()
            ->route('leave.holidays.index')
            ->with('success', FlashMessage::created(FileResources::HOLIDAYS));
    }

    /**
     * Show the form for editing an open resource.
     * Show the form for editing the specified holiday.
     *
     * @param  Holiday  $holiday  The holiday model instance.
     * @return View Returns the rendered Blade view.
     */
    public function edit(Holiday $holiday): View
    {
        $this->allows(FileResources::HOLIDAYS, FileAction::UPDATE);

        return view('leave.holidays.edit', compact('holiday'));
    }

    /**
     * Update an existing resource by its object.
     * Update the specified holiday.
     *
     * @param  UpdateHolidayRequest  $request  The validated holiday data.
     * @param  Holiday  $holiday  The holiday model instance.
     * @return RedirectResponse Redirect response to the holiday list.
     */
    public function update(UpdateHolidayRequest $request, Holiday $holiday): RedirectResponse
    {
        $data = HolidayData::from($request->validated());

        $holiday->update([
            'name' => $data->name,
            'date' => $data->date?->format('Y-m-d'),
            'type' => $data->type?->value,
            'created_by' => $data->createdBy,
        ]);

        return redirect()
            ->route('leave.holidays.index')
            ->with('success', FlashMessage::updated(FileResources::HOLIDAYS));
    }

    /**
     * Remove a resource by its object.
     * Remove the specified holiday.
     *
     * @param  Holiday  $holiday  The holiday model instance.
     * @return RedirectResponse Redirect response to the holiday list.
     */
    public function destroy(Holiday $holiday): RedirectResponse
    {
        $this->allows(FileResources::HOLIDAYS, FileAction::REMOVE);

        $holiday->delete();

        return redirect()
            ->route('leave.holidays.index')
            ->with('success', FlashMessage::deleted(FileResources::HOLIDAYS));
    }
}
