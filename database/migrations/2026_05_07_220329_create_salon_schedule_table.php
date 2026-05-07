<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('salon_schedule', function (Blueprint $table) {
            $table->id();
            $table->integer('day_of_week'); // 0 = Sunday, 1 = Monday, etc.
            $table->boolean('is_open')->default(true);
            $table->time('open_time')->nullable();
            $table->time('close_time')->nullable();
            $table->time('start_time')->nullable();  // أضف هذا
            $table->time('end_time')->nullable();    // أضف هذا
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salon_schedule');
    }
};