<?php

namespace Database\Seeders;

use App\Models\Leave\Holiday;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LeaveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $managerRoleId = DB::table('roles')->where('name', 'Manager')->value('id');
        $userRoleId = DB::table('roles')->where('name', 'User')->value('id');

        // Department mapping
        $dealingDeptId = DB::table('departments')->where('name', 'Dealing & Compliance')->value('id');
        $opsDeptId = DB::table('departments')->where('name', 'Operations & Settlement')->value('id');
        $clientDeptId = DB::table('departments')->where('name', 'Client Relations')->value('id');

        // 1. Remisier back-office staff assigned to proper departments
        $staff = [
            [
                'name' => 'Siti Nabila',
                'email' => 'siti.nabila@tada.com',
                'role_id' => $managerRoleId,
                'department_id' => $dealingDeptId,
            ],
            [
                'name' => 'Amirul Hakim',
                'email' => 'amirul.hakim@tada.com',
                'role_id' => $userRoleId,
                'department_id' => $dealingDeptId,
            ],
            [
                'name' => 'Farah Idayu',
                'email' => 'farah.idayu@tada.com',
                'role_id' => $userRoleId,
                'department_id' => $dealingDeptId,
            ],
            [
                'name' => 'Ken Wei Jian',
                'email' => 'ken.weijian@tada.com',
                'role_id' => $userRoleId,
                'department_id' => $opsDeptId,
            ],
            [
                'name' => 'Reza Iskandar',
                'email' => 'reza.iskandar@tada.com',
                'role_id' => $userRoleId,
                'department_id' => $dealingDeptId,
            ],
            [
                'name' => 'Balqis Hana',
                'email' => 'balqis.hana@tada.com',
                'role_id' => $userRoleId,
                'department_id' => $clientDeptId,
            ],
        ];

        $userIds = [];

        foreach ($staff as $person) {
            $existingId = DB::table('users')->where('email', $person['email'])->value('id');
            $userId = $existingId ?? (string) Str::uuid7();

            DB::table('users')->updateOrInsert(
                ['email' => $person['email']],
                [
                    'id' => $userId,
                    'name' => $person['name'],
                    'password' => Hash::make('password'),
                    'role_id' => $person['role_id'],
                    'department_id' => $person['department_id'],
                    'email_verified_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            $userIds[$person['email']] = $userId;
        }

        $approverId = $userIds['siti.nabila@tada.com'];

        // 2. Dummy leave applications (a single plain leave — no types)
        $leaves = [
            [
                'email' => 'amirul.hakim@tada.com',
                'start' => now()->addDays(9),
                'end' => now()->addDays(13),
                'reason' => 'Year-end family trip, booked before the Q4 results season ramp-up.',
                'status' => 'pending',
            ],
            [
                'email' => 'farah.idayu@tada.com',
                'start' => now()->subDays(3),
                'end' => now()->subDays(2),
                'reason' => 'Down with flu after the IPO roadshow site visit.',
                'status' => 'approved',
                'approved_at' => now()->subDays(3),
            ],
            [
                'email' => 'ken.weijian@tada.com',
                'start' => now()->subDay(),
                'end' => now()->subDay(),
                'reason' => 'Personal errand — CDS account matter at the bank.',
                'status' => 'approved',
                'approved_at' => now()->subDays(2),
            ],
            [
                'email' => 'reza.iskandar@tada.com',
                'start' => now()->startOfWeek()->addDays(),
                'end' => now()->startOfWeek()->addDays(3),
                'reason' => 'Pre-planned leave, coverage arranged with Amirul for client calls.',
                'status' => 'approved',
                'approved_at' => now()->subWeek(),
            ],
            [
                'email' => 'balqis.hana@tada.com',
                'start' => now()->addDays(2),
                'end' => now()->addDays(2),
                'reason' => 'Follow-up eye checkup — extended hours monitoring live market feeds.',
                'status' => 'pending',
            ],
            [
                'email' => 'amirul.hakim@tada.com',
                'start' => now()->subDays(15),
                'end' => now()->subDays(15),
                'reason' => 'Emergency — family matter during trading hours, covered by Reza.',
                'status' => 'approved',
                'approved_at' => now()->subDays(15),
            ],
            [
                'email' => 'ken.weijian@tada.com',
                'start' => now()->subDays(20),
                'end' => now()->subDays(18),
                'reason' => 'Requested during a quiet settlement cycle; clashed with month-end reconciliation.',
                'status' => 'rejected',
                'approved_at' => now()->subDays(22),
                'rejection_reason' => 'Clashes with month-end settlement reconciliation — please re-apply for the following week.',
            ],
            [
                'email' => 'farah.idayu@tada.com',
                'start' => now()->addDays(30),
                'end' => now()->addDays(34),
                'reason' => 'Annual leave block ahead of the Bursa Malaysia compliance audit cycle.',
                'status' => 'pending',
            ],
            [
                'email' => 'reza.iskandar@tada.com',
                'start' => now()->subDays(40),
                'end' => now()->subDays(39),
                'reason' => 'Medical leave — food poisoning, MC attached.',
                'status' => 'approved',
                'approved_at' => now()->subDays(40),
            ],
            [
                'email' => 'balqis.hana@tada.com',
                'start' => now()->subDays(5),
                'end' => now()->subDays(5),
                'reason' => "Attending a client's account-opening appointment outside office hours.",
                'status' => 'approved',
                'approved_at' => now()->subDays(6),
            ],
        ];

        foreach ($leaves as $leave) {
            $userId = $userIds[$leave['email']];
            if (! $userId) {
                continue;
            }

            $start = $leave['start'];
            $end = $leave['end'];
            $totalDays = Holiday::workingDaysBetween($start->copy(), $end->copy());

            $existingLeave = DB::table('leaves')
                ->where('user_id', $userId)
                ->where('start_date', $start->toDateString())
                ->where('end_date', $end->toDateString())
                ->first();

            $leaveId = $existingLeave->id ?? (string) Str::uuid7();
            $refNumber = $existingLeave->reference_number ?? ('LV-'.strtoupper(substr(md5($leaveId), 0, 8)));

            DB::table('leaves')->updateOrInsert(
                [
                    'user_id' => $userId,
                    'start_date' => $start->toDateString(),
                    'end_date' => $end->toDateString(),
                ],
                [
                    'id' => $leaveId,
                    'reference_number' => $refNumber,
                    // The intranet edition has a single plain leave; normalise
                    // any legacy type left over from earlier seeds.
                    'type' => 'annual',
                    'approved_by' => in_array($leave['status'], ['approved', 'rejected'], true) ? $approverId : null,
                    'created_by' => $userId,
                    'total_days' => $totalDays,
                    'reason' => $leave['reason'],
                    'status' => $leave['status'],
                    'approved_at' => $leave['approved_at'] ?? null,
                    'rejection_reason' => $leave['rejection_reason'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
