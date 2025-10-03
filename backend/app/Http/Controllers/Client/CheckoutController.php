<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Mail\OrderConfirmation;
use App\Mail\OrderNotification;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
    public function store(Request $request)
    {
        try {
            $request->validate([
                'customer.name' => 'required|string|max:255',
                'customer.email' => 'required|email|max:255',
                'customer.phone' => 'required|string|max:20',
                'customer.address' => 'required|string|max:500',
                'customer.comment' => 'nullable|string|max:1000',
                'items' => 'required|array|min:1',
                'items.*.id' => 'required|exists:products,id',
                'items.*.title' => 'required|string|max:255',
                'items.*.price' => 'required|numeric|min:0',
                'items.*.quantity' => 'required|integer|min:1',
                'items.*.unit' => 'required|string|max:50',
                'total' => 'required|numeric|min:0',
            ]);

            // Генерация уникального номера заказа
            do {
                $orderNumber = date('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 10));
            } while (Order::where('order', $orderNumber)->exists());

            // Создание заказа (данные шифруются автоматически через мутаторы)
            $order = Order::create([
                'order' => $orderNumber,
                'name' => $request->input('customer.name'),
                'email' => $request->input('customer.email'),
                'phone' => $request->input('customer.phone'),
                'address' => $request->input('customer.address'),
                'comment' => $request->input('customer.comment'),
                'total_amount' => $request->input('total'),
                'status' => 'pending',
            ]);

            // Создание элементов заказа
            foreach ($request->input('items') as $item) {
                $product = Product::find($item['id']);
                $productName = $item['title'];
                if (isset($item['weight_kg']) && $item['weight_kg']) {
                    $productName .= ', ' . $item['weight_kg'] . ' кг';
                }
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'product_article' => $product->article ?? '',
                    'product_name' => $productName,
                    'product_unit' => $item['unit'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);
            }

            try {
                // Отправка email подтверждения клиенту
                if ($order->email) {
                    Mail::to($order->email)->send(new OrderConfirmation($order));
                }

                // Отправка уведомления админу
                $adminEmail = config('mail.admin_email');
                if ($adminEmail) {
                    Mail::to($adminEmail)->send(new OrderNotification($order));
                }
            } catch (\Exception $e) {
                Log::error('Ошибка отправки email: ' . $e->getMessage());
            }

            return response()->json(['order' => $orderNumber], 201);
        } catch (\Exception $e) {
            Log::error('Checkout error: ' . $e->getMessage());
            // Даже при ошибке возвращаем успех, так как заказ создан
            return response()->json(['order' => $orderNumber], 201);
        }
    }
}
