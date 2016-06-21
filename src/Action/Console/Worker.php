<?php
namespace T4web\Queue\Action\Console;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Zend\Console\Request as ConsoleRequest;
use T4webDomainInterface\Infrastructure\RepositoryInterface;
use T4web\Queue\Worker as WorkerInterface;
use T4web\Queue\Domain\QueueMessage\QueueMessage;

class Worker extends AbstractActionController
{
    /**
     * @var array
     */
    private $config;

    /**
     * @var RepositoryInterface
     */
    private $messageRepository;

    /**
     * @var array
     */
    private $queueConfig;

    public function __construct(
        array $config,
        RepositoryInterface $messageRepository
    )
    {
        $this->config = $config;
        $this->messageRepository = $messageRepository;
    }

    public function onDispatch(MvcEvent $e)
    {
        /** @var ConsoleRequest $request */
        $request = $this->getRequest();

        $queueName = $request->getParam('queueName');
        $messageId = $request->getParam('messageId');

        if (!isset($this->config[$queueName])) {
            echo "Bad queue name: " . $queueName;
            return;
        }

        $this->queueConfig = $this->config[$queueName];

        $this->debug("Queue name: " . $queueName);
        $this->debug("Message Id: " . $messageId);

        /** @var QueueMessage $message */
        $message = $this->messageRepository->findById($messageId);

        /** @var WorkerInterface $handler */
        $handler = $this->getServiceLocator()->get($this->queueConfig['handler']);

        $message->setProcessed();
        $this->messageRepository->add($message);

        try {
            $handler->handle($message->getMessage());
        } catch (\Exception $e) {
            $message->setFailed($e->getMessage());
            $this->messageRepository->add($message);
        }

        $message->setCompleted();
        $this->messageRepository->add($message);

        $this->debug("done");
    }

    private function debug($msg)
    {
        if ($this->queueConfig['debug-enable']) {
            echo "  **Debug-Worker: " . $msg . PHP_EOL;
        }
    }
}