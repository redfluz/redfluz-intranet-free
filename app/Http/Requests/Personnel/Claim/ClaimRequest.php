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

use App\Http\Requests\BaseFormRequest;
use App\Support\Traits\ChecksFilePermission;

/**
 * The fields of a self-service expense claim, named.
 *
 * An employee raises a claim against their own expenses, so creating and editing
 * it ask for the same fields and each states its own rules rather than one
 * inheriting from the other. What they share is the permission that guards them,
 * which is here so a controller asking for access gets the same check whichever
 * request it was handed.
 */
abstract class ClaimRequest extends BaseFormRequest
{
    use ChecksFilePermission;
}
