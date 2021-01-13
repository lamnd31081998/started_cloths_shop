<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class Tinh extends Model
{
    use Notifiable;

    protected $table = 'tinh';

    protected $fillable = [

    ];

    public static function getAllTinh()
    {
        return DB::table('tinh')->orderBy('id')->get();
    }

    public static function getTinhById($id)
    {
        return DB::table('tinh')->where('id', '=', $id)->first();
    }
}
