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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hotel_id');
            $table->string('type'); // E.g., Single

            $table->integer('bookable_rooms')->default(0);
            $table->integer('booked_rooms')->default(0);
            $table->integer('available_rooms')->default(0);
            $table->integer('max_adult')->default(1);
            $table->integer('max_children')->default(0);
            $table->decimal('price_per_adult', 10, 2);
            $table->decimal('price_per_child', 10, 2)->default(0);
            $table->string('features')->nullable();
            $table->string('free_services')->nullable();
            $table->decimal('discount', 5, 2)->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('hotel_id')->references('id')->on('hotels')->onDelete('cascade');

            $table->index(['hotel_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};

