<!-- Header (For Mobile) -->
<header class="header">
    <div class="header_content d-flex flex-row align-items-center justify-content-start">

        <!-- Hamburger -->
        <div class="hamburger menu_mm"><i class="fa fa-bars menu_mm" aria-hidden="true"></i></div>

        <!-- Navigation -->
        <nav class="header_nav">
            <ul class="d-flex flex-row align-items-center justify-content-start">
                <li><a href="{{ route('fe.index') }}">Trang chủ</a></li>
                @foreach($categories as $category)
                    <li><a href="{{ route('fe.category.index', ['category_slug' => $category->slug]) }}">{{ $category->name }}</a></li>
                @endforeach
                <li>
                    <a href="{{ route('fe.index.tracking_orders') }}">Tra cứu đơn hàng</a>
                </li>
                <li>
                    <a href="{{ route('fe.index.about_us') }}">Về chúng tôi</a>
                </li>
                <li>
                    <a href="{{ route('fe.index.contact_us') }}">Liên hệ với chúng tôi</a>
                </li>
            </ul>
        </nav>

        <!-- Header Extra -->
        <div class="header_extra ml-auto d-flex flex-row align-items-center justify-content-start">

            <!-- Cart -->
            <div class="cart d-flex flex-row align-items-center justify-content-start">
                <div class="cart_icon">
                    <a href="{{ route('fe.cart.index') }}">
                        <img class="img-fluid" src="{{ asset('aStar/images/bag.png') }}">
                        <div class="cart_num">
                            @if (count($carts) != 0)
                                {{ count($carts)-1 }}
                            @else
                                0
                            @endif
                        </div>
                    </a>
                </div>
            </div>

        </div>

    </div>
</header>

<!-- Menu (For Mobile) -->

<div class="menu d-flex flex-column align-items-start justify-content-start menu_mm trans_400">
    <div class="menu_close_container"><div class="menu_close"><div></div><div></div></div></div>
    <div class="menu_top d-flex flex-row align-items-center justify-content-start">
        <!-- Logo -->
        <div class="sidebar_logo">
            <a href="{{ route('fe.index') }}">
                @if ($contact->images == "")
                    <div>a<span>star</span></div>
                @else
                    <img class="img-fluid" src="{{ asset($contact->images) }}">
                @endif
            </a>
        </div>
    </div>

    <br>

    <nav class="menu_nav">
        <ul class="menu_mm">
            <li class="menu_mm"><a href="{{ route('fe.index') }}">Trang chủ</a></li>
            @foreach($categories as $category)
                <li><a href="{{ route('fe.category.index', ['category_slug' => $category->slug]) }}">{{ $category->name }}</a></li>
            @endforeach
            <li>
                <a href="{{ route('fe.index.tracking_orders') }}">Tra cứu đơn hàng</a>
            </li>
            <li>
                <a href="{{ route('fe.index.about_us') }}">Về chúng tôi</a>
            </li>
            <li>
                <a href="{{ route('fe.index.contact_us') }}">Liên hệ với chúng tôi</a>
            </li>
        </ul>
    </nav>
    <div class="menu_extra">
        <div class="menu_social">
            <ul>
                <li><a href="#"><i class="fa fa-pinterest" aria-hidden="true"></i></a></li>
                <li><a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                <li><a href="#"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
                <li><a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
            </ul>
        </div>
    </div>
</div>

<!-- Sidebar -->
<div class="sidebar">
    <!-- Logo -->
    <div class="sidebar_logo">
        <a href="{{ route('fe.index') }}">
            @if ($contact->images == "")
                <div>a<span>star</span></div>
            @else
                <img class="img-fluid" src="{{ asset($contact->images) }}">
            @endif
        </a>
    </div>

    <!-- Sidebar Navigation -->
    <nav class="sidebar_nav">
        <ul>
            <li><a href="{{ route('fe.index') }}">Trang chủ<i class="fa fa-angle-right" aria-hidden="true"></i></a></li>
            @foreach($categories as $category)
                <li><a href="{{ route('fe.category.index', ['category_slug' => $category->slug]) }}">{{ $category->name }}<i class="fa fa-angle-right" aria-hidden="true"></i></a></li>
            @endforeach
            <li>
                <a href="{{ route('fe.index.tracking_orders') }}">Tra cứu đơn hàng<i class="fa fa-angle-right" aria-hidden="true"></i></a>
            </li>
            <li>
                <a href="{{ route('fe.index.about_us') }}">Về chúng tôi<i class="fa fa-angle-right" aria-hidden="true"></i></a>
            </li>
            <li>
                <a href="{{ route('fe.index.contact_us') }}">Liên hệ với chúng tôi<i class="fa fa-angle-right" aria-hidden="true"></i></a>
            </li>
        </ul>
    </nav>

    <!-- Cart -->
    <div class="cart d-flex flex-row align-items-center justify-content-start">
        <div class="cart_icon"><a href="{{ route('fe.cart.index') }}">
                <img src="{{ asset('aStar/images/bag.png') }}">
                <div class="cart_num">
                    @if (count($carts) == 0)
                        0
                    @else
                        {{ count($carts)-1 }}
                    @endif
                </div>
            </a></div>
        <div class="cart_price">
            @if (count($carts) == 0)

            @elseif (!isset($carts['information']['ship_fee']) && !isset($carts['information']['saleoff']))
                {{ number_format($carts['information']['totals'], 0, '', '.') }}vnđ
            @elseif (isset($carts['information']['ship_fee']) && !isset($carts['information']['saleoff']))
                {{ number_format($carts['information']['totals'] + $carts['information']['ship_fee'], 0, '', '.') }}vnđ
            @elseif(!isset($carts['information']['ship_fee']) && isset($carts['information']['saleoff']))
                {{ number_format($carts['information']['totals'] - $carts['information']['saleoff'], 0, '', '.') }}vnđ
            @else
                {{ number_format($carts['information']['totals'] + $carts['information']['ship_fee'] - $carts['information']['saleoff'], 0, '', '.') }}vnđ
            @endif
        </div>
    </div>
</div>
