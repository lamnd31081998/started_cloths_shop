<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class Order_detail extends Model
{
    use Notifiable;

    protected $table = 'order_details';

    protected $fillable = [

    ];

    public static function getOrderDetailsByOrderId($id)
    {
        return DB::table('order_details')->where('order_id', '=', $id)->orderBy('id')->get();
    }

    public static function createOrderDetails($carts, $order_id)
    {
        foreach ($carts as $index=>$cart) {
            if ($index != 'information') {
                $order_detail = new Order_detail();
                $order_detail->order_id = $order_id;
                $order_detail->product_id = $carts[$index]['id'];
                $order_detail->product_attribute_value_id = $carts[$index]['product_attribute_value_id'];
                $order_detail->quantity = $carts[$index]['quantity'];
                $stock_quantity = DB::table('product_attribute_values')->where('id', '=', $carts[$index]['product_attribute_value_id'])->first()->quantity;
                $update_quantity = $stock_quantity - $carts[$index]['quantity'];
                DB::table('product_attribute_values')->where('id', '=', $carts[$index]['product_attribute_value_id'])->update(['quantity' => $update_quantity, 'updated_at' => Carbon::now()]);
                $order_detail->total_price = $carts[$index]['total_price'];
                if (!$order_detail->save()) {
                    return false;
                }
                $order_detail->save();
            }
        }
    }

    public static function createOrderDetailsfromBE($order_id, $products_id, $product_attribute_values_id, $quantities, $totals_price)
    {
        foreach ($products_id as $index=>$product_id) {
            $order_detail = new Order_detail();
            $order_detail->order_id = $order_id;
            $order_detail->product_id = $product_id;
            $order_detail->product_attribute_value_id = $product_attribute_values_id[$index];
            $stock = Product_Attribute_value::getProductAttributeValueById($product_attribute_values_id[$index])->quantity;
            $update_stock = $stock - $quantities[$index];
            DB::table('product_attribute_values')->where('id', '=', $product_attribute_values_id[$index])->update(['quantity' => $update_stock, 'updated_at' => Carbon::now()]);
            $order_detail->quantity = $quantities[$index];
            $order_detail->total_price = $totals_price[$index];
            if (!$order_detail->save()) {
                return false;
            }
            $order_detail->save();

        }
        return true;
    }
}
