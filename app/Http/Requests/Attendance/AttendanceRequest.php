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
 * reverse engineering, or public update of this software via any medium is
 * strictly prohibited without explicit written permission from the copyright owner.
 */

declare(strict_types=1);

namespace App\Http\Requests\Attendance;

use App\Http\Requests\BaseFormRequest;
use App\Support\Traits\ChecksFilePermission;

/**
 * The fields shared across attendance actions, named.
 *
 * Attendance has several distinct workflows: the employee self-service clock-in /
 * clock-out, the salary-admin listing, and the salary-admin correction. Each has
 * its own concrete request. What they share is the field naming, which lives here
 * so a controller always reads a typed value by a stable name.
 */
abstract class AttendanceRequest extends BaseFormRequest
{
    use ChecksFilePermission;

    /**
     * Get the selected month filter (Y-m format).
     */
    public function month(): string
    {
        return $this->stringOrEmpty('month');
    }

    /**
     * Get the department ID filter, if provided.
     */
    public function departmentId(): ?string
    {
        return $this->stringOrNull('department_id');
    }

    /**
     * Get the clock-in datetime string, if provided.
     */
    public function clockIn(): ?string
    {
        return $this->stringOrNull('clock_in');
    }

    /**
     * Get the clock-out datetime string, if provided.
     */
    public function clockOut(): ?string
    {
        return $this->stringOrNull('clock_out');
    }
}
