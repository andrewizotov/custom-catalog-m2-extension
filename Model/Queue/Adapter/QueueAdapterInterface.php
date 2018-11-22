<?php

namespace Andrew\CustomCatalog\Model\Queue\Adapter;

interface QueueAdapterInterface
{

    const QUEUE_HOST = 'queue/amqp/host';
    const QUEUE_PORT = 'queue/amqp/port';
    const QUEUE_USER = 'queue/amqp/user';
    const QUEUE_PASSWORD = 'queue/amqp/password';

    /**
     * @param  \Andrew\CustomCatalog\Model\Queue\Message $message
     * @param  string|null                               $nameOfQueue
     * @return mixed
     */
    public function addMessageToQueue(
        \Andrew\CustomCatalog\Model\Queue\MessageInterface $message,
        string $nameOfQueue = ''
    );

    /**
     * @param  string $name
     * @return mixed
     */
    public function createConnection();

    public function getConnection();
}
