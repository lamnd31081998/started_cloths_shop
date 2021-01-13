@extends('backend.layout.page')

@section('title')
    Quản lý phí giao hàng
@endsection

@section('css')

@endsection

@section('page-title')
    Quản lý phí giao hàng
@endsection

@section('right-navbar')
    <li class="breadcrumb-item"><a href="{{ route('be.index') }}">Trang chủ</a></li>
    <li class="breadcrumb-item active">Quản lý phí giao hàng</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12 col-xxl-12 col-sm-12 col-xl-12 col-md-12 col-lg-12 col-xs-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h4 class="card-title">Danh sách loại hình giao hàng</h4>
                </div>
                <div class="card-body table-responsive">
                    <table class="text-center table table-hover table-bordered dtr-inline dataTable" id="ship_fees_table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Loại hình giao hàng</th>
                                <th>Mô tả</th>
                                <th>Giá</th>
                                <th>Chức năng</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ship_fees as $ship_fee)
                                <tr>
                                    <td>{{ $ship_fee->id }}</td>
                                    <td>{{ $ship_fee->name }}</td>
                                    <td>{{ $ship_fee->description }}</td>
                                    <td>
                                        @if ($ship_fee->price != 0)
                                        {{ number_format($ship_fee->price, 0, '', '.') }}vnđ
                                        @else
                                            Miễn phí
                                        @endif
                                    </td>
                                    <td><button title="Sửa thông tin giao hàng" onclick="formatmoney({{ $ship_fee->id }})" class="btn btn-primary" type="button" data-toggle="modal" data-target="#edit_ship_fee_{{ $ship_fee->id }}"><i class="fas fa-edit"></i></button></td>
                                </tr>
                                <div class="modal fade" role="dialog" id="edit_ship_fee_{{ $ship_fee->id }}">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Sửa loại hình giao hàng</h4>
                                                <button type="button" data-dismiss="modal" class="close">&times;</button>
                                            </div>
                                            <div class="modal-body">
                                                {{-- Name --}}
                                                <div class="form-group">
                                                    <label>Tên loại hình giao hàng <span style="color: red">(*)</span></label>
                                                    <div class="input-group mb-3">
                                                        <input class="form-control" type="text" value="{{ $ship_fee->name }}" id="name_{{ $ship_fee->id }}">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">
                                                                <i class="fas fa-info"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Description --}}
                                                <div class="form-group">
                                                    <label>Mô tả</label>
                                                    <div class="input-group mb-3">
                                                        <input class="form-control" type="text" value="{{ $ship_fee->description }}" id="description_{{ $ship_fee->id }}">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">
                                                                <i class="fas fa-info"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Ship fee --}}
                                                <div class="form-group">
                                                    <label>Giá (đơn vị: vnđ) <span style="color: red">(*)</span></label>
                                                    <div class="input-group mb-3">
                                                        <input class="form-control" type="text" value="{{ $ship_fee->price }}" id="price_{{ $ship_fee->id }}">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">
                                                                <i class="fas fa-money-bill-alt"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-danger" type="button" data-dismiss="modal">Đóng</button>
                                                <button class="btn btn-primary" type="button" onclick="updateShipfee({{ $ship_fee->id }})">Cập nhật</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
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
            $('#quan-ly-phi-giao-hang').addClass('active');
            $('#ship_fees_table').DataTable({
                "oLanguage" : {
                    "sSearch" : 'Tìm kiếm',
                    "sInfo" : 'Đang xem loại hình giao hàng _START_ - _END_ trên tổng số _TOTAL_ loại hình giao hàng',
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
        }

        function formatmoney(id) {
            $('#price_'+id).simpleMoneyFormat();
        }

        function updateShipfee(id) {
            let pattern = /[!@#$%^&*()_+\=\[\]{};':"\\|.<>\/?]+/;
            let name = $('#name_'+id).val();
            let description = $('#description_'+id).val();
            let price = $('#price_'+id).val();
            if (name.length === 0 || price.length === 0) {
                alert('Vui lòng nhập tên loại hình giao hàng và phí giao hàng');
            }
            else if (pattern.test(name) === true || pattern.test(description) === true) {
                alert('Tên loại hình giao hàng và mô tả không được phép có ký tự đặc biệt');
            }
            else {
                price = parseInt(price.replace(",", ""));
                $.ajax({
                    headers: {
                       'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'put',
                    url: '{{ route('be.ship_fee.update') }}',
                    data: {
                        id : id,
                        name : name,
                        description : description,
                        price : price
                    },
                    dataType: 'json',
                    statusCode: {
                        500: function (response) {
                            console.log(response.responseText);
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
    </script>
@endsection
