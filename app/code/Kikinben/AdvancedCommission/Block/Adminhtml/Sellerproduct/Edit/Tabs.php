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
 * */

namespace Kikinben\AdvancedCommission\Block\Adminhtml\Sellerproduct\Edit;
use Magento\Backend\Block\Widget\Tabs as WidgetTabs;

class Tabs extends WidgetTabs {
	/**
	 * Constructor for seller commission edit
	 *
	 * @return void
	 */
		
	
	protected function _beforeToHtml() {
		$customer_id = $this->getRequest()->getParam('id');
		
		$this->addTab ( 'Commission', [
				'label' => __ ( 'Seller\'s Commission' ),
				'title' => __ ( 'Seller\'s Commission' ),
				'content' => $this->getLayout()->createBlock(
						'Kikinben\AdvancedCommission\Block\Adminhtml\Edit\Form',
						"block_name",
						['data' => ['customer_id' => $customer_id]
						])->toHtml(),
				'active' => true
		] );
		
		$this->addTab ( 'Products', [
				'label' => __ ( 'Seller\'s Products' ),
				'title' => __ ( 'Seller\'s Products' ),
				'content' => $this->getLayout()->createBlock(
						'Kikinben\AdvancedCommission\Block\Adminhtml\Sellerproduct\Edit\Tab\Grid',
						"seller_products",
						['data' => ['customer_id' => $customer_id]
						])->toHtml(),
				
		] );
				
        $this->addTab ( 'Categories', [
				'label' => __ ( 'Seller\'s Categories' ),
				'title' => __ ( 'Seller\'s Categories' ),
				/*'content' => $this->getLayout()->createBlock(
						'Kikinben\AdvancedCommission\Block\Adminhtml\Sellerproduct\Edit\Tab\Grid',
						"seller_categories",
						['data' => ['customer_id' => $customer_id]
                    ])->toHtml(),*/
				
        ] );


	
		return parent::_beforeToHtml ();
	}
	
}
