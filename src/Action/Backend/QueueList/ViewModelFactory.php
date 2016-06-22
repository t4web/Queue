<?php

namespace T4web\Queue\Action\Backend\QueueList;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ViewModelFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');
        return new ViewModel(
            $config['t4web-queue']['queues']
        );
    }
}