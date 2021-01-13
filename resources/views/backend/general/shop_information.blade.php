@extends('backend.layout.page')

@section('title')
    Cài đặt thông tin cửa hàng
@endsection

@section('css')
    <style>
        iframe {
            width: 100%;
            height: 400px;
        }
    </style>
@endsection

@section('page-title')
    Cài đặt chung
@endsection

@section('right-navbar')
    <li class="breadcrumb-item" xmlns="http://www.w3.org/1999/html"><a href="{{ route('be.index') }}">Trang chủ</a></li>
    <li class="breadcrumb-item"><a href="#">Cài đặt chung</a></li>
    <li class="breadcrumb-item active">Cài đặt thông tin cửa hàng</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12 col-xs-12 col-xl-12 col-sm-12 col-md-12 col-xxl-12 col-lg-12">
            <form method="post" action="{{ route('be.general.update_shop_information') }}" enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="card card-primary">
                    <div class="card-header">
                        <h4 class="card-title">Cài đặt thông tin cửa hàng</h4>
                    </div>
                    <div class="card-body">
                        {{-- Shop name --}}
                        <div class="form-group">
                            <label>Tên cửa hàng</label>
                            <div class="input-group mb-3">
                                <input required oninvalid="this.setCustomValidity('Tên cửa hàng không được để trống')" oninput="this.setCustomValidity('')" placeholder="Tên cửa hàng" name="name" type="text" class="form-control" value="{{ $contact->name }}">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-info"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Shop phone number --}}
                        <div class="form-group">
                            <label>Số điện thoại cửa hàng</label>
                            <div class="input-group mb-3">
                                <input placeholder="Số điện thoại cửa hàng" required oninvalid="this.setCustomValidity('Số điện thoại cửa hàng không được để trống')" oninput="this.setCustomValidity('')" name="phone_number" type="text" class="form-control" value="{{ $contact->phone_number }}">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-phone"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Shop email --}}
                        <div class="form-group">
                            <label>Email cửa hàng</label>
                            <div class="input-group mb-3">
                                <input placeholder="Email cửa hàng" name="email" type="email" required oninvalid="this.setCustomValidity('Email không được để trống hoặc email sai định dạng')" oninput="this.setCustomValidity('')" class="form-control" value="{{ $contact->email }}">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- About Shop --}}
                        <div class="form-group">
                            <label>Mô tả ngắn về cửa hàng</label>
                            <div class="input-group mb-3">
                                <textarea required oninput="this.setCustomValidity('')" oninvalid="this.setCustomValidity('Mô tả cửa hàng không được để trống')" name="description" class="form-control">
                                    {!! $contact->description !!}
                                </textarea>
                            </div>
                        </div>

                        {{-- Shop Map --}}
                        <div class="form-group">
                            <label>Nhúng iframe bản đồ Google của cửa hàng</label>
                            <a target="_blank" style="display: block" href="https://vn4u.vn/2-cach-dua-ban-do-vao-website-chi-tiet-tung-buoc-mot/">Hướng dẫn lấy iframe bản đồ</a>
                            <textarea placeholder="Bản đồ Google của cửa hàng" onkeyup="getmap(this)" name="link" type="text" class="form-control" style="height: 150px">{{ $contact->link }}</textarea>
                        </div>

                        <div class="form-group" id="preview_map">
                            {!! $contact->link !!}
                        </div>

                        {{-- Facebook Script --}}
                        <div class="form-group">
                            <label>Đoạn mã của Facebook Chat Plugin</label>
                            <a target="_blank" href="https://wiki.matbao.net/kb/huong-dan-tich-hop-facebook-chat-vao-website-ma-khong-can-dung-plugin/" style="display: block">Hướng dẫn lấy bộ mã Facebook Chat Plugin của Fanpage Facebook</a>
                            <textarea placeholder="Facebook Chat Plugin" name="fb_script" class="form-control" style="height: 640px">{{ $contact->fb_script }}</textarea>
                        </div>

                        {{-- Shop Address --}}
                        <div class="form-group">
                            <label>Địa chỉ cửa hàng</label>
                            <div class="input-group mb-3">
                                <input placeholder="Địa chỉ cửa hàng" required oninput="this.setCustomValidity('')" oninvalid="this.setCustomValidity('Địa chỉ cửa hàng không được để trống')" name="address" type="text" class="form-control" value="{{ $contact->address }}">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-info"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Thành phố</label>
                            <select name="tinh_id" id="tinh_id" onchange="getHuyen()" class="form-control" required oninput="this.setCustomValidity('')" oninvalid="this.setCustomValidity('Thành phố không được để trống')">
                                <option value="">--- Chọn thành phố ---</option>
                                @foreach($tinhs as $tinh)
                                    @if ($tinh->id == $contact->tinh_id)
                                        <option value="{{ $tinh->id }}" selected>{{ $tinh->name }}</option>
                                    @else
                                        <option value="{{ $tinh->id }}">{{ $tinh->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group" id="huyen_box">
                            <label>Quận</label>
                            <select name="huyen_id" id="huyen_id" onchange="getXa()" class="form-control" required oninvalid="this.setCustomValidity('Quận không được để trống')" oninput="this.setCustomValidity('')">
                                <option value="">--- Chọn quận ---</option>
                                @foreach($huyens as $huyen)
                                    @if ($huyen->id == $contact->huyen_id)
                                        <option value="{{ $huyen->id }}" selected>{{ $huyen->name }}</option>
                                    @elseif ($huyen->tinh_id == $contact->tinh_id)
                                        <option value="{{ $huyen->id }}">{{ $huyen->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group" id="xa_box">
                            <label>Phường</label>
                            <select name="xa_id" id="xa_id" class="form-control" required oninvalid="this.setCustomValidity('Phường không được để trống')" oninput="this.setCustomValidity('')">
                                <option value="">--- Chọn Phường ---</option>
                                @foreach($xas as $xa)
                                    @if ($xa->id == $contact->xa_id)
                                        <option value="{{ $xa->id }}" selected>{{ $xa->name }}</option>
                                    @elseif ($xa->huyen_id == $contact->huyen_id)
                                        <option value="{{ $xa->id }}">{{ $xa->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Logo cửa hàng</label>
                            <div class="form-group">
                                <div class="custom-file">
                                    <input type="text" class="custom-file-input" id="logo" name="logo" onclick="upload_images('logo', this)">
                                    <label style="text-overflow: ellipsis; white-space: nowrap; overflow: hidden" for="logo" class="custom-file-label">{{ $contact->images }}</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <img class="img-fluid" style="width: 300px; height: 150px" id="preview_logo" src="{{ asset($contact->images) }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Logo nhỏ cửa hàng</label>
                            <div class="form-group">
                                <div class="custom-file">
                                    <input type="text" class="custom-file-input" id="small_logo" name="small_logo" onclick="upload_images('small_logo', this)">
                                    <label style="text-overflow: ellipsis; white-space: nowrap; overflow: hidden" for="small_logo" class="custom-file-label">{{ $contact->small_images }}</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <img class="img-fluid" style="width: 100px; height: 100px" id="preview_small_logo" src="{{ asset($contact->small_images) }}">
                            </div>
                        </div>

                    </div>
                    <div class="card-footer">
                        <span style="color: red; font-weight: 700">Nếu bạn muốn quay trở về ảnh ban đầu, vui lòng nhấn phím F5</span>
                        <button type="submit" class="btn btn-primary" style="float: right">Cập nhật</button>
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
            $('#thong-tin-cua-hang').addClass('active');
            $('#tinh_id').select2();
            $('#huyen_id').select2();
            $('#xa_id').select2();

            CKEDITOR.replace('description', {
                width: '100%',
                height: 200,
                filebrowserBrowseUrl: '{{ asset('ckfinder/ckfinder.html') }}',
                filebrowserImageBrowseUrl: '{{ asset('ckfinder/ckfinder.html?type=Images') }}',
                filebrowserUploadUrl: '{{ asset('ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files') }}',
                filebrowserImageUploadUrl: '{{ asset('ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images') }}',
            });
        }

        function getmap(input) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                },
                method: 'get',
                url: '{{ route('be.general.get_map') }}',
                data: { map : input.value },
                dataType: 'json',
                statusCode: {
                    500: function (response) {
                        console.log(JSON.parse(response.responseText));
                    },

                    200: function (response) {
                        $('#preview_map').html(response.view);
                    }
                }
            })
        }

        function getHuyen() {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: 'post',
                url: '{{ route('be.get_huyen') }}',
                data: {tinh_id : $('#tinh_id').val()},
                dataType: 'json',
                success: function (response) {
                    if (response.status === 200) {
                        $('#huyen_box').html(response.view);
                        $('#huyen_span').remove();
                        getXa();
                    }
                }
            });
        }

        function getXa() {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: 'post',
                url: '{{ route('be.get_xa') }}',
                data: { huyen_id : $('#huyen_id').val()},
                dataType: 'json',
                success: function (response) {
                    if (response.status === 200) {
                        $('#xa_box').html(response.view);
                        $('#xa_span').remove();
                    }
                }
            });
        }

        function upload_images(type, input) {
            CKFinder.popup({
                chooseFiles: true,
                width: 1200,
                height: 600,
                onInit: function (finder) {
                    finder.on('files:choose', function (event) {
                        let file_uploaded = event.data.files.first();
                        input.value = file_uploaded.getUrl();
                        $('#'+type).siblings('.custom-file-label').html(file_uploaded.getUrl());
                        $('#preview_'+type).attr('src', file_uploaded.getUrl());
                    })
                    finder.on('file:choose:resizedImage', function (event) {
                        input.value = event.data.resizedUrl;
                        $('#'+type).siblings('.custom-file-label').html(event.data.resizedUrl);
                        $('#preview_'+type).attr('src', event.data.resizedUrl);
                    })
                }
            });
        }
    </script>
@endsection
