<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTailorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tailor', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('username', 200)->nullable();
            $table->string('first_name', 200)->nullable();
            $table->string('last_name', 200)->nullable();
            $table->string('image_path', 200)->nullable();
            $table->string('address', 200)->nullable();
            $table->string('city', 200)->nullable();
            $table->string('postal_code', 200)->nullable();
            $table->string('tailor_avg_rating', 100)->nullable()->default('1');
            $table->integer('is_allowed')->default(0);
            $table->foreign('user_id', 'tailor_ibfk_1')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tailor');
    }
}
