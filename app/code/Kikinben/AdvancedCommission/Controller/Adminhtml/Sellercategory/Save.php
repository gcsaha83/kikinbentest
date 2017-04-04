<?php
namespace Kikinben\AdvancedCommission\Controller\Adminhtml\Sellercategory;
class Save extends \Magento\Framework\App\Action\Action
{
	protected $resultPageFactory;
	protected $_sellerCategoryCommission;
	public function __construct(
			\Magento\Backend\App\Action\Context $context,
			\Magento\Framework\View\Result\PageFactory $resultPageFactory,
			\Kikinben\AdvancedCommission\Model\SellerCategoryCommission $sellerCategoryCommission)
	{				
		$this->resultPageFactory = $resultPageFactory;
		$this->_sellerCategoryCommission = $sellerCategoryCommission;		
		return parent::__construct($context);
	}
	public function execute()
	{
		/** @var \Magento\Backend\Model\View\Result\Page $resultPage */
       	$resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig ()->getTitle ()->prepend ( __ ('Advanced Commission') );

        $id = $this->getRequest()->getParam('kikinben_advancedcommission_sellercategorycommission_id');
        $sellerId = $this->getRequest()->getParam('seller_id');
        $productId = $this->getRequest()->getParam('category_id');
        $kikibinFulfiled = $this->getRequest()->getParam('kikibin_fullfiled'); 
        $commissionType = $this->getRequest()->getParam('percentage');
        $amount = $this->getRequest()->getParam('amount'); 

        if($id){
            $this->_sellerCategoryCommission->load($id);
        }
        $this->_sellerCategoryCommission->setSellerId($sellerId);
        $this->_sellerCategoryCommission->setProductId($productId);
        $this->_sellerCategoryCommission->setKikibinFullfiled($kikibinFulfiled);
        $this->_sellerCategoryCommission->setPercentage($commissionType);
        $this->_sellerCategoryCommission->setAmount($amount);
        try{
            //$this->_sellerCategoryCommission->save();
            $this->messageManager->addSuccess ( __ ( 'Data has been saved.' ) );
            //$this->_redirect ('kikinben_advancedcommission/sellercategory/edit',['id'=>$productId,'seller_id'=>$sellerId]);

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
