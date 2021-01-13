@extends('backend.layout.page')

@section('title')
    Thêm mã giảm giá
@endsection

@section('css')
    <style>
        @media (min-width: 1200px) {
            .modal-content {
                width: 1000px;
                margin-left: -50%;
            }
        }
    </style>
@endsection

@section('page-title')
    Quản lý mã giảm giá
@endsection

@section('right-navbar')
    <li class="breadcrumb-item"><a href="{{ route('be.index') }}">Trang chủ</a></li>
    <li class="breadcrumb-item"><a href="{{ route('be.promotion.index') }}">Quản lý mã giảm giá</a></li>
    <li class="breadcrumb-item active">Thêm mã giảm giá</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12 col-lg-12 col-xs-12 col-md-12 col-sm-12 col-xxl-12 col-xl-12">
            <form method="post" action="{{ route('be.promotion.store') }}" id="create_promotion">
                @csrf
                <div class="card card-primary">
                    <div class="card-header">
                        <h4 class="card-title">Thêm mã giảm giá</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="code">Mã giảm giá <span style="color: red">(*)</span></label>
                            <div class="input-group mb-3">
                                <input id="code" required oninput="this.setCustomValidity('')" oninvalid="this.setCustomValidity('Vui lòng nhập mã giảm giá')" class="form-control" name="code" type="text" placeholder="Mã giảm giá">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-info-circle"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="discount">Mức giảm giá (%) <span style="color:red;">(*)</span></label>
                            <div class="input-group mb-3">
                                <input required oninput="this.setCustomValidity('')" oninvalid="this.setCustomValidity('Vui lòng nhập mức giảm giá')" class="form-control" type="number" name="discount" min="1" placeholder="Mức giảm giá (%)">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-info-circle"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="at_least">Yêu cầu đơn hàng tối thiểu (vnđ) <span style="color: red">(*)</span></label>
                            <div class="input-group mb-3">
                                <input required oninput="this.setCustomValidity('')" oninvalid="this.setCustomValidity('Vui lòng nhập yêu cầu đơn hàng tối thiểu giá')" class="form-control" type="text" id="at_least" name="at_least" placeholder="Yêu cầu đơn hàng tối thiểu (vnđ)">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-info-circle"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" id="default_at_least" class="form-check-input">
                                <label class="form-check-label" for="default_at_least">Nếu bạn chọn tùy chọn này, đơn hàng tối thiểu sẽ là 0vnđ</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="start_time">Ngày bắt đầu (Ngày/Tháng/Năm) <span style="color: red">(*)</span></label>
                            <div class="input-group mb-3">
                                <input required oninput="this.setCustomValidity('')" oninvalid="this.setCustomValidity('Vui lòng nhập ngày bắt đầu')" type="date" class="form-control" name="start_time" id="start_time">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" id="default_start_time" class="form-check-input">
                                <label class="form-check-label" for="default_start_time">Nếu bạn chọn tùy chọn này, ngày bắt đầu mặc định sẽ là ngày tạo mã giảm giá</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="expire_time">Ngày hết hạn (Ngày/Tháng/Năm) <span style="color: red">(*)</span></label>
                            <div class="input-group mb-3">
                                <input required oninput="this.setCustomValidity('')" oninvalid="this.setCustomValidity('Vui lòng nhập ngày hết hạn')" type="date" class="form-control" name="expire_time" id="expire_time">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" id="default_expire_time" class="form-check-input">
                                <label class="form-check-label" for="default_expire_time">Nếu bạn chọn tùy chọn này, ngày hết hạn sẽ không được thiết lập, bạn có thể tùy chỉnh sau nếu muốn</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description">Mô tả <span style="color: red">(*)</span></label>
                            <div class="input-group mb-3">
                                <input required oninput="this.setCustomValidity('')" oninvalid="this.setCustomValidity('Vui lòng nhập mô tả')" class="form-control" type="text" name="description" placeholder="Mô tả mã giảm giá">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-info-circle"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="max_use">Số lượt sử dụng tối đa của mã giảm giá <span style="color: red">(*)</span></label>
                            <div class="input-group mb-3">
                                <input required oninput="this.setCustomValidity('')" oninvalid="this.setCustomValidity('Vui lòng nhập số lượt sử dụng tối đa của mã giảm giá')" class="form-control" type="number" id="max_use" name="max_use" placeholder="Số lượt sử dụng tối đa của mã giảm giá">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-info-circle"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="max_user_use">Số lượt dùng mã giảm giá tối đa của mỗi số điện thoại <span style="color: red">(*)</span></label>
                            <div class="input-group mb-3">
                                <input required oninput="this.setCustomValidity('')" oninvalid="this.setCustomValidity('Vui lòng nhập số lượt dùng mã giảm giá tối đa của mỗi số điện thoại')" class="form-control" type="number" id="max_user_use" name="max_user_use" placeholder="Số lượt dùng mã giảm giá tối đa của mỗi số điện thoại">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-info-circle"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="button" onclick="checkCode()" class="btn btn-primary" style="float: right">Thêm mã giảm giá</button>
                        <button type="reset" class="btn btn-danger" style="float: right; margin-right: 10px">Nhập lại</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection

@section('js')
    <script>
        function activelink() {
            $('.ma-giam-gia').addClass('menu-open');
            $('#ma-giam-gia').addClass('active');
            $('#them-ma-giam-gia').addClass('active');
            $('#at_least').simpleMoneyFormat();
        }

        $('#default_at_least').change(function () {
            if ($(this).is(':checked')) {
                $('#at_least').prop('disabled', true);
            }
            else {
                $('#at_least').prop('disabled', false);
            }
        })

        $('#default_start_time').change(function () {
            if ($(this).is(':checked')) {
                $('#start_time').prop('disabled', true);
            }
            else {
                $('#start_time').prop('disabled', false);
            }
        })

        $('#default_expire_time').change(function () {
            if ($(this).is(':checked')) {
                $('#expire_time').prop('disabled', true);
            }
            else {
                $('#expire_time').prop('disabled', false);
            }
        })

        function checkCode() {
            let code = $('#code').val();
            if (parseInt($('#max_use').val()) < parseInt($('#max_user_use').val())) {
                console.log($('#max_use').val());
                console.log($('#max_user_use').val());
                alert('Số lượt sử dụng tối đa của mã giảm giá không được nhỏ hơn số lượt dùng mã giảm giá tối đa của mỗi số điện thoại')
            }
            else {
                $.ajax({
                    headers: {
                       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'get',
                    url: '{{ route('be.promotion.check_promotion') }}',
                    data: {
                        code: code
                    },
                    dataType: 'json',
                    statusCode: {
                        400: function (response) {
                            let responseText = JSON.parse(response.responseText);
                            alert(responseText['message']);
                        },
                        200:function () {
                            $('#create_promotion').submit();
                        }
                    }
                });
            }
        }
    </script>
@endsection
