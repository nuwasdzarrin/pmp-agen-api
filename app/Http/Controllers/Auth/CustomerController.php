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

    public function login(Request $request)
    {
      $this->validate($request,[
        'email' => 'required',
        'password' => 'required'
      ]);
      $user = Customer::where('email', $request->email)->first();
      if (!$user) return response()->json(['status' => 'ERROR','message' => 'Email/Password not found'],401);
      if (!Hash::check($request->password,$user->password)) return response()->json(['status' => 'ERROR','message' => 'Email/Password not found'],401);
      $user->token = $user->createToken('Customer',['customer'])->accessToken;
      return response()->json([
        'status' => 'OK',
        'data' => $user
      ]);
    }

    public function register(Request $request)
    {
        $this->validate($request,[
            'name' => 'required|string',
            'email' => 'required|unique:m_customers,email',
            'password' => 'required|string',
            'phone' => 'required|string',
            'address' => 'string|nullable',
            'img' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048|nullable',
        ]);

        $img = $request->hasFile('img') ? $request->file('img')->store('agents') : null;
        DB::beginTransaction();
        DB::table('m_customers')->insert([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
            'img' => $img,
        ]);
        DB::commit();
        return response()->json(['message' => 'OK']);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'name' => 'string|nullable',
            'email' => 'email|nullable',
            'password' => 'string|nullable',
            'phone' => 'string|nullable',
            'address' => 'string|nullable',
            'img' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048|nullable',
        ]);

        $old = DB::table('m_customers')->where('id', $id)->first();
        DB::beginTransaction();
        DB::table('m_customers')->where('id', $id)->update([
            'name' => $request->name ? $request->name : $old->name,
            'email' => $request->email ? $request->email : $old->email,
            'password' => $request->password ? Hash::make($request->password) : $old->password,
            'phone' => $request->phone ? $request->phone : $old->phone,
            'address' => $request->address ? $request->address : $old->address,
            'img' => $request->hasFile('img') ? $request->file('img')->store('agents') : $old->img,
        ]);
        DB::commit();
        return response()->json(['message' => 'OK']);
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
        'latitude' => null,
        'longitude' => null,
      ]);
      auth('customer')->user()->token()->revoke();
      return response()->json(['message' => 'OK']);
    }

    public function show($id)
    {
        $data = DB::table('m_customers')->where('id', $id)->first();
        return response()->json(['data' => $data]);
    }

    public function destroy($id)
    {
        DB::table('m_customers')->where('id', $id)->delete();
    }

    //
}
