@extends('backend.layout.page')

@section('title')
    Cài đặt ảnh trang chủ
@endsection

@section('css')

@endsection

@section('page-title')
    Cài đặt chung
@endsection

@section('right-navbar')
    <li class="breadcrumb-item"><a href="{{ route('be.index') }}">Trang chủ</a></li>
    <li class="breadcrumb-item"><a href="#">Cài đặt chung</a></li>
    <li class="breadcrumb-item active">Cài đặt ảnh trang chủ</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12 col-xxl-12 col-xl-12 col-sm-12 col-md-12 col-lg-12 col-xs-12">
            <form method="post" action="{{ route('be.general.update_home_images') }}" onsubmit="checkimages(event)">
                @csrf
                @method('put')
                <div class="card card-primary">
                    <div class="card-header">
                        <h4 class="card-title">Cập nhật ảnh trang chủ</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Ảnh trang chủ <span style="color: red">(Tối đa 5 ảnh)</span></label>
                            <div class="input-group mb-3">
                                <input type="text" id="images" onclick="upload_images(this)" class="custom-file-input">
                                <label for="images" class="custom-file-label">Chọn File</label>
                            </div>
                        </div>

                        <div class="form-group table-responsive">
                            <table class="table table-hover table-bordered dataTable dtr-inline text-center" style="width: 100% !important;">
                                <thead>
                                    <tr>
                                        <th>STT</th>
                                        <th>Ảnh</th>
                                        <th>Đường dẫn ảnh</th>
                                        <th>Nội dung</th>
                                        <th>Chức năng</th>
                                    </tr>
                                </thead>
                                <tbody id="uploaded_images_table">
                                    @foreach($home_images as $index=>$image)
                                        <tr>
                                            <input type="hidden" name="uploaded_images[]" id="uploaded_image_{{ $index+1 }}" value="{{ $image->images }}">
                                            <td>{{ $index+1 }}</td>
                                            <td><img src="{{ asset($image->images) }}" id="preview_image_{{ $index+1 }}" class="img-fluid" style="width: 400px; height: 300px"></td>
                                            <td id="image_src_{{ $index+1 }}">{{ $image->images }}</td>
                                            <td><textarea onblur="updateContent(this, {{ $index+1 }})" style="height: 200px" type="text" class="form-control" placeholder="Nội dung cho ảnh" id="content_image_{{ $index+1 }}" name="content_images[]">{{ $image->description }}</textarea></td>
                                            <td>
                                                <button title="Thay đổi ảnh" type="button" onclick="edit_image({{ $index+1 }})" class="btn btn-primary"><i class="fas fa-edit"></i></button>
                                                <button title="Gỡ ảnh" type="button" onclick="remove_image({{ $index+1 }})" class="btn btn-danger"><i class="fas fa-trash"></i></button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <span style="color: red; font-weight: 700">Nếu bạn muốn quay trở về ảnh ban đầu, vui lòng nhấn phím F5</span>
                        <button type="submit" style="float: right" class="btn btn-primary">Cập nhật</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
    <script>
        function activelink() {
            $('.cai-dat-chung').addClass('menu-open');
            $('#cai-dat-chung').addClass('active');
            $('#anh-trang-chu').addClass('active');
        }

        let j = 1;
        let i = {{ count($home_images)+1 }};
        let total_images = {{ count($home_images) }};
        let images_uploaded = new Object();
        let uploaded_contents = new Object();

        while (j <= total_images) {
            images_uploaded[j] = $('#uploaded_image_'+j).val();
            uploaded_contents[j] = $('#content_image_'+j).val();
            j = j + 1;
        }

        function upload_images(input) {
            CKFinder.popup({
                chooseFiles: true,
                width: 1200,
                height: 600,
                onInit: function (finder) {
                    finder.on('files:choose', function (event) {
                        if (event.data.files.length > 5 || total_images + event.data.files.length > 5) {
                            alert('Chỉ được phép thêm tối đa 5 ảnh');
                        }
                        else {
                            let file_uploaded = event.data.files['_byId'];
                            $.each(file_uploaded, function (id, item) {
                                $('#uploaded_images_table').append(
                                    '<tr>' +
                                        '<input type="hidden" name="uploaded_images[]" id="uploaded_image_' + i + '" value="' + item['changed']['url'] + '">' +
                                        '<td>'+i+'</td>' +
                                        '<td><img id="preview_image_'+i+'" style="width: 400px; height: 300px" src="' + item['changed']['url'] + '"></td>' +
                                        '<td id="image_src_'+i+'">'+ item['changed']['url'] + '</td>' +
                                        '<td><textarea onblur="updateContent(this, '+i+')" style="height: 200px" type="text" class="form-control" placeholder="Nội dung cho ảnh" id="content_image_'+i+'" name="content_images[]"></textarea></td>' +
                                        '<td>' +
                                            '<button title="Thay đổi ảnh" type="button" class="btn btn-primary" style="margin-right: 5px;" onclick="edit_image(' + i + ')"><i class="fas fa-edit"></i></button>' +
                                            '<button title="Gỡ ảnh" type="button" class="btn btn-danger" onclick="remove_image(' + i + ')"><i class="fas fa-trash"></i></button>' +
                                        '</td>' +
                                    '</tr>'
                                );
                                images_uploaded[i] = item['changed']['url'];
                                uploaded_contents[i] = "";
                                i = i + 1;
                                total_images = total_images + 1;
                            })
                        }
                    });
                    finder.on('file:choose:resizedImage', function(event) {
                        if (total_images + 1 > 5) {
                            alert('Chỉ được thêm tối đa 5 ảnh');
                        }
                        else {
                            let file_uploaded = event.data.resizedUrl;
                            $('#uploaded_images_table').append(
                                '<tr>' +
                                    '<input type="hidden" name="uploaded_images[]" id="uploaded_image_' + i + '" value="' + file_uploaded + '">' +
                                    '<td>'+i+'</td>' +
                                    '<td><img id="preview_image_'+i+'" style="width: 400px; height: 300px" src="' + file_uploaded + '"></td>' +
                                    '<td id="image_src_'+i+'">'+ file_uploaded + '</td>' +
                                    '<td><textarea onblur="updateContent(this, '+i+')" style="height: 200px" type="text" class="form-control" placeholder="Nội dung cho ảnh" id="content_image_'+i+'" name="content_images[]"></textarea></td>' +
                                    '<td>' +
                                        '<button title="Thay đổi ảnh" type="button" class="btn btn-primary" style="margin-right: 5px;" onclick="edit_image(' + i + ')"><i class="fas fa-edit"></i></button>' +
                                        '<button title="Gỡ ảnh" type="button" class="btn btn-danger" onclick="remove_image(' + i + ')"><i class="fas fa-trash"></i></button>' +
                                    '</td>' +
                                '</tr>'
                            );
                            images_uploaded[i] = file_uploaded;
                            uploaded_contents[i] = "";
                            i = i + 1;
                            total_images = total_images + 1;
                        }
                    })
                }
            });
        }

        function edit_image(id) {
            let content = uploaded_contents[id];
            CKFinder.popup({
                chooseFiles: true,
                width: 1200,
                height: 600,
                onInit: function (finder) {
                    finder.on('files:choose', function (event) {
                        let file_uploaded = event.data.files.first();
                        $('#uploaded_image_'+id).val(file_uploaded.getUrl());
                        $('#image_src_'+id).html(file_uploaded.getUrl());
                        $('#preview_image_'+id).attr('src', file_uploaded.getUrl());
                        $('#content_image_'+id).val(content);
                        images_uploaded[id] = file_uploaded.getUrl();
                    })
                    finder.on('file:choose:resizedImage', function (event) {
                        let file_uploaded = event.data.resizedUrl;
                        $('#uploaded_image_'+id).val(file_uploaded);
                        $('#image_src_'+id).html(file_uploaded);
                        $('#preview_image_'+id).attr('src', file_uploaded);
                        $('#content_image_'+id).val(content);
                        images_uploaded[id] = file_uploaded;
                    })
                }
            })
        }

        function updateContent(input, id) {
            uploaded_contents[id] = input.value;
        }

        function remove_image(id) {
            if (confirm('Bạn có chắc chắn muốn gỡ ảnh này?')) {
                delete images_uploaded[id];
                delete uploaded_contents[id];
                total_images = total_images - 1;
                $.ajax({
                    headers: {
                       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'get',
                    url: '{{ route('be.general.home_image_table') }}',
                    data: {
                        total_images: total_images,
                        uploaded_images: images_uploaded,
                        uploaded_contents: uploaded_contents
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
                            j = response.new_total_images;
                            uploaded_contents = new Object();
                            response.uploaded_contents.forEach(function (item,index) {
                                uploaded_contents[index+1] = item;
                            })
                            console.log(images_uploaded);
                            console.log(uploaded_contents);
                        }
                    }
                });
            }
        }

        function checkimages(e) {
            if (Object.keys(images_uploaded).length === 0) {
                alert('Vui lòng thêm ít nhất 1 ảnh');
                e.preventDefault();
            }
        }
    </script>
@endsection
