<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExchangesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('exchanges', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('from_enrollment_id')->unsigned();
            $table->integer('to_enrollment_id')->unsigned();
            $table->timestamps();

            $table->foreign('from_enrollment_id')->references('id')->on('enrollments');
            $table->foreign('to_enrollment_id')->references('id')->on('enrollments');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('exchanges');
    }
}
