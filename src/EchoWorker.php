<?php

namespace T4web\Queue;

class EchoWorker implements WorkerInterface
{
    public function handle(array $data)
    {
        echo PHP_EOL . var_export($data, true) . PHP_EOL;
    }
}