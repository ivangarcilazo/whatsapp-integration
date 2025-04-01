<?php

return [
    'bitrix' => [
        'key' => env('EGLOBAL_BITRIX_HOOK'),
    ],
    'twilio' => [
        'sid' => env('EGLOBAL_TWILIO_SID'),
        'auth' => env('EGLOBAL_TWILIO_AUTH'),
        'phone_number' => env('EGLOBAL_PHONE_NUMBER'),
    ],
    'typebot' => [
        'typeboy_id' => env('EGLOBAL_TYPEBOT_ID'),
    ],
];
