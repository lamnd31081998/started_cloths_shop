@extends('backend.layout.page')

@section('title')
    Cài đặt thông tin mạng xã hội
@endsection

@section('css')

@endsection

@section('page-title')
    Cài đặt chung
@endsection

@section('right-navbar')
    <li class="breadcrumb-item"><a href="{{ route('be.index') }}">Trang chủ</a></li>
    <li class="breadcrumb-item"><a href="#">Cài đặt chung</a></li>
    <li class="breadcrumb-item active">Cài đặt thông tin mạng xã hội</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12 col-lg-12 col-xxl-12 col-md-12 col-sm-12 col-xl-12 col-xs-12">
            <form method="post" action="{{ route('be.general.update_social_links') }}">
                @csrf
                @method('put')
                <div class="card card-primary">
                    <div class="card-header">
                        <h4 class="card-title">Cài đặt thông tin mạng xã hội</h4>
                    </div>
                    <div class="card-body">
                        @foreach($social_links as $index=>$social_link)
                            <div class="form-group">
                                <label>{{ $social_link->name }}</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            https://
                                        </span>
                                    </div>
                                    <input placeholder="{{ $social_link->name }}" name="link_{{ $social_link->id }}" type="text" value="{{ $social_link->link == '#' ? '' : str_replace('https://', '', $social_link->link) }}" class="form-control">
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <i class="fab fa-{{ strtolower($social_link->name) }}"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <?php
                                if ($index == 0) {
                                    $links_id = $social_link->id;
                                }
                                else {
                                    $links_id = $links_id.','.$social_link->id;
                                }
                            ?>
                        @endforeach
                        <input type="hidden" name="links" value="{{ $links_id }}">
                    </div>
                    <div class="card-footer">
                        <span style="color:red; font-weight: 700">Nếu bạn muốn quay trở về ảnh ban đầu, vui lòng nhấn phím F5</span>
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
            $('#mang-xa-hoi').addClass('active');
        }
    </script>
@endsection
