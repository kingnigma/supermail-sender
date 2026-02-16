<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('subject');
            $table->text('message');
            $table->string('status')->default('processing');
            $table->integer('sent_count')->default(0);
            $table->integer('failed_count')->default(0);
            $table->timestamp('completed_at')->nullable();
            $table->string('attachment_path')->nullable();
            
            // Foreign keys
            $table->foreignId('contact_group_id')->constrained();
            $table->foreignId('message_template_id')->constrained();
            $table->foreignId('invoice_template_id')->nullable()->constrained('invoice_templates');
            $table->foreignId('user_id')->constrained();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('campaigns');
    }
};