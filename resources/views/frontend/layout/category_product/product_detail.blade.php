<!-- Products -->
<div class="products" style="padding-bottom: 87px">

    <!-- Product Content -->
    <div class="section_container">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="product_content_container d-flex flex-lg-row flex-column align-items-start justify-content-start" style="padding-bottom: 50px">
                        <div class="product_content order-lg-1 order-2">
                            <div class="product_content_inner">
                                @for($i = 0; $i < count($product_images); $i++)
                                    @if ($i == 2)
                                        <div class="product_image_row">
                                            <div class="product_image_3 product_image">
                                                <img src="{{ asset($product_images[$i]->images) }}">
                                            </div>
                                        </div>
                                    @elseif ($i == 0)
                                        <div class="product_image_row d-flex flex-md-row flex-column align-items-md-end align-items-start justify-content-start">
                                            <div class="product_image_1 product_image">
                                                <img src="{{ asset($product_images[$i]->images) }}">
                                            </div>
                                            @if (isset($product_images[$i+1]))
                                                <div class="product_image_2 product_image">
                                                    <img src="{{ asset($product_images[$i+1]->images) }}">
                                                </div>
                                            @endif
                                        </div>
                                    @elseif ($i == 3)
                                        <div class="product_image_row d-flex flex-md-row flex-column align-items-start justify-content-start">
                                            <div class="product_image_4 product_image">
                                                <img src="{{ asset($product_images[$i]->images) }}">
                                            </div>
                                            @if (isset($product_images[$i+1]))
                                                <div class="product_image_5 product_image">
                                                    <img src="{{ asset($product_images[$i+1]->images) }}">
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                @endfor
                            </div>
                        </div>
                        <div class="product_sidebar order-lg-2 order-1">
                                <div class="product_name">{{ $product->name }}</div>
                                @if ($product->sale_price != "")
                                    <div class="product_price" style="color: #fd006b">
                                        {{ number_format($product->sale_price, 0, '', '.') }}vnđ
                                        <span>Giá cũ: {{ number_format($product->price, 0, '', '.')  }}vnđ</span>
                                    </div>
                                @else
                                    <div class="product_price">
                                        {{ number_format($product->price, 0, '', '.') }}vnđ
                                    </div>
                                @endif
                                {{-- Colors --}}
                                <div class="product_size">
                                    <div class="product_size_title">Màu sắc</div>
                                    <div class="product_size_list">
                                        <ul>
                                            @foreach($colors as $color)
                                                <li class="size_available">
                                                    <input onclick="getSizes({{ $color->id }})" type="radio" id="color_{{ $color->id }}" name="color" class="regular_radio" value="{{ $color->id }}">
                                                    <label for="color_{{ $color->id }}">{{ $color->value }}</label>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                {{-- Sizes --}}
                                <div class="product_size">
                                    <div class="product_size_title">Kích cỡ</div>
                                    <div class="product_size_list">
                                        <ul id="sizes_box">
                                            @foreach($sizes as $size)
                                                <li class="size_available">
                                                    <input onclick="checkColor({{ $size->id }})" type="radio" id="size_{{ $size->id }}" name="size" class="regular_radio" value="{{ $size->id }}">
                                                    <label for="size_{{ $size->id }}">{{ $size->value }}</label>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                {{-- Quantity --}}
                                <div class="product_size" id="quantity_box" style="display: none">
                                    <div class="product_size_title">Số lượng còn lại trong kho</div>
                                    <div class="product_size_list">
                                        <ul>
                                            <li class="size_available" style="pointer-events: none;" id="instock"></li>
                                        </ul>
                                    </div>
                                </div>
                                <div id="quantity_input" class="product_size" style="display: none">
                                    <div class="product_size_title">Nhập số lượng</div>
                                    <div class="product_size_list">
                                        <ul>
                                            <li>
                                                <div class="def-number-input number-input safari_only">
                                                    <button type="button" id="basic-example-decrease" onclick="this.parentNode.querySelector('input[type=number]').stepDown()" class="minus"></button>
                                                    <input min="1" class="quantity" name="quantity" value="1" type="number">
                                                    <button type="button" id="basic-example-add" onclick="this.parentNode.querySelector('input[type=number]').stepUp()" class="plus"></button>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <button class="cart_button trans_200" type="button" onclick="addProduct({{ $product->id }}, event)">Thêm vào giỏ hàng</button>
                                <div class="product_links">
                                    <ul class="text-center">
                                        <li><a href="javascript:void(0)" data-toggle="modal" data-target="#size_image">Bảng kích cỡ</a></li>
                                    </ul>
                                </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Rating --}}
            <div style="color: black">
                <div class="row">
                    <div class="col">
                        <hr style="border: solid 1px">
                        <div class="product_content_container d-flex flex-lg-row flex-column align-items-start justify-content-start" style="padding: 10px 0 30px 0">
                            <h1 style="font-weight: 500">Phiếu đánh giá sản phẩm <span style="font-size: 14px; color: red">(1)</span></h1>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6 col-xl-6 col-sm-6 col-md-6 col-lg-6 col-xxl-6 col-xs-6">
                        <div class="form-group">
                            <label for="name">Họ và tên <span style="color: red">(*)</span></label>
                            <div class="input-group mb-3">
                                <input placeholder="Họ và tên" type="text" id="name" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-xl-6 col-sm-6 col-md-6 col-lg-6 col-xxl-6 col-xs-6">
                        <div class="form-group">
                            <label for="phone_number">Số điện thoại <span style="color: red">(*)</span></label>
                            <div class="input-group mb-3">
                                <input placeholder="Số điện thoại mua hàng" type="text" pattern="[0]{1}[1-9]{1}[0-9]{8}" id="phone_number" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-xl-12 col-sm-12 col-md-12 col-lg-12 col-xxl-12 col-xs-12">
                        <div class="form-group">
                            <label for="comment">Đánh giá sản phẩm <span style="color: red">(*)</span></label>
                            <div class="input-group mb-3">
                                <textarea id="comment" placeholder="Đánh giá sản phẩm ..." class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-xl-12 col-sm-12 col-md-12 col-lg-12 col-xxl-12 col-xs-12">
                        <div class="form-group">
                            <label>Chấm điểm sản phẩm <span style="color: red">(*)</span></label>
                            <div class="input-group mb-3" style="color: gold">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="fa fa-star-o fa-2x" id="star_{{ $i }}" onclick="confirmStar({{ $i }})"></i>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-xl-12 col-sm-12 col-md-12 col-lg-12 col-xxl-12 col-xs-12">
                        <div class="form-group">
                            <button type="button" class="btn btn-primary" onclick="send_comment()">Gửi đánh giá</button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-xl-12 col-sm-12 col-md-12 col-lg-12 col-xxl-12 col-xs-12">
                        <div class="form-group">
                            <span style="color: red">(1): Bạn chỉ có thể đánh giá sản phẩm khi đơn hàng ở trạng thái đã giao thành công</span>
                        </div>
                    </div>
                </div>
                <hr style="border: solid 1px">
                <div class="row" style="padding: 10px 0 30px 0">
                    <div class="col">
                        <h1 style="font-weight: 500">Đánh giá của khách hàng</h1>
                    </div>
                </div>
                <div class="row" style="border: solid 1px; margin:0">
                    <div class="col-md-3 col-3 col-lg-3 col-sm-3 col-xl-3 col-xxl-3 col-xs-3 text-center" id="avg_ratings_box">
                        <?php
                            $avg_ratings = $avg_round = 0;
                            if (count(\App\Models\Rating::getRatingsByProductId($product->id)) != 0) {
                                $avg_ratings = \App\Models\Rating::getAvgRatingsByProductId($product->id);
                                $avg_round = round($avg_ratings, 1, PHP_ROUND_HALF_EVEN);
                                if (gettype($avg_ratings) != 'integer') {
                                    $str_avg_round = strval($avg_round);
                                    $str_avg_round_explode = explode('.', $str_avg_round);
                                    foreach ($str_avg_round_explode as $index=>$value) {
                                        if ($index == 0) {
                                            $so_nguyen = $value;
                                        }
                                        else {
                                            $so_sau_dau_phay = $value;
                                        }
                                    }
                                    if ($so_sau_dau_phay >= 5) {
                                        $so_nguyen = $so_nguyen + 1;
                                    }
                                }
                            }
                        ?>
                        <div class="form-group">
                            <h1>{{ $avg_round }} trên 5</h1>
                        </div>
                        <div class="form-group" style="color: gold">
                            @if (gettype($avg_ratings) == 'integer')
                                @for ($i = 1; $i <= $avg_ratings; $i++)
                                    <i class="fa fa-star fa-2x"></i>
                                @endfor
                                @for($i = $avg_ratings+1; $i <= 5; $i++)
                                    <i class="fa fa-star-o fa-2x"></i>
                                @endfor
                            @else
                                @for ($i = 1; $i <= $so_nguyen; $i++)
                                    @if ($so_sau_dau_phay >= 5 && $so_nguyen == $i)
                                        <i class="fa fa-star-half-o fa-2x"></i>
                                    @else
                                        <i class="fa fa-star fa-2x"></i>
                                    @endif
                                @endfor
                                @for ($i = $so_nguyen + 1; $i <= 5; $i++)
                                    <i class="fa fa-star-o fa-2x"></i>
                                @endfor
                            @endif
                        </div>
                    </div>
                    <div class="col-md-1 col-1 col-lg-1 col-sm-1 col-xl-1 col-xxl-1 col-xs-1">
                    </div>

                    <div class="col-md-8 col-8 col-lg-8 col-sm-8 col-xl-8 col-xxl-8 col-xs-8">
                        <div class="form-group" id="search_star_box" style="margin-top: 25px;">
                            <button type="button" class="btn btn-outline-primary" onclick="searchStar(0)">Tất cả ({{ count(\App\Models\Rating::getRatingsByProductId($product->id)) }})</button>
                            @for($i = 1; $i <= 5; $i++)
                                <button type="button" class="btn btn-outline-primary" onclick="searchStar({{ $i }})">{{ $i }} sao ({{ count(\App\Models\Rating::getRatingsByStar($product->id, $i)) }})</button>
                            @endfor
                        </div>
                    </div>
                </div>

                <div class="rating_box" style="margin-top: 30px;">

                    <div id="new_rating_box" style="margin-top: 10px;">
                    </div>

                    @foreach($ratings as $rating)
                        <div class="row rating_{{ $rating->rating }}">
                            <div class="col-12 col-lg-12 col-xs-12 col-md-12 col-sm-12 col-xxl-12 col-xl-12">
                                <table style="margin: 0 0 10px 0;width: 100%;border-bottom: solid 1px">
                                    <tr>
                                        <td style="width: 40px;/*border-right: hidden;*/">
                                            <img style="position: absolute; top: 5px; left: 20px" width="40px" height="40px" src="{{ asset('images/default_avatar.png') }}">
                                        </td>
                                        <td style="padding-left: 20px">
                                            <div class="form-group">
                                                <?php
                                                    $phone_number = $rating->phone_number;
                                                    $new_phone_number = (substr($phone_number, 0, 4));
                                                    $new_phone_number = $new_phone_number.'xxxxxx';
                                                ?>
                                                <h4 class="customer_name" style="margin-top: 5px">{{ $rating->name }} (SĐT mua hàng: {{ $new_phone_number }})</h4>
                                                @if ($rating->rating == 5)
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="fa fa-star" style="color: gold;"></i>
                                                    @endfor
                                                @elseif ($rating->rating == 1)
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if ($i == 1)
                                                            <i class="fa fa-star" style="color: gold;"></i>
                                                        @else
                                                            <i class="fa fa-star-o" style="color: gold;"></i>
                                                        @endif
                                                    @endfor
                                                @else
                                                    @for($i = 1; $i <= $rating->rating; $i++)
                                                        <i class="fa fa-star" style="color: gold;"></i>
                                                    @endfor
                                                    @for($i = $rating->rating+1; $i <= 5; $i++)
                                                        <i class="fa fa-star-o" style="color: gold;"></i>
                                                    @endfor
                                                @endif
                                                <p style="color: black; margin-bottom: 5px">{{ $rating->comment }}</p>
                                                <span style="color: #8f8f8f">{{ date("d/m/Y H:i:s", strtotime($rating->created_at)) }}</span>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    @endforeach

                </div>

            </div>

        </div>
    </div>
</div>
