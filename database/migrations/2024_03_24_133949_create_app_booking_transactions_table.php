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
        Schema::create('app_booking_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('app_bookings')->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->string('payment_method', 50);
            $table->string('transaction_code', 127)->nullable()->unique();
            $table->enum('status', ['pending', 'success', 'failed'])->default('pending');
            $table->json('payment_response')->nullable();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_booking_transactions');
    }
};
