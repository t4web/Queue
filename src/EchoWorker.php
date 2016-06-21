<?php
namespace T4web\Queue;


class EchoWorker implements Worker
{
    public function handle(array $data)
    {
        sleep(2);
        echo PHP_EOL . var_export($data, true) . PHP_EOL;
    }
}