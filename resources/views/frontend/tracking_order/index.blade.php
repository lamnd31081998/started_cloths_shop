@extends('frontend.layout.page')

@section('title')
    Tra cứu đơn hàng
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('aStar/styles/categories.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('aStar/styles/categories_responsive.css') }}">
    <style>
        .form-control {
            color:black;
        }

        @media (min-width: 1200px) {
            .modal-content {
                width: 1000px;
                margin-left: -50%;
            }
        }

        .table-responsive > .table-bordered {
            border: 1px solid #dee2e6;;
        }
    </style>
@endsection

@section('home')
    @include('frontend.layout.tracking_order.home')
@endsection

@section('products')
    <div class="products">

        <!-- Product Content -->
        <div class="section_container" style="margin-top: 100px">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <h1 class="text-center" style="color: black">Tra cứu đơn hàng</h1>
                        <input style="margin-bottom: 10px; outline: none" class="form-control" type="search" id="phone_number" onkeyup="searchOrder()" placeholder="Tìm kiếm theo số điện thoại">
                        <div class="table-responsive">
                            <table class="text-center table table-hover table-bordered dataTable dtr-inline">
                                <thead>
                                    <tr>
                                        <th>Mã đơn hàng</th>
                                        <th>Tên khách hàng</th>
                                        <th>Số điện thoại</th>
                                        <th>Địa chỉ nhận hàng</th>
                                        <th>Mã giảm giá</th>
                                        <th>Giá trị đơn hàng</th>
                                        <th>Hình thức giao hàng</th>
                                        <th>Hình thức thanh toán</th>
                                        <th>Thời gian tạo đơn</th>
                                        <th>Lần cập nhật cuối</th>
                                        <th>Trạng thái đơn hàng</th>
                                        <th style="width: 105px">Chức năng</th>
                                    </tr>
                                </thead>
                                <tbody id="tracking_orders_body">
                                    <tr>
                                        <td colspan="15">Không có dữ liệu</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('optional')
    <div id="modal_search"></div>
@endsection

@section('js')
    <script src="{{ asset('aStar/js/categories.js') }}"></script>
    <script src="{{ asset('aStar/js/product.js') }}"></script>
    <!-- DataTables -->
    <script src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>

    <script>
        function getlinks() {
            $('.home_title').html('Tra cứu đơn hàng');
            $('#breadcrumbs').html(
                '<li><a href="{{ route('fe.index') }}">Trang chủ</a></li>' +
                '<li class="active">Tra cứu đơn hàng</li>'
            );
        }

        function searchOrder() {
            let phone_number = $('#phone_number').val();
            $.ajax({
                headers: {
                   'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                },
                method: 'get',
                url: '{{ route('fe.index.search_orders') }}',
                data: { phone_number : phone_number },
                dataType: 'json',
                statusCode: {
                    500: function (response) {
                        console.log(JSON.parse(response.responseText));
                    },

                    200: function (response) {
                        $('#tracking_orders_body').html(response.view);
                        $('#modal_search').html(response.view_modal);
                    }
                }
            });
        }
    </script>
@endsection
