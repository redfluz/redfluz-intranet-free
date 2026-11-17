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

namespace App\Http\Controllers\Claim;

use App\Constants\FlashMessage;
use App\Enums\Claim\ClaimStatus;
use App\Enums\FileAction;
use App\Enums\FileResources;
use App\Http\Controllers\Controller;
use App\Http\Requests\Claim\ClaimManagement\RejectClaimManagementRequest;
use App\Models\Claim\Claim;
use App\Notifications\SystemAlert;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Administrator view of employee claims. The intranet edition keeps the
 * workflow to a single decision: approve or reject. There are no claim types,
 * limits or payment posting.
 */
class ClaimManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     * Display a paginated grid of claims with filtering options.
     *
     * @param  Request  $request  The HTTP request instance.
     * @return View Returns the rendered Blade view.
     */
    public function index(Request $request): View
    {
        $this->allows(FileResources::CLAIM_MANAGEMENTS);

        $search = $request->string('search')->toString() ?: null;
        $status = $request->string('status')->toString() ?: null;
        $startDate = $request->string('start_date')->toString() ?: null;
        $endDate = $request->string('end_date')->toString() ?: null;

        $query = Claim::query()->with(['user']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('reference_number', 'like', "%$search%")
                    ->orWhere('receipt_no', 'like', "%$search%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%$search%");
                    });
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($startDate && $endDate) {
            $query->whereBetween('receipt_date', [$startDate, $endDate]);
        }

        $claims = $query->latest('receipt_date')->paginate(self::PER_PAGE_GRID_NORMAL)->withQueryString();

        return view('claim.claim-managements.index', compact('claims', 'search', 'status', 'startDate', 'endDate'));
    }

    /**
     * Display the specified resource.
     * Show a single claim with its receipt attachment so the reviewer can
     * inspect the supporting document before approving or rejecting.
     *
     * @param  Claim  $claim  The claim model instance.
     * @return View Returns the rendered Blade view.
     */
    public function show(Claim $claim): View
    {
        $this->allows(FileResources::CLAIM_MANAGEMENTS);

        $claim->load(['user', 'approver']);

        return view('claim.claim-managements.show', compact('claim'));
    }

    /**
     * Approve a pending claim.
     *
     * @param  Claim  $claim  The claim model instance.
     * @return RedirectResponse Redirect response to the claim management grid.
     */
    public function approve(Claim $claim): RedirectResponse
    {
        $this->allows(FileResources::CLAIM_MANAGEMENTS, FileAction::APPROVE);

        abort_unless(in_array($claim->status, [ClaimStatus::PENDING, ClaimStatus::SUBMITTED], true), 422);

        $claim->update([
            'status' => ClaimStatus::APPROVED->value,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'rejection_reason' => null,
        ]);

        $this->notifyClaimant($claim, ClaimStatus::APPROVED);

        return redirect()
            ->route('claim.claim-managements.index')
            ->with('success', FlashMessage::approved(FileResources::CLAIM_MANAGEMENTS));
    }

    /**
     * Reject a pending claim with a required reason.
     *
     * @param  RejectClaimManagementRequest  $request  The validated reject request.
     * @param  Claim  $claim  The claim model instance.
     * @return RedirectResponse Redirect response to the claim management grid.
     */
    public function reject(RejectClaimManagementRequest $request, Claim $claim): RedirectResponse
    {
        abort_unless(in_array($claim->status, [ClaimStatus::PENDING, ClaimStatus::SUBMITTED], true), 422);

        $rejectionReason = $request->string('rejection_reason')->toString();

        $claim->update([
            'status' => ClaimStatus::REJECTED->value,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'rejection_reason' => $rejectionReason,
        ]);

        $this->notifyClaimant($claim, ClaimStatus::REJECTED, $rejectionReason);

        return redirect()
            ->route('claim.claim-managements.index')
            ->with('success', FlashMessage::rejected(FileResources::CLAIM_MANAGEMENTS));
    }

    /**
     * Notify the employee after a claim decision.
     *
     * @param  Claim  $claim  The decided claim.
     * @param  ClaimStatus  $status  The decision status.
     * @param  string|null  $rejectionReason  The rejection reason, when rejected.
     * @return void Does not return a value.
     */
    private function notifyClaimant(Claim $claim, ClaimStatus $status, ?string $rejectionReason = null): void
    {
        $claim->loadMissing('user');

        if (! $claim->user) {
            return;
        }

        $decision = $status === ClaimStatus::APPROVED ? 'approved' : 'rejected';
        $claim->user->notify(new SystemAlert(
            __("claims.notifications.{$decision}_title"),
            __("claims.notifications.{$decision}_message", [
                'reference' => $claim->reference_number,
                'reason' => $rejectionReason,
            ]),
            route('personnel.claims.index'),
            $claim->user->id
        ));
    }
}
