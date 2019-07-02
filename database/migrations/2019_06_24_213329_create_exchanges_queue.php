<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExchangesQueue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exchanges_queue', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('from_shift_id');
            $table->unsignedInteger('to_shift_id');
            $table->unsignedInteger('from_enrollment_id');
            $table->unsignedInteger('from_student_id');
            $table->timestamps();

            $table->foreign('from_shift_id')->references('id')->on('shifts');
            $table->foreign('to_shift_id')->references('id')->on('shifts');
            $table->foreign('from_enrollment_id')->references('id')->on('enrollments');
            $table->foreign('from_student_id')->references('id')->on('students');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exchanges_queue');
    }
}
