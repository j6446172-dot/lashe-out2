<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('leave_requests', function (Blueprint $table) {
            // إضافة خيار الإجازة بالساعات
            $table->enum('duration_type', ['days', 'hours'])->default('days');
            $table->integer('hours')->nullable(); // عدد الساعات (إذا كان duration_type = hours)
            $table->time('start_time')->nullable(); // وقت البداية (لإجازة الساعات)
            $table->time('end_time')->nullable(); // وقت النهاية (لإجازة الساعات)
            
            // ملاحظات الإدارة
            $table->text('admin_notes')->nullable();
            $table->timestamp('reviewed_at')->nullable();
        });
    }

    public function down()
    {
        Schema::table('leave_requests', function (Blueprint $table) {
            $table->dropColumn(['duration_type', 'hours', 'start_time', 'end_time', 'admin_notes', 'reviewed_at']);
        });
    }
};