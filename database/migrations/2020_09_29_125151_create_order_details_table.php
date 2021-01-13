<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_id')->unsigned()->comment('ID đơn hàng');
            $table->bigInteger('product_id')->unsigned()->comment('ID sản phẩm');
            $table->bigInteger('product_attribute_value_id')->unsigned()->comment('ID biến thể sản phẩm');
            $table->integer('quantity')->comment('Số lượng sản phẩm chọn mua');
            $table->integer('total_price')->comment('Tổng giá trị của đơn hàng');
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('product_id')->references('id')->on('products')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('product_attribute_value_id')->references('id')->on('product_attribute_values')
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
        Schema::dropIfExists('order_details');
    }
}
