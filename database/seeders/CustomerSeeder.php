<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use DB;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('m_customers')->insert([
        'name' => 'User Test',
        'email' => 'user@email.com',
        'phone' => '123456',
        'password' => Hash::make('user123'),
        'gender' => 'L'
      ]);
    }
}
