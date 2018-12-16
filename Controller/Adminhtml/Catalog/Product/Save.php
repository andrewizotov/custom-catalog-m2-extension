<?php
/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Andrew\CustomCatalog\Controller\Adminhtml\Catalog\Product;

use Magento\Backend\App\Action;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Controller\Adminhtml\Product;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Request\DataPersistorInterface;

/**
 * Class Save
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Save extends \Magento\Catalog\Controller\Adminhtml\Product
{
    /**
     * @var \Magento\Catalog\Api\CategoryLinkManagementInterface
     */
    protected $categoryLinkManagement;

    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * Save constructor.
     *
     * @param Action\Context $context
     * @param Builder $productBuilder
     * @param Initialization\Helper $initializationHelper
     * @param \Magento\Catalog\Model\Product\Copier $productCopier
     * @param \Magento\Catalog\Model\Product\TypeTransitionManager $productTypeManager
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        Product\Builder $productBuilder,
        \Magento\Catalog\Model\Product\Copier $productCopier,
        \Magento\Catalog\Model\Product\TypeTransitionManager $productTypeManager,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
    ) {
        $this->productTypeManager = $productTypeManager;
        $this->productRepository = $productRepository;
        parent::__construct($context, $productBuilder);
    }

    /**
     * Save product action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        $storeId = $this->getRequest()->getParam('store', 0);
        $store = $this->getStoreManager()->getStore($storeId);
        $this->getStoreManager()->setCurrentStore($store->getCode());
        $redirectBack = $this->getRequest()->getParam('back', false);
        $productId = $this->getRequest()->getParam('id');
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();

        if ($data) {
            try {
                if (!isset($data['entity_id'])) {
                    throw new \Magento\Framework\Exception\LocalizedException(__('Unable to save product'));
                }

                $productId = (int) $data['entity_id'];

                $product = $this->productRepository->getById($productId, true, $storeId);

                $productId = $product->getEntityId();

                $product->setVpn($this->getRequest()->getParam('vpn'));
                $product->setCopyWriteInfo($this->getRequest()->getParam('copy_write_info'));

                $product->save();

                $this->messageManager->addSuccessMessage(__('You saved the product.'));
                $this->getDataPersistor()->clear('catalog_product');
                $redirectBack = true;
            } catch (\Exception $e) {
                $this->_objectManager->get(\Psr\Log\LoggerInterface::class)->critical($e);
                $this->messageManager->addErrorMessage($e->getMessage());
                $redirectBack = true;
            }
        } else {
            $resultRedirect->setPath('custom_catalog/catalog_product/*', ['store' => $storeId]);
            $this->messageManager->addErrorMessage('No data to save');
            return $resultRedirect;
        }

        if ($redirectBack) {
            $resultRedirect->setPath(
                'custom_catalog/catalog_product/edit',
                ['id' => $productId, '_current' => true]
            );
        } else {
            $resultRedirect->setPath('custom_catalog/catalog/index', ['store' => $storeId]);
        }

        return $resultRedirect;
    }

    /**
     * @return StoreManagerInterface
     * @deprecated 101.0.0
     */
    private function getStoreManager()
    {
        if (null === $this->storeManager) {
            $this->storeManager = \Magento\Framework\App\ObjectManager::getInstance()
                ->get(\Magento\Store\Model\StoreManagerInterface::class);
        }
        return $this->storeManager;
    }

    /**
     * Retrieve data persistor
     *
     * @return DataPersistorInterface|mixed
     * @deprecated 101.0.0
     */
    protected function getDataPersistor()
    {
        if (null === $this->dataPersistor) {
            $this->dataPersistor = $this->_objectManager->get(DataPersistorInterface::class);
        }

        return $this->dataPersistor;
    }
}
