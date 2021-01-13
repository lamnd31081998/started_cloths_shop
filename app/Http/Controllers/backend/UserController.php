<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Huyen;
use App\Models\Tinh;
use App\Models\Xa;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use File;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function admin_index()
    {
        $admins = User::getAllUsers()->where('id', '!=', Auth::id())->orderBy('id')->get();
        return view('backend.user_admin.admin.index')->with('admins', $admins);
    }

    //edit status
    public static function edit_status(Request $request)
    {
        if (DB::table('users')->where('id', '=', $request->id)->update(['status' => $request->status, 'updated_at' => Carbon::now()])) {
            $admins = User::getAllUsers()->where('id', '!=', Auth::id())->orderBy('id')->paginate(10);
            if ($request->status == 1) {
                return response()->json([
                    'message' => 'Đã mở khóa tài khoản'
                ], 200);
            }
            return response()->json([
                'message' => 'Đã khóa tài khoản'
            ], 200);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function admin_create()
    {
        $tinhs = DB::table('tinh')->orderBy('id')->get();
        return view('backend.user_admin.admin.create')->with('tinhs', $tinhs);
    }

    //get Huyen for address
    public static function get_huyen()
    {
        $huyens = DB::table('huyen')->where('tinh_id', '=', $_POST['tinh_id'])->orderBy('id')->get();
        return response()->json([
            'status' => 200,
            'view' => view('backend.user_admin.components.huyen')->with('huyens', $huyens)->render()
        ]);
    }

    //get Xa for address
    public static function get_xa()
    {
        $xas = DB::table('xa')->where('huyen_id', '=', $_POST['huyen_id'])->orderBy('id')->get();
        return response()->json([
           'status' => 200,
           'view' => view('backend.user_admin.components.xa')->with('xas', $xas)->render()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function admin_store(Request $request)
    {
        $rules = [
            'username' => 'unique:users,username',
            'email' => 'unique:users,email',
            'password' => 'confirmed',
        ];
        $message = [
            'username.unique' => 'Tên tài khoản đã tồn tại',
            'email.unique' => 'Email đã tồn tại',
            'password.confirmed' => 'Mật khẩu nhập lại không khớp'
        ];

        $validates = validator($request->all(), $rules, $message);
        if ($validates->fails()) {
            return redirect()->back()->withInput()->withErrors($validates);
        }

        if (User::insertAdmin($request->all())) {
            echo '<script>';
            echo 'alert("Thêm tài khoản admin thành công");';
            echo 'window.location.href="'.route('be.admin.index').'";';
            echo '</script>';
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function admin_edit($id)
    {
        $admin = User::getUserById($id);
        $tinhs = DB::table('tinh')->orderBy('id')->get();
        $huyens = DB::table('huyen')->orderBy('id')->get();
        $xas = DB::table('xa')->orderBy('id')->get();
        return view('backend.user_admin.admin.edit')->with(['admin' => $admin, 'tinhs' => $tinhs, 'huyens' => $huyens, 'xas' => $xas]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function admin_update(Request $request, $id)
    {
        $rules = [
          'email' => 'unique:users,email,'.$id
        ];
        $messages = [
            'email.unique' => 'Email đã tồn tại'
        ];
        $validates = validator($request->all(), $rules, $messages);
        if ($validates->fails()) {
            return redirect()->back()->withInput()->withErrors($validates);
        }

        if (User::updateAdmin($id, $request->all())) {
            echo '<script>';
            echo 'alert("Cập nhật tài khoản thành công ");';
            echo 'window.location.href="'.route('be.admin.index').'";';
            echo '</script>';
        }
        else {
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function admin_destroy(Request $request)
    {
        $username = User::getUserById($request->admin_id)->username;
        if (User::destroy($request->admin_id)) {
            File::deleteDirectory(public_path('images/admins/'.$username.'/'));
            $admins = User::getAllUsers()->where('permission', '=', 2)->where('id', '!=', Auth::id())->orderBy('id')->paginate(10);
            return response()->json([
                'message' => 'Xóa tài khoản thành công',
                'view' => view('backend.user_admin.admin.index')->with('admins', $admins)->render()
            ], 200);
        }
    }
}
