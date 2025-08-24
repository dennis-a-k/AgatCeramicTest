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
        Schema::create('product_attribute_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('attribute_id')->constrained();
            $table->string('string_value')->nullable();
            $table->decimal('number_value', 15, 4)->nullable();
            $table->boolean('boolean_value')->nullable();
            $table->text('text_value')->nullable();
            $table->timestamps();

            $table->index(['attribute_id', 'string_value']);
            $table->index(['attribute_id', 'number_value']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_attribute_values');
    }
};
