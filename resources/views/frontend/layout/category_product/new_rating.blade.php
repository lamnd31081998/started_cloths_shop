<div class="row rating_{{ $data['star'] }}" style="margin-top: 10px;">
    <div class="col-12 col-lg-12 col-xs-12 col-md-12 col-sm-12 col-xxl-12 col-xl-12">
        <table style="margin: 0 0 10px 0;width: 100%; border-bottom: solid 1px">
            <tr>
                <td style="width: 40px;/*border-right: hidden;*/">
                    <img style="position: absolute; top: 5px; left: 20px" width="40px" height="40px" src="{{ asset('images/default_avatar.png') }}">
                </td>
                <td style="padding-left: 20px">
                    <div class="form-group">
                        <h4 class="customer_name" style="margin-top: 5px">
                            <?php
                                $phone_number = $data['phone_number'];
                                $new_phone_number = substr($phone_number, 0, 4);
                                $new_phone_number = $new_phone_number.'xxxxxx';
                            ?>
                            {{ $data['name'] }} (SĐT mua hàng: {{ $new_phone_number }})
                        </h4>
                        @for($i = 1; $i <= $data['star']; $i++)
                            <i class="fa fa-star" style="color: gold;"></i>
                        @endfor
                        @for($i = $data['star']+1; $i <= 5; $i++)
                            <i class="fa fa-star-o" style="color: gold;"></i>
                        @endfor
                        <p style="color: black; margin-bottom: 5px">{{ $data['comment'] }}</p>
                        <span style="color: #8f8f8f">{{ date("d/m/Y H:i:s", strtotime($rating->created_at)) }}</span>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>

