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
namespace Kikinben\AdvancedCommission\Block\Adminhtml\Sellerproduct;


use Magento\Backend\Block\Widget\Context;
use Magento\Framework\Registry;

class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
	protected $_coreRegistry = null;
	public function __construct(
			\Magento\Backend\Block\Widget\Context $context,
			\Magento\Framework\Registry $registry,
			array $data = []
			) {
				$this->_coreRegistry = $registry;
				parent::__construct($context, $data);
	}
	protected function _construct(){
		$this->_objectId = 'id';
		$this->_controller = 'adminhtml_sellerproduct';
		$this->_blockGroup = 'Kikinben_AdvancedCommission';
		parent::_construct();
		$this->buttonList->remove('delete');
        $this->buttonList->remove('back');
        $this->addButton(
        'my_back_button',
        [
            'label' => __('Back'),
            'onclick' => 'setLocation(\'' . $this->getUrl('kikinben_advancedcommission/index/index') . '\')',
            'class' => 'back'
        ],
        -1
    );
		$this->buttonList->update('save', 'label', __('Save Info'));		
		
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
