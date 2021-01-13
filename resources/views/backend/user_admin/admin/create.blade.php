@extends('backend.layout.page')

@section('title')
    Thêm tài khoản quản lý
@endsection

@section('css')

@endsection

@section('page-title')
    Quản lý tài khoản
@endsection

@section('right-navbar')
    <li class="breadcrumb-item"><a href="{{ route('be.admin.index') }}">Trang chủ</a></li>
    <li class="breadcrumb-item"><a href="#">Quản lý tài khoản</a></li>
    <li class="breadcrumb-item"><a href="{{ route('be.admin.index') }}">Danh sách quản lý</a></li>
    <li class="breadcrumb-item active">Thêm tài khoản quản lý</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12 col-md-12 col-lg-12 col-lg-12 col-md-12 col-sm-12 col-xl-12">
            <form action="{{ route('be.admin.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="card card-primary">
                    <div class="card-header">
                        <h4 class="card-title">Thêm tài khoản quản lý</h4>
                    </div>
                    <div class="card-body">
                        {{-- Username --}}
                        <div class="form-group">
                            <label>Tên tài khoản <span style="color: red">(*)</span></label>
                            <div class="input-group mb-3">
                                <input class="form-control" required oninvalid="this.setCustomValidity('Vui lòng nhập tên tài khoản')" oninput="this.setCustomValidity('')" type="text" name="username" placeholder="Tên tài khoản" value="{{ old('username') }}">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                </div>
                            </div>
                        </div>
                        @if ($errors->has('username'))
                            <p style="color: red">{{ $errors->first('username') }}</p>
                        @endif

                        {{-- Email --}}
                        <div class="form-group">
                            <label>Email <span style="color: red">(*)</span></label>
                            <div class="input-group mb-3">
                                <input class="form-control" required oninvalid="this.setCustomValidity('Vui lòng nhập email')" oninput="this.setCustomValidity('')" type="email" name="email" placeholder="Email" value="{{ old('email') }}">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        @if ($errors->has('email'))
                            <p style="color: red">{{ $errors->first('email') }}</p>
                        @endif

                        {{-- Password --}}
                        <div class="form-group">
                            <label>Mật khẩu <span style="color: red">(*)</span></label>
                            <div class="input-group mb-3">
                                <input required oninvalid="this.setCustomValidity('Vui lòng nhập mật khẩu')" oninput="this.setCustomValidity('')" type="password" name="password" placeholder="Mật khẩu" class="form-control" value="{{ old('password') }}">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Confirm password --}}
                        <div class="form-group">
                            <label>Nhập lại mật khẩu <span style="color: red">(*)</span></label>
                            <div class="input-group mb-3">
                                <input required oninvalid="this.setCustomValidity('Vui lòng nhập lại mật khẩu')" oninput="this.setCustomValidity('')" type="password" name="password_confirmation" placeholder="Mật khẩu" class="form-control" value="{{ old('retype_password') }}">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        @if ($errors->has('password'))
                            <p style="color: red">{{ $errors->first('password') }}</p>
                        @endif

                        {{-- Full Name --}}
                        <div class="form-group">
                            <label>Tên đầy đủ <span style="color: red">(*)</span></label>
                            <div class="input-group mb-3">
                                <input required oninvalid="this.setCustomValidity('Vui lòng nhập tên đầy đủ')" oninput="this.setCustomValidity('')" class="form-control" type="text" name="name" placeholder="Tên đầy đủ" value="{{ old('name') }}">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-user"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Gender--}}
                        <div class="form-group">
                            <label>Giới tính <span style="color: red">(*)</span></label>
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" id="male" value="1" name="gender" checked>Nam
                                </label>
                            </div>
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" id="female" value="2" name="gender">Nữ
                                </label>
                            </div>
                        </div>

                        {{-- Phone Number --}}
                        <div class="form-group">
                            <label>Số điện thoại <span style="color: red">(*)</span> </label>
                            <div class="input-group mb-3">
                                <input class="form-control" required oninvalid="this.setCustomValidity('Vui lòng nhập số điện thoại')" oninput="this.setCustomValidity('')" type="text" name="phone_number" value="{{ old('phone_number') }}" placeholder="Số điện thoại" pattern="[0]{1}[1-9]{1}[0-9]{8}">
                                <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="fas fa-phone"></i>
                                </span>
                                </div>
                            </div>
                        </div>

                        {{-- Birthday --}}
                        <div class="form-group">
                            <label>Ngày sinh</label>
                            <div class="input-group mb-3">
                                <input class="form-control" type="date" name="birthday">
                            </div>
                        </div>

                        {{-- Address --}}
                        <div class="form-group" id="address_default">
                            <label>Địa chỉ cụ thể <span style="color: red">(*)</span></label>
                            <div class="input-group mb-3">
                                <input class="form-control" type="text" name="address" placeholder="Địa chỉ cụ thể số nhà, ngõ, ..." required oninvalid="this.setCustomValidity('Vui lòng nhập địa chỉ cụ thể')" oninput="this.setCustomValidity('')">
                            </div>
                        </div>

                        <div class="form-group" id="thanhpho_box">
                            <label>Thành phố <span style="color: red">(*)</span></label>
                            <select required  name="tinh_id" id="tinh_id" class="form-control" onchange="getHuyen()" oninvalid="this.setCustomValidity('Vui lòng chọn thành phố')" oninput="this.setCustomValidity('')">
                                <option value="">--- Chọn thành phố ---</option>
                                @foreach($tinhs as $tinh)
                                    <option value="{{ $tinh->id }}">{{ $tinh->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div id="huyen_box" class="form-group">
                            <label>Quận <span style="color: red">(*)</span></label>
                            <select required oninvalid="this.setCustomValidity('Vui lòng chọn quận')" oninput="this.setCustomValidity('')"  name="huyen_id" id="huyen_id" class="form-control">
                                <option value="">--- Chọn quận ---</option>
                            </select>
                        </div>
                        <div id="xa_box" class="form-group">
                                <label>Phường <span style="color: red">(*)</span></label>
                                <select required oninvalid="this.setCustomValidity('Vui lòng chọn phường')" oninput="this.setCustomValidity('')"  name="xa_id" id="xa_id" class="form-control">
                                    <option value="">--- Chọn phường ---</option>
                                </select>
                        </div>
                    </div>
                    {{-- /.card-body --}}
                    <div class="card-footer">
                        <button style="float:right" type="submit" class="btn btn-primary">Thêm tài khoản</button>
                        <button style="float: right; margin-right: 10px" type="reset" class="btn btn-danger">Nhập lại</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
    <script>
        function activelink() {
            $('#quan-ly-tai-khoan').addClass('active');
            $('.quan-ly-tai-khoan').addClass('menu-open');
            $('#them-tai-khoan').addClass('active');
            $("#tinh_id").select2();
            $('#huyen_id').select2();
            $('#xa_id').select2();
        }

        $(".custom-file-input").on("change", function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);

            if (this.files && this.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#avatar_preview').css('display', '').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]);
            }
        });

        function getHuyen() {

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: 'post',
                url: '{{ route('be.get_huyen') }}',
                data: {tinh_id : $('#tinh_id').val()},
                dataType: 'json',
                success: function (response) {
                    if (response.status === 200) {
                        $('#huyen_box').html(response.view);
                        getXa();
                    }
                }
            });
        }

        function getXa() {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: 'post',
                url: '{{ route('be.get_xa') }}',
                data: { huyen_id : $('#huyen_id').val()},
                dataType: 'json',
                success: function (response) {
                    if (response.status === 200) {
                        $('#xa_box').html(response.view);
                    }
                }
            });
        }
    </script>
@endsection
