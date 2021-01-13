<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Mail\MailNotify;
use App\Mail\MailVerify;
use App\Models\Category;
use App\Models\Order;
use App\Models\Order_detail;
use App\Models\Product;
use App\Models\Product_Attribute_value;
use App\Models\Product_images;
use App\Models\Promotion;
use App\Models\Shipfee;
use App\Models\Tinh;
use Faker\Provider\File;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Session;
use VNPay;

class CartController extends Controller
{
    public function index()
    {
        if (session()->has('carts')) {
            $carts = Session::get('carts');
        }
        else {
            $carts = [];
        }
        $thumb = DB::table('generals')->where('type', '=', 'cart-thumb')->first();
        $categories = Category::getAllCategoriesFirstLevel();
        $ship_fees = Shipfee::getAllShipfees();
        return view('frontend.cart.index')->with([
            'social_links' => $this->social_links,
            'contact' => $this->contact,
            'fast_links' => $this->fast_links,
            'categories' => $categories,
            'ship_fees' => $ship_fees,
            'carts' => $carts,
            'thumb' => $thumb
        ]);
    }

    public function add_product(Request $request)
    {
        $product_attribute_value = DB::table('product_attribute_values')->where('product_id', '=', $request->product_id)->where('color_id', '=', $request->color_id)->where('size_id', '=', $request->size_id)->first();
        $product = Product::getProductById($request->product_id);
        $product_images = Product_images::getProductimagesByProductId($product->id);
        $product_image = $product_images[0]->images;
        //TH đã có giỏ hàng
        if (session()->has('carts')) {
            $carts = Session::get('carts');
            //TH sp đã tồn tại trong giỏ hàng
            if (isset($carts[$product->id.'_'.$request->color_id.'_'.$request->size_id])) {
                if ($product_attribute_value->quantity < $carts[$product->id.'_'.$request->color_id.'_'.$request->size_id]['quantity'] + $request->quantity) {
                    return response()->json([
                        'message' => 'Số lượng đặt vượt quá số lượng sản phẩm trong kho'
                    ], 400);
                }
                $carts[$product->id.'_'.$request->color_id.'_'.$request->size_id]['quantity'] = $carts[$product->id.'_'.$request->color_id.'_'.$request->size_id]['quantity'] + $request->quantity;
                if ($product->sale_price != "") {
                    $price = $product->sale_price;
                }
                else {
                    $price = $product->price;
                }

                $carts[$product->id.'_'.$request->color_id.'_'.$request->size_id]['price'] = $price;
                $old_total_price = $carts[$product->id.'_'.$request->color_id.'_'.$request->size_id]['total_price'];
                $carts[$product->id.'_'.$request->color_id.'_'.$request->size_id]['total_price'] = $carts[$product->id.'_'.$request->color_id.'_'.$request->size_id]['total_price'] + $price * $request->quantity;

                $carts['information']['totals'] = $carts['information']['totals'] - $old_total_price + $carts[$product->id.'_'.$request->color_id.'_'.$request->size_id]['total_price'];

            }
            //TH sp chưa tồn tại trong giỏ hàng
            else {
                $product_attribute_value = DB::table('product_attribute_values')->where('product_id', '=', $request->product_id)->where('color_id', '=', $request->color_id)->where('size_id', '=', $request->size_id)->first();
                if ($product_attribute_value->quantity < $request->quantity) {
                    return \response()->json([
                        'message' => 'Số lượng đặt vượt quá số lượng sản phẩm trong kho'
                    ], 400);
                }

                if ($product->sale_price != "") {
                    $price = $product->sale_price;
                }
                else {
                    $price = $product->price;
                }

                $carts[$product->id.'_'.$request->color_id.'_'.$request->size_id] = [
                    'link' => $request->link,
                    'id' => $product->id,
                    'image' => $product_image,
                    'name' => $product->name,
                    'product_attribute_value_id' => $product_attribute_value->id,
                    'color_id' => $request->color_id,
                    'size_id' => $request->size_id,
                    'price' => $price,
                    'quantity' => $request->quantity,
                    'total_price' => $price * $request->quantity,
                ];
                $carts['information']['totals'] = $carts['information']['totals'] + $price * $request->quantity;
                $carts['information']['total_quantity'] = $carts['information']['total_quantity'] + $request->quantity;

            }
        }

        //TH không có giỏ hàng
        else {
            $product_attribute_value = DB::table('product_attribute_values')->where('product_id', '=', $request->product_id)->where('color_id', '=', $request->color_id)->where('size_id', '=', $request->size_id)->first();
            if ($product_attribute_value->quantity < $request->quantity) {
                return \response()->json([
                    'message' => 'Số lượng đặt vượt quá số lượng sản phẩm trong kho'
                ], 400);
            }

            if ($product->sale_price != "") {
                $price = $product->sale_price;
            }
            else {
                $price = $product->price;
            }

            $carts[$product->id.'_'.$request->color_id.'_'.$request->size_id] = [
                'link' => $request->link,
                'id' => $product->id,
                'image' => $product_image,
                'name' => $product->name,
                'product_attribute_value_id' => $product_attribute_value->id,
                'color_id' => $request->color_id,
                'size_id' => $request->size_id,
                'price' => $price,
                'quantity' => $request->quantity,
                'total_price' => $price * $request->quantity
            ];
            $carts['information'] = [
                'ship_fee' => 0,
                'total_quantity' => $request->quantity,
                'totals' => $price * $request->quantity
            ];

        }

        Session::put('carts', $carts);
        return response()->json([
            'totals' => number_format($carts['information']['totals'], 0, '', '.').'vnđ',
            'count_carts' => count(Session::get('carts'))
        ], 200);
    }

