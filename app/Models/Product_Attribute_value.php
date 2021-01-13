<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class Product_Attribute_value extends Model
{
    use Notifiable;

    protected $table = 'product_attribute_values';

    protected $fillable = [
      'quantity'
    ];

    public static function getAllProductAttributeValues()
    {
        return DB::table('product_attribute_values')->orderBy('id')->get();
    }

    public static function getProductAttributeValueById($id)
    {
        return DB::table('product_attribute_values')->where('id', '=', $id)->first();
    }

    public static function getProductAttributevalueByProductId($id)
    {
        return DB::table('product_attribute_values')->where('product_id', '=', $id)->get();
    }

    public static function createProductAtributevalues($product_id, $datas)
    {
        foreach ($datas as $data) {
            $attribute_value = new Product_Attribute_value();
            $attribute_value->product_id = $product_id;
            $attribute_value->color_id = $data['color_id'];
            $attribute_value->size_id = $data['size_id'];
            $attribute_value->quantity = $data['quantity'];
            if (!$attribute_value->save()) {
                return false;
            }
            else {
                $attribute_value->save();
            }
        }
        return true;
    }

    public static function updateQuantities($product_id, $data)
    {
        $product_attribute_values = Product_Attribute_value::getProductAttributevalueByProductId($product_id);
        foreach ($product_attribute_values as $index=>$product_attribute_value) {
            DB::table('product_attribute_values')->where('id', '=', $product_attribute_value->id)->update(['quantity' => $data[$index]]);
        }
    }

    public static function updateProductattributevalues($product_id, $datas)
    {
        Product_Attribute_value::where('product_id', $product_id)->delete();
        foreach ($datas as $data) {
            $product_attribute_value = new Product_Attribute_value();
            $product_attribute_value->product_id = $product_id;
            $product_attribute_value->color_id = $data['color_id'];
            $product_attribute_value->size_id = $data['size_id'];
            $product_attribute_value->quantity = $data['quantity'];
            if (!$product_attribute_value->save()) {
                return false;
            }
            $product_attribute_value->save();
        }
        return true;
    }
}
