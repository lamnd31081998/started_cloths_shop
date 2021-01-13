<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    use Notifiable;

    protected $table = 'orders';

    protected $fillable = [
        'name', 'email', 'phone_number', 'address'
    ];

    public static function createOrder($request, $carts)
    {
        if (!isset($request['vnp_PayDate']) && !isset($request['vnp_TransactionNo']) && !isset($request['vnp_TxnRef']) && !isset($request['vnp_OrderInfo'])) {
            $request['vnp_PayDate'] = $request['vnp_TransactionNo'] = $request['vnp_TxnRef'] = $request['vnp_OrderInfo'] = "";
        }

        if (isset($carts['information']['code'])) {
            $max_use = DB::table('promotions')->where('code', '=', $carts['information']['code'])->first()->max_use;
            DB::table('promotions')->where('code', '=', $carts['information']['code'])->update(['max_use' => ($max_use-1), 'updated_at' => Carbon::now()]);
            return DB::table('orders')->insertGetId([
                'name' => $request['name'],
                'email' => $request['email'],
                'phone_number' => $request['phone_number'],
                'tinh_id' => $request['tinh_id'],
                'huyen_id' => $request['huyen_id'],
                'xa_id' => $request['xa_id'],
                'address' => $request['address'],
                'ship_id' => Shipfee::getShipfeeByPrice($carts['information']['ship_fee'])->id,
                'payment_method' => $request['payment_method'],
                'promotion' => $carts['information']['code'],
                'totals' => $carts['information']['totals'],
                'note' => $request['note'],
                'vnp_PayDate' => $request['vnp_PayDate'],
                'vnp_TransactionNo' => $request['vnp_TransactionNo'],
                'vnp_TxnRef' => $request['vnp_TxnRef'],
                'vnp_OrderInfo' => $request['vnp_OrderInfo'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        }

        return DB::table('orders')->insertGetId([
            'name' => $request['name'],
            'email' => $request['email'],
            'phone_number' => $request['phone_number'],
            'tinh_id' => $request['tinh_id'],
            'huyen_id' => $request['huyen_id'],
            'xa_id' => $request['xa_id'],
            'address' => $request['address'],
            'ship_id' => Shipfee::getShipfeeByPrice($carts['information']['ship_fee'])->id,
            'payment_method' => $request['payment_method'],
            'totals' => $carts['information']['totals'],
            'note' => $request['note'],
            'vnp_PayDate' => $request['vnp_PayDate'],
            'vnp_TransactionNo' => $request['vnp_TransactionNo'],
            'vnp_TxnRef' => $request['vnp_TxnRef'],
            'vnp_OrderInfo' => $request['vnp_OrderInfo'],
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }

    public static function createOrderfromBE($data, $totals)
    {
        return DB::table('orders')->insertGetId([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone_number' => $data['phone_number'],
            'address' => $data['address'],
            'tinh_id' => $data['tinh_id'],
            'huyen_id' => $data['huyen_id'],
            'xa_id' => $data['xa_id'],
            'ship_id' => $data['ship_id'],
            'payment_method' => $data['payment_method'],
            'totals' => $totals,
            'note' => $data['note'],
            'status' => 2,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }

    public static function getAllOrders()
    {
        return DB::table('orders')->orderBy('id')->get();
    }

    public static function getOrderById($id)
    {
        return DB::table('orders')->where('id', '=', $id)->first();
    }

    public static function getOrderCountByMonthYear($month, $year)
    {
        return Order::whereYear('created_at', $year)->whereMonth('created_at', $month)->count();
    }

    public static function getOrderCountSuccessByMonthYear($month, $year)
    {
        return Order::whereIn('status', [1,2,3,4])->whereYear('created_at', $year)->whereMonth('created_at', $month)->count();
    }

    public static function getOrderTotalByMonthYear($month, $year)
    {
        return Order::whereIn('status', [1,2,3,4])->whereYear('created_at', $year)->whereMonth('created_at', $month)->sum('totals');
    }
}
