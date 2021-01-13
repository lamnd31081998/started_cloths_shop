@extends('frontend.layout.page')

@section('title')
    {{ $product->name }}
@endsection

@section('home')
    @include('frontend.layout.category_product.home')
@endsection

@section('products')
    @include('frontend.layout.category_product.product_detail')
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('aStar/styles/product.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('aStar/styles/product_responsive.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('mdb/css/mdb.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('mdb/css/addons/datatables.min.css') }}">

    <style>
        .home_container {
            bottom: 15px;
        }

        .number-input input[type="number"] {
            -webkit-appearance: textfield;
            -moz-appearance: textfield;
            appearance: textfield;
        }

        .number-input input[type=number]::-webkit-inner-spin-button,
        .number-input input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
        }

        .number-input {
            display: flex;
            justify-content: space-around;
            align-items: center;
        }

        .number-input button {
            -webkit-appearance: none;
            background-color: transparent;
            border: none;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            margin: 0;
            position: relative;
        }

        .number-input button:before,
        .number-input button:after {
            display: inline-block;
            position: absolute;
            content: '';
            height: 2px;
            transform: translate(-50%, -50%);
        }

        .number-input button.plus:after {
            transform: translate(-50%, -50%) rotate(90deg);
        }

        .number-input input[type=number] {
            text-align: center;
        }

        .number-input.number-input {
            border: 1px solid #ced4da;
            width: 10rem;
            border-radius: .25rem;
        }

        .number-input.number-input button {
            width: 2.6rem;
            height: .7rem;
        }

        .number-input.number-input button.minus, .number-input.number-input button.plus {
            outline: none;
        }

        .number-input.number-input button:before,
        .number-input.number-input button:after {
            width: .7rem;
            background-color: #495057;
        }

        .number-input.number-input input[type=number] {
            max-width: 4rem;
            padding: .5rem;
            border: 1px solid #ced4da;
            border-width: 0 1px;
            font-size: 1rem;
            height: 2rem;
            color: #495057;
        }

        @media not all and (min-resolution:.001dpcm) {
            @supports (-webkit-appearance: none) and (stroke-color:transparent) {

                .number-input.def-number-input.safari_only button:before,
                .number-input.def-number-input.safari_only button:after {
                    margin-top: -.3rem;
                }
            }
        }

        .modal-content {
            margin-top: 30%;
        }

        @media (max-width: 991.98px) {
            .home_title {
                font-size: 25px;
            }

            .modal-content {
                margin-top: 50%;
            }
        }
        @media (min-width: 1200px) {
            #size_image_content {
                width: 1000px;
                margin-left: -50%;
            }
        }
    </style>
@endsection

