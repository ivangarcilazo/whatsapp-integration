<?php

namespace EglobalOneLab\WhatsappIntegration\Http\Services;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class BitrixService
{

    public function __construct()
    {
        if (!config('whatsapp-integration.bitrix.key')) {
            throw new Exception('You must set the bitrix key in the config file');
        }
    }

    public function sendMessage($form_params = null)
    {
        $client = new Client();

        try {
            $client->post(config('whatsapp-integration.bitrix.key'), [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ],
                'form_params' => $form_params ?? $_POST
            ]);
        } catch (Exception $e) {
            Log::info($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }
}
