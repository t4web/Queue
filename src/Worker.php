<?php
namespace T4web\Queue;


interface Worker
{
    public function handle(array $data);
}