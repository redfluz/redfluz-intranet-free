<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Seeds all Malaysia national, state-specific, and company holidays for 2026 and 2027
 * per the official gazetted lists on publicholidays.com.my.
 */
class HolidaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $holidays = [
            // ==========================================
            // 2026 HOLIDAYS
            // ==========================================

            // National Holidays
            ['name' => "New Year's Day", 'date' => '2026-01-01', 'type' => 'public'],
            ['name' => 'Chinese New Year', 'date' => '2026-02-17', 'type' => 'public'],
            ['name' => 'Chinese New Year Holiday', 'date' => '2026-02-18', 'type' => 'public'],
            ['name' => 'Hari Raya Aidilfitri Holiday', 'date' => '2026-03-20', 'type' => 'public'],
            ['name' => 'Hari Raya Aidilfitri', 'date' => '2026-03-21', 'type' => 'public'],
            ['name' => 'Hari Raya Aidilfitri Holiday', 'date' => '2026-03-22', 'type' => 'public'],
            ['name' => 'Hari Raya Aidilfitri Holiday', 'date' => '2026-03-23', 'type' => 'public'],
            ['name' => 'Labour Day', 'date' => '2026-05-01', 'type' => 'public'],
            ['name' => 'Hari Raya Haji', 'date' => '2026-05-27', 'type' => 'public'],
            ['name' => 'Wesak Day', 'date' => '2026-05-31', 'type' => 'public'],
            ['name' => 'Birthday of Yang di-Pertuan Agong', 'date' => '2026-06-01', 'type' => 'public'],
            ['name' => 'Wesak Day Holiday', 'date' => '2026-06-02', 'type' => 'public'],
            ['name' => 'Awal Muharram (Hijri New Year)', 'date' => '2026-06-17', 'type' => 'public'],
            ['name' => "Prophet Muhammad's Birthday (Maulidur Rasul)", 'date' => '2026-08-25', 'type' => 'public'],
            ['name' => 'National Day (Merdeka Day)', 'date' => '2026-08-31', 'type' => 'public'],
            ['name' => 'Malaysia Day', 'date' => '2026-09-16', 'type' => 'public'],
            ['name' => 'Deepavali', 'date' => '2026-11-08', 'type' => 'public'],
            ['name' => 'Deepavali Holiday', 'date' => '2026-11-09', 'type' => 'public'],
            ['name' => 'Christmas Day', 'date' => '2026-12-25', 'type' => 'public'],

            // State-Specific Holidays
            ['name' => "YDPB Negeri Sembilan's Birthday", 'date' => '2026-01-14', 'type' => 'state'],
            ['name' => 'Israk and Mikraj', 'date' => '2026-01-17', 'type' => 'state'],
            ['name' => 'Israk and Mikraj Holiday', 'date' => '2026-01-18', 'type' => 'state'],
            ['name' => 'Thaipusam', 'date' => '2026-02-01', 'type' => 'state'],
            ['name' => 'Federal Territory Day', 'date' => '2026-02-01', 'type' => 'state'],
            ['name' => 'Thaipusam Holiday', 'date' => '2026-02-02', 'type' => 'state'],
            ['name' => 'Federal Territory Day Holiday', 'date' => '2026-02-03', 'type' => 'state'],
            ['name' => 'Awal Ramadan', 'date' => '2026-02-19', 'type' => 'state'],
            ['name' => 'Independence Declaration Day', 'date' => '2026-02-20', 'type' => 'state'],
            ['name' => 'Installation of Sultan Terengganu', 'date' => '2026-03-04', 'type' => 'state'],
            ['name' => 'Nuzul Al-Quran', 'date' => '2026-03-07', 'type' => 'state'],
            ['name' => 'Nuzul Al-Quran Holiday', 'date' => '2026-03-08', 'type' => 'state'],
            ['name' => "Sultan of Johor's Birthday", 'date' => '2026-03-23', 'type' => 'state'],
            ['name' => 'Hari Raya Aidilfitri Holiday (Johor)', 'date' => '2026-03-24', 'type' => 'state'],
            ['name' => "Sabah Governor's Birthday", 'date' => '2026-03-30', 'type' => 'state'],
            ['name' => 'Good Friday', 'date' => '2026-04-03', 'type' => 'state'],
            ['name' => "Sultan of Terengganu's Birthday", 'date' => '2026-04-26', 'type' => 'state'],
            ['name' => 'Labour Day Holiday (Kedah)', 'date' => '2026-05-03', 'type' => 'state'],
            ['name' => "Raja Perlis' Birthday", 'date' => '2026-05-17', 'type' => 'state'],
            ['name' => "Raja Perlis' Birthday Holiday", 'date' => '2026-05-18', 'type' => 'state'],
            ['name' => 'Hari Hol Pahang', 'date' => '2026-05-22', 'type' => 'state'],
            ['name' => 'Arafat Day', 'date' => '2026-05-26', 'type' => 'state'],
            ['name' => 'Hari Raya Haji Holiday (State)', 'date' => '2026-05-28', 'type' => 'state'],
            ['name' => 'Harvest Festival (Kaamatan)', 'date' => '2026-05-30', 'type' => 'state'],
            ['name' => 'Harvest Festival Holiday', 'date' => '2026-05-31', 'type' => 'state'],
            ['name' => 'Hari Gawai', 'date' => '2026-06-01', 'type' => 'state'],
            ['name' => 'Hari Gawai Holiday', 'date' => '2026-06-02', 'type' => 'state'],
            ['name' => 'Harvest Festival Holiday', 'date' => '2026-06-03', 'type' => 'state'],
            ['name' => 'Wesak Day Holiday (Sarawak)', 'date' => '2026-06-03', 'type' => 'state'],
            ['name' => 'Hari Gawai Holiday', 'date' => '2026-06-04', 'type' => 'state'],
            ['name' => "Sultan of Kedah's Birthday", 'date' => '2026-07-05', 'type' => 'state'],
            ['name' => 'Georgetown World Heritage City Day', 'date' => '2026-07-07', 'type' => 'state'],
            ['name' => "Penang Governor's Birthday", 'date' => '2026-07-11', 'type' => 'state'],
            ['name' => 'Hari Hol Almarhum Sultan Iskandar', 'date' => '2026-07-21', 'type' => 'state'],
            ['name' => 'Sarawak Day', 'date' => '2026-07-22', 'type' => 'state'],
            ['name' => "Sultan of Pahang's Birthday", 'date' => '2026-07-31', 'type' => 'state'],
            ['name' => "Melaka Governor's Birthday", 'date' => '2026-08-24', 'type' => 'state'],
            ['name' => "Sultan of Kelantan's Birthday", 'date' => '2026-09-29', 'type' => 'state'],
            ['name' => "Sultan of Kelantan's Birthday Holiday", 'date' => '2026-09-30', 'type' => 'state'],
            ['name' => "Sarawak Governor's Birthday", 'date' => '2026-10-10', 'type' => 'state'],
            ['name' => "Sultan of Perak's Birthday", 'date' => '2026-11-06', 'type' => 'state'],
            ['name' => "Sultan of Selangor's Birthday", 'date' => '2026-12-11', 'type' => 'state'],
            ['name' => 'Christmas Eve', 'date' => '2026-12-24', 'type' => 'state'],
            ['name' => 'Christmas Holiday (Kedah)', 'date' => '2026-12-27', 'type' => 'state'],

            // Company Holidays
            ['name' => 'TADA Corporation Anniversary', 'date' => '2026-10-05', 'type' => 'company'],

            // ==========================================
            // 2027 HOLIDAYS
            // ==========================================

            // National Holidays
            ['name' => "New Year's Day", 'date' => '2027-01-01', 'type' => 'public'],
            ['name' => 'Chinese New Year', 'date' => '2027-02-06', 'type' => 'public'],
            ['name' => 'Chinese New Year Holiday', 'date' => '2027-02-07', 'type' => 'public'],
            ['name' => 'Chinese New Year Holiday', 'date' => '2027-02-08', 'type' => 'public'],
            ['name' => 'Hari Raya Aidilfitri', 'date' => '2027-03-10', 'type' => 'public'],
            ['name' => 'Hari Raya Aidilfitri Holiday', 'date' => '2027-03-11', 'type' => 'public'],
            ['name' => 'Labour Day', 'date' => '2027-05-01', 'type' => 'public'],
            ['name' => 'Hari Raya Haji', 'date' => '2027-05-17', 'type' => 'public'],
            ['name' => 'Wesak Day', 'date' => '2027-05-20', 'type' => 'public'],
            ['name' => 'Awal Muharram (Hijri New Year)', 'date' => '2027-06-06', 'type' => 'public'],
            ['name' => 'Birthday of Yang di-Pertuan Agong', 'date' => '2027-06-07', 'type' => 'public'],
            ['name' => 'Awal Muharram Holiday', 'date' => '2027-06-07', 'type' => 'public'],
            ['name' => "Prophet Muhammad's Birthday (Maulidur Rasul)", 'date' => '2027-08-15', 'type' => 'public'],
            ['name' => "Prophet Muhammad's Birthday Holiday", 'date' => '2027-08-16', 'type' => 'public'],
            ['name' => 'National Day (Merdeka Day)', 'date' => '2027-08-31', 'type' => 'public'],
            ['name' => 'Malaysia Day', 'date' => '2027-09-16', 'type' => 'public'],
            ['name' => 'Deepavali', 'date' => '2027-10-28', 'type' => 'public'],
            ['name' => 'Christmas Day', 'date' => '2027-12-25', 'type' => 'public'],

            // State-Specific Holidays
            ['name' => 'Israk and Mikraj', 'date' => '2027-01-06', 'type' => 'state'],
            ['name' => "YDPB Negeri Sembilan's Birthday", 'date' => '2027-01-14', 'type' => 'state'],
            ['name' => 'Thaipusam', 'date' => '2027-01-22', 'type' => 'state'],
            ['name' => 'Thaipusam Holiday', 'date' => '2027-01-24', 'type' => 'state'],
            ['name' => 'Federal Territory Day', 'date' => '2027-02-01', 'type' => 'state'],
            ['name' => 'Awal Ramadan', 'date' => '2027-02-08', 'type' => 'state'],
            ['name' => 'Independence Declaration Day', 'date' => '2027-02-20', 'type' => 'state'],
            ['name' => 'Nuzul Al-Quran', 'date' => '2027-02-24', 'type' => 'state'],
            ['name' => 'Installation of Sultan Terengganu', 'date' => '2027-03-04', 'type' => 'state'],
            ['name' => "Sultan of Johor's Birthday", 'date' => '2027-03-23', 'type' => 'state'],
            ['name' => 'Good Friday', 'date' => '2027-03-26', 'type' => 'state'],
            ['name' => "Sabah Governor's Birthday", 'date' => '2027-03-30', 'type' => 'state'],
            ['name' => "Sultan of Terengganu's Birthday", 'date' => '2027-04-26', 'type' => 'state'],
            ['name' => 'Labour Day Holiday (Kelantan & Terengganu)', 'date' => '2027-05-02', 'type' => 'state'],
            ['name' => 'Arafat Day', 'date' => '2027-05-16', 'type' => 'state'],
            ['name' => "Raja Perlis' Birthday", 'date' => '2027-05-17', 'type' => 'state'],
            ['name' => 'Hari Raya Haji Holiday (State)', 'date' => '2027-05-18', 'type' => 'state'],
            ['name' => 'Hari Hol Pahang', 'date' => '2027-05-22', 'type' => 'state'],
            ['name' => 'Harvest Festival (Kaamatan)', 'date' => '2027-05-30', 'type' => 'state'],
            ['name' => 'Harvest Festival Holiday', 'date' => '2027-05-31', 'type' => 'state'],
            ['name' => 'Hari Gawai', 'date' => '2027-06-01', 'type' => 'state'],
            ['name' => 'Hari Gawai Holiday', 'date' => '2027-06-02', 'type' => 'state'],
            ['name' => "Sultan of Kedah's Birthday", 'date' => '2027-06-20', 'type' => 'state'],
            ['name' => 'Georgetown World Heritage City Day', 'date' => '2027-07-07', 'type' => 'state'],
            ['name' => "Penang Governor's Birthday", 'date' => '2027-07-10', 'type' => 'state'],
            ['name' => 'Hari Hol Almarhum Sultan Iskandar', 'date' => '2027-07-10', 'type' => 'state'],
            ['name' => 'Sarawak Day', 'date' => '2027-07-22', 'type' => 'state'],
            ['name' => "Sultan of Pahang's Birthday", 'date' => '2027-07-30', 'type' => 'state'],
            ['name' => "Melaka Governor's Birthday", 'date' => '2027-08-24', 'type' => 'state'],
            ['name' => "Sultan of Kelantan's Birthday", 'date' => '2027-09-29', 'type' => 'state'],
            ['name' => "Sultan of Kelantan's Birthday Holiday", 'date' => '2027-09-30', 'type' => 'state'],
            ['name' => "Sarawak Governor's Birthday", 'date' => '2027-10-09', 'type' => 'state'],
            ['name' => "Sultan of Perak's Birthday", 'date' => '2027-11-05', 'type' => 'state'],
            ['name' => "Sultan of Selangor's Birthday", 'date' => '2027-12-11', 'type' => 'state'],
            ['name' => 'Christmas Eve', 'date' => '2027-12-24', 'type' => 'state'],
            ['name' => 'Christmas Holiday (Kelantan & Terengganu)', 'date' => '2027-12-26', 'type' => 'state'],
            ['name' => 'Israk and Mikraj (2nd Observation)', 'date' => '2027-12-26', 'type' => 'state'],
            ['name' => 'Israk and Mikraj Holiday', 'date' => '2027-12-27', 'type' => 'state'],

            // Company Holidays
            ['name' => 'TADA Corporation Anniversary', 'date' => '2027-10-05', 'type' => 'company'],
        ];

        foreach ($holidays as $holiday) {
            DB::table('holidays')->updateOrInsert(
                [
                    'name' => $holiday['name'],
                    'date' => $holiday['date'],
                ],
                [
                    'id' => (string) Str::uuid7(),
                    'type' => $holiday['type'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
