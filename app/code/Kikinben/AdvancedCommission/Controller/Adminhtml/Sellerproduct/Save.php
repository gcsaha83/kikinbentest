<?php
namespace Kikinben\AdvancedCommission\Controller\Adminhtml\Sellerproduct;
class Save extends \Magento\Framework\App\Action\Action
{
	protected $resultPageFactory;
    protected $sellerCommission;
	public function __construct(
			\Magento\Backend\App\Action\Context $context,
            \Magento\Framework\View\Result\PageFactory $resultPageFactory,
            \Kikinben\AdvancedCommission\Model\Commission $commission,
            \Apptha\Marketplace\Model\Seller $sellerCollection,
            array $data = [])
	{
		
        $this->resultPageFactory = $resultPageFactory;
        $this->sellerCommission  = $commission;
        $this->seller            = $sellerCollection;
		return parent::__construct($context);
	}
	public function execute()
	{
		/** @var \Magento\Backend\Model\View\Result\Page $resultPage */
		$resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig ()->getTitle ()->prepend ( __ ( 'Advanced Commission' ) );

        $product_id        = $this->getRequest()->getParam('product_id'); 
        $seller_id         = $this->getRequest()->getParam('seller_id'); 
        $seller_list_id        = $this->getRequest()->getParam('seller_list_id');
        $id                = $this->getRequest()->getParam('kikinben_advancedcommission_commission_id');
        $upriceRangeFrom   = $this->getRequest()->getParam('uprice_range_from'); 
        $commissiontype    = $this->getRequest()->getParam('commission_type');
        echo $uprice_range_to   = $this->getRequest()->getParam('uprice_range_to'); 
        $amount            = $this->getRequest()->getParam('amount');

        if($id){
            $this->sellerCommission->load($id);
        }

        $this->sellerCommission->setProductId($product_id);
        $this->sellerCommission->setUpriceRangeFrom($upriceRangeFrom);
        $this->sellerCommission->setCommissionType($commissiontype);
        $this->sellerCommission->setUpriceRangeTo($uprice_range_to);
        $this->sellerCommission->setAmount($amount);
        $this->sellerCommission->setSellerId($seller_id);
        $this->sellerCommission->setSellerListId($seller_list_id);

        try{

            $this->sellerCommission->save();
            $this->messageManager->addSuccess ( __ ( 'Data has been saved.' ) );
            $this->_redirect ('kikinben_advancedcommission/sellerproduct/index',['id'=>$seller_list_id ]);

        }catch(\Exception $e){
            
            $this->messageManager->addError ( $e->getMessage () );

        }











		return $resultPage;
	}
	protected function _isAllowed()
	{
		return $this->_authorization->isAllowed('ACL RULE HERE');
	}
}

