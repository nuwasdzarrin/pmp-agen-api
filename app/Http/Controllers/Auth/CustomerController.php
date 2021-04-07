<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;
use DB;

class CustomerController extends Controller
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

    public function register(Request $request)
    {
      $this->validate($request,[
        'email' => 'required|unique:m_customers,email',
        'name' => 'required',
        'password' => 'required',
        'gender' => 'required',
        'phone' => 'required',
      ]);
      DB::beginTransaction();
      DB::table('m_customers')->insert([
        'name' => $request->name,
        'email' => $request->email,
        'gender' => $request->gender,
        'phone' => $request->phone,
        'password' => Hash::make($request->password),
      ]);
      DB::commit();
      return response()->json(['message' => 'OK']);
    }

    public function login(Request $request)
    {
      // dd($request);
      $this->validate($request,[
        'email' => 'required',
        'password' => 'required'
      ]);
      $user = Customer::where('email', $request->email)->first();
      if (!$user) return response()->json(['status' => 'ERROR','message' => 'Unauthenticated'],401);
      if (!Hash::check($request->password,$user->password)) return response()->json(['status' => 'ERROR','message' => 'Unauthenticated'],401);
      $user->token = $user->createToken('Customer',['customer'])->accessToken;
      return response()->json([
        'status' => 'OK',
        'data' => $user
      ]);
    }

    public function get_users()
    {
        $customers = Customer::query()->get();
        return response()->json(['message' => 'OK', 'data' => $customers]);
    }

    public function get_user()
    {
      return response()->json(['message' => 'OK', 'data' => auth('customer')->user()]);
    }

    public function logout()
    {
      DB::table('m_customers')->where('id', auth('customer')->id())->update([
        'selected_branch_id' => null,
        'latitude' => null,
        'longitude' => null,
      ]);
      auth('customer')->user()->token()->revoke();
      return response()->json(['message' => 'OK']);
    }

    //
}
