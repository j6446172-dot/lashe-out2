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
    Schema::table('bookings', function (Blueprint $table) {
        $table->string('building_number')->nullable()->after('address_text');
        $table->string('apartment')->nullable()->after('building_number');
    });
}

public function down(): void
{
    Schema::table('bookings', function (Blueprint $table) {
        $table->dropColumn(['building_number', 'apartment']);
    });
}
};
