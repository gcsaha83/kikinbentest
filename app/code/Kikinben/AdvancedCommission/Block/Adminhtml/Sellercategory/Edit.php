<?php
namespace Kikinben\AdvancedCommission\Block\Adminhtml\Sellercategory;

use Magento\Backend\Block\Widget\Context;
use Magento\Framework\Registry;

class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
	protected $_coreRegistry = null;
	public function __construct(
			\Magento\Framework\App\Request\Http $request,
			\Magento\Backend\Block\Widget\Context $context,
			\Magento\Framework\Registry $registry,
			array $data = []
			) {
				$this->_coreRegistry = $registry;
				$this->request = $request;
				parent::__construct($context, $data);
	}
	protected function _construct(){
		$parameter = $this->request->getParams();
		$this->_objectId   = 'id';
		$this->_controller = 'adminhtml_sellercategory';
		$this->_blockGroup = 'Kikinben_AdvancedCommission';
		parent::_construct();
		$this->buttonList->remove('delete');
		$this->buttonList->remove('back');
		$this->buttonList->update('save', 'label', __('Save Info'));
		$this->buttonList->add(
				'saveandcontinue',
				[
						'label' => __('Save and Continue Edit'),
						'class' => 'save',
						'data_attribute' => [
								'mage-init' => [
										'button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form'],
								],
						]
				],
				-100
				);
		$this->addButton(
				'my_back_button',
				[
						'label' => __('Back'),
						'onclick' => 'setLocation(\'' . $this->getUrl('kikinben_advancedcommission/sellercategory/index',['id'=>$parameter['seller_id']]) . '\')',
						'class' => 'back'
				],
				-1
		);
		
	}
	protected function _prepareLayout()
	{
		$this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('page_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'content');
                }
            };
        ";
		return parent::_prepareLayout();
	}
	
}

