<?php

use App\Models\Information;
use Illuminate\Support\Facades\Route;

Route::prefix('client')->group(function () {
    Route::get('/information', function () {
        $information = Information::first();

        if (!$information) {
            return response()->json([
                'message' => 'Информация не найдена'
            ], 404);
        }

        return response()->json([
            'phone' => $information->phone,
            'email' => $information->email,
            'telegram' => $information->telegram,
            'whatsapp' => $information->whatsapp,
            'organization' => $information->organization,
            'adress' => $information->adress,
            'inn' => $information->inn,
            'ogrn' => $information->ogrn,
        ]);
    });
});
