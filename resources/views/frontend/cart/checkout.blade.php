@extends('frontend.layout.page')

@section('title')
    Thanh toán
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('aStar/styles/checkout.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('aStar/styles/checkout_responsive.css') }}">
    <style>
        @media (min-width: 768px) {
            .modal.show .modal-dialog {
                margin-top: 20px;
            }
        }

        @media (max-width: 991.98px) {
            .modal.show .modal-dialog {
                margin-top: 50%;
            }
        }

        /* Select2 CSS */
        .select2-container .select2-selection--single {
            height: 52px !important;
            border: none !important;
            outline: none !important;
        }

        [class^='select2'] {
            border-radius: 0 !important;
        }

        .select2-selection__rendered {
            font-size: 14px;
            font-weight: 500;
            margin-top: 10px;
            margin-left: 3px;
        }

    </style>
@endsection

@section('home')
    @include('frontend.layout.cart.home')
@endsection

@section('checkout')
    @include('frontend.layout.cart.checkout')
@endsection

@section('optional')
    <div class="modal show text-center" role="dialog" id="wait_store_order" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog text-center">
            <img src="{{ asset('images/waiting-checkout-ajax.gif') }}" class="img-fluid">
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('aStar/js/checkout.js') }}"></script>
    <script>
        function getlinks() {
            $('.home_title').html('Thanh toán');
            $('#breadcrumbs').append(
                '<li><a href="{{ route('fe.index') }}">Trang chủ</a></li>' +
                '<li><a href="{{ route('fe.cart.index') }}">Giỏ hàng</a></li>' +
                '<li class="active">Thanh toán</li>'
            );
            $('#tinh_id').select2({
                theme: "bootstrap"
            });
            $('#huyen_id').select2({
                theme: "bootstrap"
            });
            $('#xa_id').select2({
                theme: "bootstrap"
            });
        }

        function getHuyen() {
            $.ajax({
                headers: {
                   'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                },
                method: 'get',
                url: '{{ route('fe.cart.get_huyen') }}',
                data: { tinh_id : $('#tinh_id').val() },
                dataType: 'json',
                statusCode: {
                    500: function (response) {
                        console.log(response.responseText);
                    },

                    200: function (response) {
                        $('#huyen_box').html(response.view);
                        getXa();
                    }
                }
            });
        }

        function getXa() {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                },
                method: 'get',
                url: '{{ route('fe.cart.get_xa') }}',
                data: { huyen_id : $('#huyen_id').val() },
                dataType: 'json',
                statusCode: {
                    500: function (response) {
                        console.log(response.responseText);
                    },

                    200: function (response) {
                        $('#xa_box').html(response.view);
                    }
                }
            });
        }

        function addOrder() {
            let pattern = new RegExp($('#phone_number').attr('pattern'));
            let name = $('#name').val();
            let email = $('#email').val();
            let address = $('#address').val();
            let tinh_id = $('#tinh_id').val();
            let huyen_id = $('#huyen_id').val();
            let xa_id = $('#xa_id').val();
            let phone_number = $('#phone_number').val();
            let payment_method = $('#payment_method:checked').val();
            let note = $('#note').val();

            if (pattern.test(phone_number) !== true) {
                alert('Số điện thoại không đúng định dạng, vui lòng thử lại');
            }
            else {
                if (payment_method == 1) {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        },
                        method: 'post',
                        url: '{{ route('fe.cart.store') }}',
                        data: {
                            name: name,
                            email: email,
                            address: address,
                            tinh_id: tinh_id,
                            huyen_id: huyen_id,
                            xa_id: xa_id,
                            phone_number: phone_number,
                            payment_method: payment_method,
                            note: note,
                            ship_id: {{ \App\Models\Shipfee::getShipfeeByPrice($carts['information']['ship_fee'])->id }}
                        },
                        dataType: 'json',
                        beforeSend: function () {
                            $('#wait_store_order').modal('show');
                        },
                        statusCode: {
                            500: function (response) {
                                $('#wait_store_order').modal('hide');
                                console.log(JSON.parse(response.responseText));
                            },

                            401: function (response) {
                                $('#wait_store_order').modal('hide');
                                let responseText = JSON.parse(response.responseText);
                                let message = responseText['message'];
                                alert(message);
                            },

                            400: function (response) {
                                $('#wait_store_order').modal('hide');
                                let responseText = JSON.parse(response.responseText);
                                let errors = responseText['errors'];
                                if (typeof errors['name'] !== 'undefined') {
                                    alert(errors['name'])
                                } else if (typeof errors['email'] !== 'undefined') {
                                    alert(errors['email'])
                                } else if (typeof errors['address'] !== 'undefined') {
                                    alert(errors['address'])
                                } else if (typeof errors['tinh_id'] !== 'undefined') {
                                    alert(errors['tinh_id'])
                                } else if (typeof errors['huyen_id'] !== 'undefined') {
                                    alert(errors['huyen_id'])
                                } else if (typeof errors['xa_id'] !== 'undefined') {
                                    alert(errors['xa_id'])
                                } else if (typeof errors['payment_method'] !== 'undefined') {
                                    alert(errors['payment_method'])
                                }

                            },

                            200: function (response) {
                                $('#wait_store_order').modal('hide');
                                alert(response.message);
                                window.location.href = "{{ route('fe.index') }}";
                            }
                        }
                    });
                }
                else {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content'),
                        },
                        url: '{{ route('fe.cart.vnpay') }}',
                        method: 'get',
                        data: {
                            name: name,
                            email: email,
                            address: address,
                            tinh_id: tinh_id,
                            huyen_id: huyen_id,
                            xa_id: xa_id,
                            phone_number: phone_number,
                            payment_method: payment_method,
                            note: note,
                            ship_id: {{ \App\Models\Shipfee::getShipfeeByPrice($carts['information']['ship_fee'])->id }}
                        },

                        statusCode: {
                            500: function (response) {
                                console.log(JSON.parse(response.responseText));
                            },

                            401: function (response) {
                                let responseText = JSON.parse(response.responseText);
                                let message = responseText['message'];
                                alert(message);
                            },

                            400: function (response) {
                                let responseText = JSON.parse(response.responseText);
                                let errors = responseText['errors'];
                                if (typeof errors['name'] !== 'undefined') {
                                    alert(errors['name'])
                                } else if (typeof errors['email'] !== 'undefined') {
                                    alert(errors['email'])
                                } else if (typeof errors['address'] !== 'undefined') {
                                    alert(errors['address'])
                                } else if (typeof errors['tinh_id'] !== 'undefined') {
                                    alert(errors['tinh_id'])
                                } else if (typeof errors['huyen_id'] !== 'undefined') {
                                    alert(errors['huyen_id'])
                                } else if (typeof errors['xa_id'] !== 'undefined') {
                                    alert(errors['xa_id'])
                                } else if (typeof errors['payment_method'] !== 'undefined') {
                                    alert(errors['payment_method'])
                                }
                            },

                            200: function (response) {
                                window.location.href = response.url;
                            }
                        }
                    });
                }
            }
        }

        function changePromotion() {
            let promotion = prompt('Nhập mã giảm giá mới');
            if (promotion !== "") {
                $.ajax({
                    headers: {
                       'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'post',
                    url: '{{ route('fe.cart.checkout_promotion') }}',
                    data: { code : promotion },
                    dataType: 'json',
                    statusCode: {
                        500: function (response) {
                            console.log(JSON.parse(response.responseText));
                        },

                        400: function (response) {
                            let responseText = JSON.parse(response.responseText);
                            alert(responseText['message']);
                        },

                        200: function (response) {
                            $('.cart_price').html(response.totals);
                            $('#totals_box').html(response.view);
                        }
                    }
                });
            }
        }

        function deletePromotion() {
            if (confirm('Bạn có chắc chắn muốn xóa mã giảm giá này?')) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'post',
                    url: '{{ route('fe.cart.delete_promotion_checkout') }}',
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
                    },
                });
            }
        }
    </script>
@endsection
