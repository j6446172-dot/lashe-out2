<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staff_schedule', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('staff_id');
            $table->integer('day_of_week'); // 0=السبت, 1=الأحد, ..., 6=الجمعة
            $table->enum('status', ['active', 'dayoff', 'annual', 'sick', 'emergency', 'unpaid', 'half_day', 'swap'])->default('active');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->timestamps();
            
            // منع تكرار نفس اليوم لنفس الموظف
            $table->unique(['staff_id', 'day_of_week']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_schedule');
    }
};