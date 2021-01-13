@extends('backend.layout.page')

@section('title')
    Giá trị thuộc tính màu sắc
@endsection

@section('css')
    <!-- Bootstrap-tagsinput -->
    <link rel="stylesheet" href="{{ asset('bootstrap-tagsinput/bootstrap-tagsinput.css') }}" />

    <style>
        .modal-open {
            overflow-y: scroll
        }

        .bootstrap-tagsinput .label-info {
            background-color: #5bc0de;
            display: inline;
            padding: .2em .6em .3em;
            font-size: 90%;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: .25em;
        }

        .bootstrap-tagsinput input {
            border: none;
            box-shadow: none;
            outline: none;
            background-color: transparent;
            padding: 0 6px;
            margin: 10px 0 0 0;
            width: auto;
            max-width: inherit;
            display: block;
        }

        .bootstrap-tagsinput {
            background-color: #fff;
            border: 1px solid #ccc;
            box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
            display: inline-block;
            padding: 4px 6px;
            color: #555;
            vertical-align: middle;
            border-radius: 4px;
            width: 100%;
            line-height: 22px;
            cursor: text;
        }
    </style>
@endsection

@section('page-title')
    Quản lý giá trị thuộc tính
@endsection

@section('right-navbar')
    <li class="breadcrumb-item"><a href="{{ route('be.index') }}">Trang chủ</a></li>
    <li class="breadcrumb-item"><a href="#">Giá trị thuộc tính</a></li>
    <li class="breadcrumb-item active">Màu sắc</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12 col-xl-12 col-sm-12 col-xl-12 col-md-12 col-lg-12">
            <div class="card card-primary" id="attribute_values_list">
                <div class="card-header">
                    <h4 class="card-title">Danh sách giá trị thuộc tính màu sắc</h4>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <button class="btn btn-primary" onclick="select2()" data-toggle="modal" data-target="#create_color_values_modal">Thêm giá trị</button>

                        <div class="modal fade" id="create_color_values_modal" role="dialog">
                            <div class="modal-dialog">
                                <div class="modal-content" style="margin-top: 40%">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Thêm giá trị</h4>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        {{-- Category --}}
                                        <div class="form-group">
                                            <label>Danh mục <span style="color: red">(*)</span></label>
                                            <select id="category_id" class="form-control">
                                                @if (count($categories) == 0)
                                                    <option value="">--- Vui lòng thêm danh mục cấp 2 trước ---</option>
                                                @else
                                                    <option value="">--- Chọn danh mục ---</option>
                                                    @foreach($categories as $category)
                                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>

                                        {{-- Attribute Values --}}
                                        <div class="form-group">
                                            <label>Giá trị thuộc tính <span style="color: red">(*)</span></label>
                                            <div class="input-group mb-3">
                                                <input placeholder="---" type="text" id="attribute_values" data-role="tagsinput" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    {{-- /.modal-body --}}
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Đóng</button>
                                        <button type="button" class="btn btn-primary" onclick="createAttributevalues()">Thêm giá trị</button>
                                    </div>
                                </div>
                                {{-- /.modal-content --}}
                            </div>
                            {{-- /.modal-dialog --}}
                        </div>
                        {{-- /.modal --}}
                    </div>
                    <div class="form-group table-responsive">
                        <table class="text-center dataTable table table-hover table-bordered dtr-inline" id="attribute_values_table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Danh mục</th>
                                    <th>Giá trị</th>
                                    <th>Thời gian tạo</th>
                                    <th>Chức năng</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($attribute_values as $attribute_value)
                                    <tr>
                                        <td>{{ $attribute_value->id }}</td>
                                        <td>{{ \App\Models\Category::getCategoryById($attribute_value->category_id)->name }}</td>
                                        <td>{{ $attribute_value->value }}</td>
                                        <td data-sort="{{ $attribute_value->created_at }}">{{ date('d-m-Y H:i:s', strtotime($attribute_value->created_at)) }}</td>
                                        <td><button title="Xóa giá trị" class="btn btn-danger" onclick="deleteAttributevalue({{ $attribute_value->id }})"><i class="fas fa-trash"></i></button></td>
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
    <!-- Bootstrap-tagsinput -->
    <script src="{{ asset('bootstrap-tagsinput/bootstrap-tagsinput.min.js') }}"></script>

    <script>
        function activelink() {
            $('#gia-tri-thuoc-tinh').addClass('active');
            $('.gia-tri-thuoc-tinh').addClass('menu-open');
            $('#mau-sac').addClass('active');
        }

        function select2() {
            $('#category_id').select2();
        }

        $('#attribute_values_table').DataTable({
            "oLanguage" : {
               "sSearch" : "Tìm kiếm",
               "sInfo" : "Đang xem giá trị _START_ - _END_ trên tổng số _TOTAL_ giá trị",
               "sLengthMenu" : "Hiển thị <select class='custom-select custom-select-sm form-control form-control-sm'>" +
                   '<option value="10">10</option>' +
                   '<option value="25">25</option>' +
                   '<option value="50">50</option>' +
                   '</select> bản ghi',
               "sEmptyTable" : 'Không có dữ liệu',
               "sZeroRecords" : 'Không có dữ liệu',
               "sInfoEmpty" : "",
               "oPaginate" : {
                 "sNext" : ">>",
                 "sPrevious" : "<<"
               },
            }
        });

        function createAttributevalues() {
            var category_id = $('#category_id').val();
            var attribute_values = $('#attribute_values').val();
            $.ajax({
               headers: {
                   'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
               },
                method: 'post',
                url: '{{ route('be.attribute_value.attribute_color.store') }}',
                data: {
                    category_id : category_id,
                    attribute_values : attribute_values
                },
                dataType: 'json',
                statusCode: {
                    500: function (response) {
                        console.log(response.responseText);
                    },

                    400: function (response) {
                        var responseText = JSON.parse(response.responseText);
                        var errors = responseText['errors'];

                        if (typeof errors['category_id'] !== 'undefined') {
                            alert(errors['category_id'])
                        }
                        else if (typeof errors['attribute_values'] !== 'undefined') {
                            alert(errors['attribute_values'])
                        }
                    },

                    200: function (response) {
                        alert(response.message);
                        $('body').html(response.view);
                        activelink();
                    }
                }
            });
        }

        function deleteAttributevalue(color_id) {
            if (confirm("Bạn có chắc chắn muốn xóa giá trị này không?")) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'delete',
                    url: '{{ route('be.attribute_value.attribute_color.delete') }}',
                    data: {color_id : color_id},
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
