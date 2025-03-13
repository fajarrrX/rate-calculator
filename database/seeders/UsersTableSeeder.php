<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Super admin',
            'email' => 'superadmin@asdordigital.com.sg',
            'password' => Hash::make('superadmin'),
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
