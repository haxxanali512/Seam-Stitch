<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDisputeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dispute', function (Blueprint $table) {
            $table->integer('dispute_id')->primary();
            $table->integer('product_id')->index('product_id');
            $table->integer('order_id')->index('order_id');
            $table->integer('tailor_id')->index('tailor_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dispute');
    }
}
