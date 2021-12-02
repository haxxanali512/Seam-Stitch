<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_attributes', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('component_id')->nullable();
            $table->string('name', 500)->nullable();
            $table->string('photo', 500)->nullable();
            $table->string('price', 500)->nullable();
            $table->foreign('component_id', 'product_attributes_ibfk_1')->references('id')->on('product_components')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_attributes');
    }
}
