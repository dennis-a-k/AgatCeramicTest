<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Order::with('items');

        // Поиск
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order', 'like', '%' . $search . '%')
                  ->orWhere(function ($sq) use ($search) {
                      $sq->searchByName($search);
                  })
                  ->orWhere(function ($sq) use ($search) {
                      $sq->searchByEmail($search);
                  });
            });
        }

        // Поиск по телефону
        if ($request->has('phone') && !empty($request->phone)) {
            $phone = $request->phone;
            $query->where(function ($q) use ($phone) {
                $q->searchByPhone($phone);
            });
        }

        // Фильтр по статусу
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        // Пагинация
        $perPage = $request->get('per_page', 50);
        $orders = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json([
            'orders' => $orders
        ]);
    }

    public function show($orderNumber): JsonResponse
    {
        $order = Order::with(['items.product' => function($query) {
            $query->select('id', 'name', 'article')->with(['images' => function($imageQuery) {
                $imageQuery->orderBy('sort_order')->select('product_id', 'image_path');
            }]);
        }])->where('order', $orderNumber)->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Форматируем данные для фронтенда
        $formattedOrder = $order->toArray();
        $formattedOrder['items'] = $order->items->map(function($item) {
            $product = $item->product;
            return [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'product_article' => $item->product_article,
                'product_name' => $item->product_name,
                'product_unit' => $item->product_unit,
                'price' => $item->price,
                'quantity' => $item->quantity,
                'subtotal' => $item->subtotal,
                'product' => $product ? [
                    'id' => $product->id,
                    'name' => $product->name,
                    'article' => $product->article,
                    'image' => $product->images->isNotEmpty() ? $product->images->first()->image_path : null,
                ] : null,
            ];
        });

        return response()->json(['order' => $formattedOrder]);
    }

    public function statistics(Request $request): JsonResponse
    {
        // Получаем выбранный месяц или используем текущий
        $selectedMonth = $request->has('month') && !empty($request->month)
            ? \Carbon\Carbon::createFromFormat('Y-m', $request->month)->startOfMonth()
            : now()->startOfMonth();

        $currentMonthStart = $selectedMonth;
        $currentMonthEnd = $selectedMonth->copy()->endOfMonth();
        $previousMonthStart = $selectedMonth->copy()->subMonth();
        $previousMonthEnd = $selectedMonth->copy()->subMonth()->endOfMonth();

        // Статистика за весь период (для pending и processing)
        $allTimeStats = [
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
        ];

        // Текущий месяц (для shipped и total_amount)
        $currentStats = [
            'shipped' => Order::where('status', 'shipped')
                ->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
                ->count(),
            'total_amount' => Order::where('status', 'shipped')
                ->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
                ->sum('total_amount'),
        ];

        // Предыдущий месяц (для shipped и total_amount)
        $previousStats = [
            'shipped' => Order::where('status', 'shipped')
                ->whereBetween('created_at', [$previousMonthStart, $previousMonthEnd])
                ->count(),
            'total_amount' => Order::where('status', 'shipped')
                ->whereBetween('created_at', [$previousMonthStart, $previousMonthEnd])
                ->sum('total_amount'),
        ];

        // Расчет процентов только для shipped и total_amount
        $calculatePercentage = function($current, $previous) {
            if ($previous == 0) return $current > 0 ? 100 : 0;
            return round((($current - $previous) / $previous) * 100, 2);
        };

        $statistics = [
            'current' => array_merge($allTimeStats, $currentStats),
            'previous' => $previousStats,
            'percentages' => [
                'pending' => 0, // Не показываем проценты для pending
                'processing' => 0, // Не показываем проценты для processing
                'shipped' => $calculatePercentage($currentStats['shipped'], $previousStats['shipped']),
                'total_amount' => $calculatePercentage($currentStats['total_amount'], $previousStats['total_amount']),
            ]
        ];

        return response()->json([
            'statistics' => $statistics
        ]);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $validated = $request->validate([
            'status' => 'sometimes|in:pending,processing,shipped,return,cancelled',
            'comment' => 'nullable|string|max:1000',
        ]);

        $order->update($validated);

        return response()->json([
            'message' => 'Order updated successfully',
            'order' => $order->load('items')
        ]);
    }
}
