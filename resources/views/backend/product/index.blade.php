@extends('backend.layout.page')

@section('title')
    Danh sách sản phẩm
@endsection

@section('css')

@endsection

@section('page-title')
    Quản lý sản phẩm
@endsection

@section('right-navbar')
    <li class="breadcrumb-item"><a href="{{ route('be.index') }}">Trang chủ</a></li>
    <li class="breadcrumb-item active">Quản lý sản phẩm</li>
@endsection

@section('searchbar')

@endsection

@section('content')
    <div class="row">
        <div class="col-12 col-lg-12 col-md-12 col-xl-12 col-sm-12">
            <div class="card card-primary" id="products_list">
                <div class="card-header">
                    <h4 class="card-title">Danh sách sản phẩm</h4>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <a class="btn btn-primary" href="{{ route('be.product.create') }}">Thêm sản phẩm</a>
                    </div>

                    <div class="form-group table-responsive">
                        <table class="text-center table table-hover table-bordered dtr-inline dataTable" id="products_table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Danh mục</th>
                                    <th>Sản phẩm</th>
                                    <th>Giá</th>
                                    <th>Giá khuyến mãi</th>
                                    <th>Điểm đánh giá của khách hàng</th>
                                    <th>Thời gian tạo</th>
                                    <th>Thời gian cập nhật lần cuối</th>
                                    <th>Chức năng</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                    <tr>
                                        <td>{{ $product->id }}</td>
                                        <td>{{ \App\Models\Category::getCategoryById($product->category_id)->name }}</td>
                                        <td>
                                            <?php
                                                $category = \App\Models\Category::getCategoryById($product->category_id);
                                                $category_slug = $category->slug;
                                                $category_dad_slug = \App\Models\Category::getCategoryById($category->parent_id)->slug;
                                            ?>
                                            <a title="Xem sản phẩm" target="_blank" href="{{ route('fe.product.detail', ['category_dad_slug' => $category_dad_slug, 'category_slug' => $category_slug, 'product_slug' => $product->slug]) }}">{{ $product->name }}</a>
                                        </td>
                                        <td>{{ number_format($product->price, 0, '', '.') }} vnđ</td>
                                        <td>
                                            @if ($product->sale_price != "")
                                                {{ number_format($product->sale_price, 0, '', '.') }} vnđ
                                            @else
                                                Không có
                                            @endif
                                        </td>
                                        <td>
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
                                                    <i class="fas fa-star"></i>
                                                @endfor
                                                @for($i = $avg_ratings+1; $i <= 5; $i++)
                                                    <i class="far fa-star"></i>
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
                                        </td>
                                        <td data-sort="{{ $product->created_at }}">{{ date('d-m-Y H:i:s', strtotime($product->created_at)) }}</td>
                                        <td data-sort="{{ $product->updated_at }}">{{ date('d-m-Y H:i:s', strtotime($product->updated_at)) }}</td>
                                        <td>
                                            <a title="Sửa sản phẩm" class="btn btn-primary" href="{{ route('be.product.edit', ['id' => $product->id]) }}"><i class="fas fa-edit"></i></a>
                                            <button title="Xóa sản phẩm" type="button" onclick="deleteProduct({{ $product->id }})" class="btn btn-danger"><i class="fas fa-trash"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                {{-- /.card-body --}}
            </div>
            {{-- /.card --}}
        </div>
    </div>
@endsection

@section('js')
    <script>
        function activelink() {
            $('.quan-ly-san-pham').addClass('menu-open');
            $('#quan-ly-san-pham').addClass('active');
            $('#danh-sach-san-pham').addClass('active');
        }

        $('#products_table').DataTable({
            "oLanguage" : {
                "sSearch" : 'Tìm kiếm',
                "sInfo" : 'Đang xem sản phẩm _START_ - _END_ trên tổng số _TOTAL_ sản phẩm',
                "sLengthMenu" : 'Hiển thị <select class="custom-select custom-select-sm form-control form-control-sm">' +
                    '<option value="10">10</option>' +
                    '<option value="25">25</option>' +
                    '<option value="50">50</option>' +
                    '</select> bản ghi',
                "sEmptyTable" : 'Không có dữ liệu',
                "sZeroRecords" : 'Không có dữ liệu',
                "sInfoEmpty" : "",
                "oPaginate" : {
                    'sNext' : '>>',
                    'sPrevious' : '<<'
                }
            }
        });

        function deleteProduct(product_id) {
            if (confirm('Bạn có chắc chắn muốn xóa sản phẩm này không?')) {
                $.ajax({
                    headers: {
                       'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'delete',
                    url: '{{ route('be.product.delete') }}',
                    data: {product_id : product_id},
                    dataType: 'json',
                    statusCode: {
                        500: function (response) {
                            console.log(JSON.parse(response.responseText));
                        },

                        200: function (response) {
                            $('body').html(response.view);
                            alert(response.message);
                        }
                    }
                });
            }
        }
    </script>
@endsection
