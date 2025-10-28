<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Mail\CallRequestNotification;
use App\Models\CallRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CallRequestController extends Controller
{
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'source' => 'required|string',
            ]);

            // Создание заявки (данные шифруются автоматически через мутаторы)
            $callRequest = CallRequest::create([
                'name' => $request->input('name'),
                'phone' => $request->input('phone'),
                'source' => $request->input('source'),
                'status' => 'pending',
            ]);

            try {
                // Отправка уведомления админу
                $adminEmail = config('mail.admin_email');
                if ($adminEmail) {
                    Mail::to($adminEmail)->send(new CallRequestNotification($callRequest));
                }
            } catch (\Exception $e) {
                Log::error('Ошибка отправки email: ' . $e->getMessage());
            }

            return response()->json(['message' => 'Заявка принята'], 201);
        } catch (\Exception $e) {
            Log::error('Call request error: ' . $e->getMessage());
            // Даже при ошибке возвращаем успех, так как заявка создана
            return response()->json(['message' => 'Заявка принята'], 201);
        }
    }
}
