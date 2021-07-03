<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product', function (Blueprint $table) {
            $table->integer('product_id')->primary();
            $table->string('name');
            $table->string('description', 1000);
            $table->string('type');
            $table->string('catalogurl', 2048);
            $table->integer('sub_category_id');
            $table->integer('quantity');
            $table->foreign('sub_category_id', 'product_ibfk_1')->references('id')->on('sub_category');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product');
    }
}