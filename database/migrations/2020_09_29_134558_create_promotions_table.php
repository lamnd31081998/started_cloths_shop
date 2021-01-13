<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromotionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('code', 191)->unique()->comment('Mã giảm giá');
            $table->string('description')->comment('Mô tả');
            $table->integer('at_least')->default(0)->comment('Yêu cầu giá trị đơn hàng tối thiểu mức này');
            $table->integer('discount')->comment('Mức giảm giá (%)');
            $table->integer('max_use')->comment('Số lần sử dụng của mã');
            $table->integer('max_user_use')->comment('Giới hạn số lần sử dụng mã giảm giá của mỗi số điện thoại');
            $table->date('start_time')->default(date('Ymd'))->comment('Ngày bắt đầu');
            $table->date('expire_time')->nullable()->comment('Ngày hết hạn');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('promotions');
    }
}
