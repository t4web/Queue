<?php

namespace T4web\Queue;

return [
    'entity_map' => include 'entity_map.config.php',
    't4web-queue' => include 't4web-queue.config.php',
    'console' => include 'console.config.php',
    'sebaks-view' => include 'sebaks-view.config.php',
    't4web-crud' => include 't4web-crud.config.php',

    'controllers' => array(
        'factories' => array(
            Action\Console\Init::class => Action\Console\InitFactory::class,
            Action\Console\RunRealtimeServer::class => Action\Console\RunRealtimeServerFactory::class,
            Action\Console\Worker::class => Action\Console\WorkerFactory::class,
        ),
    ),

    'view_manager' => [
        'template_path_stack' => [
            'cest' => __DIR__ . '/../view',
        ],
    ],
];
