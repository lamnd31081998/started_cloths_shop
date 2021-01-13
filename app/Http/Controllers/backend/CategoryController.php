<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use File;

class CategoryController extends Controller
{
    //Index Category
    public function index()
    {
        $categories = Category::getAllCategories();
        return view('backend.category.index')->with('categories', $categories);
    }

    public static function store(Request $request)
    {
        $rules = [
            'parent_id' => 'required',
            'name' => 'required|unique:categories,name',
        ];

        $message = [
            'parent_id.required' => 'Vui lòng chọn danh mục cha',
            'name.required' => 'Vui lòng nhập tên danh mục',
            'name.unique' => 'Tên danh mục đã tồn tại',
        ];

        $validates = validator($request->all(), $rules, $message);
        if ($validates->fails()) {
            return response()->json([
                'errors' => $validates->errors()
            ], 400);
        }

        if ($request->parent_id == 0 && !isset($request->thumb)) {
            return response()->json([
                'message' => 'Danh mục cha trống yêu cầu có ảnh đại diện'
            ], 400);
        }

        if (Category::createCategory($request->all())) {
            $categories = Category::getAllCategories();
            return response()->json([
                'message' => 'Thêm danh mục thành công',
                'view' => view('backend.category.index')->with('categories', $categories)->render()
            ],200);
        }
    }

    //Get Slug
    public static function get_slug()
    {
        return response()->json([
           'slug' => Str::slug($_GET['category_name'], '-')
        ], 200);
    }

    //Update Category
    public static function update(Request $request)
    {
        $rules = [
            'parent_id' => 'required',
            'name' => 'unique:categories,name,'.$request->category_id,
        ];

        $message = [
            'parent_id.required' => 'Vui lòng chọn danh mục cha',
            'name.unique' => 'Tên danh mục đã tồn tại',
        ];

        $validates = validator($request->all(), $rules, $message);
        if ($validates->fails()) {
            return response()->json([
                'errors' => $validates->errors()
            ], 400);
        }

        if ($request->parent_id == 0 && !isset($request->thumb)) {
            return response()->json([
                'message' => 'Danh mục cha trống yêu cầu có ảnh đại diện'
            ], 400);
        }

        if (Category::updateCategory($request->all())) {
            $categories = Category::getAllCategories();
            return response()->json([
                'message' => 'Sửa danh mục thành công',
                'view' => view('backend.category.index')->with('categories', $categories)->render()
            ], 200);
        }
    }

    //Delete Category
    public function destroy(Request $request)
    {
        if (Category::destroy($request->category_id)) {
            Category::where('parent_id', $request->category_id)->delete();
            $categories = Category::getAllCategories();
            return response()->json([
                'message' => 'Xóa danh mục thành công',
                'view' => view('backend.category.index')->with(['categories' => $categories])->render()
            ], 200);
        }
    }
}
