<?php
namespace Andrew\CustomCatalog\Model\Queue;

use Andrew\CustomCatalog\Model\Queue\Adapter\QueueAdapterInterface;

interface RabbitmqInterface
{

    const QUEUE_NAME = 'update_product';

    public function __construct(QueueAdapterInterface $adapter);

    public function addMessage(\Andrew\CustomCatalog\Model\Queue\MessageInterface $message);
}
