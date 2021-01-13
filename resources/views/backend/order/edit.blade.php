@extends('backend.layout.page')

@section('title')
    Sửa đơn hàng
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
    <li class="breadcrumb-item active">Sửa đơn hàng</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12 col-lg-12 col-md-12 col-sm-12 col-xl-12 col-xs-12 col-xxl-12">
            <form method="post" action="{{ route('be.order.update', ['id' => $order->id]) }}" onsubmit="checkProduct(event)">
                @csrf
                @method('put')
                <div class="card card-primary">
                    <div class="card-header">
                        <h4 class="card-title">Sửa đơn hàng</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="category_id">Danh mục</label>
                            <select class="form-control" id="category_id" onchange="getProducts(this)">
                                <option value="0">--- Vui lòng chọn danh mục ---</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="product_id">Sản phẩm</label>
                            <select class="form-control" id="product_id" onchange="getProductAttributeValues(this)">
                                <option value="0">--- Vui lòng chọn sản phẩm ---</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="product_attribute_value_id">Màu sắc - kích cỡ - số lượng trong kho</label>
                            <select class="form-control" id="product_attribute_value_id">
                                <option value="0">--- Vui lòng chọn màu sắc & kích cỡ ---</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="quantity">Số lượng</label>
                            <div class="input-group mb-3">
                                <input type="number" id="quantity" class="form-control">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-info"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Add Product Button --}}
                        <div class="form-group">
                            <button type="button" onclick="addProduct()" id="add_product_button" class="btn btn-primary">Thêm sản phẩm</button>
                        </div>

                        <div class="form-group table-responsive">
                            <table class="table table-hover table-bordered dataTable dtr-inline">
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
                                    @foreach($order_details as $index=>$order_detail)
                                        <tr id="product_{{ $index+1 }}">
                                            <td>{{ $index+1 }}</td>
                                            <td>{{ \App\Models\Category::getCategoryById(\App\Models\Product::getProductById($order_detail->product_id)->category_id)->name }}</td>
                                            <td>
                                                <input type="hidden" name="products[]" id="product_id_{{ $index+1 }}" value="{{ $order_detail->product_id }}">
                                                <?php
                                                    $product = \App\Models\Product::getProductById($order_detail->product_id);
                                                    $product_slug = $product->slug;
                                                    $category = \App\Models\Category::getCategoryById($product->category_id);
                                                    $category_slug = $category->slug;
                                                    $category_dad_slug = \App\Models\Category::getCategoryById($category->parent_id)->slug;
                                                ?>
                                                <a title="Xem sản phẩm" target="_blank" href="{{ route('fe.product.detail', ['category_dad_slug' => $category_dad_slug, 'category_slug' => $category_slug, 'product_slug' => $product_slug]) }}">{{ \App\Models\Product::getProductById($order_detail->product_id)->name }}</a>

                                            </td>
                                            <input type="hidden" name="product_attribute_values[]" id="product_attribute_value_id_{{ $index+1 }}" value="{{ $order_detail->product_attribute_value_id }}">
                                            <td>{{ \App\Models\Attribute_value::getAttributevalueById(\App\Models\Product_Attribute_value::getProductAttributeValueById($order_detail->product_attribute_value_id)->color_id)->value }}</td>
                                            <td>{{ \App\Models\Attribute_value::getAttributevalueById(\App\Models\Product_Attribute_value::getProductAttributeValueById($order_detail->product_attribute_value_id)->size_id)->value }}</td>
                                            <td>
                                                <?php
                                                    $product = \App\Models\Product::getProductById($order_detail->product_id);
                                                    if ($product->sale_price != "") {
                                                        $price = $product->sale_price;
                                                    }
                                                    else {
                                                        $price = $product->price;
                                                    }
                                                ?>
                                                <input type="hidden" id="price_{{ $index+1 }}" value="{{ $price }}">
                                                {{ number_format($price, 0, '', '.') }}vnđ
                                            </td>
                                            <td><input min="0" type="number" class="form-control" id="quantity_{{ $index+1 }}" value="{{ $order_detail->quantity }}" name="quantities[]" onchange="calcTotals(this, {{ $index+1 }})"></td>
                                            <td>{{ number_format($order_detail->total_price, 0, '', '.') }}vnđ</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="8" style="text-align: right" id="shipping_preview">Hình thức giao hàng - <span id="shipping_preview">{{ \App\Models\Shipfee::getShipfeeById($order->ship_id)->name }}: {{ \App\Models\Shipfee::getShipfeeById($order->ship_id)->price == 0 ? 'Miễn phí' : number_format(\App\Models\Shipfee::getShipfeeById($order->ship_id)->price, 0, '', '.').'vnđ' }}</span></td>
                                </tr>
                                <tr>
                                    <td colspan="8" style="text-align: right">Tổng tiền: <span id="totals_preview">{{ number_format($order->totals, 0, '', '.') }}</span>vnđ</td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>

                        {{-- Ship --}}
                        <div class="form-group">
                            <label for="ship_id">Hình thức giao hàng <span style="color: red">(*)</span></label>
                            @foreach($shipfees as $shipfee)
                                <div class="custom-control custom-radio">
                                    @if ($shipfee->id == $order->ship_id)
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
                                <input type="text" class="form-control" id="name" name="name" value="{{ $order->name }}" placeholder="Tên khách hàng (*)" required oninvalid="this.setCustomValidity('Vui lòng nhập tên khách hàng')" oninput="this.setCustomValidity('')">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-user"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Phone number --}}
                        <div class="form-group">
                            <label for="phone_number">Số điện thoại <span style="color: red">(*)</span></label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" id="phone_number" name="phone_number" value="{{ $order->phone_number }}" placeholder="Số điện thoại (*)" pattern="[0]{1}[1-9]{1}[0-9]{8}" required oninvalid="this.setCustomValidity('Vui lòng nhập số điện thoại hoặc kiểm tra lại định dạng')" oninput="this.setCustomValidity('')">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-user"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Email --}}
                        <div class="form-group">
                            <label for="address">Email <span style="color: red">(*)</span></label>
                            <div class="input-group mb-3">
                                <input type="email" name="email" id="email" class="form-control" value="{{ $order->email }}" placeholder="Email" required oninvalid="this.setCustomValidity('Vui lòng nhập email của khách hàng hoặc kiểm tra lại định dạng của email')" oninput="this.setCustomValidity('')">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- City --}}
                        <div class="form-group">
                            <label for="tinh_id">Thành phố <span style="color: red">(*)</span></label>
                            <select class="form-control" name="tinh_id" id="tinh_id" onchange="getHuyen()" required oninvalid="this.setCustomValidity('Vui lòng chọn thành phố')" oninput="this.setCustomValidity('')">
                                <option value="">--- Chọn thành phố ---</option>
                                @foreach($tinhs as $tinh)
                                    @if ($tinh->id == $order->tinh_id)
                                        <option value="{{ $tinh->id }}" selected>{{ $tinh->name }}</option>
                                    @else
                                        <option value="{{ $tinh->id }}">{{ $tinh->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        {{-- District --}}
                        <div class="form-group" id="huyen_box">
                            <label for="huyen_id">Quận <span style="color: red">(*)</span></label>
                            <select class="form-control" name="huyen_id" id="huyen_id" onchange="getXa()" required oninvalid="this.setCustomValidity('Vui lòng chọn quận')" oninput="this.setCustomValidity('')">
                                <option value="">--- Chọn quận ---</option>
                                @foreach($huyens as $huyen)
                                    @if ($huyen->id == $order->huyen_id)
                                        <option value="{{ $huyen->id }}" selected>{{ $huyen->name }}</option>
                                    @elseif ($huyen->tinh_id == $order->tinh_id)
                                        <option value="{{ $huyen->id }}">{{ $huyen->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        {{-- Ward --}}
                        <div class="form-group" id="xa_box">
                            <label for="xa_id">Phường <span style="color: red">(*)</span></label>
                            <select class="form-control" name="xa_id" id="xa_id" onchange="getXa()" required oninvalid="this.setCustomValidity('Vui lòng chọn phường')" oninput="this.setCustomValidity('')">
                                <option value="">--- Chọn phường ---</option>
                                @foreach($xas as $xa)
                                    @if ($xa->huyen_id == $order->huyen_id)
                                        <option value="{{ $xa->id }}" selected>{{ $xa->name }}</option>
                                    @elseif ($xa->huyen_id == $order->huyen_id)
                                        <option value="{{ $xa->id }}">{{ $xa->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        {{-- Address --}}
                        <div class="form-group">
                            <label for="address">Địa chỉ cụ thể <span style="color: red">(*)</span></label>
                            <div class="input-group mb-3">
                                <input type="text" name="address" id="address" class="form-control" value="{{ $order->address }}" placeholder="Địa chỉ cụ thể (*)" required oninvalid="this.setCustomValidity('Vui lòng nhập địa chỉ cụ thể')" oninput="this.setCustomValidity('')">
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
                                <textarea style="height: 100px" class="form-control" name="note" placeholder="Ghi chú đơn hàng (nếu có)">{{ $order->note }}</textarea>
                            </div>
                        </div>

                    </div>
                    {{-- /.card-body --}}
                    <div class="card-footer">
                        <button type="submit" style="float: right" class="btn btn-primary">Sửa đơn hàng</button>
                        <button data-toggle="modal" data-target="#preview_cart" type="button" class="btn btn-primary" style="float:right; margin: 0 10px 0 10px" onclick="getCart()">Xem thông tin đơn hàng</button>
                        <button type="reset" class="btn btn-danger" style="float: right">Nhập lại</button>
                    </div>
                </div>
                {{-- /.card --}}
            </form>
        </div>
    </div>

    <div class="modal fade" role="dialog" id="preview_cart">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Thông tin đơn hàng</h4>
                    <button type="button" data-dismiss="modal" class="close">&times;</button>
                </div>
                <div class="modal-body table-responsive">
                    <table class="table table-bordered table-hover dataTable dtr-inline text-center">
                        <thead>
                        <tr>
                            <th>STT</th>
                            <th>Sản phẩm</th>
                            <th>Màu sắc</th>
                            <th>Kích cỡ</th>
                            <th>Số lượng</th>
                            <th>Đơn giá</th>
                            <th>Thành tiền</th>
                        </tr>
                        </thead>
                        <tbody id="cart_table">
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        function activelink() {
            $('.quan-ly-don-hang').addClass('menu-open');
            $('#quan-ly-don-hang').addClass('active');
            $('#sua-don-hang').addClass('active');
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

        let i = {{ count($order_details)+1 }};
        let j = 1;
        let products_ordered = new Object();
        @foreach($order_details as $order_detail)
            products_ordered[j] = {
                'i': j,
                'category_id': '{{ \App\Models\Category::getCategoryById(\App\Models\Product::getProductById($order_detail->product_id)->category_id)->id }}',
                'product_id': '{{ $order_detail->product_id }}',
                'product_attribute_value_id': '{{ $order_detail->product_attribute_value_id }}',
                'quantity': '{{ $order_detail->quantity }}',
                'total_price': '{{ $order_detail->total_price }}',
            };
            j = j + 1;
        @endforeach

        let totals = {{ $order->totals - \App\Models\Shipfee::getShipfeeById($order->ship_id)->price }}; /* Không bao gồm phí ship */
        console.log(totals);
        console.log(products_ordered);

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
                        $('#order_products_box').append('<tr><td class="text-center" colspan="8" id="no-data">Không có dữ liệu</td></tr>');
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
                else {
                    $('#quantity_'+id).val(products_ordered[id]['quantity']);
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
                alert('Vui lòng thêm ít nhất 1 sản phẩm cho đơn hàng');
                e.preventDefault();
            }
        }
    </script>
@endsection
