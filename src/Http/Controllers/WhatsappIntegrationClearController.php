<?php

namespace EglobalOneLab\WhatsappIntegration\Http\Controllers;

use Carbon\Carbon;
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

            //Update created at when finish the typebot flow
            $integrationRecord = WhatsappIntegration::where('whatsapp_id', $phone)->firstOrFail();
            $integrationRecord->update([
                'created_at' => Carbon::now()
            ]);
        } catch (\Exception $e) {
            Log::error('Error clearing whatsapp integration history', [
                'context' => $e->getMessage()
            ]);
        }
    }
}
