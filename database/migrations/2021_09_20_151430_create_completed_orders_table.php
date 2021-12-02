<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompletedOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('completed_orders', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->integer('tailor_id')->nullable();
            $table->string('product', 10000)->nullable();
            $table->string('status', 1000)->nullable();
            $table->integer('quantity')->nullable();
            $table->string('shipping_type')->nullable();
            $table->string('tracking_number')->nullable()->default('0');
            $table->string('final_price', 1000)->nullable();
            $table->timestamps();
            $table->foreign('user_id', 'completed_orders_ibfk_1')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('tailor_id', 'completed_orders_ibfk_2')->references('id')->on('tailor')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('completed_orders');
    }
}
