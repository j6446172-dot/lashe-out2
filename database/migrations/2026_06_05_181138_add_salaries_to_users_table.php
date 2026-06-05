<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('salaries', 10, 2)->nullable();
            $table->decimal('rent', 10, 2)->nullable();
            $table->decimal('materials_percentage', 5, 2)->nullable();
            $table->decimal('other_expenses', 10, 2)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['salaries', 'rent', 'materials_percentage', 'other_expenses']);
        });
    }
};