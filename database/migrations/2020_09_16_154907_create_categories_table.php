<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 191)->unique()->comment('Tên danh mục');
            $table->string('slug', 191)->unique()->comment('Đường dẫn tĩnh của danh mục');
            $table->string('thumb')->default('images/default_generals/categories-thumb/thumb.jpg')->comment('Ảnh đại diện cho danh mục');
            $table->integer('parent_id')->default(0)->comment('Danh mục cha');
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
        Schema::dropIfExists('categories');
    }
}
