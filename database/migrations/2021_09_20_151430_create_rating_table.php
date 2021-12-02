<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRatingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rating', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('rating', 1000)->default('1');
            $table->integer('tailor_id');
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->foreign('tailor_id', 'rating_ibfk_1')->references('id')->on('tailor')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('customer_id', 'rating_ibfk_2')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rating');
    }
}
