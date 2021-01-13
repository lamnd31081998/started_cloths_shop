<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Product_images;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Session;
class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $products = Product::getLastestProduct();
        $product_images = [];
        foreach ($products as $product) {
            $product_image = Product_images::getProductimagesByProductId($product->id);
            $product_images[] = $product_image[0]->images;
        }
        $categories = Category::getAllCategoriesFirstLevel();
        if (session()->has('carts')) {
            $carts = Session::get('carts');
        }
        else {
            $carts = [];
        }

        $home_images = DB::table('generals')->where('type', '=', 'home-images')->orderBy('id')->get();
        return view('frontend.index')->with([
            'social_links' => $this->social_links,
            'contact' => $this->contact,
            'fast_links' => $this->fast_links,
            'home_images' => $home_images,
            'carts' => $carts,
            'products' => $products,
            'product_images' => $product_images,
            'categories' => $categories,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_products(Request $request)
    {
        if ($request->title == 'new') {
            $products = Product::getLastestProduct();
            $product_images = [];
            foreach ($products as $product) {
                $product_image = Product_images::getProductimagesByProductId($product->id);
                $product_images[] = $product_image[0]->images;
            }
            return response()->json([
                'view' => view('frontend.layout.index.products')->with(['products' => $products, 'product_images' => $product_images])->render()
            ]);
        }

        $products = Product::getLastestProductSale();
        $product_images = [];
        foreach ($products as $product) {
            $product_image = Product_images::getProductimagesByProductId($product->id);
            $product_images[] = $product_image[0]->images;
        }
        return response()->json([
            'view' => view('frontend.layout.index.products')->with(['products' => $products, 'product_images' => $product_images])->render()
        ]);
    }

    public function tracking_orders()
    {
        $categories = Category::getAllCategoriesFirstLevel();
        if (session()->has('carts')) {
            $carts = Session::get('carts');
        }
        else {
            $carts = [];
        }
        $thumb = DB::table('generals')->where('type', '=', 'trackingorder-thumb')->first();
        return view('frontend.tracking_order.index')->with([
            'social_links' => $this->social_links,
            'contact' => $this->contact,
            'fast_links' => $this->fast_links,
            'categories' => $categories,
            'carts' => $carts,
            'thumb' => $thumb
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search_orders(Request $request)
    {
        $orders = DB::table('orders')->where('phone_number', '=', $request->phone_number)->orderBy('created_at', 'desc')->get();
        if (count($orders) == 0) {
            $orders = [];
        }
        return response()->json([
            'view' => view('frontend.layout.tracking_order.search_body_table')->with(['orders' => $orders])->render(),
            'view_modal' => view('frontend.layout.tracking_order.search_modal')->with(['orders' => $orders])->render()
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function about_us()
    {
        $categories = Category::getAllCategoriesFirstLevel();
        if (session()->has('carts')) {
            $carts = Session::get('carts');
        }
        else {
            $carts = [];
        }

        $thumb = DB::table('generals')->where('type', '=', 'about-us')->first();
        return view('frontend.about_us.index')->with([
            'social_links' => $this->social_links,
            'contact' => $this->contact,
            'fast_links' => $this->fast_links,
            'categories' => $categories,
            'carts' => $carts,
            'thumb' => $thumb
        ]);
    }

    public function contact_us()
    {
        $categories = Category::getAllCategoriesFirstLevel();
        if (session()->has('carts')) {
            $carts = Session::get('carts');
        }
        else {
            $carts = [];
        }

        $thumb = DB::table('generals')->where('type', '=', 'contact-us')->first();

        return view('frontend.contact_us.index')->with([
            'social_links' => $this->social_links,
            'contact' => $this->contact,
            'fast_links' => $this->fast_links,
            'categories' => $categories,
            'carts' => $carts,
            'thumb' => $thumb
        ]);
    }

    public function terms_and_security()
    {
        $categories = Category::getAllCategoriesFirstLevel();
        if (session()->has('carts')) {
            $carts = Session::get('carts');
        }
        else {
            $carts = [];
        }

        $thumb = DB::table('generals')->where('type', '=', 'terms-and-security')->first();

        return view('frontend.terms_and_security.index')->with([
            'social_links' => $this->social_links,
            'contact' => $this->contact,
            'fast_links' => $this->fast_links,
            'categories' => $categories,
            'carts' => $carts,
            'thumb' => $thumb
        ]);
    }

    public function shipping_terms()
    {
        $categories = Category::getAllCategoriesFirstLevel();
        if (session()->has('carts')) {
            $carts = Session::get('carts');
        }
        else {
            $carts = [];
        }

        $thumb = DB::table('generals')->where('type', '=', 'shipping-terms')->first();

        return view('frontend.shipping_terms.index')->with([
            'social_links' => $this->social_links,
            'contact' => $this->contact,
            'fast_links' => $this->fast_links,
            'categories' => $categories,
            'carts' => $carts,
            'thumb' => $thumb
        ]);
    }

    public function return_terms()
    {
        $categories = Category::getAllCategoriesFirstLevel();
        if (session()->has('carts')) {
            $carts = Session::get('carts');
        }
        else {
            $carts = [];
        }

        $thumb = DB::table('generals')->where('type', '=', 'return-terms')->first();

        return view('frontend.return_terms.index')->with([
            'social_links' => $this->social_links,
            'contact' => $this->contact,
            'fast_links' => $this->fast_links,
            'categories' => $categories,
            'carts' => $carts,
            'thumb' => $thumb
        ]);
    }

    public function checkout_terms()
    {
        $categories = Category::getAllCategoriesFirstLevel();
        if (session()->has('carts')) {
            $carts = Session::get('carts');
        }
        else {
            $carts = [];
        }

        $thumb = DB::table('generals')->where('type', '=', 'checkout-terms')->first();

        return view('frontend.checkout_terms.index')->with([
            'social_links' => $this->social_links,
            'contact' => $this->contact,
            'fast_links' => $this->fast_links,
            'categories' => $categories,
            'carts' => $carts,
            'thumb' => $thumb
        ]);
    }

}
