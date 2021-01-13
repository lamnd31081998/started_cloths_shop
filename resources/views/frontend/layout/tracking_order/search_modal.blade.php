@foreach($orders as $order)
    <?php
        $order_details = Illuminate\Support\Facades\DB::table('order_details')->where('order_id', '=', $order->id)->get();
        $totals_price = 0;
    ?>
    <div class="modal fade" role="dialog" id="detail_order_{{ $order->id }}" style="padding-right: unset">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top: 30%;">
                <div class="modal-header">
                    <h4 class="modal-title" style="color: black; font-weight: 700">Chi tiết đơn hàng {{ $order->id }}</h4>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="text-center dataTable table-bordered table table-hover dtr-inline" id="order_details_table">
                            <thead>
                                <tr>
                                    <th>Mã sản phẩm</th>
                                    <th>Danh mục</th>
                                    <th>Tên sản phẩm</th>
                                    <th>Màu sắc</th>
                                    <th>Kích cỡ</th>
                                    <th>Đơn giá</th>
                                    <th>Số lượng</th>
                                    <th>Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order_details as $order_detail)
                                    <tr>
                                        <td>{{ $order_detail->product_id }}</td>
                                        <td>
                                            <?php $product =  \App\Models\Product::getProductById($order_detail->product_id); ?>
                                            {{ \App\Models\Category::getCategoryById($product->category_id)->name }}
                                        </td>
                                        <td>
                                            <?php
                                                $category = \App\Models\Category::getCategoryById($product->category_id);
                                                $category_slug = $category->slug;
                                                $category_dad_slug = \App\Models\Category::getCategoryById($category->parent_id)->slug;
                                                $product_slug =  \App\Models\Product::getProductById($order_detail->product_id)->slug;
                                            ?>
                                            <a target="_blank" href="{{ route('fe.product.detail', ['category_dad_slug' => $category_dad_slug, 'category_slug' => $category_slug, 'product_slug' => $product_slug]) }}">{{ \App\Models\Product::getProductById($order_detail->product_id)->name }}</a>
                                        </td>
                                        <td>{{ \App\Models\Attribute_value::getAttributevalueById(\App\Models\Product_Attribute_value::getProductAttributeValueById($order_detail->product_attribute_value_id)->color_id)->value }}</td>
                                        <td>{{ \App\Models\Attribute_value::getAttributevalueById(\App\Models\Product_Attribute_value::getProductAttributeValueById($order_detail->product_attribute_value_id)->size_id)->value }}</td>
                                        <td>
                                            @if (\App\Models\Product::getProductById($order_detail->product_id)->sale_price != 0)
                                                {{ number_format(\App\Models\Product::getProductById($order_detail->product_id)->sale_price, 0, '', '.') }}vnđ
                                            @else
                                                {{ number_format(\App\Models\Product::getProductById($order_detail->product_id)->price, 0, '', '.') }}vnđ
                                            @endif
                                        </td>
                                        <td>{{ $order_detail->quantity }}</td>
                                        <td>{{ number_format($order_detail->total_price, 0, '', '.') }}vnđ</td>
                                    </tr>
                                    <?php
                                        $totals_price += $order_detail->total_price;
                                    ?>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="7">
                                        <span style="float: right">Loại hình giao hàng - {{ \App\Models\Shipfee::getShipfeeById($order->ship_id)->name }}</span>
                                    </td>
                                    <td>
                                        <span>{{ \App\Models\Shipfee::getShipfeeById($order->ship_id)->price != 0 ? number_format(\App\Models\Shipfee::getShipfeeById($order->ship_id)->price, 0, '', '.').'vnđ' : 'Miễn phí' }}</span>
                                    </td>
                                </tr>
                                @if ($order->promotion != null)
                                    <tr>
                                        <td colspan="7">
                                            <span style="float: right">Mã giảm giá - {{ $order->promotion }}</span>
                                        </td>
                                        <td>
                                            <span>- {{ number_format($totals_price * \App\Models\Promotion::getPromotionByCode($order->promotion)->discount /100, 0, '', '.') }}vnđ</span>
                                        </td>
                                    </tr>
                                @endif
                                <tr>
                                    <td colspan="7">
                                        <span style="float: right">Tổng tiền</span>
                                    </td>
                                    <td>
                                        <span>{{ number_format($order->totals, 0, '', '.') }}vnđ</span>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                {{-- /.modal-body --}}
                <div class="modal-footer">
                    <button class="btn btn-danger" data-dismiss="modal">Đóng</button>
                </div>
            </div>
            {{-- /.modal-content --}}
        </div>
    </div>
    {{-- /.modal-dialog --}}
@endforeach
