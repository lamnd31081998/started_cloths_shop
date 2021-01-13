<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Shipfee;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use File;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    public function index()
    {
        $products = DB::table('products')->orderBy('created_at', 'desc')->limit(5)->get();
        $orders = DB::table('orders')->orderBy('created_at', 'desc')->limit(5)->get();
        $user_quantities = count(User::getAllUsers()->get());
        $totals = Order::whereIn('status', [1,2,3,4])->sum('totals');
        $quantities = Order::count();
        if (date("d") == 31) {
            $previous_month = date("m", strtotime('-1 day -1 month'));
        }
        else {
            $previous_month = date("m", strtotime('-1 month'));
        }
        $quantity_previous_month = Order::getOrderCountByMonthYear($previous_month, date("Y"));
        $quantity_this_month = Order::getOrderCountByMonthYear(date("m"), date("Y"));
        $total_previous_month = Order::getOrderTotalByMonthYear($previous_month, date("Y"));
        $total_this_month = Order::getOrderTotalByMonthYear(date("m"), date("Y"));

        //Tính % số lượng đơn hàng
        if ($quantity_previous_month == 0 && $quantity_this_month == 0) {
            $quantity_percents = 0;
        }
        else if ($quantity_previous_month == 0) {
            $quantity_percents = 100;
        }
        else {
            $quantity_percents = ($quantity_this_month-$quantity_previous_month)*100/$quantity_previous_month;
            $quantity_percents = round($quantity_percents, 1);
        }

        //Tính % số lượng doanh thu
        if ($total_previous_month == 0 && $total_this_month == 0) {
            $percents = 0;
        }
        else if ($total_previous_month == 0) {
            $percents = 100;
        }
        else {
            $percents = ($total_this_month-$total_previous_month)*100/$total_previous_month;
            $percents = round($percents, 1);
        }

        return view('backend.index')->with(['products' => $products, 'orders' => $orders, 'user_quantities' => $user_quantities, 'totals' => $totals, 'percents' => $percents, 'quantities' => $quantities, 'quantity_percents' => $quantity_percents]);

    }

    public function logout()
    {
        Auth::logout();
        echo '<script>';
        echo 'alert("Đăng xuất thành công");';
        echo 'window.location.href="'.route('be.login.index').'";';
        echo '</script>';
    }

    public function self_update_password(Request $request)
    {
        $rules = [
            'old_password' => 'required',
            'password' => 'required|confirmed',
        ];

        $message = [
            'old_password.required' => 'Vui lòng nhập mật khẩu cũ',
            'password.required' => 'Vui lòng nhập mật khẩu mới',
            'password.confirmed' => 'Mật khẩu mới nhập lại không khớp, vui lòng thử lại'
        ];

        $validates = validator($request->all(), $rules, $message);
        if ($validates->fails()) {
            return response()->json([
                'errors' => $validates->errors()
            ], 400);
        }

        $admin = User::getUserById($request->admin_id);
        if (!Hash::check($request->old_password, $admin->password)) {
            return response()->json([
                'message' => 'Mật khẩu cũ không đúng, vui lòng kiểm tra lại'
            ], 400);
        }

        if ($request->old_password == $request->password) {
            return response()->json([
                'message' => 'Mật khẩu mới không được giống với mật khẩu cũ'
            ], 400);
        }

        DB::table('users')->where('id', '=', $request->admin_id)->update(['password' => bcrypt($request->password), 'updated_at' => Carbon::now()]);
        return response()->json([
            'message' => 'Cập nhật mật khẩu thành công',
        ], 200);
    }

    public function edit_self_information()
    {
        $admin = User::getUserById(Auth::id());
        $tinhs = DB::table('tinh')->orderBy('id')->get();
        $huyens = DB::table('huyen')->orderBy('id')->get();
        $xas = DB::table('xa')->orderBy('id')->get();
        return view('backend.edit_information')->with(['admin' => $admin, 'tinhs' => $tinhs, 'huyens' => $huyens, 'xas' => $xas]);
    }

    public function update_self_information(Request $request)
    {
        $rules = [
            'email' => 'unique:users,email,'.Auth::id(),
        ];
        $messages = [
            'email.unique' => 'Email đã tồn tại',
        ];
        $validates = validator($request->all(), $rules, $messages);
        if ($validates->fails()) {
            return redirect()->back()->withInput()->withErrors($validates);
        }

        if (User::updateAdmin(Auth::id(), $request->all())) {
            echo '<script>';
            echo 'alert("Sửa thông tin cá nhân thành công");';
            echo 'window.location.href="'.route('be.index.edit_self_information').'";';
            echo '</script>';
        }
    }

    public function get_sales_data()
    {
        $i = 1;
        $sales_data = [];
        while ($i <= 12) {
            $sales_data[] = Order::getOrderTotalByMonthYear($i, date("Y"));
            $total_sales_data[] = Order::getOrderCountSuccessByMonthYear($i, date("Y"));
            $total_fails_data[] = (int)Order::getOrderCountByMonthYear($i,date("Y")) - (int)Order::getOrderCountSuccessByMonthYear($i, date("Y"));
            $i = $i + 1;
        };
        return response()->json([
            'sales_data' => $sales_data,
            'total_sales_data' => $total_sales_data,
            'total_fails_data' => $total_fails_data,
        ], 200);
    }
}
