<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use DB;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::beginTransaction();
      DB::table('m_admins')->insert([
        'username' => 'admin',
        'password' => Hash::make('admin123')
      ]);
      DB::commit();
    }
}
