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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')->constrained()->onDelete('cascade'); // ✅  Link to reservation
            $table->decimal('total_price', 10, 2);// ✅]
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('hotel_owner_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['pending', 'confirmed', 'checked_in', 'checked_out', 'cancelled'])->default('pending');// ✅

            $table->string('payment_gateway')->default('payhere');
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->string('transaction_id')->nullable(); // ✅ Store transaction ID from PayHere
            $table->text('payment_response')->nullable(); // ✅ Store PayHere response details
            $table->timestamp('payment_date')->nullable(); // ✅ Store payment time

            $table->timestamp('expire_at')->nullable();// ✅  Expire 1 day before check-in
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
