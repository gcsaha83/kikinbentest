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

        $id = $this->getRequest()->getParam('kikinben_advancedcommission_sellerproductcommission_id');
        $sellerId = $this->getRequest()->getParam('seller_id');
        $productId = $this->getRequest()->getParam('product_id');
        $kikibinFulfiled = $this->getRequest()->getParam('kikibin_fullfiled');
        $commissionType = $this->getRequest()->getParam('percentage');
        $amount = $this->getRequest()->getParam('amount');

        if($id){
            $this->_sellerProductCommission->load($id);
        }
        $this->_sellerProductCommission->setSellerId($sellerId);
        $this->_sellerProductCommission->setProductId($productId);
        $this->_sellerProductCommission->setKikibinFullfiled($kikibinFulfiled);
        $this->_sellerProductCommission->setPercentage($commissionType);
        $this->_sellerProductCommission->setAmount($amount);
        try{
            $this->_sellerProductCommission->save();
            $this->messageManager->addSuccess ( __ ( 'Data has been saved.' ) );
            $this->_redirect ('kikinben_advancedcommission/sellerproduct/edit',['id'=>$productId,'seller_id'=>$sellerId]);

        }catch ( \Exception $e ) {
                $this->messageManager->addError ( $e->getMessage () );
        }
        

		return $resultPage;
	}
	protected function _isAllowed()
	{
		return $this->_authorization->isAllowed('ACL RULE HERE');
	}
}
