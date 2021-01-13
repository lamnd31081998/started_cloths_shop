@extends('backend.layout.page')

@section('title')
    Danh sách thuộc tính
@endsection

@section('css')

@endsection

@section('page-title')
    Quản lý thuộc tính
@endsection

@section('right-navbar')
    <li class="breadcrumb-item"><a href="{{ route('be.index') }}">Trang chủ</a></li>
    <li class="breadcrumb-item active">Quản lý thuộc tính</li>
@endsection

@section('searchbar')

@endsection

@section('content')
    <div class="row">
        <div class="col-12 col-lg-12 col-md-12 col-sm-12 col-xl-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h4 class="card-title">Danh sách thuộc tính</h4>
                </div>
                <div class="card-body">
                    <table class="table table-hover table-bordered dataTable dtr-inline text-center">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tên thuộc tính</th>
                                <th>Thời gian tạo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($attributes as $attribute)
                                <tr>
                                    <td>{{ $attribute->id }}</td>
                                    <td>{{ $attribute->name }}</td>
                                    <td>{{ date('d-m-Y H:i:s', strtotime($attribute->created_at)) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{-- /.card-body --}}
                <div class="card-footer">
                    Đang xem thuộc tính {{ $attributes->firstitem() }} - {{ $attributes->lastitem() }} trên tổng số {{ $attributes->total() }} thuộc tính
                </div>
            </div>
            {{-- /.card --}}
        </div>
    </div>
@endsection

@section('js')
    <script>
        function activelink() {
            $('#quan-ly-thuoc-tinh').addClass('active');
        }
    </script>
@endsection