    public function update_carts(Request $request)
    {
        $carts = Session::get('carts');
        if (count($carts) == 2) {
            foreach ($request->quantities as $index=>$quantity) {
                if ($quantity == 0) {
                    Session::forget('carts');
                    $carts = [];
                    $categories = Category::getAllCategoriesFirstLevel();
                    $ship_fees = Shipfee::getAllShipfees();
                    $thumb = DB::table('generals')->where('type', '=', 'cart-thumb')->first();
                    return response()->json([
                        'view' => view('frontend.cart.index')->with(['social_links' => $this->social_links, 'contact' => $this->contact, 'fast_links' => $this->fast_links, 'thumb' => $thumb, 'ship_fees' => $ship_fees, 'categories' => $categories, 'carts' => $carts])->render()
                    ], 200);
                }
            }
        }

        foreach ($request->quantities as $index=>$quantity) {
            if ($quantity == 0) {
                Session::forget('carts.'.$index);
            }
            else {
                $quantities[$index] = $quantity;
            }
        }

        $carts['information']['totals'] = 0;
        $carts['information']['total_quantity'] = 0;
        foreach ($quantities as $index=>$quantity) {
            $product_attribute_value = Product_Attribute_value::getProductAttributeValueById($carts[$index]['product_attribute_value_id']);
            if ($product_attribute_value->quantity < $quantity) {
                return \response()->json([
                    'message' => 'Số lượng đặt vượt quá số lượng có trong kho'
                ], 400);
            }
            $product = Product::getProductById($carts[$index]['id']);
            $carts[$index]['quantity'] = $quantity;
            if ($product->sale_price != "") {
                $price = $product->sale_price;
            }
            else {
                $price = $product->price;
            }

            $carts[$index]['price'] = $price;
            $carts[$index]['total_price'] = $price * $quantity;

            $carts['information']['totals'] = $carts['information']['totals'] + $carts[$index]['total_price'];
            $carts['information']['total_quantity'] = $carts['information']['total_quantity'] + $quantity;
        }

        $totals = $carts['information']['totals'];

        if (isset($carts['information']['ship_fee'])) {
            $totals = $totals + $carts['information']['ship_fee'];
        }

        if (isset($carts['information']['code'])) {
            $promotion = Promotion::getPromotionByCode($carts['information']['code']);
            if ($carts['information']['totals'] < $promotion->at_least) {
                return response()->json([
                    'errors' => 'Đơn hàng của bạn sau khi cập nhật không đủ yêu cầu tối thiểu của mã, vui lòng xóa mã giảm giá trước khi cập nhật lại đơn hàng'
                ], 400);
            }
            $carts['information']['saleoff'] = $carts['information']['totals']*$promotion->discount/100;
            $totals = $totals - $carts['information']['saleoff'];
        }

        Session::put('carts', $carts);
        $categories = Category::getAllCategoriesFirstLevel();
        $ship_fees = Shipfee::getAllShipfees();
        $thumb = DB::table('generals')->where('type', '=', 'cart-thumb')->first();
        return response()->json([
            'totals' => number_format($totals, 0, '', '.').'vnđ',
            'view' => view('frontend.cart.index')->with(['social_links' => $this->social_links, 'contact' => $this->contact, 'fast_links' => $this->fast_links, 'thumb' => $thumb, 'ship_fees' => $ship_fees, 'categories' => $categories, 'carts' => Session::get('carts')])->render()
        ], 200);

    }

