<?php

/**
 * ============================================================================
 *
 * @author      redfluz
 *
 * @github      https://github.com/redfluz
 *
 * @youtube     https://www.youtube.com/@redfluz
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

declare(strict_types=1);

namespace App\Http\Requests\Personnel\Claim;

use App\Enums\FileAction;
use App\Enums\FileResources;

/**
 * The fields a claim is edited from.
 *
 * The rules are stated in full rather than taken from the create request, because
 * a rule inherited and then changed is a rule nobody can read in one place. A
 * claim raised here carries no uniqueness check the create does not, so the two
 * rule sets match field for field — they are kept separate so a future change to
 * one cannot silently miss the other.
 *
 * Status is deliberately absent. A claim's status moves only through approve
 * and reject, each guarded by its own permission; an edit form that could also
 * set the status would let an employee approve their own claim.
 */
class UpdateClaimRequest extends ClaimRequest
{
    /**
     * Who may edit a claim.
     *
     * The permission lives here rather than in the controller, so that the request
     * says everything about who may send it and what it must contain. Laravel
     * asks this before the controller runs, so a request that is not permitted
     * never reaches the action it was meant to guard.
     *
     * It refuses by redirecting with an explanation and writing the attempt to
     * the log, which is what this check has always done and what returning a
     * plain false would lose.
     *
     * @return bool Whether the authenticated user may perform this action.
     */
    public function authorize(): bool
    {
        return $this->allows(FileResources::CLAIMS, FileAction::UPDATE);
    }

    /**
     * The validation rules for editing a claim.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'receipt_date' => ['required', 'date'],
            'receipt_no' => ['nullable', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string', 'max:1000'],
            'attachment' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ];
    }
}
