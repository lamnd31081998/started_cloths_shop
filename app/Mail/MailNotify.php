<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class MailNotify extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($info, $order_id, $products_id, $product_attribute_values_id, $quantities, $totals_price, $totals, $discount_info, $type)
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
        $this->type = $type;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->type == "store") {
            return $this->view('mails_content.confirm_order_mail')->subject('Cảm ơn bạn đã đặt hàng tại '.(DB::table('generals')->where('type', '=', 'contact')->first()->name))->with(
                [
                    'info' => $this->info,
                    'order_id' => $this->order_id,
                    'products_id' => $this->products_id,
                    'product_attribute_values_id' => $this->product_attribute_values_id,
                    'quantities' => $this->quantities,
                    'totals_price' => $this->totals_price,
                    'totals' => $this->totals,
                    'discount_info' => $this->discount_info,
                    'type' => $this->type
                ]);
        }
        else {
            return $this->view('mails_content.confirm_order_mail')->subject('Đơn hàng của bạn tại aStar đã được cập nhật')->with(
                [
                    'info' => $this->info,
                    'order_id' => $this->order_id,
                    'products_id' => $this->products_id,
                    'product_attribute_values_id' => $this->product_attribute_values_id,
                    'quantities' => $this->quantities,
                    'totals_price' => $this->totals_price,
                    'totals' => $this->totals,
                    'discount_info' => $this->discount_info,
                    'type' => $this->type
                ]);
        }
    }
}
