<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGeneralsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('generals', function (Blueprint $table) {
            $table->id();
            $table->string('type')->comment('Phân loại: home-images / categories-thumb / cart-thumb / checkout-thumb / trackingorder-thumb / contact / fast-link / social-link / terms-and-security / about_us / contact-us / shipping-terms / return-terms');
            $table->bigInteger('category_id')->unsigned()->nullable()->comment('ID danh mục (Dành cho thumb)');
            $table->string('image_name')->nullable()->comment('Tên ảnh');
            $table->string('images')->nullable()->comment('Đường dẫn ảnh cho slide hoặc thumb hoặc logo to');
            $table->string('small_images')->nullable()->comment('Đường dẫn ảnh cho logo nhỏ');
            $table->longText('link')->nullable()->comment('Đường dẫn web dành cho social link và map');
            $table->longText('fb_script')->nullable()->comment('Bộ mã của Facebook Chat Plugin');
            $table->string('name')->nullable()->comment('Tên cửa hàng');
            $table->string('address')->nullable()->comment('Địa chỉ cửa hàng');
            $table->integer('xa_id')->nullable()->unsigned()->comment('ID của phường');
            $table->integer('huyen_id')->nullable()->unsigned()->comment('ID của quận');
            $table->integer('tinh_id')->nullable()->unsigned()->comment('ID của thành phố');
            $table->string('phone_number')->nullable()->comment('Số điện thoại cửa hàng');
            $table->string('email')->nullable()->comment('Email của cửa hàng, email sẽ nhận thông báo về liên hệ mới của khách hàng');
            $table->longText('description')->nullable()->comment('Mô tả ngắn về cửa hàng, tên của link, giới thiệu cửa hàng, thông tin điều khoản và bảo mật');
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('xa_id')->references('id')->on('xa')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('huyen_id')->references('id')->on('huyen')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('tinh_id')->references('id')->on('tinh')
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
        Schema::dropIfExists('generals');
    }
}
