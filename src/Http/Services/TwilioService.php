<?php

namespace EglobalOneLab\WhatsappIntegration\Http\Services;

use Exception;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;

class TwilioService
{

    protected $twilio;
    protected $ownNumber = '';

    public function __construct()
    {

        if (!config('whatsapp-integration.twilio.phone_number') || !config('whatsapp-integration.twilio.sid') || !config('whatsapp-integration.twilio.auth')) {
            throw new Exception('You must set the phone number, sid and auth in the config file');
        }

        $this->ownNumber = config('whatsapp-integration.twilio.phone_number');
        $this->twilio = new Client(config('whatsapp-integration.twilio.sid'), config('whatsapp-integration.twilio.auth'));
    }

    /**
     * Send a messsage
     * @param string $content
     * @param string $to The number you'd like to send the message to
     */
    public function sendMessage(string $content, string $to)
    {

        if (!str_contains($to, 'whatsapp')) {
            throw new Exception('To send a message using Whatsapp channel, the "whatsapp:+xxxxxx" format is required');
        }

        try {

            $this->twilio->messages->create(
                $to,
                [
                    'from' => 'whatsapp:' . $this->ownNumber,
                    'body' => $content
                ]
            );
        } catch (\Exception $e) {
            Log::info($e);
        }
    }

    /**
     * Send message to services of Bitrix and Typebot
     * 
     * 
     */
    public function senders()
    {
        $typebot = new TypeBotService();
        $typebot->sendMessage();
    }
}
