<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvincesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $provinces = [
            ['province_name' => 'Western Province', 'districts' => 'Colombo, Gampaha, Kalutara'],
            ['province_name' => 'Central Province', 'districts' => 'Kandy, Matale, Nuwara Eliya'],
            ['province_name' => 'Southern Province', 'districts' => 'Galle, Matara, Hambantota'],
            ['province_name' => 'Northern Province', 'districts' => 'Jaffna, Kilinochchi, Mannar, Mullaitivu, Vavuniya'],
            ['province_name' => 'Eastern Province', 'districts' => 'Trincomalee, Batticaloa, Ampara'],
            ['province_name' => 'North Western Province', 'districts' => 'Kurunegala, Puttalam'],
            ['province_name' => 'North Central Province', 'districts' => 'Anuradhapura, Polonnaruwa'],
            ['province_name' => 'Uva Province', 'districts' => 'Badulla, Monaragala'],
            ['province_name' => 'Sabaragamuwa Province', 'districts' => 'Ratnapura, Kegalle'],
        ];

        DB::table('provinces')->insert($provinces);
    }
}
