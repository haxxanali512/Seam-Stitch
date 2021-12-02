<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankdetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bankdetails', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('tailor_id')->nullable();
            $table->string('account_title', 200)->nullable();
            $table->string('account_num', 200)->nullable();
            $table->string('bank_name', 200)->nullable();
            $table->string('branch_code', 200)->nullable();
            $table->string('cheque', 200)->nullable();
            $table->foreign('tailor_id', 'bankdetails_ibfk_1')->references('id')->on('tailor')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bankdetails');
    }
}
