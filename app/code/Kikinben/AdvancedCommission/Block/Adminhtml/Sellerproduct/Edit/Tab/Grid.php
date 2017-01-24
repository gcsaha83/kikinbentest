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

namespace Kikinben\AdvancedCommission\Block\Adminhtml\Sellerproduct\Edit\Tab;

use Apptha\Marketplace\Model\Seller AS SellerCollectionFactory;
use Magento\Customer\Model\Customer AS Customer;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended {
	
	protected $productFactory;
	protected $_sellerCollectionFactory;
	protected $_customer;
	
	public function __construct(
			\Magento\Backend\Block\Template\Context $context,
			\Magento\Backend\Helper\Data $backendHelper,
			\Magento\Catalog\Model\ProductFactory $productFactory,
			\Magento\Framework\Registry $coreRegistry,
			SellerCollectionFactory $sellerCollectionFactory,
			Customer $customer,
			array $data = []			
			
			) {
				$this->productFactory = $productFactory;
				$this->_coreRegistry = $coreRegistry;
				$this->_sellerCollectionFactory =  $sellerCollectionFactory;
				$this->_customer = $customer;
				parent::__construct($context, $backendHelper, $data);
	}
	
	protected function _construct()
	{
		parent::_construct();
		$this->setId('hello_tab_grid');
		$this->setDefaultSort('entity_id');
		$this->setUseAjax(true);
	}
	
	protected function _preparePage()
	{
		$this->getCollection()->setPageSize(5)->setCurPage(1);
	}
	
	protected function _prepareCollection()
	{
		$customerId = $this->_sellerCollectionFactory->load($this->getData('customer_id'))->getCustomerId();
		$collection = $this->productFactory->create()->getCollection()->addAttributeToSelect("*");
		$collection->addAttributeToFilter ( 'seller_id', $customerId );
		$this->setCollection($collection);
		return parent::_prepareCollection();
	}
	protected function _prepareColumns()
	{
		$this->addColumn(
				'entity_id',
				[
						'header' => __('Product Id'),
						'sortable' => true,
						'index' => 'entity_id',
						'header_css_class' => 'col-id',
						'column_css_class' => 'col-id'
				]
				);
		$this->addColumn(
				'name',
				[
						'header' => __('Product Name'),
						'index' => 'name'
				]
				);
		$this->addColumn(
				'sku',
				[
						'header' => __('Sku'),
						'index' => 'sku'
				]
				);
	
		return parent::_prepareColumns();
	}
	public function getGridUrl()
	{
		return $this->getUrl('sellerproduct/*/index', ['_current' => true]);
	}
	public function getRowUrl($row)
	{
		return $this->getUrl('kikinben_advancedcommission/sellerproduct/edit', ['id' => $row->getId()]);
	}
	
}