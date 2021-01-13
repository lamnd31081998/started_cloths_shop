@php
    $links = "";
@endphp

@extends('backend.layout.page')

@section('title')
    Cài đặt đường dẫn nhanh
@endsection

@section('css')

@endsection

@section('page-title')
    Cài đặt chung
@endsection

@section('right-navbar')
    <li class="breadcrumb-item"><a href="{{ route('be.index') }}">Trang chủ</a></li>
    <li class="breadcrumb-item"><a href="#">Cài đặt chung</a></li>
    <li class="breadcrumb-item active">Cài đặt đường dẫn nhanh</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12 col-lg-12 col-md-12 col-sm-12 col-xl-12 col-xxl-12 col-xs-12">
            <form method="post" action="{{ route('be.general.update_fast_links') }}">
                @csrf
                @method('put')
                <div class="card card-primary">
                    <div class="card-header">
                        <h4 class="card-title">Cài đặt đường dẫn nhanh</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Đường dẫn <span style="color: red">(*)</span></label>
                            <div class="form-group">
                                <div class="form-check-inline">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" value="1" name="http_type" checked>http
                                    </label>
                                </div>
                                <div class="form-check-inline">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" value="2" name="http_type">https
                                    </label>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-append">
                                    <span class="input-group-text" id="http_type">
                                        http://
                                    </span>
                                </div>
                                <input placeholder="ex: facebook.com..." class="form-control" type="text" id="link">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Tên hiển thị <span style="color: red">(*)</span></label>
                            <div class="input-group mb-3">
                                <input placeholder="ex: Facebook" class="form-control" type="text" id="name">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-info"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button class="btn btn-primary" type="button" id="add_link" onclick="updatelinks({{ count($fast_links)+1 }})">Thêm đường dẫn</button>
                        </div>

                        <div class="form-group table-responsive">
                            <table class="table text-center table-bordered table-hover dataTable dtr-inline">
                                <thead>
                                    <tr>
                                        <th>Tên hiển thị</th>
                                        <th>Đường dẫn</th>
                                        <th>Chức năng</th>
                                    </tr>
                                </thead>
                                <tbody id="links_box">
                                    @if(count($fast_links) == 0)
                                        <tr>
                                            <td id="no-data" colspan="4">Không có dữ liệu</td>
                                        </tr>
                                    @else
                                        @foreach($fast_links as $index=>$fast_link)
                                            <tr class="link_{{ $index+1 }}">
                                                <td>
                                                    @if ($fast_link->name == "")
                                                        <input type="hidden" name="description[]" id="description_{{ $index+1 }}" class="form-control" value="{{ $fast_link->description }}">
                                                        <div id="edit_name_box_{{ $index+1 }}">
                                                            {{ $fast_link->description }}
                                                        </div>
                                                    @else
                                                        {{ $fast_link->description }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($fast_link->name == "")
                                                        <input type="hidden" name="link[]" id="link_{{ $index+1 }}" class="form-control" value="{{ $fast_link->link }}">
                                                        <div id="edit_link_box_{{ $index+1 }}">
                                                            <a target="_blank" href="{{ $fast_link->link }}">{{ $fast_link->link }}</a>
                                                        </div>
                                                    @else
                                                        <a target="_blank" href="{{ $fast_link->link }}">{{ $fast_link->link }}</a>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($fast_link->name == "")
                                                        <button type="button" title="Xác nhận" id="confirm_fastlink_button_{{ $index+1 }}" class="btn btn-primary" style="margin-right: 10px; display: none" onclick="confirm_fastlink({{ $index+1 }})"><i class="fas fa-check"></i></button>
                                                        <button type="button" title="Sửa đường dẫn" id="edit_fastlink_button_{{ $index+1 }}" class="btn btn-primary" style="margin-right: 10px;" onclick="edit_fastlink({{ $index+1 }})"><i class="fas fa-edit"></i></button>
                                                        <button title="Xóa đường dẫn" class="btn btn-danger" type="button" onclick="deleteLinks({{ $index+1 }})"><i class="fas fa-trash"></i></button>
                                                    @else
                                                        Đường dẫn mặc định không thể chỉnh sửa
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <span style="color: red; font-weight: 700">Nếu bạn muốn quay trở về đường dẫn ban đầu, vui lòng nhấn phím F5</span>
                        <button class="btn btn-primary" type="submit" style="float: right">Cập nhật</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection

@section('js')
    <script>
        function activelink() {
            $('.cai-dat-chung').addClass('menu-open');
            $('#cai-dat-chung').addClass('active');
            $('#duong-dan-nhanh').addClass('active');
            console.log($('#links').val());
        }

        let i = 0;

        $('input[name="http_type"]').on('change', function () {
            if (this.value == 1) {
                $('#http_type').html('http://');
            }
            else {
                $('#http_type').html('https://');
            }
        })

        function updatelinks(id) {
            if ($('#link').val() === "" || $('#name').val() === "") {
                alert('Vui lòng nhập đường dẫn và tên hiển thị');
            }
            else {
                let link = $('#link').val();
                let name = $('#name').val();
                let http_type = $('#http_type').text();
                $('#no-data').remove();
                $('#links_box').append(
                    '<tr class="link_' + id + '">' +
                    '<td>' +
                        '<input type="hidden" name="description[]" id="description_'+id+'" class="form-control" value="' + name + '">' +
                        '<div id="edit_name_box_'+id+'"> ' +
                            + name +
                        '</div>' +
                    '</td>' +
                    '<td>' +
                        '<input type="hidden" name="link[]" id="link_'+id+'" class="form-control" value="'+http_type.trim()+link.trim()+'">' +
                        '<div id="edit_link_box_'+id+'">' +
                            '<a target="_blank" href="'+http_type.trim()+link.trim()+'">'+http_type.trim()+link.trim()+'</a>' +
                        '</div>' +
                    '</td>' +
                    '<td>' +
                        '<button type="button" id="confirm_fastlink_button_'+id+'"  class="btn btn-primary" style="margin-right: 10px; display: none" title="Xác nhận" onclick="confirm_fastlink('+id+')"><i class="fas fa-check"></i></button>' +
                        '<button type="button" id="edit_fastlink_button_'+id+'" class="btn btn-primary" style="margin-right: 10px" title="Sửa đường dẫn" onclick="edit_fastlink('+id+')"><i class="fas fa-edit"></i></button>' +
                        '<button class="btn btn-danger" type="button" onclick="deleteLinks('+id+')"><i class="fas fa-trash"></button>' +
                    '</td>' +
                    '</tr>'
                );
                i = id + 1;
                $('#link').val('');
                $('#name').val('');
                $('#add_link').prop('onclick', '').off('click');
                $('#add_link').on('click', function () {
                    updatelinks(i);
                })
                let links = $('#links').val();
                $('#links').val(links+','+(i-1));
            }
        }

        function edit_fastlink(id) {
            let name = $('#description_'+id).val();
            let link = $('#link_'+id).val();
            $('#edit_fastlink_button_'+id).css('display', 'none');
            $('#confirm_fastlink_button_'+id).css('display', '');
            $('#edit_name_box_'+id).html('<input type="text" id="edit_name_'+id+'" value="'+name+'" class="form-control">');
            $('#edit_link_box_'+id).html('<input type="text" id="edit_link_'+id+'" value="'+link+'" class="form-control">');
        }

        function confirm_fastlink(id) {
            let name = $('#edit_name_'+id).val();
            let link = $('#edit_link_'+id).val();
            $('#description_'+id).val(name);
            $('#link_'+id).val(link);
            $('#edit_name_box_'+id).html(name);
            $('#edit_link_box_'+id).html('<a target="_blank" href="'+link+'">'+link+'</a>');
            $('#confirm_fastlink_button_'+id).css('display', 'none');
            $('#edit_fastlink_button_'+id).css('display', '');
        }

        function deleteLinks(id) {
            if (confirm('Bạn có chắn chắn muốn xóa link này không?')) {
                if (typeof $('#fast_link_id_'+id).val() !== 'undefined') {
                    let fast_link_id = $('#fast_link_id_'+id).val();
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                        },
                        method: 'delete',
                        url: '{{ route('be.general.delete_fast_links') }}',
                        data: { fast_link_id : fast_link_id },
                        dataType: 'json',
                        statusCode: {
                            200: function (response) {

                            },

                            500: function (response) {
                                console.log(JSON.parse(response.responseText));
                            }
                        }
                    });
                }
                $('.link_' + id).remove();
                let links = $('#links').val();
                $('#links').val(links.replace(id, ''));
            }
        }
    </script>
@endsection
