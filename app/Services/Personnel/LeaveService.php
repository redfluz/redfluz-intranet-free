<?php

namespace App\Services\Personnel;

use App\Enums\Leave\LeaveStatus;
use App\Models\Leave\Holiday;
use App\Models\Leave\Leave;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\Storage;

class LeaveService
{
    /**
     * Get the user's leave dashboard data.
     *
     * @param  string  $userId  The user ID.
     * @param  int  $perPage  The pagination size.
     * @return array{recent: LengthAwarePaginator<int, Leave>, pending_count: int}
     */
    public function getDashboardData(string $userId, int $perPage = 15): array
    {
        $recentRequests = Leave::query()
            ->where('user_id', $userId)
            ->latest('start_date')
            ->paginate($perPage);

        $pendingCount = Leave::query()
            ->where('user_id', $userId)
            ->whereIn('status', [LeaveStatus::PENDING->value, LeaveStatus::SUBMITTED->value])
            ->count();

        return [
            'recent' => $recentRequests,
            'pending_count' => $pendingCount,
        ];
    }

    /**
     * Create a new leave request.
     *
     * @param  array<string, mixed>  $validated  The validated data.
     * @param  string|null  $attachmentPath  The optional attachment path.
     * @param  int  $totalDays  The calculated total days.
     * @param  string  $status  The leave status.
     * @param  string  $userId  The user ID.
     * @param  string  $referenceNumber  The generated reference number.
     * @return Leave The created leave.
     */
    public function createLeave(
        array $validated,
        ?string $attachmentPath,
        int $totalDays,
        string $status,
        string $userId,
        string $referenceNumber,
    ): Leave {
        /** @var Leave $leave */
        $leave = Leave::query()->create([
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'reason' => $validated['reason'] ?? null,
            'attachment_path' => $attachmentPath,
            'total_days' => $totalDays,
            'user_id' => $userId,
            'created_by' => $userId,
            'status' => $status,
        ]);

        $leave->reference_number = $referenceNumber;
        $leave->save();

        return $leave;
    }

    /**
     * Update an existing leave request.
     *
     * @param  Leave  $leave  The leave to update.
     * @param  array<string, mixed>  $validated  The validated data.
     * @param  string|null  $attachmentPath  The optional new attachment path.
     * @param  int  $totalDays  The calculated total days.
     * @param  string  $status  The leave status.
     * @return void Returns nothing after updating the leave request.
     */
    public function updateLeave(Leave $leave, array $validated, ?string $attachmentPath, int $totalDays, string $status): void
    {
        $data = [
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'reason' => $validated['reason'] ?? null,
            'total_days' => $totalDays,
            'status' => $status,
        ];

        if ($attachmentPath !== null) {
            $data['attachment_path'] = $attachmentPath;
        }

        $leave->update($data);
    }

    /**
     * Delete a leave and its attachment.
     *
     * @param  Leave  $leave  The leave to delete.
     * @return void Returns nothing after deleting the leave and its attachment.
     */
    public function deleteLeave(Leave $leave): void
    {
        if ($leave->attachment_path && Storage::disk('public')->exists($leave->attachment_path)) {
            Storage::disk('public')->delete($leave->attachment_path);
        }

        $leave->delete();
    }

    /**
     * Handle attachment upload for leave.
     *
     * @param  UploadedFile|null  $file  The uploaded file.
     * @param  string|null  $existingPath  The existing attachment path to replace.
     * @return string|null The new attachment path, or existing if no new file.
     */
    public function handleAttachment(?UploadedFile $file, ?string $existingPath = null): ?string
    {
        if ($file === null) {
            return $existingPath;
        }

        if ($existingPath && Storage::disk('public')->exists($existingPath)) {
            Storage::disk('public')->delete($existingPath);
        }

        return $file->store('leaves/attachments', 'public') ?: null;
    }

    /**
     * Build calendar data for a given month.
     *
     * @param  Carbon  $month  The month to build calendar for.
     * @return array{month: Carbon, monthStart: Carbon, monthEnd: Carbon, holidays: SupportCollection<string, Holiday>, leavesByDay: array<string, array<int, Leave>>}
     */
    public function buildCalendarData(Carbon $month): array
    {
        $monthStart = $month->copy()->startOfMonth();
        $monthEnd = $month->copy()->endOfMonth();

        $holidays = Holiday::between($monthStart, $monthEnd);
        $leaves = Leave::approvedOverlappingRange($monthStart, $monthEnd);

        $leavesByDay = $this->mapLeavesByDay($leaves, $monthStart, $monthEnd);

        return [
            'month' => $month,
            'monthStart' => $monthStart,
            'monthEnd' => $monthEnd,
            'holidays' => $holidays,
            'leavesByDay' => $leavesByDay,
        ];
    }

    /**
     * Build holidays view data for a given year.
     *
     * @param  int  $year  The year.
     * @return array{holidays: EloquentCollection<int, Holiday>, leaves: SupportCollection<int, Leave>, year: int, leavesByDay: array<string, array<int, Leave>>}
     */
    public function buildHolidaysData(int $year): array
    {
        $yearStart = (Carbon::create($year) ?: now())->startOfDay();
        $yearEnd = (Carbon::create($year, 12, 31) ?: now())->endOfDay();

        $holidays = Holiday::query()
            ->whereYear('date', $year)
            ->oldest('date')
            ->get();

        $leaves = Leave::approvedOverlappingRange($yearStart, $yearEnd);
        $leavesByDay = $this->mapLeavesByDay($leaves, $yearStart, $yearEnd);

        return [
            'holidays' => $holidays,
            'leaves' => $leaves,
            'year' => $year,
            'leavesByDay' => $leavesByDay,
        ];
    }

    /**
     * Calculate the working days covered by a leave period.
     *
     * The intranet edition counts weekdays minus registered public holidays;
     * there is no salary working-day configuration to consult.
     *
     * @param  Carbon  $start  The starting date of leave.
     * @param  Carbon  $end  The ending date of leave.
     * @return int The calculated number of days.
     */
    public function calculateTotalDays(Carbon $start, Carbon $end): int
    {
        return Holiday::workingDaysBetween($start, $end);
    }

    /**
     * Map leaves to their respective days within a date range.
     *
     * @param  SupportCollection<int, Leave>  $leaves  The leaves collection.
     * @param  Carbon  $rangeStart  The range start date.
     * @param  Carbon  $rangeEnd  The range end date.
     * @return array<string, array<int, Leave>>
     */
    private function mapLeavesByDay(SupportCollection $leaves, Carbon $rangeStart, Carbon $rangeEnd): array
    {
        $leavesByDay = [];

        foreach ($leaves as $leave) {
            $cursor = $leave->start_date->copy()->max($rangeStart);
            $stop = $leave->end_date->copy()->min($rangeEnd);

            while ($cursor->lte($stop)) {
                $leavesByDay[$cursor->toDateString()][] = $leave;
                $cursor->addDay();
            }
        }

        return $leavesByDay;
    }
}
