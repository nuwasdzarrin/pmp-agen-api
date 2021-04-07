<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $data = [
        [
          'name' => 'Madiun'
        ]
      ];
      DB::table('m_branches')->insert($data);
    }
}
