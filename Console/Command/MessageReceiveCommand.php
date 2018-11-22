<?php

namespace Andrew\CustomCatalog\Console\Command;

use Andrew\CustomCatalog\Model\Queue\RabbitmqInterface;
use Magento\TestFramework\Event\Magento;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command for running receiver.
 */
class MessageReceiveCommand extends Command
{

    /**
     *
     * @var \Magento\Framework\App\State
     **/
    private $state;

    /**
     * @var \Andrew\CustomCatalog\Model\Queue\Adapter\Rabbitmq\PhpAmqpLib\Adapter
     */
    protected $adapter;

    /**
     * MessageReceiveСommand constructor.
     * @param \Andrew\CustomCatalog\Model\Queue\Adapter\Rabbitmq\PhpAmqpLib\Adapter $adapter
     * @param \Magento\Framework\App\State $state
     */
    public function __construct(
        \Andrew\CustomCatalog\Model\Queue\Adapter\Rabbitmq\PhpAmqpLib\Adapter $adapter,
        \Magento\Framework\App\State $state
    ) {
        $this->state = $state;
        $this->adapter = $adapter;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('run:receiver');
        $this->setDescription('receiver will start');
        parent::configure();
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->state->setAreaCode(\Magento\Framework\App\Area::AREA_FRONTEND);

        $this->adapter->receiveCallback(
            RabbitmqInterface::QUEUE_NAME,
            \Andrew\CustomCatalog\Model\Queue\ReceiveCallback::saveProduct()
        );
    }
}
