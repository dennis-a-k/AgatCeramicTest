<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\CallRequest;
use Illuminate\Database\Seeder;

class FillSearchableFieldsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hasher = app(\App\Contracts\HasherInterface::class);

        // Обновляем searchable поля для существующих заказов
        Order::all()->each(function ($order) use ($hasher) {
            if ($order->name) {
                $order->searchable_name = strtolower($order->name);
            }
            if ($order->email) {
                $order->searchable_email = $hasher->hash(strtolower($order->email));
            }
            if ($order->phone) {
                $phone = preg_replace('/[^0-9]/', '', $order->phone);
                $order->searchable_phone = $hasher->hash($phone);
            }
            $order->save();
        });

        // Обновляем searchable поля для существующих заявок на звонок
        CallRequest::all()->each(function ($callRequest) use ($hasher) {
            if ($callRequest->name) {
                $callRequest->searchable_name = strtolower($callRequest->name);
            }
            if ($callRequest->phone) {
                $phone = preg_replace('/[^0-9]/', '', $callRequest->phone);
                $callRequest->searchable_phone = $hasher->hash($phone);
            }
            if ($callRequest->email) {
                $callRequest->searchable_email = $hasher->hash(strtolower($callRequest->email));
            }
            $callRequest->save();
        });
    }
}
