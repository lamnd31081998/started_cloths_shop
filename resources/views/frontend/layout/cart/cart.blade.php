<div class="cart_section">
    <div class="section_container">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="cart_container">

                        <!-- Cart Bar -->
                        <div class="cart_bar">
                            <ul class="cart_bar_list item_list d-flex flex-row align-items-center justify-content-start">
                                <li>Sản phẩm</li>
                                <li>Màu sắc</li>
                                <li>Kích cỡ</li>
                                <li>Giá</li>
                                <li>Số lượng</li>
                                <li>Tổng tiền</li>
                            </ul>
                        </div>

                        <!-- Cart Items -->
                        <div class="cart_items">
                            <ul class="cart_items_list">
                                @if (count($carts) == 0)
                                    <li class="cart_item item_list d-flex flex-lg-row flex-column align-items-lg-center align-items-start justify-content-start">
                                        Hãy tiếp tục mua sắm
                                    </li>
                                @else
                                    @foreach($carts as $index=>$item)
                                        @if ($index != 'information')
                                            <input type="hidden" name="index" value="{{ $index }}">
                                            <!-- Cart Item -->
                                            <li class="cart_item item_list d-flex flex-lg-row flex-column align-items-lg-center align-items-start justify-content-start">
                                                <div class="product d-flex flex-lg-row flex-column align-items-lg-center align-items-start justify-content-start">
                                                    <div><div class="product_image"><img src="{{ asset($item['image']) }}"></div></div>
                                                    <div class="product_name"><a href="{{ $item['link'] }}">{{ $item['name'] }}</a></div>
                                                </div>
                                                <div class="product_color text-lg-center product_text"><span>Màu sắc: </span>{{ \App\Models\Attribute_value::getAttributevalueById($item['color_id'])->value }}</div>
                                                <div class="product_size text-lg-center product_text"><span>Kích cỡ: </span>{{ \App\Models\Attribute_value::getAttributevalueById($item['size_id'])->value }}</div>
                                                <div class="product_price text-lg-center product_text"><span>Giá: </span>{{ number_format($item['price'], 0, '', '.') }}vnđ</div>
                                                <div class="product_quantity_container">
                                                    <div class="product_quantity ml-lg-auto mr-lg-auto text-center">
                                                        <span class="product_text product_num" id="quantity_{{ $index }}">{{ $item['quantity'] }}</span>
                                                        <div class="qty_sub qty_button trans_200 text-center"><span>-</span></div>
                                                        <div class="qty_add qty_button trans_200 text-center"><span>+</span></div>
                                                    </div>
                                                </div>
                                                <div class="product_total text-lg-center product_text"><span>Total: </span>{{ number_format($item['total_price'], 0, '', '.') }}vnđ</div>
                                            </li>
                                        @endif
                                    @endforeach
                                @endif
                            </ul>
                        </div>

                        <!-- Cart Buttons -->
                        <div class="cart_buttons d-flex flex-row align-items-start justify-content-start">
                            <div class="cart_buttons_inner ml-auto d-flex flex-row align-items-start justify-content-start flex-wrap">
                                <div class="button button_continue trans_200"><a href="{{ route('fe.index') }}">Tiếp tục mua sắm</a></div>
                                <div class="button button_clear trans_200"><a href="javascript:void(0)" onclick="deleteCart()">Xóa toàn bộ giỏ hàng</a></div>
                                <div class="button button_update trans_200"><a href="javascript:void(0)" onclick="updateCart()">Cập nhật giỏ hàng</a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="section_container cart_extra_container">
        <div class="container">
            <div class="row">

                <!-- Shipping & Delivery -->
                <div class="col-xxl-6">
                    <div class="cart_extra cart_extra_1">
                        <div class="cart_extra_content cart_extra_coupon">
                            <div class="cart_extra_title">Mã giảm giá</div>
                            <div class="coupon_form_container">
                                <input type="text" class="coupon_input" id="promotion">
                                <button type="button" class="coupon_button" style="outline: none" onclick="checkPromotion()">Nhập mã</button>
                            </div>

                            <div class="shipping">
                                <div class="cart_extra_title">Loại hình giao hàng</div>
                                <ul>
                                    @foreach($ship_fees as $ship_fee)
                                        <li class="shipping_option d-flex flex-row align-items-center justify-content-start">
                                            <label class="radio_container">
                                                <input type="radio" value="{{ $ship_fee->price }}" name="ship" class="shipping_radio">
                                                <span class="radio_mark"></span>
                                                <span class="radio_text">{{ $ship_fee->name }} {{ $ship_fee->description != "" ? '('.$ship_fee->description.')' : '' }}</span>
                                            </label>
                                            <div class="shipping_price ml-auto">{{ $ship_fee->price != 0 ? number_format($ship_fee->price, 0, '', '.').'vnđ' : 'Miễn phí' }}</div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cart Total -->
                <div class="col-xxl-6" id="totals_box">
                    <div class="cart_extra cart_extra_2">
                        <div class="cart_extra_content cart_extra_total">
                            <div class="cart_extra_title">Thành tiền</div>
                            <ul class="cart_extra_total_list">
                                <li class="d-flex flex-row align-items-center justify-content-start">
                                    <div class="cart_extra_total_title">Giá trị đơn hàng</div>
                                    @if (count($carts) != 0)
                                        <div class="cart_extra_total_value ml-auto">{{ number_format($carts['information']['totals'], 0, '', '.') }}vnđ</div>
                                    @else
                                        <div class="cart_extra_total_value ml-auto">0vnđ</div>
                                    @endif
                                </li>
                                <li class="d-flex flex-row align-items-center justify-content-start">
                                    <div class="cart_extra_total_title">Phí giao hàng</div>
                                    @if (count($carts) != 0)
                                        @if ($carts['information']['ship_fee'] != 0)
                                            <div class="cart_extra_total_value ml-auto" id="ship_price">{{ number_format($carts['information']['ship_fee'], 0, '', '.') }}vnđ</div>
                                        @else
                                            <div class="cart_extra_total_value ml-auto" id="ship_price">Miễn phí</div>
                                        @endif
                                    @else
                                        <div class="cart_extra_total_value ml-auto" id="ship_price">0vnđ</div>
                                    @endif
                                </li>
                                @if (isset($carts['information']['saleoff']))
                                    <li class="d-flex flex-row align-items-center justify-content-start">
                                        <div class="cart_extra_total_title">Mã giảm giá: {{ $carts['information']['code'] }}</div>
                                        <div class="cart_extra_total_value ml-auto">-{{ number_format($carts['information']['saleoff'], 0, '', '.') }}vnđ</div>
                                    </li>
                                @endif
                                <li class="d-flex flex-row align-items-center justify-content-start">
                                    <div class="cart_extra_total_title">Tổng tiền</div>
                                    @if (count($carts) != 0)
                                        <div class="cart_extra_total_value ml-auto">
                                            @if (isset($carts['information']['saleoff']))
                                                {{ number_format($carts['information']['totals']+$carts['information']['ship_fee']-$carts['information']['saleoff'], 0, '', '.') }}vnđ
                                            @else
                                                {{ number_format($carts['information']['totals']+$carts['information']['ship_fee'], 0, '', '.') }}vnđ
                                            @endif
                                        </div>
                                    @else
                                        <div class="cart_extra_total_value ml-auto">0vnđ</div>
                                    @endif
                                </li>
                            </ul>
                            <div class="checkout_button trans_200">
                                <a href="{{ route('fe.cart.checkout') }}">Thanh toán</a>
                            </div>
                            <div class="button button_update trans_200" style="margin-top: 30px">
                                <a href="javascript:void(0)" onclick="deletePromotion()">Xóa mã giảm giá</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
