<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTable extends Migration
{
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            $table->integer('user_id');
            $table->string('name');
            $table->string('password');
            $table->string('profilepicture');
            $table->string('email');
            $table->string('gender');
            $table->string('phone_number');
            $table->integer('isActive');
            $table->string('type');
            $table->integer('location_id');
        });
    }
    public function down()
    {
        Schema::dropIfExists('user');
    }
}
