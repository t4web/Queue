<?php
namespace T4web\Queue;


interface WorkerInterface
{
    public function handle(array $data);
}