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
        Schema::table('app_bookings', function (Blueprint $table) {
            $table->json('passenger_details')->nullable()->after('passenger_gender');
        });
    }
 
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('app_bookings', function (Blueprint $table) {
            $table->dropColumn('passenger_details');
        });
    }
};
