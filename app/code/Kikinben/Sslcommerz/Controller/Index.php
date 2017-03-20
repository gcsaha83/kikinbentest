<?php

namespace Kikinben\Sslcommerz\Controller;
abstract class Index extends \Magento\Framework\App\Action\Action
{
	protected $_helper;
	protected $_checkoutSession;
	protected $_storeManager;
	protected $_urlInterface;
	protected $resultPageFactory;

    
    public function __construct(
        \Magento\Framework\App\Action\Context $context, 
    	\Magento\Framework\View\Result\PageFactory $resultPageFactory,
    	
    ) {    	
    	$this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
	
    }
    public function execute()
    {
    	
    	
    	     	   	
    }

}
