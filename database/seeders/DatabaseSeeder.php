<?php

namespace Database\Seeders;

use App\Http\Controllers\Admin\HotelController;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //no need to change any value inside
        $this->call(UsersTableSeeder::class);
        //no need to change any value inside
        $this->call(ProvincesTableSeeder::class);

        //can change ex:image description ..
        $this->call(DistrictsTableSeeder::class);
        //can change
        $this->call(HotelTableSeeder::class);


    }
}
