<?php
/**
 * PHP version 7.0.*
 *
 * Module Andrew\CustomCatalog\Block
 *
 * Container.
 *
 * @category Andrew\CustomCatalog
 * @package  Andrew\CustomCatalog\Model
 * @author   Andrew Izototv <andrew.izotov@yahoo.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     https://github.com/andrewizotov/custom-catalog-m2-extension.git
 */

namespace Andrew\CustomCatalog\Block\Adminhtml;

class Product extends \Magento\Backend\Block\Widget\Container
{
    /**
     * @var \Magento\Catalog\Model\Product\TypeFactory
     */
    protected $typeFactory;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $productFactory;

    /**
     * @param \Magento\Backend\Block\Widget\Context      $context
     * @param \Magento\Catalog\Model\Product\TypeFactory $typeFactory
     * @param \Magento\Catalog\Model\ProductFactory      $productFactory
     * @param array                                      $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Catalog\Model\Product\TypeFactory $typeFactory,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        array $data = []
    ) {

        $this->productFactory = $productFactory;
        $this->typeFactory = $typeFactory;

        parent::__construct($context, $data);
    }

    /**
     * Prepare button and grid
     *
     * @return \Magento\Catalog\Block\Adminhtml\Product
     */
    protected function _prepareLayout()
    {
        $addButtonProps = [
            'id' => 'add_new_product',
            'label' => __('Add Product'),
            'class' => 'add',
            'button_class' => '',
            'class_name' => \Magento\Backend\Block\Widget\Button\SplitButton::class,
            'options' => $this->_getAddProductButtonOptions(),
        ];
        $this->buttonList->add('add_new', $addButtonProps);

        return parent::_prepareLayout();
    }

    /**
     * Retrieve options for 'Add Product' split button
     *
     * @return array
     */
    protected function _getAddProductButtonOptions()
    {
        $splitButtonOptions = [];
        $types = $this->typeFactory->create()->getTypes();
        uasort(
            $types,
            function ($elementOne, $elementTwo) {
                return ($elementOne['sort_order'] < $elementTwo['sort_order']) ? -1 : 1;
            }
        );

        foreach ($types as $typeId => $type) {
            $splitButtonOptions[$typeId] = [
                'label' => __($type['label']),
                'onclick' => "setLocation('" . $this->_getProductCreateUrl($typeId) . "')",
                'default' => \Magento\Catalog\Model\Product\Type::DEFAULT_TYPE == $typeId,
            ];
        }

        return $splitButtonOptions;
    }

    /**
     * Retrieve product create url by specified product type
     *
     * @param  string $type
     * @return string
     */
    protected function _getProductCreateUrl($type)
    {
        return $this->getUrl(
            'catalog/product/new',
            ['set' => $this->productFactory->create()->getDefaultAttributeSetId(), 'type' => $type]
        );
    }

    /**
     * Check whether it is single store mode
     *
     * @return bool
     */
    public function isSingleStoreMode()
    {
        return $this->_storeManager->isSingleStoreMode();
    }
}
