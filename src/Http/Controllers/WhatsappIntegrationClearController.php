<?php

namespace EglobalOneLab\WhatsappIntegration\Http\Controllers;

use EglobalOneLab\WhatsappIntegration\Models\WhatsappHistory;
use EglobalOneLab\WhatsappIntegration\Models\WhatsappIntegration;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class WhatsappIntegrationClearController
{
    public function store(Request $request)
    {
        $request->validate([
            'phone_number' => 'required'
        ]);

        $phone = $request->get('phone_number');
        $phone = (string) str_replace('+', '', $phone);

        $records = WhatsappHistory::where('whatsapp_id', $phone)->get();

        try {
            WhatsappHistory::whereIn('id', $records->pluck('id'))->delete();

            $integrationRecord = WhatsappIntegration::where('whatsapp_id', $phone)->firstOrFail();

            $integrationRecord->delete();
        } catch (\Exception $e) {
            Log::error('Error clearing whatsapp integration history', [
                'context' => $e->getMessage()
            ]);
        }
    }
}
