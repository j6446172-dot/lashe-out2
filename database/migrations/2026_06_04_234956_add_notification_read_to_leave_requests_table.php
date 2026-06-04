<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('leave_requests', function (Blueprint $table) {
            $table->boolean('notification_read')
                  ->default(false)
                  ->after('status');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('leave_requests', function (Blueprint $table) {
            $table->dropColumn('notification_read');
        });
    }
};
