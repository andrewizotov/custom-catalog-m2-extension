<?php
/**
 * PHP version 7.0.*
 *
 * Module Andrew\CustomCatalog\Model\Api
 *
 * Message object
 *
 * @category  Andrew\CustomCatalog
 * @package   Andrew\CustomCatalog\Model
 * @author    Andrew Izototv <andrew.izotov@yahoo.com>
 * @license   http://opensource.org/licenses/MIT MIT
 * @link      https://github.com/andrewizotov/custom-catalog-m2-extension.git
 */

namespace Andrew\CustomCatalog\Model\Queue;

use Magento\Framework\ObjectManagerInterface;
use \PhpAmqpLib\Connection\AMQPStreamConnection;
use \PhpAmqpLib\Message\AMQPMessage;

class Message implements MessageInterface
{
    /**
     * @var \PhpAmqpLib\Message\AMQPMessage
     */
    protected $amqpMessage;

    /**
     * @var string
     */
    protected $content;

    /**
     * @param array $message
     */
    public function setContent(array $message)
    {
        $this->content = json_encode($message);
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }
}
