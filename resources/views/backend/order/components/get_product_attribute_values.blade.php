<option value="0">--- Vui lòng chọn màu sắc & kích cỡ ---</option>
@foreach($product_attribute_values as $product_attribute_value)
    <option value="{{ $product_attribute_value->id }}">{{ \App\Models\Attribute_value::getAttributevalueById($product_attribute_value->color_id)->value }} - {{ \App\Models\Attribute_value::getAttributevalueById($product_attribute_value->size_id)->value }} - {{ $product_attribute_value->quantity }}</option>
@endforeach
