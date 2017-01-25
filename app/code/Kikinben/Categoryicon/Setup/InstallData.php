<?php
namespace Kikinben\Categoryicon\Setup;

use Magento\Catalog\Setup\CategorySetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;


class InstallData implements \Magento\Framework\Setup\InstallDataInterface
{
    private $categorySetupFactory;

    public function __construct(CategorySetupFactory $categorySetupFactory)
    {
        $this->categorySetupFactory = $categorySetupFactory;
    }


    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $categorySetup = $this->categorySetupFactory->create(['setup' => $setup]);
        $categorySetup ->addAttribute(
                \Magento\Catalog\Model\Category::ENTITY,
                'kinkinbin_icon_thumb', [
                    'type'      	=> 'varchar',
                    'label'      	=> 'Image - icon',
                    'input'     	=> 'image',
                    'required' 	=> false,
                    'sort_order'  => 6,
                    'backend'	=> 'Kikinben\Categoryicon\Model\Category\Attribute\Backend\Thumb',
                    'global'    	=> \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                    'group'    	=> 'General Information',
                ]
        	);

    }
}
