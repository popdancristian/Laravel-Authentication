<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
class UserLoginSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_logins')->insert([
            'name' => 'Pop',
            'email' => 'popdancristian@yahoo.com',
            'phone_number' => '111111',
            'password' => Hash::make('test'),
            'verification_code' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
