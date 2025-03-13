<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('countries')->insert([
            'code' => 'SG',
            'currency_code' => 'SGD',
            'name' => 'Singapore',
            'decimal_places' => 2,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('countries')->insert([
            'code' => 'HK',
            'currency_code' => 'HKD',
            'name' => 'Hongkong',
            'decimal_places' => 0,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        DB::table('countries')->insert([
            'code' => 'TH',
            'currency_code' => 'THB',
            'name' => 'Thailand',
            'decimal_places' => 0,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
