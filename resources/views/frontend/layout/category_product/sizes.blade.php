@foreach($attribute_values as $attribute_value)
    @if ($attribute_value->quantity != 0)
        <li class="size_available">
            <input onclick="getQuantity({{ $attribute_value->quantity }})" type="radio" id="size_{{ $attribute_value->size_id }}" name="size" class="regular_radio" value="{{ $attribute_value->size_id }}">
            <label for="size_{{ $attribute_value->size_id }}">{{ \App\Models\Attribute_value::getAttributevalueById($attribute_value->size_id)->value }}</label>
        </li>
    @else
        <li>
            <input disabled type="radio" id="size_{{ $attribute_value->size_id }}" class="regular_radio">
            <label for="size_{{ $attribute_value->size_id }}">{{ \App\Models\Attribute_value::getAttributevalueById($attribute_value->size_id)->value }} (Hết hàng)</label>
        </li>
    @endif
@endforeach
