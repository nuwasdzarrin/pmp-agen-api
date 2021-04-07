<?php
namespace App\Helper;
use Carbon\Carbon;
use DB;

class Numbering
{
  protected $formatdate;
  public function __construct()
  {
    $this->formatdate = Carbon::now()->format("Ym");
  }
  protected function counter($value)
  {
    $last_value = $value;
    $angka = '0000';
    $counter = str_repeat("0", 4 - strlen($angka+$last_value)).($angka+$last_value);
    return $counter;
  }
  public function generate()
  {
    $counter = DB::table('t_numberings')->first();
    $format = $this->formatdate."-".$this->counter($counter->counter);
    DB::table('t_numberings')->where('id', $counter->id)->update([
      'counter' => DB::raw('counter+1')
    ]);
    return $format;
  }
}
