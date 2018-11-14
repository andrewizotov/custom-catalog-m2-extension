<?php

namespace Andrew\CustomCatalog\Model\Api;

use \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;

class Product implements \Andrew\CustomCatalog\Api\ProductInterface
{
    /**
     * @var \Magento\Framework\App\ObjectManager
     */
    protected $objectManager;

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
     * ShippingWeb constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Catalog\Model\ProductRepository $productRepository
     * @param \Magento\Eav\Model\AttributeRepository $attributeRepository
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\Eav\Model\AttributeRepository $attributeRepository,
        CollectionFactory $productColletionFactory
    ) {
        $this->objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->coreRegistry = $registry;
        $this->productRepository = $productRepository;
        $this->attributeRepository = $attributeRepository;
        $this->productColletionFactory = $productColletionFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function getByVpn(string $vpn)
    {
        /** @var $products \Magento\Catalog\Model\ResourceModel\Product\Collection */
        $products = $this->productColletionFactory->create();
        $products->addAttributeToFilter('vpn', ['like' => "%$vpn%"]);

        return $products->toArray();
    }

    /**
     * {@inheritdoc}
     */
    public function update(\Andrew\CustomCatalog\Api\Data\RequestProductInterface $BODY)
    {
        if($BODY->getEntityId()) {
            try {
                $product = $this->productRepository->getById($BODY->getEntityId());
            }catch (\Magento\Framework\Exception\NoSuchEntityException $e){
                $product = null;
            }

            if($product) {
                $product->setVpn($BODY->getEntityId());
                $product->setCopyWriteInfo($BODY->getCopyWriteInfo());
                $this->productRepository->save($product);
            }
        }

        return $this;
    }
}