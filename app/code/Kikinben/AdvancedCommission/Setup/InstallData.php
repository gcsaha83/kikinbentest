<?php
/**
 * Apptha
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.apptha.com/LICENSE.txt
 *
 * ==============================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * ==============================================================
 * This package designed for Magento COMMUNITY edition
 * Apptha does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * Apptha does not provide extension support in case of
 * incorrect edition usage.
 * ==============================================================
 *
 * @category    Apptha
 * @package     Apptha_Marketplace
 * @version     1.1
 * @author      Apptha Team <developers@contus.in>
 * @copyright   Copyright (c) 2016 Apptha. (http://www.apptha.com)
 * @license     http://www.apptha.com/LICENSE.txt
 *
 */
namespace Kikinben\AdvancedCommission\Setup;

use Magento\Eav\Setup\EavSetupFactory;

use Magento\Catalog\Setup\CategorySetupFactory;


class InstallData implements \Magento\Framework\Setup\InstallDataInterface
{
	private $eavSetupFactory;
	private $categorySetupFactory;
	
	public function __construct(EavSetupFactory $eavSetupFactory,CategorySetupFactory $categorySetupFactory)
	{
		$this->eavSetupFactory = $eavSetupFactory;
		$this->categorySetupFactory = $categorySetupFactory;
	}
    public function install(\Magento\Framework\Setup\ModuleDataSetupInterface $setup, \Magento\Framework\Setup\ModuleContextInterface $context)
    {
    	$setup->startSetup();
    	
    	$eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
    	
    	/*$eavSetup->addAttribute(
    			\Magento\Catalog\Model\Category::ENTITY,
    			'kikinben_category_commission',
    			[
    					'type' => 'text',
    					'label' => 'Kikinben Category Advanced Commission',
    					'input' => 'text',
    					'required' => false,
    					'sort_order' => 4,
    					'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
    					'wysiwyg_enabled' => false,
    					'is_html_allowed_on_front' => true,
    					'group' => 'General Information',
    			]
    		);
    	
    	$eavSetup->addAttribute( \Magento\Catalog\Model\Category::ENTITY, 'kikinben_fulfilled', ['type' => 'int','backend' => '','frontend' => '','label' => 'Fullfiled by Kikinben','input' => 'select','class' => '',
    			'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean','group' => 'Marketplace Details','visible' => true,
    			'required' => false,'user_defined' => false,'default' => '','searchable' => false,'filterable' => false,
    			'comparable' => false,'visible_on_front' => false,'used_in_product_listing' => true,'apply_to' => ''] );
    	
    	$eavSetup->addAttribute ( \Magento\Catalog\Model\Category::ENTITY, 'kikinben_percentage_amount', ['type' => 'int','backend' => '','frontend' => '','label' => 'Kikinben Commission Type as Percentage','input' => 'select','class' => '',
    			'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean','group' => 'Marketplace Details','visible' => true,
    			'required' => false,'user_defined' => false,'default' => '','searchable' => false,'filterable' => false,
    			'comparable' => false,'visible_on_front' => false,'used_in_product_listing' => true,'apply_to' => ''] );
    	
    	$eavSetup->addAttribute ( \Magento\Catalog\Model\Category::ENTITY, 'kikinben_product_commission', ['type' =>'text','backend' => '','frontend' => '','label' => 'Kikinben Product Advanced Commission',
    			'input' => 'text','class' => '','source' => '','group' => 'Marketplace Details','visible' => true,'required' => false,'user_defined' => false,'default' => '',
    			'searchable' => false,'filterable' => false,'comparable' => false,'visible_on_front' => false,
    			'used_in_product_listing' => true,'unique' => false,'apply_to' => ''] );
    	
    	$categorySetup = $this->categorySetupFactory->create ( [
    			'setup' => $setup
    	] );*/
    	
    	/*$eavSetup->addAttribute(
    			\Magento\Catalog\Model\Category::ENTITY,
    			'kikinben_fulfilled',
    			[
    					'type' => 'int',
    					'label' => 'Fullfiled by Kikinben',
    					'input' => 'select',
    					'required' => false,
    	
    					'source' => 'Kikinben\AdvancedCommission\Model\Category\Attribute\Source\Customfulfilled',
    					'backend' => 'Kikinben\AdvancedCommission\Model\Category\Attribute\Backend\Customfulfilled',
    					'input_renderer' => 'Kikinben\AdvancedCommission\Helper\Category\Custom\Optionsfulfilled',
    	
    					'sort_order' => 100,
    					'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
    					'group' => 'General Information',
    					'is_used_in_grid' => true,
    					'is_visible_in_grid' => false,
    					'is_filterable_in_grid' => true,
    			]
    			);
    	
    	$eavSetup->addAttribute(
    			\Magento\Catalog\Model\Category::ENTITY,
    			'kikinben_percentage_amount',
    			[
    					'type' => 'int',
    					'label' => 'Kikinben Commission Type as Percentage',
    					'input' => 'select',
    					'required' => false,
    					 
    					'source' => 'Kikinben\AdvancedCommission\Model\Category\Attribute\Source\Customfulfilled',
    					'backend' => 'Kikinben\AdvancedCommission\Model\Category\Attribute\Source\Customfulfilled',
    					'input_renderer' => 'Kikinben\AdvancedCommission\Block\Adminhtml\Category\Helper\Custom\Optionsfulfilled',
    					 
    					'sort_order' => 100,
    					'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
    					'group' => 'General Information',
    					'is_used_in_grid' => true,
    					'is_visible_in_grid' => false,
    					'is_filterable_in_grid' => true,
    			]
    			);*/
    	
    	$eavSetup->addAttribute (
    			
    			\Magento\Catalog\Model\Category::ENTITY, 'kikinben_fulfilled', [
    					'type' => 'int',
    					'label' => 'Fullfiled by Kikinben',
    					'input' => 'select',
    					'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
    					'required' => false,
    					'sort_order' => 100,
    					'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
    					'group' => 'General Information',
    			]
    			
    			);
    	
    	$eavSetup->addAttribute (
    			 
    			\Magento\Catalog\Model\Category::ENTITY, 'kikinben_percentage_amount', [
    					'type' => 'int',
    					'label' => 'Kikinben Commission Type as Percentage',
    					'input' => 'select',
    					'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
    					'required' => false,
    					'sort_order' => 100,
    					'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
    					'group' => 'General Information',
    			]
    			 
    			);
    	
    	
    	$eavSetup->addAttribute ( \Magento\Catalog\Model\Category::ENTITY, 'kikinben_product_commission', ['type' =>'text','backend' => '','frontend' => '','label' => 'Kikinben Product Advanced Commission',
    			'input' => 'text','class' => '','source' => '','group' => 'Marketplace Details','visible' => true,'required' => false,'user_defined' => false,'default' => '',
    			'searchable' => false,'filterable' => false,'comparable' => false,'visible_on_front' => false,
    			'used_in_product_listing' => true,'unique' => false,'apply_to' => ''] );

    	
    	$categorySetup = $this->categorySetupFactory->create ( [
    			'setup' => $setup
    	] );

    	$categorySetup->addAttribute ( \Magento\Catalog\Model\Product::ENTITY, 'kikinben_fulfilled', ['type' => 'int','backend' => '','frontend' => '','label' => 'Fullfiled by Kikinben','input' => 'select','class' => '',
    			'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean','group' => 'Marketplace Details','visible' => true,
    			'required' => false,'user_defined' => false,'default' => '','searchable' => false,'filterable' => false,
    			'comparable' => false,'visible_on_front' => false,'used_in_product_listing' => true,'apply_to' => ''] );
    	
    	$categorySetup->addAttribute ( \Magento\Catalog\Model\Product::ENTITY, 'kikinben_percentage_amount', ['type' => 'int','backend' => '','frontend' => '','label' => 'Kikinben Commission Type as Percentage','input' => 'select','class' => '',
    			'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean','group' => 'Marketplace Details','visible' => true,
    			'required' => false,'user_defined' => false,'default' => '','searchable' => false,'filterable' => false,
    			'comparable' => false,'visible_on_front' => false,'used_in_product_listing' => true,'apply_to' => ''] );
    	
    	$categorySetup->addAttribute ( \Magento\Catalog\Model\Product::ENTITY, 'kikinben_product_commission', ['type' =>'text','backend' => '','frontend' => '','label' => 'Kikinben Product Advanced Commission',
    			'input' => 'text','class' => '','source' => '','group' => 'Marketplace Details','visible' => true,'required' => false,'user_defined' => false,'default' => '',
    			'searchable' => false,'filterable' => false,'comparable' => false,'visible_on_front' => false,
    			'used_in_product_listing' => true,'unique' => false,'apply_to' => ''] );
    	    	    
    	
    	$setup->endSetup();
    	
    	
        
    }
}
