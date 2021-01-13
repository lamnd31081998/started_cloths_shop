<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Mail\MailNotify;
use App\Mail\MailVerify;
use App\Models\Attribute_value;
use App\Models\Category;
use App\Models\Huyen;
use App\Models\Order;
use App\Models\Order_detail;
use App\Models\Product;
use App\Models\Product_Attribute_value;
use App\Models\Shipfee;
use App\Models\Tinh;
use App\Models\Xa;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use mysql_xdevapi\Exception;
use Session;
use VNPay;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $orders = Order::getAllOrders();
        return view('backend.order.index')->with(['orders' => $orders]);
    }

    public function update_status(Request $request)
    {
        $order = Order::getOrderById($request->order_id);
        $order_details = Order_detail::getOrderDetailsByOrderId($request->order_id);

        if ($order->payment_method == 1) {
            if ($request->status == 7) {
                foreach ($order_details as $order_detail) {
                    $stock = Product_Attribute_value::getProductAttributeValueById($order_detail->product_attribute_value_id)->quantity;
                    $update_stock = $order_detail->quantity + $stock;
                    DB::table('product_attribute_values')->where('id', '=', $order_detail->product_attribute_value_id)->update(['quantity' => $update_stock, 'updated_at' => Carbon::now()]);
                }
                DB::table('orders')->where('id', '=', $request->order_id)->update(['status' => $request->status, 'updated_at' => Carbon::now()]);
                $orders = Order::getAllOrders();
                return response()->json([
                    'message' => 'Cập nhật trạng thái đơn hàng thành công',
                    'view' => view('backend.order.index')->with(['orders' => $orders])->render()
                ], 200);
            }
            else if (DB::table('orders')->where('id', '=', $request->order_id)->update(['status' => $request->status, 'updated_at' => Carbon::now()])) {
                $orders = Order::getAllOrders();
                return response()->json([
                    'message' => 'Cập nhật trạng thái đơn hàng thành công',
                    'view' => view('backend.order.index')->with(['orders' => $orders])->render()
                ], 200);
            }
        }

        else {
            if ($request->status == 8) {
                foreach ($order_details as $order_detail) {
                    $stock = Product_Attribute_value::getProductAttributeValueById($order_detail->product_attribute_value_id)->quantity;
                    $update_stock = $order_detail->quantity + $stock;
                    DB::table('product_attribute_values')->where('id', '=', $order_detail->product_attribute_value_id)->update(['quantity' => $update_stock, 'updated_at' => Carbon::now()]);
                }
                $order_status = Order::getOrderById($request->order_id)->status;
                if ($order_status < 4) {
                    DB::table('orders')->where('id', '=', $request->order_id)->update(['promotion' => null, 'status' => $request->status, 'updated_at' => Carbon::now()]);
                }
                else {
                    DB::table('orders')->where('id', '=', $request->order_id)->update(['status' => $request->status, 'updated_at' => Carbon::now()]);
                }
                $orders = Order::getAllOrders();
                return response()->json([
                    'vnp_TransactionNo' => $order->vnp_TransactionNo,
                    'message' => 'Cập nhật trạng thái đơn hàng thành công',
                    'view' => view('backend.order.index')->with(['orders' => $orders])->render()
                ], 200);
            }
            else if (DB::table('orders')->where('id', '=', $request->order_id)->update(['status' => $request->status, 'updated_at' => Carbon::now()])) {
                $orders = Order::getAllOrders();
                return response()->json([
                    'vnp_TransactionNo' => $order->vnp_TransactionNo,
                    'message' => 'Cập nhật trạng thái đơn hàng thành công',
                    'view' => view('backend.order.index')->with(['orders' => $orders])->render()
                ], 200);
            }
        }
    }

    public static function get_order_detail(Request $request)
    {
        $order_details = Order_detail::getOrderDetailsByOrderId($request->order_id);
        return response()->json([
            'view' => view('backend.order.components.order_detail')->with(['order_details' => $order_details, 'order' => Order::getOrderById($request->order_id)])->render()
        ], 200);
    }

    public static function cancel(Request $request)
    {
        $order_details = Order_detail::getOrderDetailsByOrderId($request->order_id);
        DB::beginTransaction();
        try {
            foreach ($order_details as $order_detail) {
                $quantity_stock = Product_Attribute_value::getProductAttributeValueById($order_detail->product_attribute_value_id)->quantity;
                DB::table('product_attribute_values')->where('id', '=', $order_detail->product_attribute_value_id)->update(['quantity' => ($quantity_stock+$order_detail->quantity), 'updated_at' => Carbon::now()]);
            }
            $order_status = Order::getOrderById($request->order_id)->status;
            if ($order_status < 4) {
                DB::table('orders')->where('id', '=', $request->order_id)->update(['promotion' => null, 'status' => 0, 'updated_at' => Carbon::now()]);
            }
            else {
                DB::table('orders')->where('id', '=', $request->order_id)->update(['status' => 0, 'updated_at' => Carbon::now()]);
            }
            DB::commit();
            $orders = Order::getAllOrders();
            return response()->json([
                'message' => 'Hủy đơn hàng thành công',
                'view' => view('backend.order.index')->with(['orders' => $orders])->render()
            ], 200);
        }
        catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'error' => $e
            ], 500);
        }
    }

    public function vnpay_refund(Request $request)
    {
        $order = Order::getOrderById($request->order_id);
        $vnp_TmnCode = 'B18K5L6Q';
        $vnp_Amount = ($order->totals*100);
        $vnp_IpAddr = request()->ip();
        $vnp_OrderInfo = 'Hoàn tiền đơn hàng '.$order->id;
        $vnp_TxnRef = $order->vnp_TxnRef;
        $vnp_Url = 'https://sandbox.vnpayment.vn/merchant_webapi/merchant.html';
        $vnp_HashSecret = 'VQ4EQ402YTD51XVX0X1ZMGX3TXQ8WS2S';

        $inputData = array(
            "vnp_Version" => "2.0.0",
            "vnp_Command" => "refund",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_CreateBy" => "aStar",
            "vnp_TransactionType" => "02",
            "vnp_TxnRef" => $vnp_TxnRef,
            "vnp_Amount" => $vnp_Amount,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_TransDate" => $order->vnp_PayDate,
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_IpAddr" => $vnp_IpAddr,
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

        $client = new Client();
        $response = $client->get($vnp_Url);

        return response()->json([
            'data' => $response->getBody()->getContents()
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request)
    {
        $categories = Category::getAllCategoriesSecondLevel();
        $tinhs = Tinh::getAllTinh();
        $shipfees = Shipfee::getAllShipfees();
        return view('backend.order.create')->with(['tinhs' => $tinhs, 'shipfees' => $shipfees, 'categories' => $categories]);
    }

    public static function get_products(Request $request)
    {
        $products = Product::getProductByCategoryId($request->category_id);
        return response()->json([
            'total_products' => count($products),
            'view' => view('backend.order.components.get_products')->with(['products' => $products])->render()
        ], 200);
    }

    public static function get_product_attribute_values(Request $request)
    {
        $product_attribute_values = Product_Attribute_value::getProductAttributevalueByProductId($request->product_id);
        return response()->json([
            'total_product_attribute_values' => count($product_attribute_values),
            'view' => view('backend.order.components.get_product_attribute_values')->with(['product_attribute_values' => $product_attribute_values])->render()
        ], 200);
    }

    public function add_product(Request $request)
    {
        $quantity = $request->quantity;
        $shipfee = Shipfee::getShipfeeById($request->ship_id);
        $category = Category::getCategoryById($request->category_id);
        $product = Product::getProductById($request->product_id);
        $product_attribute_value = Product_Attribute_value::getProductAttributeValueById($request->product_attribute_value_id);
        if ($quantity > $product_attribute_value->quantity) {
            return response()->json([
                'message' => 'Số lượng đặt vượt quá số lượng sản phẩm có trong kho'
            ], 400);
        }

        if ($product->sale_price != "") {
            $price = $product->sale_price;
        }
        else {
            $price = $product->price;
        }
        $color = Attribute_value::getAttributevalueById($product_attribute_value->color_id);
        $size = Attribute_value::getAttributevalueById($product_attribute_value->size_id);

        return response()->json([
            'totals' => number_format($request->totals + $shipfee->price + $price * $quantity, 0, '', '.'),
            'total_price' => $price * $quantity,
            'view' => view('backend.order.components.order_product_table')->with(['i' => $request->i, 'category' => $category, 'product' => $product, 'price' => $price, 'product_attribute_value' => $product_attribute_value, 'color' => $color, 'size' => $size, 'quantity' => $quantity])->render()
        ], 200);
    }

    public function update_totals(Request $request)
    {
        $shipfee = Shipfee::getShipfeeById($request->ship_id);
        $totals = $request->totals + $shipfee->price;
        $product_attribute_value = Product_Attribute_value::getProductAttributeValueById($request->product_attribute_value_id);
        if (isset($request->quantity) && $request->quantity > $product_attribute_value->quantity) {
            return response()->json([
                'message' => 'Số lượng đặt vượt quá số lượng còn trong kho'
            ], 400);
        }

        if (!isset($request->type)) {
            return response()->json([
                'totals' => number_format($totals, 0, '', '.')
            ], 200);
        }
        else if ($request->type == "change_ship") {
            if ($shipfee->price == 0) {
                $shipfee_price = 'Miễn phí';
            }
            else {
                $shipfee_price = number_format($shipfee->price, 0, '', '.').'vnđ';
            }
            return response()->json([
                'view' => 'Hình thức giao hàng - '.$shipfee->name.': '.$shipfee_price,
                'totals' => number_format($totals, 0, '', '.')
            ]);
        }
        else {
            $products_ordered = [];
            $i = 1;
            if (isset($request->products_ordered)) {
                foreach ($request->products_ordered as $product_ordered) {
                    $products_ordered[$i] = [
                        'i' => $i,
                        'product_id' => $product_ordered['product_id'],
                        'category_id' => $product_ordered['category_id'],
                        'product_attribute_value_id' => $product_ordered['product_attribute_value_id'],
                        'quantity' => $product_ordered['quantity'],
                        'total_price' => $product_ordered['total_price']
                    ];
                    $i = $i + 1;
                }
            }
            return response()->json([
                'totals' => number_format($totals, 0, '', '.'),
                'products_ordered' => $products_ordered,
                'view' => view('backend.order.components.order_product_table')->with(['products_ordered' => $products_ordered])->render()
            ], 200);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $totals = 0;
        $totals_price = [];
        $shipfee = Shipfee::getShipfeeById($request->ship_id);
        foreach ($request->products as $index=>$product_id) {
            $product = Product::getProductById($product_id);
            if ($product->sale_price != "") {
                $totals = $totals + $product->sale_price * $request->quantities[$index];
                $totals_price[] = $product->sale_price * $request->quantities[$index];
            }
            else {
                $totals = $totals + $product->price * $request->quantities[$index];
                $totals_price[] = $product->price * $request->quantities[$index];
            }
        }
        $totals = $totals + $shipfee->price;
        DB::beginTransaction();
        try {
            $order_id = Order::createOrderfromBE($request->all(), $totals);
            Order_detail::createOrderDetailsfromBE($order_id, $request->products, $request->product_attribute_values, $request->quantities, $totals_price);
            Mail::to($request->email)->send(new MailNotify($request->all(), $order_id, $request->products, $request->product_attribute_values, $request->quantities, $totals_price, $totals, [], 'store'));
            DB::commit();
            echo '<script>';
            echo 'alert("Thêm đơn hàng thành công");';
            echo 'window.location.href="'.route('be.order.index').'";';
            echo '</script>';
        }
        catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e);
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
        $order = Order::getOrderById($id);
        $order_details = Order_detail::getOrderDetailsByOrderId($id);
        $categories = Category::getAllCategoriesSecondLevel();
        $tinhs = Tinh::getAllTinh();
        $huyens = Huyen::getAllHuyen();
        $xas = Xa::getAllXa();
        $shipfees = Shipfee::getAllShipfees();
        return view('backend.order.edit')->with([
            'order' => $order,
            'order_details' => $order_details,
            'categories' => $categories,
            'tinhs' => $tinhs,
            'huyens' => $huyens,
            'xas' => $xas,
            'shipfees' => $shipfees
        ]);
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
        $order_details = Order_detail::getOrderDetailsByOrderId($id);
        DB::beginTransaction();
        try {
            foreach ($order_details as $order_detail) {
                $stock = Product_Attribute_value::getProductAttributeValueById($order_detail->product_attribute_value_id)->quantity;
                DB::table('product_attribute_values')->where('id', '=', $order_detail->product_attribute_value_id)->update([
                    'quantity' => $stock + $order_detail->quantity,
                    'updated_at' => Carbon::now()
                ]);
                Order_detail::destroy($order_detail->id);
            }
            $totals = 0;
            $totals_price = [];
            $shipfee = Shipfee::getShipfeeById($request->ship_id);
            foreach ($request->products as $index=>$product_id) {
                $product = Product::getProductById($product_id);
                if ($product->sale_price != "") {
                    $totals = $totals + $product->sale_price * $request->quantities[$index];
                    $totals_price[] = $product->sale_price * $request->quantities[$index];
                }
                else {
                    $totals = $totals + $product->price * $request->quantities[$index];
                    $totals_price[] = $product->price * $request->quantities[$index];
                }
            }
            $totals = $totals + $shipfee->price;
            Order::where('id', $id)->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'tinh_id' => $request->tinh_id,
                'huyen_id' => $request->huyen_id,
                'xa_id' => $request->xa_id,
                'address' => $request->address,
                'ship_id' => $request->ship_id,
                'payment_method' => $request->payment_method,
                'totals' => $totals,
                'note' => $request->note,
                'updated_at' => Carbon::now()
            ]);
            Order_detail::createOrderDetailsfromBE($id, $request->products, $request->product_attribute_values, $request->quantities, $totals_price);
            Mail::to($request->email)->send(new MailNotify($request->all(), $id, $request->products, $request->product_attribute_values, $request->quantities, $totals_price, $totals, [], 'update'));
            DB::commit();

            echo '<script>';
            echo 'alert("Thêm đơn hàng thành công");';
            echo 'window.location.href="'.route('be.order.index').'";';
            echo '</script>';

        }
        catch (\Exception $e) {
            DB::rollBack();
            throw new Exception($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
