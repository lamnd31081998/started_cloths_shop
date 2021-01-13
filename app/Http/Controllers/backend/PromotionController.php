<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class PromotionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $promotions = Promotion::getAllPromotions();
        return view('backend.promotion.index')->with(['promotions' => $promotions]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('backend.promotion.create');
    }

    public static function check_promotion(Request $request)
    {
        if (isset($request->id)) {
            if (DB::table('promotions')->where('id', '!=', $request->id)->where('code', '=', $request->code)->first() !== null) {
                return response()->json([
                    'message' => 'Mã giảm giá đã tồn tại'
                ], 400);
            }
        }

        else if (Promotion::getPromotionByCode($request->code)) {
            return response()->json([
                'message' => 'Mã giảm giá đã tồn tại'
            ], 400);
        }

        return response()->json([

        ],200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $rules = [
            'code' => 'unique:promotions,code',
        ];

        $message = [
            'code.unique' => 'Mã giảm giá đã tồn tại',
        ];

        $validates = validator($request->all(), $rules, $message);
        if ($validates->fails()) {
            return redirect()->back()->withInput()->withErrors($validates);
        }

        if (Promotion::createPromotion($request->all())) {
            echo '<script>';
            echo 'alert("Thêm mã giảm giá thành công");';
            echo 'window.location.href="'.route('be.promotion.index').'";';
            echo '</script>';
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
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
    public function edit($id)
    {
        $promotion = Promotion::getPromotionById($id);
        return view('backend.promotion.edit')->with(['promotion' => $promotion]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        if (Promotion::updatePromotion($id, $request)) {
            echo '<script>';
            echo 'alert("Cập nhật mã giảm giá thành công");';
            echo 'window.location.href="'.route('be.promotion.index').'";';
            echo '</script>';
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
