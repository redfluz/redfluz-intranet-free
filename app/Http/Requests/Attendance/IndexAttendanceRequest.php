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

use App\Enums\FileResources;

/**
 * Guards the employee self-service attendance listing.
 *
 * No field rules are needed; the only purpose of this request is to ensure
 * the signed-in user holds the READ permission for attendance before the
 * controller action runs.
 */
class IndexAttendanceRequest extends AttendanceRequest
{
    /**
     * Who may view the attendance listing.
     */
    public function authorize(): bool
    {
        return $this->allows(FileResources::ATTENDANCE);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [];
    }
}
