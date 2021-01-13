@extends('backend.layout.page')

@section('title')
    Sửa tài khoản
@endsection

@section('css')

@endsection

@section('page-title')
    Quản lý tài khoản
@endsection

@section('right-navbar')
    <li class="breadcrumb-item"><a href="{{ route('be.index') }}">Trang chủ</a></li>
    <li class="breadcrumb-item"><a href="#">Quản lý tài khoản</a></li>
    <li class="breadcrumb-item"><a href="{{ route('be.admin.index') }}">Danh sách quản lý</a></li>
    <li class="breadcrumb-item active">Sửa tài khoản quản lý</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12 col-lg-12 col-md-12 col-sm-12 col-xl-12">
            <form action="{{ route('be.admin.update', ['id' => $admin->id]) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="card card-primary">
                    <div class="card-header">
                        <h4>Sửa tài khoản: {{ $admin->username }}</h4>
                    </div>
                    <div class="card-body">
                        {{-- Username --}}
                        <div class="form-group">
                            <label>Tên tài khoản <span style="color: red">(*)</span></label>
                            <div class="input-group mb-3">
                                <input class="form-control" type="text" value="{{ $admin->username }}" disabled>
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
                            <div class="input-group">
                                <input placeholder="Email" required class="form-control" oninvalid="this.setCustomValidity('Vui lòng nhập email')" oninput="this.setCustomValidity('')" type="email" name="email" value="{{ old('email') != null ? old('email') : $admin->email }}">
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
                                <input placeholder="Tên đầy đủ" required class="form-control" oninvalid="this.setCustomValidity('Vui lòng nhập tên đầy đủ')" oninput="this.setCustomValidity('')" type="text" name="name" value="{{ old('name') != null ? old('name') : $admin->name }}">
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
                            <div class="form-check">
                                <label class="form-check-label">
                                    @if ($admin->gender == 1)
                                        <input type="radio" class="form-check-input" id="male" value="1" name="gender" checked>Nam
                                    @else
                                        <input type="radio" class="form-check-input" id="male" value="1" name="gender">Nam
                                    @endif
                                </label>
                            </div>
                            <div class="form-check">
                                <label class="form-check-label">
                                    @if ($admin->gender == 2)
                                        <input type="radio" class="form-check-input" id="female" value="2" name="gender" checked>Nữ
                                    @else
                                        <input type="radio" class="form-check-input" id="female" value="2" name="gender">Nữ
                                    @endif
                                </label>
                            </div>
                        </div>

                        {{-- Phone Number--}}
                        <div class="form-group">
                            <label>Số điện thoại <span style="color: red">(*)</span> </label>
                            <div class="input-group mb-3">
                                <input placeholder="Số điện thoại" required oninvalid="this.setCustomValidity('Vui lòng nhập số điện thoại')" oninput="this.setCustomValidity('')" class="form-control" name="phone_number" value="{{ old('phone_number') != null ? old('phone_number') : $admin->phone_number }}">
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
                                <input name="birthday" type="date" class="form-control" value="{{ old('birthday') != null ? old('birthday') : $admin->birthday }}">
                            </div>
                        </div>

                        {{-- Address --}}
                        <div class="form-group">
                            <label>Địa chỉ cụ thể <span style="color: red">(*)</span></label>
                            <div class="input-group mb-3">
                                <input required oninvalid="this.setCustomValidity('Vui lòng nhập địa chỉ cụ thể')" oninput="this.setCustomValidity('')" name="address" class="form-control" value="{{ old('address') != null ? old('address') : $admin->address }}">
                            </div>
                        </div>

                        {{-- Tinh --}}
                        <div class="form-group">
                            <div class="form-group" id="tinh_box">
                                <label>Thành phố<span style="color: red">(*)</span></label>
                                <select id="tinh_id" name="tinh_id" class="form-control" onchange="getHuyen()" required oninvalid="this.setCustomValidity('Vui lòng chọn thành phố')" oninput="this.setCustomValidity('')">
                                    <option value="">--- Chọn thành phố ---</option>
                                    @foreach($tinhs as $tinh)
                                        @if ($admin->tinh_id == $tinh->id)
                                            <option value="{{ $tinh->id }}" selected>{{ $tinh->name }}</option>
                                        @else
                                            <option value="{{ $tinh->id }}">{{ $tinh->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            {{-- Huyen --}}
                            <div id="huyen_box" class="form-group">
                                <label>Quận<span style="color: red">(*)</span></label>
                                <select required oninvalid="this.setCustomValidity('Vui lòng chọn quận')" oninput="this.setCustomValidity('')" id="huyen_id" name="huyen_id" class="form-control" onchange="getXa()">
                                    <option value="">--- Chọn quận ---</option>
                                    @foreach($huyens as $huyen)
                                        @if ($admin->huyen_id == $huyen->id )
                                            <option value="{{ $huyen->id }}" selected>{{ $huyen->name }}</option>
                                        @elseif ($admin->tinh_id == $huyen->tinh_id)
                                            <option value="{{ $huyen->id }}">{{ $huyen->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            {{-- Xa --}}
                            <div id="xa_box" class="form-group">
                                    <label>Phường<span style="color: red">(*)</span></label>
                                    <select required oninvalid="this.setCustomValidity('Vui lòng chọn phường')" oninput="this.setCustomValidity('')" id="xa_id" name="xa_id" class="form-control">
                                        <option value="">--- Chọn phường ---</option>
                                        @foreach($xas as $xa)
                                            @if ($admin->xa_id == $xa->id )
                                                <option value="{{ $xa->id }}" selected>{{ $xa->name }}</option>
                                            @elseif ($admin->huyen_id == $xa->huyen_id)
                                                <option value="{{ $xa->id }}">{{ $xa->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                            </div>
                        </div>
                    </div>
                    {{-- /.card-body --}}
                    <div class="card-footer">
                        <button style="float: right" type="submit" class="btn btn-primary">Sửa tài khoản</button>
                        <button style="float: right; margin-right: 10px;" type="reset" class="btn btn-danger">Nhập lại</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
    <script>
        function activelink() {
            $('.quan-ly-tai-khoan').addClass('menu-open');
            $('#quan-ly-tai-khoan').addClass('active');
            $('#sua-tai-khoan').addClass('active');
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
                dataType: 'json',
                success: function (response) {
                    if(response.status === 200) {
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
                dataType: 'json',
                success: function (response) {
                    if(response.status === 200) {
                        $('#xa_box').html(response.view);
                    }
                }
            })
        }
    </script>
@endsection
