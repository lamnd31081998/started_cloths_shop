@foreach ($attribute_values as $attribute_value)
    <option value="{{ $attribute_value->id }}">{{ $attribute_value->value }}</option>
@endforeach

