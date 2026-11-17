<?php

namespace Database\Seeders;

use App\Enums\Claim\ClaimType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ClaimSeeder extends Seeder
{
    public function run(): void
    {
        $users = DB::table('users')->whereIn('email', [
            'amirul.hakim@tada.com', 'farah.idayu@tada.com', 'ken.weijian@tada.com', 'reza.iskandar@tada.com',
        ])->pluck('id', 'email');
        $approver = DB::table('users')->where('email', 'siti.nabila@tada.com')->value('id');
        if ($users->isEmpty()) {
            return;
        }

        $rows = [
            ['ref' => 'CLM-DEMO-0001', 'email' => 'amirul.hakim@tada.com', 'date' => '2026-08-04', 'amount' => 185.50, 'status' => 'pending', 'desc' => 'Tuntutan makan dan tol urusan pelanggan.'],
            ['ref' => 'CLM-DEMO-0002', 'email' => 'farah.idayu@tada.com', 'date' => '2026-08-07', 'amount' => 420.00, 'status' => 'approved', 'desc' => 'Tuntutan perjalanan ke pejabat pelanggan.'],
            ['ref' => 'CLM-DEMO-0003', 'email' => 'ken.weijian@tada.com', 'date' => '2026-08-12', 'amount' => 680.00, 'status' => 'approved', 'desc' => 'Perbelanjaan projek dan bahan pembentangan.'],
            ['ref' => 'CLM-DEMO-0004', 'email' => 'reza.iskandar@tada.com', 'date' => '2026-08-16', 'amount' => 125.00, 'status' => 'submitted', 'desc' => 'Tuntutan rawatan klinik.'],
            ['ref' => 'CLM-DEMO-0005', 'email' => 'amirul.hakim@tada.com', 'date' => '2026-08-20', 'amount' => 350.00, 'status' => 'approved', 'desc' => 'Tuntutan jamuan pelanggan.'],
            ['ref' => 'CLM-DEMO-0006', 'email' => 'farah.idayu@tada.com', 'date' => '2026-08-24', 'amount' => 95.00, 'status' => 'rejected', 'desc' => 'Tuntutan tanpa dokumen sokongan lengkap.'],
        ];
        foreach ($rows as $row) {
            $userId = $users->get($row['email']);
            if (! $userId) {
                continue;
            }
            $existing = DB::table('claims')->where('reference_number', $row['ref'])->value('id');
            DB::table('claims')->updateOrInsert(['reference_number' => $row['ref']], [
                'id' => $existing ?? (string) Str::uuid7(), 'user_id' => $userId, 'approved_by' => $row['status'] === 'approved' ? $approver : null,
                'created_by' => $userId, 'claim_type' => ClaimType::NORMAL->value, 'receipt_date' => $row['date'], 'receipt_no' => 'RC-'.$row['ref'], 'amount' => $row['amount'],
                'description' => $row['desc'], 'status' => $row['status'], 'approved_at' => $row['status'] === 'approved' ? $row['date'].' 10:00:00' : null,
                'created_at' => now(), 'updated_at' => now(),
            ]);
        }
    }
}
