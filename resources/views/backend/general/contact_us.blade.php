@extends('backend.layout.page')

@section('title')
    Cài đặt biểu mẫu liên hệ
@endsection

@section('css')

@endsection

@section('page-title')
    Cài đặt chung
@endsection

@section('right-navbar')
    <li class="breadcrumb-item"><a href="{{ route('be.index') }}">Trang chủ</a></li>
    <li class="breadcrumb-item"><a href="#">Cài đặt chung</a></li>
    <li class="breadcrumb-item active">Cài đặt biểu mẫu liên hệ</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12 col-lg-12 col-xs-12 col-md-12 col-sm-12 col-xxl-12 col-xl-12">
            <form method="post" action="{{ route('be.general.update_contact_us') }}">
                @csrf
                @method('put')
                <div class="card card-primary">
                    <div class="card-header">
                        <h4 class="card-title">Cài đặt biểu mẫu liên hệ</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="thumb">Ảnh đại diện</label>
                            <div class="custom-file">
                                <input class="custom-file-input" id="thumb" name="thumb" onclick="upload_thumb(this)" value="{{ $data->images }}">
                                <label style="text-overflow: ellipsis; white-space: nowrap; overflow: hidden" for="thumb" class="custom-file-label">{{ $data->images }}</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <img class="img-fluid" style="width: 100%; height: 400px" src="{{ asset($data->images) }}" id="preview_thumb">
                        </div>

                        <div class="form-group">
                            <label for="link">Iframe biểu mẫu Google <span style="color: red">(Vui lòng cài đặt width="xxx" thành width="100%")</span></label>
                            <a target="_blank" href="https://www.hostinger.vn/huong-dan/chen-google-form-vao-wordpress/" style="display: block">Hướng dẫn lấy Iframe biểu mẫu Google</a>
                            <div class="input-group mb-3">
                                <textarea style="height: 100px" class="form-control" name="link" onkeyup="getDocs(this)" placeholder="Iframe biểu mẫu Google">{{ $data->link }}</textarea>
                            </div>
                        </div>

                        <div class="form-group" id="docs">
                            {!! $data->link !!}
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" style="float:right;" class="btn btn-primary">Cập nhật</button>
                        <button type="reset" style="float: right; margin-right: 10px" class="btn btn-danger">Nhập lại</button>
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
            $('#bieu-mau-lien-he').addClass('active');
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

        function getDocs(input) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                },
                method: 'get',
                url: '{{ route('be.general.get_docs') }}',
                data: { docs : input.value },
                dataType: 'json',
                statusCode: {
                    500: function (response) {
                        console.log(JSON.parse(response.responseText));
                    },

                    200: function (response) {
                        $('#docs').html(response.view);
                    }
                }
            });
        }
    </script>
@endsection


