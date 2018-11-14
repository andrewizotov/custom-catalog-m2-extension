<?php

namespace Andrew\CustomCatalog\Block\Adminhtml;

class Product extends \Magento\Backend\Block\Widget\Grid\Container
{
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context, array $data = []
    ) {
        $this->_blockGroup = 'Andrew_CustomCatalog';
        $this->_controller = 'adminhtml_product';
        parent::__construct($context, $data);
    }

    public function getGridHtml()
    {
        return $this->getChildHtml('grid');
    }
}
