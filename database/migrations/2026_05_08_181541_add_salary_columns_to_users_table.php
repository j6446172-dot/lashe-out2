<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'base_salary')) {
                $table->decimal('base_salary', 10, 2)->default(350);
            }
            if (!Schema::hasColumn('users', 'bonus')) {
                $table->decimal('bonus', 10, 2)->default(0);
            }
            if (!Schema::hasColumn('users', 'deduction')) {
                $table->decimal('deduction', 10, 2)->default(0);
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['base_salary', 'bonus', 'deduction']);
        });
    }
};