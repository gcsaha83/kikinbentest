<?php
namespace Kikinben\AdvancedCommission\Controller\Adminhtml\Sellerproduct;
class Save extends \Magento\Framework\App\Action\Action
{
	protected $resultPageFactory;
	public function __construct(
			\Magento\Backend\App\Action\Context $context,
			\Magento\Framework\View\Result\PageFactory $resultPageFactory)
	{
		
		$this->resultPageFactory = $resultPageFactory;
		return parent::__construct($context);
	}
	public function execute()
	{
		/** @var \Magento\Backend\Model\View\Result\Page $resultPage */
		$resultPage = $this->resultPageFactory->create();
		$resultPage->getConfig ()->getTitle ()->prepend ( __ ( 'Advanced Commission' ) );		
		return $resultPage;
	}
	protected function _isAllowed()
	{
		return $this->_authorization->isAllowed('ACL RULE HERE');
	}
}

