@extends('backend.layout.page')

@section('title')
    Danh sách danh mục
@endsection

@section('css')
    <style>
        .modal-open {
            overflow-y: scroll
        }

        @media (min-width: 1200px) {
            .modal-content {
                width: 1000px;
                margin-left: -50%;
            }
        }

    </style>
@endsection

@section('page-title')
    Quản lý danh mục
@endsection

@section('right-navbar')
    <li class="breadcrumb-item"><a href="{{ route('be.index') }}">Trang chủ</a></li>
    <li class="breadcrumb-item active">Quản lý danh mục</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12 col-xl-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card card-primary" id="list_categories">
                <div class="card-header">
                    <h4 class="card-title">Danh sách danh mục</h4>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <button type="button" onclick="select2()" class="btn btn-primary" data-toggle="modal" data-target="#create_category_modal">Thêm danh mục</button>
                    </div>
                        <div id="create_category_modal" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Thêm danh mục</h4>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        {{-- Parent Category --}}
                                        <div class="form-group">
                                            <label>Danh mục cha <span style="color: red">(*)</span></label>
                                            <select id="parent_id" class="form-control" onchange="request_thumb(0)">
                                                @if (count($categories) == 0)
                                                    <option value="0">Trống</option>
                                                @else
                                                    <option value="">--- Chọn danh mục ---</option>
                                                    <option value="0">Trống</option>
                                                    @foreach($categories as $category)
                                                        @if ($category->parent_id != 0)
                                                        @else
                                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>

                                        {{-- Category Name --}}
                                        <div class="form-group">
                                            <label>Tên danh mục <span style="color: red">(*)</span></label>
                                            <div class="input-group mb-3">
                                                <input required type="text" class="form-control" onkeyup="getSlug('','create')" placeholder="Tên danh mục" id="name">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-info"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Slug --}}
                                        <div class="form-group">
                                            <label>Đường dẫn tĩnh của danh mục <span style="color:red">(*)</span></label>
                                            <div class="input-group mb-3">
                                                <input placeholder="Đường dẫn tĩnh của danh mục" type="text" class="form-control" id="slug" readonly>
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-info"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Thumb --}}
                                        <div class="form-group">
                                            <label>Ảnh đại diện của danh mục <span style="color: red;display: none" id="request_thumb">(*)</span></label>
                                            <div class="custom-file">
                                                <input disabled="disabled" type="text" name="thumb" id="thumb" onclick="upload_thumb(0, this)" class="custom-file-input">
                                                <label for="thumb" class="custom-file-label" style="text-overflow: ellipsis;white-space: nowrap;overflow: hidden;">Chọn File</label>
                                            </div>
                                        </div>

                                        <div class="form-group" id="new_thumb_box">
                                        </div>
                                    </div>
                                    {{-- /.modal-body --}}
                                    <div class="modal-footer">
                                        <button type="button" data-dismiss="modal" class="btn btn-danger">Đóng</button>
                                        <button type="button" class="btn btn-primary" onclick="createCategory()">Thêm danh mục</button>
                                    </div>
                                </div>
                                {{-- /.modal-content --}}
                            </div>
                            {{-- /.modal-dialog --}}
                        </div>
                        {{-- /.modal --}}

                    <div class="form-group table-responsive">
                        <table class="table table-hover table-bordered dtr-inline dataTable text-center" id="categories_table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Danh mục cha</th>
                                    <th>Tên danh mục</th>
                                    <th>Thời gian tạo</th>
                                    <th>Cập nhật lần cuối</th>
                                    <th>Chức năng</th>
                                </tr>
                            </thead>
                            <tbody id="category_body">
                                @foreach($categories as $category)
                                    <tr id="category_{{ $category->id }}">
                                        <td>{{ $category->id }}</td>
                                        <td>
                                            @if ($category->parent_id == 0)
                                                Không có
                                            @else
                                                {{ \App\Models\Category::getCategoryById($category->parent_id)->name }}
                                            @endif
                                        </td>
                                        <td>{{ $category->name }}</td>
                                        <td data-sort="{{ $category->created_at }}">{{ date('d-m-Y H:i:s', strtotime($category->created_at)) }}</td>
                                        <td data-sort="{{ $category->updated_at }}">{{ date('d-m-Y H:i:s', strtotime($category->updated_at)) }}</td>
                                        <td>
                                            <a title="Sửa danh mục" class="btn btn-primary" href="#edit_category_modal_{{ $category->id }}" data-toggle="modal" onclick="select2_edit({{ $category->id }})"><i class="fas fa-edit"></i></a>
                                            <button title="Xóa danh mục" class="btn btn-danger" onclick="deleteCategory({{ $category->id }})"><i class="fas fa-trash"></i></button>
                                        </td>
                                    </tr>

                                    <div class="modal fade" id="edit_category_modal_{{ $category->id }}" role="dialog">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Sửa danh mục: {{ $category->name }}</h4>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>
                                                <div class="modal-body">
                                                    {{-- Parent Category --}}
                                                    <div class="form-group">
                                                        <label>Danh mục cha <span style="color: red">(*)</span></label>
                                                        <select id="parent_id_{{ $category->id }}" class="form-control" onchange="request_thumb({{ $category->id }})">
                                                            <option value="">--- Chọn danh mục ---</option>
                                                            @if ($category->parent_id == 0)
                                                                <option value="0" selected>Trống</option>
                                                            @else
                                                                <option value="0">Trống</option>
                                                            @endif
                                                            @foreach($categories as $parent)
                                                                @if ($category->parent_id == $parent->id)
                                                                    <option value="{{ $parent->id }}" selected>{{ $parent->name }}</option>
                                                                @elseif ($parent->id == $category->id)

                                                                @else
                                                                    <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    {{-- Name --}}
                                                    <div class="form-group">
                                                        <label>Tên danh mục <span style="color: red">(*)</span></label>
                                                        <div class="input-group mb-3">
                                                            <input class="form-control" type="text" id="name_{{ $category->id }}" value="{{ $category->name }}" onkeyup="getSlug({{ $category->id }}, 'edit')">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">
                                                                    <i class="fas fa-info"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    {{-- Slug --}}
                                                    <div class="form-group">
                                                        <label>Đường dẫn tĩnh của danh mục <span style="color: red">(*)</span></label>
                                                        <div class="input-group mb-3">
                                                            <input class="form-control" type="text" readonly id="slug_{{ $category->id }}" value="{{ $category->slug }}">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">
                                                                    <i class="fas fa-info"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    {{-- Thumb --}}
                                                    <div class="form-group">
                                                        <label>Ảnh đại diện của danh mục @if ($category->parent_id == 0) <span style="color: red" id="request_thumb_{{ $category->id }}">(*)</span> @else <span style="color: red; display: none" id="request_thumb_{{ $category->id }}">(*)</span> @endif</label>
                                                        <div class="custom-file">
                                                            @if ($category->parent_id == 0)
                                                                <input class="custom-file-input" type="text" id="thumb_{{ $category->id }}" name="thumb_{{ $category->id }}" value="{{ $category->thumb }}" onclick="upload_thumb({{ $category->id }}, this)">
                                                            @else
                                                                <input disabled="disabled" class="custom-file-input" type="text" id="thumb_{{ $category->id }}" name="thumb_{{ $category->id }}" value="{{ $category->thumb }}" onclick="upload_thumb({{ $category->id }}, this)">
                                                            @endif
                                                            <label style="text-overflow: ellipsis;white-space: nowrap;overflow: hidden;" for="thumb_{{ $category->id }}" class="custom-file-label">{{ $category->thumb }}</label>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        @if ($category->parent_id == 0)
                                                            <img id="uploaded_thumb_{{ $category->id }}" class="img-fluid" style="width: 100%; height: 300px" src="{{ asset($category->thumb) }}">
                                                        @else
                                                            <img id="uploaded_thumb_{{ $category->id }}" class="img-fluid" style="width: 100%; height: 300px; display: none">
                                                        @endif
                                                    </div>
                                                </div>
                                                {{-- /.modal-body --}}
                                                <div class="modal-footer">
                                                    <button class="btn btn-danger" type="button" data-dismiss="modal">Đóng</button>
                                                    <button type="button" class="btn btn-primary" onclick="edit_category({{ $category->id }})">Cập nhật</button>
                                                </div>
                                            </div>
                                            {{-- /.modal-content --}}
                                        </div>
                                        {{-- /.modal-dialog --}}
                                    </div>
                                    {{-- /.modal --}}
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
            $('#quan-ly-danh-muc').addClass('active');
        }

        function select2() {
            $('#parent_id').select2();
        }

        function select2_edit(id) {
            $('#parent_id_'+id).select2();
        }

        $('#categories_table').dataTable({
            "oLanguage" : {
                "sSearch" : 'Tìm kiếm',
                "sInfo" : 'Đang xem danh mục _START_ - _END_  trên tổng số _TOTAL_ danh mục',
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

        function getSlug(category_id, type) {
            if (type === 'edit') {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'get',
                    url: '{{ route('be.category.get_slug') }}',
                    data: {category_name: $('#name_' + category_id).val()},
                    dataType: 'json',
                    statusCode: {
                        200: function (response) {
                            $('#slug_' + category_id).val(response.slug);
                        }
                    }
                })
            }
            else {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'get',
                    url: '{{ route('be.category.get_slug') }}',
                    data: {category_name: $('#name').val()},
                    dataType: 'json',
                    statusCode: {
                        200: function (response) {
                            $('#slug').val(response.slug);
                        }
                    }
                })
            }
        }

        function request_thumb(id) {
            if (id === 0) {
                let parent_id = $('#parent_id').val();
                if (parent_id == "" || parent_id != 0) {
                    $('#request_thumb').css('display', 'none');
                    $('#thumb').attr('disabled', true);
                }
                else if (parent_id == 0) {
                    $('#request_thumb').css('display', '');
                    $('#thumb').attr('disabled', false);
                }
            }
            else {
                let parent_id = $('#parent_id_'+id).val();
                if (parent_id == "" || parent_id != 0) {
                    $('#request_thumb_'+id).css('display', 'none');
                    $('#thumb_'+id).attr('disabled', true);
                }
                else {
                    $('#request_thumb_'+id).css('display', '');
                    $('#thumb_'+id).attr('disabled', false);
                }
            }
        }

        function upload_thumb(id, input) {
            CKFinder.popup({
               chooseFiles: true,
               width: 1200,
               height: 600,
               onInit: function (finder) {
                   finder.on('files:choose', function (event) {
                       let file_uploaded = event.data.files.first();
                       input.value = file_uploaded.getUrl();
                       if (id === 0) {
                           $('#thumb').siblings('.custom-file-label').html(file_uploaded.getUrl());
                           $('#new_thumb_box').html('<img style="width: 100%; height: 200px" src="'+file_uploaded.getUrl()+'"/>');
                       }
                       else {
                           $('#thumb_'+id).siblings('.custom-file-label').html(file_uploaded.getUrl());
                           $('#uploaded_thumb_'+id).attr('src', file_uploaded.getUrl());
                       }
                   })
                   finder.on('file:choose:resizedImage', function (event) {
                        input.value = event.data.resizedUrl;
                        if (id === 0) {
                            $('#thumb').siblings('.custom-file-label').html(event.data.resizedUrl);
                            $('#new_thumb_box').html('<img style="width: 100%; height: 200px" src="'+event.data.resizedUrl+'"/>');
                        }
                        else {
                            $('#thumb_'+id).siblings('.custom-file-label').html(event.data.resizedUrl);
                            $('#uploaded_thumb_'+id).attr('src', event.data.resizedUrl);
                        }
                   })
               }
            });
        }

        function createCategory() {
            var pattern = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]+/;
            var name = $('#name').val();
            var slug = $('#slug').val();
            var parent_id = $('#parent_id').val();
            var thumb = $('#thumb').val();

            if (pattern.test(name) === true) {
                alert('Tên danh mục không được có ký tự đặc biệt');
            }
            else {
                $.ajax({
                   headers: {
                       'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                   },
                    method: 'post',
                    url: '{{ route('be.category.store') }}',
                    data: {
                       parent_id : parent_id,
                        name : name,
                        slug : slug,
                        thumb: thumb
                    },
                    dataType: 'json',
                    statusCode: {
                        500: function (response) {
                            console.log(JSON.parse(response.responseText));
                        },

                        400: function (response) {
                            var responseText = JSON.parse(response.responseText);
                            var errors = responseText['errors'];
                            var message = responseText['message'];
                            if (typeof errors !== 'undefined') {
                                if (typeof errors['parent_id'] !== 'undefined') {
                                    alert(errors['parent_id']);
                                } else if (typeof errors['name'] !== 'undefined') {
                                    alert(errors['name']);
                                } else {
                                    alert(errors['thumb']);
                                }
                            }
                            else {
                                alert(message);
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
        }

        function edit_category(category_id) {
            var pattern = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]+/;
            var name = $('#name_'+category_id).val();
            var slug = $('#slug_'+category_id).val();
            var parent_id = $('#parent_id_'+category_id).val();
            var thumb = $('#thumb_'+category_id).val();

            if (pattern.test(name) === true) {
                alert('Tên danh mục không được có ký tự đặc biệt');
            }
            else {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'post',
                    url: '{{ route('be.category.update') }}',
                    data: {
                        parent_id: parent_id,
                        name: name,
                        slug: slug,
                        category_id: category_id,
                        thumb: thumb
                    },
                    dataType: 'json',
                    statusCode: {
                        500: function (response) {
                            console.log(JSON.parse(response.responseText));
                        },

                        400: function (response) {
                            var responseText = JSON.parse(response.responseText);
                            var errors = responseText['errors'];
                            var message = responseText['message'];
                            if (typeof errors !== 'undefined') {
                                if (typeof errors['parent_id'] !== 'undefined') {
                                    alert(errors['parent_id'])
                                } else {
                                    alert(errors['name']);
                                }
                            }
                            else {
                                alert(message);
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
        }

        function deleteCategory(category_id) {
            if (confirm('Khi bạn xóa danh mục này, tất cả các danh mục con của nó sẽ được xóa theo. Bạn có chắc chắn muốn xóa danh mục này không?')) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'delete',
                    url: '{{ route('be.category.delete') }}',
                    data: {category_id : category_id},
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
                })
            }
        }
    </script>
@endsection
