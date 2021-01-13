<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use File;
use Illuminate\Support\Facades\DB;

class Product_images extends Model
{
    use Notifiable;

    protected $table = 'product_images';

    public static function getProductimagesById($id)
    {
        return DB::table('product_images')->where('id', '=', $id)->first();
    }

    public static function getProductimagesByProductId($id) {
        return DB::Table('product_images')->where('product_id', '=', $id)->get();
    }

    public static function createProductimages($product_id, $images)
    {
        foreach ($images as $image) {
            $product_image = new Product_images();
            $product_image->product_id = $product_id;
            $product_image->images = $image;
            if (!$product_image->save()) {
                return false;
            }
            $product_image->save();
        }
        return true;
    }

    public static function updateImages($product_id, $uploaded_images)
    {
        Product_images::where('product_id', $product_id)->delete();
        foreach ($uploaded_images as $new_image) {
            $product_image = new Product_images();
            $product_image->product_id = $product_id;
            $product_image->images = $new_image;
            $product_image->save();
        }

    }
}
