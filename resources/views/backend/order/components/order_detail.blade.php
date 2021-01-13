<table class="table table-bordered table-hover text-center dataTable dtr-inline" style="border: 1px solid #dee2e6">
    <thead>
    <tr>
        <th>STT</th>
        <th>Danh mục</th>
        <th>Sản phẩm</th>
        <th>Màu sắc</th>
        <th>Kích cỡ</th>
        <th>Đơn giá</th>
        <th>Số lượng</th>
        <th>Thành tiền</th>
    </tr>
    </thead>
    <tbody>

    @foreach($order_details as $index=>$order_detail)
        <tr>
            <td>{{ $index+1 }}</td>
            <td>{{ \App\Models\Category::getCategoryById(\App\Models\Product::getProductById($order_detail->product_id)->category_id)->name }}</td>
            <td>
                <?php
                    $product = \App\Models\Product::getProductById($order_detail->product_id);
                    $product_slug = $product->slug;
                    $category = \App\Models\Category::getCategoryById($product->category_id);
                    $category_slug = $category->slug;
                    $category_dad_slug = \App\Models\Category::getCategoryById($category->parent_id)->slug;
                ?>
                <a target="_blank" href="{{ route('fe.product.detail', ['category_dad_slug' => $category_dad_slug, 'category_slug' => $category_slug, 'product_slug' => $product_slug]) }}">{{ \App\Models\Product::getProductById($order_detail->product_id)->name }}</a>
            </td>
            <td>{{ \App\Models\Attribute_value::getAttributevalueById(\App\Models\Product_Attribute_value::getProductAttributeValueById($order_detail->product_attribute_value_id)->color_id)->value }}</td>
            <td>{{ \App\Models\Attribute_value::getAttributevalueById(\App\Models\Product_Attribute_value::getProductAttributeValueById($order_detail->product_attribute_value_id)->size_id)->value }}</td>
            <td>
                @if (\App\Models\Product::getProductById($order_detail->product_id)->sale_price != "")
                    {{ number_format(\App\Models\Product::getProductById($order_detail->product_id)->sale_price, 0, '', '.') }}vnđ
                @else
                    {{ number_format(\App\Models\Product::getProductById($order_detail->product_id)->price, 0, '', '.') }}vnđ
                @endif
            </td>
            <td>{{ $order_detail->quantity }}</td>
            <td>{{ number_format($order_detail->total_price,0 ,'', '.') }}vnđ</td>
        </tr>
    @endforeach
    <tr>
        <td colspan="7">
            <span style="float: right">Loại hình giao hàng - {{ \App\Models\Shipfee::getShipfeeById($order->ship_id)->name }}</span>
        </td>
        <td>
            {{ (\App\Models\Shipfee::getShipfeeById($order->ship_id)->price) != 0 ? number_format(\App\Models\Shipfee::getShipfeeById($order->ship_id)->price, 0, '', '.').'vnđ' : 'Miễn phí'  }}
        </td>
    </tr>
    @if (!empty($order->promotion))
        <?php
            $order_details = \App\Models\Order_detail::getOrderDetailsByOrderId($order->id);
            $totals_price = 0;
            foreach ($order_details as $order_detail) {
                $totals_price = $totals_price + $order_detail->total_price;
            }
        ?>
        <tr style="text-align: right">
            <td colspan="7">
                <span style="float: right">Mã giảm giá - {{ $order->promotion }}</span>
            </td>
            <td>
                -{{ number_format($totals_price * \App\Models\Promotion::getPromotionByCode($order->promotion)->discount/100, 0, '', '.') }}vnđ
            </td>
        </tr>
    @endif
    <tr style="text-align: right">
        <td colspan="7">
            <span style="float: right">Tổng giá trị đơn hàng</span>
        </td>
        <td>
            {{ number_format($order->totals, 0, '', '.') }}vnđ
        </td>
    </tr>
    </tbody>
</table>
