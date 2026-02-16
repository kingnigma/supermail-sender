<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateInvoiceTemplatesTable extends Migration
{
    public function up()
    {
        Schema::table('invoice_templates', function (Blueprint $table) {
            
            $table->string('title');
            $table->string('heading')->nullable();
            $table->text('address')->nullable();
            $table->text('description');
            $table->decimal('amount', 10, 2);
            $table->text('payment_details')->nullable();
            $table->string('invoice_number')->unique();
            
        });
    }

    public function down()
    {
        Schema::table('invoice_templates', function (Blueprint $table) {
            $table->dropColumn(['title', 'heading', 'address', 'description', 'amount', 'payment_details', 'invoice_number']);
        });
    }
}
