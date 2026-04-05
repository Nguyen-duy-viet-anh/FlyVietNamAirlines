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
        Schema::create('flights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('airline_id')->constrained('airlines')->cascadeOnDelete();
            $table->string('flight_number', 50)->unique();
            $table->foreignId('origin_id')->constrained('airports')->cascadeOnDelete();
            $table->foreignId('destination_id')->constrained('airports')->cascadeOnDelete();
            $table->dateTime('departure_time');
            $table->dateTime('arrival_time');
            $table->unsignedTinyInteger('stops')->default(0);
            $table->decimal('price', 12, 2);
            $table->unsignedInteger('total_seats');
            $table->integer('economy_available')->default(0);
            $table->integer('business_available')->default(0);
            $table->unsignedInteger('available_seats');
            $table->enum('status', ['scheduled', 'delayed', 'cancelled', 'completed'])->default('scheduled');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flights');
    }
};
