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
namespace Kikinben\AdvancedCommission\Ui\Component\Listing\Column;
use Magento\Framework\Escaper;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Customer\Model\Customer;

class Sellername extends Column
{
	protected $escaper;
	protected $_customer;
	public function __construct(
		ContextInterface $context,
		UiComponentFactory $uiComponentFactory,
		Escaper $escaper,
		Customer $customer,
		array $components = [],
		array $data = []
			
			){
		  $this->escaper = $escaper;
		  $this->_customer = $customer;
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
		$customer_id = $item['customer_id'];
		$customerDetails = $this->_customer->load ($customer_id);
		$name = $customerDetails->getFirstname ();
		$sellerUrl = $this->getUrl ( 'customer/index/edit/id/' . $customer_id );
		$sellerDetails = '<a  href="' . $sellerUrl . '" alt= "' . $name . '">' . $name . '</a>';
		return $sellerDetails;
		
	}

	
}
