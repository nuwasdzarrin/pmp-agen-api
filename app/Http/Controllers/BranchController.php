<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class BranchController extends Controller
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

    public function index(Request $request)
    {
      $data = DB::table('m_branches')->select('id','name','address')->get();
      return response()->json(['data' => $data]);
    }

    public function user_branch(Request $request)
    {
      $dt = DB::table('m_branch_users as bu');
      $dt = $dt->leftJoin('m_branches as b','b.id','bu.branch_id');
      if ($request->filled('search')) {
        $dt = $dt->where(function($q) use ($request){
          $q->where('bu.name','like','%'.$request->search.'%');
          $q->orWhere('bu.phone','like','%'.$request->search.'%');
          $q->orWhere('bu.username','like','%'.$request->search.'%');
          $q->orWhere('b.name','like','%'.$request->search.'%');
        });
      }
      $dt = $dt->select('bu.id','bu.name','bu.phone','bu.username','b.name as branch')->paginate(15);
      return response()->json(['data' => $dt]);
    }

    public function select_user_branch(Request $request)
    {
      $this->validate($request,[
        'branch_id' => 'required'
      ]);
      DB::beginTransaction();
      $user = auth('customer')->user();
      DB::table('m_customers')->where('id', $user->id)->update([
        'selected_branch_id' => $request->branch_id
      ]);
      DB::commit();
      return response()->json(['message' => 'OK']);
    }

    public function save_lat_lng(Request $request)
    {
      $this->validate($request,[
        'latitude' => 'required',
        'longitude' => 'required',
      ]);
      DB::beginTransaction();
      $user = auth('customer')->user();
      DB::table('m_customers')->where('id', $user->id)->update([
        'latitude' => $request->latitude,
        'longitude' => $request->longitude
      ]);
      DB::commit();
      return response()->json(['message' => 'OK']);
    }

    public function store(Request $request)
    {
      $this->validate($request,[
        'name' => 'required',
        'address' => 'required',
      ]);
      DB::beginTransaction();
      DB::table('m_branches')->insert([
        'name' => $request->name,
        'address' => $request->address,
      ]);
      DB::commit();
      return response()->json(['message' => 'OK']);
    }

    public function show($id)
    {
      $data = DB::table('m_branches')->where('id', $id)->first();
      return response()->json(['message' => 'OK']);
    }

    public function update(Request $request, $id)
    {
      $this->validate($request,[
        'name' => 'required',
        'address' => 'required',
      ]);
      DB::beginTransaction();
      $data = DB::table('m_branches')->where('id', $id)->update([
        'name' => $request->name,
        'address' => $request->address,
      ]);
      DB::commit();
      return response()->json(['message' => 'OK']);
    }

    public function destroy($id)
    {
      DB::table('m_branches')->where('id', $id)->delete();
    }
}
