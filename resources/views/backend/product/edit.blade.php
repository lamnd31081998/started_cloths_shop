@extends('backend.layout.page')

@section('title')
    Sửa sản phẩm
@endsection

@section('css')
    <style>
        .select2-container .select2-selection--multiple .select2-selection__rendered {
            display: block;
            margin-bottom: 5px;
        }

        .select2-container--default .select2-dropdown .select2-search__field:focus, .select2-container--default .select2-search--inline .select2-search__field:focus, .select2-container--default.select2-container--focus .select2-search__field, .select2-container--default .select2-dropdown .select2-search__field, .select2-container--default .select2-search--inline .select2-search__field {
            border: 0;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            border-right: none;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__display, .select2-container--default .select2-selection--multiple .select2-selection__rendered {
            padding: 0;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            padding: 0 5px;
        }

        @media (max-width: 1024px) {
            #preview_product_button {
                display: none;
            }
            #reset_product_button {
                margin-right: 10px;
            }
        }

        @media (min-width: 1600px) {
            .preview_modal {
                margin-left: -125%;
                width: 1775px !important;
            }
        }

        .modal {
            overflow: auto !important;
        }
    </style>
@endsection

@section('page-title')
    Quản lý sản phẩm
@endsection

@section('right-navbar')
    <li class="breadcrumb-item"><a href="{{ route('be.index') }}">Trang chủ</a></li>
    <li class="breadcrumb-item"><a href="{{ route('be.product.index') }}">Quản lý sản phẩm</a></li>
    <li class="breadcrumb-item active">Sửa sản phẩm</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12 col-lg-12 col-md-12 col-xl-12 col-sm-12 col-xxl-12">
            <form method="post" enctype="multipart/form-data" action="{{ route('be.product.update', ['id' => $product->id]) }}" onsubmit="checkprice(event)">
                @csrf
                @method('put')
                <div class="card card-primary">
                    <div class="card-header">
                        <h4 class="card-title">Sửa sản phẩm: {{ $product->name }}</h4>
                    </div>
                    <div class="card-body">
                        {{-- Category --}}
                        <div class="form-group">
                            <label>Danh mục <span style="color:red">(*)</span></label>
                            <select style="width: 100%" name="category_id" id="category_id" onchange="getAttributevalues()" required oninvalid="this.setCustomValidity('Vui lòng chọn danh mục')" oninput="this.setCustomValidity('')">
                                <option value="">--- Chọn danh mục ---</option>
                                @foreach($categories as $category)
                                    @if ($category->id == $product->category_id)
                                        <option value="{{ $category->id }}" selected>{{ $category->name }}</option>
                                    @else
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        {{-- Name --}}
                        <div class="form-group">
                            <label>Tên sản phẩm <span style="color: red">(*)</span></label>
                            <div class="input-group mb-3">
                                <input placeholder="Tên sản phẩm" class="form-control" type="text" onkeyup="getSlug()" value="{{ $product->name }}" name="name" id="name" required oninvalid="this.setCustomValidity('Vui lòng nhập tên sản phẩm')" oninput="this.setCustomValidity('')">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-info"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Slug --}}
                        <div class="form-group">
                            <label>Đường dẫn tĩnh của sản phẩm <span style="color: red">(*)</span></label>
                            <div class="input-group mb-3">
                                <input readonly placeholder="Đường dẫn tĩnh của sản phẩm" class="form-control" name="slug" id="slug" value="{{ $product->slug }}">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-info"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Price --}}
                        <div class="form-group">
                            <label>Giá sản phẩm (Đơn vị: vnđ) <span style="color: red">(*)</span></label>
                            <div class="input-group mb-3">
                                <input class="form-control" type="text" placeholder="Giá sản phẩm" name="price" id="price" value="{{ number_format($product->price, 0, '', ',') }}" required oninvalid="this.setCustomValidity('Vui lòng nhập giá cho sản phẩm')" oninput="this.setCustomValidity('')">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-money-bill-alt"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Sale Price--}}
                        <div class="form-group">
                            <label>Giá khuyến mãi (Đơn vị: vnđ)</label>
                            <div class="input-group mb-3">
                                <input class="form-control" type="text" placeholder="Giá khuyến mãi" name="sale_price" id="sale_price" value="{{ $product->sale_price == "" ?  null : number_format($product->sale_price, 0, '', ',') }}">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-money-bill-alt"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Colors --}}
                        <div class="form-group">
                            <label>Màu sắc</label>
                            <select style="width: 100%" multiple name="attribute_value_1[]" id="attribute_value_1">
                                @foreach($colors as $color)
                                    @if ($color->category_id == $product->category_id)
                                        <option value="{{ $color->id }}">{{ $color->value }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        {{-- Sizes --}}
                        <div class="form-group">
                            <label>Kích cỡ</label>
                            <select style="width: 100%" multiple name="attribute_value_2[]" id="attribute_value_2">
                                @foreach($sizes as $size)
                                    @if ($size->category_id == $product->category_id)
                                        <option value="{{ $size->id }}">{{ $size->value }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        {{-- Button create variants --}}
                        <div class="form-group">
                            <button type="button" class="btn btn-primary" onclick="createVariants()">Tạo biến thể mới</button>
                            <div id="add_quantity" style="display: inline-block">
                                <button type="button" onclick="addQuantity({{ count($product_attribute_values) }})" class="btn btn-primary">Thêm số lượng cho tất cả biến thể</button>
                            </div>
                            <div id="remove_quantity" style="display: inline-block">
                                <button type="button" onclick="removeQuantity({{ count($product_attribute_values) }})" class="btn btn-danger">Xóa số lượng cho tất cả biến thể</button>
                            </div>
                            <button id="return_origin_variants" style="display: none" type="button" class="btn btn-danger" onclick="returnVariantsDefault()">Trở về bản gốc</button>
                        </div>

                        {{-- Created Variants --}}
                        <div class="form-group" id="created_variants">
                            <label>Biến thể đã tạo</label>
                            <table class="text-center table table-bordered table-hover dataTable dtr-inline" id="variants_table" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Màu sắc</th>
                                        <th>Kích cỡ</th>
                                        <th>Số lượng</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($product_attribute_values as $index=>$product_attribute_value)
                                        <tr>
                                            <td>{{ $product_attribute_value->id }}</td>
                                            <td>
                                                <input type="hidden" id="attribute_value_{{ ($index+1) }}_1" value="{{ $product_attribute_value->color_id }}">
                                                {{ \App\Models\Attribute_value::getAttributevalueById($product_attribute_value->color_id)->value }}
                                            </td>
                                            <td>
                                                <input type="hidden" id="attribute_value_{{ ($index+1) }}_2" value="{{ $product_attribute_value->size_id  }}">
                                                {{ \App\Models\Attribute_value::getAttributevalueById($product_attribute_value->size_id)->value }}
                                            </td>
                                            <td><input class="form-control" type="number" name="quantity_{{ $index+1 }}" value="{{ $product_attribute_value->quantity }}" required oninvalid="this.setCustomValidity('Vui lòng nhập số lượng')" oninput="this.setCustomValidity('')"></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Images --}}
                        <div class="form-group">
                            <label>Ảnh sản phẩm <span style="color:red;">(Tối đa 5 ảnh)</span></label>
                            <div class="custom-file">
                                <input onclick="upload_image()" type="text" class="custom-file-input">
                                <label class="custom-file-label" for="images">Chọn File</label>
                            </div>
                        </div>

                        <div class="form-group preview_images">
                            <table class="table table-bordered table-hover text-center dtr-inline" style="margin-top: 10px">
                                <thead>
                                    <tr>
                                        <th>STT</th>
                                        <th>Ảnh</th>
                                        <th>Đường dẫn ảnh</th>
                                        <th>Chức năng</th>
                                    </tr>
                                </thead>
                                <tbody id="uploaded_images_table">
                                    @foreach($product_images as $index=>$product_image)
                                        <tr>
                                            <td>{{ $index+1 }}</td>
                                            <input type="hidden" name="uploaded_images[]" id="uploaded_image_{{ $index+1 }}" value="{{ $product_image->images }}">
                                            <td><img class="img-fluid" style="width: 300px; height: 300px" id="preview_image_{{ $index+1 }}" src="{{ asset($product_image->images) }}"></td>
                                            <td id="image_src_{{ $index+1 }}">{{ $product_image->images }}</td>
                                            <td>
                                                <button type="button" class="btn btn-primary" title="Thay đổi ảnh" onclick="edit_image({{ $index+1 }})"><i class="fas fa-edit"></i></button>
                                                <button type="button" class="btn btn-danger" title="Gỡ ảnh" onclick="remove_image({{ $index+1 }})"><i class="fas fa-trash"></i></button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Size Image --}}
                        <div class="form-group">
                            <label>Ảnh kích cỡ</label>
                            <div class="custom-file">
                                <input onclick="upload_size_image(this)" type="text" name="size_image" value="{{ $product->size_image }}" id="size_image" class="custom-file-input">
                                <label class="custom-file-label" for="size_image">{{ $product->size_image }}</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <img class="img-fluid" style="width: 100%; height: 400px" src="{{ asset($product->size_image) }}" id="size_image_preview_src">
                        </div>

                    </div>
                    {{-- /.card-body --}}
                    <div class="card-footer">
                        <button class="btn btn-primary" style="float:right;" type="submit">Sửa sản phẩm</button>
                        <button type="button" id="preview_product_button" onclick="preview_product({{ $product->id }})" class="btn btn-primary" style="float: right; margin: 0 10px 0 10px">Xem trước</button>
                        <button id="reset_product_button" class="btn btn-danger" style="float:right;" type="reset">Nhập lại</button>
                    </div>
                </div>
                {{-- /.card --}}
            </form>
        </div>
        <div class="modal fade" role="dialog" id="preview_modal" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="preview_modal modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Xem trước</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body" id="preview_product">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" onclick="$('#preview_modal').modal('hide'); $('#size_image_preview').modal('hide')">Đóng</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="size_image_preview" role="dialog" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content text-center">
                    <div class="modal-header">
                        <h4 class="modal-title">Bảng kích cỡ</h4>
                        <button type="button" class="close" onclick="$('#size_image_preview').modal('hide');">&times;</button>
                    </div>
                    <div class="modal-body">
                        <img class="img-fluid" src="{{ asset($product->size_image) }}" id="size_image_src">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" onclick="$('#size_image_preview').modal('hide')">Đóng</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        function activelink() {
            $('.quan-ly-san-pham').addClass('menu-open');
            $('#quan-ly-san-pham').addClass('active');
            $('#sua-san-pham').addClass('active');
            $('#category_id').select2();
            $('#attribute_value_1').select2();
            $('#attribute_value_2').select2();
            $('#price').simpleMoneyFormat();
            $('#sale_price').simpleMoneyFormat();
        }

        let j = 1;
        let i = {{ count($product_images)+1 }};
        let total_images =  {{ count($product_images) }};
        let images_uploaded = new Object();

        while (j <= total_images) {
            images_uploaded[j] = $('#uploaded_image_'+j).val();
            j = j + 1;
        }

        // DataTable
        var table = $('#variants_table').DataTable({
            pageLength: 50,
            ordering: false,
            "oLanguage": {
                "sInfo" : "Đang xem biến thể _START_ - _END_ trên tổng số _TOTAL_ biến thể",
                "sSearch" : "Tìm kiếm",
                "sLengthMenu" :  'Hiển thị <select class="custom-select custom-select-sm form-control form-control-sm">' +
                    '<option value="10">10</option>' +
                    '<option value="25">25</option>' +
                    '<option value="50">50</option>' +
                    '</select> bản ghi',
                "oPaginate" : {
                    "sNext" : '>>',
                    "sPrevious" : '<<'
                }
            }
        });

        function getSlug() {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                },
                method: 'get',
                url: '{{ route('be.product.get_slug') }}',
                data: { name: $('#name').val() },
                dataType: 'json',
                statusCode: {
                    500: function (response) {
                        console.log(response.responseText);
                    },

                    200: function (response) {
                        $('#slug').val(response.slug);
                    }
                }
            })
        }

        function getAttributevalues() {
            if ($('#category_id').val() === "") {
                $('#attribute_value_1').html('<option disabled>--- Vui lòng chọn danh mục trước ---</option>');
                $('#attribute_value_2').html('<option disabled>--- Vui lòng chọn danh mục trước ---</option>');
            }
            else {
                var category_id = $('#category_id').val();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'get',
                    url: '{{ route('be.product.get_attribute_values') }}',
                    data: {category_id : category_id},
                    dataType: 'json',
                    statusCode: {
                        500: function (response) {
                            console.log(response.responseText);
                        },

                        400: function (response) {
                            alert(response.message);
                        },

                        200: function (response) {
                            $('#attribute_value_1').html(response.colors_view);
                            $('#attribute_value_2').html(response.sizes_view);
                        }
                    }
                });
            }
        }

        let check_variants = 0;

        function createVariants() {
            var colors = $('#attribute_value_1').val();
            var sizes = $('#attribute_value_2').val();

            if (colors.length === 0 || sizes.length === 0) {
                alert('Vui lòng nhập đầy đủ giá trị thuộc tính');
            }

            else if (confirm("Lưu ý: Tất cả những giá trị bạn vừa nhập sẽ mất. Bạn chắc chắn muốn tạo biến thể mới không?")) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'get',
                    url: '{{ route('be.product.create_variants') }}',
                    data: {
                        colors: colors,
                        sizes: sizes
                    },
                    dataType: 'json',
                    statusCode: {
                        500: function (response) {
                            console.log(response.responseText);
                        },
                        200: function (response) {
                            check_variants = 1;
                            $('#created_variants').html(response.view);
                            alert(response.message);
                            $('#add_quantity').html('<button type="button" onclick="addQuantity(' + response.total + ')" class="btn btn-primary">Thêm số lượng cho tất cả biến thể</button>');
                            $('#remove_quantity').html('<button type="button" onclick="removeQuantity(' + response.total + ')" class="btn btn-danger">Xóa số lượng cho tất cả biến thể</button>');
                            $('#return_origin_variants').css('display', '');
                        }
                    }
                });
            }
        }

        function addQuantity(total) {
            var quantity = prompt('Nhập số lượng');

            if (quantity !== null) {
                for (var i = 1; i<= total; i++) {
                    if ($('#quantity_'+i).val().length === 0) {
                        $('#quantity_'+i).val(quantity);
                    }
                }
                alert('Thêm số lượng thành công');
            }
        }

        function removeQuantity(total) {
            if (confirm('Bạn có chắc chắn muốn xóa số lượng của tất cả các biến thể?')) {
                for (let i = 1; i <= total; i++) {
                    $('#quantity_'+i).val('');
                }
                alert('Xóa số lượng thành công');
            }
        }

        function returnVariantsDefault() {
            if (confirm('Bạn chắc chắn muốn quay về bản gốc không?')) {
               $.ajax({
                   headers: {
                       'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                   },
                   method: 'get',
                   url: '{{ route('be.product.return_origin_variants') }}',
                   data: { product_id : {{ $product->id }} },
                   dataType: 'json',
                   statusCode: {
                       500: function (response) {
                            console.log(response.responseText);
                       },

                       200: function (response) {
                            $('#created_variants').html(response.view);
                            alert(response.message);
                            $('#return_origin_variants').css('display', 'none');
                            $('#attribute_value_1').val('');
                            $('#attribute_value_2').val('');
                            $('.select2-selection__rendered').empty();
                       }
                   }
               })
            }
        }

        function upload_image() {
            CKFinder.popup({
                chooseFiles: true,
                width: 1200,
                height: 600,
                onInit: function (finder) {
                    finder.on('files:choose', function (event) {
                        if (event.data.files.length > 5 || event.data.files.length + total_images > 5) {
                            alert('Chỉ được phép thêm tối đa 5 ảnh');
                        }
                        else {
                            let file_uploaded = event.data.files['_byId'];
                            $('#no-data').remove();
                            $.each(file_uploaded, function (id, item) {
                                $('#uploaded_images_table').append(
                                    '<tr>' +
                                        '<td>'+i+'</td>' +
                                        '<td>' +
                                            '<input type="hidden" name="uploaded_images[]" id="uploaded_image_' + i + '" value="' + item['changed']['url'] + '">' +
                                            '<img id="image_preview_'+i+'" style="width: 300px; height: 300px" class="img-fluid" src="' + item['changed']['url'] + '">' +
                                        '</td>' +
                                        '<td id="image_src_'+i+'">' + item['changed']['url'] + '</td>' +
                                        '<td>' +
                                            '<button style="margin-right: 5px;" type="button" class="btn btn-primary" title="Thay đổi ảnh" onclick="edit_image(' + i + ')"><i class="fas fa-edit"></i></button>' +
                                            '<button class="btn btn-danger" type="button" title="Gỡ ảnh" onclick="remove_image(' + i + ')"><i class="fas fa-trash"></i></button>' +
                                        '</td>' +
                                    '</tr>'
                                );
                                images_uploaded[i] = item['changed']['url'];
                                i = i + 1;
                                total_images = total_images + 1;
                            })
                        }
                    })
                    finder.on('file:choose:resizedImage', function (event) {
                        if (total_images + 1 > 5) {
                            alert('Chỉ được phép thêm tối đa 5 ảnh');
                        }
                        else {
                            let file_uploaded = event.data.resizedUrl;
                            $('#no-data').remove();
                            $('#uploaded_images_table').append(
                                '<tr>' +
                                    '<td>'+i+'</td>' +
                                    '<td>' +
                                        '<input type="hidden" name="uploaded_images[]" id="uploaded_image_' + i + '" value="' + file_uploaded + '">' +
                                        '<img id="image_preview_'+i+'" style="width: 300px; height: 300px" class="img-fluid" src="' + file_uploaded + '">' +
                                    '</td>' +
                                    '<td id="image_src_'+i+'">' + file_uploaded + '</td>' +
                                    '<td>' +
                                        '<button style="margin-right: 5px;" type="button" class="btn btn-primary" title="Thay đổi ảnh" onclick="edit_image(' + i + ')"><i class="fas fa-edit"></i></button>' +
                                        '<button class="btn btn-danger" type="button" title="Gỡ ảnh" onclick="remove_image(' + i + ')"><i class="fas fa-trash"></i></button>' +
                                    '</td>' +
                                '</tr>'
                            );
                            images_uploaded[i] = file_uploaded;
                            i = i + 1;
                            total_images = total_images + 1;
                        }
                    })
                }
            });
        }

        function edit_image(id) {
            CKFinder.popup({
                chooseFiles: true,
                width: 1000,
                height: 600,
                onInit: function (finder) {
                    finder.on('files:choose', function (event) {
                        let file_uploaded = event.data.files.first();
                        $('#uploaded_image_'+id).val(file_uploaded.getUrl());
                        $('#image_preview_'+id).attr('src', file_uploaded.getUrl());
                        $('#image_src_'+id).html(file_uploaded.getUrl());

                        images_uploaded[id] = file_uploaded.getUrl();
                    })
                    finder.on('file:choose:resizedImage', function (event) {
                        let file_uploaded = event.data.resizedUrl;
                        $('#uploaded_image_'+id).val(file_uploaded);
                        $('#image_preview_'+id).attr('src', file_uploaded);
                        $('#image_src_'+id).html(file_uploaded);
                        images_uploaded[id] = file_uploaded;
                    })
                }
            });
        }

        function remove_image(id) {
            if (confirm('Bạn có chắc chắn muốn gỡ ảnh này?')) {
                total_images = total_images - 1;
                delete images_uploaded[id];

                $.ajax({
                    headers: {
                       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'get',
                    url: '{{ route('be.product.product_image_table') }}',
                    data: {
                        total_images: total_images,
                        uploaded_images: images_uploaded
                    },
                    dataType: 'json',
                    statusCode: {
                        500: function (response) {
                            console.log(JSON.parse(response.responseText));
                        },

                        200: function (response) {
                            $('#uploaded_images_table').html(response.view);
                            i = response.new_total_images;
                            images_uploaded = new Object();
                            response.images_uploaded.forEach(function (item,index) {
                                images_uploaded[index+1] = item;
                            })
                        }
                    }
                });
            }
        }

        function upload_size_image(input) {
            CKFinder.popup({
                chooseFiles: true,
                width: 1000,
                height: 600,
                onInit: function (finder) {
                    finder.on('files:choose', function (event) {
                        let file_uploaded = event.data.files.first();
                        input.value = file_uploaded.getUrl();
                        $('#size_image_preview_src').attr('src', file_uploaded.getUrl());
                        $('#size_image').siblings('.custom-file-label').html(file_uploaded.getUrl());
                    })
                    finder.on('file:choose:resizedImage', function (event) {
                        let file_uploaded = event.data.resizedUrl;
                        input.value = file_uploaded;
                        $('#size_image_preview_src').attr('src', file_uploaded);
                        $('#size_image').siblings('.custom-file-label').html(file_uploaded);
                    })
                }
            })
        }

        function preview_product(product_id) {
            let category_id = $('#category_id').val();
            let name = $('#name').val();
            let price = $('#price').val();
            let sale_price = $('#sale_price').val();
            let size_image = $('#size_image').val();
            let variants = new Object();
            let colors = $('#attribute_value_1').val();
            let sizes = $('#attribute_value_2').val();
            let i = 1;

            if (check_variants === 1) {
                colors.forEach(function (color) {
                    sizes.forEach(function (size) {
                        variants[i] = {'color_id': color, 'size_id': size};
                        i = i + 1;
                    })
                })
            }

            $.ajax({
               headers: {
                   'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
               },
               method: 'get',
               url: '{{ route('be.product.preview_product') }}',
               data: {
                   name: name,
                   category_id: category_id,
                   price: price,
                   sale_price: sale_price,
                   total_images: total_images,
                   images: images_uploaded,
                   size_image: size_image,
                   variants: variants,
                   product_id: product_id
               },
                dataType: 'json',
                statusCode: {
                   500: function (response) {
                       console.log(JSON.parse(response.responseText));
                   },

                    200: function (response) {
                        $('#size_image_src').attr('src', size_image);
                       $('#preview_modal').modal('show');
                       $('#preview_product').html(response.view);
                    }
                }
            });
        }

        function checkprice(e) {
            var price = $('#price').val();
            var sale_price = $('#sale_price').val();
            var price_length = price.split(',').length;
            var sale_price_length = sale_price.split(',').length;
            while (price_length !== 0 && sale_price_length !== 0) {
                price = price.replace(',', '');
                sale_price = sale_price.replace(',', '');
                price_length = price_length - 1;
                sale_price_length = sale_price_length - 1;
            }

            price = parseInt(price);
            sale_price = parseInt(sale_price);

            if (price <= sale_price) {
                alert('Giá khuyến mãi không được phép nhỏ hơn giá niêm yết');
                e.preventDefault();
            }

            if (Object.keys(images_uploaded).length === 0) {
                alert('Vui lòng thêm ít nhất một ảnh sản phẩm');
                e.preventDefault();
            }
        }
    </script>
@endsection
