<?php

namespace EglobalOneLab\WhatsappIntegration\Http\Services;

use EglobalOneLab\WhatsappIntegration\Models\WhatsappHistory;
use EglobalOneLab\WhatsappIntegration\Models\WhatsappIntegration;
use Exception;
use GuzzleHttp\Client;

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
     * Save data from user
     */
    public function saveRecord()
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
     * Send message to TypeBot system.
     * This create a new chat or continue old chat
     */
    public function sendMessage()
    {
        $client = new Client();

        $url = $this->getUrl();

        try {

            $response = $client->post($url, [
                'headers' => [
                    'Content-Type' => 'application/json'
                ],
                'json' => [
                    'message' => $_POST['Body']
                ]
            ]);

            $this->response = json_decode($response->getBody(), true);

            $this->saveRecord();

            // Save history from messages of user
            $this->saveHistory();

            $this->responseMessage();
        } catch (Exception $e) {

            if ($e->getCode() == 404) {
                // Handler messages when chatbot is finished
                $bitrix = new BitrixService();
                $bitrix->sendMessage();

                return;
            }

            throw new Exception($e->getMessage());
        }
    }

    /**
     * Send a response to user with twilio and save history for bitrix
     * @return void
     */
    public function responseMessage()
    {
        $messages = $this->parsedResponse();

        $twilio = new TwilioService();

        $postCopy = [...$_POST];

        foreach ($messages as $message) {

            $twilio->sendMessage($message['content'], $_POST['From']);

            $postCopy['Body'] = "Mensaje CHATBOT :" . PHP_EOL . $message['content'];

            // Save chatbot messages for send to bitrix
            $this->saveHistory($postCopy);
        }
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
}
