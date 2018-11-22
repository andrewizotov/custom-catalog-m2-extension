<?php
/**
 * PHP version 7.0.*
 *
 * Module Andrew\CustomCatalog\Model\Api
 *
 * Base logic for accept api requests.
 *
 * @category  Andrew\CustomCatalog
 * @package   Andrew\CustomCatalog\Model
 * @author    Andrew Izototv <andrew.izotov@yahoo.com>
 * @license   http://opensource.org/licenses/MIT MIT
 * @link      https://github.com/andrewizotov/custom-catalog-m2-extension.git
 */

namespace Andrew\CustomCatalog\Model\Api;

class Product implements \Andrew\CustomCatalog\Api\ProductInterface
{
    /**
     * Object manager
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry;

    /**
     * @var \Magento\Catalog\Model\ProductRepository
     */
    protected $productRepository;

    /**
     * @var \Magento\Eav\Model\AttributeRepository
     */
    protected $attributeRepository;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $productColletionFactory;

    /**
     * Escaper
     *
     * @var \Magento\Framework\Escaper
     */
    protected $escaper;

    /**
     * @var \Andrew\CustomCatalog\Model\Queue\Rabbitmq;
     */
    protected $queue;

    /**
     * ShippingWeb constructor.
     *
     * @param \Magento\Framework\App\Action\Context                          $context
     * @param \Magento\Framework\ObjectManagerInterface                      $productRepository
     * @param \Andrew\CustomCatalog\Model\Queue\Repository\MessageRepository $attributeRepository
     * @param \Magento\Framework\Escaper                                     $escaper
     */
    public function __construct(
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\Eav\Model\AttributeRepository $attributeRepository,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productColletionFactory,
        \Andrew\CustomCatalog\Model\Queue\Rabbitmq $queue,
        \Magento\Framework\Escaper $escaper
    ) {
        $this->objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->productRepository = $productRepository;
        $this->attributeRepository = $attributeRepository;
        $this->productColletionFactory = $productColletionFactory;
        $this->queue = $queue;
        $this->escaper = $escaper;
    }

    /**
     * {@inheritdoc}
     */
    public function getByVpn(string $vpn)
    {
        $products = $this->productColletionFactory->create();
        $products->addAttributeToFilter('vpn', ['like' => "%$vpn%"]);

        return $products->toArray();
    }

    /**
     * {@inheritdoc}
     */
    public function update(\Andrew\CustomCatalog\Api\Data\RequestProductInterface $BODY)
    {
        if ($BODY->getEntityId()) {
            try {
                $product = $this->productRepository->getById($BODY->getEntityId());
            } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
                $product = null;
            }

            if ($product) {
                $productId = $product->getId();
                $productVpn = $this->escaper->escapeHtml($BODY->getVpn());
                $productCopyWriteInfo = $this->escaper->escapeHtml($BODY->getCopyWriteInfo());
                /* @var \Andrew\CustomCatalog\Model\Queue\Message $message */
                $message = $this->objectManager->get(\Andrew\CustomCatalog\Model\Queue\MessageInterface::class);
                $message->setContent(
                    [
                        'entity_id' => $productId,
                        'vpn' => $productVpn,
                        'copy_write_info' => $productCopyWriteInfo
                    ]
                );
                $this->queue->addMessage($message);
            }
        }

        return $this;
    }
}
