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
namespace Kikinben\AdvancedCommission\Block\Adminhtml;
class Edit extends \Magento\Backend\Block\Template
{
	public function __construct(
			\Magento\Backend\Block\Template\Context $context,			
			array $data = []
			) {				
				parent::__construct($context, $data);
	}
    function _prepareLayout(){}
    public function getForm()
    {
    	$customer_id = $this->getRequest()->getParam('id');    	
    	return $this->getLayout()->createBlock(
    			'Kikinben\AdvancedCommission\Block\Adminhtml\Edit\Form',
    			"block_name",
    			['data' => ['customer_id' => $customer_id]
        ])->toHtml();
    }
}
