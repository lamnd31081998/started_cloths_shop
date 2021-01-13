@extends('backend.layout.page')

@section('title')
    Trang quản trị
@endsection

@section('css')
    <style>
        @media (min-width: 1280px) {
            .modal-content {
                width: 1000px;
                margin-left: -50%;
                margin-top: 30%;
            }
        }
    </style>
@endsection

@section('page-title')
    Trang chủ
@endsection

@section('right-navbar')
    <li class="breadcrumb-item active">Trang chủ</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $user_quantities }}</h3>

                    <p>Số lượng admin</p>
                </div>
                <div class="icon">
                    <i class="ion ion-person-add"></i>
                </div>
                <a target="_blank" href="{{ route('be.admin.index') }}" class="small-box-footer">Xem chi tiết <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ count(DB::table('products')->where('status', '!=', 0)->get()) }}</h3>

                    <p>Số lượng sản phẩm</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <a target="_blank" href="{{ route('be.product.index') }}" class="small-box-footer">Xem chi tiết <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $quantities }}</h3>

                    <p>Số lượng đơn hàng</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
                <a target="_blank" href="{{ route('be.order.index') }}" class="small-box-footer">Xem chi tiết <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
    </div>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header border-0">
                    <div class="d-flex justify-content-between">
                        <h3 class="card-title">Doanh thu của năm</h3>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex">
                        <p class="d-flex flex-column">
                            <span>Tổng doanh thu (Không bao gồm đơn hàng bị hủy)</span>
                            <span class="text-bold text-lg">{{ number_format($totals, 0, '', '.') }}vnđ</span>
                            <span>Biểu đồ doanh thu theo tháng</span>
                        </p>
                        <p class="ml-auto d-flex flex-column text-right">
                            @if ($percents >= 0)
                                <span class="text-success">
                                  <i class="fas fa-arrow-up">{{ $percents }}%</i>
                                </span>
                            @else
                                <span class="text-danger">
                                  <i class="fas fa-arrow-down" style="margin-left: 10px">{{ $percents }}%</i>
                                </span>
                            @endif
                                <span class="text-muted">So với tháng trước</span>
                        </p>
                    </div>
                    <!-- /.d-flex -->

                    <div class="position-relative mb-4">
                        <canvas id="sales-chart" height="200"></canvas>
                    </div>
                </div>
            </div>
            <!-- /.card -->

            <div class="card">
                <div class="card-header border-0">
                    <div class="d-flex justify-content-between">
                        <h3 class="card-title">Đơn hàng trong năm</h3>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex">
                        <p class="d-flex flex-column">
                            <span>Tổng số đơn hàng</span>
                            <span class="text-bold text-lg">{{ $quantities }}</span>
                            <span>Biểu đồ đơn hàng theo tháng</span>
                        </p>
                        <p class="ml-auto d-flex flex-column text-right">
                            @if ($quantity_percents >= 0)
                                <span class="text-success">
                                  <i class="fas fa-arrow-up">{{ $quantity_percents }}%</i>
                                </span>
                            @else
                                <span class="text-danger">
                                  <i class="fas fa-arrow-down" style="margin-left: 10px">{{ $quantity_percents }}%</i>
                                </span>
                            @endif
                            <span class="text-muted">So với tháng trước</span>
                        </p>
                    </div>
                    <!-- /.d-flex -->

                    <div class="position-relative mb-4">
                        <canvas id="quantity-sales-chart" height="200"></canvas>
                    </div>
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 col-xs-12 col-xl-12 col-lg-12 col-sm-12 col-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Đơn hàng mới nhất</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive">
                    <table class="table table-hover table-bordered dataTable dtr-inline text-center">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tên khách hàng</th>
                                <th>Số điện thoại</th>
                                <th>Địa chỉ nhận hàng</th>
                                <th>Mã giảm giá</th>
                                <th>Hình thức giao hàng</th>
                                <th>Phương thức thanh toán</th>
                                <th>Giá trị đơn hàng</th>
                                <th>Trạng thái đơn hàng</th>
                                <th>Thời gian tạo đơn hàng</th>
                                <th style="width: 140px">Chức năng</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($orders) == 0)
                                <tr>
                                    <td colspan="15">Không có dữ liệu</td>
                                </tr>
                            @else
                                @foreach($orders as $order)
                                    <tr>
                                        <td>{{ $order->id }}</td>
                                        <td>{{ $order->name }}</td>
                                        <td>{{ $order->phone_number }}</td>
                                        <td>{{ $order->address }}, {{ \App\Models\Xa::getXaById($order->xa_id)->name }}, {{ \App\Models\Huyen::getHuyenById($order->huyen_id)->name }}, {{ \App\Models\Tinh::getTinhById($order->tinh_id)->name }}</td>
                                        <td>
                                            @if ($order->promotion != "")
                                                {{ $order->promotion }}
                                            @else
                                                Không sử dụng
                                            @endif
                                        </td>
                                        <td>{{ \App\Models\Shipfee::getShipfeeById($order->ship_id)->name }}</td>
                                        <td>
                                            @switch($order->payment_method)
                                                @case(1)
                                                    Thanh toán bằng tiền mặt
                                                    @break
                                                @case(2)
                                                    Thanh toán trực tuyến
                                                    @break
                                            @endswitch
                                        </td>
                                        <td>{{ number_format($order->totals, 0, '', '.') }}vnđ</td>
                                        <td>
                                            @switch($order->status)
                                                @case(0)
                                                    ĐƠn hàng bị hủy
                                                    @break
                                                @case(1)
                                                    Đơn hàng chưa được xác nhận
                                                    @break
                                                @case(2)
                                                    Đơn hàng đã được xác nhận
                                                    @break
                                                @case(3)
                                                    Đơn hàng đang được vận chuyển
                                                    @break
                                                @case(4)
                                                    ĐƠn hàng đã giao thành công
                                                    @break
                                                @case(5)
                                                    Khách hàng từ chối nhận hàng
                                                    @break
                                                @case(6)
                                                    Đơn hàng đang được chuyển hoàn
                                                    @break
                                                @case(7)
                                                    Đơn hàng đã được chuyển về kho
                                                    @break
                                                @case(8)
                                                    Đơn hàng đã được hoàn tiền
                                                    @break
                                            @endswitch
                                        </td>
                                        <td>{{ date('d-m-Y H:i:s', strtotime($order->created_at)) }}</td>
                                        <td><button title="Xem chi tiết" data-toggle="modal" data-target="#order_detail_{{ $order->id }}" type="button" onclick="getOrderDetail({{ $order->id }})" class="btn btn-primary"><i class="fas fa-info-circle"></i></button></td>
                                    </tr>

                                    <div class="modal fade" id="order_detail_{{ $order->id }}" role="dialog">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Chi tiết đơn hàng {{ $order->id }}</h4>
                                                    <button type="button" data-dismiss="modal" class="close">&times;</button>
                                                </div>
                                                <div class="modal-body table-responsive" id="order_detail_table_{{ $order->id }}">

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" data-dismiss="modal" class="btn btn-danger">Đóng</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <a style="float: right" href="{{ route('be.order.index') }}" class="btn btn-primary">Xem thêm</a>
                </div>
            </div>
        </div>
        <!-- /.col-md-6 -->
    </div>

    <div class="row">
        <div class="col-md-12 col-xs-12 col-xl-12 col-lg-12 col-sm-12 col-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Sản phẩm mới nhất</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive">
                    <table class="table table-hover table-bordered dataTable dtr-inline text-center">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Danh mục</th>
                                <th>Sản phẩm</th>
                                <th>Giá</th>
                                <th>Giá khuyến mãi</th>
                                <th>Thời gian tạo</th>
                                <th>Điểm đánh giá của khách hàng</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($products) == 0)
                                <tr>
                                    <td colspan="6">Không có dữ liệu</td>
                                </tr>
                            @else
                                @foreach($products as $product)
                                    <tr>
                                        <td>{{ $product->id }}</td>
                                        <td>{{ \App\Models\Category::getCategoryById($product->category_id)->name }}</td>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ number_format($product->price, 0, '', ',') }} vnđ</td>
                                        @if ($product->sale_price != "")
                                            <td>{{ number_format($product->sale_price, 0, '', ',') }} vnđ</td>
                                        @else
                                            <td>Không có</td>
                                        @endif
                                        <td>{{ date('d-m-Y H:i:s', strtotime($product->created_at)) }}</td>
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
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <a style="float: right" class="btn btn-primary" href="{{ route('be.product.index') }}">Xem thêm</a>
                </div>
            </div>
        </div>
        <!-- /.col-md-6 -->
    </div>
