<?php
namespace Andrew\CustomCatalog\Model\Queue;

use \PhpAmqpLib\Connection\AMQPStreamConnection;
use \PhpAmqpLib\Message\AMQPMessage;

class Rabbitmq implements RabbitmqInterface
{
    protected $queueAdapter;

    public function __construct(
        \Andrew\CustomCatalog\Model\Queue\Adapter\QueueAdapterInterface $adapter
    ) {
        $this->queueAdapter = $adapter;
    }

    public function addMessage(\Andrew\CustomCatalog\Model\Queue\MessageInterface $message)
    {
        $this->queueAdapter->addMessageToQueue($message);
    }
}
