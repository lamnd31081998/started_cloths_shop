@extends('frontend.layout.page')

@section('title')
    Chính sách thanh toán
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('aStar/styles/categories.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('aStar/styles/categories_responsive.css') }}">

    <style>
        .col p, .products {
            color: black;
        }
    </style>
@endsection

@section('home')
    @include('frontend.layout.checkout_terms.home')
@endsection

@section('products')
    <div class="products">
        <div class="section_container">
            <div class="container">
                <div class="row">
                    <div class="col" style="margin-top: 20px">
                        {!! $thumb->description !!}
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
            $('.home_title').html('Chính sách thanh toán');
            $('#breadcrumbs').html(
                '<li><a href="{{ route('fe.index') }}">Trang chủ</a></li>' +
                '<li class="active">Chính sách thanh toán</li>'
            );
        }
    </script>
@endsection
