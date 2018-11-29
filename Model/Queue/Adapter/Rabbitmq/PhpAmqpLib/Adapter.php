<?php
/**
 * PHP version 7.0.*
 *
 * Adapter for PhpAmqpLib
 *
 * Wrapper PhpAmqpLib
 *
 * @category  Andrew\CustomCatalog\Model\Queue\Adapter\Rabbitmq\PhpAmqpLib
 * @package   Andrew\CustomCatalog\Model
 * @author    Andrew Izototv <andrew.izotov@yahoo.com>
 * @license   http://opensource.org/licenses/MIT MIT
 * @link      https://github.com/andrewizotov/custom-catalog-m2-extension.git
 */
namespace Andrew\CustomCatalog\Model\Queue\Adapter\Rabbitmq\PhpAmqpLib;

use \Andrew\CustomCatalog\Model\Queue\Adapter\QueueAdapterInterface;
use Andrew\CustomCatalog\Model\Queue\RabbitmqInterface;
use \Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\ObjectManager\ObjectManager;
use \PhpAmqpLib\Connection\AMQPStreamConnection;

class Adapter implements \Andrew\CustomCatalog\Model\Queue\Adapter\QueueAdapterInterface
{

    const VIRTUAL_HOST = 'queue/amqp/virtualhost';

    /**
     * @var \PhpAmqpLib\Connection\AMQPStreamConnection
     */
    private $connection;

    /**
     * @var \PhpAmqpLib\Channel\AMQPChannel
     */
    private $channel;

    /**
     * @var string
     */
    protected $chanelName;

    /**
     * @var \Magento\Framework\App\DeploymentConfig
     */
    protected $config;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * Adapter constructor.
     * @param \Magento\Framework\App\DeploymentConfig $config
     * @param ProductRepositoryInterface $productRepository
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        \Magento\Framework\App\DeploymentConfig $config,
        ProductRepositoryInterface $productRepository,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->config = $config;
        $this->logger = $logger;
        $this->productRepository = $productRepository;
    }

    /**
     * @param  string $name
     * @return $this|mixed
     */
    public function createConnection()
    {
        if (!$this->connection) {
            $this->connection = new AMQPStreamConnection(
                $this->config->get(QueueAdapterInterface::QUEUE_HOST),
                $this->config->get(QueueAdapterInterface::QUEUE_PORT),
                $this->config->get(QueueAdapterInterface::QUEUE_USER),
                $this->config->get(QueueAdapterInterface::QUEUE_PASSWORD),
                $this->config->get(self::VIRTUAL_HOST)
            );
        }
        return $this;
    }

    /**
     * @return $this
     */
    protected function declareQueue()
    {
        if (!$this->channel) {
            $this->channel = $this->getConnection()->channel();
            $this->channel->queue_declare(RabbitmqInterface::QUEUE_NAME, false, false, false, false);
        }
        return $this;
    }

    /**
     * @param \Andrew\CustomCatalog\Model\Queue\MessageInterface $message
     * @param string $nameOfQueue
     * @return mixed|void
     */
    public function addMessageToQueue(
        \Andrew\CustomCatalog\Model\Queue\MessageInterface $message,
        string $nameOfQueue = ''
    ) {
        try {
            $this->declareQueue();
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $amqpMessage = $objectManager->get(\PhpAmqpLib\Message\AMQPMessage::class);
            $amqpMessage->setBody($message->getContent());
            $this->channel->basic_publish($amqpMessage, '', RabbitmqInterface::QUEUE_NAME);
            $this->channel->close();
            $this->connection->close();
        } catch (\AMQPConnectionClosedException $exception) {
            $this->logger->error(
                $exception->getMessage()
            );
        }
    }

    /**
     * @return AMQPStreamConnection
     */
    public function getConnection()
    {
        if (!$this->connection) {
            $this->createConnection();
        }

        return $this->connection;
    }

    /**
     * @param string $nameOfQueue
     * @param null $callback
     * @throws \ErrorException
     */
    public function receiveCallback(string $nameOfQueue = '', $callback = null)
    {
        try {
            $channel = $this->getConnection()->channel();
            $this->declareQueue();
            echo " [*] Waiting for messages. To exit press CTRL+C\n";
            $channel->basic_consume(RabbitmqInterface::QUEUE_NAME, '', false, true, false, false, $callback);
            while (count($channel->callbacks)) {
                $channel->wait();
            }
            $channel->close();
            $connection->close();
        } catch (\AMQPConnectionClosedException $exception) {
            $this->logger->error(
                $exception->getMessage()
            );
        }
    }
}
