@if (!isset($products_ordered))
<tr id="product_{{ $i }}">
    <td>{{ $i }}</td>
    <td>{{ $category->name }}</td>
    <td>
        <input type="hidden" name="products[]" value="{{ $product->id }}" id="product_id_{{ $i }}">
        <?php
            $product_slug = $product->slug;
            $category_slug = $category->slug;
            $category_dad_slug = \App\Models\Category::getCategoryById($category->parent_id)->slug;
        ?>
        <a title="Xem sản phẩm" target="_blank" href="{{ route('fe.product.detail', ['category_dad_slug' => $category_dad_slug, 'category_slug' => $category_slug, 'product_slug' => $product_slug]) }}">{{ $product->name }}</a>
    </td>
    <input type="hidden" name="product_attribute_values[]" id="product_attribute_value_id_{{ $i }}" value="{{ $product_attribute_value->id }}">
    <td>{{ $color->value }}</td>
    <td>{{ $size->value }}</td>
    <td>
        <input type="hidden" id="price_{{ $i }}" value="{{ $price }}">
        {{ number_format($price, 0, '', '.') }}vnđ
    </td>
    <td><input id="quantity_{{ $i }}" type="number" name="quantities[]" min="0" class="form-control" onchange="calcTotals(this,{{ $i }})" value="{{ $quantity }}"></td>
    <td>{{ number_format($price * $quantity, 0, '', '.') }}vnđ</td>
</tr>
@else
    @foreach($products_ordered as $index=>$product_ordered)
        <tr id="product_{{ $index }}">
            <td>{{ $index }}</td>
            <td>{{ \App\Models\Category::getCategoryById($product_ordered['category_id'])->name }}</td>
            <td>
                <input type="hidden" name="products[]" id="product_id_{{ $index }}" value="{{ $product_ordered['product_id'] }}">
                <?php
                    $product = \App\Models\Product::getProductById($product_ordered['product_id']);
                    $product_slug = $product->slug;
                    $category = \App\Models\Category::getCategoryById($product_ordered['category_id']);
                    $category_slug = $category->slug;
                    $category_dad_slug = \App\Models\Category::getCategoryById($category->parent_id)->slug;
                ?>
                <a title="Xem sản phẩm" target="_blank" href="{{ route('fe.product.detail', ['category_dad_slug' => $category_dad_slug, 'category_slug' => $category_slug, 'product_slug' => $product_slug]) }}">{{ \App\Models\Product::getProductById($product_ordered['product_id'])->name }}</a>

            </td>
            <input type="hidden" name="product_attribute_values[]" id="product_attribute_value_{{ $index }}" value="{{ \App\Models\Product_Attribute_value::getProductAttributeValueById($product_ordered['product_attribute_value_id'])->id }}">
            <td>{{ \App\Models\Attribute_value::getAttributevalueById(\App\Models\Product_Attribute_value::getProductAttributeValueById($product_ordered['product_attribute_value_id'])->color_id)->value }}</td>
            <td>{{ \App\Models\Attribute_value::getAttributevalueById(\App\Models\Product_Attribute_value::getProductAttributeValueById($product_ordered['product_attribute_value_id'])->size_id)->value }}</td>
            <td>
                <input type="hidden" id="price_{{ $index }}" value="{{ \App\Models\Product::getProductById($product_ordered['product_id'])->sale_price != "" ? \App\Models\Product::getProductById($product_ordered['product_id'])->sale_price : \App\Models\Product::getProductById($product_ordered['product_id'])->price  }}">
                {{ \App\Models\Product::getProductById($product_ordered['product_id'])->sale_price != "" ? number_format(\App\Models\Product::getProductById($product_ordered['product_id'])->sale_price, 0, '', '.') : number_format(\App\Models\Product::getProductById($product_ordered['product_id'])->price, 0, '', '.') }}vnđ
            </td>
            <td><input min="0" onchange="calcTotals(this,{{ $index }})" class="form-control" value="{{ $product_ordered['quantity'] }}" type="number" name="quantities[]" id="quantity_{{ $index }}"></td>
            <td>{{ number_format($product_ordered['total_price'], 0, '', '.') }}vnđ</td>
        </tr>
    @endforeach
@endif
