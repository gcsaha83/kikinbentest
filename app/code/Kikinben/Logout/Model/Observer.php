<?php
namespace Kikinben\Logout\Model;
class Observer implements \Magento\Framework\Event\ObserverInterface
{
    protected $_responseFactory;
    protected $_url;

    public function __construct(
        
        \Magento\Framework\App\ResponseFactory $responseFactory,
        \Magento\Framework\UrlInterface $url,
        \Magento\Framework\Controller\ResultFactory $resultFactory,
        \Magento\Store\Model\StoreManagerInterface $store
        
    ) {
        $this->_responseFactory = $responseFactory;
        $this->_url = $url;
    }
    public function execute(\Magento\Framework\Event\Observer $observer){
      $loginPage   = $this->_url->getUrl('customer/account/login');
      $this->_responseFactory->create()->setRedirect($loginPage)->sendResponse();
      exit();
    }
}
