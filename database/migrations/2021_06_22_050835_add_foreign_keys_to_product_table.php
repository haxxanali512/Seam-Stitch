<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product', function (Blueprint $table) {
            $table->foreign('category_id', 'product_ibfk_1')->references('category_id')->on('categories')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('inventory_id', 'product_ibfk_2')->references('inventory_id')->on('inventory')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product', function (Blueprint $table) {
            $table->dropForeign('product_ibfk_1');
            $table->dropForeign('product_ibfk_2');
        });
    }
}
