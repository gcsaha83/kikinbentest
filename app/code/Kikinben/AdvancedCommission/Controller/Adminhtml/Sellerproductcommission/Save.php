<?php
namespace Kikinben\AdvancedCommission\Controller\Adminhtml\Sellerproductcommission;
class Save extends \Magento\Framework\App\Action\Action
{
	protected $resultPageFactory;
	protected $_sellerProductCommission;
	public function __construct(
			\Magento\Backend\App\Action\Context $context,
			\Magento\Framework\View\Result\PageFactory $resultPageFactory,
			\Kikinben\AdvancedCommission\Model\SellerProductCommission $sellerProductCommission)
	{		
		$this->resultPageFactory = $resultPageFactory;
		$this->_sellerProductCommission = $sellerProductCommission;
		return parent::__construct($context);
	}
	public function execute()
	{
		/** @var \Magento\Backend\Model\View\Result\Page $resultPage */
       	$resultPage = $this->resultPageFactory->create();
		$resultPage->getConfig ()->getTitle ()->prepend ( __ ( 'Advanced Commission' ) );
        $data = $this->getRequest()->getPost('post');
        print_r($data);die;

		return $resultPage;
	}
	protected function _isAllowed()
	{
		return $this->_authorization->isAllowed('ACL RULE HERE');
	}
}
