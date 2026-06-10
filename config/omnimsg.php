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
                        'base_url' => env('WASENDERAPI_BASE_URL', 'https://api.wasenderapi.com/'),
                        'token'    => env('WASENDERAPI_TOKEN'),
                    ],
                ],
            ],
        ],
        'sms' => [],
        // ... other channels
    ],
];
