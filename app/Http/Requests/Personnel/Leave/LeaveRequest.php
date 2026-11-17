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

namespace App\Http\Requests\Personnel\Leave;

use App\Http\Requests\BaseFormRequest;
use App\Support\Traits\ChecksFilePermission;

/**
 * The fields of a self-service leave request, named.
 *
 * An employee requests leave against their own entitlement, so creating and
 * editing it ask for the same fields and each states its own rules rather than one
 * inheriting from the other. What they share is the permission that guards them,
 * which is here so a controller asking for access gets the same check whichever
 * request it was handed.
 */
abstract class LeaveRequest extends BaseFormRequest
{
    use ChecksFilePermission;
}
