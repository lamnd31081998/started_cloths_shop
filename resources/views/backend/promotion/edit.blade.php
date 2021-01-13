@extends('backend.layout.page')

@section('title')
    Sửa mã giảm giá
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
    <li class="breadcrumb-item active">Sửa mã giảm giá</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12 col-lg-12 col-xs-12 col-md-12 col-sm-12 col-xxl-12 col-xl-12">
            <form method="post" action="{{ route('be.promotion.update', ['id' => $promotion->id]) }}" id="create_promotion">
                @csrf
                @method('put')
                <div class="card card-primary">
                    <div class="card-header">
                        <h4 class="card-title">Sửa mã giảm giá</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="code">Mã giảm giá <span style="color: red">(*)</span></label>
                            <div class="input-group mb-3">
                                <input disabled value="{{ $promotion->code }}" class="form-control" name="code" type="text" placeholder="Mã giảm giá">
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
                                <input disabled value="{{ $promotion->discount }}"  class="form-control" type="number" name="discount" min="1" placeholder="Mức giảm giá (%)">
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
                                    <input disabled value="{{ number_format($promotion->at_least, 0, '', ',') }}" class="form-control" type="text" id="at_least" name="at_least" placeholder="Yêu cầu đơn hàng tối thiểu (vnđ)">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-info-circle"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="start_time">Ngày bắt đầu (Ngày/Tháng/Năm) <span style="color: red">(*)</span></label>
                            <div class="input-group mb-3">
                                @if (date('Ymd', strtotime($promotion->start_time)) != date('Ymd', strtotime($promotion->created_at)))
                                    <input value="{{ $promotion->start_time }}" required oninput="this.setCustomValidity('')" oninvalid="this.setCustomValidity('Vui lòng nhập ngày bắt đầu')" type="date" class="form-control" name="start_time" id="start_time">
                                @else
                                    <input disabled value="{{ $promotion->start_time }}" required oninput="this.setCustomValidity('')" oninvalid="this.setCustomValidity('Vui lòng nhập ngày bắt đầu')" type="date" class="form-control" name="start_time" id="start_time">
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-check">
                                @if (date('Ymd', strtotime($promotion->start_time)) != date('Ymd', strtotime($promotion->created_at)))
                                    <input type="checkbox" id="default_start_time" class="form-check-input">
                                @else
                                    <input checked type="checkbox" id="default_start_time" class="form-check-input">
                                @endif
                                <label class="form-check-label" for="default_start_time">Ngày mặc định</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="expire_time">Ngày hết hạn (Ngày/Tháng/Năm) <span style="color: red">(*)</span></label>
                            <div class="input-group mb-3">
                                @if (!is_null($promotion->expire_time))
                                    <input value="{{ $promotion->expire_time }}" required oninput="this.setCustomValidity('')" oninvalid="this.setCustomValidity('Vui lòng nhập ngày hết hạn')" type="date" class="form-control" name="expire_time" id="expire_time">
                                @else
                                    <input disabled required oninput="this.setCustomValidity('')" oninvalid="this.setCustomValidity('Vui lòng nhập ngày hết hạn')" type="date" class="form-control" name="expire_time" id="expire_time">
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-check">
                                @if (!is_null($promotion->expire_time))
                                    <input type="checkbox" id="default_expire_time" class="form-check-input">
                                @else
                                    <input checked type="checkbox" id="default_expire_time" class="form-check-input">
                                @endif
                                <label class="form-check-label" for="default_expire_time">Nếu bạn chọn tùy chọn này, ngày hết hạn sẽ không được thiết lập, bạn có thể tùy chỉnh sau nếu muốn</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description">Mô tả <span style="color: red">(*)</span></label>
                            <div class="input-group mb-3">
                                <input value="{{ $promotion->description }}" required oninput="this.setCustomValidity('')" oninvalid="this.setCustomValidity('Vui lòng nhập mô tả')" class="form-control" type="text" name="description" placeholder="Mô tả mã giảm giá">
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
                                <input value="{{ $promotion->max_use }}" required oninput="this.setCustomValidity('')" oninvalid="this.setCustomValidity('Vui lòng nhập số lượt sử dụng tối đa của mã giảm giá')" class="form-control" type="number" id="max_use" name="max_use" placeholder="Số lượt sử dụng tối đa của mã giảm giá">
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
                                <input value="{{ $promotion->max_user_use }}" required oninput="this.setCustomValidity('')" oninvalid="this.setCustomValidity('Vui lòng nhập số lượt dùng mã giảm giá tối đa của mỗi số điện thoại')" class="form-control" type="number" id="max_user_use" name="max_user_use" placeholder="Số lượt dùng mã giảm giá tối đa của mỗi số điện thoại">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-info-circle"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="button" onclick="checkCode()" class="btn btn-primary" style="float: right">Sửa mã giảm giá</button>
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
            $('#sua-ma-giam-gia').addClass('active');
        }

        $('#default_start_time').change(function () {
            if ($(this).is(':checked')) {
                $('#start_time').prop('disabled', true);
                $("#start_time").val('{{ $promotion->start_time }}');
            }
            else {
                $('#start_time').prop('disabled', false);
            }
        })

        $('#default_expire_time').change(function () {
            if ($(this).is(':checked')) {
                $('#expire_time').prop('disabled', true).val('');
            }
            else {
                $('#expire_time').prop('disabled', false).val('{{ $promotion->expire_time }}');
            }
        })

        function checkCode() {
            let code = $('#code').val();
            if (parseInt($('#max_use').val()) < parseInt($('#max_user_use').val())) {
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
                        id: {{ $promotion->id }},
                        code: code
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
                        200:function () {
                            $('#create_promotion').submit();
                        }
                    }
                });
            }
        }
    </script>
@endsection
