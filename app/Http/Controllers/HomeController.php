<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use Faker;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function transaction_chart(Request $request)
    {
      $faker = Faker\Factory::create();
      $now = Carbon::now();
      $statuses = DB::table('m_statuses')->get();
      $labels=array();
      $rawdata=array();
      $datasets=array();
      for ($i=0; $i < 7; $i++) {
        $date = $now->copy()->subDay($i);
        $all = DB::table('t_transactions as t')
        ->leftJoin('m_statuses as s','s.id','t.status_id')
        ->whereDate('t.created_at',$date)
        ->groupBy('t.status_id')
        ->select('t.status_id','s.name',DB::raw('count(t.id) as total'))
        ->get();
        array_push($rawdata,$all);
        array_push($labels,$date->format('d/m'));
      }
      // return response()->json($rawdata);
      foreach ($statuses as $v) {
        $row=array();
        foreach ($rawdata as $r) {
          $isi=0;
          foreach ($r as $k) {
            if($k->status_id==$v->id){
              $isi=$k->total;
              break;
            }
            if($isi>0)break;
          }
          array_push($row,$isi);
        }
        $colorrgb=$faker->rgbColor();
        array_push($datasets,[
          'label' => $v->name,
          'data' => $row,
          'tension' => 0.3,
          'borderColor' => "rgba($colorrgb,1)",
          'backgroundColor' => "rgba($colorrgb,0.1)",
          'borderWidth' => 1
        ]);
      }

      return response()->json([
        'labels' => $labels,
        'datasets' => $datasets,
        'options' => [
          'responsive' => true,
          'maintainAspectRatio' => false
        ]
      ]);
    }

    //
}
