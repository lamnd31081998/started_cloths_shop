<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;

class LoginController extends Controller
{
    //function show login page
    public function index()
    {
        if (Auth::check()) {
            return redirect()->route('be.index');
        }
        return view('backend.auth.login');
    }

    //function check account
    public function auth(Request $request)
    {
        if ($request->remember != null) {
            if (Auth::attempt(['username' => $request->username, 'password' => $request->password], true)) {
                if (Auth::user()->status == 0) {
                    $errors = new MessageBag(['locked_account' => 'Tài khoản của bạn đã bị khóa, vui lòng liên hệ bộ phận quản lý để được xử lý']);
                    Auth::logout();
                    return redirect()->back()->withInput()->withErrors($errors);
                }
                return redirect()->route('be.index');
            }
            else {
                $errors = new MessageBag(['username_password_wrong' => 'Tên tài khoản hoặc mật khẩu bị sai, vui lòng thử lại']);
                return redirect()->back()->withInput()->withErrors($errors);
            }
        }
        else {
            if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
                if (Auth::user()->status == 0) {
                    $errors = new MessageBag(['locked_account' => 'Tài khoản của bạn đã bị khóa, vui lòng liên hệ bộ phận quản lý để được xử lý']);
                    Auth::logout();
                    return redirect()->back()->withInput()->withErrors($errors);
                }
                return redirect()->route('be.index');
            }
            else {
                $errors = new MessageBag(['username_password_wrong' => 'Tên tài khoản hoặc mật khẩu bị sai, vui lòng thử lại']);
                return redirect()->back()->withInput()->withErrors($errors);
            }
        }
    }
}
