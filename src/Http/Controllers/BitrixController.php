<?php

namespace EglobalOneLab\WhatsappIntegration\Http\Controllers;

use EglobalOneLab\WhatsappIntegration\Http\Services\BitrixService;
use EglobalOneLab\WhatsappIntegration\Models\WhatsappHistory;
use EglobalOneLab\WhatsappIntegration\Models\WhatsappIntegration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class BitrixController
{
    public function index(Request $request)
    {
        $request->validate([
            'phone_number' => 'required'
        ]);

        $phone = $request->get('phone_number');
        $phone = (string) str_replace('+', '', $phone);

        $records = WhatsappHistory::where('whatsapp_id', $phone)->get();

        if ($records->isNotEmpty()) {

            $bitrix = new BitrixService();

            try {
                collect($records)->each(function (WhatsappHistory $record) use ($bitrix) {
                    $bitrix->sendMessage($record->messages);
                    sleep(.5);
                });

                WhatsappHistory::whereIn('id', $records->pluck('id'))->delete();

                $integrationRecord = WhatsappIntegration::where('whatsapp_id', $phone)->firstOrFail();

                $integrationRecord->delete();
            } catch (Exception $e) {

                Log::error('Error sending message on bitrix:' . $e->getMessage());

                throw new Exception($e->getMessage());
            }
        }

        // $integrationRecord = WhatsappIntegration::where('whatsapp_id', $phone)->first();

        // $integrationRecord->delete();
    }
}
