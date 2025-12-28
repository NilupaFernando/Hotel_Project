<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            // admin
            [
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('12345678'),
                'role' => 'admin',
                'permit_status' => 'active', // Always active for admin
            ],

            // user
            [
                'name' => 'User',
                'email' => 'user@gmail.com',
                'password' => Hash::make('12345678'),
                'role' => 'user',
                'permit_status' => 'active', // Active by default
            ],

            // hotel owner
            [
                'name' => 'Hotel Owner',
                'email' => 'hotelowner@gmail.com',
                'password' => Hash::make('12345678'),
                'role' => 'hotel_owner',
                'permit_status' => 'pending', // Needs admin approval
            ],
            [
                'name' => 'Hotel Owner',
                'email' => 'hotelvilla@gmail.com',
                'password' => Hash::make('12345678'),
                'role' => 'hotel_owner',
                'permit_status' => 'active', // Needs admin approval
            ],
        ]);
    }
}


//[
//    'name' =>  'Admin2',
//    'email' => 'admin1@gmail.com',
//    'password' => Hash::make('12345678'),
//    'role' => 'admin',
//],
