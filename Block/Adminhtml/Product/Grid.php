<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Andrew\CustomCatalog\Block\Adminhtml\Product;

class Grid extends \Magento\Catalog\Block\Adminhtml\Product\Grid
{
    protected function _prepareColumns()
    {
        $this->addColumnAfter('vpn', [
            'header' => __('Vpn'),
            'index' => 'vpn',
        ], 'entity_id');

        $this->addColumnAfter('copy_write_info', [
            'header' => __('Copy Write Info'),
            'index' => 'copy_write_info',
        ], 'vpn');

        parent::_prepareColumns();

        $this->removeColumn('name');
        $this->removeColumn('type');
        $this->removeColumn('set_name');
        $this->removeColumn('price');
        $this->removeColumn('visibility');
        $this->removeColumn('status');
        $this->removeColumn('websites');
        $this->removeColumn('edit');
        if ($this->moduleManager->isEnabled('Magento_CatalogInventory')) {
            $this->removeColumn('qty');
        }

        return $this;
    }

    protected function _prepareCollection()
    {
        $collection = $this->_productFactory->create()->getCollection()->addAttributeToSelect(
            'sku'
        )->addAttributeToSelect(
            'vpn'
        )->addAttributeToSelect(
            'copy_write_info'
        );
        $this->setCollection($collection);

        \Magento\Backend\Block\Widget\Grid\Extended::_prepareCollection();

        return $this;
    }

    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('custom_catalog/*/grid', ['_current' => true]);
    }

    /**
     * @param \Magento\Catalog\Model\Product|\Magento\Framework\DataObject $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl(
            'catalog/product/edit',
            ['store' => $this->getRequest()->getParam('store'), 'id' => $row->getId()]
        );
    }
}
