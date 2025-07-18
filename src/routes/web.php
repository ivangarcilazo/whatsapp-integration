<?php

use EglobalOneLab\WhatsappIntegration\Http\Controllers\BitrixController;
use EglobalOneLab\WhatsappIntegration\Http\Controllers\WhatsappIntegrationClearController;
use Illuminate\Support\Facades\Route;

Route::post('/whatsapp-integration-webhook', [\EglobalOneLab\WhatsappIntegration\Http\Controllers\TwilioController::class, 'webhook']);

Route::post('/whatsapp-integration-history', [BitrixController::class, 'index']);

Route::post('/whatsapp-integration-clear', [WhatsappIntegrationClearController::class, 'store']);
