<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('app_bookings', function (Blueprint $table) {
            $table->string('outbound_class')->default('economy')->after('ticket_class');
            $table->string('return_class')->default('economy')->after('outbound_class');
        });
    }

    public function down(): void
    {
        Schema::table('app_bookings', function (Blueprint $table) {
            $table->dropColumn(['outbound_class', 'return_class']);
        });
    }
};
