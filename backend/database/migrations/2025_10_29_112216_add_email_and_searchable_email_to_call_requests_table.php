<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('call_requests', function (Blueprint $table) {
            $table->text('email')->nullable()->after('phone'); // email клиента
            $table->string('searchable_email')->nullable()->after('searchable_phone'); // хэш для поиска по email
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('call_requests', function (Blueprint $table) {
            $table->dropColumn(['email', 'searchable_email']);
        });
    }
};
