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
        User::create([
            'name' => 'owner',
            'email' => 'owner@test.com',
            'password' => Hash::make('123456789'), // كلمة السر الجديدة
            'role' => 'owner',
            'loyalty_points' => 0,
        ]);

        // 2. إنشاء حساب الموظفة (Staff)
        User::create([
            'name' => 'staff',
            'email' => 'staff@test.com',
            'password' => Hash::make('123456789'),
            'role' => 'staff',
            'loyalty_points' => 0,
        ]);

        // 3. إنشاء حساب زبونة (Customer)
        User::create([
            'name' => 'customer',
            'email' => 'customer@test.com',
            'password' => Hash::make('123456789'),
            'role' => 'customer',
            'loyalty_points' => 0,
        ]);
    }
}