@extends('frontend.layout.page')

@section('title')
    Liên hệ với chúng tôi
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('aStar/styles/categories.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('aStar/styles/categories_responsive.css') }}">
@endsection

@section('home')
    @include('frontend.layout.contact_us.home')
@endsection

@section('products')
    <div class="products">
        <div class="section_container">
            <div class="container">
                <div class="row">
                    <div class="col text-center" style="margin-top: 20px">
                        {!! $thumb->link !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('aStar/js/categories.js') }}"></script>
    <script src="{{ asset('aStar/js/product.js') }}"></script>
    <script>
        function getlinks() {
            $('.home_title').html('Liên hệ với chúng tôi');
            $('#breadcrumbs').html(
                '<li><a href="{{ route('fe.index') }}">Trang chủ</a></li>' +
                '<li class="active">Liên hệ với chúng tôi</li>'
            );
        }
    </script>
@endsection
