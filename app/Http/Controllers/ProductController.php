<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
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
      $data = DB::table('m_products')->select('*')->get();
      return response()->json(['data' => $data]);
    }

    public function store(Request $request)
    {
      $this->validate($request,[
        'name' => 'required|string',
        'price' => 'required|integer|min:1',
        'stock' => 'required|integer|min:1',
        'img' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048|nullable',
      ]);
      $img = $request->hasFile('img') ? $request->file('img')->store('products') : null;
      DB::beginTransaction();
      DB::table('m_products')->insert([
        'name' => $request->name,
        'price' => $request->price,
        'stock' => $request->stock,
        'img' => $img,
      ]);
      DB::commit();
      return response()->json(['message' => 'OK']);
    }

    public function show($id)
    {
      $data = DB::table('m_products')->where('id', $id)->first();
      return response()->json(['data' => $data]);
    }

    public function update(Request $request, $id)
    {
      $this->validate($request,[
          'name' => 'required|string',
          'price' => 'required|integer|min:1',
          'stock' => 'required|integer|min:1',
          'img' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048|nullable',
      ]);
      $old = DB::table('m_products')->where('id', $id)->first();
      $img = $request->hasFile('img') ? $request->file('img')->store('products') : $old->img;
      DB::beginTransaction();
      $data = DB::table('m_products')->where('id', $id)->update([
        'name' => $request->name,
        'price' => $request->price,
        'stock' => $request->stock,
        'img' => $img,
      ]);
      DB::commit();
      return response()->json(['message' => 'OK']);
    }

    public function destroy($id)
    {
      DB::table('m_products')->where('id', $id)->delete();
    }

    //
}
