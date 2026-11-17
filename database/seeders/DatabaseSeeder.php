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

/**
 * @noinspection ALL
 *
 * @phpstan-ignore-file
 */

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * @noinspection PhpUnused
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Whether the demo data is wanted.
     *
     * Set by whichever command is running the seed, because "did the user ask
     * for demo data" is a question about the install, not about the arguments
     * this seeder happens to be able to see.
     */
    public bool $withDemoData = false;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Artisan has already parsed command options before seeder execution,
        // so the installer explicitly sets this property for both prompted and
        // --demo installation paths.
        $demo = $this->withDemoData;

        // ── Core/system tables ──
        $this->call([
            // Timezones must exist before SystemSeeder links the default
            // Malaysia timezone onto the settings row.
            TimezoneSeeder::class,
            SystemSeeder::class,
            MenuSeeder::class,
            DocumentSequenceSeeder::class,
            HolidaySeeder::class,
        ]);

        // ── Currencies used by the settings currency picker ──
        $this->call([
            FinanceConfigurationSeeder::class,
        ]);

        // Set currency on settings after the currencies have been seeded
        $myrCurrencyId = DB::table('currencies')->where('code', 'MYR')->value('id');
        if ($myrCurrencyId) {
            DB::table('settings')->update(['currency_id' => $myrCurrencyId]);
        }

        // ── Demo data (grouped by the feature migrations that own the tables) ──
        if (! $demo) {
            return;
        }

        // ── Demo data for the intranet workspace ──
        // LeaveSeeder creates the demo employees that ClaimSeeder references,
        // so it must run first.
        $this->call([
            LeaveSeeder::class,
            ClaimSeeder::class,
            NewsSeeder::class,
            MessageSeeder::class,
            TaskSeeder::class,
        ]);
    }
}
