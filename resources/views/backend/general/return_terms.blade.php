@extends('backend.layout.page')

@section('title')
    Cài đặt trang chính sách bảo hành và đổi trả
@endsection

@section('css')

@endsection

@section('page-title')
    Cài đặt chung
@endsection

@section('right-navbar')
    <li class="breadcrumb-item"><a href="{{ route('be.index') }}">Trang chủ</a></li>
    <li class="breadcrumb-item"><a href="#">Cài đặt chung</a></li>
    <li class="breadcrumb-item active">Cài đặt trang chính sách bảo hành và đổi trả</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12 col-xl-12 col-sm-12 col-md-12 col-xs-12 col-xxl-12 col-lg-12">
            <form method="post" action="{{ route('be.general.update_return_terms') }}">
                @csrf
                @method('put')
                <div class="card card-primary">
                    <div class="card-header">
                        <h4 class="card-title">Cài đặt trang chính sách bảo hành và đổi trả</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="thumb">Ảnh đại diện</label>
                            <div class="custom-file">
                                <input class="custom-file-input" id="thumb" name="thumb" value="{{ $data->images }}" onclick="upload_thumb(this)">
                                <label style="text-overflow: ellipsis; white-space: nowrap; overflow: hidden" for="thumb" class="custom-file-label">{{ $data->images }}</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <img class="img-fluid" style="width: 100%; height: 400px" src="{{ asset($data->images) }}" id="preview_thumb">
                        </div>

                        <div class="form-group">
                            <label for="description">Nội dung trang</label>
                            <div class="input-group mb-3">
                                <textarea class="form-control" id="description" name="description" placeholder="Nội dung trang">{{ $data->description }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary" style="float: right">Cập nhật</button>
                        <button type="reset" class="btn btn-danger" style="float:right; margin-right: 10px;">Nhập lại</button>
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
            $('#chinh-sach-bao-hanh-va-doi-tra').addClass('active');
            CKEDITOR.replace('description', {
                width: '100%',
                height: 500,
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
                        $('#preview_thumb').attr('src', file_uploaded.getUrl());
                    })
                    finder.on('file:choose:resizedImage', function (event) {
                        let file_uploaded = event.data.resizedUrl;
                        input.value = file_uploaded;
                        $('#thumb').siblings('.custom-file-label').html(file_uploaded);
                        $('#preview_thumb').attr('src', file_uploaded);
                    })
                }
            });
        }
    </script>
@endsection
