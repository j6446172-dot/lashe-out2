<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class StaffSeeder extends Seeder
{
    public function run()
    {
        $staff = [
            [
                'name' => 'لينا العتوم',
                'email' => 'lina@lasheout.com',
                'phone' => '0790000001',
            ],
            [
                'name' => 'هيا الكردي',
                'email' => 'haya@lasheout.com',
                'phone' => '0790000002',
            ],
            [
                'name' => 'ندى جابر',
                'email' => 'nada@lasheout.com',
                'phone' => '0790000003',
            ],
            [
                'name' => 'سارة أحمد',
                'email' => 'sara@lasheout.com',
                'phone' => '0790000004',
            ],
            [
                'name' => 'ريم عمر',
                'email' => 'reem@lasheout.com',
                'phone' => '0790000005',
            ],
        ];

        foreach ($staff as $s) {
            User::create([
                'name' => $s['name'],
                'email' => $s['email'],
                'password' => Hash::make('password123'),
                'role' => 'staff',
                'phone' => $s['phone'],
                'loyalty_points' => 0
            ]);
        }

        $this->command->info('تم إضافة ' . count($staff) . ' موظفات بنجاح!');
    }
}