<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGroupsToSettingsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->unsignedInteger('min_group_members')->nullable();
            $table->unsignedInteger('max_group_members')->nullable();
            $table->timestamp('groups_start_at')->nullable();
            $table->timestamp('groups_end_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('min_group_members');
            $table->dropColumn('max_group_members');
            $table->dropColumn('groups_start_at');
            $table->dropColumn('groups_end_at');
        });
    }
}
