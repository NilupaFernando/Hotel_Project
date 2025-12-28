<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HotelTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('hotels')->insert([
            [
                'district_id' => 1,
                'hotel_owner_id' => 3,
                'accommodation_type' => 'Hotel',
                'province_name' => 'Western Province',
                'name' => 'Grand Ocean Hotel',
                'description' => 'A luxurious 5-star hotel with an ocean view, featuring a rooftop infinity pool, spa, and gourmet dining.',
                'category' => 'beach',
                'location' => 'Colombo, Sri Lanka',
                'latitude' => 6.9271,
                'longitude' => 79.8612,
                'thumbnail_image' => 'uploads/images/1.jpeg',
                'images' => json_encode("[\"images\\/1739979667_4943.jpg\",\"images\\/1740136832_13638.jpg\"]"),
                'star' => 4,
                'price_per_night' => 250.00,
                'amenities' => 'WiFi, Pool, Spa, Parking, Gym, Restaurant, Bar',
                'room_types' => '"Deluxe Room,Suite,Presidential Suite"',
                'check_in_time' => '14:00:00',
                'check_out_time' => '11:00:00',
                'contact_number' => '+94 112 345 678',
                'email' => 'info2@grandoceanhotel.com',
                'website' => 'https://grandoceanhotel.com',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'district_id' => 2,  // Assuming Kandy's district_id is 2
                'hotel_owner_id' => 4,
                'accommodation_type' => 'Villa',
                'province_name' => 'Central Province',
                'name' => 'Mountain View Resort',
                'description' => 'A tranquil 5-star resort located in the heart of Kandy, offering panoramic mountain views, serene gardens, and luxurious accommodations.',
                'category' => 'mountain',
                'location' => 'Kandy, Sri Lanka',
                'latitude' => 7.2906,
                'longitude' => 80.6337,
                'thumbnail_image' => 'uploads/images/1739538379_tower-7314495_1280.jpg',
                'images' => json_encode("[\"images\\/1739979667_4943.jpg\",\"images\\/1740136832_13638.jpg\"]"),
                'star' => 5,
                'price_per_night' => 300.00,
                'amenities' => 'WiFi, Pool, Spa, Garden, Gym, Restaurant, Bar, Conference Hall',
                'room_types' => '"Standard Room, Deluxe Room, Honeymoon Suite"',
                'check_in_time' => '14:00:00',
                'check_out_time' => '11:00:00',
                'contact_number' => '+94 812 345 678',
                'email' => 'info@mountainviewresort.com',
                'website' => 'https://mountainviewresort.com',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
