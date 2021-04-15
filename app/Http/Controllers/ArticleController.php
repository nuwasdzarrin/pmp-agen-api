<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArticleController extends Controller
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
      $data = DB::table('articles')->select('*')->get();
      return response()->json(['data' => $data]);
    }

    public function store(Request $request)
    {
      $this->validate($request,[
        'title' => 'required|string',
        'content' => 'required|string',
        'img' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048|nullable',
      ]);
      $img = $request->hasFile('img') ? $request->file('img')->store('articles') : null;
      DB::beginTransaction();
      DB::table('articles')->insert([
        'title' => $request->title,
        'content' => $request->content,
        'img' => $img,
      ]);
      DB::commit();
      return response()->json(['message' => 'OK']);
    }

    public function show($id)
    {
      $data = DB::table('articles')->where('id', $id)->first();
      return response()->json(['data' => $data]);
    }

    public function update(Request $request, $id)
    {
      $this->validate($request,[
          'title' => 'string|nullable',
          'content' => 'string|nullable',
          'img' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048|nullable',
      ]);
      $old = DB::table('articles')->where('id', $id)->first();
      $img = $request->hasFile('img') ? $request->file('img')->store('articles') : $old->img;
      DB::beginTransaction();
      $data = DB::table('articles')->where('id', $id)->update([
          'title' => $request->title ?? $old->title,
          'content' => $request->content ?? $old->content,
          'img' => $img,
      ]);
      DB::commit();
      return response()->json(['message' => 'OK']);
    }

    public function destroy($id)
    {
      DB::table('articles')->where('id', $id)->delete();
    }
}
