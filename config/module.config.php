<?php

namespace T4web\Queue;

return [
    'entity_map' => include 'entity_map.config.php',
    't4web-queue' => include 't4web-queue.config.php',
    'console' => include 'console.config.php',

    'controllers' => array(
        'factories' => array(
            Action\Console\Init::class => Action\Console\InitFactory::class,
            Action\Console\RunRealtimeServer::class => Action\Console\RunRealtimeServerFactory::class,
            Action\Console\Worker::class => Action\Console\WorkerFactory::class,
        ),
    ),
];
