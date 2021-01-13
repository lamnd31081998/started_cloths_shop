@extends('backend.layout.page')

@section('title')
    Thông tin cá nhân
@endsection

@section('css')

@endsection

@section('page-title')
    Thông tin cá nhân
@endsection

@section('right-navbar')
    <li class="breadcrumb-item"><a href="{{ route('be.index') }}">Trang chủ</a></li>
    <li class="breadcrumb-item active">Thông tin cá nhân</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12 col-xl-12 col-sm-12 col-md-12 col-lg-12">
            <form method="post" action="{{ route('be.index.update_self_information') }}" enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="card card-primary">
                    <div class="card-header">
                        <h4 class="card-title">Chỉnh sửa thông tin cá nhân</h4>
                    </div>
                    <div class="card-body">
                        {{-- Username --}}
                        <div class="form-group">
                            <label>Tên tài khoản <span style="color: red">(*)</span></label>
                            <div class="input-group mb-3">
                                <input class="form-control" disabled value="{{ $admin->username }}">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-user"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Email --}}
                        <div class="form-group">
                            <label>Email <span style="color: red">(*)</span></label>
                            <div class="input-group mb-3">
                                <input placeholder="Email" type="email" name="email" value="{{ old('email') != null ? old('email') : $admin->email }}" class="form-control" required oninvalid="this.setCustomValidity('Vui lòng nhập Email')" oninput="this.setCustomValidity('')">
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

                        {{-- Full Name --}}
                        <div class="form-group">
                            <label>Tên đầy đủ <span style="color: red">(*)</span></label>
                            <div class="input-group mb-3">
                                <input value="{{ old('name') != null ? old('name') : $admin->name }}" type="text" required oninvalid="this.setCustomValidity('Vui lòng nhập tên đầy đủ')" oninput="this.setCustomValidity('')" name="name" class="form-control" placeholder="Tên đầy đủ">
                                <div class="input-group-append">
                                        <span class="input-group-text">
                                            <i class="fas fa-user"></i>
                                        </span>
                                </div>
                            </div>
                        </div>

                        {{-- Gender --}}
                        <div class="form-group">
                            <label>Giới tính <span style="color: red">(*)</span></label>
                            <div class="custom-control custom-radio">
                                @if ($admin->gender == 1)
                                    <input type="radio" id="male" name="gender" value="1" class="custom-control-input" checked>
                                @else
                                    <input type="radio" id="male" name="gender" value="1" class="custom-control-input">
                                @endif
                                <label for="male" class="custom-control-label">Nam</label>
                            </div>
                            <div class="custom-control custom-radio">
                                @if ($admin->gender == 2)
                                    <input type="radio" id="female" name="gender" value="2" class="custom-control-input" checked>
                                @else
                                    <input type="radio" id="female" name="gender" value="2" class="custom-control-input">
                                @endif
                                <label for="female" class="custom-control-label">Nữ</label>
                            </div>
                        </div>

                        {{-- Phone Number --}}
                        <div class="form-group">
                            <label>Số điện thoại <span style="color: red">(*)</span></label>
                            <div class="input-group mb-3">
                                <input class="form-control" type="text" pattern="[0]{1}[1-9]{1}[0-9]{8}" name="phone_number" required oninvalid="this.setCustomValidity('Vui lòng nhập số điện thoại')" oninput="this.setCustomValidity('')" value="{{ old('phone_number') != null ? old('phone_number') : $admin->phone_number }}">
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
                                <input class="form-control" type="date" name="birthday" value="{{ old('birthday') != null ? old('birthday') : $admin->birthday }}">
                            </div>
                        </div>

                        {{-- Address --}}
                        <div class="form-group">
                            <div class="form-group" id="tinh_box">
                                <label>Thành phố <span style="color: red">(*)</span></label>
                                <select class="form-control" name="tinh_id" id="tinh_id" onchange="getHuyen()" required oninvalid="this.setCustomValidity('Vui lòng chọn thành phố')" oninput="this.setCustomValidity('')">
                                    <option value="">--- Chọn thành phố ---</option>
                                    @foreach($tinhs as $tinh)
                                        @if ($tinh->id == $admin->tinh_id)
                                            <option value="{{ $tinh->id }}" selected>{{ $tinh->name }}</option>
                                        @else
                                            <option value="{{ $tinh->id }}">{{ $tinh->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group" id="huyen_box">
                                <label>Quận <span style="color: red">(*)</span></label>
                                <select name="huyen_id" id="huyen_id" onchange="getXa()" class="form-control" required oninvalid="this.setCustomValidity('Vui lòng chọn quận')" oninput="this.setCustomValidity('')">
                                    <option value="">--- Chọn quận ---</option>
                                    @foreach ($huyens as $huyen)
                                        @if ($huyen->id == $admin->huyen_id)
                                            <option value="{{ $huyen->id }}" selected>{{ $huyen->name }}</option>
                                        @elseif ($huyen->tinh_id == $admin->tinh_id)
                                            <option value="{{ $huyen->id }}">{{ $huyen->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group" id="xa_box">
                                <div class="form-group">
                                    <label>Phường <span style="color: red">(*)</span></label>
                                    <select name="xa_id" id="xa_id" class="form-control" required oninvalid="this.setCustomValidity('Vui lòng chọn phường')" oninput="this.setCustomValidity('')">
                                        <option value="">--- Chọn phường ---</option>
                                        @foreach($xas as $xa)
                                            @if ($xa->id == $admin->xa_id)
                                                <option value="{{ $xa->id }}" selected>{{ $xa->name }}</option>
                                            @elseif ($xa->huyen_id == $admin->huyen_id)
                                                <option value="{{$xa->id}}">{{ $xa->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Địa chỉ cụ thể <span style="color: red">(*)</span></label>
                                    <div class="input-group mb-3">
                                        <input placeholder="Địa chỉ cụ thể số nhà, ngõ, ..." type="text" name="address" value="{{ old('address') != null ? old('address') : $admin->address }}" class="form-control">
                                        <div class="input-group-append">
                                            <span class="input-group-text">
                                                <i class="fas fa-info"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    {{-- /.card-body --}}
                    <div class="card-footer">
                        <a href="javascript:void(0)" data-toggle="modal" data-target="#change_password_modal">Đổi mật khẩu</a>
                        <button class="btn btn-primary" type="submit" style="float: right">Sửa thông tin</button>
                        <button class="btn btn-danger" type="reset" style="float: right; margin-right: 10px">Nhập lại</button>
                    </div>
                </div>
                {{-- /.card --}}
            </form>
        </div>
    </div>

    <div class="modal fade" id="change_password_modal" data-role="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Đổi mật khẩu</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    {{-- Old password --}}
                    <div class="form-group">
                        <label for="old_password">Mật khẩu cũ <span style="color: red">(*)</span></label>
                        <div class="input-group mb-3">
                            <input type="password" class="form-control" id="old_password" placeholder="Mật khẩu cũ (*)">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="fas fa-lock"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- New Password --}}
                    <div class="form-group">
                        <label for="password">Mật khẩu mới <span style="color: red">(*)</span></label>
                        <div class="input-group mb-3">
                            <input type="password" class="form-control" id="password" placeholder="Mật khẩu mới (*)">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="fas fa-lock"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Confirm new password --}}
                    <div class="form-group">
                        <label for="password_confirmation">Nhập lại mật khẩu mới <span style="color: red">(*)</span></label>
                        <div class="input-group mb-3">
                            <input type="password" class="form-control" id="password_confirmation" placeholder="Nhập lại mật khẩu mới (*)">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="fas fa-lock"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary" onclick="changepassword({{ $admin->id }})">Cập nhật mật khẩu</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        function activelink() {
            $('#tinh_id').select2();
            $('#huyen_id').select2();
            $('#xa_id').select2();
        }

        function getHuyen() {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: 'post',
                url: '{{ route('be.get_huyen') }}',
                data: {tinh_id : $('#tinh_id').val()},
                success: function (response) {
                    if (response.status === 200) {
                        $('#huyen_box').html(response.view);
                        getXa();
                    }
                }
            })
        }

        function getXa() {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: 'post',
                url: '{{ route('be.get_xa') }}',
                data: {huyen_id : $('#huyen_id').val()},
                success: function (response) {
                    if (response.status === 200) {
                        $('#xa_box').html(response.view);
                    }
                }
            })
        }

        function changepassword(admin_id) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                },
                method: 'put',
                url: '{{ route('be.index.update_self_password') }}',
                data: {
                    admin_id : admin_id,
                    old_password : $('#old_password').val(),
                    password : $('#password').val(),
                    password_confirmation : $('#password_confirmation').val()
                },
                dataType: 'json',
                statusCode: {
                    200: function (response) {
                        alert(response.message);
                        $('#old_password').val('');
                        $('#password').val('');
                        $('#password_confirmation').val('');
                        $('#change_password_modal').modal('hide');
                    },

                    500: function (response) {
                        console.log(JSON.parse(response.responseText));
                    },

                    400: function (response) {
                        let responseText = JSON.parse(response.responseText);
                        let errors = responseText['errors'];
                        let message = responseText['message'];
                        if (typeof message !== 'undefined') {
                            alert(message);
                        }
                        if (typeof errors['old_password'] !== 'undefined') {
                            alert(errors['old_password']);
                        }
                        else if (typeof errors['password'] !== 'undefined') {
                            alert(errors['password']);
                        }
                    },
                }
            });
        }
    </script>
@endsection
