@extends('backend.layout.page')

@section('title')
    Danh sách quản lý
@endsection

@section('css')

@endsection

@section('page-title')
    Quản lý tài khoản
@endsection

@section('right-navbar')
    <li class="breadcrumb-item"><a href="{{ route('be.index') }}">Trang chủ</a></li>
    <li class="breadcrumb-item"><a href="#">Quản lý tài khoản</a></li>
    <li class="breadcrumb-item active">Danh sách quản lý</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12 col-sm-12 col-lg-12 col-md-12 col-xl-12">
            <div class="card card-primary" id="account_list">
                <div class="card-header">
                    <h3 class="card-title">Danh sách quản lý</h3>
                </div>
                <div class="card-body">
                    {{-- Add account button --}}
                    <div class="form-group">
                        <a class="btn btn-primary" href="{{ route('be.admin.create') }}">Thêm tài khoản</a>
                    </div>

                    {{-- list admin account --}}
                    <div class="form-group table-responsive">
                        <table class="table table-hover dataTable table-bordered dtr-inline text-center" id="admins_table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tên tài khoản</th>
                                    <th>Email</th>
                                    <th>Họ và tên</th>
                                    <th>Giới tính</th>
                                    <th>Ngày sinh</th>
                                    <th>Số điện thoại</th>
                                    <th>Thời gian tạo</th>
                                    <th>Thời gian cập nhật cuối</th>
                                    <th>Trạng thái</th>
                                    <th>Chức năng</th>
                                </tr>
                            </thead>
                            <tbody id="admin_table">
                            @foreach($admins as $admin)
                                <tr>
                                    <td>{{ $admin->id }}</td>
                                    <td>{{ $admin->username }}</td>
                                    <td>{{ $admin->email }}</td>
                                    <td>{{ $admin->name }}</td>
                                    <td>{{ ($admin->gender == 1) ? 'Nam' : 'Nữ' }}</td>
                                    <td>
                                        @if ($admin->birthday != null)
                                            {{ date('d-m-Y', strtotime($admin->birthday)) }}
                                        @else
                                            Chưa thiết lập
                                        @endif
                                    </td>
                                    <td>{{ $admin->phone_number }}</td>
                                    <td data-sort="{{ $admin->created_at }}">{{ date('d-m-Y H:i:s', strtotime($admin->created_at)) }}</td>
                                    <td data-sort="{{ $admin->updated_at }}">{{ date('d-m-Y H:i:s', strtotime($admin->updated_at)) }}</td>
                                    <td>
                                        @if ($admin->status == 0)
                                            <input type="checkbox" data-toggle="toggle" data-on=" " data-off=" " data-onstyle="success" data-offstyle="danger" id="status_{{ $admin->id }}" onchange="changeStatus({{ $admin->id }})">
                                        @else
                                            <input type="checkbox" data-toggle="toggle" data-on=" " data-off=" " data-onstyle="success" data-offstyle="danger" id="status_{{ $admin->id }}" onchange="changeStatus({{ $admin->id }})" checked>
                                        @endif
                                    </td>
                                    <td>
                                        <a title="Sửa tài khoản" class="btn btn-primary" href="{{ route('be.admin.edit', ['id' => $admin->id]) }}"><i class="fas fa-edit"></i></a>
                                        <button title="Xóa tài khoản" type="button" class="btn btn-danger" onclick="deleteAccount({{ $admin->id }})"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            {{-- /.card-body --}}
        </div>
        {{-- /.card --}}
    </div>
@endsection

@section('js')
    <script>
        function activelink() {
            $('#quan-ly-tai-khoan').addClass('active');
            $('.quan-ly-tai-khoan').addClass('menu-open');
            $('#danh-sach-tai-khoan').addClass('active');
        }

        // DataTable
        $('#admins_table').DataTable({
            "oLanguage": {
                "sInfo" : "Đang xem tài khoản _START_ - _END_ trên tổng số _TOTAL_ tài khoản",
                "sSearch" : "Tìm kiếm",
                "sLengthMenu" :  'Hiển thị <select class="custom-select custom-select-sm form-control form-control-sm">' +
                    '<option value="10">10</option>' +
                    '<option value="25">25</option>' +
                    '<option value="50">50</option>' +
                    '</select> bản ghi',
                "sEmptyTable" : "Không có dữ liệu",
                "sZeroRecords" : "Không có dữ liệu",
                "sInfoEmpty" : "",
                "oPaginate" : {
                    "sNext" : '>>',
                    "sPrevious" : '<<'
                }
            }
        });

        function changeStatus(admin_id) {
            if ($('#status_'+admin_id).is(':checked')) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'post',
                    url: '{{ route('be.admin.edit_status') }}',
                    data: {status : 1, id : admin_id},
                    dataType: 'json',
                    statusCode: {
                        200: function(response) {
                            $('#status_' + admin_id).prop('checked', true);
                            alert(response.message);
                        },

                        500: function (response) {
                            console.log(JSON.parse(response.responseText))
                        }
                    }
                });
            }
            else {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'post',
                    url: '{{ route('be.admin.edit_status') }}',
                    data: {status : 0, id : admin_id},
                    dataType: 'json',
                    statusCode: {
                        200: function (response) {
                            $('#status_' + admin_id).prop('checked', false);
                            alert(response.message);
                        },

                        500: function (response) {
                            console.log(JSON.parse(response.responseText))
                        }
                    }
                });
            }
        }

        function deleteAccount(admin_id) {
            if (confirm('Bạn có chắc chắn muốn xóa tài khoản này không?')) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'delete',
                    url: '{{ route('be.admin.delete') }}',
                    data: {admin_id: admin_id},
                    dataType: 'json',
                    statusCode: {
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
