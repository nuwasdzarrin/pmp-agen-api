<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class NumberingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('t_numberings')->insert([
        'counter' => 1
      ]);
    }
}