@endsection

@section('js')
    <script src="{{ asset('adminlte/plugins/chart.js/Chart.min.js') }}"></script>
    <script>
        function activelink() {
            $('#trang-chu').addClass('active');

            let $salesChart = $('#sales-chart')
            let salesChart = new Chart($salesChart, {
                data   : {
                    labels  : ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'],
                    datasets: [
                        {
                            backgroundColor: '#007bff',
                            borderColor    : '#007bff',
                            data: [],
                            type: 'bar'
                        },
                    ]
                },
                options: {
                    maintainAspectRatio: false,
                    legend             : {
                        display: false
                    },
                    tooltips: {
                        callbacks: {
                            label: function(tooltipItem, data) {
                                return "Doanh thu: " + Number(tooltipItem.yLabel).toFixed(0).replace(/./g, function (c, i, a) {
                                    return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "," + c : c;
                                }) + 'vnđ';
                            }
                        }
                    },
                    scales :{
                        yAxes: [{
                            display: true,
                            ticks: {
                                display: true,
                                beginAtZero: true,
                                stepSize: 5000000,
                                callback: function (label, index, labels) {
                                    return Number(label).toFixed(0).replace(/./g, function (c, i, a) {
                                        return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "," + c : c;
                                    }) + 'vnđ';
                                }
                            }
                        }],
                        xAxes: [{
                            display  : true,
                            gridLines: {
                                display: false
                            },
                            offset: true,
                        }]
                    }
                }
            })

            let $quantitysalesChart = $('#quantity-sales-chart')
            let quantitysalesChart = new Chart($quantitysalesChart, {
                data   : {
                    labels  : ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'],
                    datasets: [
                        {
                            label: 'Tổng số đơn hàng thành công',
                            backgroundColor: '#007bff',
                            borderColor    : '#007bff',
                            data: [],
                            type: 'bar'
                        },
                        {
                            label: 'Tổng số đơn hàng thất bại',
                            backgroundColor: 'red',
                            borderColor: 'red',
                            data: [],
                            type: 'bar'
                        }
                    ]
                },
                options: {
                    maintainAspectRatio: false,
                    legend: {
                        display: true
                    },
                    scales :{
                        yAxes: [{
                            display: true,
                            /*gridLines: {
                                display      : true,
                                lineWidth    : '10px',
                                color        : 'rgba(0, 0, 0, .2)',
                                zeroLineColor: 'transparent',
                            },*/
                            ticks: {
                                display: true,
                                beginAtZero: true,
                                stepSize: 10,
                            }
                        }],
                        xAxes: [{
                            display  : true,
                            gridLines: {
                                display: false
                            },
                            offset: true,
                        }]
                    }
                }
            })

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN' : $('meta[name="csrf-token]').attr('content')
                },
                method: 'get',
                url: '{{ route('be.index.get_sales_data') }}',
                data: {},
                dataType: 'json',
                statusCode: {
                    500: function (response) {
                        console.log(JSON.parse(response.responseText));
                    },
                    200: function (response) {
                        var sales_data = response.sales_data;
                        var total_sales_data = response.total_sales_data;
                        var total_fails_data = response.total_fails_data;
                        sales_data.map((item, index) => {
                            salesChart.data.datasets[0].data.push(item);
                            salesChart.update();
                        })

                        total_sales_data.map((item, index) => {
                            quantitysalesChart.data.datasets[0].data.push(item);
                        })
                        total_fails_data.map((item, index) => {
                            quantitysalesChart.data.datasets[1].data.push(item);
                        })
                        quantitysalesChart.update();
                    }
                }
            });
        }

        function getOrderDetail(id) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                },
                method: 'get',
                url: '{{ route('be.order.get_order_detail') }}',
                data: { order_id : id },
                dataType: 'json',
                statusCode: {
                    500: function (response) {
                        console.log(JSON.parse(response.responseText));
                    },
                    200: function (response) {
                        $('#order_detail_table_'+id).html(response.view);
                    }
                }
            });
        }
    </script>
@endsection
