<?php
namespace Kikinben\cartPopup\Model;
class Observer implements \Magento\Framework\Event\ObserverInterface
{
	protected $_catalogSession;
	public function __construct(
			\Magento\Catalog\Model\Session $catalogSession
			){
				$this->_catalogSession = $catalogSession;
		
	}
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
    	
    	/*$observer->getEvent()
    	->getBlock()
    	->getLayout()
    	->createBlock('Kikinben\cartPopup\Block')
    	->setTemplate('custommodulefolder/customhtmlfileforadminorderview.phtml')
    	->toHtml();*/
    	//$layout = $observer->getData('layout');
    	//print_r($layout);
    	//$layout->getUpdate()->addHandle('cartpopup_index_index');
    	
    	//$observer->getEvent()
    	//->getBlock()
    	//->getLayout();
    	//->createBlock('Kikinben\cartPopup\Block');
    	//->setTemplate('popup.phtml')
    	//->toHtml();
    	
    	
    	
    }
}
