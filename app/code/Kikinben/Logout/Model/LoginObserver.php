<?php
namespace Kikinben\Logout\Model;
class LoginObserver implements \Magento\Framework\Event\ObserverInterface
{
    protected $_responseFactory;
    protected $_url;
    protected $_store;

    public function __construct(
        
        \Magento\Framework\App\ResponseFactory $responseFactory,
        \Magento\Framework\UrlInterface $url,
        \Magento\Framework\Controller\ResultFactory $resultFactory,
        \Magento\Store\Model\StoreManagerInterface $store
        
    ) {
        $this->_responseFactory = $responseFactory;
        $this->_url = $url;
        $this->_store = $store;
    }
    public function execute(\Magento\Framework\Event\Observer $observer){
        $loginPage   = $this->_store->getStore()->getBaseUrl();
        $this->_responseFactory->create()->setRedirect($loginPage)->sendResponse();
      exit();
    }
}
