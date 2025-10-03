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
        Schema::create('call_requests', function (Blueprint $table) {
            $table->id();
            $table->text('name'); // имя клиента
            $table->text('phone'); // телефон клиента
            $table->string('status')->default('pending'); // статус заявки
            $table->string('searchable_name')->nullable(); // хэш для поиска по имени
            $table->string('searchable_phone')->nullable(); // хэш для поиска по телефону
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('call_requests');
    }
};
