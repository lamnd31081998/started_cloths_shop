<option value="0">--- Vui lòng chọn sản phẩm ---</option>
@foreach($products as $product)
    <option value="{{ $product->id }}">{{ $product->name }} - {{ $product->sale_price != "" ? number_format($product->sale_price, 0, '', '.') : number_format($product->price, 0, '', '.') }}vnđ</option>
@endforeach
