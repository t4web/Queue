<?php

namespace T4web\Queue;

return [

    'realtime-server' => [
        'enabled' => true,
        'hostname' => 'localhost',
        'port' => 4000,
    ],

    'queues' => [

        // Queue name
        'test-engine' => [

            // Handler class
            'handler' => EchoWorker::class,

            // count workers, optional, default 1
            'worker-count' => 1,

            // You can limit the amount of time a process takes to complete by setting a timeout (in seconds)
            // optional, default 300
            'timeout' => 300,

            // optional, default 0
            'debug-enable' => 1,
        ],
    ],
];
