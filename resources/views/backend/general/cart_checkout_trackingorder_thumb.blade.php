@extends('backend.layout.page')

@section('title')
    Cài đặt ảnh cho giỏ hàng, thanh toán và theo dõi đơn hàng
@endsection

@section('css')

@endsection

@section('page-title')
    Cài đặt chung
@endsection

@section('right-navbar')
    <li class="breadcrumb-item"><a href="{{ route('be.index') }}">Trang chủ</a></li>
    <li class="breadcrumb-item"><a href="#">Cài đặt chung</a></li>
    <li class="breadcrumb-item active">Cài đặt ảnh cho giỏ hàng, thanh toán và theo dõi đơn hàng</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12 col-xs-12 col-xl-12 col-sm-12 col-md-12 col-xxl-12 col-lg-12">
            <form method="post" action="{{ route('be.general.update_cart_checkout_trackingorder_thumb') }}" enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="card card-primary">
                    <div class="card-header">
                        Cài đặt ảnh đại diện cho giỏ hàng, thanh toán và theo dõi đơn hàng
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <button class="form-control btn btn-primary" data-toggle="collapse" data-target="#cart_thumb_collapse" type="button">Giỏ hàng</button>
                            <div class="collapse show" id="cart_thumb_collapse">
                                <div class="card card-body">
                                    <div class="form-group">
                                        <label for="cart_image">Ảnh đại diện</label>
                                        <div class="custom-file">
                                            <input type="text" class="custom-file-input" name="cart" id="cart" onclick="upload_thumb('cart',this)">
                                            <label for="cart" style="text-overflow: ellipsis; white-space: nowrap; overflow: hidden" class="custom-file-label">{{ \Illuminate\Support\Facades\DB::table('generals')->where('type', '=', 'cart-thumb')->first()->images }}</label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <img id="preview_cart" src="{{ asset(\Illuminate\Support\Facades\DB::table('generals')->where('type', '=', 'cart-thumb')->first()->images) }}" class="img-fluid" style="width: 100%; height: 300px">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button class="form-control btn btn-primary" type="button" data-toggle="collapse" data-target="#checkout_thumb_collapse">Thanh toán</button>
                            <div class="collapse show" id="checkout_thumb_collapse">
                                <div class="card card-body">
                                    <div class="form-group">
                                        <label for="checkout">Ảnh đại diện</label>
                                        <div class="custom-file">
                                            <input type="text" class="custom-file-input" name="checkout" id="checkout" onclick="upload_thumb('checkout',this)">
                                            <label for="checkout" style="text-overflow: ellipsis; white-space: nowrap; overflow: hidden" class="custom-file-label">{{ \Illuminate\Support\Facades\DB::table('generals')->where('type', '=', 'checkout-thumb')->first()->images }}</label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <img id="preview_checkout" src="{{ asset(\Illuminate\Support\Facades\DB::table('generals')->where('type', '=', 'checkout-thumb')->first()->images) }}" class="img-fluid" style="width: 100%; height: 300px">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button class="form-control btn btn-primary" data-toggle="collapse" type="button" data-target="#trackingorder_thumb_collapse">Theo dõi đơn hàng</button>
                            <div class="collapse show" id="trackingorder_thumb_collapse">
                                <div class="card card-body">
                                    <div class="form-group">
                                        <label for="trackingorder_image">Ảnh đại diện</label>
                                        <div class="custom-file">
                                            <input type="text" class="custom-file-input" name="trackingorder" id="trackingorder" onclick="upload_thumb('trackingorder',this)" accept="image/png, image/jpeg, image/jpg">
                                            <label style="text-overflow: ellipsis; white-space: nowrap; overflow: hidden" for="trackingorder" class="custom-file-label">{{ \Illuminate\Support\Facades\DB::table('generals')->where('type', '=', 'trackingorder-thumb')->first()->images }}</label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <img id="preview_trackingorder" src="{{ asset(\Illuminate\Support\Facades\DB::table('generals')->where('type', '=', 'trackingorder-thumb')->first()->images) }}" class="img-fluid" style="width: 100%; height: 300px">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <span style="font-weight: 700; color: red">Nếu bạn muốn quay trở về ảnh ban đầu, vui lòng nhấn phím F5</span>
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
            $('#anh-gio-hang-don-hang').addClass('active');
        }

        function upload_thumb(type, input) {
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
