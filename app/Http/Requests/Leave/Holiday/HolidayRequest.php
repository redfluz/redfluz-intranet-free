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

namespace App\Http\Requests\Leave\Holiday;

use App\Http\Requests\BaseFormRequest;
use App\Support\Traits\ChecksFilePermission;

/**
 * The fields of a holiday, named.
 *
 * Creating and editing a holiday ask for the same fields but with a small
 * difference on the uniqueness check for date: a store validates that the date
 * does not exist at all, whereas an update ignores the current record's own date.
 * What they share is the field naming, which is here, so a controller asking for
 * the holiday name gets the same field whichever request it was handed.
 */
abstract class HolidayRequest extends BaseFormRequest
{
    use ChecksFilePermission;

    /**
     * Get the holiday name.
     */
    public function holidayName(): string
    {
        return $this->stringOrEmpty('name');
    }

    /**
     * Get the holiday date.
     */
    public function dateValue(): string
    {
        return $this->stringOrEmpty('date');
    }

    /**
     * Get the holiday type (public, company, or state).
     */
    public function type(): string
    {
        return $this->stringOrEmpty('type');
    }
}
