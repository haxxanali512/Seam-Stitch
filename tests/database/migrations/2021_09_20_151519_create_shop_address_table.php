<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_address', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('tailor_id')->nullable();
            $table->string('address', 500)->nullable();
            $table->string('country_region', 200)->nullable();
            $table->string('state', 200)->nullable();
            $table->string('area', 200)->nullable();
            $table->timestamps();
            $table->foreign('tailor_id', 'shop_address_ibfk_1')->references('id')->on('tailor');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shop_address');
    }
}
