<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $init = [
        [
          'name' => 'Es Balok',
          'price' => 20000,
          'stock' => 0
        ],
        [
          'name' => 'Es Serut',
          'price' => 15000,
          'stock' => 0,
        ],
      ];
      DB::table('m_products')->insert($init);
    }
}
