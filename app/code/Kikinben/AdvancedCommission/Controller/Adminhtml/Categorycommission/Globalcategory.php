<?php
namespace Kikinben\AdvancedCommission\Controller\Adminhtml\Categorycommission;
class Globalcategory extends \Magento\Framework\App\Action\Action
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
		$resultPage->getConfig ()->getTitle ()->prepend ( __ ( 'Set Category' ) );
		return $resultPage;
	}
	protected function _isAllowed()
	{
		return $this->_authorization->isAllowed('ACL RULE HERE');
	}
}