<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class General extends Model
{
    use Notifiable;

    protected $table = 'generals';

    protected $fillable = [
      'images', 'link', 'name', 'address', 'phone_number', 'email', 'description', 'question'
    ];

    public static function getHomeImages()
    {
        return DB::table('generals')->where('type', '=', 'home-images')->orderBy('id')->get();
    }

    public static function UpdateHomeImages($data)
    {

    }
}
