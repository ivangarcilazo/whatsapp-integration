<?php

use EglobalOneLab\WhatsappIntegration\Http\Controllers\BitrixController;
use Illuminate\Support\Facades\Route;

Route::post('/whatsapp-integration-webhook', [\EglobalOneLab\WhatsappIntegration\Http\Controllers\TwilioController::class, 'webhook']);

Route::post('/whatsapp-integration-history', [BitrixController::class, 'index']);
