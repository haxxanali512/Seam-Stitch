<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComplaintTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complaint', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->integer('tailor_id')->nullable();
            $table->string('complaint_name', 1000)->nullable();
            $table->string('description', 1000)->nullable();
            $table->string('photo', 1000)->nullable();
            $table->timestamps();
            $table->index(['user_id', 'tailor_id'], 'user_id');
            $table->foreign('user_id', 'complaint_ibfk_1')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('tailor_id', 'complaint_ibfk_2')->references('id')->on('tailor')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('complaint');
    }
}
