<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class Xa extends Model
{
    use Notifiable;

    protected $table = 'xa';

    protected $fillable = [

    ];

    public static function getAllXa()
    {
        return DB::table('xa')->orderBy('id')->get();
    }

    public static function getXaById($id)
    {
        return DB::table('xa')->where('id', '=', $id)->first();
    }
}
