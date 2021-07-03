<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            $table->integer('user_id', true);
            $table->string('name');
            // $table->string('lastname');
            $table->string('password');
            $table->string('profilepicture');
            $table->string('email');
            $table->string('gender');
            $table->string('phone_number');
            $table->integer('isActive');
            $table->string('type');
            $table->integer('location_id')->index('FOREIGN');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user');
    }
}
