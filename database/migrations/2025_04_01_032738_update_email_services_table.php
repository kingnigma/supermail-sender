<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateEmailServicesTable extends Migration
{
    public function up()
    {
        Schema::table('email_services', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->after('id');
            $table->string('service_type')->after('user_id'); // mailchimp, smtp, mailgun, postmark
            $table->json('credentials')->after('service_type'); // Store API keys and settings as JSON
        });
    }

    public function down()
    {
        Schema::table('email_services', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'service_type', 'credentials']);
        });
    }
}
