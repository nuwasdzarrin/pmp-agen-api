<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helper\Numbering;
use DB;
use Carbon\Carbon;

class TransactionController extends Controller
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
      $data = DB::table('t_transactions as t');
      $data = $data->leftJoin('m_statuses as s','s.id','t.status_id');
      $data = $data->leftJoin('m_products as p','p.id','t.product_id');
      $data = $data->leftJoin('m_customers as c','c.id','t.customer_id');
      if (auth('customer')->user()) {
        /* Customer */
        $data = $data->where('t.customer_id', auth('customer')->id());
      } else {
        /* Admin */
      }
      if ($request->status_id) {
        $data = $data->where('status_id', $request->status_id);
      }
      $data = $data->select([
        't.id',
        't.code',
        's.name as status',
        'p.name as product_name',
        't.total_price as product_price',
        't.status_id',
        't.schedule',
        's.name as status',
        DB::raw("date(t.created_at) as transaction_date"),
        'c.name as customer_name',
//        'c.address as customer_address',
      ]);
      $data = $data->orderBy('status','asc')->orderBy('id','desc')->paginate(20);
      return response()->json(['message' => 'OK', 'data' => $data]);
    }

    public function store(Request $request)
    {
      $this->validate($request,[
        'product_id' => 'required|exists:m_products,id',
        'quantity' => 'required|numeric',
        'total_price' => 'numeric|nullable',
        'schedule' => 'string|nullable',
      ]);
      DB::beginTransaction();
      $code = new Numbering;
      $code = $code->generate();
      $user = auth('customer')->user();
//      if (!$user->selected_branch_id) return response()->json(['message' => 'User Branch not found'],500);
      $product = DB::table('m_products')->where('id', $request->product_id)->first();
      DB::table('t_transactions')->insert([
//        'branch_id' => $user->selected_branch_id,
        'customer_id' => $user->id,
        'status_id' => 1,
        'code' => $code,
        'product_id' => $product->id,
        'quantity' => $request->quantity,
        'total_price' => $request->total_price ? $request->total_price : ($request->quantity * $product->price),
        'schedule' => $request->schedule ? Carbon::parse($request->schedule): Carbon::now()->addDay(),
      ]);
      DB::commit();

      return response()->json(['message' => 'OK']);
    }

    public function show($id)
    {
      $data = DB::table('t_transactions as t');
      $data = $data->leftJoin('m_statuses as s','s.id','t.status_id');
      $data = $data->leftJoin('m_products as p','p.id','t.product_id');
      $data = $data->where('t.id', $id);
      $data = $data->selectRaw('
        t.id,
        t.code,
        t.branch_id,
        t.customer_id,
        t.gender,
        t.branch_user_id,
        t.schedule,
        t.status_id,
        t.address,
        s.name as status,
        p.name as product_name,
        t.total_price
      ');
      $data = $data->first();

      $data->customer = DB::table('m_customers')->where('id', $data->customer_id)->select('name','gender','phone','email')->first();
      $data->customer->address = $data->address;
      $data->vendor_location = DB::table('m_branches')->where('id', $data->branch_id)->select('name','address')->first();
      $data->vendor = DB::table('m_branch_users')->where('id', $data->branch_user_id)->select('name','phone')->first();
      unset($data->address);
      unset($data->branch_user_id);
      unset($data->customer_id);

      return response()->json(['message' => 'OK','data' => $data]);
    }

    public function submit_vendor(Request $request, $id)
    {
      $this->validate($request,[
        'vendor_id' => 'required'
      ]);
      DB::beginTransaction();
      DB::table('t_transactions')->where('id', $id)->update([
        'branch_user_id' => $request->vendor_id,
        'status_id' => 2
      ]);
      DB::commit();
      return response()->json(['message' => 'OK']);
    }

    public function select_vendor($id)
    {
      $dt = DB::table('m_branch_users as bu');
      $dt = $dt->where('bu.branch_id', $id);
      $dt = $dt->select('id','name','phone')->get();

      return response()->json(['message' => 'OK','data' => $dt]);
    }

    //
}
