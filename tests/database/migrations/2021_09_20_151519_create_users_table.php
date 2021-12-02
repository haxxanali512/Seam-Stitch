<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('image_path', 200)->nullable();
            $table->string('email');
            $table->string('email_code')->nullable();
            $table->string('password');
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->date('dob')->nullable();
            $table->string('phone_number', 15)->nullable();
            $table->boolean('isVerified')->nullable()->default(0);
            $table->string('shop_name', 200)->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->integer('account_id')->nullable();
            $table->integer('role_id')->nullable();
            $table->string('fcm_token', 1000)->nullable()->default('0');
            $table->foreign('account_id', 'users_ibfk_1')->references('id')->on('bankdetails');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
