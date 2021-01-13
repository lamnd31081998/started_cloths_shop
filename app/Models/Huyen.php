<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class Huyen extends Model
{
    use Notifiable;

    protected $table = 'huyen';

    protected $fillable = [

    ];

    public static function getAllHuyen()
    {
        return DB::table('huyen')->orderBy('id')->get();
    }

    public static function getHuyenById($id)
    {
        return DB::table('huyen')->where('id', '=', $id)->first();
    }
}
