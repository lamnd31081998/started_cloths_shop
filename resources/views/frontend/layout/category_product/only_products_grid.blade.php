<div class="products_container grid">
    @if (count($products) == 0)
        <h1 class="text-center">Đang cập nhật ...</h1>
    @else
        @foreach($products as $index=>$product)
            <?php
            $product_attribute_values = \App\Models\Product_Attribute_value::getProductAttributevalueByProductId($product->id);
            $i = 0;
            foreach ($product_attribute_values as $product_attribute_value) {
                if ($product_attribute_value->quantity == 0) {
                    $i = $i + 1;
                }
            }
            ?>
            @if ($i == count($product_attribute_values))
                <div class="product grid-item">
            @elseif ($product->sale_price != "")
                <div class="product grid-item sale">
            @elseif ($index <= 5)
                <div class="product grid-item new">
            @else
                <div class="product grid-item">
            @endif
                    <div class="product_image">
                        <a href="{{ route('fe.product.detail', ['category_dad_slug' => \App\Models\Category::getCategoryById(\App\Models\Category::getCategoryById($product->category_id)->parent_id)->slug, 'category_slug' => \App\Models\Category::getCategoryById($product->category_id)->slug, 'product_slug' => $product->slug]) }}">
                            <img class="preview_product_image" src="{{ asset($product_images[$index]) }}">
                        </a>
                        @if ($i == count($product_attribute_values))
                            <div class="product_tag" style="width: 100px; background-color: black">Hết hàng</div>
                        @elseif ($product->sale_price != "")
                            <div class="product_tag" style="width: 100px">Giảm giá</div>
                        @elseif ($index <= 5)
                            <div class="product_tag" style="width: 100px;background: #bbe432;">Mới về</div>
                        @else
                        @endif
                    </div>
                    <div class="product_content text-center">
                        <div class="product_title">
                            <a style="color: black">{{ $product->name }}</a>
                            <div style="color: gold; margin-top: 20px">
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
                        <div class="product_price">
                            @if ($product->sale_price != "")
                                {{ number_format($product->sale_price, 0, '', '.') }}vnđ
                                <span>Giá cũ: {{ number_format($product->price, 0, '', '.') }}vnđ</span>
                            @else
                                {{ number_format($product->price, 0, '', '.') }}vnđ
                            @endif
                        </div>
                        <div class="product_button ml-auto mr-auto trans_200"><a href="{{ route('fe.product.detail', ['category_dad_slug' => \App\Models\Category::getCategoryById(\App\Models\Category::getCategoryById($product->category_id)->parent_id)->slug, 'category_slug' => \App\Models\Category::getCategoryById($product->category_id)->slug, 'product_slug' => $product->slug]) }}">Xem chi tiết</a></div>
                    </div>
                </div>
        @endforeach
    @endif
</div>
