@if (count($orders) == 0)
    <tr>
        <td colspan="15">Không có dữ liệu</td>
    </tr>
@else
    @foreach($orders as $order)
        <tr>
            <td>{{ $order->id }}</td>
            <td>{{ $order->name }}</td>
            <td>{{ $order->phone_number }}</td>
            <td>{{ $order->address }}, {{ \App\Models\Xa::getXaById($order->xa_id)->name }}, {{ \App\Models\Huyen::getHuyenById($order->huyen_id)->name }}, {{ \App\Models\Tinh::getTinhById($order->tinh_id)->name }}</td>
            <td>
                @if ($order->promotion != "")
                    {{ $order->promotion }}
                @else
                    Không sử dụng
                @endif
            </td>
            <td>{{ number_format($order->totals, 0, '', '.') }}vnđ</td>
            <td>{{ \App\Models\Shipfee::getShipfeeById($order->ship_id)->name }}</td>
            <td>
                @switch($order->payment_method)
                    @case(1)
                        Thanh toán bằng tiền mặt
                        @break
                    @case(2)
                        Thanh toán trực tuyến
                        @break
                @endswitch
            </td>
            <td>{{ date('d-m-Y H:i:s', strtotime($order->created_at)) }}</td>
            <td>{{ date('d-m-Y H:i:s', strtotime($order->updated_at)) }}</td>
            <td>
                @switch($order->status)
                    @case(0)
                        Đã hủy
                        @break

                    @case(1)
                        Đơn chưa được admin xác nhận
                        @break

                    @case(2)
                        Đơn đã được admin xác nhận
                        @break

                    @case(3)
                        Đơn hàng đang vận chuyển
                        @break

                    @case(4)
                        Đơn hàng thành công
                        @break

                    @case(5)
                        Khách hàng từ chối nhận hàng
                        @break

                    @case(6)
                        Đơn hàng đang được trả lại
                        @break

                    @case(7)
                        Đơn hàng đã được chuyển lại
                        @break

                    @case(8)
                        Đơn hàng đã được hoàn tiền
                        @break
                @endswitch
            </td>
            <td><a href="javascript:void(0)" data-toggle="modal" data-target="#detail_order_{{ $order->id }}">Xem chi tiết</a></td>
        </tr>
    @endforeach
@endif
