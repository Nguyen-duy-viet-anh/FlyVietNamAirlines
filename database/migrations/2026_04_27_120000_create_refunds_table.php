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
        Schema::create('refunds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('app_bookings')->cascadeOnDelete();
            $table->foreignId('original_transaction_id')->nullable()->constrained('app_booking_transactions')->nullOnDelete();
            $table->decimal('amount', 12, 2);
            $table->string('currency', 10)->default('VND');
            $table->string('method', 50)->nullable();
            $table->text('reason')->nullable();
            $table->enum('status', ['requested', 'processing', 'completed', 'failed', 'cancelled'])->default('requested');
            $table->foreignId('requested_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('processed_at')->nullable();
            $table->json('meta')->nullable();
            $table->boolean('email_sent')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refunds');
    }
};
