<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use DB;

class BranchAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $branch = DB::table('m_branches')->select('id','name')->get();
      $data=array();
      foreach ($branch as $key => $value) {
        array_push($data,[
          'name' => 'Admin '.$value->name,
          'branch_id' => $value->id,
          'phone' => "081234567",
          'username' => 'admin'.strtolower($value->name),
          'password' => Hash::make('admin')
        ]);
      }
      DB::table('m_branch_users')->insert($data);
    }
}
