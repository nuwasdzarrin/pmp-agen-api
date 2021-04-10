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
          'name' => 'Order'
        ],
        [
          'id' => 2,
          'name' => 'Process'
        ],
        [
          'id' => 3,
          'name' => 'Shipping'
        ],
        [
          'id' => 4,
          'name' => 'Finish'
        ],
        [
          'id' => 5,
          'name' => 'Cancel'
        ],
      ];
      DB::table('m_statuses')->insert($data);
    }
}
