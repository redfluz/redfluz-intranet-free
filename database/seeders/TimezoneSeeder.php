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

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TimezoneSeeder extends Seeder
{
    /**
     * Seed the timezones table.
     */
    public function run(): void
    {
        $timezones = [
            [
                'name' => 'Malaysia Time',
                'zone' => 'Asia/Kuala_Lumpur',
                'utc_offset' => '+08:00',
                'abbreviation' => 'MYT',
                'is_active' => true,
            ],
            [
                'name' => 'Singapore Time',
                'zone' => 'Asia/Singapore',
                'utc_offset' => '+08:00',
                'abbreviation' => 'SGT',
                'is_active' => true,
            ],
            [
                'name' => 'China Standard Time',
                'zone' => 'Asia/Shanghai',
                'utc_offset' => '+08:00',
                'abbreviation' => 'CST',
                'is_active' => true,
            ],
            [
                'name' => 'Hong Kong Time',
                'zone' => 'Asia/Hong_Kong',
                'utc_offset' => '+08:00',
                'abbreviation' => 'HKT',
                'is_active' => true,
            ],
            [
                'name' => 'Japan Standard Time',
                'zone' => 'Asia/Tokyo',
                'utc_offset' => '+09:00',
                'abbreviation' => 'JST',
                'is_active' => true,
            ],
            [
                'name' => 'Korea Standard Time',
                'zone' => 'Asia/Seoul',
                'utc_offset' => '+09:00',
                'abbreviation' => 'KST',
                'is_active' => true,
            ],
            [
                'name' => 'India Standard Time',
                'zone' => 'Asia/Kolkata',
                'utc_offset' => '+05:30',
                'abbreviation' => 'IST',
                'is_active' => true,
            ],
            [
                'name' => 'Greenwich Mean Time',
                'zone' => 'Europe/London',
                'utc_offset' => '+00:00',
                'abbreviation' => 'GMT',
                'is_active' => true,
            ],
            [
                'name' => 'Central European Time',
                'zone' => 'Europe/Paris',
                'utc_offset' => '+01:00',
                'abbreviation' => 'CET',
                'is_active' => true,
            ],
            [
                'name' => 'Eastern Standard Time',
                'zone' => 'America/New_York',
                'utc_offset' => '-05:00',
                'abbreviation' => 'EST',
                'is_active' => true,
            ],
            [
                'name' => 'Pacific Standard Time',
                'zone' => 'America/Los_Angeles',
                'utc_offset' => '-08:00',
                'abbreviation' => 'PST',
                'is_active' => true,
            ],
            [
                'name' => 'Australian Eastern Standard Time',
                'zone' => 'Australia/Sydney',
                'utc_offset' => '+10:00',
                'abbreviation' => 'AEST',
                'is_active' => true,
            ],
        ];

        foreach ($timezones as $timezone) {
            DB::table('timezones')->updateOrInsert(
                ['zone' => $timezone['zone']],
                array_merge(
                    ['id' => DB::table('timezones')->where('zone', $timezone['zone'])->value('id') ?? (string) Str::uuid7()],
                    $timezone,
                    ['created_at' => now(), 'updated_at' => now()]
                )
            );
        }
    }
}
