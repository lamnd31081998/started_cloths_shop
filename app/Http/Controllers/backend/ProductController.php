<?php

namespace App\Http\Controllers\backend;

use App\Models\Order;
use App\Models\Order_detail;
use Session;
use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\Attribute_value;
use App\Models\Category;
use App\Models\Product;
use App\Models\Product_Attribute_value;
use App\Models\Product_images;
use Carbon\Carbon;
use http\Env\Response;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use File;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $products = Product::getAllProduct();
        return view('backend.product.index')->with(['products' => $products]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $categories = Category::getAllCategoriesSecondLevel();
        $attributes = Attribute::getAllAttributes();
        $colors = Attribute_value::getAllColorValues();
        $sizes = Attribute_value::getAllSizeValues();
        return view('backend.product.create')->with(['categories' => $categories, 'attributes' => $attributes, 'colors' => $colors, 'sizes' => $sizes]);
    }

    public static function get_slug()
    {
        $slug = Str::slug($_GET['name'], '-');
        return response()->json([
            'slug' => $slug
        ], 200);
    }

    public static function get_attribute_values(Request $request)
    {
        $colors = DB::table('attribute_values')->where('attribute_id', '=', 1)->where('category_id', '=', $request->category_id)->get();
        $sizes = DB::table('attribute_values')->where('attribute_id', '=', 2)->where('category_id', '=', $request->category_id)->get();
        if (count($colors) == 0 || count($sizes) == 0) {
            return response()->json([
                'message' => 'Vui lòng thêm giá trị thuộc tính trước'
            ], 400);
        }

        return response()->json([
            'colors_view' => view('backend.product.components.attribute_value_options')->with('attribute_values', $colors)->render(),
            'sizes_view' => view('backend.product.components.attribute_value_options')->with('attribute_values', $sizes)->render()
        ], 200);
    }

    public static function product_image_table(Request $request)
    {
        $uploaded_images = [];
        if (isset($request->uploaded_images)) {
            foreach ($request->uploaded_images as $image) {
                $uploaded_images[] = $image;
            }
        }

        return \response()->json([
            'images_uploaded' => $uploaded_images,
            'new_total_images' => count($uploaded_images)+1,
            'view' => view('backend.product.components.product_images_table')->with(['total_images' => $request->total_images, 'uploaded_images' => $uploaded_images])->render()
        ], 200);
    }

    public static function preview_product(Request $request)
    {
        if (isset($request->product_id) && !isset($request->variants)) {
            $variants = [];
            $product_attribute_values = Product_Attribute_value::getProductAttributevalueByProductId($request->product_id);
            foreach ($product_attribute_values as $index=>$product_attribute_value) {
                $variants[$index] = ['color_id' => $product_attribute_value->color_id, 'size_id' => $product_attribute_value->size_id];
            }
        }
        else if (!isset($request->product_id)) {
            $variants = $request->variants;
        }

        return response()->json([
            'view' => view('backend.product.components.product_preview')->with([
                'name' => $request->name,
                'price' => $request->price,
                'sale_price' => $request->sale_price,
                'total_images' => $request->total_images,
                'images' => $request->images,
                'variants' => $variants
            ])->render(),
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        if (DB::table('products')->where('name', '=', $request->name)->where('category_id', '=', $request->category_id)->first()) {
            Session::flash('error', 'Sản phẩm này đã tồn tại trong danh mục '.Category::getCategoryById($request->category_id)->name.', bạn có thể thêm vào danh mục khác');
            return redirect()->back()->withInput();
        }

        $i = 1;
        while ($request->has('attribute_value_'.$i.'_1')) {
            $color_name = 'attribute_value_'.$i.'_1';
            $size_name = 'attribute_value_'.$i.'_2';
            $quantity_name = 'quantity_'.$i;
            $variants[] = ['color_id' => $request->$color_name, 'size_id' => $request->$size_name, 'quantity' => $request->$quantity_name];
            $i = $i + 1;
        }

        DB::beginTransaction();
        try {
            $product_id = Product::createProduct($request->all());
            Product_attribute_value::createProductAtributevalues($product_id, $variants);
            Product_images::createProductimages($product_id, $request->uploaded_images);
            DB::commit();
            echo '<script>';
            echo 'alert("Thêm sản phẩm thành công");';
            echo 'window.location.href="'.route('be.product.index').'";';
            echo '</script>';
        }
        catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e);
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
    public function edit($id)
    {
        $categories = Category::getAllCategoriesSecondLevel();
        $colors = Attribute_value::getAllColorValues();
        $sizes = Attribute_value::getAllSizeValues();
        $product = Product::getProductById($id);
        $product_attribute_values = Product_attribute_value::getProductAttributevalueByProductId($id);
        $product_images = Product_images::getProductimagesByProductId($id);
        return view('backend.product.edit')->with(['categories' => $categories, 'colors' => $colors, 'sizes' => $sizes, 'product' => $product, 'product_attribute_values' => $product_attribute_values, 'product_images' => $product_images]);
    }

    public static function create_variants(Request $request)
    {
        $colors = $request->colors;
        $sizes = $request->sizes;
        $variants = [];
        $i = 1;
        foreach ($colors as $color) {
            foreach ($sizes as $size) {
                array_push($variants,['color_id' => $color, 'size_id' => $size]);
                $i = $i + 1;
            }
        }

        return response()->json([
            'message' => 'Đã tạo '.($i-1).' biến thể',
            'total' => $i-1,
            'view' => view('backend.product.components.create_variants')->with('variants', $variants)->render()
        ], 200);
    }

    public static function return_origin_variants(Request $request)
    {
        $product_attribute_values = Product_attribute_value::getProductAttributevalueByProductId($request->product_id);
        return response()->json([
            'message' => 'Đã quay trở về bản gốc',
            'view' => view('backend.product.components.origin_variants')->with('product_attribute_values', $product_attribute_values)->render()
        ], 200);
    }

    public static function return_origin_images(Request $request)
    {
        $product_images = Product_images::getProductimagesByProductId($request->product_id);
        return response()->json([
            'message' => 'Đã quay trở về bản gốc',
            'view' => view('backend.product.components.origin_images')->with('product_images', $product_images)->render()
        ], 200);
    }

    public static function return_origin_size_image(Request $request)
    {
        $product = Product::getProductById($request->product_id);
        return response()->json([
            'message' => 'Đã quay trở về bản gốc',
            'size_image' => asset($product->size_image),
            'view' => view('backend.product.components.origin_size_image')->with('size_image', $product->size_image)->render()
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $variants = [];
        $quantities = [];

        if ($request->has('attribute_value_1_1')) {
            $i = 1;
            while ($request->has('attribute_value_'.$i.'_1')) {
                $color_name = 'attribute_value_'.$i.'_1';
                $color = $request->$color_name;
                $size_name = 'attribute_value_'.$i.'_2';
                $size = $request->$size_name;
                $quantity_name = 'quantity_'.$i;
                $quantity = $request->$quantity_name;
                $variants[$i] = ['color_id' => $color, 'size_id' => $size, 'quantity' => $quantity];
                $i = $i + 1;
            }
        }
        else {
            $i = 0;
            while($request->has('quantity_'.($i+1))) {
                $quantity_name = 'quantity_'.($i+1);
                $quantities[$i] = $request->$quantity_name;
                $i = $i + 1;
            }
        }

        DB::beginTransaction();
        try {
            Product::updateProduct($id, $request->all());
            Product_images::updateImages($id, $request->uploaded_images);
            if (count($variants) == 0) {
                Product_Attribute_value::updateQuantities($id, $quantities);
            }
            else {
                Product_Attribute_value::updateProductattributevalues($id, $variants);
            }
            DB::commit();
            echo '<script>';
            echo 'alert("Cập nhật sản phẩm thành công");';
            echo 'window.location.href="'.route('be.product.index').'";';
            echo '</script>';
        }
        catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        $orders_id = [];
        $order_have_products = Order_detail::where('product_id', $request->product_id)->get();

        foreach ($order_have_products as $order_have_product) {
            $orders_id[] = $order_have_product->order_id;
        }

        if (Product::destroy($request->product_id)) {
            Product_images::where('product_id', $request->product_id)->delete();
            Order::whereIn('id', $orders_id)->delete();
            $products = Product::getAllProduct();
            return response()->json([
                'message' => 'Đã xóa sản phẩm thành công',
                'view' => view('backend.product.index')->with('products', $products)->render()
            ]);
        }
    }
}
