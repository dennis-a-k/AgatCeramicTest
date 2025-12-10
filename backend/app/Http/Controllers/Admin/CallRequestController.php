<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CallRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CallRequestController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = CallRequest::query();

        // Поиск по имени или телефону
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Фильтр по статусу
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        $perPage = $request->get('per_page', 50);
        $callRequests = $query->orderBy('created_at', 'desc')
                              ->paginate($perPage);

        return response()->json([
            'call_requests' => $callRequests
        ]);
    }

    public function show(CallRequest $callRequest): JsonResponse
    {
        return response()->json([
            'call_request' => $callRequest
        ]);
    }

    public function update(Request $request, CallRequest $callRequest): JsonResponse
    {
        $request->validate([
            'status' => 'sometimes|in:pending,processed',
            'comment' => 'nullable|string',
            'source' => 'nullable|string',
        ]);

        $callRequest->update($request->only(['status', 'comment', 'source']));

        return response()->json([
            'call_request' => $callRequest,
            'message' => 'Заявка обновлена'
        ]);
    }

    public function statistics(Request $request): JsonResponse
    {
        $month = $request->get('month', now()->format('Y-m'));
        $selectedMonth = \Carbon\Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $nextMonth = $selectedMonth->copy()->addMonth();
        $previousMonth = $selectedMonth->copy()->subMonth();

        $currentStats = CallRequest::selectRaw('status, COUNT(*) as count')
            ->whereBetween('created_at', [$selectedMonth, $nextMonth])
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $previousStats = CallRequest::selectRaw('status, COUNT(*) as count')
            ->whereBetween('created_at', [$previousMonth, $selectedMonth])
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $statuses = ['pending', 'processed'];

        $statistics = [
            'current' => array_fill_keys($statuses, 0),
            'previous' => array_fill_keys($statuses, 0),
            'percentages' => array_fill_keys($statuses, 0),
        ];

        foreach ($statuses as $status) {
            $statistics['current'][$status] = $currentStats[$status] ?? 0;
            $statistics['previous'][$status] = $previousStats[$status] ?? 0;

            if ($statistics['previous'][$status] > 0) {
                $statistics['percentages'][$status] = round(
                    (($statistics['current'][$status] - $statistics['previous'][$status]) / $statistics['previous'][$status]) * 100,
                    2
                );
            }
        }

        return response()->json([
            'statistics' => $statistics
        ]);
    }
}
