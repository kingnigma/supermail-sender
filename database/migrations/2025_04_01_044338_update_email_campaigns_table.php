<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateEmailCampaignsTable extends Migration
{
    public function up()
    {
        Schema::table('email_campaigns', function (Blueprint $table) {
            
            $table->string('name');
            $table->foreignId('template_id')->constrained('email_templates');
            $table->string('contact_list');
            $table->integer('total_recipients');
            $table->integer('sent_count');
            $table->integer('failed_count')->default(0);
            $table->string('status'); // completed, failed, partial, scheduled
            $table->string('status_color'); // success, danger, warning, info
            $table->text('subject');
            $table->timestamp('sent_at')->nullable();
            
        });
    }

    public function down()
    {
        Schema::table('email_campaigns', function (Blueprint $table) {
            $table->dropForeign(['template_id']);
            $table->dropColumn(['name', 'template_id', 'contact_list', 'total_recipients', 'sent_count', 'failed_count', 'status', 'status_color', 'subject', 'sent_at']);
        });
    }
}
