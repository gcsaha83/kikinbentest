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
namespace Kikinben\AdvancedCommission\Block\Adminhtml\SellerProductCommission\Edit;

use Magento\Backend\Block\Widget\Tabs as WidgetTabs;

class Tabs extends WidgetTabs {
	
	
	
	protected function _beforeToHtml() {
		$product_id = $this->getRequest()->getParam('id');
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$productCollection = $objectManager->create('Magento\Catalog\Model\Product');
		$product = $productCollection->load($product_id);
		
		$this->addTab ( 'seller_products', [
				'label' => __ ( $product->getName() ),
				'title' => __ ( $product->getName() ),
				'content' => $this->getLayout()->createBlock(
						//'Kikinben\AdvancedCommission\Block\Adminhtml\SellerProductCommission\Edit\Form',
						'Kikinben\AdvancedCommission\Block\Adminhtml\SellerProductCommission\Edit\Tab\Main',
						"seller_products",
						['data' => ['customer_id' => $product_id]
                    ])->toHtml(),			
				'active' => true
		] );
		return parent::_beforeToHtml ();
	}
}
