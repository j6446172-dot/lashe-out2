<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // ✅ أضيفي الأعمدة إذا كانت غير موجودة
            if (!Schema::hasColumn('users', 'last_eye_shape')) {
                $table->string('last_eye_shape')->nullable();
            }
            if (!Schema::hasColumn('users', 'last_lash_duration')) {
                $table->string('last_lash_duration')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['last_eye_shape', 'last_lash_duration']);
        });
    }
};