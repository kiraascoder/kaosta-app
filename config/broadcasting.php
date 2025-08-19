<?php

return [
    'default' => env('BROADCAST_DRIVER', 'null'),

    'connections' => [
        'pusher' => [
            'driver' => 'pusher',
            'key' => env('PUSHER_APP_KEY'),
            'secret' => env('PUSHER_APP_SECRET'),
            'app_id' => env('PUSHER_APP_ID'),
            'options' => [
                'cluster' => env('PUSHER_APP_CLUSTER'),
                'host' => '127.0.0.1',
                'port' => 6001,
                'scheme' => 'http',
                'encrypted' => false,
                'useTLS' => false,
            ],
            'client_options' => [
                //
            ],
        ],
        'redis' => [
            'driver' => 'redis',
            'connection' => 'default',
        ],
        'null' => [
            'driver' => 'null',
        ],
    ],
];