    public function update_ship_fee(Request $request)
    {
        if (!session()->has('carts')) {
            return response()->json([
                'message' => 'Vui lòng thêm sản phẩm vào giỏ hàng trước'
            ], 400);
        }
        $carts = Session::get('carts');
        $carts['information']['ship_fee'] = (int)$request->ship_fee;
        $totals = $carts['information']['totals'] + $carts['information']['ship_fee'];
        if (isset($carts['information']['code'])) {
            $totals = $totals - $carts['information']['saleoff'];
        }
        Session::put('carts', $carts);
        return response()->json([
            'totals' => number_format($totals, 0, '','.').'vnđ',
            'view' => view('frontend.layout.cart.totals')->with(['carts' => Session::get('carts')])->render()
        ], 200);
    }

    public function check_promotion(Request $request)
    {
        $code = $request->code;
        if (!session()->has('carts')) {
            return response()->json([
                'message' => 'Vui lòng thêm sản phẩm vào giỏ hàng trước'
            ], 400);
        }

        if ($promotion = Promotion::getPromotionByCode($code)) {
            if (date('Ymd', strtotime($promotion->start_time)) > date('Ymd')) {
                return response()->json([
                    'message' => 'Mã giảm giá này chưa bắt đầu, bạn hãy kiên nhẫn chờ đợi nhé'
                ], 400);
            }

            if (isset($promotion->expire_time) && date('Ymd', strtotime($promotion->expire_time)) < date('Ymd')) {
                return response()->json([
                    'message' => 'Mã giảm giá đã hết hạn, vui lòng thử mã giảm giá khác'
                ], 400);
            }

            if ($promotion->max_use == 0) {
                return \response()->json([
                    'message' => 'Số lượng mã giảm giá đã hết, vui lòng thử mã giảm giá khác'
                ], 400);
            }

            $carts = Session::get('carts');
            if ($promotion->at_least != 0) {
                if ($carts['information']['totals'] < $promotion->at_least) {
                    return \response()->json([
                        'message' => 'Đơn hàng của bạn chưa đạt tới mức yêu cầu tối thiểu của mã giảm giá này'
                    ], 400);
                }
            }
            $carts['information']['code'] = $code;
            $carts['information']['saleoff'] = $carts['information']['totals'] * $promotion->discount / 100;
            Session::put('carts', $carts);
            if (isset($carts['information']['ship_fee'])) {
                $totals = $carts['information']['totals'] + $carts['information']['ship_fee'] - $carts['information']['saleoff'];
            }
            else {
                $totals = $carts['information']['totals'] - $carts['information']['saleoff'];
            }
            return response()->json([
                'totals' => number_format($totals,0,'','.').'vnđ',
                'view' => view('frontend.layout.cart.totals')->with(['carts' => Session::get('carts')])->render(),
            ], 200);

        }

        return response()->json([
            'message' => 'Mã giảm giá không tồn tại'
        ], 400);
    }

