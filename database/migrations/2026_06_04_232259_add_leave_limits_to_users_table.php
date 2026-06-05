<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('annual_leave_limit')->default(14);
            $table->integer('remaining_annual_leave')->default(14);
            $table->integer('sick_leave_limit')->default(7);
            $table->integer('remaining_sick_leave')->default(7);
            $table->integer('emergency_leave_limit')->default(3);
            $table->integer('remaining_emergency_leave')->default(3);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'annual_leave_limit', 'remaining_annual_leave',
                'sick_leave_limit', 'remaining_sick_leave',
                'emergency_leave_limit', 'remaining_emergency_leave'
            ]);
        });
    }
};