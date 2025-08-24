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
        Schema::create('category_attribute_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attribute_id')->constrained();
            $table->foreignId('category_id')->constrained();
            $table->integer('order')->default(0);
            $table->boolean('is_filterable')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_attribute_values');
    }
};
