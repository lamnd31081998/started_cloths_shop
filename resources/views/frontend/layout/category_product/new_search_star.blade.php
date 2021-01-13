<button type="button" class="btn btn-outline-primary" onclick="searchStar(0)">Tất cả ({{ count(\App\Models\Rating::getRatingsByProductId($product_id)) }})</button>
@for($i = 1; $i <= 5; $i++)
    <button type="button" class="btn btn-outline-primary" onclick="searchStar({{ $i }})">{{ $i }} sao ({{ count(\App\Models\Rating::getRatingsByStar($product_id, $i)) }})</button>
@endfor
