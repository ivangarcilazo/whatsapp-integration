<?php

namespace EglobalOneLab\WhatsappIntegration\Http\Services;

use EglobalOneLab\WhatsappIntegration\Models\WhatsappHistory;
use EglobalOneLab\WhatsappIntegration\Models\WhatsappIntegration;
use Exception;
use GuzzleHttp\Client;
use Psr\Http\Message\StreamInterface;

class TypeBotService
{
    protected $user = null;
    protected $response;

    public function __construct()
    {
        $this->user = WhatsappIntegration::where('whatsapp_id', $_POST['WaId'])->first();
    }

    /**
     * Return conversation with typebot to continue chat or start a new chat
     * @return string
     */
    public function getUrl(): string
    {
        $hasConversation = $this->user;

        if ($hasConversation) {
            $sessionId = $hasConversation->typebot_session_id;
            return "https://typebot-view.eg1lab.com/api/v1/sessions/$sessionId/continueChat";
        }

        $typeBot = config('whatsapp-integration.typebot.typeboy_id');

        if (!$typeBot) {
            throw new Exception('You must be add typeboy ID for start a chat');
        }

        return "https://typebot-view.eg1lab.com/api/v1/typebots/$typeBot/startChat";
    }

    /**
     * Save data from user if not exist
     */
    public function saveRecordIfNotExist()
    {
        if (!$this->user) {
            WhatsappIntegration::create([
                'whatsapp_id' => $_POST['WaId'],
                'typebot_session_id' => $this->response['sessionId']
            ]);
        }
    }

    /**
     * Save history of chat
     * @param array $data Data of post from twilio to send to bitrix
     */
    public function saveHistory($data = null)
    {
        WhatsappHistory::create([
            'whatsapp_id' => $this->user->whatsapp_id ?? $_POST['WaId'],
            'messages' => $data ?? $_POST
        ]);
    }

    /**
     * General message management 
     *  - Send message to TypeBot system.
     *  - Create a new chat or continue old chat
     */
    public function sendMessage()
    {
        try {
            $this->response = json_decode($this->getResponseTypebot(), true);

            $this->saveRecordIfNotExist();

            $this->saveHistory();

            $this->responseUserTypebot();
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Send a response to user with twilio and save history for bitrix
     * @return void
     */
    public function responseUserTypebot()
    {
        $messages = $this->parsedResponse();

        if (empty($messages)) {
            $this->handleEmptyResponse();
            return;
        }

        $twilio = new TwilioService();

        $postCopy = [...$_POST];

        foreach ($messages as $message) {

            $twilio->sendMessage($message['content'], $_POST['From']);

            $postCopy['Body'] = "Mensaje CHATBOT :" . PHP_EOL . $message['content'];

            // Save chatbot messages for send to bitrix
            $this->saveHistory($postCopy);

            usleep(500000); //pause 5ms
        }
    }

    /**
     * Handle empty response from typebot sending user message to bitrix
     * @return  void
     */
    public function handleEmptyResponse()
    {
        $bitrix = new BitrixService();
        $bitrix->sendMessage();
    }

    /**
     * Parse response from typebot
     */
    protected function parsedResponse(): array
    {
        if (!$this->response) {
            throw new \Exception('Response is null or invalid');
        }

        $messages = [];

        if ($this->user) {

            foreach ($this->response['messages'] as $message) {

                $content = '';

                foreach ($message['content']['richText'][0]['children'] as $contentMessage) {

                    if (isset($contentMessage['type']) && $contentMessage['type'] == 'inline-variable') {

                        $content .= $contentMessage['children'][0]['children'][0]['text'];

                        continue;
                    }

                    $content .= $contentMessage['text'];
                }

                $messages[] = [
                    'type' => $message['type'],
                    'content' => $content
                ];
            }

            return $messages;
        }

        foreach ($this->response['messages'] as $message) {
            $messages[] = [
                'type' => $message['type'],
                'content' => $message['content']['richText'][0]['children'][0]['text']
            ];
        }

        $input = $this->response['input'];

        // TODO: make example with other inputs
        if (isset($input['id'])) {
            $messages[] = [
                'type' => $input['type'],
                'content' => $input['options']['labels']['placeholder']
            ];
        }

        return $messages;
    }

    /**
     * Get response from typebot sending user message
     */
    protected function getResponseTypebot(): StreamInterface
    {

        $client = new Client();

        $url = $this->getUrl();

        $response = $client->post($url, [
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'json' => [
                'message' => $_POST['Body']
            ]
        ]);

        // TODO: separate logic and improve (i have a headache rn)
        // Intercept response from typebot for response backend data or handling data avoiding user input
        if (str_contains((string)$response->getBody(), 'get_phone_number')) {

            $response = $client->post($url, [
                'headers' => [
                    'Content-Type' => 'application/json'
                ],
                'json' => [
                    'message' => $_POST['WaId']
                ]
            ]);
            return $response->getBody();
        }

        return $response->getBody();
    }
}
