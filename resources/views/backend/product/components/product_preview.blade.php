<link rel="stylesheet" type="text/css" href="{{ asset('aStar/styles/bootstrap-4.1.3/bootstrap.css') }}">
<link href="{{ asset('aStar/plugins/font-awesome-4.7.0/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="{{ asset('aStar/plugins/OwlCarousel2-2.2.1/owl.carousel.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('aStar/plugins/OwlCarousel2-2.2.1/owl.theme.default.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('aStar/plugins/OwlCarousel2-2.2.1/animate.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('aStar/styles/product_preview.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('aStar/styles/product_responsive.css') }}">

<div class="preview">
    <!-- Product Content -->
    <div class="section_container">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="product_content_container d-flex flex-lg-row flex-column align-items-start justify-content-start">
                        <div class="product_content order-lg-1 order-2">
                            <div class="product_content_inner">
                                @if ($total_images == 0)
                                    <span style="color: red">Thêm ảnh</span>
                                @else
                                    <?php $i = 1; ?>
                                    @if ($total_images <= 2)
                                        <div class="product_image_row d-flex flex-md-row flex-column align-items-md-end align-items-start justify-content-start">
                                            @foreach($images as $index=>$image)
                                                <div class="product_image_{{ $i}} product_image">
                                                    <img src="{{ $image }}">
                                                </div>
                                                <?php $i = $i + 1 ?>
                                            @endforeach
                                        </div>
                                    @elseif ($total_images == 3)
                                         <div class="product_image_row d-flex flex-md-row flex-column align-items-md-end align-items-start justify-content-start">
                                             @foreach($images as $index=>$image)
                                                 @if ($i < 3)
                                                     <div class="product_image_{{ $i }} product_image">
                                                         <img src="{{ $image }}">
                                                     </div>
                                                     <?php $i = $i + 1 ?>
                                                 @else
                                                     <?php $image_3 = $image ?>
                                                 @endif
                                             @endforeach
                                         </div>
                                        <div class="product_image_row">
                                            <div class="product_image_3 product_image">
                                                <img src="{{ $image_3 }}">
                                            </div>
                                        </div>
                                    @elseif ($total_images > 3)
                                         <div class="product_image_row d-flex flex-md-row flex-column align-items-md-end align-items-start justify-content-start">
                                             @foreach($images as $index=>$image)
                                                 @if ($i < 3)
                                                     <div class="product_image_{{ $i }} product_image">
                                                         <img src="{{ $image }}">
                                                     </div>
                                                     <?php $i = $i + 1 ?>
                                                 @elseif ($i == 3)
                                                     <?php $image_3 = $image ?>
                                                     <?php $i = $i + 1 ?>
                                                 @elseif ($i == 4)
                                                     <?php $image_4 = $image ?>
                                                     <?php $i = $i + 1 ?>
                                                 @else
                                                     <?php $image_5 = $image ?>
                                                 @endif
                                             @endforeach
                                         </div>
                                        <div class="product_image_row">
                                            <div class="product_image_3 product_image">
                                                <img src="{{ $image_3 }}">
                                            </div>
                                        </div>
                                        <div class="product_image_row d-flex flex-md-row flex-column align-items-start justify-content-start">
                                            <div class="product_image_4 product_image">
                                                <img src="{{ $image_4 }}">
                                            </div>
                                            @if (isset($image_5))
                                                <div class="product_image_5 product_image">
                                                    <img src="{{ $image_5 }}">
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                        <div class="product_sidebar order-lg-2 order-1">
                            <form action="#" id="product_form" class="product_form">
                                <div class="product_name">{!!   is_null($name) ? '<span style="color:red">Điền tên</span>' : $name !!}</div>
                                    @if (is_null($price))
                                        <div class="product_price">
                                            <span style="color:red">Điền giá</span>
                                        </div>
                                    @elseif ($sale_price != "")
                                        <div class="product_price" style="color:#fd006b;">
                                            {{ $sale_price }} vnđ
                                            <span>Giá cũ: {{ $price }} vnđ</span>
                                        </div>
                                    @endif

                                @if (is_null($variants))
                                @else
                                    @foreach($variants as $index=>$variant)
                                        <?php
                                            $colors_array[] = $variant['color_id'];
                                            $sizes_array[] = $variant['size_id'];
                                        ?>
                                    @endforeach
                                    <?php
                                    $colors = \Illuminate\Support\Facades\DB::table('attribute_values')->whereIn('id', $colors_array)->orderBy('id')->get();
                                    $sizes = Illuminate\Support\Facades\DB::table('attribute_values')->whereIn('id', $sizes_array)->orderBy('id')->get();
                                    ?>
                                @endif

                                <!-- Colors -->
                                <div class="product_size">
                                    <div class="product_size_title">Màu sắc</div>
                                    <div class="product_size_list">
                                        <ul>
                                            @if (!isset($colors))
                                                Thêm biến thể
                                            @else
                                                @foreach ($colors as $color)
                                                    <li class="size_available">
                                                        <input type="radio" id="radio_{{ $color->id }}" name="color" class="regular_radio radio_{{ $color->id }}">
                                                        <label for="radio_{{ $color->id }}">{{ $color->value }}</label>
                                                    </li>
                                                @endforeach
                                            @endif
                                        </ul>
                                    </div>
                                </div>

                                <!-- Sizes -->
                                <div class="product_size">
                                    <div class="product_size_title">Kích cỡ</div>
                                    <div class="product_size_list">
                                        <ul>
                                            @if (!isset($sizes))
                                                Thêm biến thể
                                            @else
                                                @foreach ($sizes as $size)
                                                    <li class="size_available">
                                                        <input type="radio" id="radio_{{ $size->id }}" name="size" class="regular_radio radio_{{ $size->id }}">
                                                        <label for="radio_{{ $size->id }}">{{ $size->value }}</label>
                                                    </li>
                                                @endforeach
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                                <button class="cart_button trans_200">Thêm vào giỏ hàng</button>
                            </form>
                            <div class="product_links">
                                <ul class="text-center">
                                    <li><a href="javascript:void(0)" onclick="$('#size_image_preview').modal('show')">Bảng kích cỡ</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('aStar/styles/bootstrap-4.1.3/popper.js') }}"></script>
<script src="{{ asset('aStar/styles/bootstrap-4.1.3/bootstrap.min.js') }}"></script>
<script src="{{ asset('aStar/plugins/greensock/TweenMax.min.js') }}"></script>
<script src="{{ asset('aStar/plugins/greensock/TimelineMax.min.js') }}"></script>
<script src="{{ asset('aStar/plugins/scrollmagic/ScrollMagic.min.js') }}"></script>
<script src="{{ asset('aStar/plugins/greensock/animation.gsap.min.js') }}"></script>
<script src="{{ asset('aStar/plugins/greensock/ScrollToPlugin.min.js') }}"></script>
<script src="{{ asset('aStar/plugins/OwlCarousel2-2.2.1/owl.carousel.js') }}"></script>
<script src="{{ asset('aStar/plugins/easing/easing.js') }}"></script>
<script src="{{ asset('aStar/plugins/parallax-js-master/parallax.min.js') }}"></script>
<script src="{{ asset('aStar/plugins/Isotope/isotope.pkgd.min.js') }}"></script>
<script src="{{ asset('aStar/plugins/Isotope/fitcolumns.js') }}"></script>
<script src="{{ asset('aStar/js/product.js') }}"></script>

