<?php

namespace EglobalOneLab\WhatsappIntegration\Http\Controllers;

use EglobalOneLab\WhatsappIntegration\Http\Services\BitrixService;
use EglobalOneLab\WhatsappIntegration\Models\WhatsappHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BitrixController
{
    public function index(Request $request)
    {
        $request->validate([
            'phone_number' => 'required'
        ]);

        $phone = $request->get('phone_number');

        $records = WhatsappHistory::where('whatsapp_id', (string) str_replace('+', '', $phone))->get();

        $bitrix = new BitrixService();

        collect($records)->each(function (WhatsappHistory $record) use ($bitrix) {
            $bitrix->sendMessage($record->messages);

            sleep(.5);
        });
    }
}
