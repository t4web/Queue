<?php

namespace T4web\Queue;

return [
    'router' => array(
        'routes' => array(
            'queue-init' => [
                'type' => 'Simple',
                'options' => [
                    'route' => 'queue init',
                    'defaults' => [
                        'controller' => Action\Console\Init::class,
                    ]
                ]
            ],
            'queue-run-realtime-server' => [
                'type' => 'simple',
                'options' => [
                    'route' => 'queue realtime-server',
                    'defaults' => [
                        'controller' => Action\Console\RunRealtimeServer::class,
                    ]
                ]
            ],
            'queue-run-worker' => [
                'type' => 'simple',
                'options' => [
                    'route' => 'queue worker <queueName> <messageId>',
                    'defaults' => [
                        'controller' => Action\Console\Worker::class,
                    ]
                ]
            ],
        ),
    ),
];
