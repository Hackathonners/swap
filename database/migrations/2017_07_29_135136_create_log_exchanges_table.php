<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogExchangesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('log_exchanges', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('from_shift_id')->unsigned();
            $table->integer('to_shift_id')->unsigned();
            $table->integer('from_student_id')->unsigned();
            $table->integer('to_student_id')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('from_shift_id')->references('id')->on('shifts');
            $table->foreign('to_shift_id')->references('id')->on('shifts');
            $table->foreign('from_student_id')->references('id')->on('students');
            $table->foreign('to_student_id')->references('id')->on('students');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('log_exchanges');
    }
}
