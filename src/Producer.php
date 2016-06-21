<?php
namespace T4web\Queue;

use Zend\Json\Json;
use T4webDomainInterface\Infrastructure\RepositoryInterface;
use T4web\Queue\Domain\QueueMessage\QueueMessage;

/**
 * Produces messages and sends them to the queue.
 *
 * This class is absolutley not required to write messages to the queue,
 * the following snippet would do the same thing:
 *
 *     $fp = stream_socket_client('tcp://localhost:4000');
 *     fwrite($fp, json_encode(array('queue'=>'default','message'=>'bazbazbaz')));
 *     fclose($fp);
 *
 * However, Producer makes it a little bit easier:
 *
 *     $producer = $serviceLocator()->get(\T4web\Queue\Producer::class);
 *     $producer->produce('default', 'Hello World');
 */
class Producer
{
    /**
     * @var RepositoryInterface
     */
    private $messageRepository;

    /**
     * @var array
     */
    private $config;

    /**
     * Constructor.
     * @param RepositoryInterface $messageRepository
     * @param array $config
     */
    public function __construct(
        RepositoryInterface $messageRepository,
        array $config
    )
    {
        $this->messageRepository = $messageRepository;
        $this->config = $config;
    }

    /**
     * Produces a new message.
     *
     * @param string $queueName The queue name
     * @param array $message The message
     *
     * @return void
     *
     * @codeCoverageIgnore
     */
    public function produce($queueName, array $message)
    {
        if (!isset($this->config['queues'][$queueName])) {
            throw new Exception\QueueDoesNotExistsException("Queue $queueName does not exists.");
        }

        $queueMessage = new QueueMessage([
            'queueName' => $queueName,
            'message' => Json::encode($message),
            'status' => QueueMessage::STATUS_NEW,
            'options' => Json::encode($this->config['queues'][$queueName]),
        ]);
        
        $this->messageRepository->add($queueMessage);

        if ($this->config['realtime-server']['enabled']) {
            $fp = @stream_socket_client(
                sprintf('tcp://%s:%d', $this->config['realtime-server']['hostname'], $this->config['realtime-server']['port']),
                $errno,
                $errstr,
                30
            );
            if (!$fp) {
                throw new \RuntimeException(sprintf('%s (%s)', $errstr, $errno));
            }

            fwrite($fp, Json::encode(array(
                'queueName' => $queueName,
                'messageId' => $queueMessage->getId(),
            )));

            fclose($fp);
        }
    }
}