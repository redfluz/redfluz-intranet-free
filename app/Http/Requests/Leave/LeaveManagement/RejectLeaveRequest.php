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

declare(strict_types=1);

namespace App\Http\Requests\Leave\LeaveManagement;

use App\Enums\FileAction;
use App\Enums\FileResources;

/**
 * The rejection reason required when an HR manager rejects a single leave
 * application.
 *
 * A rejection without a reason leaves the employee with no feedback, so the
 * reason is required rather than nullable. It is a separate request from the
 * batch reject because the batch version names the field differently
 * (batch_rejection_reason) to avoid ambiguity when multiple IDs are submitted
 * in the same payload.
 */
class RejectLeaveRequest extends LeaveManagementRequest
{
    /**
     * Who may reject a leave application.
     */
    public function authorize(): bool
    {
        return $this->allows(FileResources::LEAVE_MANAGEMENTS, FileAction::CANCEL);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'rejection_reason' => ['required', 'string', 'max:1000'],
        ];
    }
}
