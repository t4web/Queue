<?php

namespace T4web\Queue;

use Zend\Console\Adapter\AdapterInterface as Console;

class Module
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__,
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include dirname(__DIR__) . '/config/module.config.php';
    }

    public function getConsoleUsage(Console $console)
    {
        return array(
            // Describe available commands
            'queue init' => 'Check config, create table `queue_messages`',
            'queue realtime-server' => 'Run realtime queue server',
            'queue worker QUEUE-NAME MESSAGE' => 'Run queue worker',
        );
    }
}

