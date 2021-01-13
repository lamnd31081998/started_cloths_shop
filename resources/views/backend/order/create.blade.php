@extends('backend.layout.page')

@section('title')
    Thêm đơn hàng
@endsection

@section('css')
    <style>
        @media (min-width: 1280px) {
            .modal-content {
                width: 1000px;
                margin-left: -50%;
            }
        }
    </style>
@endsection

@section('page-title')
    Quản lý đơn hàng
@endsection

@section('right-navbar')
    <li class="breadcrumb-item"><a href="{{ route('be.index') }}">Trang chủ</a></li>
    <li class="breadcrumb-item"><a href="{{ route('be.order.index') }}">Quản lý đơn hàng</a></li>
    <li class="breadcrumb-item active">Thêm đơn hàng</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12 col-xl-12 col-sm-12 col-md-12 col-lg-12 col-xxl-12 col-xs-12">
            <form method="post" action="{{ route('be.order.store') }}" onsubmit="checkProduct(event)">
                @csrf
                <div class="card card-primary">
                    <div class="card-header">
                        <h4 class="card-title">Thêm đơn hàng</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="category_id">Danh mục</label>
                            <div class="input-group mb-3">
                                <select id="category_id" onchange="getProducts(this)" class="form-control">
                                    <option value="0" selected="selected">--- Vui lòng chọn danh mục ---</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="product_id">Sản phẩm</label>
                            <div class="input-group mb-3">
                                <select id="product_id" onchange="getProductAttributeValues(this)" class="form-control">
                                    <option value="0">--- Vui lòng chọn sản phẩm ---</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label id="">Màu sắc - kích cỡ - Số lượng trong kho</label>
                            <div class="input-group mb-3">
                                <select id="product_attribute_value_id" class="form-control">
                                    <option value="0">--- Vui lòng chọn màu sắc & kích cỡ ---</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="quantity">Số lượng</label>
                            <div class="input-group mb-3">
                                <input class="form-control" id="quantity" type="number">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-info"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="button" onclick="addProduct()" class="btn btn-primary">Thêm sản phẩm</button>
                        </div>

                        <div class="form-group table-responsive">
                            <table class="text-center table table-bordered table-hover dataTable dtr-inline" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>STT</th>
                                        <th>Danh mục</th>
                                        <th>Sản phẩm</th>
                                        <th>Màu sắc</th>
                                        <th>Kích cỡ</th>
                                        <th>Đơn giá</th>
                                        <th>Số lượng</th>
                                        <th>Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody id="order_products_box">
                                    <tr>
                                        <td colspan="8" id="no-data">Không có dữ liệu</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="8" style="text-align: right" id="shipping_preview">Hình thức giao hàng - <span id="shipping_preview">Đến lấy tại cửa hàng: Miễn phí</span></td>
                                    </tr>
                                    <tr>
                                        <td colspan="8" style="text-align: right">Tổng tiền: <span id="totals_preview">0</span>vnđ</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        {{-- Ship method --}}
                        <div class="form-group">
                            <label for="ship_id">Hình thức giao hàng <span style="color: red">(*)</span></label>
                            @foreach($shipfees as $index=>$shipfee)
                                <div class="custom-control custom-radio">
                                    @if ($index == 0)
                                        <input checked class="custom-control-input" type="radio" name="ship_id" id="ship_{{ $shipfee->id }}" value="{{ $shipfee->id }}">
                                    @else
                                        <input class="custom-control-input" type="radio" name="ship_id" id="ship_{{ $shipfee->id }}" value="{{ $shipfee->id }}">
                                    @endif
                                    <label style="font-weight: normal" class="custom-control-label" for="ship_{{ $shipfee->id }}">{{ $shipfee->name }} - {{ $shipfee->price == 0? 'Miễn phí' : number_format($shipfee->price, 0, '', '.').'vnđ' }}</label>
                                </div>
                            @endforeach
                        </div>

                        {{-- Payment method --}}
                        <div class="form-group">
                            <label for="payment_method">Hình thức thanh toán <span style="color: red">(*)</span></label>
                            <div class="custom-control custom-radio">
                                <input checked class="custom-control-input" type="radio" name="payment_method" id="payment_method_1" value="1">
                                <label style="font-weight: normal" class="custom-control-label" for="payment_method_1">Thanh toán bằng tiền mặt</label>
                            </div>
                        </div>

                        {{-- Name --}}
                        <div class="form-group">
                            <label for="name">Tên khách hàng <span style="color: red">(*)</span></label>
                            <div class="input-group mb-3">
                                <input class="form-control" type="text" placeholder="Tên khách hàng (*)" name="name" id="name" oninvalid="this.setCustomValidity('Vui lòng nhập tên khách hàng')" oninput="this.setCustomValidity('')" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-user"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Email --}}
                        <div class="form-group">
                            <label for="email">Email <span style="color: red">(*)</span></label>
                            <div class="input-group mb-3">
                                <input type="email" class="form-control" id="email" name="email" placeholder="Email" required oninvalid="this.setCustomValidity('Vui lòng nhập email của khách hàng hoặc kiểm tra lại định dạng của email')" oninput="this.setCustomValidity('')">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Phone Number --}}
                        <div class="form-group">
                            <label for="phone_number">Số điện thoại <span style="color: red">(*)</span></label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" pattern="[0]{1}[1-9]{1}[0-9]{8}" placeholder="Số điện thoại (*)" name="phone_number" id="phone_number" required oninvalid="this.setCustomValidity('Vui lòng nhập số điện thoại hoặc kiểm tra lại định dạng của số')" oninput="this.setCustomValidity('')">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-phone"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Province --}}
                        <div class="form-group" id="tinh_box">
                            <label for="tinh_id">Thành phố <span style="color: red">(*)</span></label>
                            <select class="form-control" name="tinh_id" id="tinh_id" onchange="getHuyen()" required oninvalid="this.setCustomValidity('Vui lòng chọn thành phố')" oninput="this.setCustomValidity('')">
                                <option value="">--- Chọn thành phố ---</option>
                                @foreach($tinhs as $tinh)
                                    <option value="{{ $tinh->id }}">{{ $tinh->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- District --}}
                        <div class="form-group" id="huyen_box">
                            <label for="huyen_id">Quận <span style="color:red;">(*)</span></label>
                            <select class="form-control" name="huyen_id" id="huyen_id" onchange="getXa()" required oninvalid="this.setCustomValidity('Vui lòng chọn quận')" oninput="this.setCustomValidity('')">
                                <option value="">--- Chọn quận ---</option>
                            </select>
                        </div>

                        {{-- Ward --}}
                        <div class="form-group" id="xa_box">
                            <label for="xa_id">Phường <span style="color: red">(*)</span></label>
                            <select class="form-control" name="xa_id" id="xa_id" required oninvalid="this.setCustomValidity('Vui lòng chọn phường')" oninput="this.setCustomValidity('')">
                                <option value="">--- Chọn phường ---</option>
                            </select>
                        </div>

                        {{-- Address --}}
                        <div class="form-group">
                            <label for="address">Địa chỉ nhận hàng <span style="color: red">(*)</span></label>
                            <div class="input-group mb-3">
                                <input class="form-control" type="text" placeholder="Địa chỉ nhận hàng (*)" name="address" id="address" required oninvalid="this.setCustomValidity('Vui lòng nhập địa chỉ nhận hàng')" oninput="this.setCustomValidity('')">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-info"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Note --}}
                        <div class="form-group">
                            <label for="note">Ghi chú đơn hàng (Nếu có)</label>
                            <div class="input-group mb-3">
                                <textarea style="height: 100px" class="form-control" name="note" placeholder="Ghi chú đơn hàng (nếu có)"></textarea>
                            </div>
                        </div>

                    </div>
                    {{-- /.card-body --}}
                    <div class="card-footer">
                        <button class="btn btn-primary" type="submit" style="float: right;">Thêm đơn hàng</button>
                        <button type="reset" class="btn btn-danger" style="float: right; margin-right: 10px">Nhập lại</button>
                    </div>
                </div>
                {{-- /.card --}}
            </form>
        </div>
    </div>
