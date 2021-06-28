<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToOrderItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_item', function (Blueprint $table) {
            $table->foreign('order_id', 'order_item_ibfk_1')->references('order_id')->on('orders')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('tailor_id', 'order_item_ibfk_2')->references('tailor_id')->on('tailor')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('product_id', 'order_item_ibfk_3')->references('product_id')->on('product')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_item', function (Blueprint $table) {
            $table->dropForeign('order_item_ibfk_1');
            $table->dropForeign('order_item_ibfk_2');
            $table->dropForeign('order_item_ibfk_3');
        });
    }
}
