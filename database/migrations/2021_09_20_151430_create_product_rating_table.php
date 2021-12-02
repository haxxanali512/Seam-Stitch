<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductRatingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_rating', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('product_id')->nullable();
            $table->string('rating', 10)->nullable()->default('1');
            $table->string('review', 100)->nullable();
            $table->string('images', 1000)->default('0');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();
            $table->foreign('product_id', 'product_rating_ibfk_1')->references('id')->on('product')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('user_id', 'product_rating_ibfk_2')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_rating');
    }
}
