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

use App\Http\Requests\BaseFormRequest;
use App\Support\Traits\ChecksFilePermission;

/**
 * The fields of a leave management action, named.
 *
 * The HR approval workflow covers several distinct actions — editing leave details,
 * approving, rejecting, and batch operations — each with its own concrete request.
 * What they share is field naming, which is here, so a controller can ask for the
 * rejection reason by name regardless of which request it was handed.
 */
abstract class LeaveManagementRequest extends BaseFormRequest
{
    use ChecksFilePermission;

    /**
     * Get the leave type value.
     */
    public function leaveType(): string
    {
        return $this->stringOrEmpty('type');
    }

    /**
     * Get the leave start date.
     */
    public function startDate(): string
    {
        return $this->stringOrEmpty('start_date');
    }

    /**
     * Get the leave end date.
     */
    public function endDate(): string
    {
        return $this->stringOrEmpty('end_date');
    }

    /**
     * Get the reason for the leave.
     */
    public function reason(): ?string
    {
        return $this->stringOrNull('reason');
    }

    /**
     * Get the rejection reason.
     */
    public function rejectionReason(): string
    {
        return $this->stringOrEmpty('rejection_reason');
    }
}
