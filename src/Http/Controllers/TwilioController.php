<?php

namespace EglobalOneLab\WhatsappIntegration\Http\Controllers;

use EglobalOneLab\WhatsappIntegration\Http\Services\TwilioService;

class TwilioController
{
    public function webhook()
    {

        $twilio = new TwilioService();

        $twilio->senders();

    }
}
