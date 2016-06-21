<?php
namespace T4web\Queue\Action\Console;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class WorkerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $controllerManager)
    {
        $serviceLocator = $controllerManager->getServiceLocator();

        $config = $serviceLocator->get('Config');

        return new Worker(
            $config['t4web-queue']['queues'],
            $serviceLocator->get('QueueMessage\Infrastructure\Repository')
        );
    }
}
