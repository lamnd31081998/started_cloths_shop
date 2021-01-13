<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id()->comment('ID người dùng');
            $table->string('username', 191)->unique()->comment('Tên tài khoản');
            $table->string('email', 191)->unique()->comment('Email cua tài khoản');
            $table->string('password')->comment('Mật khẩu của tài khoản');
            $table->string('name')->comment('Tên người dùng');
            $table->string('avatar')->default('images/default_avatar.png')->comment('Ảnh đại diện của tài khoản');
            $table->date('birthday')->nullable()->comment('Ngày sinh');
            $table->string('phone_number')->comment('Số điện thoại');
            $table->integer('tinh_id')->unsigned()->comment('Mã tỉnh');
            $table->integer('huyen_id')->unsigned()->comment('Mã huyện');
            $table->integer('xa_id')->unsigned()->comment('Mã xã');
            $table->string('address')->comment('Địa chỉ cụ thể');
            $table->integer('gender')->comment('Giới tính: 1 - Nam, 2 - Nữ');
            $table->integer('status')->default(1)->comment('Trạng thái tài khoản: 0 - Bị khóa, 1 - Hoạt động');
            $table->rememberToken();
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
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
