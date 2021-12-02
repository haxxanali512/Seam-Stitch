<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('type', 200)->nullable();
            $table->string('name', 1000)->nullable();
            $table->string('shoulder', 1000)->nullable();
            $table->string('arms', 1000)->nullable();
            $table->string('pantslength', 1000)->nullable();
            $table->string('shirtlength', 1000)->nullable();
            $table->string('chest', 1000)->nullable();
            $table->string('stomach', 1000)->nullable();
            $table->string('waist', 1000)->nullable();
            $table->string('images', 1000)->nullable();
            $table->timestamp('created_at')->useCurrent()->useCurrentOnUpdate();
            $table->timestamp('updated_at')->default('0000-00-00 00:00:00');
            $table->foreign('user_id', 'measurement_ibfk_1')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
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
