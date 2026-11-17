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

use App\Enums\FileAction;
use App\Enums\FileResources;
use App\Enums\Leave\HolidayType;
use Illuminate\Validation\Rule;

/**
 * The fields a holiday is created from.
 *
 * The rules live here rather than in the controller so that the controller can be
 * handed a request that has already been checked, and can ask for a named field
 * instead of reaching into an array it built itself.
 *
 * The date uniqueness check here is strict: the same date cannot appear twice in
 * the holidays table. On edit (UpdateHolidayRequest) the check ignores the record
 * being updated.
 */
class StoreHolidayRequest extends HolidayRequest
{
    /**
     * Who may create a holiday.
     *
     * The permission lives here rather than in the controller, so that the request
     * says everything about who may send it and what it must contain.
     */
    public function authorize(): bool
    {
        return $this->allows(FileResources::HOLIDAYS, FileAction::ADD);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date', 'unique:holidays,date'],
            'type' => ['required', Rule::enum(HolidayType::class)],
        ];
    }
}
