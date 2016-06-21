<?php

namespace T4web\Queue\Action\Console;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class InitFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $controllerManager)
    {
        $serviceLocator = $controllerManager->getServiceLocator();

        return new Init(
            $serviceLocator->get('Zend\Db\Adapter\Adapter')
        );
    }
}
