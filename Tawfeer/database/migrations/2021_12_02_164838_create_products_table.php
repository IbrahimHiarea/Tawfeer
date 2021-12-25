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
            $table->string('category');
            $table->foreignId('ownerId');//->constrained('users');
            $table->date('firstDate')->nullable();
            $table->integer('firstDiscount')->nullable()->default(0);
            $table->date('secondDate')->nullable();
            $table->integer('secondDiscount')->nullable()->default(0);
            $table->date('thirdDate')->nullable();
            $table->integer('thirdDiscount')->nullable()->default(0);
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
