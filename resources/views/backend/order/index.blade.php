@extends('backend.layout.page')

@section('title')
    Danh sách đơn hàng
@endsection

@section('css')
    <style>
        .form-control {
            width: unset;
        }

        @media (min-width: 1280px) {
            .modal-content {
                width: 1000px;
                margin-left: -50%;
                margin-top: 30%;
            }
        }

        #function_box {
            width: 130px;
        }
    </style>
@endsection

@section('page-title')
    Quản lý đơn hàng
@endsection

@section('right-navbar')
    <li class="breadcrumb-item"><a href="{{ route('be.index') }}">Trang chủ</a></li>
    <li class="breadcrumb-item active">Quản lý đơn hàng</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12 col-lg-12 col-md-12 col-sm-12 col-xl-12 col-xs-12 col-xxl-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h4 class="card-title">Danh sách đơn hàng</h4>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <a href="{{ route('be.order.create') }}" class="btn btn-primary">Thêm đơn hàng</a>
                    </div>

                    <div class="form-group table-responsive">
                        <table class="text-center table table-hover table-bordered dataTable dtr-inline" id="orders_table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tên khách hàng</th>
                                    <th>Số điện thoại</th>
                                    <th>Địa chỉ nhận hàng</th>
                                    <th>Mã giảm giá</th>
                                    <th>Giá trị đơn hàng</th>
                                    <th>Hình thức giao hàng</th>
                                    <th>Phương thức thanh toán</th>
                                    <th>Ghi chú đơn hàng</th>
                                    <th>Trạng thái đơn hàng</th>
                                    <th>Thời gian tạo đơn hàng</th>
                                    <th>Thời gian cập nhật cuối</th>
                                    <th id="function_box">Chức năng</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                    <tr>
                                        <td>{{ $order->id }}</td>
                                        <td>{{ $order->name }}</td>
                                        <td>{{ $order->phone_number }}</td>
                                        <td>{{ $order->address }}, {{ \App\Models\Xa::getXaById($order->xa_id)->name }}, {{ \App\Models\Huyen::getHuyenById($order->huyen_id)->name }}, {{ \App\Models\Tinh::getTinhById($order->tinh_id)->name }}</td>
                                        <td>
                                            @if (isset($order->promotion))
                                                {{ $order->promotion }}
                                            @else
                                                Không sử dụng
                                            @endif
                                        </td>
                                        <td>{{ number_format($order->totals, 0, '', '.') }}vnđ</td>
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
                                        <td>
                                            @if (!empty($order->note))
                                            {{ $order->note }}
                                            @else
                                                Không có
                                            @endif
                                        </td>
                                        <td>
                                            @if ($order->status == 1)
                                                <select class="form-control" id="status_{{ $order->id }}" onchange="updateStatusOrder({{ $order->id }})">
                                                    <option value="1" selected>Đơn hàng chưa được xác nhận</option>
                                                    <option value="2">Đơn hàng đã được xác nhận</option>
                                                    <option value="3">Đơn hàng đang được vận chuyển</option>
                                                    <option value="4">Đơn hàng đã giao thành công</option>
                                                    <option value="5">Khách hàng từ chối nhận đơn hàng</option>
                                                    <option value="6">Đơn hàng đang được trả lại</option>
                                                    <option value="7">Đơn hàng đã được chuyển lại</option>
                                                </select>
                                            @elseif ($order->status == 2)
                                                <select class="form-control" id="status_{{ $order->id }}" onchange="updateStatusOrder({{ $order->id }})">
                                                    <option value="2" selected>Đơn hàng đã được xác nhận</option>
                                                    <option value="3">Đơn hàng đang được vận chuyển</option>
                                                    <option value="4">Đơn hàng đã giao thành công</option>
                                                    <option value="5">Khách hàng từ chối nhận đơn hàng</option>
                                                    <option value="6">Đơn hàng đang được trả lại</option>
                                                    <option value="7">Đơn hàng đã được chuyển lại</option>
                                                </select>
                                            @elseif ($order->status == 3)
                                                <select class="form-control" id="status_{{ $order->id }}" onchange="updateStatusOrder({{ $order->id }})">
                                                    <option value="3" selected>Đơn hàng đang được vận chuyển</option>
                                                    <option value="4">Đơn hàng đã giao thành công</option>
                                                    <option value="5">Khách hàng từ chối nhận đơn hàng</option>
                                                    <option value="6">Đơn hàng đang được trả lại</option>
                                                    <option value="7">Đơn hàng đã được chuyển lại</option>
                                                </select>
                                            @elseif ($order->status == 4)
                                                <select class="form-control" id="status_{{ $order->id }}" onchange="updateStatusOrder({{ $order->id }})" style="width: 100%">
                                                    <option value="4" selected>Đơn hàng đã giao thành công</option>
                                                    <option value="6">Đơn hàng đang được trả lại</option>
                                                    <option value="7">Đơn hàng đã được chuyển lại</option>
                                                </select>
                                            @elseif ($order->status == 5)
                                                <select class="form-control" id="status_{{ $order->id }}" onchange="updateStatusOrder({{ $order->id }})">
                                                    <option value="3">Đơn hàng đang được vận chuyển</option>
                                                    <option value="4">Đơn hàng đã giao thành công</option>
                                                    <option value="5" selected>Khách hàng từ chối nhận đơn hàng</option>
                                                    <option value="6">Đơn hàng đang được trả lại</option>
                                                    <option value="7">Đơn hàng đã được chuyển lại</option>
                                                </select>
                                            @elseif ($order->status == 6)
                                                <select class="form-control" id="status_{{ $order->id }}" onchange="updateStatusOrder({{ $order->id }})" style="width: 100%;">
                                                    <option value="6" selected>Đơn hàng đang được trả lại</option>
                                                    <option value="7">Đơn hàng đã được chuyển lại</option>
                                                </select>
                                            @elseif ($order->status == 0)
                                                Đơn hàng đã bị hủy
                                            @elseif ($order->status == 7)
                                                Đơn hàng đã được chuyển lại
                                            @elseif ($order->status == 8)
                                                Đơn hàng đã được hoàn tiền
                                            @endif
                                        </td>
                                        <td data-sort="{{ $order->created_at }}">{{ date('d-m-Y H:i:s', strtotime($order->created_at)) }}</td>
                                        <td data-sort="{{ $order->updated_at }}">{{ date('d-m-Y H:i:s', strtotime($order->updated_at)) }}</td>
                                        <td>
                                            @if ($order->payment_method == 2 && $order->status != 8)
                                                <button title="Xem chi tiết" class="btn btn-primary" type="button" onclick="getOrderDetail({{ $order->id }})" data-target="#order_detail_{{ $order->id }}" data-toggle="modal"><i class="fas fa-info-circle"></i></button>
                                                <a title="Xem chi tiết giao dịch tại vnpay" class="btn btn-info" target="_blank" href="https://sandbox.vnpayment.vn/merchantv2/Transaction/PaymentDetail/{{ $order->vnp_TransactionNo }}.htm"><i class="fas fa-money-bill-alt"></i></a>
                                                <button title="Hoàn tiền" class="btn btn-danger" onclick="vnpay_refund({{ $order->id }})" type="button"><i class="fas fa-undo"></i></button>
                                            @elseif ($order->payment_method == 2 && $order->status == 8)
                                                <button title="Xem chi tiết" class="btn btn-primary" type="button" onclick="getOrderDetail({{ $order->id }})" data-target="#order_detail_{{ $order->id }}" data-toggle="modal"><i class="fas fa-info-circle"></i></button>
                                                <a title="Xem chi tiết giao dịch tại vnpay" class="btn btn-info" target="_blank" href="https://sandbox.vnpayment.vn/merchantv2/Transaction/PaymentDetail/{{ $order->vnp_TransactionNo }}.htm"><i class="fas fa-money-bill-alt"></i></a>
                                            @elseif ($order->payment_method == 1 && $order->promotion != null)
                                                <button title="Xem chi tiết" class="btn btn-primary" type="button" onclick="getOrderDetail({{ $order->id }})" data-target="#order_detail_{{ $order->id }}" data-toggle="modal"><i class="fas fa-info-circle"></i></button>
                                                <button title="Hủy đơn hàng" class="btn btn-danger" type="button" onclick="cancelOrder({{ $order->id }})"><i class="fas fa-trash"></i></button>
                                            @elseif ($order->status == 1 || $order->status == 2)
                                                <button title="Xem chi tiết" class="btn btn-primary" type="button" onclick="getOrderDetail({{ $order->id }})" data-target="#order_detail_{{ $order->id }}" data-toggle="modal"><i class="fas fa-info-circle"></i></button>
                                                <a title="Sửa đơn hàng" href="{{ route('be.order.edit', ['id' => $order->id]) }}" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                                                <button title="Hủy đơn hàng" class="btn btn-danger" type="button" onclick="cancelOrder({{ $order->id }})"><i class="fas fa-trash"></i></button>
                                            @else
                                                <button class="btn btn-primary" type="button" onclick="getOrderDetail({{ $order->id }})" data-target="#order_detail_{{ $order->id }}" data-toggle="modal"><i class="fas fa-info-circle"></i></button>
                                            @endif
                                        </td>
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
            $('.quan-ly-don-hang').addClass('menu-open');
            $('#quan-ly-don-hang').addClass('active');
            $('#danh-sach-don-hang').addClass('active');
            $('#orders_table').DataTable({
                "order": [[ 10, "desc" ]],
                "oLanguage" : {
                    "sSearch" : 'Tìm kiếm',
                    "sInfo" : 'Đang xem đơn hàng _START_ - _END_  trên tổng số _TOTAL_ đơn hàng',
                    "sLengthMenu" : 'Hiển thị <select class="custom-select custom-select-sm form-control form-control-sm">' +
                        '<option value="10">10</option>' +
                        '<option value="25">25</option>' +
                        '<option value="50">50</option>' +
                        '</select> bản ghi',
                    "sEmptyTable" : "Không có dữ liệu",
                    "sZeroRecords" : "Không có dữ liệu",
                    "sInfoEmpty" : "",
                    "oPaginate" : {
                        "sNext" : ">>",
                        "sPrevious" : "<<"
                    },
                },
            });
        }

        function updateStatusOrder(id) {
            $.ajax({
                headers: {
                   'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                },
                method: 'put',
                url: '{{ route('be.order.update_status') }}',
                data: { order_id : id, status : $('#status_'+id).val() },
                dataType: 'json',
                statusCode: {
                    500: function (response) {
                        console.log(JSON.parse(response.responseText));
                    },

                    200: function (response) {
                        alert(response.message);
                        $('body').html(response.view);
                        activelink();
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

        function cancelOrder(id) {
            if (confirm('Bạn có chắc chắn muốn hủy đơn hàng này không?')) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'put',
                    url: '{{ route('be.order.cancel') }}',
                    data: { order_id : id },
                    dataType: 'json',
                    statusCode: {
                        500: function (response) {
                            console.log(JSON.parse(response.responseText));
                        },
                        200: function (response) {
                            alert(response.message);
                            $('body').html(response.view);
                            activelink();
                        }
                    }
                });
            }
        }

        function vnpay_refund(id) {
            if (confirm('Bạn có chắc chắn muốn hủy và hoàn tiền đơn hàng này không?')) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'put',
                    url: '{{ route('be.order.vnpay_refund') }}',
                    data: { order_id : id },
                    dataType: 'json',
                    statusCode: {
                        500: function (response) {
                            console.log(JSON.parse(response.responseText));
                        },

                        200: function (response) {
                            let data = response.data.split('&');
                            let check = 0;
                            let vnp_TransactionNo = "";
                            data.forEach(function (item) {
                                let item_explode = item.split('=');
                                if (item_explode[0] === "vnp_ResponseCode" && item_explode[1] === "00") {
                                    check = 1;
                                }
                                else if (item_explode[0] === "vnp_TransactionNo"){
                                    vnp_TransactionNo = item_explode[1];
                                }
                            })
                            if (check === 1) {
                                $.ajax({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    url: '{{ route('be.order.update_status') }}',
                                    method: 'put',
                                    data: {order_id: id, status: 8},
                                    dataType: 'json',
                                    statusCode: {
                                        500: function (view) {
                                            console.log(JSON.parse(view.responseText));
                                        },
                                        200: function (view) {
                                            $('body').html(view.view);
                                            alert('Yêu cầu hoàn tiền thành công');
                                            window.open('https://sandbox.vnpayment.vn/merchantv2/Transaction/PaymentRefundDetail/'+vnp_TransactionNo+'.htm');
                                            activelink();
                                        }
                                    }
                                });
                            }
                            else {
                                alert('Yêu cầu hoàn tiền thất bại');
                            }
                        }
                    }
                });
            }
        }
    </script>
@endsection
