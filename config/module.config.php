<?php

namespace T4web\Queue;

return [
    'console' => array(
        'router' => array(
            'routes' => array(
                'run-realtime-server' => [
                    'type' => 'simple',
                    'options' => [
                        'route' => 'queue realtime-server',
                        'defaults' => [
                            'controller' => Action\Console\RunRealtimeServer::class,
                        ]
                    ]
                ],
            ),
        ),
    ),

    'controllers' => array(
        'factories' => array(
            Action\Console\RunRealtimeServer::class => Action\Console\RunRealtimeServerFactory::class,
        ),
    ),
];
