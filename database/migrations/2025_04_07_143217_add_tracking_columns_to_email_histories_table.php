<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('email_histories', function (Blueprint $table) {
            $table->timestamp('opened_at')->nullable()->after('status');
            $table->timestamp('clicked_at')->nullable()->after('opened_at');
        });
    }

    public function down(): void
    {
        Schema::table('email_histories', function (Blueprint $table) {
            $table->dropColumn(['opened_at', 'clicked_at']);
        });
    }
};
