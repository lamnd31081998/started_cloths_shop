<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class Attribute_value extends Model
{
    use Notifiable;

    protected $table = 'attribute_values';

    protected $fillable = [
      'value'
    ];

    public static function getAllColorValues()
    {
        return DB::table('attribute_values')->where('attribute_id', '=', 1)->orderBy('id')->get();
    }

    public static function getAllSizeValues()
    {
        return DB::table('attribute_values')->where('attribute_id', '=', 2)->orderBy('id')->get();
    }

    public static function getAttributevalueById($id)
    {
        return DB::table('attribute_values')->where('id', '=', $id)->first();
    }

    public static function insertColorAttributeValues($attribute_values, $request)
    {
        foreach ($attribute_values as $value) {
            $attribute_value = new Attribute_value();
            $attribute_value->category_id = $request['category_id'];
            $attribute_value->attribute_id = 1;
            $attribute_value->value = $value;
            $attribute_value->save();
        }
        return true;
    }

    public static function insertSizeAttributeValues($attribute_values, $request)
    {
        foreach ($attribute_values as $value) {
            $attribute_value = new Attribute_value();
            $attribute_value->category_id = $request['category_id'];
            $attribute_value->attribute_id = 2;
            $attribute_value->value = $value;
            $attribute_value->save();
        }
        return true;
    }
}
