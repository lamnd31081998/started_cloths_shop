@extends('backend.layout.page')

@section('title')
    Quản lý mã giảm giá
@endsection

@section('css')
    <style>
        @media (min-width: 1200px) {
            .modal-content {
                width: 1000px;
                margin-left: -50%;
            }
        }
    </style>
@endsection

@section('page-title')
    Quản lý mã giảm giá
@endsection

@section('right-navbar')
    <li class="breadcrumb-item"><a href="{{ route('be.index') }}">Trang chủ</a></li>
    <li class="breadcrumb-item active">Quản lý mã giảm giá</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12 col-lg-12 col-xs-12 col-md-12 col-sm-12 col-xxl-12 col-xl-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h4 class="card-title">Danh sách mã giảm giá</h4>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <a href="{{ route('be.promotion.create') }}" class="btn btn-primary">Thêm mã giảm giá</a>
                    </div>

                    <div class="form-group table-responsive">
                        <table class="table-bordered table table-hover dtr-inline dataTable text-center" id="promotions_table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Mã giảm giá</th>
                                    <th>Mô tả</th>
                                    <th>Yêu cầu đơn hàng tối thiểu</th>
                                    <th>Mức giảm giá</th>
                                    <th>Số lần sử dụng tối đa của mỗi người dùng</th>
                                    <th>Số lần sử dụng còn lại của mã giảm giá</th>
                                    <th>Ngày bắt đầu</th>
                                    <th>Ngày hết hạn</th>
                                    <th>Chức năng</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($promotions as $promotion)
                                    <tr id="promotion_{{ $promotion->id }}">
                                        <td>{{ $promotion->id }}</td>
                                        <td>{{ $promotion->code }}</td>
                                        <td>{{ $promotion->description }}</td>
                                        <td>{{ number_format($promotion->at_least, 0, '', '.') }}vnđ</td>
                                        <td>{{ $promotion->discount }}%</td>
                                        <td>{{ $promotion->max_user_use }}</td>
                                        <td>{{ $promotion->max_use }}</td>
                                        <td>{{ date('d-m-Y', strtotime($promotion->start_time)) }}</td>
                                        <td>
                                            @if ($promotion->expire_time != null)
                                                {{ date('d-m-Y', strtotime($promotion->expire_time)) }}
                                            @else
                                                Chưa thiết lập
                                            @endif
                                        </td>
                                        <td style="width: 100px;">
                                            <a title="Sửa mã giảm giá" class="btn btn-primary" href="{{ route('be.promotion.edit', ['id' => $promotion->id]) }}"><i class="fas fa-edit"></i></a>
                                            <button title="Xóa mã giảm giá" class="btn btn-danger" type="button" onclick="delete_promotion({{ $promotion->id }})"><i class="fas fa-trash"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div role="dialog" class="modal fade" id="add_promotion_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Thêm mã giảm giá</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    {{----}}<div class="form-group">
                        <label for="code">Mã giảm giá <span style="color: red">(*)</span></label>
                        <div class="input-group mb-3">
                            <input class="form-control" type="text" id="code" placeholder="Mã giảm giá">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="fas fa-info-circle"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="discount">Mức giảm giá (%) <span style="color:red;">(*)</span></label>
                        <div class="input-group mb-3">
                            <input class="form-control" type="number" id="discount" min="1" placeholder="Mức giảm giá (%)">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="fas fa-info-circle"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="at_least">Yêu cầu đơn hàng tối thiểu (vnđ)</label>
                        <div class="input-group mb-3">
                            <input class="form-control" type="text" id="at_least" placeholder="Yêu cầu đơn hàng tối thiểu (vnđ)">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="fas fa-info-circle"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" id="default_at_least" class="form-check-input">
                        <label class="form-check-label" for="default_at_least">Nếu bạn chọn tùy chọn này, đơn hàng tối thiểu sẽ là 0vnđ</label>
                    </div>

                    <div class="form-group">
                        <label for="start_time">Ngày bắt đầu (Ngày/Tháng/Năm)</label>
                        <div class="input-group mb-3">
                            <input type="date" class="form-control" id="start_time">
                        </div>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" id="default_start_time" class="form-check-input">
                        <label class="form-check-label" for="default_start_time">Nếu bạn chọn tùy chọn này, ngày bắt đầu mặc định sẽ là ngày tạo mã giảm giá</label>
                    </div>

                    <div class="form-group">
                        <label for="expire_time">Ngày hết hạn (Ngày/Tháng/Năm)</label>
                        <div class="input-group mb-3">
                            <input type="date" class="form-control" id="expire_time">
                        </div>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" id="default_expire_time" class="form-check-input">
                        <label class="form-check-label" for="default_expire_time">Nếu bạn chọn tùy chọn này, ngày hết hạn sẽ không được thiết lập, bạn có thể tùy chỉnh sau nếu muốn</label>
                    </div>

                    <div class="form-group">
                        <label for="description">Mô tả <span style="color: red">(*)</span></label>
                        <div class="input-group mb-3">
                            <input class="form-control" type="text" id="description" placeholder="Mô tả mã giảm giá">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="fas fa-info-circle"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="max_use">Số lượt sử dụng tối đa của mã giảm giá <span style="color: red">(*)</span></label>
                        <div class="input-group mb-3">
                            <input class="form-control" type="number" id="max_use" placeholder="Số lượt sử dụng tối đa của mã giảm giá">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="fas fa-info-circle"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="max_user_use">Số lượt dùng mã giảm giá tối đa của mỗi số điện thoại <span style="color: red">(*)</span></label>
                        <div class="input-group mb-3">
                            <input class="form-control" type="number" id="max_user_use" placeholder="Số lượt dùng mã giảm giá tối đa của mỗi số điện thoại">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="fas fa-info-circle"></i>
                                </span>
                            </div>
                        </div>
                    </div>{{----}}

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary">Thêm</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        function activelink() {
            $('.ma-giam-gia').addClass('menu-open');
            $('#ma-giam-gia').addClass('active');
            $('#danh-sach-ma-giam-gia').addClass('active');
            $('#promotions_table').DataTable({
                "oLanguage" : {
                    "sSearch" : 'Tìm kiếm',
                    "sInfo" : 'Đang xem mã giảm giá _START_ - _END_  trên tổng số _TOTAL_ mã giảm giá',
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
    </script>
@endsection
