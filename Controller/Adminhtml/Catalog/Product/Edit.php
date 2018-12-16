<?php
/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Andrew\CustomCatalog\Controller\Adminhtml\Catalog\Product;

class Edit extends \Magento\Catalog\Controller\Adminhtml\Product\Edit
{
    public function execute()
    {
        $resultPage = parent::execute();

        if (!$this->_objectManager->get(\Magento\Store\Model\StoreManagerInterface::class)->isSingleStoreMode()
            &&
            ($switchBlock = $resultPage->getLayout()->getBlock('store_switcher'))
        ) {
            $switchBlock->setDefaultStoreName(__('Default Values'))
                ->setSwitchUrl(
                    $this->getUrl(
                        '*/*/*',
                        ['_current' => true, 'active_tab' => null, 'tab' => null, 'store' => null]
                    )
                );
        }

        return $resultPage;
    }
}
