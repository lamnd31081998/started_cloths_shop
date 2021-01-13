<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Product extends Model
{
    use Notifiable;

    protected $table = 'products';

    protected $fillable = [
      'name', 'slug', 'price', 'sale_price'
    ];

    public static function getAllProduct()
    {
        return DB::table('products')->where('status' , '=', 1)->orderBy('id')->get();
    }

    public static function getLastestProduct()
    {
        return DB::table('products')->where('status', '=', 1)->orderBy('created_at', 'desc')->limit(16)->get();
    }

    public static function getLastestProductSale()
    {
        return DB::table('products')->where('status', '=', '1')->where('sale_price', '!=', "0")->limit(16)->get();
    }

    public static function getProductById($id)
    {
        return DB::table('products')->where('id' ,'=', $id)->first();
    }

    public static function getProductByCategoryId($id)
    {
        return DB::table('products')->where('category_id', '=', $id)->orderBy('created_at', 'desc')->get();
    }

    public static function createProduct($data)
    {
        return DB::table('products')->insertGetId([
            'category_id' => $data['category_id'],
            'name' => $data['name'],
            'slug' => $data['slug'],
            'price' => (int)str_replace(',', '', $data['price']),
            'sale_price' => (int)str_replace(',', '', $data['sale_price']),
            'size_image' => $data['size_image'],
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }

    public static function updateProduct($id, $data)
    {
        return DB::table('products')->where('id', '=', $id)->update([
            'category_id' => $data['category_id'],
            'name' => $data['name'],
            'slug' => $data['slug'],
            'price' => (int)str_replace(',', '', $data['price']),
            'sale_price' => (int)str_replace(',', '', $data['sale_price']),
            'size_image' => $data['size_image'],
            'updated_at' => Carbon::now(),
        ]);
    }
}
