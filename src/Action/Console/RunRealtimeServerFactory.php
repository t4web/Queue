<?php
namespace T4web\Queue\Action\Console;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use React\EventLoop\Factory as EventLoopFactory;
use React\Socket\Server as SocketServer;

class RunRealtimeServerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $controllerManager)
    {
        $serviceLocator = $controllerManager->getServiceLocator();

        $loop = EventLoopFactory::create();

        return new RunRealtimeServer(
            $loop,
            new SocketServer($loop),
            new Process('ls')
        );
    }
}
