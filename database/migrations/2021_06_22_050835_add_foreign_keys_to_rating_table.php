<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToRatingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rating', function (Blueprint $table) {
            $table->foreign('tailor_id', 'rating_ibfk_1')->references('tailor_id')->on('tailor')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('customer_id', 'rating_ibfk_2')->references('customer_id')->on('customer')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rating', function (Blueprint $table) {
            $table->dropForeign('rating_ibfk_1');
            $table->dropForeign('rating_ibfk_2');
        });
    }
}
