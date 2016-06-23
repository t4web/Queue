<?php
namespace T4web\Queue\Action\Console;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Zend\Json\Json;
use React\Socket\Server as SocketServer;
use React\Socket\ConnectionInterface;
use React\EventLoop\StreamSelectLoop;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessTimedOutException;

class RunRealtimeServer extends AbstractActionController
{
    /**
     * @var SocketServer
     */
    private $socket;

    /**
     * @var StreamSelectLoop
     */
    private $loop;

    /**
     * @var Process
     */
    private $processes = [];

    /**
     * @var array
     */
    private $config;

    /**
     * @var array
     */
    private $queueConfigs;

    public function __construct(
        StreamSelectLoop $loop,
        SocketServer $socketServer,
        array $config
    )
    {
        $this->loop = $loop;
        $this->socket = $socketServer;
        $this->config = $config;
    }

    public function onDispatch(MvcEvent $e)
    {

        $this->socket->on('connection', function (ConnectionInterface $conn) {
            $conn->on('data', function ($dataRaw) use ($conn) {
                $dataRaw = trim($dataRaw);

                $data = Json::decode($dataRaw, Json::TYPE_ARRAY);

                if (!isset($this->config[$data['queueName']])) {
                    echo "Bad queue name: " . $data['queueName'];
                    return;
                }

                $queueName = $data['queueName'];

                $this->checkWorkersCount($queueName);

                $process = $this->handleData($data, $dataRaw, $this->getQueueConfig($queueName), $conn);

                if (!$process) {
                    return;
                }

                $this->processes[$queueName][] = $process;
            });
        });

        $this->loop->addPeriodicTimer(1, function($timer) {
            $processes = [];

            foreach ($this->processes as $queueName=>$queue) {
                /** @var Process $process */
                foreach ($queue as $process) {
                    if ($process->isRunning()) {
                        $processes[$queueName][] = $process;
                    } else {
                        $this->printProcessOutput($queueName, $process);
                    }
                }
            }

            $this->processes = $processes;
        });

        $this->socket->listen(4000);
        $this->loop->run();
    }

    private function printProcessOutput($queueName, Process $process)
    {
        $output = $process->getOutput();

        if (!empty($output)) {
            $this->debug("process output: ", $this->getQueueConfig($queueName));
            $this->debug($output, $this->getQueueConfig($queueName), '');
        }

        $output = $process->getErrorOutput();

        if (!empty($output)) {
            $this->debug("process error: " , ['debug-enable' => 1]);
            $this->debug($output, ['debug-enable' => 1], '');
        }
    }

    private function getQueueConfig($queueName)
    {
        if (isset($this->queueConfigs[$queueName])) {
            return $this->queueConfigs[$queueName];
        }

        $this->queueConfigs[$queueName] = array_merge(
            [
                'worker-count' => 1,
                'timeout' => 300,
                'debug-enable' => 0,
            ],
            $this->config[$queueName]
        );

        return $this->queueConfigs[$queueName];
    }

    private function checkWorkersCount($queueName)
    {
        $queueConfig = $this->getQueueConfig($queueName);

        while (isset($this->processes[$queueName]) && count($this->processes[$queueName]) >= $queueConfig['worker-count']) {
            // wait 50 ms
            usleep(50000);

            $processes = [];

            foreach ($this->processes as $queueName=>$queue) {
                /** @var Process $process */
                foreach ($queue as $process) {
                    if ($process->isRunning()) {
                        $processes[$queueName][] = $process;
                    } else {
                        $this->printProcessOutput($queueName, $process);
                    }
                }
            }

            $this->processes = $processes;
        }
    }

    /**
     * @param array $data
     * @param $dataRaw
     * @param array $queueConfig
     * @param ConnectionInterface $connection
     * @return Process|void
     */
    public function handleData(array $data, $dataRaw, array $queueConfig, ConnectionInterface $connection)
    {
        $this->debug("Queue config: " . Json::encode($queueConfig), $queueConfig);
        $this->debug("Income data: " . $dataRaw, $queueConfig);

        if (!isset($data['queueName'])) {
            echo "Bad data[queueName]: " . $dataRaw . PHP_EOL;
            return;
        }

        if (!isset($data['messageId'])) {
            echo "Bad data[messageId]: " . $dataRaw . PHP_EOL;
            return;
        }

        $process = new Process($this->getCommand($data, $queueConfig));

        if (isset($queueConfig['timeout'])) {
            $process->setTimeout($queueConfig['timeout']);
        }

        try {
            $process->start();
        } catch (ProcessTimedOutException $e) {
            echo "Stopped by timeout" . PHP_EOL;
        }

        $connection->close();

        return $process;
    }

    private function debug($msg, $queueConfig, $prefix = "**Debug: ")
    {
        if ($queueConfig['debug-enable']) {
            echo $prefix . $msg . PHP_EOL;
        }
    }

    private function getCommand($data, $queueConfig)
    {
        $consumer = 'php public/index.php queue worker %s %s';

        $command = sprintf($consumer, $data['queueName'], $data['messageId']);

        $this->debug("Command: " . $command, $queueConfig);

        return sprintf($consumer, $data['queueName'], $data['messageId']);
    }
}
