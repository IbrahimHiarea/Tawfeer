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
            $table->string('name');
            $table->string('description');
            $table->string('expiryDate');
            $table->integer('mainPrice');
            $table->string('imgUrl');
            $table->integer('quantity');
            $table->string('category');
            $table->integer('ownerId');
            $table->string('date1');
            $table->integer('price1');
            $table->string('date2');
            $table->integer('price2');
            $table->string('date3');
            $table->integer('price3');
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
