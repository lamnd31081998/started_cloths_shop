<div class="cart_total_inner checkout_box">
    <div class="checkout_title">Thông tin thanh toán</div>
    <ul class="cart_extra_total_list">
        <li class="d-flex flex-row align-items-center justify-content-start">
            <div class="cart_extra_total_title">Giá trị đơn hàng</div>
            <div class="cart_extra_total_value ml-auto">{{ number_format($carts['information']['totals'], 0, '', '.') }}vnđ</div>
        </li>
        <li class="d-flex flex-row align-items-center justify-content-start">
            <div class="cart_extra_total_title">Hình thức giao hàng</div>
            <div class="cart_extra_total_value ml-auto">
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
                <input type="radio" id="payment_method" value="1"  name="payment_method">
                <span class="checkmark"></span>
            </label>
            <label class="payment_option clearfix">Thanh toán trực tuyến
                <input type="radio" id="payment_method" value="2" name="payment_method">
                <span class="checkmark"></span>
            </label>
        </div>
    </div>

    <div class="checkout_button trans_200"><a href="javascript:void(0)" onclick="addOrder()">Đặt đơn hàng</a></div>
    <div class="checkout_button trans_200" style="margin-top: 30px"><a href="javascript:void(0)" onclick="changePromotion()">Thay đổi mã giảm giá</a></div>
    <div class="checkout_button trans_200" style="margin-top: 30px"><a href="javascript:void(0)" onclick="deletePromotion()">Xóa mã giảm giá</a></div>
</div>
