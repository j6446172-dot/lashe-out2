<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SalonScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $days = [
            ['day_of_week' => 0, 'is_open' => true, 'start_time' => '10:00', 'end_time' => '18:00'], // Sunday
            ['day_of_week' => 1, 'is_open' => true, 'start_time' => '10:00', 'end_time' => '18:00'], // Monday
            ['day_of_week' => 2, 'is_open' => true, 'start_time' => '10:00', 'end_time' => '18:00'], // Tuesday
            ['day_of_week' => 3, 'is_open' => true, 'start_time' => '10:00', 'end_time' => '18:00'], // Wednesday
            ['day_of_week' => 4, 'is_open' => true, 'start_time' => '10:00', 'end_time' => '18:00'], // Thursday
            ['day_of_week' => 5, 'is_open' => true, 'start_time' => '10:00', 'end_time' => '18:00'], // Friday
            ['day_of_week' => 6, 'is_open' => false, 'start_time' => null, 'end_time' => null], // Saturday (closed)
        ];

        foreach ($days as $day) {
            DB::table('salon_schedule')->insert($day);
        }
    }
}