<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use DB;

class AdminController extends Controller
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

    public function login(Request $request)
    {
      // dd($request);
      $this->validate($request,[
        'username' => 'required',
        'password' => 'required'
      ]);
      $user = Admin::where('username', $request->username)->first();
      if(!$user) return response()->json(['status' => 'ERROR','message' => 'Unauthenticated'],401);
      if (!Hash::check($request->password,$user->password)) return response()->json(['status' => 'ERROR','message' => 'Unauthenticated'],401);
      $user->token = $user->createToken('Admin',['admin'])->accessToken;
      return response()->json([
        'status' => 'OK',
        'data' => $user
      ]);
    }

    public function get_user()
    {
      return response()->json(['message' => 'OK', 'data' => auth('admin')->user()]);
    }

    public function logout()
    {
      auth('admin')->user()->token()->revoke();
      return response()->json(['message' => 'OK']);
    }

    //
}
