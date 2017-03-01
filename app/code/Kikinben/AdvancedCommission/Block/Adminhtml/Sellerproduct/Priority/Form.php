<?php 
namespace Kikinben\AdvancedCommission\Block\Adminhtml\Sellerproduct\Priority;

use Kikinben\AdvancedCommission\Model\CommissionFactory AS CommissionCollectionFactory;
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
			
			array $data = []
			) {
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
						'label' => __('Set Commission priority'),
						'title' => __('Set Commission priority'),
						
		
				]
				);
		
		$fieldset->addField('product_id', 'radios', array(
				'label'     => 'priority order',
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
	
	public function getCollection(){
	
		return $this->_commissionCollectionFactory->create()->getCollection();
	
	}
	
}

