@php
    $change_select = 1;
@endphp

@extends('backend.layout.page')

@section('title')
    Thêm sản phẩm
@endsection

@section('css')
    <style>
        @media (max-width: 1024px) {
            #preview_product_button {
                display: none;
            }
            #reset_product_button {
                margin-right: 10px;
            }
        }

        .modal {
            overflow: auto !important;
        }

        @media (min-width: 1600px) {
            .preview_modal {
                margin-left: -125%;
                width: 1775px !important;
            }
        }
    </style>
@endsection

@section('page-title')
    Quản lý sản phẩm
@endsection

@section('right-navbar')
    <li class="breadcrumb-item"><a href="{{ route('be.index') }}">Trang chủ</a></li>
    <li class="breadcrumb-item"><a href="{{ route('be.product.index') }}">Quản lý sản phẩm</a></li>
    <li class="breadcrumb-item active">Thêm sản phẩm</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12 col-sm-12 col-xl-12 col-md-12 col-lg-12">
            <form method="post" action="{{ route('be.product.store') }}" enctype="multipart/form-data" onsubmit="checkdata(event)">
                @csrf
                <div class="card card-primary">
                    <div class="card-header">
                        <h4 class="card-title">Thêm sản phẩm</h4>
                    </div>
                    <div class="card-body">
                        {{-- Category --}}
                        <div class="form-group">
                            <label>Danh mục sản phẩm <span style="color: red">(*)</span></label>
                            <select class="form-control" name="category_id" id="category_id" onchange="getAttributevalues()" required oninvalid="this.setCustomValidity('Vui lòng chọn danh mục')" oninput="this.setCustomValidity('')">
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

                        {{-- Name --}}
                        <div class="form-group">
                            <label>Tên sản phẩm <span style="color: red">(*)</span></label>
                            <div class="input-group mb-3">
                                <input onkeyup="getSlug()" class="form-control" placeholder="Tên sản phẩm" type="text" name="name" id="name" required oninvalid="this.setCustomValidity('Vui lòng nhập tên sản phẩm')" value="{{ old('name') }}" oninput="this.setCustomValidity('')">
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
                                <input readonly placeholder="Đường dẫn tĩnh của sản phẩm" class="form-control" type="text" value="{{ old('slug') }}" name="slug" id="slug">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-info"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Price --}}
                        <div class="form-group">
                            <label>Giá sản phẩm (Đơn vị: vnđ) <span style="color:red;">(*)</span></label>
                            <div class="input-group mb-3">
                                <input placeholder="Giá sản phẩm" type="text" id="price" name="price" class="form-control" required oninvalid="this.setCustomValidity('Vui lòng nhập giá sản phẩm')" oninput="this.setCustomValidity('')">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-money-bill-alt"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Sale Price --}}
                        <div class="form-group">
                            <label>Giá khuyến mãi nếu có (Đơn vị: vnđ)</label>
                            <div class="input-group mb-3">
                                <input placeholder="Giá khuyến mãi (Nếu có)" type="text" id="sale_price" name="sale_price" class="form-control">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-money-bill-alt"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Attributes --}}
                        @foreach($attributes as $attribute)
                            <div class="form-group">
                                <label>{{ $attribute->name }} <span style="color: red">(*)</span></label>
                                <select class="form-control" id="attribute_value_{{ $attribute->id }}" name="attribute_value_{{ $attribute->id }}[]" multiple required oninvalid="this.setCustomValidity('Vui lòng thêm giá trị thuộc tính')" oninput="this.setCustomValidity('')">
                                        <option disabled>--- Vui lòng chọn danh mục trước ---</option>
                                </select>
                            </div>
                        @endforeach

                        {{-- Create Variants Button --}}
                        <div class="form-group">
                            <button type="button" onclick="createVariants()" class="btn btn-primary">Tạo biến thể</button>
                            <button style="display: none" type="button" id="add_quantity" class="btn btn-primary">Thêm số lượng cho tất cả các biến thể</button>
                            <button style="display: none" type="button" id="remove_quantity" class="btn btn-danger">Xóa số lượng cho tất cả các biến thể</button>
                        </div>

                        {{-- Variants Box --}}
                        <div class="form-group" id="variants_box">
                        </div>

                        {{-- Images --}}
                        <div class="form-group">
                            <label>Ảnh sản phẩm <span style="color: red">(Không quá 5 ảnh) (*)</span></label>
                            <div class="custom-file">
                                <input type="text" class="custom-file-input" id="detail_image" onclick="upload_image()">
                                <label class="custom-file-label" for="images">Chọn File</label>
                            </div>
                        </div>

                        {{-- Preview Images Uploaded --}}
                        <div class="form-group">
                            <table class="text-center dataTable table table-bordered table-hover dtr-inline">
                                <thead>
                                    <tr>
                                        <th>STT</th>
                                        <th>Ảnh</th>
                                        <th>Đường dẫn ảnh</th>
                                        <th>Chức năng</th>
                                    </tr>
                                </thead>
                                <tbody id="uploaded_images_table">
                                    <tr id="no-data">
                                        <td colspan="4">Không có dữ liệu</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        {{-- Size Images --}}
                        <div class="form-group">
                            <label>Ảnh kích cỡ sản phẩm <span style="color: red">(*)</span></label>
                            <div class="custom-file">
                                <input type="text" class="custom-file-input" id="size_image" name="size_image" onclick="upload_size_image(this)">
                                <label class="custom-file-label" for="size_image">Chọn File</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <img class="img-fluid" style="width: 100%; height: 400px;display: none" id="uploaded_size_image">
                        </div>

                    </div>
                    {{-- /.card-body --}}
                    <div class="card-footer">
                        <button type="submit" style="float: right;" class="btn btn-primary">Thêm sản phẩm</button>
                        <button type="button" class="btn btn-primary" id="preview_product_button" style="float: right; margin: 0 10px 0 10px" onclick="preview_product()">Xem trước</button>
                        <button type="reset" id="reset_product_button" style="float: right;" class="btn btn-danger">Nhập lại</button>
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
                        <button type="button" class="close" onclick="$('#preview_modal').modal('hide'); $('#size_image_preview').modal('hide')">&times;</button>
                    </div>
                    <div class="modal-body" id="preview_product">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" onclick="$('#preview_modal').modal('hide'); $('#size_image_preview').modal('hide')">Đóng</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" role="dialog" id="size_image_preview" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Bảng kích cỡ</h4>
                        <button type="button" class="close" onclick="$('#size_image_preview').modal('hide')">&times;</button>
                    </div>
                    <div class="modal-body text-center">
                        <img id="size_img" class="img-fluid" style="width: 100%; height: 300px">
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
            $('#them-san-pham').addClass('active');
            $('#category_id').select2();
            $('#price').simpleMoneyFormat();
            $('#sale_price').simpleMoneyFormat();
            $('#multiple').select2();
            @foreach($attributes as $attribute)
                $('#attribute_value_{{ $attribute->id }}').select2();
            @endforeach

            @if (session()->has('error'))
                alert('{{ session()->get('error') }}');
            @endif
        }

        function getSlug() {
            $.ajax({
               header: {
                   'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
               },
                method: 'get',
                url: '{{ route('be.product.get_slug') }}',
                data: {name : $('#name').val()},
                dataType: 'json',
                statusCode: {
                    500: function (response) {
                        console.log(response.responseText);
                    },

                    200: function (response) {
                        $('#slug').val(response.slug);
                    }
                }
            });
        }

        let i = 1;
        let total_images = 0;
        let images_uploaded = new Object();

        function upload_image() {
            CKFinder.popup({
                chooseFiles: true,
                width: 1200,
                height: 600,
                onInit: function (finder) {
                    finder.on('files:choose', function (event) {
                        if (event.data.files.length > 5 || total_images + event.data.files.length > 5) {
                            alert('Chỉ được phép thêm tối đa 5 ảnh')
                        }
                        else {
                            let file_uploaded = event.data.files['_byId'];
                            $('#no-data').remove();
                            $.each(file_uploaded, function (id, item) {
                                $('#uploaded_images_table').append(
                                    '<tr>' +
                                        '<input type="hidden" id="uploaded_image_'+i+'" name="uploaded_images[]" value="'+item['changed']['url']+'">' +
                                        '<td>'+i+'</td>' +
                                        '<td>' +
                                            '<img class="img-fluid" id="preview_image_'+i+'" src="' + item['changed']['url'] + '" style="width: 300px; height: 300px">' +
                                        '</td>' +
                                        '<td id="image_src_'+i+'">' + item['changed']['url'] + '</td>' +
                                        '<td>' +
                                            '<button title="Thay đổi ảnh" style="margin-right: 5px" type="button" class="btn btn-primary" onclick="edit_image(' + i + ')"><i class="fas fa-edit"></i></button>' +
                                            '<button title="Gỡ ảnh" type="button" class="btn btn-danger" onclick="remove_image(' + i + ')"><i class="fas fa-trash"></i></button>' +
                                        '</td>' +
                                    '</tr>'
                                );
                                images_uploaded[i] = item['changed']['url'];
                                total_images = total_images + 1;
                                i = i + 1;
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
                                    '<input type="hidden" name="uploaded_images[]" id="uploaded_image_' + i + '" value="' + file_uploaded + '">' +
                                    '<td>'+i+'</td>' +
                                    '<td>' +
                                        '<img class="img-fluid" id="preview_image_'+i+'" src="' + file_uploaded + '" style="width: 300px; height: 300px">' +
                                    '</td>' +
                                    '<td id="image_src_'+i+'">' + file_uploaded + '</td>' +
                                    '<td>' +
                                        '<button title="Thay đổi ảnh" style="margin-right: 5px" type="button" class="btn btn-primary" onclick="edit_image(' + i + ')"><i class="fas fa-edit"></i></button>' +
                                        '<button title="Gỡ ảnh" type="button" class="btn btn-danger" onclick="remove_image(' + i + ')"><i class="fas fa-trash"></i></button>' +
                                    '</td>' +
                                '</tr>'
                            );
                            images_uploaded[i] = file_uploaded;
                            total_images = total_images + 1;
                            i = i + 1;
                        }
                    })
                }
            })
        }

        function edit_image(id) {
            CKFinder.popup({
                chooseFiles: true,
                width: 12000,
                height: 600,
                onInit: function (finder) {
                    finder.on('files:choose', function (event) {
                        let file_uploaded = event.data.files.first();
                        $('#uploaded_image_'+id).val(file_uploaded.getUrl());
                        $('#image_src_'+id).html(file_uploaded.getUrl());
                        $('#preview_image_'+id).attr('src', file_uploaded.getUrl());
                        images_uploaded[id] = file_uploaded.getUrl();
                    })
                    finder.on('file:choose:resizedImage', function (event) {
                        let file_uploaded = event.data.resizedUrl;
                        $('#uploaded_image_'+id).val(file_uploaded);
                        $('#image_src_'+id).html(file_uploaded);
                        $('#preview_image_'+id).attr('src', file_uploaded);
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
                        total_images : total_images,
                        uploaded_images : images_uploaded
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
               width: 1200,
               height: 600,
               onInit: function (finder) {
                   finder.on('files:choose', function (event) {
                       let file_uploaded = event.data.files.first();
                       input.value = file_uploaded.getUrl();
                       $('#size_image').siblings('.custom-file-label').html(file_uploaded.getUrl());
                       $('#uploaded_size_image').attr('src', file_uploaded.getUrl()).css('display', '');
                   })
                   finder.on('file:choose:resizedImage', function (event) {
                       let file_uploaded = event.data.resizedUrl;
                       input.value = file_uploaded;
                       $('#size_image').siblings('.custom-file-label').html(file_uploaded);
                       $('#uploaded_size_image').attr('src', file_uploaded).css('display', '');
                   })
               }
            });
        }

        function getAttributevalues() {
            if ($('#category_id').val() === "") {
                $('#attribute_value_1').html('<option disabled>--- Vui lòng chọn danh mục trước ---</option>');
                $('#attribute_value_2').html('<option disabled>--- Vui lòng chọn danh mục trước ---</option>');
            }
            else {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'get',
                    url: '{{ route('be.product.get_attribute_values') }}',
                    data: {category_id : $('#category_id').val()},
                    dataType: 'json',
                    statusCode: {
                        500: function (response) {
                            console.log(response.responseText);
                        },

                        400: function (response) {
                            var responseText = JSON.parse(response.responseText);
                            alert(responseText['message']);
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
            if ($('#attribute_value_1').val().length === 0 || $('#attribute_value_2').val().length === 0) {
                alert('Vui lòng nhập giá trị thuộc tính trước');
                $('#add_quantity').css('display', 'none');
            }
            else if (confirm('Bạn chắc chắn muốn tạo biến thể này không? Lưu ý: Nếu có lỗi ở một vài biến thể bạn sẽ phải tạo lại từ đầu')){
                var colors = $('#attribute_value_1').val();
                var sizes = $('#attribute_value_2').val();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'get',
                    url: '{{ route('be.product.create_variants') }}',
                    data: {
                        colors : colors,
                        sizes : sizes
                    },
                    dataType: 'json',
                    statusCode: {
                        500: function (response) {
                            console.log(response.responseText);
                        },

                        400: function (response) {
                            console.log(response.responseText);
                        },

                        200: function (response) {
                            check_variants = 1;

                            $('#variants_box').html(response.view);

                            $('#add_quantity').css('display', '').unbind('click').click(function () {
                                addQuantity(response.total);
                            });
                            $('#remove_quantity').css('display', '').unbind('click').click(function () {
                                removeQuantity(response.total);
                            });
                            alert(response.message);
                        }
                    }
                });
            }
        }

        function addQuantity(total) {
            var quantity = prompt('Số lượng: ');
            if (quantity !== null) {
                for (let i = 1; i <= total; i++) {
                    if ($('#quantity_'+i).val().length === 0) {
                        $('#quantity_'+i).val(quantity);
                    }
                }
                alert('Thêm số lượng thành công ');
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

        function preview_product() {
            let colors = $('#attribute_value_1').val();
            let sizes = $('#attribute_value_2').val();
            let variants = new Object();
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
                    name: $('#name').val(),
                    price: $('#price').val(),
                    sale_price: $('#sale_price').val(),
                    total_images: total_images,
                    images: images_uploaded,
                    variants: variants
                },
                dataType: 'json',
                statusCode: {
                    500: function (response) {
                        console.log(JSON.parse(response.responseText));
                    },

                    200: function (response) {
                        $('#size_img').attr('src', $('#size_image').val());
                        $('#preview_modal').modal('show');
                        $('#preview_product').html(response.view);
                    }
                }
            });
        }

        function checkdata(e) {
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

            else if (typeof $('#quantity_1').val() === 'undefined') {
                alert('Vui lòng tạo biến thể cho sản phẩm');
                e.preventDefault();
            }

            if (Object.keys(images_uploaded).length === 0) {
                alert('Vui lòng thêm ít nhất 1 ảnh sản phẩm');
                e.preventDefault();
            }

            else if ($('#size_image').val() === "") {
                alert('Vui lòng thêm ảnh kích cỡ sản phẩm');
                e.preventDefault();
            }
        }
    </script>
@endsection
