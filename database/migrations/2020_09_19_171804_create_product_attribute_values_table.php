<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductAttributeValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('product_attribute_values', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('product_id')->unsigned()->comment('ID sản phẩm');
            $table->bigInteger('color_id')->unsigned()->comment('ID giá trị thuộc tính màu sắc');
            $table->bigInteger('size_id')->unsigned()->comment('ID giá trị thuộc tính kích cỡ');
            $table->integer('quantity')->comment('Số lượng sản phẩm');
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('color_id')->references('id')->on('attribute_values')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('size_id')->references('id')->on('attribute_values')
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
        Schema::dropIfExists('product_attribute_values');
    }
}