@section('optional')
    <div class="modal fade" role="dialog" id="size_image">
        <div class="modal-dialog">
            <div class="modal-content" id="size_image_content">
                <div class="modal-header">
                    <h4 class="modal-title" style="color: black; font-weight: 700">Bảng kích cỡ</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body text-center">
                    <img class="img-fluid" src="{{ asset($product->size_image) }}">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" role="dialog" id="add_to_cart" data-backdrop="static" data-keybroad="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" style="color: black; font-weight: 700">Thêm sản phẩm vào giỏ hàng thành công</h4>
                </div>
                <div class="modal-body text-center">
                    <img class="img-fluid" src="{{ asset('images/add-to-cart-done.gif') }}">
                </div>
                <div class="modal-footer">
                    <button style="margin-top: 0" id="update_cart_button" data-dismiss="modal" type="button" class="cart_button trans_200">Tiếp tục mua sắm</button>
                    <button style="margin-top: 0" onclick="return window.location.href='{{ route('fe.cart.index') }}'" class="cart_button trans_200">Thanh toán</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('aStar/js/product.js') }}"></script>
    <script src="{{ asset('mdb/js/mdb.min.js') }}"></script>
    <script src="{{ asset('mdb/js/addons/datatables.min.js') }}"></script>

    <script>
        function getlinks() {
            $('.home_title').html('{{ $product->name }}');
            $('#breadcrumbs').html(
                '<li><a href="{{ route('fe.index') }}">Trang chủ</a></li>' +
                '<li><a href="{{ route('fe.category.index', ['category_slug' => \App\Models\Category::getCategoryById($category->parent_id)->slug]) }}">{{ $category_dad->name }}</a></li>' +
                '<li class="acitve">{{ $category->name }}</li>' +
                '<li class="active" style="text-overflow: ellipsis;white-space: nowrap;overflow:hidden">{{ $product->name }}</li>'
            );
        }

        function searchStar(rating) {
            let check = 0;
            if (rating === 0) {
                for (let i = 1; i <= 5; i++) {
                    $('.rating_'+i).css('display', '');
                    check = 1;
                }
            }
            else {
                check = 0;
                for (let i = 1; i <= 5; i++) {
                    if (i !== rating) {
                        $('.rating_'+i).css('display', 'none');
                    }
                    else if (i === rating) {
                        $('.rating_'+i).css('display', '');
                    }
                }
            }

        }

        let star = 0;

        function confirmStar(max) {
            let i = 1;
            let j = 1;
            while (j<=5) {
                $('#star_'+j).removeClass('fa-star').addClass('fa-star-o');
                j = j + 1;
            }
            while (i <= max) {
                $('#star_'+i).removeClass('fa-star-o').addClass('fa-star');
                i = i + 1;
            }
            star = max;
        }

        function send_comment() {
            let product_id = {{ $product->id }};
            let name = $('#name').val();
            let phone_number = $('#phone_number').val();
            let comment = $('#comment').val();
            let phone_pattern = new RegExp($('#phone_number').attr('pattern'));
            if (phone_pattern.test(phone_number) !== true) {
                alert('Số điện thoại nhập không đúng định dạng, vui lòng kiểm tra lại');
            }
            else {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'post',
                    url: '{{ route('fe.product.store_rating') }}',
                    data: {
                        product_id: product_id,
                        name: name,
                        phone_number: phone_number,
                        comment: comment,
                        star: star
                    },
                    dataType: 'json',
                    statusCode: {
                        500: function (response) {
                            console.log(JSON.parse(response.responseText));
                        },

                        400: function (response) {
                            let responseText = JSON.parse(response.responseText);
                            let errors = responseText['errors'];
                            let message = responseText['message'];
                            if (typeof errors !== 'undefined') {
                                if (typeof errors['name'] !== 'undefined') {
                                    alert(errors['name']);
                                }
                                else if (typeof errors['comment'] !== 'undefined') {
                                    alert(errors['comment']);
                                }
                                else {
                                    alert(errors['star'])
                                }
                            }
                            if (typeof message !== 'undefined') {
                                alert(message);
                            }
                        },

                        200: function (response) {
                            $('#name').val('');
                            $('#phone_number').val('');
                            $('#comment').val('');
                            for (let i = 1; i <= 5; i++) {
                                $('#star_'+i).removeClass('fa-star').addClass('fa-star-o');
                            }
                            $('#search_star_box').html(response.view_search_star);
                            $('#avg_ratings_box').html(response.view_avg_rating);
                            $('#new_rating_box').prepend(response.view);
                        }
                    }
                });
            }
        }

        function checkColor(size_id) {
            if ($("input[name='color']:checked").val()) {

            }
            else {
                alert('Vui lòng chọn màu sắc trước');
                $('#size_'+size_id).prop('checked', false);
            }
        }

        function getSizes(color_id) {
            $.ajax({
                headers: {
                   'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                },
                method: 'get',
                url: '{{ route('fe.product.get_sizes') }}',
                data: {
                    product_id : {{ $product->id }},
                    color_id : color_id
                },
                dataType: 'json',
                statusCode: {
                    500: function (response) {
                        console.log(response.responseText);
                    },

                    200: function (response) {
                        $('#quantity_box').css('display', 'none');
                        $('#quantity_input').css('display', 'none');
                        $('#sizes_box').html(response.view);
                    }
                }
            });
        }

        function getQuantity(quantity) {
            $('#instock').html(+quantity+' sản phẩm');
            $('#quantity_box').css('display', '');
            $('#quantity_input').css('display', 'block');
            $('.quantity').attr('max', quantity).val(1);
        }

        function addProduct(product_id, event) {
            let color_id = $('input[name="color"]:checked').val();
            let size_id = $('input[name="size"]:checked').val();
            let quantity = $('input[name="quantity"]').val();

            if ((typeof color_id) === 'undefined' || (typeof size_id) === 'undefined') {
                alert('Vui lòng chọn màu sắc và kích cỡ trước');
            }
            else {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'post',
                    url: '{{ route('fe.cart.add_product') }}',
                    data: {
                        link: '{{ url()->current() }}',
                        product_id: product_id,
                        color_id: color_id,
                        size_id: size_id,
                        quantity: quantity
                    },
                    dataType: 'json',
                    statusCode: {
                        500: function (response) {
                            console.log(response.responseText);
                        },

                        400: function (response) {
                            let responseText = JSON.parse(response.responseText);
                            let message = responseText['message'];
                            alert(message);
                        },

                        200: function (response) {
                            $('#add_to_cart').modal('show');
                            $('#update_cart_button').unbind('click').click(  function () { updateBag(response.count_carts, response.totals); } );
                        }
                    }
                });
            }
        }

        function updateBag(count_carts, totals) {
            $('.cart_num').html(count_carts-1);
            $('.cart_price').html(totals);
        }
    </script>
@endsection
