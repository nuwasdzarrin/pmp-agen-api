<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BranchUser;
use Illuminate\Support\Facades\Hash;
use DB;

class BranchUserController extends Controller
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
      $user = BranchUser::where('username', $request->username)->first();
      if (!$user) return response()->json(['status' => 'ERROR','message' => 'Unauthenticated'],401);
      if (!Hash::check($request->password,$user->password)) return response()->json(['status' => 'ERROR','message' => 'Unauthenticated'],401);
      $user->token = $user->createToken('BranchUser',['vendor'])->accessToken;
      return response()->json([
        'status' => 'OK',
        'data' => $user
      ]);
    }

    public function get_user()
    {
      return response()->json(['message' => 'OK', 'data' => auth('vendor')->user()]);
    }

    public function logout()
    {
      auth('vendor')->user()->token()->revoke();
      return response()->json(['message' => 'OK']);
    }

    //
}
