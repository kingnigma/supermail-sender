<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecentActivitiesTable extends Migration
{
    public function up()
    {
        Schema::create('recent_activities', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('recent_activities');
    }
}
