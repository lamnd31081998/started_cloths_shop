<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class Attribute extends Model
{
    use Notifiable;

    protected $table = 'attributes';

    protected $fillable = [
      'name'
    ];

    public static function getAllAttributes()
    {
        return DB::table('attributes')->orderBy('id')->paginate(5);
    }
}
