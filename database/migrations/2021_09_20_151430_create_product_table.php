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
            $table->integer('id')->primary();
            $table->integer('tailor_id')->nullable();
            $table->string('name');
            $table->string('brand', 1000)->nullable();
            $table->string('description', 1000);
            $table->string('catalogurl', 2048);
            $table->integer('sub_category_id');
            $table->integer('quantity');
            $table->string('price', 100)->nullable();
            $table->string('average_rating', 20)->nullable()->default('0');
            $table->string('discount', 100)->nullable()->default('0%');
            $table->integer('sold_product')->nullable()->default(0);
            $table->string('shipping_type', 100)->nullable();
            $table->timestamps();
            $table->foreign('sub_category_id', 'product_ibfk_1')->references('id')->on('sub_category')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('tailor_id', 'product_ibfk_2')->references('id')->on('tailor')->onDelete('cascade')->onUpdate('cascade');
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
