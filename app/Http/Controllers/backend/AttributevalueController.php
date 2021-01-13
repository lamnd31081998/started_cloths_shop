<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Attribute_value;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AttributevalueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function color_index()
    {
        $attribute_values = Attribute_value::getAllColorValues();
        $categories = Category::getAllCategoriesSecondLevel();
        return view('backend.attribute_value.color.index')->with(['attribute_values' => $attribute_values, 'categories' => $categories]);
    }

    public static function color_search()
    {
        $attribute_values = Attribute_value::where('value', 'like', '%' . $_GET['keyword'] . '%')->where('attribute_id', '=', 1)->paginate(10);
        return response()->json([
            'status' => 200,
            'view' => view('backend.attribute_value.color.components.search_reload')->with(['attribute_values' => $attribute_values, 'keyword' => $_GET['keyword']])->render(),
        ]);
    }

    //Color store
    public function color_store(Request $request)
    {
        $rules = [
          'category_id' => 'required',
          'attribute_values' => 'required'
        ];

        $message = [
          'category_id.required' => 'Vui lòng chọn danh mục',
          'attribute_values.required' => 'Vui lòng nhập giá trị thuộc tính'
        ];

        $validates = validator($request->all(), $rules, $message);
        if ($validates->fails()) {
            return response()->json([
                'errors' => $validates->errors()
            ], 400);
        }

        $attribute_values = explode(',', $request->attribute_values);
        if (Attribute_value::insertColorAttributeValues($attribute_values, $request->all())) {
            $categories = Category::getAllCategoriesSecondLevel();
            $attribute_values = Attribute_value::getAllColorValues();
            return response()->json([
                'message' => 'Thêm giá trị thuộc tính thành công',
                'view' => view('backend.attribute_value.color.index')->with(['attribute_values'=>$attribute_values, 'categories' => $categories])->render()
            ], 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function color_destroy(Request $request)
    {
        if (Attribute_value::destroy($request->color_id)) {
            $attribute_values = Attribute_value::getAllColorValues();
            $categories = Category::getAllCategoriesSecondLevel();
            return response()->json([
               'message' => 'Xóa giá trị thành công',
               'view' => view('backend.attribute_value.color.index')->with(['attribute_values' => $attribute_values, 'categories' => $categories])->render()
            ], 200);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function size_index()
    {
        $attribute_values = Attribute_value::getAllSizeValues();
        $categories = Category::getAllCategoriesSecondLevel();
        return view('backend.attribute_value.size.index')->with(['attribute_values' => $attribute_values, 'categories' => $categories]);
    }

    public static function size_search()
    {
        {
            $attribute_values = Attribute_value::where('value', 'like', '%' . $_GET['keyword'] . '%')->where('attribute_id', '=', 2)->paginate(10);
            return response()->json([
                'status' => 200,
                'view' => view('backend.attribute_value.size.components.search_reload')->with(['attribute_values' => $attribute_values, 'keyword' => $_GET['keyword']])->render(),
            ]);
        }
    }

    //Size store
    public function size_store(Request $request)
    {
        $rules = [
            'category_id' => 'required',
            'attribute_values' => 'required'
        ];
        $message = [
            'category_id.required' => 'Vui lòng chọn danh mục',
            'attribute_values.required' => 'Vui lòng nhập giá trị thuộc tính'
        ];

        $validates = validator($request->all(), $rules, $message);
        if ($validates->fails()) {
            return response()->json([
                'errors' => $validates->errors()
            ], 400);
        }

        $attribute_values = explode(',', $request->attribute_values);
        if (Attribute_value::insertSizeAttributeValues($attribute_values, $request)) {
            $attribute_values = Attribute_value::getAllSizeValues();
            $categories = Category::getAllCategoriesSecondLevel();
            return response()->json([
               'message' => 'Thêm giá trị thuộc tính thành công',
               'view' => view('backend.attribute_value.size.index')->with(['attribute_values' => $attribute_values, 'categories' => $categories])->render()
            ]);
        }
    }

    public static function size_destroy(Request $request)
    {
        if (Attribute_value::destroy($request->size_id)) {
            $attribute_values = Attribute_value::getAllSizeValues();
            $categories = Category::getAllCategoriesSecondLevel();
            return response()->json([
                'status' => 200,
                'view' => view('backend.attribute_value.size.index')->with(['attribute_values' => $attribute_values, 'categories' => $categories])->render()
            ]);
        }
    }
}
