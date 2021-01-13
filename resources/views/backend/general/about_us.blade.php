@extends('backend.layout.page')

@section('title')
    Giới thiệu cửa hàng
@endsection

@section('css')

@endsection

@section('page-title')
    Cài đặt chung
@endsection

@section('right-navbar')
    <li class="breadcrumb-item"><a href="{{ route('be.index') }}">Trang chủ</a></li>
    <li class="breadcrumb-item"><a href="#">Cài đặt chung</a></li>
    <li class="breadcrumb-item active">Giới thiệu cửa hàng</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12 col-xl-12 col-sm-12 col-md-12 col-xxl-12 col-xxl-12 col-xs-12 col-lg-12">
            <form method="post" action="{{ route('be.general.update_about_us') }}" enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="card card-primary">
                    <div class="card-header">
                        <h4 class="card-title">Giới thiệu của hàng</h4>
                    </div>
                    <div class="card-body">
                        {{-- Thumb with CkFinder --}}
                        <div class="form-group">
                            <label>Ảnh đại diện</label>
                            <div class="custom-file">
                                <input type="text" class="custom-file-input" name="thumb" id="thumb" value="{{ $data->images }}" onclick="upload_thumb(this)">
                                <label style="text-overflow: ellipsis; white-space: nowrap; overflow: hidden" for="thumb" class="custom-file-label">{{ $data->images }}</label>
                            </div>
                        </div>

                        {{-- Old Thumb or Uploaded Thumb --}}
                        <div class="form-group">
                            <img class="img-fluid" style="width: 100%" id="uploaded_image" src="{{ asset($data->images) }}">
                        </div>

                        {{-- About us --}}
                        <div class="form-group">
                            <label>Giới thiệu về cửa hàng</label>
                            <div class="input-group mb-3">
                                <textarea class="form-control" placeholder="Giới thiệu về cửa hàng" name="description" id="description" required oninput="this.setCustomValidity('')" oninvalid="this.setCustomValidity('Vui lòng nhập giới thiệu về cửa hàng')">{!! $data->description !!}</textarea>
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
            $('#gioi-thieu-cua-hang').addClass('active');
            CKEDITOR.replace('description', {
                width: '100%',
                height: '500px',
                filebrowserBrowseUrl: '{{ asset('ckfinder/ckfinder.html') }}',
                filebrowserImageBrowseUrl: '{{ asset('ckfinder/ckfinder.html?type=Images') }}',
                filebrowserUploadUrl: '{{ asset('ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files') }}',
                filebrowserImageUploadUrl: '{{ asset('ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images') }}',
            });
        }

        function upload_thumb(input) {
            CKFinder.popup({
                chooseFiles: true,
                width: 1200,
                height: 600,
                onInit: function (finder) {
                    finder.on('files:choose', function (event) {
                        let file_uploaded = event.data.files.first();
                        input.value = file_uploaded.getUrl();
                        $('#thumb').siblings('.custom-file-label').html(file_uploaded.getUrl());
                        $('#uploaded_image').attr('src', file_uploaded.getUrl());
                    })
                    finder.on('file:choose:resizedImage', function(event) {
                        input.value = event.data.resizedUrl;
                        $('#thumb').siblings('.custom-file-label').html(event.data.resizedUrl);
                        $('#uploaded_image').attr('src', event.data.resizedUrl);
                    })
                }
            });
        }
    </script>
@endsection
