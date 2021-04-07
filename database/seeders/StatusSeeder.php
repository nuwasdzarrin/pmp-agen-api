<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class StatusSeeder extends Seeder
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
          'id' => 1,
          'name' => 'Open Order'
        ],
        [
          'id' => 2,
          'name' => 'Sedang Diproses Admin'
        ],
        [
          'id' => 3,
          'name' => 'On Going'
        ],
        [
          'id' => 4,
          'name' => 'Selesai'
        ],
        [
          'id' => 5,
          'name' => 'Batal'
        ],
      ];
      DB::table('m_statuses')->insert($data);
    }
}
