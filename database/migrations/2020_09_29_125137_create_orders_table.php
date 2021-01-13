<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Tên người mua');
            $table->string('email')->nullable()->comment('Email người mua');
            $table->string('phone_number')->comment('Số điện thoại đặt hàng');
            $table->integer('tinh_id')->unsigned()->comment('ID thành phố');
            $table->integer('huyen_id')->unsigned()->comment('ID quận');
            $table->integer('xa_id')->unsigned()->comment('ID phường');
            $table->string('address')->comment('Địa chỉ cụ thể');
            $table->bigInteger('ship_id')->unsigned()->comment('Loại hình ship');
            $table->integer('payment_method')->comment('Loại hình thanh toán: 1 - Thanh toán bằng tiền mặt, 2 - Thanh toán trực tuyến');
            $table->string('promotion', 191)->nullable()->comment('Mã giảm giá sử dụng(Nếu có)');
            $table->integer('totals')->comment('Tổng giá trị đơn hàng');
            $table->integer('status')->default(1)->comment('Trạng thái đơn hàng : 0 - Đã hủy, 1 - Đơn chưa được admin xác nhận, 2 - Đơn đã được admin xác nhận, 3 - Đơn hàng đang vận chuyển, 4 - Đơn hàng thành công, 5 - Khách hàng từ chối nhận hàng, 6 - Đơn hàng đang được trả lại, 7 - Đơn hàng đã được chuyển lại, 8 - Đã hoàn tiền');
            $table->string('vnp_PayDate')->nullable()->comment('Thời gian giao dịch vnpay');
            $table->string('vnp_TransactionNo')->nullable()->comment('Mã GD vnpay');
            $table->string('vnp_TxnRef')->nullable()->comment('Mã bất kì vnpay');
            $table->string('vnp_OrderInfo')->nullable()->comment('Nội dung vnpay');
            $table->longText('note')->nullable()->comment('Ghi chú đơn hàng');
            $table->timestamps();

            $table->foreign('tinh_id')->references('id')->on('tinh')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('huyen_id')->references('id')->on('huyen')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('xa_id')->references('id')->on('xa')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('ship_id')->references('id')->on('shipfees')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
