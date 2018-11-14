<?php
namespace Andrew\CustomCatalog\Setup;

use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{
    /**
     * EAV setup factory
     *
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * Init
     *
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(EavSetupFactory $eavSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        /** @var \Magento\Eav\Setup\EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        if (version_compare($context->getVersion(), '0.0.1') < 0) {

            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'copy_write_info',
                [
                    'type' => 'varchar',
                    'label' => 'Copy Write Info',
                    'input' => 'text',
                    'required' => false,
                    'sort_order' => 20,
                    'user_defined' => true,
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                    'group' => 'Andrew Custom Catalog',
                ]
            );

            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'vpn',
                [
                    'type' => 'varchar',
                    'label' => 'VPN',
                    'input' => 'text',
                    'required' => false,
                    'sort_order' => 25,
                    'user_defined' => true,
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                    'group' => 'Andrew Custom Catalog',
                ]
            );

        }

        $setup->endSetup();
    }
}