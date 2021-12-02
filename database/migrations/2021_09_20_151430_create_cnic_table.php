<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCnicTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cnic', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('id_type', 200)->nullable();
            $table->string('name', 200)->nullable();
            $table->string('cnic', 20)->nullable();
            $table->string('front_image', 200)->nullable();
            $table->string('back_image', 200)->nullable();
            $table->integer('tailor_id')->nullable();
            $table->foreign('tailor_id', 'cnic_ibfk_1')->references('id')->on('tailor')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cnic');
    }
}
