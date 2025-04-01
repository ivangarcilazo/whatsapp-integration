<?php

namespace Eglobal\WhatsappIntegration\Http\Controllers;

use Eglobal\WhatsappIntegration\Http\Services\TwilioService;
use Illuminate\Support\Facades\Log;

class TwilioController
{
    public function webhook()
    {

        $twilio = new TwilioService();

        $twilio->senders();

    }
}
