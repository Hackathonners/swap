<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExchangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exchanges', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('approved')->default(false);
            $table->integer('from_student_id')->unsigned();
            $table->integer('from_shift_id')->unsigned();
            $table->integer('to_student_id')->unsigned();
            $table->integer('to_shift_id')->unsigned()->nullable();
            $table->integer('academic_year_id')->unsigned();
            $table->timestamps();

            $table->foreign('from_student_id')->references('id')->on('students');
            $table->foreign('from_shift_id')->references('id')->on('shifts');
            $table->foreign('to_student_id')->references('id')->on('students');
            $table->foreign('to_shift_id')->references('id')->on('shifts');
            $table->foreign('academic_year_id')->references('id')->on('academic_years');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exchanges');
    }
}
