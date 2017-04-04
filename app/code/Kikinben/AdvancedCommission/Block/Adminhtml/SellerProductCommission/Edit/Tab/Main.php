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
namespace Kikinben\AdvancedCommission\Block\Adminhtml\SellerProductCommission\Edit\Tab;

class Main extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
	protected $_systemStore;
	protected $_formFactory;
	protected $_status;
    protected $_sellerProductCommission;
    protected $_sellerCollectionFactory;
	
	public function __construct(
			\Magento\Backend\Block\Template\Context $context,
			\Magento\Framework\Registry $registry,
			\Magento\Framework\Data\FormFactory $formFactory,
			\Magento\Store\Model\System\Store $systemStore,
			\Kikinben\AdvancedCommission\Model\SellerProductCommission $sellerProductCommission,
            \Kikinben\AdvancedCommission\Model\SellerProductCommissionFactory $sellerfactory,
			array $data = []
			) {
				$this->_systemStore = $systemStore;
				$this->_formFactory =  $formFactory;
                $this->_sellerProductCommission = $sellerProductCommission;
                $this->_sellerCollectionFactory = $sellerfactory;
				parent::__construct($context, $registry, $formFactory, $data);
    }

    protected function _construct()
	{		
		parent::_construct();
		$this->setId('page_tabs');
		$this->setDestElementId('edit_form');
		$this->setTitle(__('Product level Commission'));
	}	
	
	protected function _prepareForm() {

        $productFilter = $this->getCollection()
            ->addFieldToFilter('product_id',['eq'=>$this->getData('product_id')])
            ->addFieldToFilter('seller_id',['eq'=>$this->getData('seller_id')])
            ->getData();

	
		$form = $this->_formFactory->create(['data' => ['id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post']]);
		
		$fieldset = $form->addFieldset(
				'base_fieldset',
				['legend' => __('Commission Information'), 'class' => 'fieldset-wide']
				);


        $this->_sellerProductCommission->setData('seller_id',$this->getData('seller_id'));
        $this->_sellerProductCommission->setData('product_id',$this->getData('product_id'));


        if(!empty($productFilter[0])){
            $this->_sellerProductCommission->setData('kikibin_fullfiled', $productFilter[0]['kikibin_fullfiled']);
            $this->_sellerProductCommission->setData('percentage', $productFilter[0]['percentage']);
            $this->_sellerProductCommission->setData('amount', $productFilter[0]['amount']);
            $this->_sellerProductCommission->setData('kikinben_advancedcommission_sellerproductcommission_id', $productFilter[0]['kikinben_advancedcommission_sellerproductcommission_id']);
        }

				
		$fieldset->addField('seller_id','hidden', ['name' => 'seller_id']);
		$fieldset->addField('product_id','hidden', ['name' => 'product_id']);
        $fieldset->addField('kikinben_advancedcommission_sellerproductcommission_id','hidden', ['name' => 'kikinben_advancedcommission_sellerproductcommission_id']);
        
        $fieldset->addField('uprice_range_from', 'text', array(
        		'label'     => 'Price Range From',
        		'class'     => 'required-entry',
        		'required'  => true,
        		'name'      => 'uprice_range_from',
        		'disabled' => false,
        		'after_element_html' => '',
        		'tabindex' => 1
        ));
        
        $fieldset->addField('uprice_range_to', 'text', array(
        		'label'     => 'Price Range To',
        		'class'     => 'required-entry',
        		'required'  => true,
        		'name'      => 'uprice_range_to',
        		'disabled' => false,
        		'after_element_html' => '',
        		'tabindex' => 1
        ));
	
		$fieldset->addField('kikibin_fullfiled', 'select', array(
				'label'     => 'Fullfiled By kikinben',
				'class'     => 'required-entry',
				'required'  => true,
				'name'      => 'kikibin_fullfiled',
				'value'  => '2',
				'values' => array('-1'=>'Please Select..',1 => 'Yes',2 => 'No'),
	
		));
	
		$fieldset->addField('percentage', 'select', array(
				'label'     => 'Commission Type',
				'class'     => 'required-entry',
				'required'  => true,
				'name'      => 'percentage',
				'value'  => '2',
				'values' => array('-1'=>'Please Select..',1 => 'Fixed',2 => 'Percentage'),
	
		));
	
		$fieldset->addField('amount', 'text', array(
				'label'     => 'Amount',
				'class' => 'validate-zero-or-greater',
				'required'  => true,
				'name'      => 'amount'
				
		));

        $form->setAction($this->getUrl('kikinben_advancedcommission/sellerproductcommission/save'));		
        $form->setUseContainer(true);

        $SellerProductCommissionData = $this->_sellerProductCommission->getData();
        $form->setValues ( $SellerProductCommissionData  );

        



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
    public function getCollection(){

        return $this->_sellerCollectionFactory->create()->getCollection();

   }
	
}
