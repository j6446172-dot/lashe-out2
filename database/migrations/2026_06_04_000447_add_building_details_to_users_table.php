<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // إضافة الأعمدة الجديدة فقط (بدون التحقق لأنها غير موجودة)
            $table->string('default_building_number')->nullable();
            $table->string('default_apartment')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['default_building_number', 'default_apartment']);
        });
    }
};