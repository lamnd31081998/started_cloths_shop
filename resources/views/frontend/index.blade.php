@extends('frontend.layout.page')

@section('title')
    Trang chủ
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('aStar/styles/main_styles.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('aStar/styles/responsive.css') }}">
    <style>
        @media (max-width: 991.98px) {
            .home {
                height: 500px;
            }

            .home_title {
                max-width: 690px;
            }

            .categories {
                padding-top: 30px;
            }

            .products {
                margin-top: 30px;
                padding-bottom: 0;
            }
        }
    </style>
@endsection

@section('home')
    @include('frontend.layout.index.home')
@endsection

@section('categories')
    @include('frontend.layout.index.categories')
@endsection

@section('products')
    <div id="products_box">
        @include('frontend.layout.index.products')
    </div>
@endsection

@section('js')
    <script src="{{ asset('aStar/js/custom.js') }}"></script>
    <script>
        function getlinks() {
            @if (count($products) == 0)
                $('#products_box').css('height', '300px');
            @endif
        }

        function getProducts(title) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                },
                method: 'get',
                url: '{{ route('fe.index.get_products') }}',
                data: { title : title },
                dataType: 'json',
                beforeSend: function() {
                  $('#products_box').html('<img style="display: block; margin-left: auto; margin-right: auto;" src="{{ asset('images/ajax-loader.gif') }}">');
                },
                statusCode: {
                    500: function (response) {
                      console.log(response.responseText);
                    },
                    200: function (response) {
                        $('#products_box').empty().html(response.view);
                        $.getScript("aStar/js/custom.js");
                    }
                },
            })
        }
    </script>
@endsection
