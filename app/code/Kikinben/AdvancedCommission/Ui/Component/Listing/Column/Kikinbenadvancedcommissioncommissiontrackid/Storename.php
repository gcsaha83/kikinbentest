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
namespace Kikinben\AdvancedCommission\Ui\Component\Listing\Column\Kikinbenadvancedcommissioncommissiontrackid;
use Magento\Framework\Escaper;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Customer\Model\Customer;


class Storename extends Column
{
	protected $escaper;
	protected $_customer;
	public function __construct(
		ContextInterface $context,
		UiComponentFactory $uiComponentFactory,
		Escaper $escaper,
		Customer $customer,
		\Apptha\Marketplace\Model\SellerFactory $seller,
		array $components = [],
		array $data = []
			
			){
		  $this->escaper = $escaper;
		  $this->_customer = $customer;
		  $this->_seller  = $seller->create();
		  parent::__construct($context, $uiComponentFactory, $components, $data);
		
	}
	
	/**
	 * Prepare Data Source
	 *
	 * @param array $dataSource
	 * @return array
	 */
	public function prepareDataSource(array $dataSource)
	{
		if (isset($dataSource['data']['items'])) {
			foreach ($dataSource['data']['items'] as & $item) {
				$item[$this->getData('name')] = $this->prepareItem($item);
			}
		}
	
		return $dataSource;
	}
	protected function prepareItem(array $item)
	{		
		$customer_id =  $item['seller_id'];
		$sellerCollection = $this->_seller->getCollection();
		$data = $sellerCollection->addFieldToFilter('customer_id',array('eq'=>$customer_id))->getData();		
		return $data[0]['store_name'];
		
	}

	
}