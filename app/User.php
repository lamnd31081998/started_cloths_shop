<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use Notifiable;
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username' , 'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function getAllUsers()
    {
        return DB::table('users');
    }

    public static function getUserById($id) {
        return DB::table('users')->where('id', '=', $id)->first();
    }

    public static function insertAdmin($data) {
        $admin = new  User();
        $admin->username = $data['username'];
        $admin->email = $data['email'];
        $admin->password = bcrypt($data['password']);
        $admin->name = $data['name'];
        $admin->gender = $data['gender'];
        $admin->birthday = $data['birthday'];
        $admin->phone_number = $data['phone_number'];
        $admin->tinh_id = $data['tinh_id'];
        $admin->huyen_id = $data['huyen_id'];
        $admin->xa_id = $data['xa_id'];
        $admin->address = $data['address'];
        return $admin->save();
    }

    public static function updateAdmin($id, $request)
    {
        return DB::table('users')->where('id', '=', $id)->update(['email' => $request['email'], 'name' => $request['name'], 'gender' => $request['gender'], 'birthday' => $request['birthday'], 'phone_number' => $request['phone_number'], 'tinh_id' => $request['tinh_id'], 'huyen_id' => $request['huyen_id'], 'xa_id' => $request['xa_id'], 'address' => $request['address'], 'updated_at' => Carbon::now()]);
    }
}
