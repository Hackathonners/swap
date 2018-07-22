<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvitationsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('invitations', function (Blueprint $table) {
            $table->increments('id');

            $table->string('student_number');
            $table->integer('group_id')->unsigned();
            $table->integer('course_id')->unsigned();

            $table->foreign('student_number')->references('student_number')->on('students');
            $table->foreign('group_id')->references('id')->on('groups');
            $table->foreign('course_id')->references('id')->on('courses');

            $table->unique(['student_number', 'group_id']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('invitations');
    }
}
