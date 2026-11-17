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

use App\Notifications\AdminBroadcastNotification;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if --demo option exists and is set
        $demo = false;
        if ($this->command && $this->command->getDefinition()->hasOption('demo')) {
            $demo = (bool) $this->command->option('demo');
        }

        // 0b. Seed Organizations
        $organizations = [
            ['code' => 'STAFF', 'name' => 'Staff', 'task_scope' => 'own', 'is_default' => true],
            ['code' => 'MANAGER', 'name' => 'Manager', 'task_scope' => 'department', 'is_default' => false],
            ['code' => 'CEO', 'name' => 'Chief Executive Officer', 'task_scope' => 'system', 'is_default' => false],
            ['code' => 'TECHNICIAN', 'name' => 'Technician', 'task_scope' => 'own', 'is_default' => false],
            ['code' => 'ACCOUNTANT', 'name' => 'Accountant', 'task_scope' => 'own', 'is_default' => false],
        ];

        foreach ($organizations as $organization) {
            $existing = DB::table('organizations')->where('code', $organization['code'])->first();
            DB::table('organizations')->updateOrInsert(
                ['code' => $organization['code']],
                [
                    'id' => $existing->id ?? (string) Str::uuid7(),
                    'name' => $organization['name'],
                    'task_scope' => $organization['task_scope'],
                    'is_default' => $organization['is_default'],
                    'is_active' => true,
                    'created_at' => $existing->created_at ?? now(),
                    'updated_at' => now(),
                ]
            );
        }

        // 1. Seed Roles with Dashboards
        $roles = [
            ['name' => 'Super Admin', 'dashboard' => 'user', 'is_default' => false],
            ['name' => 'Manager',     'dashboard' => 'user', 'is_default' => false],
            ['name' => 'Editor',      'dashboard' => 'user', 'is_default' => false],
            ['name' => 'Finance',     'dashboard' => 'user', 'is_default' => false],
            ['name' => 'User',        'dashboard' => 'user', 'is_default' => true],
            ['name' => 'Human',       'dashboard' => 'user', 'is_default' => false],
            ['name' => 'Sales',       'dashboard' => 'user', 'is_default' => false],
            ['name' => 'Technician',  'dashboard' => 'user', 'is_default' => false],
            ['name' => 'Clerk',       'dashboard' => 'user', 'is_default' => false],
        ];

        if ($demo) {
            $roles = array_merge($roles, [
                ['name' => 'Test Finance', 'dashboard' => 'user', 'is_default' => false],
                ['name' => 'Test AP',      'dashboard' => 'user', 'is_default' => false],
                ['name' => 'Test AR',      'dashboard' => 'user', 'is_default' => false],
                ['name' => 'Test HR',      'dashboard' => 'user', 'is_default' => false],
            ]);
        }

        foreach ($roles as $role) {
            $existing = DB::table('roles')->where('name', $role['name'])->first();

            DB::table('roles')->updateOrInsert(
                ['name' => $role['name']],
                [
                    'id' => $existing->id ?? (string) Str::uuid7(),
                    'dashboard' => $role['dashboard'],
                    'is_default' => $role['is_default'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // 2. Seed Departments
        $departments = [
            ['name' => 'Management', 'is_default' => false],
            ['name' => 'Dealing & Compliance', 'is_default' => false],
            ['name' => 'Operations & Settlement', 'is_default' => false],
            ['name' => 'Client Relations', 'is_default' => false],
            ['name' => 'IT & System Administration', 'is_default' => false],
            ['name' => 'Technical Services', 'is_default' => false],
            ['name' => 'Finance & Accounting', 'is_default' => false],
            ['name' => 'Human Resources', 'is_default' => false],
            ['name' => 'new', 'is_default' => true],
        ];

        foreach ($departments as $department) {
            $existing = DB::table('departments')->where('name', $department['name'])->first();

            DB::table('departments')->updateOrInsert(
                ['name' => $department['name']],
                [
                    'id' => $existing->id ?? (string) Str::uuid7(),
                    'is_default' => $department['is_default'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // Fetch UUID Keys
        $adminRoleId = DB::table('roles')->where('name', 'Super Admin')->value('id');
        $managerRoleId = DB::table('roles')->where('name', 'Manager')->value('id');
        $editorRoleId = DB::table('roles')->where('name', 'Editor')->value('id');
        $userRoleId = DB::table('roles')->where('name', 'User')->value('id');
        $financeRoleId = DB::table('roles')->where('name', 'Finance')->value('id');
        $humanRoleId = DB::table('roles')->where('name', 'Human')->value('id');
        $salesRoleId = DB::table('roles')->where('name', 'Sales')->value('id');
        $technicianRoleId = DB::table('roles')->where('name', 'Technician')->value('id');
        $clerkRoleId = DB::table('roles')->where('name', 'Clerk')->value('id');

        $managementDeptId = DB::table('departments')->where('name', 'Management')->value('id');
        $itDeptId = DB::table('departments')->where('name', 'IT & System Administration')->value('id');
        $opsDeptId = DB::table('departments')->where('name', 'Operations & Settlement')->value('id');
        $technicalDeptId = DB::table('departments')->where('name', 'Technical Services')->value('id');
        $financeDeptId = DB::table('departments')->where('name', 'Finance & Accounting')->value('id');
        $hrDeptId = DB::table('departments')->where('name', 'Human Resources')->value('id');

        $organizationIds = DB::table('organizations')->pluck('id', 'code');

        // 3. System Core Users (Using DB query builder to bypass Eloquent events)
        $users = [
            ['name' => 'Super Admin', 'email' => 'admin@example.com', 'role_id' => $adminRoleId, 'department_id' => $itDeptId],
            ['name' => 'Manager User', 'email' => 'manager@example.com', 'role_id' => $managerRoleId, 'department_id' => $managementDeptId],
            ['name' => 'Editor User', 'email' => 'editor@example.com', 'role_id' => $editorRoleId, 'department_id' => $opsDeptId],
            ['name' => 'Finance User', 'email' => 'finance@example.com', 'role_id' => $financeRoleId, 'department_id' => $opsDeptId],
            ['name' => 'Human Resources User', 'email' => 'hr@example.com', 'role_id' => $humanRoleId, 'department_id' => $opsDeptId],
            ['name' => 'Sales User', 'email' => 'sales@example.com', 'role_id' => $salesRoleId, 'department_id' => $opsDeptId],
            ['name' => 'Standard User', 'email' => 'user@example.com', 'role_id' => $userRoleId, 'department_id' => $opsDeptId],
            ['name' => 'Operations Clerk', 'email' => 'clerk@example.com', 'role_id' => $clerkRoleId, 'department_id' => $opsDeptId],
            ['name' => 'Technical Services Manager', 'email' => 'technical.manager@example.com', 'role_id' => $managerRoleId, 'department_id' => $technicalDeptId],
            ['name' => 'Workshop Technician One', 'email' => 'technician.one@example.com', 'role_id' => $technicianRoleId, 'department_id' => $technicalDeptId],
            ['name' => 'Workshop Technician Two', 'email' => 'technician.two@example.com', 'role_id' => $technicianRoleId, 'department_id' => $technicalDeptId],
            ['name' => 'Field Technician', 'email' => 'technician.field@example.com', 'role_id' => $technicianRoleId, 'department_id' => $technicalDeptId],
        ];

        // Payroll test users are always created because AttendancePayrollSeeder
        // depends on them regardless of demo mode.
        $users = array_merge($users, [
            ['name' => 'Payroll Age 60 Test User', 'email' => 'payroll.age60@example.com', 'role_id' => $userRoleId, 'department_id' => $financeDeptId],
            ['name' => 'Payroll Age 75 Test User', 'email' => 'payroll.age75@example.com', 'role_id' => $userRoleId, 'department_id' => $financeDeptId],
            ['name' => 'Payroll Non Malaysian Test User', 'email' => 'payroll.foreign@example.com', 'role_id' => $userRoleId, 'department_id' => $technicalDeptId],
        ]);

        if ($demo) {
            $testFinanceRoleId = DB::table('roles')->where('name', 'Test Finance')->value('id');
            $testApRoleId = DB::table('roles')->where('name', 'Test AP')->value('id');
            $testArRoleId = DB::table('roles')->where('name', 'Test AR')->value('id');
            $testHrRoleId = DB::table('roles')->where('name', 'Test HR')->value('id');

            $users = array_merge($users, [
                ['name' => 'Finance Test User', 'email' => 'finance.test@example.com', 'role_id' => $testFinanceRoleId, 'department_id' => $financeDeptId],
                ['name' => 'AP Test User', 'email' => 'ap.test@example.com', 'role_id' => $testApRoleId, 'department_id' => $financeDeptId],
                ['name' => 'AR Test User', 'email' => 'ar.test@example.com', 'role_id' => $testArRoleId, 'department_id' => $financeDeptId],
                ['name' => 'HR Test User', 'email' => 'hr.test@example.com', 'role_id' => $testHrRoleId, 'department_id' => $hrDeptId],
            ]);
        }

        foreach ($users as $u) {
            $organizationCode = match ($u['role_id']) {
                $adminRoleId => 'CEO',
                $managerRoleId => 'MANAGER',
                $technicianRoleId => 'TECHNICIAN',
                $financeRoleId, $testFinanceRoleId ?? null, $testApRoleId ?? null, $testArRoleId ?? null => 'ACCOUNTANT',
                default => 'STAFF',
            };
            $existingId = DB::table('users')->where('email', $u['email'])->value('id');

            DB::table('users')->updateOrInsert(
                ['email' => $u['email']],
                [
                    'id' => $existingId ?? (string) Str::uuid7(),
                    'name' => $u['name'],
                    'password' => Hash::make('password'),
                    'role_id' => $u['role_id'],
                    'department_id' => $u['department_id'],
                    'organization_id' => $organizationIds[$organizationCode],
                    'email_verified_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        $managerUserId = DB::table('users')->where('email', 'manager@example.com')->value('id');
        $editorUserId = DB::table('users')->where('email', 'editor@example.com')->value('id');
        $standardUserId = DB::table('users')->where('email', 'user@example.com')->value('id');

        // 4. System Configuration Settings
        $malaysiaTimezoneId = DB::table('timezones')->where('zone', 'Asia/Kuala_Lumpur')->value('id');
        $myrCurrencyId = DB::table('currencies')->where('code', 'MYR')->value('id');

        DB::table('settings')->updateOrInsert(
            ['company_email' => 'support@tada.com'],
            [
                'id' => DB::table('settings')->where('company_email', 'support@tada.com')->value('id') ?? (string) Str::uuid7(),
                'timezone_uuid' => $malaysiaTimezoneId,
                'currency_id' => $myrCurrencyId,
                'company_name' => 'REDFLUZ BERHAD',
                'company_registration_no' => '202001234567',
                'company_tax_no' => 'C1234567890',
                'company_phone' => '+603-8888-9999',
                'company_mobile' => '+6012-345-6789',
                'company_postcode' => '63000',
                'company_city' => 'Cyberjaya',
                'company_state' => 'Selangor',
                'company_country' => 'Malaysia',
                'logo_path' => null,
                'short_date_format' => 'd/m/Y',
                'long_date_format' => 'd/m/Y H:i:s',
                'address' => "Level 20, TADA Tower\nPersiaran Multimedia\nCyberjaya, Selangor",
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // 5. Notifications
        $notificationsData = [
            [
                'id' => (string) Str::uuid7(),
                'type' => AdminBroadcastNotification::class,
                'notifiable_type' => 'App\Models\System\User',
                'notifiable_id' => $standardUserId,
                'data' => json_encode([
                    'title' => 'Scheduled System Upgrade',
                    'message' => 'The TADA platform will undergo scheduled maintenance tonight at 11:00 PM UTC.',
                ]),
                'read_at' => null,
                'created_at' => now()->subMinutes(20),
            ],
            [
                'id' => (string) Str::uuid7(),
                'type' => AdminBroadcastNotification::class,
                'notifiable_type' => 'App\Models\System\User',
                'notifiable_id' => $standardUserId,
                'data' => json_encode([
                    'title' => 'Welcome to TADA',
                    'message' => 'Your account has been successfully created and configured with standard access.',
                ]),
                'read_at' => now()->subHour(),
                'created_at' => now()->subDays(2),
            ],
            [
                'id' => (string) Str::uuid7(),
                'type' => AdminBroadcastNotification::class,
                'notifiable_type' => 'App\Models\System\User',
                'notifiable_id' => $managerUserId,
                'data' => json_encode([
                    'title' => 'Monthly CRM Ledger Review',
                    'message' => 'Please review pending credit entries for Acme Corporation before the end of the week.',
                ]),
                'read_at' => null,
                'created_at' => now()->subHours(2),
            ],
            [
                'id' => (string) Str::uuid7(),
                'type' => AdminBroadcastNotification::class,
                'notifiable_type' => 'App\Models\System\User',
                'notifiable_id' => $editorUserId,
                'data' => json_encode([
                    'title' => 'New Article Comments',
                    'message' => 'Your recent article received new engagement from readers.',
                ]),
                'read_at' => null,
                'created_at' => now()->subMinutes(10),
            ],
        ];

        foreach ($notificationsData as $notif) {
            if ($notif['notifiable_id']) {
                DB::table('notifications')->updateOrInsert(
                    ['id' => $notif['id']],
                    [
                        'type' => $notif['type'],
                        'notifiable_type' => $notif['notifiable_type'],
                        'notifiable_id' => $notif['notifiable_id'],
                        'data' => $notif['data'],
                        'read_at' => $notif['read_at'],
                        'created_at' => $notif['created_at'],
                        'updated_at' => $notif['created_at'],
                    ]
                );
            }
        }
    }
}
