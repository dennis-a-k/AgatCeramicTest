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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('article')->unique();
            $table->string('name');
            $table->string('slug')->unique();
            $table->decimal('price', 10, 2);
            $table->string('unit');
            $table->string('product_code')->nullable();
            $table->text('description')->nullable();

            $table->foreignId('category_id')->constrained();
            $table->foreignId('color_id')->constrained();
            $table->foreignId('brand_id')->constrained();

            $table->boolean('is_published')->default(false);
            $table->boolean('is_sale')->default(false);

            $table->string('texture')->nullable();
            $table->string('pattern')->nullable();
            $table->string('country')->nullable();
            $table->string('collection')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Основные одиночные индексы
            $table->index('article');
            $table->index('name');
            $table->index('product_code');
            $table->index('price');
            $table->index('is_published');
            $table->index('is_sale');
            $table->index('category_id');
            $table->index('brand_id');
            $table->index('color_id');
            $table->index('created_at');

            // Составные индексы для частых сценариев:
            $table->index(['category_id', 'is_published']); // Для списка товаров категории
            $table->index(['brand_id', 'is_published']); // Для страницы бренда
            $table->index(['is_published', 'is_sale']); // Для распродаж
            $table->index(['category_id', 'brand_id', 'is_published']); // Для фильтрации
            $table->index(['is_published', 'created_at']); // Новинки на главной
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
