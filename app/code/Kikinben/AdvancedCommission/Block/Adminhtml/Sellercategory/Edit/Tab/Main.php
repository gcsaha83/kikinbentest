<?php 
namespace Kikinben\AdvancedCommission\Block\Adminhtml\Sellercategory\Edit\Tab;
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
			\Kikinben\AdvancedCommission\Model\SellerCategoryCommission $sellerCategoryCommission,
			\Kikinben\AdvancedCommission\Model\SellerCategoryCommissionFactory $sellerfactory,
			array $data = []
			) {
				$this->_systemStore = $systemStore;
				$this->_formFactory =  $formFactory;
				$this->_sellerProductCommission = $sellerCategoryCommission;
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
		
		$form = $this->_formFactory->create(['data' => ['id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post']]);
		$fieldset = $form->addFieldset(
				'base_fieldset',
				['legend' => __('Commission Information'), 'class' => 'fieldset-wide']
				);
		
		$this->_sellerProductCommission->setData('seller_id',$this->getData('seller_id'));
		$this->_sellerProductCommission->setData('category_id',$this->getData('category_id'));
		
		
		$fieldset->addField('seller_id','hidden', ['name' => 'seller_id']);
		$fieldset->addField('category_id','hidden', ['name' => 'category_id']);
		$fieldset->addField('kikinben_advancedcommission_sellercategorycommission_id','hidden', 
				['name' => 'kikinben_advancedcommission_sellercategorycommission_id']);
		
		$fieldset->addField('product_id', 'radios', array(
				'label'     => 'Enable Price Range Commission Rule',
				'name'      => 'product_id',
				'onclick' => "",
				'onchange' => "",
				'value'  => '2',
				'values' => array(
						array('value'=>'1','label'=>'Yes'),
						array('value'=>'2','label'=>'No'),
				),
				'disabled' => false,
				'readonly' => false,
				'after_element_html' => '',
				'tabindex' => 1
		));
		
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
		$form->setAction($this->getUrl('kikinben_advancedcommission/sellercategory/save'));		
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
