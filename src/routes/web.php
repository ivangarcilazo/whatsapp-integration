<?php

use Carbon\Carbon;
use EglobalOneLab\WhatsappIntegration\Http\Controllers\BitrixController;
use EglobalOneLab\WhatsappIntegration\Http\Controllers\WhatsappIntegrationClearController;
use EglobalOneLab\WhatsappIntegration\Models\WhatsappIntegration;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::post('/whatsapp-integration-webhook', [\EglobalOneLab\WhatsappIntegration\Http\Controllers\TwilioController::class, 'webhook']);

Route::post('/whatsapp-integration-history', [BitrixController::class, 'index']);

Route::post('/whatsapp-integration-clear', [WhatsappIntegrationClearController::class, 'store']);

Route::get('/whatsapp-integration-user-check/{phone_number}', function (Request $request, $phone_number) {
    $phone = str_replace('+', '', (string) $phone_number);

    $user = WhatsappIntegration::where('whatsapp_id', $phone)->first();

    if (!$user) {
        return response()->json([
            'success' => 200,
            'can_use_chatbot' => true,
        ]);
    }

    $createdAt = Carbon::parse($user->created_at);
    $canUseChatbot = $createdAt->addHours(24)->lte(now());

    return response()->json([
        'success' => 200,
        'can_use_chatbot' => $canUseChatbot,
    ]);
});
