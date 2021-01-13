<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class Promotion extends Model
{
    use Notifiable;

    protected $table = 'promotions';

    protected $fillable = [
      'code', 'description', 'discount', 'max_use', 'max_user_use'
    ];

    public static function getAllPromotions()
    {
        return DB::table('promotions')->orderBy('id')->get();
    }

    public static function getPromotionByCode($code)
    {
        return DB::table('promotions')->where('code', '=', $code)->first();
    }

    public static function getPromotionById($id)
    {
        return DB::table('promotions')->where('id', '=', $id)->first();
    }

    public static function createPromotion($request)
    {
        $promotion = new Promotion();
        $promotion->code = $request['code'];
        $promotion->discount = $request['discount'];
        $promotion->description = $request['description'];
        $promotion->max_use = $request['max_use'];
        $promotion->max_user_use = $request['max_user_use'];
        if (isset($request['at_least'])) {
            $promotion->at_least = (int)str_replace(',', '', $request['at_least']);
        }
        if (isset($request['start_time'])) {
            $promotion->start_time = $request['start_time'];
        }
        if (isset($request['expire_time'])) {
            $promotion->expire_time = $request['expire_time'];
        }
        return $promotion->save();
    }

    public static function updatePromotion($id ,$request)
    {
        $promotion = Promotion::find($id);
        $promotion->description = $request['description'];
        $promotion->max_use = $request['max_use'];
        $promotion->max_user_use = $request['max_user_use'];
        if (isset($request['start_time'])) {
            $promotion->start_time = $request['start_time'];
        }
        else {
            $promotion->start_time = date('Ymd');
        }
        if (isset($request['expire_time'])) {
            $promotion->expire_time = $request['expire_time'];
        }
        else {
            $promotion->expire_time = null;
        }
        return $promotion->save();
    }
}
