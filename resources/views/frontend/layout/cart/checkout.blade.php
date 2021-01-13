<!-- Checkout -->

<div class="checkout">
    <div class="section_container">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="checkout_container d-flex flex-xxl-row flex-column align-items-start justify-content-start">

                        <!-- Billing -->
                        <div class="billing checkout_box">
                            <div class="checkout_title">Địa chỉ nhận hàng</div>
                            <div class="checkout_form_container">
                                <form action="#" class="checkout_form">
                                    <div>
                                        <!-- Name -->
                                        <label for="name">Tên người đặt hàng <span style="color:red">(*)</span></label>
                                        <input type="text" id="name" class="checkout_input">
                                    </div>
                                    <div>
                                        <!-- Email -->
                                        <label for="email">Email <span style="color: red">(*)</span></label>
                                        <input type="email" id="email" class="checkout_input">
                                    </div>
                                    <div>
                                        <!-- Phone number -->
                                        <label for="checkout_phone">Số điện thoại <span style="color:red;">(*)</span></label>
                                        <input type="text" pattern="[0]{1}[1-9]{1}[0-9]{8}" id="phone_number" class="checkout_input">
                                    </div>
                                    <div>
                                        <!-- TP -->
                                        <label for="tinh_id">Thành phố <span style="color:red">(*)</span></label>
                                        <select onchange="getHuyen()" id="tinh_id" style="width: 100%;" class="dropdown_item_select checkout_input">
                                            <option value="">--- Thành phố ---</option>
                                            @foreach($tinhs as $tinh)
                                                <option value="{{ $tinh->id }}">{{ $tinh->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div id="huyen_box">
                                        <!-- Quan -->
                                        <label for="huyen_id">Quận <span style="color: red">(*)</span></label>
                                        <select id="huyen_id" style="width: 100%;" class="dropdown_item_select checkout_input">
                                            <option value="">--- Quận ---</option>
                                        </select>
                                    </div>
                                    <div id="xa_box">
                                        <!-- Phuong -->
                                        <label for="xa_id">Phường <span style="color: red">(*)</span></label>
                                        <select id="xa_id" style="width: 100%;" class="dropdown_item_select checkout_input">
                                            <option value="">--- Phường ---</option>
                                        </select>
                                    </div>
                                    <div>
                                        <!-- Address -->
                                        <label for="address">Địa chỉ cụ thể <span style="color:red">(*)</span></label>
                                        <input type="text" id="address" class="checkout_input">
                                    </div>
                                    <div>
                                        <!-- Note -->
                                        <label for="note">Ghi chú</label>
                                        <textarea style="height: 100px" type="text" id="note" class="checkout_input"></textarea>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Cart Total -->
                        <div class="cart_total" id="totals_box">
                            <div class="cart_total_inner checkout_box">
                                <div class="checkout_title">Thông tin thanh toán</div>
                                <ul class="cart_extra_total_list">
                                    <li class="d-flex flex-row align-items-center justify-content-start">
                                        <div class="cart_extra_total_title">Giá trị đơn hàng</div>
                                        <div class="cart_extra_total_value ml-auto">{{ number_format($carts['information']['totals'], 0, '', '.') }}vnđ</div>
                                    </li>
                                    <li class="d-flex flex-row align-items-center justify-content-start">
                                        <div class="cart_extra_total_title">Hình thức giao hàng</div>
                                        <div class="cart_extra_total_value ml-auto" style="text-align: right">
                                            {{ DB::table('shipfees')->where('price', '=', $carts['information']['ship_fee'])->first()->name }}
                                        </div>
                                    </li>
                                    <li class="d-flex flex-row align-items-center justify-content-start">
                                        <div class="cart_extra_total_title">Phí giao hàng</div>
                                        <div class="cart_extra_total_value ml-auto">
                                            @if ($carts['information']['ship_fee'] != 0)
                                                {{ number_format($carts['information']['ship_fee'], 0, '', '.') }}vnđ
                                            @else
                                                Miễn phí
                                            @endif
                                        </div>
                                    </li>
                                    @if (isset($carts['information']['code']))
                                        <li class="d-flex flex-row align-items-center justify-content-start">
                                            <div class="cart_extra_total_title">Mã giảm giá: {{ $carts['information']['code'] }}</div>
                                            <div class="cart_extra_total_value ml-auto">
                                                -{{ number_format($carts['information']['saleoff'], 0, '', '.') }}vnđ
                                            </div>
                                        </li>
                                    @endif
                                    <li class="d-flex flex-row align-items-center justify-content-start">
                                        <div class="cart_extra_total_title">Tổng tiền</div>
                                        <div class="cart_extra_total_value ml-auto">
                                            @if (isset($carts['information']['code']))
                                                {{ number_format($carts['information']['totals']+$carts['information']['ship_fee']-$carts['information']['saleoff'], 0, '', '.') }}vnđ
                                            @else
                                                {{ number_format($carts['information']['totals']+$carts['information']['ship_fee'], 0, '', '.') }}vnđ
                                            @endif
                                        </div>
                                    </li>
                                </ul>

                                <!-- Payment Options -->
                                <div class="payment">
                                    <div class="payment_options">
                                        <label class="payment_option clearfix">Thanh toán bằng tiền mặt
                                            <input type="radio" id="payment_method" name="payment_method" value="1">
                                            <span class="checkmark"></span>
                                        </label>
                                        <label class="payment_option clearfix">Thanh toán trực tuyến
                                            <input type="radio" id="payment_method" name="payment_method" value="2">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="checkout_button trans_200"><a href="javascript:void(0)" onclick="addOrder()">Đặt đơn hàng</a></div>
                                <div class="checkout_button trans_200" style="margin-top: 30px"><a href="javascript:void(0)" onclick="changePromotion()">Thay đổi mã giảm giá</a></div>
                                <div class="checkout_button trans_200" style="margin-top: 30px"><a href="javascript:void(0)" onclick="deletePromotion()">Xóa mã giảm giá</a></div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
