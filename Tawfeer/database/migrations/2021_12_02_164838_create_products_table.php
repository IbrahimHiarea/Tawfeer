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
            $table->date('expireDate');
            $table->integer('oldPrice');
            $table->integer('currentPrice');
            $table->text('imgUrl')->default('storage/app/public/img/default-product.png');
            $table->integer('quantity')->default(1);
            $table->foreignId('categoryId');//->constrained('categories');
            $table->foreignId('ownerId');//->constrained('users');
            $table->date('firstDate');
            $table->integer('firstDiscount');
            $table->date('secondDate');
            $table->integer('secondDiscount');
            $table->date('thirdDate');
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