    public function checkout_promotion(Request $request)
    {
        $code = $request->code;
        if (!session()->has('carts')) {
            return response()->json([
                'message' => 'Vui lòng thêm sản phẩm vào giỏ hàng trước'
            ], 400);
        }

        if ($promotion = Promotion::getPromotionByCode($code)) {
            if (date('Ymd', strtotime($promotion->start_time)) > date('Ymd')) {
                return response()->json([
                    'message' => 'Mã giảm giá này chưa bắt đầu, bạn hãy kiên nhẫn chờ đợi nhé'
                ], 400);
            }

            if (isset($promotion->expire_time) && date('Ymd', strtotime($promotion->expire_time)) < date('Ymd')) {
                return response()->json([
                    'message' => 'Mã giảm giá đã hết hạn, vui lòng thử mã giảm giá khác'
                ], 400);
            }

            $max_use = $promotion->max_use;
            if ($max_use == 0) {
                return \response()->json([
                    'message' => 'Số lượng mã giảm giá đã hết, vui lòng chọn mã giảm giá khác'
                ], 400);
            }

            $carts = Session::get('carts');
            if ($promotion->at_least != 0) {
                if ($carts['information']['totals'] < $promotion->at_least) {
                    return \response()->json([
                        'message' => 'Đơn hàng của bạn chưa đạt tới mức yêu cầu tối thiểu của mã giảm giá này'
                    ], 400);
                }
            }
            $carts['information']['code'] = $code;
            $carts['information']['saleoff'] = $carts['information']['totals'] * $promotion->discount/100;
            Session::put('carts', $carts);
            return response()->json([
                'totals' => number_format($carts['information']['totals'] + $carts['information']['ship_fee'] - $carts['information']['saleoff'], 0, '', '.').'vnđ',
                'view' => view('frontend.layout.cart.totals_checkout')->with(['carts' => Session::get('carts')])->render(),
            ], 200);
        }

        return response()->json([
            'message' => 'Mã giảm giá không tồn tại'
        ], 400);
    }

    public function delete_promotion()
    {
        if (!session()->has('carts')) {
            return \response()->json([
                'message' => 'Vui lòng thêm sản phẩm vào giỏ hàng trước'
            ], 400);
        }

        session()->forget('carts.information.code');
        session()->forget('carts.information.saleoff');

        $carts = Session::get('carts');
        $totals = $carts['information']['totals'];
        if (isset($carts['information']['ship_fee'])) {
            $totals = $totals + $carts['information']['ship_fee'];
        }

        return response()->json([
            'totals' => number_format($totals, 0, '', '.').'vnđ',
            'message' => 'Xóa mã giảm giá thành công',
            'view' => view('frontend.layout.cart.totals')->with(['carts' => $carts])->render()
        ], 200);
    }

    public function delete_carts()
    {
        Session::forget('carts');
        $carts = [];
        $categories = Category::getAllCategoriesFirstLevel();
        $ship_fees = Shipfee::getAllShipfees();
        $thumb = DB::table('generals')->where('type', '=', 'cart-thumb')->first();
        return response()->json([
            'message' => 'Đã xóa giỏ hàng thành công',
            'view' => view('frontend.cart.index')->with(['thumb' => $thumb, 'social_links' => $this->social_links, 'contact' => $this->contact, 'fast_links' => $this->fast_links, 'ship_fees' => $ship_fees, 'categories' => $categories, 'carts' => $carts])->render()
        ], 200);
    }

