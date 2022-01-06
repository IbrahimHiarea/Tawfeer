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
            $table->text('description')->nullable();
            $table->date('expireDate');
            $table->double('oldPrice');
            $table->double('currentPrice');
            $table->double('currentDiscount')->nullable();
            $table->text('imgUrl')->nullable();
            $table->integer('quantity')->default(1);
            $table->string('category');
            $table->foreignId('categoryId')->constrained('categories');
            $table->foreignId('ownerId')->constrained('users');
            $table->date('firstDate')->nullable();
            $table->double('firstDiscount')->nullable();
            $table->date('secondDate')->nullable();
            $table->double('secondDiscount')->nullable();
            $table->date('thirdDate')->nullable();
            $table->double('thirdDiscount')->nullable();
            $table->integer('seens')->default(0);
            $table->integer('likes')->default(0);
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
