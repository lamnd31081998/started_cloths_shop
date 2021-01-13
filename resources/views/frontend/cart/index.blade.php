@extends('frontend.layout.page')

@section('title')
    Giỏ hàng
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('aStar/styles/cart.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('aStar/styles/cart_responsive.css') }}">
@endsection

@section('home')
    @include('frontend.layout.cart.home')
@endsection

@section('carts')
    @include('frontend.layout.cart.cart')
@endsection

@section('js')
    <script src="{{ asset('aStar/js/cart.js') }}"></script>

    <script>
        function getlinks() {
            $('.home_title').html('Giỏ hàng');
            $('#breadcrumbs').html(
                '<li><a href="{{ route('fe.index') }}">Trang chủ</a></li>' +
                '<li class="active">Giỏ hàng</li>'
            );

            @if (count($carts) != 0)
                var value = {{ $carts['information']['ship_fee'] }};
                $("input[name=ship][value=" + value + "]").prop('checked', true);
            @endif
        }

        function checkPromotion() {
            let code = $('#promotion').val();
            if (code.length === 0) {
                alert('Vui lòng nhập mã giảm giá trước');
            }
            else {
                $.ajax({
                    headers: {
                       'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'post',
                    url: '{{ route('fe.cart.check_promotion') }}',
                    data: {
                        code : code
                    },
                    dataType: 'json',
                    statusCode: {
                        500: function (response) {
                            console.log(response.responseText)
                        },

                        400: function (response) {
                            var responseText = JSON.parse(response.responseText);
                            var message = responseText['message'];
                            alert(message);
                        },

                        200: function (response) {
                            $('.cart_price').html(response.totals);
                            $('#totals_box').html(response.view);
                        }
                    }
                });
            }
        }

        function updateCart() {
            let quantities = new Object();
            let items = $('input[name=index]').map(function (id, item) {
                return $(item).val();
            }).get();

            items.forEach(function (item) {
                quantities[item] = $('#quantity_'+item).text();
            });

            $.ajax({
               headers : {
                   'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
               },
                method: 'post',
                url: '{{ route('fe.cart.update') }}',
                data: {
                    quantities : quantities,
                },
                dataType: 'json',
                statusCode: {
                    500: function (response) {
                        console.log(response.responseText);
                    },

                    403: function (response) {
                        console.log(JSON.parse(response.responseText));
                    },

                    400: function (response) {
                        let responseText = JSON.parse(response.responseText);
                        if (typeof responseText['message'] !== 'undefined') {
                            alert(responseText['message']);
                        }
                        else {
                            if (confirm(responseText['errors'])) {
                                deletePromotion();
                            }
                        }

                    },

                    200: function (response) {
                        $('body').html(response.view);
                        getlinks();
                        console.log(response.totals);
                        $('.cart_price').html(response.totals);
                    }
                }
            });
        }

        function deleteCart() {
            if (confirm('Bạn có chắc chắn muốn xóa giỏ hàng không?')) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'post',
                    url: '{{ route('fe.cart.delete_carts') }}',
                    data: {},
                    dataType: 'json',
                    statusCode: {
                        500: function (response) {
                            console.log(response.responseText);
                        },

                        200: function (response) {
                            alert(response.message);
                            $('body').html(response.view);
                        }
                    }
                });
            }
        }

        $('input[name="ship"]').change(function () {
            let ship_fee = this.value;
            $.ajax({
               headers : {
                   'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
               },
                method: 'post',
                url: '{{ route('fe.cart.update_ship_fee') }}',
                data: { ship_fee : ship_fee },
                dataType: 'json',
                statusCode: {
                    500: function (response) {
                        console.log(response.responseText);
                    },

                    400: function (response) {
                        var responseText = JSON.parse(response.responseText);
                        var message = responseText['message'];
                        alert(message);
                    },

                    200: function (response) {
                        $('.cart_price').html(response.totals);
                        $('#totals_box').html(response.view);
                    }
                }
            });
        })

        function deletePromotion() {
            if (confirm('Bạn có chắc chắn muốn xóa mã giảm giá này?')) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'post',
                    url: '{{ route('fe.cart.delete_promotion') }}',
                    data: {},
                    dataType: 'json',
                    statusCode: {
                        500: function (response) {
                            console.log(response.responseText);
                        },

                        400: function (response) {
                            var responseText = JSON.parse(response.responseText);
                            var message = responseText['message'];
                            alert(message);
                        },

                        200: function (response) {
                            $('.cart_price').html(response.totals);
                            $('#totals_box').html(response.view);
                        }
                    }
                });
            }
        }
    </script>
@endsection
