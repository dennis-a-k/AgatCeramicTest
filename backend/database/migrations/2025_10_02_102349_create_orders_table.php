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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order')->unique(); // номер заказа
            $table->text('name'); // имя клиента
            $table->text('email'); // email клиента
            $table->text('phone'); // телефон клиента
            $table->text('address'); // адрес доставки
            $table->text('comment')->nullable(); // комментарий к заказу
            $table->decimal('total_amount', 10, 2); // общая сумма
            $table->string('status')->default('pending'); // статус заказа
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained();
            $table->string('product_article'); // артикул товара
            $table->string('product_name'); // название товара
            $table->string('product_unit'); // единица измерения
            $table->decimal('price', 10, 2); // цена
            $table->integer('quantity'); // количество
            $table->decimal('subtotal', 10, 2); // подытог
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