    public function checkout()
    {
        if (session()->has('carts')) {
            $carts = Session::get('carts');
            $tinhs = Tinh::getAllTinh();
            $categories = Category::getAllCategoriesFirstLevel();
            $thumb = DB::table('generals')->where('type', '=', 'checkout-thumb')->first();
            return view('frontend.cart.checkout')->with([
                'social_links' => $this->social_links,
                'contact' => $this->contact,
                'fast_links' => $this->fast_links,
                'categories' => $categories,
                'carts' => $carts,
                'tinhs' => $tinhs,
                'thumb' => $thumb
            ]);
        }
        echo '<script>';
        echo 'alert("vui lòng thêm sản phẩm vào giỏ hàng trước");';
        echo 'window.location.href="'.route('fe.cart.index').'";';
        echo '</script>';
    }

    public function get_huyen(Request $request)
    {
        $huyens = DB::table('huyen')->where('tinh_id', '=', $request->tinh_id)->orderBy('id')->get();
        return \response()->json([
            'view' => view('frontend.layout.cart.huyen')->with(['huyens' => $huyens])->render()
        ], 200);
    }

    public function get_xa(Request $request)
    {
        $xas = DB::table('xa')->where('huyen_id', '=', $request->huyen_id)->orderBy('id')->get();
        return response()->json([
            'view' => view('frontend.layout.cart.xa')->with(['xas' => $xas])->render()
        ], 200);
    }

    public function delete_promotion_checkout()
    {
        session()->forget('carts.information.code');
        session()->forget('carts.information.saleoff');

        $carts = Session::get('carts');
        return response()->json([
            'totals' => number_format($carts['information']['totals'] + $carts['information']['ship_fee'], 0, '', '.').'vnđ',
            'message' => 'Xóa mã giảm giá thành công',
            'view' => view('frontend.layout.cart.totals_checkout')->with(['carts' => $carts])->render()
        ], 200);
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email',
            'phone_number' => 'required',
            'address' => 'required',
            'tinh_id' => 'required',
            'huyen_id' => 'required',
            'xa_id' => 'required',
            'payment_method' => 'required'
        ];

        $message = [
            'name.required' => 'Vui lòng nhập tên người đặt hàng',
            'email.required' => 'Vui lòng nhập email để nhận xác nhận đơn hàng',
            'email.email' => 'Email không đúng định dạng',
            'address.required' => 'Vui lòng nhập địa chỉ giao hàng',
            'tinh_id.required' => 'Vui lòng chọn thành phố',
            'huyen_id.required' => 'Vui lòng chọn quận',
            'xa_id.required' => 'Vui lòng chọn phường',
            'payment_method.required' => 'Vui lòng chọn phương thức thanh toán'
        ];

        $validates = validator($request->all(), $rules, $message);
        if ($validates->fails()) {
            return \response()->json([
                'errors' => $validates->errors()
            ], 400);
        }

        $carts = Session::get('carts');
        if (isset($carts['information']['code'])) {
            $promotion = Promotion::getPromotionByCode($carts['information']['code']);
            $max_user_use = $promotion->max_user_use;
            $user_used = DB::table('orders')->where('phone_number', '=', $request->phone_number)->where('promotion', '=', $promotion->code)->count();
            if ($max_user_use <= $user_used) {
                return \response()->json([
                    'message' => 'Bạn đã sử dụng mã giảm giá này vượt quá số lượng cho phép, vui lòng thử mã giảm giá khác'
                ], 401);
            }
            $carts['information']['totals'] = $carts['information']['totals'] + $carts['information']['ship_fee'] - $carts['information']['saleoff'];
        }
        else {
            $carts['information']['totals'] = $carts['information']['totals'] + $carts['information']['ship_fee'];
        }

