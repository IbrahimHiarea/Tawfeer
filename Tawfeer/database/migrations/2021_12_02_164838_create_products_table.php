<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('productName');
            $table->string('description')->nullable();
            $table->string('expireDate');
            $table->integer('oldPrice');
            $table->string('imgUrl');
            $table->integer('quantity')->default(1);
            $table->string('category');
            $table->integer('ownerId');
            $table->string('firstDate');
            $table->integer('firstDiscount');
            $table->string('secondDate');
            $table->integer('secondDiscount');
            $table->string('thirdDate');
            $table->integer('thirdDiscount');
            $table->integer('seens')->default(0);
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
        Schema::dropIfExists('products');
    }
}
