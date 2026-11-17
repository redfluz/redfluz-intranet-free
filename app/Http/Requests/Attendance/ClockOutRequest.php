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

namespace App\Http\Requests\Attendance;

use App\Enums\FileAction;
use App\Enums\FileResources;

/**
 * Guards the employee clock-out action.
 *
 * Clock-out carries no user-submitted fields; the timestamp is set server-side
 * from now(). The only purpose of this request is the permission check.
 */
class ClockOutRequest extends AttendanceRequest
{
    /**
     * Who may clock out.
     */
    public function authorize(): bool
    {
        return $this->allows(FileResources::ATTENDANCE, FileAction::UPDATE);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [];
    }
}
