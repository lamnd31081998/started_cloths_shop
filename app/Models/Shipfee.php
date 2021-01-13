<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class Shipfee extends Model
{
    use Notifiable;

    protected $table = 'shipfees';

    protected $fillable = [
      'name', 'description', 'fee'
    ];

    public static function getAllShipfees()
    {
        return DB::table('shipfees')->orderBy('id')->get();
    }

    public static function updateShipfee($data)
    {
        return DB::table('shipfees')->where('id', '=', $data['id']) ->update([
            'name' => $data['name'],
            'description' => $data['description'],
            'price' => $data['price'],
            'updated_at' => Carbon::now()
        ]);
    }

    public static function getShipfeeById($id)
    {
        return DB::table('shipfees')->where('id', '=', $id)->first();
    }

    public static function getShipfeeByPrice($price)
    {
        return DB::table('shipfees')->where('price', '=', $price)->first();
    }
}
