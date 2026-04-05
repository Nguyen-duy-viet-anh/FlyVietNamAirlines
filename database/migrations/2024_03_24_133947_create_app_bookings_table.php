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
        Schema::create('app_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code', 32)->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('flight_type', ['one_way', 'round_trip'])->default('one_way');
            $table->foreignId('outbound_flight_id')->constrained('flights')->restrictOnDelete();
            $table->foreignId('return_flight_id')->nullable()->constrained('flights')->restrictOnDelete();

            $table->unsignedInteger('adult_count')->default(1);
            $table->unsignedInteger('child_count')->default(0);
            $table->unsignedInteger('infant_count')->default(0);
            $table->decimal('total_amount', 12, 2);

            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');
            $table->enum('payment_status', ['unpaid', 'paid', 'refunded'])->default('unpaid');

            $table->string('passenger_name', 255);
            $table->string('passenger_email', 255);
            $table->string('passenger_phone', 20);
            $table->enum('passenger_gender', ['male', 'female', 'other'])->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_bookings');
    }
};
