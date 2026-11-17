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
use App\Models\Leave\Holiday;
use Illuminate\Validation\Rule;

/**
 * The fields a holiday is edited from.
 *
 * The uniqueness check on date ignores the record currently being updated, so
 * an admin may re-save a holiday without changing its date and the request will
 * still pass. The check still prevents two different holidays sharing the same
 * calendar date.
 */
class UpdateHolidayRequest extends HolidayRequest
{
    /**
     * Who may edit a holiday.
     */
    public function authorize(): bool
    {
        return $this->allows(FileResources::HOLIDAYS, FileAction::UPDATE);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        /** @var Holiday $holiday */
        $holiday = $this->route('holiday');

        return [
            'name' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date', Rule::unique('holidays', 'date')->ignore($holiday->id)],
            'type' => ['required', Rule::enum(HolidayType::class)],
        ];
    }
}
