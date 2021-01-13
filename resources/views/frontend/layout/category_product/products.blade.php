<!-- Products -->

<div class="products">

    <!-- Sorting & Filtering -->
    <div class="products_bar">
        <div class="section_container">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="products_bar_content d-flex flex-column flex-xxl-row align-items-start align-items-xxl-center justify-content-start">
                            <div class="product_categories">
                                <ul class="d-flex flex-row align-items-start justify-content-start flex-wrap">
                                    <li id="{{ $category_dad->slug }}" class="menu_slug active"><a href="javascript:void(0)" onclick="getProducts({{ $category_dad->id }})">{{ $category_dad->name }}</a></li>
                                    @foreach($category_children as $category_child)
                                        <li class="menu_slug" id="{{ \App\Models\Category::getCategoryById($category_child)->slug }}"><a href="javascript:void(0)" onclick="getProducts({{ $category_child }})">{{ \App\Models\Category::getCategoryById($category_child)->name }}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="products_bar_side ml-xxl-auto d-flex flex-row align-items-center justify-content-start">
                                <div class="products_dropdown product_dropdown_sorting" style="margin-right: 0">
                                    <div class="isotope_sorting_text"><span>Sắp xếp</span><i class="fa fa-caret-down" aria-hidden="true"></i></div>
                                    <ul>
                                        <li class="item_sorting_btn" data-isotope-option='{ "sortBy": "original-order" ,"sortAscending": true }'>Mặc định</li>
                                        <li class="item_sorting_btn" data-isotope-option='{ "sortBy": "price", "sortAscending": true }'>Giá Thấp-Cao</li>
                                        <li class="item_sorting_btn" data-isotope-option='{ "sortBy": "price", "sortAscending": false }'>Giá Cao-Thấp</li>
                                        <li class="item_sorting_btn" data-isotope-option='{ "sortBy": "name", "sortAscending": true }'>Tên A-Z</li>
                                        <li class="item_sorting_btn" data-isotope-option='{ "sortBy": "name", "sortAscending": false }'>Tên Z-A</li>
                                    </ul>
                                </div>
                                <div class="products_dropdown text-right product_dropdown_filter" style="width: 100px">
                                    <div class="isotope_filter_text"><span>Bộ lọc</span><i class="fa fa-caret-down" aria-hidden="true"></i></div>
                                    <ul>
                                        <li class="item_filter_btn" data-filter="*">Tất cả</li>
                                        <li class="item_filter_btn" data-filter=".new">Mới về</li>
                                        <li class="item_filter_btn" data-filter=".sale">Giảm giá</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <input style="margin-top: -15px;margin-bottom: 10px;" class="form-control" type="search" id="keyword" placeholder="Tìm kiếm theo tên sản phẩm" onkeyup="searchProduct()">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="section_container">
        <div class="container">
            <div class="row">
                <div class="col" id="products_box">
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
                </div>
            </div>
        </div>
    </div>
</div>
