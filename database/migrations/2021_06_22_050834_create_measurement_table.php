<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeasurementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('measurement', function (Blueprint $table) {
            $table->integer('measurement_id', true);
            $table->integer('customer_id');
            $table->double('neck');
            $table->double('chest');
            $table->double('waist');
            $table->double('hip');
            $table->double('frontwaist');
            $table->double('backwaist');
            $table->double('shoulder');
            $table->double('armlength');
            $table->double('wrist');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('measurement');
    }
}
