<?php
    $avg_ratings = $avg_round = 0;
    if (count(\App\Models\Rating::getRatingsByProductId($product_id)) != 0) {
        $avg_ratings = \App\Models\Rating::getAvgRatingsByProductId($product_id);
        $avg_round = round($avg_ratings, 1, PHP_ROUND_HALF_EVEN);
        if (gettype($avg_ratings) != 'integer') {
            $str_avg_round = strval($avg_round);
            $str_avg_round_explode = explode('.', $str_avg_round);
            foreach ($str_avg_round_explode as $index=>$value) {
                if ($index == 0) {
                    $so_nguyen = $value;
                }
                else {
                    $so_sau_dau_phay = $value;
                }
            }
            if ($so_sau_dau_phay >= 5) {
                $so_nguyen = $so_nguyen + 1;
            }
        }
    }
?>
<div class="form-group">
    <h1>{{ $avg_ratings }} trên 5</h1>
</div>
<div class="form-group" style="color: gold">
    @if (gettype($avg_ratings) == 'integer')
        @for ($i = 1; $i <= $avg_ratings; $i++)
            <i class="fa fa-star fa-2x"></i>
        @endfor
        @for($i = $avg_ratings+1; $i <= 5; $i++)
            <i class="fa fa-star-o fa-2x"></i>
        @endfor
    @else
        @for ($i = 1; $i <= $so_nguyen; $i++)
            @if ($so_sau_dau_phay >= 5 && $so_nguyen == $i)
                <i class="fa fa-star-half-o fa-2x"></i>
            @else
                <i class="fa fa-star fa-2x"></i>
            @endif
        @endfor
        @for ($i = $so_nguyen + 1; $i <= 5; $i++)
            <i class="fa fa-star-o fa-2x"></i>
        @endfor
    @endif
</div>