        DB::beginTransaction();
        try {
            $order_id = Order::createOrder($request->all(), $carts);
            Order_detail::createOrderDetails($carts, $order_id);
            foreach ($carts as $index=>$cart) {
                if ($index == 'information') {
                    if (isset($cart['code'])) {
                        $discount_info['code'] = $cart['code'];
                        $discount_info['sale'] = $cart['saleoff'];
                    }
                    else {
                        $discount_info = [];
                    }
                    $request->ship_id = Shipfee::getShipfeeByPrice($cart['ship_fee']);
                }
                else {
                    $products_id[] = $cart['id'];
                    $product_attribute_values_id[] = $cart['product_attribute_value_id'];
                    $quantities[] = $cart['quantity'];
                    $product = Product::getProductById($cart['id']);
                    if ($product->sale_price != "") {
                        $price = $product->sale_price;
                    }
                    else {
                        $price = $product->price;
                    }
                    $totals_price[] = $cart['quantity'] * $price;
                }
            }

            $shop_mail = DB::table('generals')->where('type', '=', 'contact')->first()->email;
            Mail::to($request->email)->send(new MailNotify($request->all(), $order_id, $products_id, $product_attribute_values_id, $quantities, $totals_price, $carts['information']['totals'], $discount_info, 'store'));
            Mail::to($shop_mail)->send(new MailVerify($request->all(), $order_id, $products_id, $product_attribute_values_id, $quantities, $totals_price, $carts['information']['totals'], $discount_info));
            DB::commit();
            Session::forget('carts');
            return \response()->json([
              'message' => 'Đặt hàng thành công, vui lòng theo dõi đơn hàng với SĐT đã đặt hàng'
            ], 200);
        }
        catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e);
        }
    }

    public function vnpay(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email',
            'phone_number' => 'required',
            'address' => 'required',
            'tinh_id' => 'required',
            'huyen_id' => 'required',
            'xa_id' => 'required',
            'payment_method' => 'required'
        ];

        $message = [
            'name.required' => 'Vui lòng nhập tên người đặt hàng',
            'email.email' => 'Email không đúng định dạng',
            'email.required' => 'Vui lòng nhập email để nhận xác nhận đơn hàng',
            'address.required' => 'Vui lòng nhập địa chỉ giao hàng',
            'tinh_id.required' => 'Vui lòng chọn thành phố',
            'huyen_id.required' => 'Vui lòng chọn quận',
            'xa_id.required' => 'Vui lòng chọn phường',
            'payment_method.required' => 'Vui lòng chọn phương thức thanh toán'
        ];

        $validates = validator($request->all(), $rules, $message);
        if ($validates->fails()) {
            return \response()->json([
                'errors' => $validates->errors()
            ], 400);
        }

        $carts = Session::get('carts');
        if (isset($carts['information']['code'])) {
            $max_user_use_code = Promotion::getPromotionByCode($carts['information']['code'])->max_user_use;
            $user_use_quantity = DB::table('orders')->where('phone_number', '=', $request->phone_number)->where('promotion', '=', $carts['information']['code'])->count();
            if ($max_user_use_code < ($user_use_quantity+1)) {
                return \response()->json([
                    'message' => 'Bạn đã sử dụng vượt quá số lần cho phép của mã giảm giá này'
                ], 401);
            }
        }
        Session::put('data', $request->all());

        //count totals
        if (empty($carts['information']['code'])) {
            $vnp_Amount = ($carts['information']['totals'] + Shipfee::getShipfeeById($request->ship_id)->price) * 100;
        } else {
            $vnp_Amount = ($carts['information']['totals'] + Shipfee::getShipfeeById($request->ship_id)->price - $carts['information']['saleoff']) * 100;
        }

        //vnpay
        session(['prev_url' => url()->previous()]);
        $vnp_TmnCode = 'B18K5L6Q';
        $vnp_HashSecret = 'VQ4EQ402YTD51XVX0X1ZMGX3TXQ8WS2S';
        $vnp_IpAddr = request()->ip();
        $vnp_Locale = 'vn';
        $vnp_OrderInfo = 'Thanh toán đơn hàng '.(DB::table('orders')->max('id')+1);
        $vnp_OrderType = 200000;
        $vnp_ReturnUrl = route('fe.cart.vnpay_return');
        $vnp_Url = 'http://sandbox.vnpayment.vn/paymentv2/vpcpay.html';
        $vnp_TxnRef = uniqid('aStar_');

        $inputData = array(
            "vnp_Version" => "2.0.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_ReturnUrl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }
        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . $key . "=" . $value;
            } else {
                $hashdata .= $key . "=" . $value;
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash = hash('sha256', $vnp_HashSecret . $hashdata);
            $vnp_Url .= 'vnp_SecureHashType=SHA256&vnp_SecureHash=' . $vnpSecureHash;
        }

        return \response()->json([
            'url' => $vnp_Url
        ], 200);
    }

    public function vnpay_return(Request $request)
    {
        $url = session()->get('prev_url');
        $vnp_PayDate = $request['vnp_PayDate'];
        $vnp_TransactionNo = $request['vnp_TransactionNo'];
        $vnp_TxnRef = $request['vnp_TxnRef'];
        $vnp_OrderInfo = $request['vnp_OrderInfo'];

        if($request->vnp_ResponseCode == 00) {
            $carts = Session::get('carts');
            $request = Session::get('data');
            $request['vnp_PayDate'] = $vnp_PayDate;
            $request['vnp_TransactionNo'] = $vnp_TransactionNo;
            $request['vnp_TxnRef'] = $vnp_TxnRef;
            $request['vnp_OrderInfo'] = $vnp_OrderInfo;

            if (isset($carts['information']['code'])) {
                $carts['information']['totals'] = $carts['information']['totals'] + $carts['information']['ship_fee'] - $carts['information']['saleoff'];
            }
            else {
                $carts['information']['totals'] = $carts['information']['totals'] + $carts['information']['ship_fee'];
            }

            DB::beginTransaction();
            try {
                $order_id = Order::createOrder($request, $carts);
                Order_detail::createOrderDetails($carts, $order_id);
                foreach ($carts as $index=>$cart) {
                    if ($index == 'information') {
                        if (isset($cart['code'])) {
                            $discount_info['code'] = $cart['code'];
                            $discount_info['sale'] = $cart['saleoff'];
                        }
                        else {
                            $discount_info = [];
                        }
                    }
                    else {
                        $products_id[] = $cart['id'];
                        $product_attribute_values_id[] = $cart['product_attribute_value_id'];
                        $quantities[] = $cart['quantity'];
                        $product = Product::getProductById($cart['id']);
                        if ($product->sale_price != "") {
                            $price = $product->sale_price;
                        }
                        else {
                            $price = $product->price;
                        }
                        $totals_price[] = $cart['quantity'] * $price;
                    }
                }
                $shop_mail = DB::table('generals')->where('type', '=', 'contact')->first()->email;
                Mail::to($request['email'])->send(new MailNotify($request, $order_id, $products_id, $product_attribute_values_id, $quantities, $totals_price, $carts['information']['totals'], $discount_info, 'store'));
                Mail::to($shop_mail)->send(new MailVerify($request, $order_id, $products_id, $product_attribute_values_id, $quantities, $totals_price, $carts['information']['totals'], $discount_info));
                DB::commit();
                Session::forget('carts');
            }
            catch (\Exception $e) {
                DB::rollBack();
                throw new \Exception($e);
            }

            echo '<script>';
            echo 'alert("Bạn đã thanh toán và đặt hàng thành công, vui lòng theo dõi đơn hàng theo số điện thoại");';
            echo 'window.location.href="'.route('fe.index').'";';
            echo '</script>';
        }

        session()->forget('prev_url');
        echo '<script>';
        echo 'alert("Có lỗi xảy ra khi thanh toán, vui lòng thử lại sau");';
        echo 'window.location.href="'.$url.'";';
        echo '</script>';
    }
}
