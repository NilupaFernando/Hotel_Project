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
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hotel_owner_id')->nullable();
            $table->unsignedBigInteger('district_id');
            $table->string('accommodation_type');
            $table->string('province_name');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('category');
            $table->string('location')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('thumbnail_image');
            $table->json('images')->nullable();
            $table->decimal('star', 3, 1)->nullable(); // Allows ratings like 4.5
            $table->decimal('price_per_night', 10, 2);
            $table->string('amenities');
            $table->string('room_types')->nullable();
            $table->time('check_in_time')->default('14:00:00');
            $table->time('check_out_time')->default('11:00:00');
            $table->string('contact_number');
            $table->string('email')->unique();
            $table->string('website')->nullable();
            $table->timestamps();

            // Foreign Key Constraints
            $table->foreign('district_id')->references('id')->on('districts')->onDelete('cascade');
            $table->foreign('hotel_owner_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotels');
    }
};
