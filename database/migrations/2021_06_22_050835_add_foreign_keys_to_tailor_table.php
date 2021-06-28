<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToTailorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tailor', function (Blueprint $table) {
            $table->foreign('shop_id', 'tailor_ibfk_1')->references('shop_id')->on('shop')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tailor', function (Blueprint $table) {
            $table->dropForeign('tailor_ibfk_1');
        });
    }
}
