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
use App\Enums\Claim\ClaimStatus;
use App\Enums\FileAction;
use App\Enums\FileResources;
use App\Http\Controllers\Controller;
use App\Http\Requests\Personnel\Claim\StoreClaimRequest;
use App\Http\Requests\Personnel\Claim\UpdateClaimRequest;
use App\Models\Claim\Claim;
use App\Services\Personnel\ClaimService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Handles employee self-service claim applications including dashboard viewing,
 * claim submission, updating pending/draft claims, and cancellation.
 */
class ClaimController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param  ClaimService  $claimService  The claim service instance.
     */
    public function __construct(private readonly ClaimService $claimService) {}

    /**
     * Display a listing of the resource.
     * Display the employee's personal claim dashboard with summary metrics.
     *
     * @return View Returns the rendered dashboard view.
     */
    public function index(): View
    {
        $this->allows(FileResources::CLAIMS);

        $data = $this->claimService->getDashboardData((string) (auth()->id() ?? ''), self::PER_PAGE_GRID_NORMAL);

        return view('personnel.claims.index', [
            'recentRequests' => $data['recent'],
            'pendingCount' => $data['pending_count'],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * Show the application form for creating a new expense claim.
     *
     * @return View Returns the rendered application form view.
     */
    public function create(): View
    {
        $this->allows(FileResources::CLAIMS, FileAction::ADD);

        return view('personnel.claims.create');
    }

    /**
     * Handle the incoming request to create a new resource.
     * Store a newly created expense claim in database storage.
     *
     * @param  StoreClaimRequest  $request  The validated HTTP request containing form payload and attachments.
     * @return RedirectResponse Redirect response back to the claims dashboard.
     */
    public function store(StoreClaimRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $isDraft = $request->input('action') === 'draft';
        $attachmentPath = $this->claimService->handleAttachment($request->file('attachment'));

        $this->claimService->createClaim($validated, $attachmentPath, $isDraft, $request->user() ?? abort(403));

        return redirect()
            ->route('personnel.claims.index')
            ->with('success', FlashMessage::created(FileResources::CLAIMS));
    }

    /**
     * Show the form for editing an open resource.
     * Show the editing form for an unapproved or draft claim application.
     *
     * @param  Claim  $claim  The claim model instance to edit.
     * @return View Returns the rendered edit view.
     *
     * @throws HttpException
     */
    public function edit(Claim $claim): View
    {
        $this->allows(FileResources::CLAIMS, FileAction::UPDATE);

        abort_unless($claim->user_id === auth()->id(), 403);
        abort_unless(in_array($claim->status->value, [
            ClaimStatus::DRAFT->value,
            ClaimStatus::PENDING->value,
            ClaimStatus::SUBMITTED->value,
        ]), 422, 'Approved or rejected claims cannot be modified.');

        return view('personnel.claims.edit', compact('claim'));
    }

    /**
     * Update an existing resource by its object.
     * Update an unapproved or draft claim record in storage.
     *
     * @param  UpdateClaimRequest  $request  The validated HTTP request instance with updated details.
     * @param  Claim  $claim  The target claim instance to update.
     * @return RedirectResponse Redirect response back to claims dashboard.
     *
     * @throws HttpException
     */
    public function update(UpdateClaimRequest $request, Claim $claim): RedirectResponse
    {
        abort_unless($claim->user_id === auth()->id(), 403);
        abort_unless(in_array($claim->status->value, [
            ClaimStatus::DRAFT->value,
            ClaimStatus::PENDING->value,
            ClaimStatus::SUBMITTED->value,
        ]), 422, 'Approved or rejected claims cannot be modified.');

        $validated = $request->validated();

        $isDraft = $request->input('action') === 'draft';
        $attachmentPath = $this->claimService->handleAttachment($request->file('attachment'), $claim->attachment_path);

        $this->claimService->updateClaim($claim, $validated, $attachmentPath, $isDraft, $request->user() ?? abort(403));

        return redirect()
            ->route('personnel.claims.index')
            ->with('success', FlashMessage::updated(FileResources::CLAIMS));
    }

    /**
     * Remove a resource by its object.
     * Cancel and remove an unapproved claim application from database storage.
     *
     * @param  Claim  $claim  The target claim model instance to delete.
     * @return RedirectResponse Redirect response back to claims dashboard.
     *
     * @throws HttpException
     */
    public function destroy(Claim $claim): RedirectResponse
    {
        $this->allows(FileResources::CLAIMS, FileAction::REMOVE);

        abort_unless($claim->user_id === auth()->id(), 403);
        abort_unless(in_array($claim->status->value, [
            ClaimStatus::DRAFT->value,
            ClaimStatus::PENDING->value,
            ClaimStatus::SUBMITTED->value,
        ]), 422, 'Approved or rejected claims cannot be cancelled.');

        $this->claimService->deleteClaim($claim);

        return redirect()
            ->route('personnel.claims.index')
            ->with('success', FlashMessage::deleted(FileResources::CLAIMS));
    }
}
