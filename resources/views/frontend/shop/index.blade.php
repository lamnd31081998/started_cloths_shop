@extends('frontend.layout.page')

@section('title')
    {{ $category_dad->name }}
@endsection

@section('home')
    @include('frontend.layout.category_product.home')
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('aStar/styles/categories.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('aStar/styles/categories_responsive.css') }}">
@endsection

@section('products')
    @include('frontend.layout.category_product.products')
@endsection

@section('js')
    <script src="{{ asset('aStar/js/categories.js') }}"></script>
    <script src="{{ asset('aStar/js/product.js') }}"></script>

    <script>
        function getlinks() {
            $('.home_title').html('{{ $category_dad->name }}');
            $('#breadcrumbs').html(
              '<li><a href="{{ route('fe.index') }}">Trang chủ</a></li>' +
              '<li class="active">{{ $category_dad->name }}</li>'
            );
            @if (count($products) == 0)
                $('#products_box').css('height', '200px');
            @endif
        }

        function getProducts(category_id) {
            $.ajax({
                headers: {
                   'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                },
                method: 'get',
                url: '{{ route('fe.category.get_products') }}',
                data: {
                    category_id : category_id
                },
                dataType: 'json',
                beforeSend: function () {
                  $('#products_box').html('<img style="display: block; margin-left: auto; margin-right: auto" src="{{ asset('images/ajax-loader.gif') }}">');
                },
                statusCode: {
                    500: function (response) {
                        console.log(response.responseText);
                    },

                    200: function (response) {
                        $('#products_box').css('height', 'unset').html(response.view);
                        $.getScript("aStar/js/categories.js");
                        $('.menu_slug').removeClass('active');
                        $('#'+response.category_slug).addClass('active');
                        if (response.products_quantity === 0) {
                           $('#products_box').css('height', '200px');
                        }

                        $('.isotope_sorting_text span').text('Sắp xếp');
                        $('.isotope_filter_text span').text('Bộ lọc');
                        $('#keyword').val('');
                    }
                }
            });
        }

        function searchProduct() {
            $('.products_container.grid #no-data').remove();
            $('.products>.section_container').css('padding-bottom', 'unset');
            // quick search regex
            let qsRegex;

            // init Isotope
            let $grid = $('.grid').isotope({
                itemSelector: '.grid-item',
                filter: function() {
                    return qsRegex ? $(this).text().match(qsRegex) : true;
                }
            });

            let keyword = $('#keyword').val().replace(/  +/g, ' ');
            qsRegex = new RegExp(keyword, 'gi');
            $grid.isotope();
            let grid = $grid.data('isotope');
            $('.isotope_sorting_text span').text('Sắp xếp');
            $('.isotope_filter_text span').text('Bộ lọc');
            if (grid.filteredItems.length === 0) {
                $('.products>.section_container').css('padding-bottom', '100px');
                $('.products_container.grid').append('<h1 class="text-center" id="no-data">Sản phẩm bạn tìm kiếm không tồn tại</h1>');
            }
        }
    </script>
@endsection
