<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddActivityTitleToRecentActivitiesTable extends Migration
{
    public function up()
    {
        Schema::table('recent_activities', function (Blueprint $table) {
            $table->string('activity_title')->after('description'); // Add the new column
        });
    }

    public function down()
    {
        Schema::table('recent_activities', function (Blueprint $table) {
            $table->dropColumn('activity_title'); // Remove the column if rolling back
        });
    }
}