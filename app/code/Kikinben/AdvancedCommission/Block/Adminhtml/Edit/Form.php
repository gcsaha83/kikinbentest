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

namespace Kikinben\AdvancedCommission\Block\Adminhtml\Edit;
use Kikinben\AdvancedCommission\Model\ResourceModel\Commission\CollectionFactory AS CommissionCollectionFactory;
use Apptha\Marketplace\Model\Seller AS SellerCollectionFactory;
use Magento\Customer\Model\Customer AS Customer;
use Kikinben\AdvancedCommission\Helper\KikinbenAdvancedCommissionAdminCategoryOptions AS HelperOptions;
use Magento\Backend\Block\Widget\Tab\TabInterface;

class Form extends \Magento\Backend\Block\Widget\Form\Generic implements TabInterface
{
	protected $_formFactory;
	protected $_commissionCollectionFactory;
	protected $_sellerCollectionFactory;
	protected $_customer;
	protected $_helperoptions;

	public function __construct(
			\Magento\Backend\Block\Template\Context $context,
			\Magento\Framework\Registry $registry,
			\Magento\Framework\Data\FormFactory $formFactory,
			CommissionCollectionFactory $commissionCollectionFactory,
			SellerCollectionFactory $sellerCollectionFactory,
			Customer $customer,
			HelperOptions $helperoptions,
			array $data = []
			) {
				$this->_formFactory =  $formFactory;
				$this->_commissionCollectionFactory =  $commissionCollectionFactory;
				$this->_sellerCollectionFactory =  $sellerCollectionFactory;
				$this->_customer = $customer;
				$this->_helperoptions = $helperoptions;


				parent::__construct($context, $registry, $formFactory, $data);
	}
    protected function _construct() {		
		parent::_construct ();
		$this->setId ( 'seller_edit_tabs' );
		$this->setDestElementId ( 'edit_form' );
		$this->setTitle ( __ ( 'Commission Information' ) );
	}

	protected function _prepareForm()
	{
		$customerId = $this->_sellerCollectionFactory->load($this->getData('customer_id'))->getCustomerId();
		$this->_helperoptions->getSellerId($customerId);
		$form = $this->_formFactory->create(
				['data' => ['id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post']]
				);
		$fieldset = $form->addFieldset(
				'base_fieldset',
				[ 'class' => 'fieldset-wide']
				);
		$fieldset->addField(
				'name',
				'label',
				[
						'name' => 'name',
						'label' => __('Seller Name'),
						'title' => __('Seller Name'),
						'value' => $this->_customer->load($customerId)->getName()

				]
				);

		/*$fieldset->addField('global_commission', 'checkbox', array(
				'label'     => '',
				'name'      => 'Checkbox',
				'checked' => false,
				'onclick' => "",
				'onchange' => "",
				'value'  => '1',
				'disabled' => false,
				'after_element_html' => 'Set Global Commission-Flat Commission rate for all products sold by this seller',
				'tabindex' => 1
            ));*/
		
		$fieldset->addField('price_range', 'checkbox', array(
				'label'     => '',
				'name'      => 'Checkbox',
				'checked' => false,
				'onclick' => "",
				'onchange' => "",
				'value'  => '1',
				'disabled' => false,
				'after_element_html' => 'Enable Price Range Commission Rule',
				'tabindex' => 1
		));

		$fieldset->addField('price_range_from', 'text', array(
				'label'     => 'Price Range From',
				'class'     => 'required-entry',
				'required'  => true,
				'name'      => 'title',
				'disabled' => false,
				'after_element_html' => '',
				'tabindex' => 1
		));

		$fieldset->addField('price_range_to', 'text', array(
				'label'     => 'Price Range To',
				'class'     => 'required-entry',
				'required'  => true,
				'name'      => 'title',
				'disabled' => false,
				'after_element_html' => '',
				'tabindex' => 1
		));

		$fieldset->addField('commission_type', 'select', array(
				'label'     => 'Commission Type',
				'class'     => 'required-entry',
				'required'  => true,
				'name'      => 'title',
				
				'value'  => '2',
				'values' => array('-1'=>'Please Select..','1' => 'Fixed','2' => 'Percentage'),
				
		));

		$fieldset->addField('amount', 'text', array(
				'label'     => 'Amount',
				'class'     => 'required-entry',
				'required'  => true,
				'name'      => 'title',
				'disabled' => false,
				'after_element_html' => '',
				'tabindex' => 1
		));

		$form->setAction($this->getUrl('*/*/save'));
		$form->setUseContainer(true);
		$this->setForm($form);

		return parent::_prepareForm();
	}
	public function getTabLabel() {
		return __ ( 'Seller  Commission' );
	}

	/**
	 * Prepare title for tab
	 *
	 * @return string	
	 */
	public function getTabTitle() {
		return __ ( 'Seller Commission' );
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 */
	public function canShowTab() {
		return true;
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 */
	public function isHidden() {
		return false;
	}

}
