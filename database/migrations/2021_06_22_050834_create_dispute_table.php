<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->integer('dispute_id', true);
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
