<?php

return [
    'channels' => [
        'whatsapp' => [
            'default_driver' => env('OMNIMSG_WHATSAPP_DRIVER', 'whapi'),
            'drivers' => [
                'whapi' => [
                    'class' => \OmniMsg\Channels\WhatsApp\Drivers\WhapiCloudDriver::class,
                    'credentials' => [
                        'base_url' => env('WHAPI_BASE_URL', 'https://gate.whapi.cloud/'),
                        'token'    => env('WHAPI_TOKEN'),
                    ],
                ],
                'wasenderapi' => [
                    'class' => \OmniMsg\Channels\WhatsApp\Drivers\WasenderApiDriver::class,
                    'credentials' => [
                        'base_url' => env('WASENDERAPI_BASE_URL', 'https://www.wasenderapi.com/'),
                        'token'    => env('WASENDERAPI_TOKEN'),
                        
                        'webhook_secret' => env('WASENDERAPI_WEBHOOK_SECRET', ''),
                        'webhook_route' => env('WASENDERAPI_WEBHOOK_ROUTE', '/wasender/webhook'),
                        'webhook_signature_header' => env('WASENDERAPI_WEBHOOK_SIGNATURE_HEADER', 'x-webhook-signature'),
                        'api_key' => env('WASENDERAPI_API_KEY', ''),
                        'personal_access_token' => env('WASENDERAPI_PERSONAL_ACCESS_TOKEN', ''),
                    ],
                ],
            ],
        ],
        'sms' => [],
        // ... other channels
    ],
];
