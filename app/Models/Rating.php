<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class Rating extends Model
{
    use Notifiable;

    protected $table = 'ratings';

    protected $fillable = [
        'name', 'phone_number', 'comment'
    ];

    public static function getRatingsByStar($product_id, $rating)
    {
        return DB::table('ratings')->where('product_id', '=', $product_id)->where('rating', '=', $rating)->orderBy('created_at', 'desc')->get();
    }

    public static function getRatingsByProductId($id)
    {
        return DB::table('ratings')->where('product_id', '=', $id)->orderBy('created_at', 'desc')->get();
    }

    public static function getAvgRatingsByProductId($id)
    {
        return (DB::table('ratings')->where('product_id', '=', $id)->sum('rating')/count(Rating::getRatingsByProductId($id)));
    }

    public static function createRating($data)
    {
        $rating = new Rating();
        $rating->product_id = $data['product_id'];
        $rating->name = $data['name'];
        $rating->phone_number = $data['phone_number'];
        $rating->comment = $data['comment'];
        $rating->rating = $data['star'];
        return $rating->save();
    }
}
