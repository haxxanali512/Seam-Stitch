<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToDisputeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dispute', function (Blueprint $table) {
            $table->foreign('product_id', 'dispute_ibfk_1')->references('product_id')->on('product')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('order_id', 'dispute_ibfk_2')->references('order_id')->on('orders')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('tailor_id', 'dispute_ibfk_3')->references('tailor_id')->on('tailor')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dispute', function (Blueprint $table) {
            $table->dropForeign('dispute_ibfk_1');
            $table->dropForeign('dispute_ibfk_2');
            $table->dropForeign('dispute_ibfk_3');
        });
    }
}
