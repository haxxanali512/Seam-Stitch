<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToReviewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('review', function (Blueprint $table) {
            $table->foreign('product_id', 'review_ibfk_1')->references('product_id')->on('product')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('customer_id', 'review_ibfk_2')->references('customer_id')->on('customer')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('review', function (Blueprint $table) {
            $table->dropForeign('review_ibfk_1');
            $table->dropForeign('review_ibfk_2');
        });
    }
}
