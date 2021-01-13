<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('product_id')->unsigned()->comment('ID sản phẩm đánh giá');
            $table->string('name')->comment('Tên người đánh giá');
            $table->string('phone_number')->comment('SĐT người đánh giá');
            $table->longText('comment')->comment('Nội dung đánh giá');
            $table->integer('rating')->comment('Điểm đánh giá');
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')
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
        Schema::dropIfExists('ratings');
    }
}
