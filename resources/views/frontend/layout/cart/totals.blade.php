<div class="cart_extra cart_extra_2">
    <div class="cart_extra_content cart_extra_total">
        <div class="cart_extra_title">Thành tiền</div>
        <ul class="cart_extra_total_list">
            <li class="d-flex flex-row align-items-center justify-content-start">
                <div class="cart_extra_total_title">Giá trị đơn hàng</div>
                <div class="cart_extra_total_value ml-auto">{{ number_format($carts['information']['totals'], 0, '', '.') }}vnđ</div>
            </li>
            <li class="d-flex flex-row align-items-center justify-content-start">
                <div class="cart_extra_total_title">Phí giao hàng</div>
                @if ($carts['information']['ship_fee'] != 0)
                    <div class="cart_extra_total_value ml-auto" id="ship_price">{{ number_format($carts['information']['ship_fee'], 0, '', '.') }}vnđ</div>
                @else
                    <div class="cart_extra_total_value ml-auto" id="ship_price">Miễn phí</div>
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
                <div class="cart_extra_total_value ml-auto">
                    @if (isset($carts['information']['saleoff']))
                        {{ number_format($carts['information']['totals']+$carts['information']['ship_fee']-$carts['information']['saleoff'], 0, '', '.') }}vnđ
                    @else
                        {{ number_format($carts['information']['totals']+$carts['information']['ship_fee'], 0, '', '.') }}vnđ
                    @endif
                </div>
            </li>
        </ul>
        <div class="checkout_button trans_200"><a href="{{ route('fe.cart.checkout') }}">Thanh toán</a></div>
        <div class="button button_update trans_200" style="margin-top: 30px"><a href="javascript:void(0)" onclick="deletePromotion()">Xóa mã giảm giá</a></div>
    </div>
</div>
