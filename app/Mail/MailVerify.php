<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class MailVerify extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($info, $order_id, $products_id, $product_attribute_values_id, $quantities, $totals_price, $totals, $discount_info)
    {
        $this->info = $info;
        $this->order_id = $order_id;
        $this->products_id = $products_id;
        $this->product_attribute_values_id = $product_attribute_values_id;
        $this->quantities = $quantities;
        $this->totals_price = $totals_price;
        $this->totals = $totals;
        if (count($discount_info) != 0) {
            $this->discount_info = $discount_info;
        }
        else {
            $this->discount_info = [];
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails_content.verify_order_mail')->subject('Đơn hàng mới tại '.(DB::table('generals')->where('type', '=', 'contact')->first()->name).' đang chờ bạn xác nhận')->with(
            [
                'info'=>$this->info,
                'order_id' => $this->order_id,
                'products_id' => $this->products_id,
                'product_attribute_values_id' => $this->product_attribute_values_id,
                'quantities' => $this->quantities,
                'totals_price' => $this->totals_price,
                'totals' => $this->totals,
                'discount_info' => $this->discount_info
            ]);
    }
}
