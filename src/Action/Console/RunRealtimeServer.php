<?php
namespace T4web\Queue\Action\Console;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use React\Socket\Server as SocketServer;
use React\Socket\ConnectionInterface;
use Symfony\Component\Process\Process;

class RunRealtimeServer extends AbstractActionController
{
    /**
     * @var SocketServer
     */
    private $socket;

    /**
     * @var Backend\RunCest\Service
     */
    private $loop;

    /**
     * @var Process
     */
    private $process;

    public function __construct(
        SocketServer $loop,
        SocketServer $socketServer,
        Process $process
    )
    {
        $this->loop = $loop;
        $this->socket = $socketServer;
        $this->process = $process;
    }

    public function onDispatch(MvcEvent $e)
    {
        $this->socket->on('connection', function (ConnectionInterface $conn) use ($consumer, $callback) {
            $conn->on('data', function ($data) use ($conn, $consumer, $callback) {
                $this->handleData(trim($data), $consumer, $conn, $callback);
            });
        });

        $this->socket->listen(4000);
        $this->loop->run();
    }

    public function handleData($data, $consumer, ConnectionInterface $connection, $callback = null)
    {
        $command = sprintf('%s "%s"', $consumer, addslashes($data));
        if ($callback && is_callable($callback)) {
            call_user_func($callback, $data);
        }
        $this->process
            ->setCommandLine($command)
            ->run();
        
        $connection->close();
    }
}