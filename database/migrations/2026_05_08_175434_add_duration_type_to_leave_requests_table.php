<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('leave_requests', function (Blueprint $table) {
            // إضافة عمود نظام الإجازة (أيام أو ساعات)
            if (!Schema::hasColumn('leave_requests', 'duration_type')) {
                $table->enum('duration_type', ['days', 'hours'])->default('days');
            }
            // إضافة عمود عدد الساعات
            if (!Schema::hasColumn('leave_requests', 'hours')) {
                $table->integer('hours')->nullable();
            }
            // إضافة عمود وقت البداية
            if (!Schema::hasColumn('leave_requests', 'start_time')) {
                $table->time('start_time')->nullable();
            }
            // إضافة عمود وقت النهاية
            if (!Schema::hasColumn('leave_requests', 'end_time')) {
                $table->time('end_time')->nullable();
            }
            // إضافة عمود ملاحظات الإدارة
            if (!Schema::hasColumn('leave_requests', 'admin_notes')) {
                $table->text('admin_notes')->nullable();
            }
            // إضافة عمود تاريخ المراجعة
            if (!Schema::hasColumn('leave_requests', 'reviewed_at')) {
                $table->timestamp('reviewed_at')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('leave_requests', function (Blueprint $table) {
            $table->dropColumn(['duration_type', 'hours', 'start_time', 'end_time', 'admin_notes', 'reviewed_at']);
        });
    }
};