<label>Biến thể đã tạo</label>
<table class="text-center table table-bordered table-hover dataTable dtr-inline" id="variants_table" style="width: 100%">
    <thead>
    <tr>
        <th>ID</th>
        <th>Màu sắc</th>
        <th>Kích cỡ</th>
        <th>Số lượng</th>
    </tr>
    </thead>
    <tbody>
    @foreach($product_attribute_values as $product_attribute_value)
        <tr>
            <td>{{ $product_attribute_value->id }}</td>
            <td>{{ \App\Models\Attribute_value::getAttributevalueById($product_attribute_value->color_id)->value }}</td>
            <td>{{ \App\Models\Attribute_value::getAttributevalueById($product_attribute_value->size_id)->value }}</td>
            <td><input class="form-control" type="number" name="quantity_{{ $product_attribute_value->id }}" id="quantity_{{ $product_attribute_value->id }}" value="{{ $product_attribute_value->quantity }}" required oninvalid="this.setCustomValidity('Vui lòng nhập số lượng')" oninput="this.setCustomValidity('')"></td>
        </tr>
    @endforeach
    </tbody>
</table>

<script>
    var table = $('#variants_table').DataTable({
        ordering: false,
        "oLanguage": {
            "sInfo" : "Đang xem biến thể _START_ - _END_ trên tổng số _TOTAL_ biến thể",
            "sSearch" : "Tìm kiếm",
            "sLengthMenu" :  'Hiển thị <select class="custom-select custom-select-sm form-control form-control-sm">' +
                '<option value="10">10</option>' +
                '<option value="25">25</option>' +
                '<option value="50">50</option>' +
                '</select> bản ghi',
            "oPaginate" : {
                "sNext" : '>>',
                "sPrevious" : '<<'
            }
        }
    });
</script>
