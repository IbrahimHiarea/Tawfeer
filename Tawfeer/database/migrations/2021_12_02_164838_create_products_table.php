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
            $table->string('description');
            $table->string('expireDate');
            $table->integer('oldPrice');
            $table->string('imgUrl');
            $table->integer('quantity')->default(1);
            $table->string('category');
            $table->integer('ownerId');
            $table->string('dateOne');
            $table->integer('priceOne');
            $table->string('dateTwo');
            $table->integer('priceTwo');
            $table->string('dateThree');
            $table->integer('priceThree');
            $table->integer('Seens')->default(0);
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
