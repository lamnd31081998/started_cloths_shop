<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\General;
use App\Models\Order;
use App\Models\Order_detail;
use App\Models\Product;
use App\Models\Product_Attribute_value;
use App\Models\Product_images;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class ShopController extends Controller
{
    public function index($slug)
    {

        $category_dad = DB::table('categories')->where('parent_id', '=', 0)->where('slug', '=', $slug)->first();
        $category_children = [];
        $all_categories = Category::getAllCategoriesSecondLevel();
        foreach ($all_categories as $category) {
            if ($category->parent_id == $category_dad->id) {
                $category_children[] = $category->id;
            }
        }

        $products = DB::table('products')->whereIn('category_id', $category_children)->orderBy('created_at', 'desc')->get();
        $product_images = [];
        foreach ($products as $product) {
            $product_image = Product_images::getProductimagesByProductId($product->id);
            $product_images[] = $product_image[0]->images;
        }

        if (session()->has('carts')) {
            $carts = Session::get('carts');
        }
        else {
            $carts = [];
        }
        $categories = Category::getAllCategoriesFirstLevel();
        return view('frontend.shop.index')->with([
            'social_links' => $this->social_links,
            'contact' => $this->contact,
            'fast_links' => $this->fast_links,
            'carts' => $carts,
            'products' => $products,
            'product_images' => $product_images,
            'categories' => $categories,
            'category_dad' => $category_dad,
            'category_children' => $category_children
        ]);
    }

    public static function get_products(Request $request)
    {
        $category_id = $request->category_id;
        //TH là danh mục con
        if (Category::getCategoryById($category_id)->parent_id != 0) {
            $products = Product::getProductByCategoryId($category_id);
            $product_images = [];
            foreach ($products as $product) {
                $product_image = Product_images::getProductimagesByProductId($product->id);
                $product_images[] = $product_image[0]->images;
            }
            return response()->json([
                'view' => view('frontend.layout.category_product.only_products_grid')->with(['products' => $products, 'product_images' => $product_images])->render(),
                'category_slug' => Category::getCategoryById($category_id)->slug,
                'products_quantity' => count($products)
            ], 200);
        }

        //TH là danh mục cha
        $category_children = [];
        foreach (Category::getAllCategoriesSecondLevel() as $category_child) {
            if ($category_child->parent_id == $category_id) {
                $category_children[] = $category_child->id;
            }
        }
        $products = DB::table('products')->whereIn('category_id', $category_children)->orderBy('created_at', 'desc')->get();
        $product_images = [];
        foreach ($products as $product) {
            $product_image = Product_images::getProductimagesByProductId($product->id);
            $product_images[] = $product_image[0]->images;
        }
        return response()->json([
            'view' => view('frontend.layout.category_product.only_products_grid')->with(['products' => $products, 'product_images' => $product_images])->render(),
            'category_slug' => Category::getCategoryById($category_id)->slug,
            'products_quantity' => count($products)
        ], 200);
    }

    public function detail($category_dad_slug, $category_slug, $product_slug)
    {
        $category = DB::table('categories')->where('slug', '=', $category_slug)->first();
        $category_dad = DB::table('categories')->where('slug', '=', $category_dad_slug)->first();
        $product = DB::table('products')->where('category_id', '=', $category->id)->where('slug', '=', $product_slug)->first();
        $product_images = Product_images::getProductimagesByProductId($product->id);

        $product_attribute_values = Product_Attribute_value::getProductAttributevalueByProductId($product->id);
        $color_attribute_values = [];
        $size_attribute_values = [];
        foreach ($product_attribute_values as $product_attribute_value) {
            $color_attribute_values[] = $product_attribute_value->color_id;
            $size_attribute_values[] = $product_attribute_value->size_id;
        }
        $colors = DB::table('attribute_values')->whereIn('id', $color_attribute_values)->get();
        $sizes = DB::table('attribute_values')->whereIn('id', $size_attribute_values)->get();

        $categories = Category::getAllCategoriesFirstLevel();

        if (session()->has('carts')) {
            $carts = Session::get('carts');
        }
        else {
            $carts = [];
        }
        $ratings = Rating::getRatingsByProductId($product->id);

        return view('frontend.shop.detail')->with([
            'ratings' => $ratings,
            'social_links' => $this->social_links,
            'contact' => $this->contact,
            'fast_links' => $this->fast_links,
            'carts' => $carts,
            'categories' => $categories,
            'product' => $product,
            'product_images' => $product_images,
            'colors' => $colors,
            'sizes' => $sizes,
            'category_dad' => $category_dad,
            'category' => $category
        ]);
    }

    public static function get_sizes(Request $request)
    {
        $attribute_values = DB::table('product_attribute_values')->where('product_id', '=', $request->product_id)->where('color_id', '=', $request->color_id)->orderBy('id')->get();
        return response()->json([
            'view' => view('frontend.layout.category_product.sizes')->with('attribute_values', $attribute_values)->render()
        ], 200);
    }

    public static function store_rating(Request $request)
    {
        $rules = [
            'name' => 'required',
            'comment' => 'required',
            'star' => 'numeric|min:1'
        ];

        $message = [
            'name.required' => 'Vui lòng nhập họ và tên',
            'comment.required' => 'Vui lòng nhập nội dung đánh giá',
            'star.min' => 'Vui lòng chọn điểm đánh giá'
        ];
        $validates = validator($request->all(), $rules, $message);
        if ($validates->fails()) {
            return response()->json([
                'errors' => $validates->errors()
            ], 400);
        }

        if (count($orders = Order::where('phone_number', $request->phone_number)->get()) == 0) {
            return response()->json([
                'message' => 'Bạn không thể đánh giá sản phẩm bạn chưa từng mua'
            ], 400);
        }
        else if (Rating::where('product_id', $request->product_id)->where('phone_number', $request->phone_number)->first() != null) {
            return response()->json([
                'message' => 'Bạn đã đánh giá sản phẩm này rồi, bạn không thể đánh giá thêm lần nữa'
            ], 400);
        }
        else {
            $check = 0;
            foreach ($orders as $order) {
                $order_details = Order_detail::where('order_id', $order->id)->get();
                foreach ($order_details as $order_detail) {
                    if ($order_detail->product_id == $request->product_id) {
                        $orders_status[] = $order->status;
                        $check = 1;
                    }
                }
            }
            foreach ($orders_status as $order_status) {
                $check_status = 0;
                if ($order_status == 4) {
                    $check_status = 1;
                    break;
                }
            }
            if ($check_status == 0) {
                return response()->json([
                    'message' => 'Bạn không thể đánh giá sản phẩm bạn đã đặt mua nhưng chưa nhận được'
                ], 400);
            }
            if ($check != 1) {
                return response()->json([
                    'message' => 'Bạn không thể đánh giá sản phẩm bạn chưa từng mua'
                ], 400);
            }

            if (Rating::createRating($request->all())) {
                $rating = Rating::where('phone_number', $request->phone_number)->where('product_id', $request->product_id)->first();
                return response()->json([
                    'view_search_star' => view('frontend.layout.category_product.new_search_star')->with(['product_id' => $request->product_id])->render(),
                    'view_avg_rating' => view('frontend.layout.category_product.new_avg_rating')->with(['product_id' => $request->product_id])->render(),
                    'view' => view('frontend.layout.category_product.new_rating')->with(['data' => $request->all(), 'rating' => $rating])->render()
                ], 200);
            }
        }
    }
}
