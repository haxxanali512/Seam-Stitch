<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToMeasurementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('measurement', function (Blueprint $table) {
            $table->foreign('measurement_id', 'measurement_ibfk_1')->references('customer_id')->on('customer')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('measurement', function (Blueprint $table) {
            $table->dropForeign('measurement_ibfk_1');
        });
    }
}
