<label>Biến thể đã tạo</label>
<table class="text-center table table-bordered table-hover dataTable dtr-inline" id="variants_table" style="width: 100%">
    <thead>
    <tr>
        <th>STT</th>
        <th>Màu sắc</th>
        <th>Kích cỡ</th>
        <th>Số lượng</th>
    </tr>
    </thead>
    <tbody>
    @foreach($variants as $index=>$variant)
        <tr>
            <td>{{ ($index+1) }}</td>
            <td>
                <input type="hidden" name="attribute_value_{{ ($index+1) }}_1" id="attribute_value_{{ ($index+1) }}_1" value="{{ $variant['color_id'] }}">
                {{ \App\Models\Attribute_value::getAttributevalueById($variant['color_id'])->value }}
            </td>
            <td>
                <input type="hidden" name="attribute_value_{{ ($index+1) }}_2" id="attribute_value_{{ ($index+1) }}_2" value="{{ $variant['size_id'] }}">
                {{ \App\Models\Attribute_value::getAttributevalueById($variant['size_id'])->value }}
            </td>
            <td><input class="form-control" type="number" name="quantity_{{ ($index+1) }}" id="quantity_{{ ($index+1) }}" required oninvalid="this.setCustomValidity('Vui lòng nhập số lượng')" oninput="this.setCustomValidity('')"></td>
        </tr>
    @endforeach
    </tbody>
</table>

<script>
    var table = $('#variants_table').DataTable({
        pageLength: 50,
        ordering: false,
        "oLanguage": {
            "sInfo" : "Đang xem biến thể _START_ - _END_ trên tổng số _TOTAL_ biến thể",
            "sSearch" : "Tìm kiếm",
            "sLengthMenu" :  'Hiển thị <select class="custom-select custom-select-sm form-control form-control-sm">' +
                '<option value="10">10</option>' +
                '<option value="25">25</option>' +
                '<option value="50">50</option>' +
                '</select> bản ghi',
            "sZeroRecords" : 'Không có dữ liệu',
            "oPaginate" : {
                "sNext" : '>>',
                "sPrevious" : '<<'
            }
        }
    });
</script>
