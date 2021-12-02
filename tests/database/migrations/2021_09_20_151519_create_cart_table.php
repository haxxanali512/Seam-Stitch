<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCartTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->integer('product_id')->nullable();
            $table->string('components', 1000)->nullable()->default('0');
            $table->bigInteger('quantity')->nullable();
            $table->integer('final_price')->nullable();
            $table->string('shipping_type')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'product_id', 'components'], 'user_id');
            $table->foreign('product_id', 'cart_ibfk_2')->references('id')->on('product')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('user_id', 'cart_ibfk_3')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cart');
    }
}
