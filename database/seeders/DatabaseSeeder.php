<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. إنشاء حساب صاحبة الستوديو (Owner)
        User::updateOrCreate(
            ['email' => 'owner@test.com'],
            [
                'name' => 'owner',
                'password' => Hash::make('123'),
                'finance_password' => Hash::make('1234'), 
                'role' => 'owner',
                'loyalty_points' => 0,
            ]
        );

        // 2. إنشاء حساب الموظفة (Staff)
        User::updateOrCreate(
            ['email' => 'staff@test.com'],
            [
                'name' => 'staff',
                'password' => Hash::make('123'),
                'role' => 'staff',
                'loyalty_points' => 0,
            ]
        );

        // 3. إنشاء حساب زبونة (Customer)
        User::updateOrCreate(
            ['email' => 'customer@test.com'],
            [
                'name' => 'customer',
                'password' => Hash::make('123'),
                'role' => 'customer',
                'loyalty_points' => 0,
            ]
        );
    }
}