@endsection

@section('js')
    <script>
        function activelink() {
            $('.quan-ly-don-hang').addClass('menu-open');
            $('#quan-ly-don-hang').addClass('active');
            $('#them-don-hang').addClass('active');
            $('select').select2();
        }

        function getProducts(category_id) {
            $.ajax({
                headers: {
                   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: 'get',
                url: '{{ route('be.order.get_products') }}',
                data: { category_id : category_id.value },
                dataType: 'json',
                statusCode: {
                    500: function (response) {
                        console.log(JSON.parse(response.responseText));
                    },

                    200: function (response) {
                        if (response.total_products !== 0) {
                            $('#product_id').html(response.view);
                        }
                        else {
                            $('#product_id').html('<option value="0">--- Vui lòng chọn sản phẩm ---</option>');
                            getProductAttributeValues(0);
                        }
                    }
                }
            });
        }

        function getProductAttributeValues(product_id) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: 'get',
                url: '{{ route('be.order.get_product_attribute_values') }}',
                data: { product_id: product_id.value },
                dataType: 'json',
                statusCode: {
                    500: function (response) {
                        console.log(JSON.parse(response.responseText));
                    },

                    200: function (response) {
                        if (response.total_product_attribute_values !== 0) {
                            $('#product_attribute_value_id').html(response.view);
                        }
                        else {
                            $('#product_attribute_value_id').html('<option value="0">--- Vui lòng chọn màu sắc & kích cỡ ---</option>');
                        }
                    }
                }
            });
        }

        let i = 1;
        let products_ordered = new Object();
        let totals = 0; /* Không bao gồm phí ship */

        function addProduct() {
            let quantity = $('#quantity').val();
            let category_id = $('#category_id').val();
            let product_id = $('#product_id').val();
            let product_attribute_value_id = $('#product_attribute_value_id').val();
            let ship_id = $('input[name="ship_id"]:checked').val();
            let fail = 0;

            if (category_id == 0) {
                alert('Vui lòng chọn danh mục');
            }
            else if (product_id == 0) {
                alert('Vui lòng chọn sản phẩm');
            }
            else if (product_attribute_value_id == 0) {
                alert('Vui lòng chọn màu sắc và kích cỡ của sản phẩm');
            }
            else if (quantity === "") {
                alert('Vui lòng nhập số lượng đặt');
            }
            else {
                $.each(products_ordered, function (index, data) {
                    if (data['product_id'] === product_id && data['product_attribute_value_id'] === product_attribute_value_id) {
                        fail = 1;
                        alert('Sản phẩm đã tồn tại tại vị trí '+ data['i']+' vui lòng chỉ thêm số lượng');
                    }
                })
                if (fail === 0) {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        method: 'get',
                        url: '{{ route('be.order.add_product') }}',
                        data: {
                            i: i,
                            category_id: category_id,
                            product_id: product_id,
                            product_attribute_value_id: product_attribute_value_id,
                            quantity: quantity,
                            ship_id: ship_id,
                            totals: totals
                        },
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
                                $('#no-data').remove();
                                $('#totals_preview').html(response.totals);
                                $('#order_products_box').append(response.view);
                                $('#category_id').val(0);
                                $('#product_id').val(0);
                                $('#quantity').val('');
                                getProducts(0);
                                getProductAttributeValues(0);
                                products_ordered[i] = {
                                    'i': i,
                                    'category_id': category_id,
                                    'product_id': product_id,
                                    'product_attribute_value_id': product_attribute_value_id,
                                    'quantity': quantity,
                                    'total_price': response.total_price
                                };
                                i = i + 1;
                                totals = totals + response.total_price;
                            }
                        }
                    });
                }
            }
        }

        function calcTotals(input, id) {
            let ship_id = $('input[name="ship_id"]:checked').val();
            let quantity = input.value;
            let price = $('#price_'+id).val();
            let old_total_price = products_ordered[id]['total_price'];
            let new_total_price = quantity * price;
            /* TH xóa SP */
            if (quantity == 0) {
                if (confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi đơn hàng?')) {
                    $('#product_'+id).remove();
                    totals = totals - old_total_price;
                    delete products_ordered[id];
                    /* TH xóa hết SP */
                    if (Object.keys(products_ordered).length === 0) {
                        $('#order_products_box').append('<tr><td colspan="8" id="no-data">Không có dữ liệu</td></tr>');
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            method: 'get',
                            url: '{{ route('be.order.update_totals') }}',
                            data: {
                                'type': 'remove_product',
                                'totals': totals,
                                'ship_id': ship_id,
                            },
                            dataType: 'json',
                            statusCode: {
                                500: function (response) {
                                    console.log(JSON.parse(response.responseText));
                                },

                                200: function (response) {
                                    $('#totals_preview').html(response.totals);
                                }
                            }
                        });
                        i = 1;
                    }
                    /* TH xóa nhưng vẫn còn SP */
                    else {
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            method: 'get',
                            url: '{{ route('be.order.update_totals') }}',
                            data: {
                                'type': 'remove_product',
                                'totals': totals,
                                'ship_id': ship_id,
                                'products_ordered': products_ordered
                            },
                            dataType: 'json',
                            statusCode: {
                                500: function (response) {
                                    console.log(JSON.parse(response.responseText));
                                },

                                200: function (response) {
                                    $('#order_products_box').html(response.view);
                                    $('#totals_preview').html(response.totals);
                                    products_ordered = new Object();
                                    products_ordered = response.products_ordered;
                                }
                            }
                        });
                    }
                }
            }
            /* TH chỉ cập nhật số lượng */
            else {
                input.value = quantity;
                let old_totals = totals;
                totals = totals - old_total_price + new_total_price;
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'get',
                    url: '{{ route('be.order.update_totals') }}',
                    data: {
                        totals: totals,
                        ship_id: ship_id,
                        product_attribute_value_id: $('#product_attribute_value_id_'+id).val(),
                        quantity: quantity
                    },
                    dataType: 'json',
                    statusCode: {
                        500: function (response) {
                            console.log(JSON.parse(response.responseText));
                        },

                        400: function (response) {
                            let responseText = JSON.parse(response.responseText);
                            alert(responseText['message']);
                            totals = old_totals;
                            $('#quantity_'+id).val(products_ordered[id]['quantity']);
                        },

                        200: function (response) {
                            $('#totals_preview').html(response.totals);
                            products_ordered[id] = {'quantity': quantity, 'total_price': new_total_price};
                        }
                    }
                });
            }
        }

        /* Thay đổi ship */
        $('input[name="ship_id"]').change(function () {
            let ship_id = this.value;
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: 'get',
                url: '{{ route('be.order.update_totals') }}',
                data: {
                    type: 'change_ship',
                    ship_id: ship_id,
                    totals: totals
                },
                dataType: 'json',
                statusCode: {
                    500: function (response) {
                        console.log(JSON.parse(response.responseText));
                    },

                    200: function (response) {
                        $('#shipping_preview').html(response.view);
                        $('#totals_preview').html(response.totals);
                    }
                }
            })
        })

        function getHuyen() {
            let tinh_id = $('#tinh_id').val();
            $.ajax({
                headers: {
                   'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                },
                method: 'post',
                url: '{{ route('be.get_huyen') }}',
                data: { tinh_id : tinh_id },
                dataType: 'json',
                statusCode: {
                    200: function (response) {
                        $('#huyen_box').html(response.view);
                        getXa();
                    }
                }
            });
        }

        function getXa() {
            let huyen_id = $('#huyen_id').val();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                },
                method: 'post',
                url: '{{ route('be.get_xa') }}',
                data: { huyen_id : huyen_id },
                dataType: 'json',
                statusCode: {
                    200: function (response) {
                        $('#xa_box').html(response.view);
                    }
                }
            });
        }

        function checkProduct(e) {
            if (typeof $('#quantity_1').val() === 'undefined') {
                alert('Vui lòng thêm ít nhất 1 sản phẩm');
                e.preventDefault();
            }
        }
    </script>
@endsection
