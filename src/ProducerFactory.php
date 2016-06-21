<?php

namespace T4web\Queue;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ProducerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');

        return new Producer(
            $serviceLocator->get('QueueMessage\Infrastructure\Repository'),
            $config['t4web-queue']
        );
    }
}
