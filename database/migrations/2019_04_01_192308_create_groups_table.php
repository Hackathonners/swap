<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('course_id')->nullable();
            $table->string('name')->nullable();
            $table->timestamps();

            $table->foreign('course_id')->references('id')->on('courses');
        });

        Schema::create('group_student', function (Blueprint $table) {
            $table->unsignedInteger('group_id');
            $table->unsignedInteger('student_id');
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();

            $table->primary(['group_id', 'student_id']);
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('group_student');
        Schema::dropIfExists('groups');
    }
}
